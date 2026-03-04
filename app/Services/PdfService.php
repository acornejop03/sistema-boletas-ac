<?php

namespace App\Services;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generate(Sale $sale): string
    {
        $sale->load(['company', 'student', 'items', 'payment']);

        $pdf = Pdf::loadView('boleta.academia', ['sale' => $sale]);
        $pdf->setPaper([0, 0, 226.77, 595.28]); // ~80mm ancho

        $pdfPath = "sunat/pdf/{$sale->serie}-{$sale->correlativo}.pdf";
        Storage::put($pdfPath, $pdf->output());

        $sale->ruta_pdf = $pdfPath;
        $sale->save();

        return $pdfPath;
    }

    public function download(Sale $sale)
    {
        $sale->load(['company', 'student', 'items', 'payment']);
        $pdf = Pdf::loadView('boleta.academia', ['sale' => $sale]);
        $pdf->setPaper([0, 0, 226.77, 595.28]);
        return $pdf->download($sale->numero_comprobante . '.pdf');
    }

    public function stream(Sale $sale)
    {
        $sale->load(['company', 'student', 'items', 'payment']);
        $pdf = Pdf::loadView('boleta.academia', ['sale' => $sale]);
        $pdf->setPaper([0, 0, 226.77, 595.28]);
        return $pdf->stream($sale->numero_comprobante . '.pdf');
    }
}
