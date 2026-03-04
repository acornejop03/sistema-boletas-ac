<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\PdfService;
use App\Services\SunatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function __construct(
        private PdfService   $pdfService,
        private SunatService $sunatService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('ver comprobantes');

        $query = Sale::with(['student', 'user', 'payment']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('serie', 'like', "%{$s}%")
                  ->orWhere('correlativo', 'like', "%{$s}%");
            })->orWhereHas('student', function ($q) use ($s) {
                $q->where('nombres', 'like', "%{$s}%")
                  ->orWhere('apellido_paterno', 'like', "%{$s}%")
                  ->orWhere('numero_documento', 'like', "%{$s}%");
            });
        }
        if ($request->filled('tipo')) {
            $query->where('tipo_comprobante', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado_sunat', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_emision', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_emision', '<=', $request->fecha_hasta);
        }

        $sales = $query->latest()->paginate(25)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $this->authorize('ver comprobantes');
        $sale->load(['company', 'student', 'items', 'sunatResponses', 'user', 'payment']);
        return view('sales.show', compact('sale'));
    }

    public function pdf(Sale $sale)
    {
        $this->authorize('descargar pdf');
        return $this->pdfService->stream($sale);
    }

    public function downloadXml(Sale $sale)
    {
        $this->authorize('descargar xml');

        if (!$sale->ruta_xml || !Storage::exists($sale->ruta_xml)) {
            return back()->with('error', 'El XML no está disponible.');
        }

        return Storage::download($sale->ruta_xml, $sale->nombre_xml);
    }

    public function reenviar(Sale $sale)
    {
        $this->authorize('reenviar comprobantes');

        try {
            $sale->load(['company', 'student', 'items', 'payment']);
            $result = $this->sunatService->emitir($sale);

            if ($result['success']) {
                return back()->with('success', 'Comprobante reenviado a SUNAT correctamente.');
            } else {
                return back()->with('error', 'SUNAT rechazó el comprobante: ' . $result['description']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error al reenviar: ' . $e->getMessage());
        }
    }
}
