@extends('layouts.app')
@section('title', 'Cobros')
@section('breadcrumb')
    <li class="breadcrumb-item active">Cobros</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-cash-coin text-success"></i> Cobros</h5>
        <div class="page-subtitle">Historial y gestión de cobros registrados</div>
    </div>
    <div class="page-header-actions">
        @can('crear cobros')
        <a href="{{ route('payments.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Nuevo Cobro
        </a>
        @endcan
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Desde</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde', now()->startOfDay()->toDateString()) }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Hasta</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta', now()->toDateString()) }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Tipo</label>
            <select name="tipo_pago" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach(['MATRICULA','PENSION','MATERIALES','OTRO'] as $t)
                <option value="{{ $t }}" {{ request('tipo_pago')==$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Forma de pago</label>
            <select name="forma_pago" class="form-select form-select-sm">
                <option value="">Todas</option>
                @foreach(['EFECTIVO','TARJETA','TRANSFERENCIA','YAPE','PLIN'] as $f)
                <option value="{{ $f }}" {{ request('forma_pago')==$f?'selected':'' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size:0.72rem;color:#64748b;margin-bottom:2px">Estado</label>
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="PAGADO"  {{ request('estado')=='PAGADO' ?'selected':'' }}>Pagado</option>
                <option value="ANULADO" {{ request('estado')=='ANULADO'?'selected':'' }}>Anulado</option>
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2 align-self-end">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.82rem">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Alumno</th>
                    <th>Concepto</th>
                    <th>Periodo</th>
                    <th>Forma Pago</th>
                    <th class="text-end">Total</th>
                    <th>Estado</th>
                    <th>Comprobante</th>
                    <th>SUNAT</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr class="{{ $p->estado_pago=='ANULADO' ? 'opacity-50' : '' }}">
                    <td class="text-muted">{{ $p->fecha_pago->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('students.show', $p->student_id) }}" class="text-decoration-none fw-semibold text-dark">
                            {{ $p->student->nombre_completo }}
                        </a>
                        <div class="text-muted" style="font-size:0.72rem">{{ $p->student->codigo }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $p->tipo_pago }}</span>
                        @if($p->enrollment)
                        <div class="text-muted" style="font-size:0.72rem;margin-top:2px">{{ $p->enrollment->course->nombre }}</div>
                        @endif
                    </td>
                    <td class="text-muted">{{ $p->periodo_pago ?? '—' }}</td>
                    <td>
                        @php
                            $payColors = ['EFECTIVO'=>'success','TARJETA'=>'primary','TRANSFERENCIA'=>'info','YAPE'=>'purple','PLIN'=>'warning'];
                            $payIcons  = ['EFECTIVO'=>'💵','TARJETA'=>'💳','TRANSFERENCIA'=>'🏦','YAPE'=>'📱','PLIN'=>'📱'];
                        @endphp
                        <span class="badge bg-{{ $payColors[$p->forma_pago] ?? 'secondary' }}-subtle text-{{ $payColors[$p->forma_pago] ?? 'secondary' }} border border-{{ $payColors[$p->forma_pago] ?? 'secondary' }}-subtle"
                              style="{{ $p->forma_pago=='YAPE' ? 'background:#f5f3ff!important;color:#7c3aed!important;border-color:#ddd6fe!important' : '' }}">
                            {{ $payIcons[$p->forma_pago] ?? '' }} {{ $p->forma_pago }}
                        </span>
                    </td>
                    <td class="text-end fw-bold {{ $p->estado_pago=='ANULADO' ? 'text-muted' : 'text-success' }}">
                        S/ {{ number_format($p->total, 2) }}
                    </td>
                    <td>{!! $p->estado_badge !!}</td>
                    <td>
                        @if($p->sale)
                        <a href="{{ route('sales.show', $p->sale) }}" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle text-decoration-none">
                            {{ $p->sale->numero_comprobante }}
                        </a>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>@if($p->sale){!! $p->sale->estado_badge !!}@else<span class="text-muted">—</span>@endif</td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('payments.show', $p) }}" class="btn-act blue" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($p->sale)
                            <a href="{{ route('sales.pdf', $p->sale) }}" class="btn-act red" title="PDF" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            @endif
                            @can('anular cobros')
                            @if($p->estado_pago !== 'ANULADO')
                            <button type="button" class="btn-act slate" title="Anular cobro" onclick="anularCobro({{ $p->id }})">
                                <i class="bi bi-x-circle"></i>
                            </button>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div style="font-size:2.5rem;color:#e2e8f0"><i class="bi bi-inbox"></i></div>
                        <div class="text-muted mt-2">No hay cobros para mostrar</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($payments->count() > 0)
            <tfoot>
                <tr style="background:#f8fafc">
                    <td colspan="5" class="text-end fw-semibold text-muted" style="font-size:0.78rem">Total del período:</td>
                    <td class="text-end fw-bold text-success">
                        S/ {{ number_format($payments->where('estado_pago','PAGADO')->sum('total'), 2) }}
                    </td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">{{ $payments->links() }}</div>
    @endif
</div>

{{-- Modal Anular --}}
<div class="modal fade" id="modalAnular" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="formAnular">
            @csrf
            <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2)">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-octagon me-2"></i>Anular Cobro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size:0.875rem">Esta acción anulará el cobro y su comprobante SUNAT asociado. Esta operación no se puede deshacer.</p>
                    <label class="form-label">Motivo de anulación <span class="text-danger">*</span></label>
                    <textarea name="motivo" class="form-control" rows="3" required placeholder="Ingrese el motivo de la anulación..."></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-x-circle me-1"></i>Confirmar Anulación
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function anularCobro(id) {
    $('#formAnular').attr('action', '/cobros/' + id + '/anular');
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
@endsection
