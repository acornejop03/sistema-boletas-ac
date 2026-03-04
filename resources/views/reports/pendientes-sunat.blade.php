@extends('layouts.app')
@section('title', 'Pendientes SUNAT')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pendientes SUNAT</li>
@endsection
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-cloud-slash text-danger me-2"></i>Comprobantes Pendientes SUNAT</h5>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead><tr><th>Comprobante</th><th>Alumno</th><th>Fecha</th><th>Total</th><th>Emisor</th><th></th></tr></thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td><code>{{ $sale->numero_comprobante }}</code></td>
                    <td>{{ $sale->student->nombre_completo }}</td>
                    <td>{{ $sale->fecha_emision->format('d/m/Y') }}</td>
                    <td class="fw-bold text-success">S/ {{ number_format($sale->mto_imp_venta,2) }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>
                        @can('reenviar comprobantes')
                        <form method="POST" action="{{ route('sales.reenviar', $sale) }}">
                            @csrf
                            <button class="btn btn-xs btn-warning" onclick="return confirm('¿Reenviar a SUNAT?')" style="padding:2px 8px;font-size:0.75rem">
                                <i class="bi bi-arrow-repeat"></i> Reenviar
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                    ¡Todo sincronizado! No hay comprobantes pendientes.
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
