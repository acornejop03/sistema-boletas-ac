@extends('layouts.app')
@section('title', 'Cobros')
@section('breadcrumb')
    <li class="breadcrumb-item active">Cobros</li>
@endsection

@section('styles')
<style>
/* ══ PAYMENT INDEX STYLES ══ */
.pay-header {
    display:flex; justify-content:space-between; align-items:flex-start;
    flex-wrap:wrap; gap:.75rem; margin-bottom:1.25rem;
}
.pay-title {
    font-size:1.1rem; font-weight:800; color:#0f172a;
    display:flex; align-items:center; gap:10px;
}
.pay-title-icon {
    width:36px; height:36px; border-radius:10px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    display:flex; align-items:center; justify-content:center;
    font-size:1rem; color:#fff;
    box-shadow:0 4px 12px rgba(34,197,94,.35);
}
.pay-subtitle { font-size:.775rem; color:#64748b; margin-top:2px; }

/* ── Resumen strips ── */
.pay-summary {
    display:grid;
    grid-template-columns: repeat(4, 1fr);
    gap:.75rem; margin-bottom:1.1rem;
}
@media(max-width:991px){ .pay-summary{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:575px){ .pay-summary{ grid-template-columns: repeat(2,1fr); } }

.pay-strip {
    background:#fff; border-radius:14px;
    padding:.9rem 1.1rem;
    border:1px solid rgba(0,0,0,.04);
    box-shadow:0 1px 4px rgba(0,0,0,.05);
    display:flex; align-items:center; gap:12px;
    transition:box-shadow .2s, transform .2s;
    position:relative; overflow:hidden;
}
.pay-strip::before {
    content:''; position:absolute;
    top:0; left:0; right:0; height:3px;
    border-radius:14px 14px 0 0;
}
.pay-strip:hover { box-shadow:0 6px 20px rgba(0,0,0,.09); transform:translateY(-2px); }
.ps-green::before { background:linear-gradient(90deg,#22c55e,#16a34a); }
.ps-blue ::before { background:linear-gradient(90deg,#3b82f6,#2563eb); }
.ps-amber::before { background:linear-gradient(90deg,#f59e0b,#d97706); }
.ps-red  ::before { background:linear-gradient(90deg,#ef4444,#dc2626); }

.pay-strip .ps-icon {
    width:40px; height:40px; border-radius:11px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.15rem; flex-shrink:0;
}
.ps-green .ps-icon { background:#f0fdf4; color:#16a34a; }
.ps-blue  .ps-icon { background:#eff6ff; color:#2563eb; }
.ps-amber .ps-icon { background:#fffbeb; color:#d97706; }
.ps-red   .ps-icon { background:#fef2f2; color:#dc2626; }

.ps-val  { font-size:1.3rem; font-weight:800; letter-spacing:-.02em; line-height:1.1; }
.ps-lbl  { font-size:.72rem; color:#64748b; font-weight:500; margin-top:1px; }
.ps-green .ps-val { color:#15803d; }
.ps-blue  .ps-val { color:#1d4ed8; }
.ps-amber .ps-val { color:#b45309; }
.ps-red   .ps-val { color:#b91c1c; }

/* ── Filter card ── */
.filter-wrap {
    background:#fff; border-radius:14px;
    border:1px solid rgba(0,0,0,.05);
    box-shadow:0 1px 4px rgba(0,0,0,.05);
    padding:.9rem 1.1rem 1rem;
    margin-bottom:1rem;
}
.filter-toggle {
    display:flex; align-items:center; justify-content:space-between;
    cursor:pointer; user-select:none;
}
.filter-toggle-lbl {
    font-size:.8rem; font-weight:700; color:#374151;
    display:flex; align-items:center; gap:7px;
}
.filter-badge {
    background:#eff6ff; color:#2563eb;
    border:1px solid #bfdbfe;
    border-radius:20px; padding:1px 8px;
    font-size:.66rem; font-weight:600;
}
.filter-chevron { color:#94a3b8; transition:transform .2s; font-size:.85rem; }
.filter-chevron.open { transform:rotate(180deg); }
.filter-body { padding-top:.85rem; }

/* ── Search bar ── */
.search-wrap {
    position:relative; flex:1; min-width:180px;
}
.search-wrap .bi-search {
    position:absolute; left:10px; top:50%; transform:translateY(-50%);
    color:#94a3b8; font-size:.85rem; pointer-events:none;
}
.search-wrap input {
    padding-left:32px;
}

/* ── Table card ── */
.pay-table-card {
    background:#fff; border-radius:16px;
    border:1px solid rgba(0,0,0,.04);
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    overflow:hidden;
}
.pay-table-hdr {
    padding:.75rem 1.2rem;
    background: linear-gradient(135deg,#fafbff,#fff);
    border-bottom:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:space-between; gap:.5rem;
    flex-wrap:wrap;
}
.pay-table-title {
    font-size:.85rem; font-weight:700; color:#0f172a;
    display:flex; align-items:center; gap:8px;
}
.pay-count-badge {
    background:#f1f5f9; color:#475569;
    border:1px solid #e2e8f0; border-radius:20px;
    padding:2px 9px; font-size:.7rem; font-weight:600;
}

/* ── Student cell ── */
.stu-avatar {
    width:32px; height:32px; border-radius:50%;
    background:linear-gradient(135deg,#1a3a6b,#2563eb);
    color:#fff; font-size:.72rem; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}
.stu-name { font-weight:600; font-size:.835rem; color:#0f172a; line-height:1.25; }
.stu-code { font-size:.7rem; color:#94a3b8; }

/* ── Tipo pago badge ── */
.tipo-badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:20px;
    font-size:.68rem; font-weight:600; border:1px solid;
}
.tb-matricula { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
.tb-pension   { background:#f0fdf4; color:#16a34a; border-color:#bbf7d0; }
.tb-materiales{ background:#fffbeb; color:#d97706; border-color:#fde68a; }
.tb-otro      { background:#f5f3ff; color:#7c3aed; border-color:#ddd6fe; }

/* ── Forma pago badge ── */
.forma-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 9px; border-radius:20px;
    font-size:.7rem; font-weight:600; border:1px solid;
    white-space:nowrap;
}
.fb-efectivo    { background:#f0fdf4; color:#15803d; border-color:#86efac; }
.fb-tarjeta     { background:#eff6ff; color:#1d4ed8; border-color:#93c5fd; }
.fb-transferencia{ background:#ecfeff; color:#0e7490; border-color:#a5f3fc; }
.fb-yape        { background:#f5f3ff; color:#7c3aed; border-color:#c4b5fd; }
.fb-plin        { background:#fdf4ff; color:#a21caf; border-color:#e879f9; }

/* ── Comprobante cell ── */
.comp-pill {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 9px; border-radius:8px;
    font-size:.72rem; font-weight:600;
    text-decoration:none;
    font-family:'Roboto Mono','Courier New',monospace;
    transition:all .15s;
}
.comp-pill.accepted { background:#f0fdf4; color:#15803d; border:1px solid #86efac; }
.comp-pill.pending  { background:#fffbeb; color:#b45309; border:1px solid #fcd34d; }
.comp-pill.rejected { background:#fef2f2; color:#b91c1c; border:1px solid #fca5a5; }
.comp-pill.annulled { background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; }
.comp-pill:hover    { filter:brightness(.95); transform:scale(1.02); }

/* ── Total ── */
.pay-amount { font-weight:800; font-size:.95rem; }
.pay-amount.ok      { color:#15803d; }
.pay-amount.annulled{ color:#94a3b8; text-decoration:line-through; }

/* ── Row hover ── */
.pay-table-card tbody tr {
    transition:background .12s;
}
.pay-table-card tbody tr:hover { background:#fafbff; }
.pay-table-card tbody tr.row-annulled { opacity:.55; }

/* ── Action buttons ── */
.act-grp { display:flex; gap:4px; justify-content:flex-end; }

/* ── Footer total ── */
.pay-foot { background:#f8fafc; border-top:1px solid #f1f5f9; padding:.6rem 1rem; }
.pay-foot-row { display:flex; justify-content:flex-end; align-items:center; gap:1.5rem; flex-wrap:wrap; }
.pay-foot-item { font-size:.78rem; }
.pay-foot-item strong { color:#0f172a; }

/* ── Empty state ── */
.empty-state { text-align:center; padding:3.5rem 1rem; }
.empty-icon  { font-size:3rem; color:#e2e8f0; margin-bottom:.75rem; }

/* ── Pagination override ── */
.pagination { margin:0; }
.page-link   { border-radius:8px!important; margin:0 2px; font-size:.8rem; color:#2563eb; border-color:#e2e8f0; }
.page-item.active .page-link { background:#2563eb; border-color:#2563eb; }
</style>
@endsection

@section('content')

{{-- ── HEADER ── --}}
<div class="pay-header">
    <div>
        <div class="pay-title">
            <div class="pay-title-icon"><i class="bi bi-cash-coin"></i></div>
            Cobros
        </div>
        <div class="pay-subtitle">Historial y gestión de cobros registrados</div>
    </div>
    @can('crear cobros')
    <a href="{{ route('payments.create') }}" class="qa-btn qa-green" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:.83rem;font-weight:600;border:1.5px solid #86efac;background:#f0fdf4;color:#16a34a;text-decoration:none;transition:all .2s">
        <i class="bi bi-plus-circle-fill"></i> Nuevo Cobro
    </a>
    @endcan
</div>

{{-- ── SUMMARY STRIPS ── --}}
@php
    $allForPeriod = $payments->getCollection();
    $pagados  = $allForPeriod->where('estado_pago','PAGADO');
    $anulados = $allForPeriod->where('estado_pago','ANULADO');
    $totalPeriodo = $pagados->sum('total');
    $contPagados  = $pagados->count();
    $contAnulados = $anulados->count();
    $sinSunat = $allForPeriod->filter(fn($p) => $p->sale && $p->sale->estado_sunat === 'PENDIENTE')->count();
@endphp
<div class="pay-summary">
    <div class="pay-strip ps-green">
        <div class="ps-icon"><i class="bi bi-cash-stack"></i></div>
        <div>
            <div class="ps-val">S/ {{ number_format($totalPeriodo, 2) }}</div>
            <div class="ps-lbl">Recaudado (página)</div>
        </div>
    </div>
    <div class="pay-strip ps-blue">
        <div class="ps-icon"><i class="bi bi-receipt"></i></div>
        <div>
            <div class="ps-val">{{ $contPagados }}</div>
            <div class="ps-lbl">Cobros pagados</div>
        </div>
    </div>
    <div class="pay-strip ps-amber">
        <div class="ps-icon"><i class="bi bi-cloud-arrow-up"></i></div>
        <div>
            <div class="ps-val">{{ $sinSunat }}</div>
            <div class="ps-lbl">Pendientes SUNAT</div>
        </div>
    </div>
    <div class="pay-strip ps-red">
        <div class="ps-icon"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="ps-val">{{ $contAnulados }}</div>
            <div class="ps-lbl">Anulados</div>
        </div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<div class="filter-wrap">
    <div class="filter-toggle" onclick="toggleFilters()">
        <div class="filter-toggle-lbl">
            <i class="bi bi-funnel-fill text-primary"></i>
            Filtros
            @php
                $activeFilters = collect(['fecha_desde','fecha_hasta','tipo_pago','forma_pago','estado','q'])
                    ->filter(fn($k) => request()->filled($k))->count();
            @endphp
            @if($activeFilters > 0)
            <span class="filter-badge">{{ $activeFilters }} activo{{ $activeFilters > 1 ? 's' : '' }}</span>
            @endif
        </div>
        <i class="bi bi-chevron-down filter-chevron {{ $activeFilters > 0 ? 'open' : '' }}" id="filterChevron"></i>
    </div>

    <div class="filter-body {{ $activeFilters > 0 ? '' : 'd-none' }}" id="filterBody">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                {{-- Búsqueda --}}
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.72rem;color:#64748b;margin-bottom:3px">Buscar alumno</label>
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" value="{{ request('q') }}"
                               class="form-control form-control-sm"
                               placeholder="Nombre o código...">
                    </div>
                </div>
                {{-- Desde --}}
                <div class="col-6 col-md-2">
                    <label class="form-label" style="font-size:.72rem;color:#64748b;margin-bottom:3px">Desde</label>
                    <input type="date" name="fecha_desde"
                           value="{{ request('fecha_desde', now()->startOfMonth()->toDateString()) }}"
                           class="form-control form-control-sm">
                </div>
                {{-- Hasta --}}
                <div class="col-6 col-md-2">
                    <label class="form-label" style="font-size:.72rem;color:#64748b;margin-bottom:3px">Hasta</label>
                    <input type="date" name="fecha_hasta"
                           value="{{ request('fecha_hasta', now()->toDateString()) }}"
                           class="form-control form-control-sm">
                </div>
                {{-- Tipo --}}
                <div class="col-6 col-md-2">
                    <label class="form-label" style="font-size:.72rem;color:#64748b;margin-bottom:3px">Tipo</label>
                    <select name="tipo_pago" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach(['MATRICULA','PENSION','MATERIALES','OTRO'] as $t)
                        <option value="{{ $t }}" {{ request('tipo_pago')==$t?'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Forma --}}
                <div class="col-6 col-md-2">
                    <label class="form-label" style="font-size:.72rem;color:#64748b;margin-bottom:3px">Forma de pago</label>
                    <select name="forma_pago" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach(['EFECTIVO','TARJETA','TRANSFERENCIA','YAPE','PLIN'] as $f)
                        <option value="{{ $f }}" {{ request('forma_pago')==$f?'selected':'' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Estado --}}
                <div class="col-6 col-md-1">
                    <label class="form-label" style="font-size:.72rem;color:#64748b;margin-bottom:3px">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="PAGADO"  {{ request('estado')=='PAGADO' ?'selected':'' }}>Pagado</option>
                        <option value="ANULADO" {{ request('estado')=='ANULADO'?'selected':'' }}>Anulado</option>
                    </select>
                </div>
                {{-- Acciones --}}
                <div class="col-auto d-flex gap-2 ms-auto align-self-end">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-secondary" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── TABLA ── --}}
<div class="pay-table-card">
    {{-- Tabla header --}}
    <div class="pay-table-hdr">
        <div class="pay-table-title">
            <div style="width:26px;height:26px;border-radius:7px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:.8rem">
                <i class="bi bi-list-ul"></i>
            </div>
            Listado de cobros
        </div>
        <span class="pay-count-badge">{{ $payments->total() }} registro{{ $payments->total() != 1 ? 's' : '' }}</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size:.825rem">
            <thead>
                <tr>
                    <th class="d-none d-sm-table-cell">Fecha</th>
                    <th>Alumno</th>
                    <th>Concepto</th>
                    <th class="d-none d-md-table-cell">Periodo</th>
                    <th class="d-none d-md-table-cell">Forma de pago</th>
                    <th class="text-end">Total</th>
                    <th class="d-none d-lg-table-cell">Comprobante</th>
                    <th class="d-none d-sm-table-cell">Estado</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($payments as $p)
            <tr class="{{ $p->estado_pago === 'ANULADO' ? 'row-annulled' : '' }}">

                {{-- Fecha --}}
                <td class="d-none d-sm-table-cell">
                    <div style="font-weight:600;color:#374151">{{ $p->fecha_pago->format('d/m/Y') }}</div>
                    <div style="font-size:.68rem;color:#94a3b8">{{ $p->fecha_pago->diffForHumans() }}</div>
                </td>

                {{-- Alumno --}}
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="stu-avatar">{{ strtoupper(substr($p->student->nombres ?? $p->student->nombre_completo, 0, 2)) }}</div>
                        <div>
                            <a href="{{ route('students.show', $p->student_id) }}"
                               class="stu-name text-decoration-none">
                                {{ $p->student->nombre_completo }}
                            </a>
                            <div class="stu-code">{{ $p->student->codigo }}</div>
                        </div>
                    </div>
                </td>

                {{-- Concepto --}}
                <td>
                    @php
                        $tbClass = match($p->tipo_pago) {
                            'MATRICULA'  => 'tb-matricula',
                            'PENSION'    => 'tb-pension',
                            'MATERIALES' => 'tb-materiales',
                            default      => 'tb-otro',
                        };
                    @endphp
                    <span class="tipo-badge {{ $tbClass }}">
                        {{ $p->tipo_pago }}
                    </span>
                    @if($p->enrollment)
                    <div style="font-size:.7rem;color:#64748b;margin-top:3px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                         title="{{ $p->enrollment->course->nombre }}">
                        <i class="bi bi-book me-1"></i>{{ $p->enrollment->course->nombre }}
                    </div>
                    @endif
                </td>

                {{-- Periodo --}}
                <td class="d-none d-md-table-cell">
                    @if($p->periodo_pago)
                    <span style="font-size:.78rem;color:#475569;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:2px 8px">
                        {{ $p->periodo_pago }}
                    </span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>

                {{-- Forma pago --}}
                <td class="d-none d-md-table-cell">
                    @php
                        $fbClass = match($p->forma_pago) {
                            'EFECTIVO'      => 'fb-efectivo',
                            'TARJETA'       => 'fb-tarjeta',
                            'TRANSFERENCIA' => 'fb-transferencia',
                            'YAPE'          => 'fb-yape',
                            'PLIN'          => 'fb-plin',
                            default         => '',
                        };
                        $fbIcon = match($p->forma_pago) {
                            'EFECTIVO'      => 'bi-cash',
                            'TARJETA'       => 'bi-credit-card',
                            'TRANSFERENCIA' => 'bi-bank',
                            'YAPE'          => 'bi-phone',
                            'PLIN'          => 'bi-phone',
                            default         => 'bi-wallet',
                        };
                    @endphp
                    <span class="forma-badge {{ $fbClass }}">
                        <i class="bi {{ $fbIcon }}"></i>
                        {{ $p->forma_pago }}
                    </span>
                    @if($p->numero_operacion)
                    <div style="font-size:.67rem;color:#94a3b8;margin-top:2px">Op: {{ $p->numero_operacion }}</div>
                    @endif
                </td>

                {{-- Total --}}
                <td class="text-end">
                    <span class="pay-amount {{ $p->estado_pago === 'ANULADO' ? 'annulled' : 'ok' }}">
                        S/ {{ number_format($p->total, 2) }}
                    </span>
                    @if($p->igv > 0)
                    <div style="font-size:.67rem;color:#94a3b8">+IGV S/ {{ number_format($p->igv, 2) }}</div>
                    @endif
                </td>

                {{-- Comprobante --}}
                <td class="d-none d-lg-table-cell">
                    @if($p->sale)
                    @php
                        $cpClass = match($p->sale->estado_sunat) {
                            'ACEPTADO'  => 'accepted',
                            'PENDIENTE' => 'pending',
                            'RECHAZADO' => 'rejected',
                            default     => 'annulled',
                        };
                        $cpIcon = match($p->sale->estado_sunat) {
                            'ACEPTADO'  => 'bi-check-circle-fill',
                            'PENDIENTE' => 'bi-clock-fill',
                            'RECHAZADO' => 'bi-x-circle-fill',
                            default     => 'bi-dash-circle',
                        };
                    @endphp
                    <a href="{{ route('sales.show', $p->sale) }}"
                       class="comp-pill {{ $cpClass }}" title="{{ $p->sale->estado_sunat }}">
                        <i class="bi {{ $cpIcon }}" style="font-size:.65rem"></i>
                        {{ $p->sale->numero_comprobante }}
                    </a>
                    @else
                    <span style="color:#cbd5e1;font-size:.78rem">Sin comprobante</span>
                    @endif
                </td>

                {{-- Estado pago --}}
                <td class="d-none d-sm-table-cell">
                    @if($p->estado_pago === 'PAGADO')
                    <span style="background:#f0fdf4;color:#15803d;border:1px solid #86efac;border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:600">
                        <i class="bi bi-check2 me-1"></i>Pagado
                    </span>
                    @elseif($p->estado_pago === 'ANULADO')
                    <span style="background:#fef2f2;color:#b91c1c;border:1px solid #fca5a5;border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:600">
                        <i class="bi bi-x me-1"></i>Anulado
                    </span>
                    @else
                    <span style="background:#fffbeb;color:#b45309;border:1px solid #fcd34d;border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:600">
                        Pendiente
                    </span>
                    @endif
                </td>

                {{-- Acciones --}}
                <td class="pe-3">
                    <div class="act-grp">
                        <a href="{{ route('payments.show', $p) }}"
                           class="btn-act blue" title="Ver detalle">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($p->sale)
                        <a href="{{ route('sales.pdf', $p->sale) }}"
                           class="btn-act red" title="Descargar PDF" target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        @endif
                        @can('anular cobros')
                        @if($p->estado_pago !== 'ANULADO')
                        <button type="button" class="btn-act slate"
                                title="Anular cobro"
                                onclick="abrirAnular({{ $p->id }}, '{{ addslashes($p->student->nombre_completo) }}', '{{ number_format($p->total,2) }}')">
                            <i class="bi bi-x-circle"></i>
                        </button>
                        @endif
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                        <div style="font-weight:600;color:#374151;margin-bottom:4px">No hay cobros</div>
                        <div style="font-size:.8rem;color:#94a3b8">
                            @if(request()->hasAny(['q','tipo_pago','forma_pago','estado']))
                                Prueba cambiando los filtros aplicados
                            @else
                                Registra el primer cobro del día
                            @endif
                        </div>
                        @can('crear cobros')
                        <a href="{{ route('payments.create') }}"
                           style="display:inline-flex;align-items:center;gap:6px;margin-top:12px;padding:8px 16px;border-radius:10px;background:#16a34a;color:#fff;font-size:.82rem;font-weight:600;text-decoration:none">
                            <i class="bi bi-plus-circle-fill"></i> Nuevo Cobro
                        </a>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer con totales --}}
    @if($payments->count() > 0)
    <div class="pay-foot">
        <div class="pay-foot-row">
            <div class="pay-foot-item text-muted">
                Mostrando <strong>{{ $payments->firstItem() }}–{{ $payments->lastItem() }}</strong> de <strong>{{ $payments->total() }}</strong>
            </div>
            <div class="pay-foot-item">
                Pagados esta página: <strong class="text-success">S/ {{ number_format($totalPeriodo, 2) }}</strong>
            </div>
            @if($payments->hasPages())
            <div>{{ $payments->links() }}</div>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ── MODAL ANULAR ── --}}
<div class="modal fade" id="modalAnular" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
        <form method="POST" id="formAnular">
            @csrf
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 24px 60px rgba(0,0,0,.2)">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div>
                        <div style="width:44px;height:44px;border-radius:12px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:.75rem">
                            <i class="bi bi-x-octagon-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-0" style="color:#1e293b;font-size:1rem">Anular cobro</h5>
                        <p class="text-muted mb-0 mt-1" style="font-size:.8rem" id="modalAnularInfo"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="position:absolute;top:1.25rem;right:1.25rem"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:.75rem 1rem;font-size:.8rem;color:#991b1b;margin-bottom:1rem">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Esta acción anula el cobro y su comprobante SUNAT. No se puede deshacer.
                    </div>
                    <label class="form-label" style="font-size:.8rem;font-weight:600">Motivo de anulación <span class="text-danger">*</span></label>
                    <textarea name="motivo" class="form-control" rows="3" required
                              placeholder="Describe el motivo de la anulación..."></textarea>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-semibold px-4">
                        <i class="bi bi-x-circle me-1"></i>Confirmar anulación
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
/* Toggle filtros */
function toggleFilters() {
    const body = document.getElementById('filterBody');
    const chev = document.getElementById('filterChevron');
    body.classList.toggle('d-none');
    chev.classList.toggle('open');
}

/* Abrir modal anular */
function abrirAnular(id, nombre, total) {
    document.getElementById('formAnular').action = '/cobros/' + id + '/anular';
    document.getElementById('modalAnularInfo').textContent =
        nombre + ' — S/ ' + total;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}

/* Auto-submit al cambiar selects */
document.querySelectorAll('#filterForm select').forEach(sel => {
    sel.addEventListener('change', () => document.getElementById('filterForm').submit());
});
</script>
@endsection
