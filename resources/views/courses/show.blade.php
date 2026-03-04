@extends('layouts.app')
@section('title', $course->nombre)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
    <li class="breadcrumb-item active">{{ $course->nombre }}</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5>
            <span class="badge me-2" style="background:{{ $course->category->color ?? '#3b82f6' }}">
                {{ $course->category->icono ?? '' }}
            </span>
            {{ $course->nombre }}
            <span class="badge bg-light text-dark border ms-1" style="font-size:0.75rem">{{ $course->nivel }}</span>
        </h5>
        <div class="page-subtitle">{{ $course->codigo }} — {{ $course->category->nombre }}</div>
    </div>
    <div class="d-flex gap-2">
        @can('editar cursos')
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endcan
        @can('crear matriculas')
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-clipboard-plus me-1"></i>Nueva Matrícula
        </a>
        @endcan
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Información</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small mb-1">Descripción</div>
                    <div>{{ $course->descripcion ?? 'Sin descripción' }}</div>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold">{{ $course->duracion_meses }} mes(es)</div>
                            <div class="text-muted" style="font-size:0.72rem">Duración</div>
                        </div>
                    </div>
                    @if($course->duracion_horas)
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold">{{ $course->duracion_horas }}h</div>
                            <div class="text-muted" style="font-size:0.72rem">Horas totales</div>
                        </div>
                    </div>
                    @endif
                    @if($course->max_alumnos)
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold">{{ $course->max_alumnos }}</div>
                            <div class="text-muted" style="font-size:0.72rem">Máx. alumnos</div>
                        </div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold">{{ $course->enrollments->count() }}</div>
                            <div class="text-muted" style="font-size:0.72rem">Matriculados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-currency-dollar me-2 text-success"></i>Precios</div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Matrícula</span>
                    <span class="fw-bold">S/ {{ number_format($course->precio_matricula, 2) }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Pensión mensual</span>
                    <span class="fw-bold text-success fs-5">S/ {{ number_format($course->precio_pension, 2) }}</span>
                </div>
                @if($course->precio_materiales > 0)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Materiales</span>
                    <span class="fw-bold">S/ {{ number_format($course->precio_materiales, 2) }}</span>
                </div>
                @endif
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Afecto a IGV</span>
                    <span class="badge {{ $course->afecto_igv ? 'bg-warning text-dark' : 'bg-success' }}">
                        {{ $course->afecto_igv ? 'Gravado ('.$course->tipo_afectacion_igv.')' : 'Exonerado' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                <span><i class="bi bi-people me-2 text-primary"></i>Alumnos matriculados ({{ $course->enrollments->count() }})</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Alumno</th><th>Código</th><th>Periodo</th><th>Turno</th><th>Estado</th></tr></thead>
                    <tbody>
                        @forelse($course->enrollments as $e)
                        <tr>
                            <td>
                                <a href="{{ route('students.show', $e->student) }}" class="fw-semibold text-decoration-none text-dark">
                                    {{ $e->student->nombre_completo }}
                                </a>
                            </td>
                            <td><code>{{ $e->student->codigo }}</code></td>
                            <td>{{ $e->periodo }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $e->turno }}</span></td>
                            <td>{!! $e->estado_badge !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-person-x d-block fs-3 mb-1 opacity-25"></i>
                            Sin alumnos matriculados
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
