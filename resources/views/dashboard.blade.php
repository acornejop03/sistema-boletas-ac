@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('styles')
<style>
/* ══ WELCOME BANNER ══ */
.welcome-banner {
    background: linear-gradient(135deg, #0f2a56 0%, #1a3a6b 45%, #2563eb 100%);
    border-radius: 20px;
    padding: 1.6rem 2rem;
    margin-bottom: 1.5rem;
    position: relative; overflow: hidden;
    animation: bannerIn 0.6s ease both;
}
.welcome-banner::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}
.welcome-banner::after {
    content: '';
    position: absolute; top: -80px; right: -60px;
    width: 280px; height: 280px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 65%);
    pointer-events: none;
}
.wb-inner { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.wb-greeting { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.5); font-weight: 600; margin-bottom: 4px; }
.wb-name     { font-size: 1.35rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; line-height: 1.2; }
.wb-date     { font-size: 0.78rem; color: rgba(255,255,255,0.5); margin-top: 4px; }
.wb-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px; padding: 5px 14px;
    font-size: 0.73rem; font-weight: 600; color: rgba(255,255,255,0.8);
    margin-top: 8px;
}
.wb-badge i { color: #fbbf24; }
.wb-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.wb-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 12px;
    font-size: 0.82rem; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all 0.2s;
}
.wb-btn-primary {
    background: #fff; color: #1a3a6b;
    box-shadow: 0 2px 12px rgba(0,0,0,0.15);
}
.wb-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); color: #1a3a6b; }
.wb-btn-ghost {
    background: rgba(255,255,255,0.12); color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
}
.wb-btn-ghost:hover { background: rgba(255,255,255,0.22); color: #fff; transform: translateY(-2px); }

@keyframes bannerIn { from{opacity:0;transform:translateY(-16px)} to{opacity:1;transform:translateY(0)} }

/* ══ KPI CARDS ══ */
.kpi-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 4px 16px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.05);
    position: relative; overflow: hidden;
    transition: transform 0.2s cubic-bezier(.34,1.56,.64,1), box-shadow 0.2s;
    cursor: default;
    animation: kpiIn 0.5s cubic-bezier(.34,1.56,.64,1) both;
}
.kpi-card::after {
    content: '';
    position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
    transition: left 0.6s ease; pointer-events: none;
}
.kpi-card:hover { transform: translateY(-5px); }
.kpi-card:hover::after { left: 150%; }

