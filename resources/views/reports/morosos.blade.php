@extends('layouts.app')
@section('title', 'Pensiones Pendientes')
@section('breadcrumb')
    <li class="breadcrumb-item">Reportes</li>
    <li class="breadcrumb-item active">Morosos</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Pensiones Pendientes</h5>
        <div class="page-subtitle">Alumnos activos que no han pagado en el periodo</div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4 col-md-3">
                <label class="form-label">Periodo</label>
                <input type="month" name="periodo" value="{{ $periodo }}" class="form-control form-control-sm">
            </div>
            <div class="col-sm-4 col-md-2">
                <label class="form-label d-none d-sm-block">&nbsp;</label>
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Consultar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <span class="fw-semibold">
            <i class="bi bi-calendar-x me-2 text-warning"></i>Sin pago de pensión — {{ $periodo }}
        </span>
        <span class="badge {{ $morosos->count() > 0 ? 'bg-warning text-dark' : 'bg-success' }}">
            {{ $morosos->count() }} {{ $morosos->count() == 1 ? 'alumno' : 'alumnos' }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Alumno</th><th>Doc.</th><th>Curso</th><th>Turno</th><th>Contacto</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($morosos as $e)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $e->student->nombre_completo }}</div>
                        <small class="text-muted"><code>{{ $e->student->codigo }}</code></small>
                    </td>
                    <td class="small text-muted">{{ $e->student->numero_documento ?? '—' }}</td>
                    <td>{{ $e->course->nombre }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $e->turno }}</span></td>
                    <td class="small">{{ $e->student->telefono ?? $e->student->telefono_apoderado ?? '—' }}</td>
                    <td>
                        @can('crear cobros')
                        <a href="{{ route('payments.create', ['student_id' => $e->student_id]) }}"
                           class="btn btn-sm btn-success">
                            <i class="bi bi-cash-coin me-1"></i>Cobrar
                        </a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5">
                    <i class="bi bi-check-circle-fill text-success d-block" style="font-size:3rem;margin-bottom:0.5rem"></i>
                    <div class="fw-semibold text-success">¡Todos al día!</div>
                    <div class="text-muted small">Todos los alumnos han pagado su pensión de {{ $periodo }}</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
