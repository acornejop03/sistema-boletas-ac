<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SunatResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Integración con NubeFact API REST
 * Documentación: https://www.nubefact.com/integracion
 *
 * Pasos para activar:
 * 1. Crear cuenta en https://app.nubefact.com
 * 2. Ir a Configuración → Token API → copiar token
 * 3. Agregar en .env:
 *    SUNAT_DRIVER=nubefact
 *    NUBEFACT_TOKEN=tu_token_aqui
 *    NUBEFACT_RUC=20XXXXXXXXX
 */
class NubefactService
{
    private string $apiUrl;
    private string $token;
    private string $ruc;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('sunat.nubefact_api_url'), '/');
        $this->token  = config('sunat.nubefact_token');
        $this->ruc    = config('sunat.nubefact_ruc');
    }

    public function emitir(Sale $sale): array
    {
        try {
            $payload = $this->buildPayload($sale);

            $response = Http::withToken($this->token)
                ->timeout(30)
                ->post("{$this->apiUrl}/{$this->ruc}/documentos", $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Guardar XML si lo devuelve NubeFact
                if (!empty($data['enlace_del_xml'])) {
                    $xmlName = $sale->serie . '-' . $sale->correlativo . '.xml';
                    $sale->nombre_xml = $xmlName;
                    $sale->ruta_xml   = "sunat/xml/{$xmlName}";
                }

                // Guardar PDF si lo devuelve
                if (!empty($data['enlace_del_pdf'])) {
                    $sale->ruta_pdf = $data['enlace_del_pdf'];
                }

                $sale->estado_sunat      = 'ACEPTADO';
                $sale->sunat_descripcion = $data['sunat_description'] ?? 'Aceptado por NubeFact';
                $sale->hash_cpe          = $data['sunat_hash'] ?? null;
                $sale->save();

                // Registrar respuesta
                SunatResponse::create([
                    'sale_id'               => $sale->id,
                    'accion'                => 'ENVIO',
                    'endpoint_url'          => $this->apiUrl,
                    'xml_enviado'           => json_encode($payload),
                    'codigo_respuesta'      => $data['sunat_responsecode'] ?? '0',
                    'descripcion_respuesta' => $data['sunat_description'] ?? 'OK',
                    'exitoso'               => true,
                ]);

                return [
                    'success'     => true,
                    'code'        => $data['sunat_responsecode'] ?? '0',
                    'description' => $data['sunat_description'] ?? 'Aceptado',
                    'pdf_url'     => $data['enlace_del_pdf'] ?? null,
                    'xml_url'     => $data['enlace_del_xml'] ?? null,
                ];
            }

            // Error HTTP
            $errorBody = $response->json();
            $errorMsg  = $errorBody['errors'] ?? $response->body();
            if (is_array($errorMsg)) {
                $errorMsg = implode(', ', array_map('strval', array_values($errorMsg)));
            }

            $sale->estado_sunat      = 'RECHAZADO';
            $sale->sunat_descripcion = (string) $errorMsg;
            $sale->save();

            SunatResponse::create([
                'sale_id'               => $sale->id,
                'accion'                => 'ENVIO',
                'endpoint_url'          => $this->apiUrl,
                'xml_enviado'           => json_encode($payload),
                'codigo_respuesta'      => (string) $response->status(),
                'descripcion_respuesta' => (string) $errorMsg,
                'exitoso'               => false,
            ]);

            return [
                'success'     => false,
                'code'        => $response->status(),
                'description' => (string) $errorMsg,
            ];

        } catch (\Exception $e) {
            Log::error('NubefactService error: ' . $e->getMessage());

            $sale->estado_sunat      = 'PENDIENTE';
            $sale->sunat_descripcion = $e->getMessage();
            $sale->save();

            return [
                'success'     => false,
                'code'        => 0,
                'description' => $e->getMessage(),
            ];
        }
    }

    private function buildPayload(Sale $sale): array
    {
        $student = $sale->student;
        $items   = $sale->items;

        // Tipo de documento del cliente
        $tipoDoc = match ((int) $student->tipo_documento) {
            1       => 1,  // DNI
            6       => 6,  // RUC
            default => 0,  // Sin documento
        };

        // Construir items
        $detalles = [];
        foreach ($items as $item) {
            $detalles[] = [
                'unidad_de_medida'    => $item->unidad_medida ?? 'ZZ',
                'codigo'              => '',
                'descripcion'         => $item->descripcion,
                'cantidad'            => (float) $item->cantidad,
                'valor_unitario'      => (float) $item->valor_unitario,
                'precio_unitario'     => (float) $item->precio_unitario,
                'descuento'           => '0',
                'subtotal'            => (float) $item->mto_valor_venta,
                'tipo_de_igv'         => (int) $item->tipo_afectacion_igv,
                'igv'                 => (float) $item->igv,
                'total'               => (float) $item->total,
                'anticipo_regularizacion' => false,
                'anticipo_documento_serie'    => '',
                'anticipo_documento_numero'   => '',
            ];
        }

        // Tipo comprobante: 01=Factura, 03=Boleta
        $tipoComprobante = $sale->tipo_comprobante === '01' ? 'FACTURA' : 'BOLETA';

        return [
            'operacion'              => 'generar_comprobante',
            'tipo_de_comprobante'    => (int) $sale->tipo_comprobante,
            'serie'                  => $sale->serie,
            'numero'                 => (int) $sale->correlativo,
            'sunat_transaction'      => 1,
            'cliente_tipo_de_documento' => $tipoDoc,
            'cliente_numero_de_documento' => $tipoDoc === 0 ? '' : ($student->numero_documento ?? ''),
            'cliente_denominacion'   => $student->nombre_completo,
            'cliente_direccion'      => $student->direccion ?? '',
            'cliente_email'          => $student->email ?? '',
            'cliente_email_1'        => '',
            'cliente_email_2'        => '',
            'fecha_de_emision'       => $sale->fecha_emision->format('d-m-Y'),
            'fecha_de_vencimiento'   => '',
            'moneda'                 => 1,  // 1=PEN, 2=USD
            'tipo_de_cambio'         => '',
            'porcentaje_de_igv'      => config('sunat.igv', 18),
            'descuento_global'       => 0,
            'total_descuento'        => 0,
            'total_anticipo'         => 0,
            'total_gravada'          => (float) $sale->mto_oper_gravadas,
            'total_inafecta'         => (float) $sale->mto_oper_inafectas,
            'total_exonerada'        => (float) $sale->mto_oper_exoneradas,
            'total_igv'              => (float) $sale->mto_igv,
            'total_gratuita'         => 0,
            'total_otros_cargos'     => 0,
            'total'                  => (float) $sale->mto_imp_venta,
            'percepcion_tipo'        => '',
            'percepcion_base_imponible' => 0,
            'total_percepcion'       => 0,
            'total_incluido_percepcion' => 0,
            'detraccion'             => false,
            'observaciones'          => '',
            'documento_que_se_modifica_tipo' => '',
            'documento_que_se_modifica_serie' => '',
            'documento_que_se_modifica_numero' => '',
            'tipo_de_nota_de_credito' => '',
            'tipo_de_nota_de_debito'  => '',
            'enviar_automaticamente_a_la_sunat' => true,
            'enviar_automaticamente_al_cliente' => false,
            'codigo_unico'           => '',
            'condiciones_de_pago'    => '',
            'medio_de_pago'          => '',
            'placa_vehiculo'         => '',
            'orden_compra_servicio'  => '',
            'tabla_personalizada_codigo' => '',
            'formato_de_pdf'         => 'A4',
            'items'                  => $detalles,
        ];
    }
}
