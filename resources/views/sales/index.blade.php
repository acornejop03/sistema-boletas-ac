@extends('layouts.app')
@section('title', 'Comprobantes')
@section('breadcrumb')
    <li class="breadcrumb-item active">Comprobantes</li>
@endsection
@section('content')

<div class="page-header">
    <div>
        <h5><i class="bi bi-receipt text-primary"></i> Comprobantes Electrónicos</h5>
        <div class="page-subtitle">Boletas, facturas y notas de venta emitidas</div>
    </div>
</div>

{{-- STAT STRIPS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);border-left:4px solid #8b5cf6!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#6d28d9;line-height:1">{{ $stats['total'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Total emitidos</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#ddd6fe;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#6d28d9">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-left:4px solid #22c55e!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#15803d;line-height:1">{{ $stats['aceptados'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Aceptados SUNAT</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#bbf7d0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#15803d">
                        <i class="bi bi-cloud-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-left:4px solid #f59e0b!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#b45309;line-height:1">{{ $stats['pendientes'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Pendientes</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#fde68a;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#b45309">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#f0fdf4,#ecfeff);border-left:4px solid #10b981!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.35rem;font-weight:800;color:#065f46;line-height:1">S/ {{ number_format($stats['monto_mes'], 0) }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Facturado este mes</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#a7f3d0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#065f46">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-3">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Buscar</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="N° comprobante o alumno...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Tipo</label>
            <select name="tipo" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="03" {{ request('tipo')=='03'?'selected':'' }}>Boleta</option>
                <option value="01" {{ request('tipo')=='01'?'selected':'' }}>Factura</option>
                <option value="NV" {{ request('tipo')=='NV'?'selected':'' }}>Nota de Venta</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Estado SUNAT</label>
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="ACEPTADO"  {{ request('estado')=='ACEPTADO' ?'selected':'' }}>✅ Aceptado</option>
                <option value="PENDIENTE" {{ request('estado')=='PENDIENTE'?'selected':'' }}>⏳ Pendiente</option>
                <option value="RECHAZADO" {{ request('estado')=='RECHAZADO'?'selected':'' }}>❌ Rechazado</option>
                <option value="ANULADO"   {{ request('estado')=='ANULADO'  ?'selected':'' }}>🚫 Anulado</option>
                <option value="NO_APLICA" {{ request('estado')=='NO_APLICA'?'selected':'' }}>📄 Nota Venta</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Desde</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control form-control-sm">
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Hasta</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control form-control-sm">
        </div>
        <div class="col-12 col-md-auto d-flex gap-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon purple"><i class="bi bi-receipt"></i></div>
        <div>
            <div class="card-hdr-title">Comprobantes Emitidos</div>
            <div class="card-hdr-sub">{{ $sales->total() }} comprobante(s) encontrado(s)</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.835rem">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th class="d-none d-sm-table-cell">Tipo</th>
                    <th class="d-none d-sm-table-cell">Fecha</th>
                    <th class="d-none d-md-table-cell">Alumno</th>
                    <th class="text-end">Total</th>
                    <th>Estado</th>
                    <th class="d-none d-lg-table-cell">Emisor</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>
                        <code class="fw-bold text-primary" style="font-size:0.85rem">{{ $sale->numero_comprobante }}</code>
                        <div class="d-md-none text-muted" style="font-size:0.75rem;margin-top:2px">
                            {{ $sale->student->nombre_completo }}
                        </div>
                    </td>
                    <td class="d-none d-sm-table-cell">
                        @if($sale->tipo_comprobante === '03')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="bi bi-receipt me-1"></i>Boleta
                        </span>
                        @elseif($sale->tipo_comprobante === '01')
                        <span class="badge bg-dark-subtle text-dark border border-dark-subtle">
                            <i class="bi bi-file-earmark-text me-1"></i>Factura
                        </span>
                        @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            <i class="bi bi-file-earmark-minus me-1"></i>Nota Venta
                        </span>
                        @endif
                    </td>
                    <td class="d-none d-sm-table-cell text-muted">{{ $sale->fecha_emision->format('d/m/Y') }}</td>
                    <td class="d-none d-md-table-cell">
                        @if($sale->student_id)
                        <a href="{{ route('students.show', $sale->student_id) }}" class="text-decoration-none fw-semibold text-dark">
                            {{ $sale->student->nombre_completo }}
                        </a>
                        <div class="text-muted" style="font-size:0.72rem">{{ $sale->student->numero_documento }}</div>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($sale->mto_imp_venta, 2) }}</td>
                    <td>{!! $sale->estado_badge !!}</td>
                    <td class="d-none d-lg-table-cell text-muted" style="font-size:0.8rem">{{ $sale->user->name ?? '—' }}</td>
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
                            @if(!in_array($sale->estado_sunat, ['ACEPTADO', 'ANULADO', 'NO_APLICA']))
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
                        <div style="font-size:3rem;color:#e2e8f0"><i class="bi bi-receipt"></i></div>
                        <div class="fw-semibold text-muted mt-2">No hay comprobantes para mostrar</div>
                        <div class="text-muted" style="font-size:0.8rem">Los comprobantes aparecen al registrar cobros</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sales->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
        {{ $sales->links() }}
    </div>
    @endif
</div>
@endsection