.kpi-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem; }
.kpi-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
    transition: transform 0.2s cubic-bezier(.34,1.56,.64,1);
}
.kpi-card:hover .kpi-icon { transform: scale(1.12) rotate(-6deg); }
.kpi-trend {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 0.7rem; font-weight: 700; padding: 3px 7px;
    border-radius: 20px;
}
.trend-up   { background: #f0fdf4; color: #16a34a; }
.trend-down { background: #fef2f2; color: #dc2626; }
.trend-flat { background: #f8fafc; color: #64748b; }

.kpi-value { font-size: 1.65rem; font-weight: 800; line-height: 1.1; letter-spacing: -0.03em; }
.kpi-label { font-size: 0.72rem; color: #94a3b8; font-weight: 500; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }
.kpi-sub   { font-size: 0.72rem; color: #64748b; margin-top: 8px; padding-top: 8px; border-top: 1px solid #f1f5f9; }

/* Color variants */
.kpi-blue   { border-top: 3px solid #3b82f6; } .kpi-blue   .kpi-icon { background:#eff6ff; color:#2563eb; } .kpi-blue   .kpi-value { color:#1d4ed8; }
.kpi-blue:hover   { box-shadow: 0 12px 32px rgba(37,99,235,0.15); }
.kpi-green  { border-top: 3px solid #22c55e; } .kpi-green  .kpi-icon { background:#f0fdf4; color:#16a34a; } .kpi-green  .kpi-value { color:#15803d; }
.kpi-green:hover  { box-shadow: 0 12px 32px rgba(34,197,94,0.15); }
.kpi-amber  { border-top: 3px solid #f59e0b; } .kpi-amber  .kpi-icon { background:#fffbeb; color:#d97706; } .kpi-amber  .kpi-value { color:#b45309; }
.kpi-amber:hover  { box-shadow: 0 12px 32px rgba(245,158,11,0.15); }
.kpi-red    { border-top: 3px solid #ef4444; } .kpi-red    .kpi-icon { background:#fef2f2; color:#dc2626; } .kpi-red    .kpi-value { color:#b91c1c; }
.kpi-red:hover    { box-shadow: 0 12px 32px rgba(239,68,68,0.15); }
.kpi-purple { border-top: 3px solid #8b5cf6; } .kpi-purple .kpi-icon { background:#f5f3ff; color:#7c3aed; } .kpi-purple .kpi-value { color:#6d28d9; }
.kpi-purple:hover { box-shadow: 0 12px 32px rgba(139,92,246,0.15); }
.kpi-cyan   { border-top: 3px solid #06b6d4; } .kpi-cyan   .kpi-icon { background:#ecfeff; color:#0891b2; } .kpi-cyan   .kpi-value { color:#0e7490; }
.kpi-cyan:hover   { box-shadow: 0 12px 32px rgba(6,182,212,0.15); }

@keyframes kpiIn {
    from { opacity:0; transform:translateY(20px) scale(0.95); }
    to   { opacity:1; transform:translateY(0)    scale(1); }
}
.kd0{animation-delay:.05s} .kd1{animation-delay:.1s} .kd2{animation-delay:.15s}
.kd3{animation-delay:.2s}  .kd4{animation-delay:.25s} .kd5{animation-delay:.3s}

/* ══ PANEL CARDS ══ */
.panel-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 4px 16px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
    animation: panelIn 0.5s ease both;
}
@keyframes panelIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
.pd0{animation-delay:.35s} .pd1{animation-delay:.42s} .pd2{animation-delay:.49s}

.panel-hdr {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, #fafbff, #fff);
}
.panel-hdr-left { display: flex; align-items: center; gap: 10px; }
.panel-hdr-icon {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
}
.phi-blue   { background:#eff6ff; color:#2563eb; }
.phi-green  { background:#f0fdf4; color:#16a34a; }
.phi-red    { background:#fef2f2; color:#dc2626; }
.phi-amber  { background:#fffbeb; color:#d97706; }
.phi-purple { background:#f5f3ff; color:#7c3aed; }
.panel-hdr-title { font-weight: 700; font-size: 0.86rem; color: #0f172a; }
.panel-hdr-sub   { font-size: 0.71rem; color: #94a3b8; margin-top: 1px; }

/* Live badge */
.live-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    color: #15803d; border: 1px solid #86efac;
    border-radius: 20px; padding: 3px 9px;
    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px;
}
.live-dot {
    width: 6px; height: 6px; border-radius: 50%; background: #16a34a;
    animation: livePulse 1.5s ease-in-out infinite;
}
@keyframes livePulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

/* SUNAT status */
.sunat-ok    { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 10px; }
.sunat-alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 10px; }
.sunat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0; border-bottom: 1px solid #f8fafc;
    gap: 10px;
}
.sunat-item:last-child { border-bottom: none; padding-bottom: 0; }
.sunat-pulse {
    width: 8px; height: 8px; border-radius: 50%; background: #ef4444;
    animation: sPulse 1.5s ease-in-out infinite;
    box-shadow: 0 0 0 0 rgba(239,68,68,0.4); flex-shrink: 0;
}
@keyframes sPulse { 0%{box-shadow:0 0 0 0 rgba(239,68,68,0.4)} 70%{box-shadow:0 0 0 6px rgba(239,68,68,0)} 100%{box-shadow:0 0 0 0 rgba(239,68,68,0)} }

/* ══ TABLE ROWS ══ */
.panel-card .table tbody tr { transition: background 0.13s; }
.panel-card .table tbody tr:hover { background: #f8f9ff; }

/* ══ CAJERO BANNER ══ */
.caja-banner {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #059669 100%);
    border-radius: 20px; padding: 1.4rem 1.75rem;
    margin-bottom: 1.5rem; position: relative; overflow: hidden;
    animation: bannerIn 0.6s ease both;
}
.caja-banner::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 32px 32px;
}

/* Link btn */
.lnk-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.78rem; font-weight: 600; text-decoration: none;
    color: #2563eb; transition: color 0.15s;
}
.lnk-btn:hover { color: #1d4ed8; }

@media (max-width: 575.98px) {
    .kpi-value { font-size: 1.35rem; }
    .welcome-banner { padding: 1.25rem; }
    .wb-name { font-size: 1.1rem; }
}
</style>
@endsection

@section('content')

@if(auth()->user()->hasRole('cajero'))
{{-- ═══════════════════ VISTA CAJERO ═══════════════════ --}}

<div class="caja-banner">
    <div class="wb-inner" style="position:relative;z-index:1">
        <div>
            <div class="wb-greeting">Mi Caja</div>
            <div class="wb-name">Hola, {{ auth()->user()->name }}</div>
            <div class="wb-date">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</div>
            <div class="wb-badge mt-2">
                <div class="live-dot"></div>
                EN VIVO
            </div>
        </div>
        @can('crear cobros')
        <a href="{{ route('payments.create') }}" class="wb-btn wb-btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Registrar Cobro
        </a>
        @endcan
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="kpi-card kpi-green kd0">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div class="kpi-value" data-count="{{ $cobrosHoy->where('estado_pago','PAGADO')->sum('total') }}" data-prefix="S/ " data-decimals="2">
                S/ {{ number_format($cobrosHoy->where('estado_pago','PAGADO')->sum('total'), 2) }}
            </div>
            <div class="kpi-label">Total cobrado hoy</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="kpi-card kpi-blue kd1">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-receipt"></i></div>
            </div>
            <div class="kpi-value" data-count="{{ $cobrosHoy->count() }}">{{ $cobrosHoy->count() }}</div>
            <div class="kpi-label">Cobros realizados</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="kpi-card kpi-purple kd2">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-file-earmark-text"></i></div>
            </div>
            <div class="kpi-value" data-count="{{ $boletasHoy->count() }}">{{ $boletasHoy->count() }}</div>
            <div class="kpi-label">Comprobantes emitidos</div>
        </div>
    </div>
</div>

<div class="panel-card pd0">
    <div class="panel-hdr">
        <div class="panel-hdr-left">
            <div class="panel-hdr-icon phi-blue"><i class="bi bi-list-ul"></i></div>
            <div>
                <div class="panel-hdr-title">Mis cobros de hoy</div>
                <div class="panel-hdr-sub">Actualizados en tiempo real</div>
            </div>
        </div>
        <div class="live-badge"><div class="live-dot"></div> EN VIVO</div>
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
                    <td>
                        <span class="badge bg-light text-dark border" style="font-size:0.75rem;font-weight:600">{{ $pago->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $pago->student->nombre_completo }}</div>
                        <small class="text-muted">{{ $pago->student->codigo }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $pago->tipo_pago }}</span>
                        @if($pago->periodo_pago)<div class="text-muted" style="font-size:0.72rem">{{ $pago->periodo_pago }}</div>@endif
                    </td>
                    <td class="text-muted">{{ $pago->forma_pago_icon }}</td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($pago->total, 2) }}</td>
                    <td>@if($pago->sale)<code style="font-size:0.78rem">{{ $pago->sale->numero_comprobante }}</code>@endif</td>
                    <td>@if($pago->sale){!! $pago->sale->estado_badge !!}@endif</td>
                    <td>
                        @if($pago->sale)
                        <a href="{{ route('sales.pdf', $pago->sale) }}" class="btn-act red" target="_blank" title="Ver PDF">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:0.5rem"></i>
                    <span class="text-muted">No hay cobros registrados hoy</span>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@else
{{-- ═══════════════════ VISTA ADMIN / SUPERADMIN / CONSULTA ═══════════════════ --}}

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="wb-inner">
        <div>
            <div class="wb-greeting">Panel de Control</div>
            <div class="wb-name">Bienvenido, {{ explode(' ', auth()->user()->name)[0] }} 👋</div>
            <div class="wb-date">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</div>
            <div class="wb-badge">
                <i class="bi bi-building-fill"></i>
                {{ \App\Models\Company::first()->nombre_comercial ?? 'Academia AC' }}
            </div>
        </div>
        <div class="wb-actions">
            @can('crear cobros')
            <a href="{{ route('payments.create') }}" class="wb-btn wb-btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Nuevo Cobro
            </a>
            @endcan
            <a href="{{ route('payments.index') }}" class="wb-btn wb-btn-ghost">
                <i class="bi bi-cash-coin"></i> Cobros
            </a>
            <a href="{{ route('sales.index') }}" class="wb-btn wb-btn-ghost">
                <i class="bi bi-receipt"></i> Comprobantes
            </a>
        </div>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="kpi-card kpi-blue kd0 h-100">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-person-badge"></i></div>
                <span class="kpi-trend trend-flat"><i class="bi bi-dash"></i></span>
            </div>
            <div class="kpi-value" data-count="{{ $alumnosActivos }}">{{ $alumnosActivos }}</div>
            <div class="kpi-label">Alumnos activos</div>
            <div class="kpi-sub"><i class="bi bi-people me-1"></i>Matrículas activas: {{ $matriculasActivas }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="kpi-card kpi-green kd1 h-100">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
                <span class="kpi-trend trend-up"><i class="bi bi-arrow-up"></i> Hoy</span>
            </div>
            <div class="kpi-value" data-count="{{ $cobrosHoy }}">{{ $cobrosHoy }}</div>
            <div class="kpi-label">Cobros hoy</div>
            <div class="kpi-sub"><i class="bi bi-clock me-1"></i>{{ now()->format('d/m/Y') }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="kpi-card kpi-green kd2 h-100">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                <span class="kpi-trend trend-up"><i class="bi bi-arrow-up"></i></span>
            </div>
            <div class="kpi-value" data-count="{{ $totalHoy }}" data-prefix="S/ " data-decimals="0" style="font-size:1.3rem">
                S/ {{ number_format($totalHoy, 0) }}
            </div>
            <div class="kpi-label">Recaudado hoy</div>
            <div class="kpi-sub"><i class="bi bi-graph-up me-1"></i>Soles cobrados</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="kpi-card kpi-amber kd3 h-100">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-calendar-month"></i></div>
                <span class="kpi-trend trend-up"><i class="bi bi-arrow-up"></i> Mes</span>
            </div>
            <div class="kpi-value" data-count="{{ $ingresosMes }}" data-prefix="S/ " data-decimals="0" style="font-size:1.3rem">
                S/ {{ number_format($ingresosMes, 0) }}
            </div>
            <div class="kpi-label">Ingresos del mes</div>
            <div class="kpi-sub"><i class="bi bi-calendar me-1"></i>{{ now()->isoFormat('MMMM YYYY') }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="kpi-card {{ $pendientesSunat > 0 ? 'kpi-red' : 'kpi-cyan' }} kd4 h-100">
            <div class="kpi-top">
                <div class="kpi-icon">
                    <i class="bi bi-cloud-{{ $pendientesSunat > 0 ? 'slash' : 'check' }}"></i>
                </div>
                @if($pendientesSunat > 0)
                <span class="kpi-trend trend-down"><i class="bi bi-exclamation"></i> Alerta</span>
                @else
                <span class="kpi-trend trend-up"><i class="bi bi-check"></i> OK</span>
                @endif
            </div>
            <div class="kpi-value" data-count="{{ $pendientesSunat }}">{{ $pendientesSunat }}</div>
            <div class="kpi-label">Pend. SUNAT</div>
            <div class="kpi-sub">
                @if($pendientesSunat > 0)
                <span class="sunat-pulse d-inline-block me-1"></span>Requieren reenvío
                @else
                <i class="bi bi-check-circle me-1 text-success"></i>Todo sincronizado
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="kpi-card kpi-purple kd5 h-100">
            <div class="kpi-top">
                <div class="kpi-icon"><i class="bi bi-clipboard-check"></i></div>
                <span class="kpi-trend trend-flat"><i class="bi bi-dash"></i></span>
            </div>
            <div class="kpi-value" data-count="{{ $matriculasActivas }}">{{ $matriculasActivas }}</div>
            <div class="kpi-label">Matrículas activas</div>
            <div class="kpi-sub"><i class="bi bi-book me-1"></i>Inscritos en curso</div>
        </div>
    </div>
</div>

{{-- CHART + SUNAT --}}
<div class="row g-3 mb-4">
    {{-- Chart --}}
    <div class="col-12 col-lg-7">
        <div class="panel-card pd0 h-100">
            <div class="panel-hdr">
                <div class="panel-hdr-left">
                    <div class="panel-hdr-icon phi-blue"><i class="bi bi-bar-chart-fill"></i></div>
                    <div>
                        <div class="panel-hdr-title">Ingresos — Últimos 7 días</div>
                        <div class="panel-hdr-sub">Cobros procesados y aceptados</div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold" style="font-size:0.95rem;color:#1d4ed8">S/ {{ number_format(array_sum($chartData), 2) }}</div>
                    <div style="font-size:0.68rem;color:#94a3b8">Total 7 días</div>
                </div>
            </div>
            <div style="padding:1rem 1.25rem 1.25rem">
                <canvas id="chartIngresos" style="max-height:210px"></canvas>
            </div>
        </div>
    </div>

    {{-- SUNAT Pending --}}
    <div class="col-12 col-lg-5">
        <div class="panel-card pd1 h-100">
            <div class="panel-hdr">
                <div class="panel-hdr-left">
                    <div class="panel-hdr-icon {{ $pendientesSunat > 0 ? 'phi-red' : 'phi-green' }}">
                        <i class="bi bi-cloud-{{ $pendientesSunat > 0 ? 'slash' : 'check' }}-fill"></i>
                    </div>
                    <div>
                        <div class="panel-hdr-title" style="{{ $pendientesSunat > 0 ? 'color:#dc2626' : '' }}">
                            Pendientes SUNAT
                        </div>
                        <div class="panel-hdr-sub">Comprobantes sin sincronizar</div>
                    </div>
                </div>
                @if($pendientesSunat > 0)
                <span class="badge bg-danger rounded-pill">{{ $pendientesSunat }}</span>
                @else
                <span class="badge" style="background:#f0fdf4;color:#16a34a;border:1px solid #86efac">✓ Al día</span>
                @endif
            </div>

            @if($pendientesSunat == 0)
            <div class="p-4 text-center">
                <div style="width:56px;height:56px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;font-size:1.5rem">✅</div>
                <div class="fw-semibold text-success" style="font-size:0.88rem">Todo sincronizado</div>
                <div class="text-muted" style="font-size:0.75rem;margin-top:3px">SUNAT al día — sin pendientes</div>
            </div>
            @else
            <div style="max-height:270px;overflow-y:auto">
                @foreach($pendientesLista as $sale)
                <div class="sunat-item px-3">
                    <div class="sunat-pulse"></div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:0.82rem">{{ $sale->student->nombre_completo }}</div>
                        <code style="font-size:0.72rem;color:#64748b">{{ $sale->numero_comprobante }}</code>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold text-success" style="font-size:0.82rem">S/ {{ number_format($sale->mto_imp_venta,2) }}</div>
                        <form method="POST" action="{{ route('sales.reenviar', $sale) }}" class="d-inline"
                              onsubmit="return confirm('¿Reenviar a SUNAT?')">
                            @csrf
                            <button type="submit" class="btn-act amber mt-1" title="Reenviar">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($pendientesSunat > 0)
            <div style="padding:0.75rem 1.25rem;border-top:1px solid #f1f5f9">
                <a href="{{ route('reports.pendientesSunat') }}" class="lnk-btn">
                    Ver todos los pendientes <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ÚLTIMOS COBROS --}}
<div class="panel-card pd2">
    <div class="panel-hdr">
        <div class="panel-hdr-left">
            <div class="panel-hdr-icon phi-purple"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="panel-hdr-title">Actividad Reciente</div>
                <div class="panel-hdr-sub">Últimos 10 cobros procesados</div>
            </div>
        </div>
        <a href="{{ route('payments.index') }}" class="lnk-btn">
            Ver todos <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>
                <th>Fecha</th>
                <th>Alumno</th>
                <th>Curso</th>
                <th>Tipo</th>
                <th class="text-end">Total</th>
                <th>Comprobante</th>
                <th>SUNAT</th>
            </tr></thead>
            <tbody>
                @foreach($ultimosCobros as $pago)
                <tr>
                    <td>
                        <div style="font-size:0.8rem;font-weight:600;color:#374151">{{ $pago->fecha_pago->format('d/m/Y') }}</div>
                        <div class="text-muted" style="font-size:0.7rem">{{ $pago->fecha_pago->format('H:i') }}</div>
                    </td>
                    <td>
                        <a href="{{ $pago->student_id ? route('students.show', $pago->student_id) : '#' }}"
                           class="fw-semibold text-decoration-none text-dark d-flex align-items-center gap-2">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                 style="width:28px;height:28px;background:var(--brand-secondary);font-size:0.68rem">
                                {{ strtoupper(substr($pago->student->apellido_paterno ?? '?', 0, 1)) }}
                            </div>
                            <span>{{ $pago->student->nombre_completo }}</span>
                        </a>
                    </td>
                    <td class="text-muted" style="font-size:0.8rem">{{ $pago->enrollment?->course?->nombre ?? '—' }}</td>
                    <td>
                        <span class="badge bg-light text-dark border" style="font-size:0.72rem">{{ $pago->tipo_pago }}</span>
                    </td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($pago->total, 2) }}</td>
                    <td>
                        @if($pago->sale)
                        <a href="{{ route('sales.show', $pago->sale) }}" class="text-decoration-none">
                            <code style="font-size:0.78rem">{{ $pago->sale->numero_comprobante }}</code>
                        </a>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>@if($pago->sale){!! $pago->sale->estado_badge !!}@else<span class="text-muted">—</span>@endif</td>
                </tr>
                @endforeach
                @if($ultimosCobros->isEmpty())
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:2rem;color:#e2e8f0;display:block;margin-bottom:0.5rem"></i>
                    No hay cobros registrados
                </td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
/* ══ COUNTER-UP ══ */
function animateCounter(el) {
    const target   = parseFloat(el.dataset.count) || 0;
    const prefix   = el.dataset.prefix  || '';
    const decimals = parseInt(el.dataset.decimals) || 0;
    const duration = 1100;
    const start    = performance.now();
    const fmt = v => decimals > 0
        ? prefix + v.toLocaleString('es-PE', {minimumFractionDigits: decimals, maximumFractionDigits: decimals})
        : prefix + Math.round(v).toLocaleString('es-PE');
    const step = now => {
        const p = Math.min((now - start) / duration, 1);
        const e = 1 - Math.pow(1 - p, 3);
        el.textContent = fmt(target * e);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = fmt(target);
    };
    requestAnimationFrame(step);
}
window.addEventListener('load', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-count]').forEach((el, i) => {
            setTimeout(() => animateCounter(el), i * 60);
        });
    }, 200);
});

/* ══ 3D TILT ON KPI CARDS ══ */
document.querySelectorAll('.kpi-card').forEach(card => {
    card.addEventListener('mousemove', e => {
        const r  = card.getBoundingClientRect();
        const dx = (e.clientX - r.left - r.width  / 2) / (r.width  * 0.5);
        const dy = (e.clientY - r.top  - r.height / 2) / (r.height * 0.5);
        card.style.transform = `translateY(-5px) perspective(600px) rotateX(${-dy*3.5}deg) rotateY(${dx*3.5}deg)`;
    });
    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
});

@if(!auth()->user()->hasRole('cajero'))
/* ══ CHART ══ */
(function() {
    const ctx = document.getElementById('chartIngresos');
    if (!ctx) return;
    const labels = {!! json_encode($chartLabels) !!};
    const data   = {!! json_encode($chartData)   !!};

    const g = ctx.getContext('2d').createLinearGradient(0, 0, 0, 210);
    g.addColorStop(0,   'rgba(37,99,235,0.3)');
    g.addColorStop(0.65,'rgba(37,99,235,0.07)');
    g.addColorStop(1,   'rgba(37,99,235,0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Ingresos (S/)',
                data,
                backgroundColor: g,
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(37,99,235,0.5)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: { duration: 1000, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { size: 11, weight: '600', family: 'Inter' },
                    bodyFont:  { size: 12, family: 'Inter' },
                    padding: 12, cornerRadius: 10,
                    callbacks: { label: c => '  S/ ' + c.raw.toLocaleString('es-PE', {minimumFractionDigits:2}) }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' }, border: { display: false },
                    ticks: { callback: v => 'S/ ' + v.toLocaleString('es-PE'), font: { size: 10 }, color: '#94a3b8' }
                },
                x: {
                    grid: { display: false }, border: { display: false },
                    ticks: { font: { size: 10 }, color: '#64748b' }
                }
            }
        }
    });
})();
@endif
</script>
@endsection
