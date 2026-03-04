<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private SunatService $sunatService,
        private PdfService   $pdfService
    ) {}

    public function cobrar(array $data, User $cajero): array
    {
        return DB::transaction(function () use ($data, $cajero) {
            $company = Company::first();

            $student    = Student::findOrFail($data['student_id']);
            $enrollment = isset($data['enrollment_id']) ? Enrollment::find($data['enrollment_id']) : null;

            if ($enrollment && $enrollment->estado !== 'ACTIVO') {
                throw new \Exception('La matrícula no está activa.');
            }

            // Determinar tipo de afectación IGV
            $tipoAfectacion = '20'; // Exonerado por defecto (educación)
            $igvRate        = 0;

            if ($enrollment) {
                $course = $enrollment->course;
                if ($course->afecto_igv) {
                    $tipoAfectacion = $course->tipo_afectacion_igv;
                    $igvRate        = config('sunat.igv', 18) / 100;
                }
            }

            $monto = (float) $data['monto'];

            if ($tipoAfectacion === '10') {
                // Gravado: el monto es precio con IGV incluido
                $subtotal = round($monto / (1 + $igvRate), 2);
                $igv      = round($monto - $subtotal, 2);
            } else {
                // Exonerado/Inafecto: sin IGV
                $subtotal = $monto;
                $igv      = 0.00;
            }
            $total = $subtotal + $igv;

            // Crear Payment
            $payment = Payment::create([
                'company_id'       => $company->id,
                'student_id'       => $student->id,
                'enrollment_id'    => $enrollment?->id,
                'user_id'          => $cajero->id,
                'tipo_pago'        => $data['tipo_pago'],
                'periodo_pago'     => $data['periodo_pago'] ?? null,
                'descripcion_pago' => $data['descripcion_pago'] ?? null,
                'fecha_pago'       => now()->toDateString(),
                'forma_pago'       => $data['forma_pago'],
                'numero_operacion' => $data['numero_operacion'] ?? null,
                'subtotal'         => $subtotal,
                'igv'              => $igv,
                'total'            => $total,
                'estado_pago'      => 'PAGADO',
                'observaciones'    => $data['observaciones'] ?? null,
            ]);

            // Determinar serie según tipo de comprobante
            $tipoComprobante = $data['tipo_comprobante'] ?? '03';
            $serie           = $tipoComprobante === '01'
                ? $company->serie_factura
                : $company->serie_boleta;

            $correlativo = Sale::nextCorrelativo($company->id, $serie);

            // Calcular montos del comprobante
            $mtoGravadas    = $tipoAfectacion === '10' ? $subtotal : 0;
            $mtoExoneradas  = $tipoAfectacion === '20' ? $total    : 0;
            $mtoInafectas   = $tipoAfectacion === '30' ? $total    : 0;
            $mtoIgv         = $igv;
            $valorVenta     = $subtotal;
            $totalImpuestos = $igv;
            $mtoImpVenta    = $total;

            // Crear Sale (comprobante)
            $sale = Sale::create([
                'company_id'          => $company->id,
                'student_id'          => $student->id,
                'payment_id'          => $payment->id,
                'user_id'             => $cajero->id,
                'tipo_comprobante'    => $tipoComprobante,
                'serie'               => $serie,
                'correlativo'         => $correlativo,
                'fecha_emision'       => now()->toDateString(),
                'moneda'              => 'PEN',
                'mto_oper_gravadas'   => $mtoGravadas,
                'mto_oper_exoneradas' => $mtoExoneradas,
                'mto_oper_inafectas'  => $mtoInafectas,
                'mto_igv'             => $mtoIgv,
                'valorventa'          => $valorVenta,
                'total_impuestos'     => $totalImpuestos,
                'mto_imp_venta'       => $mtoImpVenta,
                'estado_sunat'        => 'PENDIENTE',
            ]);

            // Descripción del ítem
            $descripcion = $this->buildDescripcion($data, $enrollment);

            // Crear SaleItem
            SaleItem::create([
                'sale_id'            => $sale->id,
                'orden'              => 1,
                'descripcion'        => $descripcion,
                'unidad_medida'      => 'ZZ',
                'cantidad'           => 1,
                'valor_unitario'     => $valorVenta,
                'precio_unitario'    => $total,
                'mto_valor_venta'    => $valorVenta,
                'mto_base_igv'       => $mtoGravadas,
                'porcentaje_igv'     => $tipoAfectacion === '10' ? config('sunat.igv', 18) : 0,
                'igv'                => $mtoIgv,
                'tipo_afectacion_igv'=> $tipoAfectacion,
                'total'              => $total,
            ]);

            // Recargar relaciones para SUNAT
            $sale->load(['company', 'student', 'items', 'payment']);

            // Enviar a SUNAT
            $sunatResult = $this->sunatService->emitir($sale);

            // Generar PDF
            $this->pdfService->generate($sale);

            return [
                'payment'  => $payment,
                'sale'     => $sale->fresh(),
                'sunat'    => $sunatResult,
            ];
        });
    }

    public function anular(Payment $payment, string $motivo, User $user): void
    {
        if (!$user->hasRole(['superadmin', 'administrador'])) {
            throw new \Exception('No tienes permisos para anular cobros.');
        }

        $payment->update([
            'estado_pago'   => 'ANULADO',
            'observaciones' => $payment->observaciones . "\n[ANULADO] {$motivo}",
        ]);

        if ($payment->sale) {
            $payment->sale->update([
                'estado_sunat'       => 'ANULADO',
                'sunat_descripcion'  => "Anulado por {$user->name}: {$motivo}",
            ]);
        }
    }

    private function buildDescripcion(array $data, ?Enrollment $enrollment): string
    {
        $tipo = match ($data['tipo_pago']) {
            'MATRICULA'  => 'MATRÍCULA',
            'PENSION'    => 'PENSIÓN',
            'MATERIALES' => 'MATERIALES EDUCATIVOS',
            default      => 'SERVICIO EDUCATIVO',
        };

        $desc = $tipo;

        if ($enrollment) {
            $desc .= ' - ' . strtoupper($enrollment->course->nombre);
        }

        if (!empty($data['periodo_pago'])) {
            $desc .= ' - PERIODO ' . $data['periodo_pago'];
        }

        return $desc;
    }
}
