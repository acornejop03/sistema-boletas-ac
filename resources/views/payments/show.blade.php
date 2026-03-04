@extends('layouts.app')
@section('title', 'Detalle de Cobro')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Cobros</a></li>
    <li class="breadcrumb-item active">#{{ $payment->id }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                <span><i class="bi bi-receipt me-2"></i>Cobro #{{ $payment->id }}</span>
                {!! $payment->estado_badge !!}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong class="text-muted small d-block">Alumno</strong>
                        <a href="{{ route('students.show', $payment->student) }}">{{ $payment->student->nombre_completo }}</a>
                    </div>
                    <div class="col-md-6"><strong class="text-muted small d-block">Fecha de pago</strong>{{ $payment->fecha_pago->format('d/m/Y') }}</div>
                    <div class="col-md-6"><strong class="text-muted small d-block">Tipo de cobro</strong>{{ $payment->tipo_pago }}</div>
                    @if($payment->periodo_pago)
                    <div class="col-md-6"><strong class="text-muted small d-block">Periodo</strong>{{ $payment->periodo_pago }}</div>
                    @endif
                    @if($payment->enrollment)
                    <div class="col-md-6"><strong class="text-muted small d-block">Curso</strong>{{ $payment->enrollment->course->nombre }}</div>
                    @endif
                    <div class="col-md-6"><strong class="text-muted small d-block">Forma de pago</strong>{{ $payment->forma_pago_icon }}</div>
                    @if($payment->numero_operacion)
                    <div class="col-md-6"><strong class="text-muted small d-block">N° Operación</strong>{{ $payment->numero_operacion }}</div>
                    @endif
                    <div class="col-md-4"><strong class="text-muted small d-block">Subtotal</strong>S/ {{ number_format($payment->subtotal, 2) }}</div>
                    <div class="col-md-4"><strong class="text-muted small d-block">IGV</strong>S/ {{ number_format($payment->igv, 2) }}</div>
                    <div class="col-md-4"><strong class="text-muted small d-block">Total</strong><span class="fw-bold text-success fs-5">S/ {{ number_format($payment->total, 2) }}</span></div>
                    @if($payment->observaciones)
                    <div class="col-12"><strong class="text-muted small d-block">Observaciones</strong>{{ $payment->observaciones }}</div>
                    @endif
                    <div class="col-md-6"><strong class="text-muted small d-block">Cajero</strong>{{ $payment->user->name }}</div>
                </div>
            </div>
        </div>

        @can('anular cobros')
        @if($payment->estado_pago !== 'ANULADO')
        <div class="card border-danger">
            <div class="card-header bg-danger text-white fw-semibold">Zona de peligro</div>
            <div class="card-body">
                <form method="POST" action="{{ route('payments.anular', $payment) }}">
                    @csrf
                    <label class="form-label small fw-semibold">Motivo de anulación *</label>
                    <div class="input-group">
                        <input type="text" name="motivo" class="form-control" placeholder="Motivo requerido..." required>
                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Confirma la anulación de este cobro?')">
                            <i class="bi bi-x-circle me-1"></i>Anular
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endcan
    </div>

    <div class="col-md-5">
        @if($payment->sale)
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text me-2"></i>{{ $payment->sale->tipo_nombre }}</span>
                {!! $payment->sale->estado_badge !!}
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-6 fw-bold text-primary">{{ $payment->sale->numero_comprobante }}</div>
                    <div class="text-muted">{{ $payment->sale->fecha_emision->format('d/m/Y') }}</div>
                </div>
                @if($payment->sale->sunat_descripcion)
                <div class="alert alert-{{ $payment->sale->estado_sunat === 'ACEPTADO' ? 'success' : 'warning' }} py-2 small">
                    {{ $payment->sale->sunat_descripcion }}
                </div>
                @endif
                <div class="d-flex gap-2">
                    @can('descargar pdf')
                    <a href="{{ route('sales.pdf', $payment->sale) }}" class="btn btn-outline-danger flex-1" target="_blank">
                        <i class="bi bi-file-pdf me-1"></i>Ver PDF
                    </a>
                    @endcan
                    @can('descargar xml')
                    <a href="{{ route('sales.xml', $payment->sale) }}" class="btn btn-outline-secondary flex-1">
                        <i class="bi bi-file-code me-1"></i>XML
                    </a>
                    @endcan
                    @can('reenviar comprobantes')
                    @if($payment->sale->estado_sunat !== 'ACEPTADO')
                    <form method="POST" action="{{ route('sales.reenviar', $payment->sale) }}">
                        @csrf
                        <button class="btn btn-outline-warning" onclick="return confirm('¿Reenviar a SUNAT?')">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>
        </div>

        @if($payment->sale->sunatResponses->count() > 0)
        <div class="card">
            <div class="card-header bg-white fw-semibold small">
                <i class="bi bi-clock-history me-2"></i>Historial SUNAT
            </div>
            <div class="list-group list-group-flush">
                @foreach($payment->sale->sunatResponses->sortByDesc('created_at') as $r)
                <div class="list-group-item py-2">
                    <div class="d-flex justify-content-between">
                        <span class="badge {{ $r->exitoso ? 'bg-success' : 'bg-danger' }}">{{ $r->exitoso ? 'OK' : 'Error' }}</span>
                        <small class="text-muted">{{ $r->created_at->format('d/m H:i') }}</small>
                    </div>
                    <div class="small text-muted mt-1">{{ $r->descripcion_respuesta ?? '—' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
