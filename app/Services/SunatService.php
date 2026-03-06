<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SunatResponse;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SunatService
{
    private See $see;

    public function __construct()
    {
        $certPath = base_path(config('sunat.cert_path'));
        $this->see = new See();
        $this->see->setCertificate(file_get_contents($certPath));
        $this->see->setClaveSOL(
            config('sunat.sol_ruc'),
            config('sunat.sol_usuario'),
            config('sunat.sol_password')
        );

        $endpoint = config('sunat.ambiente') === 'beta'
            ? SunatEndpoints::FE_BETA
            : SunatEndpoints::FE_PRODUCCION;

        $this->see->setService($endpoint);
    }

    public function emitir(Sale $sale): array
    {
        try {
            $invoice = $this->buildInvoice($sale);
            $result  = $this->see->send($invoice);

            $xml     = $this->see->getFactory()->getLastXml();
            $xmlName = $invoice->getName() . '.xml';
            $xmlPath = "sunat/xml/{$xmlName}";
            Storage::put($xmlPath, $xml);

            $sale->nombre_xml = $xmlName;
            $sale->ruta_xml   = $xmlPath;

            // BillResult solo tiene isSuccess() y getError()/getCdrResponse()
            // getCode()/getDescription() están en CdrResponse (éxito) o Error (fallo)
            if ($result->isSuccess()) {
                $cdr     = $result->getCdrResponse();
                $cdrZip  = $result->getCdrZip();
                $cdrPath = "sunat/cdr/{$invoice->getName()}-CDR.zip";
                Storage::put($cdrPath, $cdrZip);

                $codigoResp = $cdr ? $cdr->getCode()        : '0';
                $descResp   = $cdr ? $cdr->getDescription() : 'Aceptado';

                $sale->estado_sunat      = 'ACEPTADO';
                $sale->sunat_descripcion = $descResp;
                $sale->hash_cpe          = $cdr ? $cdr->getId() : null;
                $sale->ruta_cdr          = $cdrPath;
            } else {
                $error      = $result->getError();
                $codigoResp = $error ? $error->getCode()    : '9999';
                $descResp   = $error ? $error->getMessage() : 'Error desconocido';

                $sale->estado_sunat      = 'RECHAZADO';
                $sale->sunat_descripcion = $descResp . ' [Código: ' . $codigoResp . ']';
            }

            $sale->save();

            $response = new SunatResponse([
                'sale_id'              => $sale->id,
                'accion'               => 'ENVIO',
                'endpoint_url'         => config('sunat.ambiente') === 'beta'
                    ? SunatEndpoints::FE_BETA
                    : SunatEndpoints::FE_PRODUCCION,
                'xml_enviado'          => substr($xml, 0, 5000),
                'codigo_respuesta'     => $codigoResp,
                'descripcion_respuesta'=> $descResp,
                'exitoso'              => $result->isSuccess(),
            ]);
            $response->save();

            return [
                'success'     => $result->isSuccess(),
                'code'        => $codigoResp,
                'description' => $descResp,
            ];
        } catch (\Exception $e) {
            Log::error('SunatService error: ' . $e->getMessage());
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

    private function buildInvoice(Sale $sale): Invoice
    {
        $company = $sale->company;
        $student = $sale->student;

        $greenterCompany = (new Company())
            ->setRuc($company->ruc)
            ->setRazonSocial($company->razon_social)
            ->setNombreComercial($company->nombre_comercial ?? $company->razon_social)
            ->setAddress(
                (new Address())
                    ->setUbigueo($company->ubigeo)
                    ->setDepartamento($company->departamento)
                    ->setProvincia($company->provincia)
                    ->setDistrito($company->distrito)
                    ->setUrbanizacion($company->urbanizacion ?? '-')
                    ->setDireccion($company->direccion)
                    ->setCodLocal('0000')
            );

        // Determinar tipo de documento del alumno para el comprobante
        $tipoDoc = match ($student->tipo_documento) {
            1  => '1',  // DNI
            6  => '6',  // RUC
            default => '0', // Sin documento
        };

        $numDoc = $tipoDoc === '0' ? '' : ($student->numero_documento ?? '');

        $client = (new Client())
            ->setTipoDoc($tipoDoc)
            ->setNumDoc($numDoc)
            ->setRznSocial($student->nombre_completo);

        $details = [];
        foreach ($sale->items as $item) {
            $detail = (new SaleDetail())
                ->setCodProducto('')
                ->setUnidad($item->unidad_medida ?? 'ZZ')
                ->setCantidad((float) $item->cantidad)
                ->setDescripcion($item->descripcion)
                ->setMtoValorUnitario((float) $item->valor_unitario)
                ->setMtoValorVenta((float) $item->mto_valor_venta)
                ->setMtoPrecioUnitario((float) $item->precio_unitario)
                ->setTipAfeIgv($item->tipo_afectacion_igv)
                ->setPorcentajeIgv((float) $item->porcentaje_igv)
                ->setIgv((float) $item->igv)
                ->setMtoBaseIgv((float) $item->mto_base_igv)
                ->setTotalImpuestos((float) $item->igv);
            $details[] = $detail;
        }

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($this->numberToWords((float) $sale->mto_imp_venta));

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($sale->tipo_comprobante)
            ->setSerie($sale->serie)
            ->setCorrelativo((string) $sale->correlativo)
            ->setFechaEmision(new \DateTime($sale->fecha_emision->format('Y-m-d')))
            ->setTipoMoneda($sale->moneda)
            ->setCompany($greenterCompany)
            ->setClient($client)
            ->setMtoOperGravadas((float) $sale->mto_oper_gravadas)
            ->setMtoOperExoneradas((float) $sale->mto_oper_exoneradas)
            ->setMtoOperInafectas((float) $sale->mto_oper_inafectas)
            ->setMtoIGV((float) $sale->mto_igv)
            ->setValorVenta((float) $sale->valorventa)
            ->setSubTotal((float) $sale->mto_imp_venta)
            ->setTotalImpuestos((float) $sale->total_impuestos)
            ->setMtoImpVenta((float) $sale->mto_imp_venta)
            ->setDetails($details)
            ->setLegends([$legend]);

        return $invoice;
    }

    private function numberToWords(float $amount): string
    {
        $entero  = (int) $amount;
        $decimal = round(($amount - $entero) * 100);
        return "SON " . $this->convertNumber($entero) . " CON " . str_pad($decimal, 2, '0', STR_PAD_LEFT) . "/100 SOLES";
    }

    private function convertNumber(int $n): string
    {
        $ones = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
                 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE',
                 'DIECIOCHO', 'DIECINUEVE'];
        $tens = ['', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $hundreds = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
                     'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        if ($n === 0) return 'CERO';
        if ($n < 0) return 'MENOS ' . $this->convertNumber(-$n);
        if ($n < 20) return $ones[$n];
        if ($n < 100) {
            return $tens[intdiv($n, 10)] . ($n % 10 ? ' Y ' . $ones[$n % 10] : '');
        }
        if ($n === 100) return 'CIEN';
        if ($n < 1000) {
            return $hundreds[intdiv($n, 100)] . ($n % 100 ? ' ' . $this->convertNumber($n % 100) : '');
        }
        if ($n < 2000) {
            return 'MIL' . ($n % 1000 ? ' ' . $this->convertNumber($n % 1000) : '');
        }
        if ($n < 1000000) {
            return $this->convertNumber(intdiv($n, 1000)) . ' MIL' .
                ($n % 1000 ? ' ' . $this->convertNumber($n % 1000) : '');
        }
        return (string) $n;
    }
}
