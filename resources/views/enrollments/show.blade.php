@extends('layouts.app')
@section('title', 'Detalle Matrícula')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">#{{ $enrollment->id }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                <span>Matrícula #{{ $enrollment->id }}</span>
                {!! $enrollment->estado_badge !!}
            </div>
            <div class="card-body">
                <div class="mb-2"><strong class="text-muted small d-block">Alumno</strong>
                    <a href="{{ route('students.show', $enrollment->student) }}" class="fw-semibold">{{ $enrollment->student->nombre_completo }}</a>
                    <div class="text-muted small">{{ $enrollment->student->codigo }}</div>
                </div>
                <div class="mb-2"><strong class="text-muted small d-block">Curso</strong>{{ $enrollment->course->nombre }}</div>
                <div class="mb-2"><strong class="text-muted small d-block">Periodo</strong>{{ $enrollment->periodo }}</div>
                <div class="mb-2"><strong class="text-muted small d-block">Turno</strong>{{ $enrollment->turno }}</div>
                <div class="mb-2"><strong class="text-muted small d-block">Fecha matrícula</strong>{{ $enrollment->fecha_matricula->format('d/m/Y') }}</div>
                <div class="mb-2"><strong class="text-muted small d-block">Registró</strong>{{ $enrollment->user->name }}</div>
                @if($enrollment->observaciones)
                <div><strong class="text-muted small d-block">Observaciones</strong>{{ $enrollment->observaciones }}</div>
                @endif
            </div>
        </div>
        @can('crear cobros')
        <a href="{{ route('payments.create', ['student_id' => $enrollment->student_id]) }}" class="btn btn-success w-100">
            <i class="bi bi-cash-coin me-1"></i>Registrar Cobro
        </a>
        @endcan
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Historial de Pagos</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 small">
                    <thead><tr><th>Fecha</th><th>Tipo</th><th>Periodo</th><th>Total</th><th>SUNAT</th></tr></thead>
                    <tbody>
                        @forelse($enrollment->payments as $p)
                        <tr>
                            <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                            <td>{{ $p->tipo_pago }}</td>
                            <td>{{ $p->periodo_pago ?? '—' }}</td>
                            <td class="fw-bold text-success">S/ {{ number_format($p->total,2) }}</td>
                            <td>@if($p->sale){!! $p->sale->estado_badge !!}@endif</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sin pagos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
