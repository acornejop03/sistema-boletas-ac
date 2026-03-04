@extends('layouts.app')
@section('title', 'Cobros')
@section('breadcrumb')
    <li class="breadcrumb-item active">Cobros</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-cash-coin me-2"></i>Cobros</h5>
    @can('crear cobros')
    <a href="{{ route('payments.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>Nuevo Cobro
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-2">
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde', now()->startOfDay()->toDateString()) }}" class="form-control form-control-sm" placeholder="Desde">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta', now()->toDateString()) }}" class="form-control form-control-sm" placeholder="Hasta">
            </div>
            <div class="col-md-2">
                <select name="tipo_pago" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    @foreach(['MATRICULA','PENSION','MATERIALES','OTRO'] as $t)
                    <option value="{{ $t }}" {{ request('tipo_pago')==$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="forma_pago" class="form-select form-select-sm">
                    <option value="">Todas las formas</option>
                    @foreach(['EFECTIVO','TARJETA','TRANSFERENCIA','YAPE','PLIN'] as $f)
                    <option value="{{ $f }}" {{ request('forma_pago')==$f?'selected':'' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="PAGADO" {{ request('estado')=='PAGADO'?'selected':'' }}>Pagado</option>
                    <option value="ANULADO" {{ request('estado')=='ANULADO'?'selected':'' }}>Anulado</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button class="btn btn-sm btn-primary flex-1">Filtrar</button>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Alumno</th>
                    <th>Concepto</th>
                    <th>Periodo</th>
                    <th>Forma Pago</th>
                    <th>Total</th>
                    <th>Estado Pago</th>
                    <th>Comprobante</th>
                    <th>SUNAT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr class="{{ $p->estado_pago=='ANULADO' ? 'table-secondary text-muted' : '' }}">
                    <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('students.show', $p->student_id) }}" class="text-decoration-none fw-semibold">
                            {{ $p->student->nombre_completo }}
                        </a>
                        <div class="text-muted" style="font-size:0.72rem">{{ $p->student->codigo }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $p->tipo_pago }}</span>
                        @if($p->enrollment)
                            <div class="text-muted" style="font-size:0.72rem">{{ $p->enrollment->course->nombre }}</div>
                        @endif
                    </td>
                    <td>{{ $p->periodo_pago ?? '—' }}</td>
                    <td>
                        @php
                            $colors = ['EFECTIVO'=>'success','TARJETA'=>'primary','TRANSFERENCIA'=>'info','YAPE'=>'purple','PLIN'=>'warning'];
                            $icons  = ['EFECTIVO'=>'💵','TARJETA'=>'💳','TRANSFERENCIA'=>'🏦','YAPE'=>'📱','PLIN'=>'📱'];
                        @endphp
                        <span class="badge bg-{{ $colors[$p->forma_pago] ?? 'secondary' }}" style="{{ $p->forma_pago=='YAPE' ? 'background:#7c3aed!important' : '' }}">
                            {{ ($icons[$p->forma_pago] ?? '') }} {{ $p->forma_pago }}
                        </span>
                    </td>
                    <td class="fw-bold {{ $p->estado_pago=='ANULADO' ? '' : 'text-success' }}">
                        S/ {{ number_format($p->total, 2) }}
                    </td>
                    <td>{!! $p->estado_badge !!}</td>
                    <td>
                        @if($p->sale)
                            <a href="{{ route('sales.show', $p->sale) }}" class="badge bg-secondary text-decoration-none">
                                {{ $p->sale->numero_comprobante }}
                            </a>
                        @endif
                    </td>
                    <td>@if($p->sale){!! $p->sale->estado_badge !!}@endif</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('payments.show', $p) }}" class="btn btn-xs btn-outline-primary" title="Ver" style="padding:2px 8px;font-size:0.75rem">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($p->sale)
                            <a href="{{ route('sales.pdf', $p->sale) }}" class="btn btn-xs btn-outline-danger" title="PDF" target="_blank" style="padding:2px 8px;font-size:0.75rem">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            @endif
                            @can('anular cobros')
                            @if($p->estado_pago !== 'ANULADO')
                            <button type="button" class="btn btn-xs btn-outline-secondary"
                                    style="padding:2px 8px;font-size:0.75rem"
                                    title="Anular"
                                    onclick="anularCobro({{ $p->id }})">
                                <i class="bi bi-x-circle"></i>
                            </button>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>No hay cobros para mostrar
                </td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="5" class="text-end">Total del período:</td>
                    <td class="text-success">S/ {{ number_format($payments->where('estado_pago','PAGADO')->sum('total'), 2) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer bg-white">{{ $payments->links() }}</div>
    @endif
</div>

<!-- Modal Anular -->
<div class="modal fade" id="modalAnular" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formAnular">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="bi bi-x-circle me-2"></i>Anular Cobro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Esta acción anulará el cobro y su comprobante asociado.</p>
                    <label class="form-label fw-semibold">Motivo de anulación <span class="text-danger">*</span></label>
                    <textarea name="motivo" class="form-control" rows="3" required placeholder="Ingrese el motivo..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>Anular Cobro</button>
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
