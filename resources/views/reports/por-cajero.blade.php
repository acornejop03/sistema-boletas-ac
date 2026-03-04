@extends('layouts.app')
@section('title', 'Reporte por Cajero')
@section('breadcrumb')
    <li class="breadcrumb-item">Reportes</li>
    <li class="breadcrumb-item active">Por Cajero</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-person-lines-fill me-2 text-primary"></i>Reporte por Cajero</h5>
        <div class="page-subtitle">Recaudación por usuario en el período</div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4 col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" name="fecha_desde" value="{{ $fecha_desde }}" class="form-control form-control-sm">
            </div>
            <div class="col-sm-4 col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ $fecha_hasta }}" class="form-control form-control-sm">
            </div>
            <div class="col-sm-4 col-md-2">
                <label class="form-label d-none d-sm-block">&nbsp;</label>
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-table me-2 text-primary"></i>
        Resultados del {{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Cajero</th><th class="text-end">N° Cobros</th><th class="text-end">Total Recaudado</th></tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:32px;height:32px;background:#2563eb;font-size:0.78rem;flex-shrink:0">
                                {{ strtoupper(substr($row['usuario'], 0, 2)) }}
                            </div>
                            <span class="fw-semibold">{{ $row['usuario'] }}</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $row['cantidad'] }}</span>
                    </td>
                    <td class="text-end fw-bold text-success fs-6">S/ {{ number_format($row['total'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-5">
                    <i class="bi bi-inbox d-block fs-1 mb-2 opacity-25"></i>No hay datos para el período
                </td></tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <td class="fw-bold text-end">Total:</td>
                    <td class="text-end fw-bold">{{ $data->sum('cantidad') }}</td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($data->sum('total'), 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
