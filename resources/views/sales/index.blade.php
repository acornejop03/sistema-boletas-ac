@extends('layouts.app')
@section('title', 'Comprobantes')
@section('breadcrumb')
    <li class="breadcrumb-item active">Comprobantes</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Comprobantes Electrónicos SUNAT</h5>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="N° comprobante o alumno...">
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    <option value="03" {{ request('tipo')=='03'?'selected':'' }}>Boleta</option>
                    <option value="01" {{ request('tipo')=='01'?'selected':'' }}>Factura</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="ACEPTADO" {{ request('estado')=='ACEPTADO'?'selected':'' }}>Aceptado</option>
                    <option value="PENDIENTE" {{ request('estado')=='PENDIENTE'?'selected':'' }}>Pendiente</option>
                    <option value="RECHAZADO" {{ request('estado')=='RECHAZADO'?'selected':'' }}>Rechazado</option>
                    <option value="ANULADO" {{ request('estado')=='ANULADO'?'selected':'' }}>Anulado</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-sm btn-primary">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Alumno</th>
                    <th>Total</th>
                    <th>Estado SUNAT</th>
                    <th>Emisor</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td><code class="fw-bold">{{ $sale->numero_comprobante }}</code></td>
                    <td>
                        <span class="badge {{ $sale->tipo_comprobante=='03' ? 'bg-primary' : 'bg-dark' }}">
                            {{ $sale->tipo_nombre }}
                        </span>
                    </td>
                    <td>{{ $sale->fecha_emision->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('students.show', $sale->student_id) }}" class="text-decoration-none">
                            {{ $sale->student->nombre_completo }}
                        </a>
                        <div class="text-muted" style="font-size:0.72rem">{{ $sale->student->numero_documento }}</div>
                    </td>
                    <td class="fw-bold text-success">S/ {{ number_format($sale->mto_imp_venta, 2) }}</td>
                    <td>{!! $sale->estado_badge !!}</td>
                    <td class="text-muted">{{ $sale->user->name }}</td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-xs btn-outline-primary" title="Ver detalle" style="padding:2px 8px;font-size:0.75rem">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('descargar pdf')
                            <a href="{{ route('sales.pdf', $sale) }}" class="btn btn-xs btn-outline-danger" target="_blank" title="PDF" style="padding:2px 8px;font-size:0.75rem">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            @endcan
                            @can('descargar xml')
                            @if($sale->ruta_xml)
                            <a href="{{ route('sales.xml', $sale) }}" class="btn btn-xs btn-outline-secondary" title="XML" style="padding:2px 8px;font-size:0.75rem">
                                <i class="bi bi-file-code"></i>
                            </a>
                            @endif
                            @endcan
                            @can('reenviar comprobantes')
                            @if($sale->estado_sunat !== 'ACEPTADO')
                            <form method="POST" action="{{ route('sales.reenviar', $sale) }}">
                                @csrf
                                <button class="btn btn-xs btn-outline-warning" title="Reenviar a SUNAT"
                                        style="padding:2px 8px;font-size:0.75rem"
                                        onclick="return confirm('¿Reenviar este comprobante a SUNAT?')">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-5">
                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>No hay comprobantes para mostrar
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sales->hasPages())
    <div class="card-footer bg-white">{{ $sales->links() }}</div>
    @endif
</div>
@endsection
