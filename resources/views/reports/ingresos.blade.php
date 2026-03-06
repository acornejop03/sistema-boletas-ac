@extends('layouts.app')
@section('title', 'Reporte de Ingresos')
@section('breadcrumb')
    <li class="breadcrumb-item">Reportes</li>
    <li class="breadcrumb-item active">Ingresos</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-bar-chart-line me-2 text-primary"></i>Reporte de Ingresos</h5>
        <div class="page-subtitle">Resumen de cobros por periodo y tipo</div>
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-sm-4 col-md-3">
            <select name="year" class="form-select form-select-sm">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                <option value="{{ $y }}" {{ $year==$y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-sm-4 col-md-3">
            <select name="month" class="form-select form-select-sm">
                <option value="">Todos los meses</option>
                @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                <option value="{{ $i+1 }}" {{ $month==$i+1 ? 'selected' : '' }}>{{ $mes }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4 col-md-auto d-flex gap-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
        </div>
    </form>
</div>

{{-- TOTAL CARD --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-green">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-value">S/ {{ number_format($totalGeneral, 2) }}</div>
                    <div class="stat-label">Total del período {{ $year }}{{ $month ? ' - Mes '.$month : '' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-blue">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="stat-value">{{ $ingresos->sum('cantidad') }}</div>
                    <div class="stat-label">Total de cobros</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABLA --}}
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon blue"><i class="bi bi-table"></i></div>
        <div>
            <div class="card-hdr-title">Detalle por periodo y tipo</div>
            <div class="card-hdr-sub">{{ $ingresos->count() }} registro(s) encontrado(s)</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Año</th><th>Mes</th><th>Tipo de Cobro</th>
                    <th class="text-end">Cantidad</th><th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio',
                              'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                    $tipoBadge = ['MATRICULA'=>'primary','PENSION'=>'success','MATERIALES'=>'info','OTRO'=>'secondary'];
                @endphp
                @forelse($ingresos as $r)
                <tr>
                    <td class="fw-semibold">{{ $r['year'] }}</td>
                    <td>{{ $meses[$r['month']] ?? $r['month'] }}</td>
                    <td>
                        <span class="badge bg-{{ $tipoBadge[$r['tipo_pago']] ?? 'secondary' }}-subtle
                                      text-{{ $tipoBadge[$r['tipo_pago']] ?? 'secondary' }} border border-{{ $tipoBadge[$r['tipo_pago']] ?? 'secondary' }}-subtle">
                            {{ $r['tipo_pago'] }}
                        </span>
                    </td>
                    <td class="text-end">{{ $r['cantidad'] }}</td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($r['total'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                        No hay datos para el período seleccionado
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($ingresos->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total general:</td>
                    <td class="text-end fw-bold">{{ $ingresos->sum('cantidad') }}</td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($ingresos->sum('total'), 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
