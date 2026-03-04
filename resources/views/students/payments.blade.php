@extends('layouts.app')
@section('title', 'Pagos del Alumno')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->codigo }}</a></li>
    <li class="breadcrumb-item active">Pagos</li>
@endsection
@section('content')
<h5 class="fw-bold mb-3">Pagos de {{ $student->nombre_completo }}</h5>
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 small">
            <thead><tr><th>Fecha</th><th>Tipo</th><th>Curso</th><th>Periodo</th><th>Forma</th><th>Total</th><th>Estado</th><th>Comprobante</th></tr></thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                    <td>{{ $p->tipo_pago }}</td>
                    <td>{{ $p->enrollment?->course?->nombre ?? '—' }}</td>
                    <td>{{ $p->periodo_pago ?? '—' }}</td>
                    <td>{{ $p->forma_pago }}</td>
                    <td class="fw-bold text-success">S/ {{ number_format($p->total,2) }}</td>
                    <td>{!! $p->estado_badge !!}</td>
                    <td>
                        @if($p->sale)
                        <a href="{{ route('sales.pdf', $p->sale) }}" class="badge bg-secondary text-decoration-none" target="_blank">{{ $p->sale->numero_comprobante }}</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Sin pagos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer bg-white">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
