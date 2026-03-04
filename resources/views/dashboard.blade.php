@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')

@if(auth()->user()->hasRole('cajero'))
{{-- ═══════════════════ VISTA CAJERO ═══════════════════ --}}
<div class="page-header">
    <div>
        <h5><i class="bi bi-cash-register me-2 text-success"></i>Mi Caja — Hoy</h5>
        <div class="page-subtitle">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</div>
    </div>
    @can('crear cobros')
    <a href="{{ route('payments.create') }}" class="btn btn-success px-4">
        <i class="bi bi-plus-circle me-2"></i>Registrar Cobro
    </a>
    @endcan
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card stat-card stat-green">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="stat-value text-success">S/ {{ number_format($cobrosHoy->where('estado_pago','PAGADO')->sum('total'), 2) }}</div>
                    <div class="stat-label">Total cobrado hoy</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card stat-card stat-blue">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="stat-value">{{ $cobrosHoy->count() }}</div>
                    <div class="stat-label">Cobros realizados</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card stat-card stat-purple">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div>
                    <div class="stat-value">{{ $boletasHoy->count() }}</div>
                    <div class="stat-label">Comprobantes emitidos</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex align-items-center gap-2 fw-semibold">
        <i class="bi bi-list-ul text-primary"></i> Mis cobros de hoy
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>
                <th>Hora</th><th>Alumno</th><th>Concepto</th><th>Forma</th>
                <th class="text-end">Total</th><th>Comprobante</th><th>SUNAT</th><th></th>
            </tr></thead>
            <tbody>
                @forelse($cobrosHoy as $pago)
                <tr>
                    <td class="text-muted">{{ $pago->created_at->format('H:i') }}</td>
                    <td>
                        <div class="fw-semibold">{{ $pago->student->nombre_completo }}</div>
                        <small class="text-muted">{{ $pago->student->codigo }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $pago->tipo_pago }}</span>
                        @if($pago->periodo_pago)<div class="text-muted" style="font-size:0.72rem">{{ $pago->periodo_pago }}</div>@endif
                    </td>
                    <td>{{ $pago->forma_pago_icon }}</td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($pago->total, 2) }}</td>
                    <td>@if($pago->sale)<code>{{ $pago->sale->numero_comprobante }}</code>@endif</td>
                    <td>@if($pago->sale){!! $pago->sale->estado_badge !!}@endif</td>
                    <td>
                        @if($pago->sale)
                        <a href="{{ route('sales.pdf', $pago->sale) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-muted opacity-50"></i>No hay cobros registrados hoy
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@else
{{-- ═══════════════════ VISTA ADMIN/SUPERADMIN/CONSULTA ═══════════════════ --}}
<div class="page-header">
    <div>
        <h5><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h5>
        <div class="page-subtitle">{{ now()->format('l, d/m/Y') }} — Bienvenido, {{ auth()->user()->name }}</div>
    </div>
    @can('crear cobros')
    <a href="{{ route('payments.create') }}" class="btn btn-success px-4">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Cobro
    </a>
    @endcan
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card stat-blue h-100">
            <div class="stat-icon mb-2"><i class="bi bi-person-badge"></i></div>
            <div class="stat-value">{{ $alumnosActivos }}</div>
            <div class="stat-label">Alumnos activos</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card stat-green h-100">
            <div class="stat-icon mb-2"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-value">{{ $cobrosHoy }}</div>
            <div class="stat-label">Cobros hoy</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card stat-green h-100">
            <div class="stat-icon mb-2"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-value" style="font-size:1.2rem">S/ {{ number_format($totalHoy, 0) }}</div>
            <div class="stat-label">Recaudado hoy</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card stat-amber h-100">
            <div class="stat-icon mb-2"><i class="bi bi-calendar-month"></i></div>
            <div class="stat-value" style="font-size:1.2rem">S/ {{ number_format($ingresosMes, 0) }}</div>
            <div class="stat-label">Ingresos del mes</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card {{ $pendientesSunat > 0 ? 'stat-red' : 'stat-cyan' }} h-100">
            <div class="stat-icon mb-2"><i class="bi bi-cloud-{{ $pendientesSunat > 0 ? 'slash' : 'check' }}"></i></div>
            <div class="stat-value {{ $pendientesSunat > 0 ? 'text-danger' : '' }}">{{ $pendientesSunat }}</div>
            <div class="stat-label">Pend. SUNAT</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card stat-purple h-100">
            <div class="stat-icon mb-2"><i class="bi bi-clipboard-check"></i></div>
            <div class="stat-value">{{ $matriculasActivas }}</div>
            <div class="stat-label">Matrículas activas</div>
        </div>
    </div>
