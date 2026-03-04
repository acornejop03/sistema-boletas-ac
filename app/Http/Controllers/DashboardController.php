<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();

        if ($user->hasRole('cajero')) {
            $cobrosHoy = Payment::where('user_id', $user->id)
                ->where('fecha_pago', $today)
                ->where('estado_pago', 'PAGADO')
                ->get();

            $boletasHoy = Sale::where('user_id', $user->id)
                ->where('fecha_emision', $today)
                ->get();

            return view('dashboard', compact('cobrosHoy', 'boletasHoy'));
        }

        // Admin / Superadmin / Consulta
        $alumnosActivos    = Student::where('activo', true)->count();
        $cobrosHoy         = Payment::where('fecha_pago', $today)->where('estado_pago', 'PAGADO')->count();
        $totalHoy          = Payment::where('fecha_pago', $today)->where('estado_pago', 'PAGADO')->sum('total');
        $ingresosMes       = Payment::whereMonth('fecha_pago', now()->month)
                                     ->whereYear('fecha_pago', now()->year)
                                     ->where('estado_pago', 'PAGADO')
                                     ->sum('total');
        $pendientesSunat   = Sale::where('estado_sunat', 'PENDIENTE')->count();
        $matriculasActivas = Enrollment::where('estado', 'ACTIVO')->count();
        $cursosActivos     = Course::where('activo', true)->count();

        // Datos últimos 7 días para Chart.js
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartData[]   = Payment::where('fecha_pago', $date)
                ->where('estado_pago', 'PAGADO')
                ->sum('total');
        }

        $ultimosCobros = Payment::with(['student', 'sale', 'enrollment.course'])
            ->where('estado_pago', 'PAGADO')
            ->latest()
            ->limit(10)
            ->get();

        $pendientesLista = Sale::with(['student'])
            ->where('estado_sunat', 'PENDIENTE')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'alumnosActivos', 'cobrosHoy', 'totalHoy', 'ingresosMes',
            'pendientesSunat', 'matriculasActivas', 'cursosActivos',
            'chartLabels', 'chartData', 'ultimosCobros', 'pendientesLista'
        ));
    }
}
