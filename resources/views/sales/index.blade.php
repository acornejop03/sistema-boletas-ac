@extends('layouts.app')
@section('title', 'Comprobantes')
@section('breadcrumb')
    <li class="breadcrumb-item active">Comprobantes</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-receipt text-primary"></i> Comprobantes Electrónicos</h5>
        <div class="page-subtitle">Boletas y facturas emitidas a SUNAT</div>
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Buscar</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="N° comprobante o alumno...">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Tipo</label>
            <select name="tipo" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="03" {{ request('tipo')=='03'?'selected':'' }}>Boleta</option>
                <option value="01" {{ request('tipo')=='01'?'selected':'' }}>Factura</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Estado SUNAT</label>
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="ACEPTADO"  {{ request('estado')=='ACEPTADO' ?'selected':'' }}>Aceptado</option>
                <option value="PENDIENTE" {{ request('estado')=='PENDIENTE'?'selected':'' }}>Pendiente</option>
                <option value="RECHAZADO" {{ request('estado')=='RECHAZADO'?'selected':'' }}>Rechazado</option>
                <option value="ANULADO"   {{ request('estado')=='ANULADO'  ?'selected':'' }}>Anulado</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Desde</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Hasta</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.835rem">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Alumno</th>
                    <th class="text-end">Total</th>
                    <th>Estado SUNAT</th>
                    <th>Emisor</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>
                        <code class="fw-bold text-primary" style="font-size:0.85rem">{{ $sale->numero_comprobante }}</code>
                    </td>
                    <td>
                        @if($sale->tipo_comprobante == '03')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="bi bi-receipt me-1"></i>Boleta
                        </span>
                        @else
                        <span class="badge bg-dark-subtle text-dark border border-dark-subtle">
                            <i class="bi bi-file-earmark-text me-1"></i>Factura
                        </span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $sale->fecha_emision->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('students.show', $sale->student_id) }}" class="text-decoration-none fw-semibold text-dark">
                            {{ $sale->student->nombre_completo }}
                        </a>
                        <div class="text-muted" style="font-size:0.72rem">{{ $sale->student->numero_documento }}</div>
                    </td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($sale->mto_imp_venta, 2) }}</td>
                    <td>{!! $sale->estado_badge !!}</td>
                    <td class="text-muted" style="font-size:0.8rem">{{ $sale->user->name }}</td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('sales.show', $sale) }}" class="btn-act blue" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('descargar pdf')
                            <a href="{{ route('sales.pdf', $sale) }}" class="btn-act red" target="_blank" title="PDF">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            @endcan
                            @can('descargar xml')
                            @if($sale->ruta_xml)
                            <a href="{{ route('sales.xml', $sale) }}" class="btn-act slate" title="XML">
                                <i class="bi bi-file-code"></i>
                            </a>
                            @endif
                            @endcan
                            @can('reenviar comprobantes')
                            @if($sale->estado_sunat !== 'ACEPTADO')
                            <form method="POST" action="{{ route('sales.reenviar', $sale) }}" class="d-inline"
                                  onsubmit="return confirm('¿Reenviar este comprobante a SUNAT?')">
                                @csrf
                                <button type="submit" class="btn-act amber" title="Reenviar a SUNAT">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="font-size:2.5rem;color:#e2e8f0"><i class="bi bi-receipt"></i></div>
                        <div class="text-muted mt-2">No hay comprobantes para mostrar</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sales->hasPages())
    <div class="card-footer">{{ $sales->links() }}</div>
    @endif
</div>
@endsection
