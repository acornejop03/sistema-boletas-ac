<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function ingresos(Request $request)
    {
        $this->authorize('ver reportes basicos');

        $year  = $request->get('year', now()->year);
        $month = $request->get('month');

        $query = Payment::where('estado_pago', 'PAGADO')
            ->whereYear('fecha_pago', $year);

        if ($month) {
            $query->whereMonth('fecha_pago', $month);
        }

        // Usar PHP para agrupar (compatible SQLite y MySQL)
        $pagos = (clone $query)->get();

        $ingresos = $pagos->groupBy(function ($p) {
            return $p->fecha_pago->format('Y-m') . '|' . $p->tipo_pago;
        })->map(function ($items, $key) {
            [$ym, $tipo] = explode('|', $key);
            return [
                'year'     => substr($ym, 0, 4),
                'month'    => (int) substr($ym, 5, 2),
                'tipo_pago'=> $tipo,
                'cantidad' => $items->count(),
                'total'    => $items->sum('total'),
            ];
        })->values()->sortBy(['year', 'month']);

        $totalGeneral = (clone $query)->sum('total');

        return view('reports.ingresos', compact('ingresos', 'totalGeneral', 'year', 'month'));
    }

    public function porCurso(Request $request)
    {
        $this->authorize('ver reportes basicos');

        $year = $request->get('year', now()->year);

        $data = Payment::with('enrollment.course')
            ->where('estado_pago', 'PAGADO')
            ->whereYear('fecha_pago', $year)
            ->whereNotNull('enrollment_id')
            ->get()
            ->groupBy(fn ($p) => $p->enrollment?->course?->nombre ?? 'Sin curso')
            ->map(fn ($items) => [
                'cantidad' => $items->count(),
                'total'    => $items->sum('total'),
            ]);

        return view('reports.por-curso', compact('data', 'year'));
    }

    public function porCajero(Request $request)
    {
        $this->authorize('ver reportes completos');

        $fecha_desde = $request->get('fecha_desde', now()->startOfMonth()->toDateString());
        $fecha_hasta = $request->get('fecha_hasta', now()->toDateString());

        $data = Payment::with('user')
            ->where('estado_pago', 'PAGADO')
            ->whereBetween('fecha_pago', [$fecha_desde, $fecha_hasta])
            ->get()
            ->groupBy('user_id')
            ->map(function ($items) {
                return [
                    'usuario'  => $items->first()->user?->name ?? 'Desconocido',
                    'cantidad' => $items->count(),
                    'total'    => $items->sum('total'),
                ];
            });

        return view('reports.por-cajero', compact('data', 'fecha_desde', 'fecha_hasta'));
    }

    public function pendientesSunat(Request $request)
    {
        $this->authorize('ver reportes basicos');

        $sales = Sale::with(['student', 'user'])
            ->where('estado_sunat', 'PENDIENTE')
            ->latest()
            ->paginate(25);

        return view('reports.pendientes-sunat', compact('sales'));
    }

    public function morosos(Request $request)
    {
        $this->authorize('ver reportes basicos');

        $periodo = $request->get('periodo', now()->format('Y-m'));

        $morosos = Enrollment::where('estado', 'ACTIVO')
            ->with(['student', 'course'])
            ->whereDoesntHave('payments', function ($q) use ($periodo) {
                $q->where('tipo_pago', 'PENSION')
                  ->where('periodo_pago', $periodo)
                  ->where('estado_pago', 'PAGADO');
            })
            ->get();

        return view('reports.morosos', compact('morosos', 'periodo'));
    }
}
