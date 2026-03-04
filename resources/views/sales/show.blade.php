@extends('layouts.app')
@section('title', 'Comprobante ' . $sale->numero_comprobante)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Comprobantes</a></li>
    <li class="breadcrumb-item active">{{ $sale->numero_comprobante }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold fs-5">{{ $sale->numero_comprobante }}</span>
                    <span class="badge {{ $sale->tipo_comprobante=='03'?'bg-primary':'bg-dark' }} ms-2">{{ $sale->tipo_nombre }}</span>
                </div>
                {!! $sale->estado_badge !!}
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6"><strong class="text-muted small d-block">Alumno</strong>
                        <a href="{{ route('students.show', $sale->student) }}">{{ $sale->student->nombre_completo }}</a>
                        <div class="small text-muted">{{ $sale->student->tipo_doc_nombre }}: {{ $sale->student->numero_documento }}</div>
                    </div>
                    <div class="col-md-3"><strong class="text-muted small d-block">Fecha emisión</strong>{{ $sale->fecha_emision->format('d/m/Y') }}</div>
                    <div class="col-md-3"><strong class="text-muted small d-block">Moneda</strong>{{ $sale->moneda }}</div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm">
                        <thead class="table-light"><tr><th>Descripción</th><th class="text-center">Cant</th><th class="text-end">V.Unit</th><th class="text-end">Total</th><th class="text-center">Afecc.</th></tr></thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $item->descripcion }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end">{{ number_format($item->valor_unitario, 2) }}</td>
                                <td class="text-end fw-semibold">S/ {{ number_format($item->total, 2) }}</td>
                                <td class="text-center"><small class="badge bg-light text-dark">{{ $item->tipo_afectacion_igv }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-5">
                        @if($sale->mto_oper_exoneradas > 0)
                        <div class="d-flex justify-content-between"><span class="text-muted small">Op. Exoneradas</span><span>S/ {{ number_format($sale->mto_oper_exoneradas,2) }}</span></div>
                        @endif
                        @if($sale->mto_oper_gravadas > 0)
                        <div class="d-flex justify-content-between"><span class="text-muted small">Op. Gravadas</span><span>S/ {{ number_format($sale->mto_oper_gravadas,2) }}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted small">IGV (18%)</span><span>S/ {{ number_format($sale->mto_igv,2) }}</span></div>
                        @endif
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2">
                            <span class="text-success">TOTAL</span>
                            <span class="text-success">S/ {{ number_format($sale->mto_imp_venta,2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($sale->sunatResponses->count() > 0)
        <div class="card">
            <div class="card-header bg-white fw-semibold small"><i class="bi bi-clock-history me-2"></i>Historial de envíos SUNAT</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 small">
                    <thead><tr><th>Fecha</th><th>Acción</th><th>Código</th><th>Descripción</th><th>Resultado</th></tr></thead>
                    <tbody>
                        @foreach($sale->sunatResponses->sortByDesc('created_at') as $r)
                        <tr>
                            <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $r->accion }}</td>
                            <td>{{ $r->codigo_respuesta ?? '—' }}</td>
                            <td>{{ $r->descripcion_respuesta ?? '—' }}</td>
                            <td><span class="badge {{ $r->exitoso?'bg-success':'bg-danger' }}">{{ $r->exitoso?'OK':'Error' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold small">Acciones</div>
            <div class="card-body d-grid gap-2">
                @can('descargar pdf')
                <a href="{{ route('sales.pdf', $sale) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i>Descargar PDF
                </a>
                @endcan
                @can('descargar xml')
                @if($sale->ruta_xml)
                <a href="{{ route('sales.xml', $sale) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-file-code me-2"></i>Descargar XML
                </a>
                @endif
                @endcan
                @can('reenviar comprobantes')
                @if($sale->estado_sunat !== 'ACEPTADO')
                <form method="POST" action="{{ route('sales.reenviar', $sale) }}">
                    @csrf
                    <button class="btn btn-warning w-100" onclick="return confirm('¿Reenviar a SUNAT?')">
                        <i class="bi bi-arrow-repeat me-2"></i>Reenviar a SUNAT
                    </button>
                </form>
                @endif
                @endcan
                @if($sale->payment)
                <a href="{{ route('payments.show', $sale->payment) }}" class="btn btn-outline-primary">
                    <i class="bi bi-cash-coin me-2"></i>Ver cobro relacionado
                </a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white fw-semibold small">Estado SUNAT</div>
            <div class="card-body text-center">
                <div class="mb-2 fs-1">
                    @if($sale->estado_sunat === 'ACEPTADO') ✅
                    @elseif($sale->estado_sunat === 'PENDIENTE') ⏳
                    @elseif($sale->estado_sunat === 'RECHAZADO') ❌
                    @else 🚫
                    @endif
                </div>
                {!! $sale->estado_badge !!}
                @if($sale->sunat_descripcion)
                <div class="mt-2 small text-muted">{{ $sale->sunat_descripcion }}</div>
                @endif
                @if($sale->hash_cpe)
                <div class="mt-2 small text-muted text-break"><strong>Hash:</strong><br>{{ $sale->hash_cpe }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