</div>

{{-- CHART + PENDIENTES --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7 col-xl-8">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-bar-chart me-2 text-primary"></i>Ingresos — últimos 7 días</span>
                <span class="badge bg-primary-subtle text-primary" style="font-size:0.7rem">S/ {{ number_format(array_sum($chartData), 2) }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartIngresos" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <span class="fw-semibold {{ $pendientesSunat > 0 ? 'text-danger' : '' }}">
                    <i class="bi bi-cloud-slash me-2"></i>Pendientes SUNAT
                </span>
                @if($pendientesSunat > 0)
                <span class="badge bg-danger">{{ $pendientesSunat }}</span>
                @endif
            </div>
            <div class="list-group list-group-flush" style="max-height:260px;overflow-y:auto">
                @forelse($pendientesLista as $sale)
                <div class="list-group-item px-3 py-2">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div style="min-width:0">
                            <div class="fw-semibold small text-truncate">{{ $sale->student->nombre_completo }}</div>
                            <code style="font-size:0.72rem">{{ $sale->numero_comprobante }}</code>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-bold text-success small">S/ {{ number_format($sale->mto_imp_venta,2) }}</div>
                            <form method="POST" action="{{ route('sales.reenviar', $sale) }}" class="d-inline" onsubmit="return confirm('¿Reenviar a SUNAT?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning" style="font-size:0.7rem;padding:2px 8px">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="list-group-item text-center py-4 text-muted">
                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                    <small>Todo sincronizado con SUNAT</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ÚLTIMOS COBROS --}}
<div class="card">
    <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Últimos 10 cobros</span>
        <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>
                <th>Fecha</th><th>Alumno</th><th>Curso</th><th>Tipo</th>
                <th class="text-end">Total</th><th>Comprobante</th><th>SUNAT</th>
            </tr></thead>
            <tbody>
                @foreach($ultimosCobros as $pago)
                <tr>
                    <td class="text-muted">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('students.show', $pago->student_id) }}" class="fw-semibold text-decoration-none text-dark">
                            {{ $pago->student->nombre_completo }}
                        </a>
                    </td>
                    <td class="text-muted small">{{ $pago->enrollment?->course?->nombre ?? '—' }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $pago->tipo_pago }}</span></td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($pago->total, 2) }}</td>
                    <td>
                        @if($pago->sale)
                        <a href="{{ route('sales.show', $pago->sale) }}" class="text-decoration-none">
                            <code>{{ $pago->sale->numero_comprobante }}</code>
                        </a>
                        @endif
                    </td>
                    <td>@if($pago->sale){!! $pago->sale->estado_badge !!}@endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@section('scripts')
@if(!auth()->user()->hasRole('cajero'))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartIngresos');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Ingresos (S/)',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(37,99,235,0.15)',
            borderColor: 'rgba(37,99,235,0.8)',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
            hoverBackgroundColor: 'rgba(37,99,235,0.35)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' S/ ' + ctx.raw.toLocaleString('es-PE', {minimumFractionDigits:2})
                }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' },
                 ticks: { callback: v => 'S/ ' + v.toLocaleString(), font: {size:11} } },
            x: { grid: { display: false }, ticks: { font: {size:11} } }
        }
    }
});
</script>
@endif
@endsection
