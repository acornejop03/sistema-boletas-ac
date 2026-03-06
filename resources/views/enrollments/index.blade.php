@extends('layouts.app')
@section('title', 'Matrículas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Matrículas</li>
@endsection
@section('content')

<div class="page-header">
    <div>
        <h5><i class="bi bi-clipboard-check text-primary"></i> Matrículas</h5>
        <div class="page-subtitle">Control de inscripciones de alumnos a cursos</div>
    </div>
    <div class="page-header-actions">
        @can('crear matriculas')
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nueva Matrícula
        </a>
        @endcan
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Buscar alumno...">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="course_id" class="form-select form-select-sm">
                <option value="">Todos los cursos</option>
                @foreach($courses as $c)
                <option value="{{ $c->id }}" {{ request('course_id')==$c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                @foreach(['ACTIVO','CULMINADO','RETIRADO','SUSPENDIDO'] as $est)
                <option value="{{ $est }}" {{ request('estado')==$est ? 'selected' : '' }}>{{ $est }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <input type="month" name="periodo" value="{{ request('periodo') }}"
                   class="form-control form-control-sm" title="Periodo">
        </div>
        <div class="col-6 col-md-auto d-flex gap-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon purple"><i class="bi bi-clipboard-check"></i></div>
        <div>
            <div class="card-hdr-title">Listado de Matrículas</div>
            <div class="card-hdr-sub">{{ $enrollments->total() }} matrícula(s) encontrada(s)</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Curso</th>
                    <th class="d-none d-md-table-cell">Periodo</th>
                    <th class="d-none d-lg-table-cell">Turno</th>
                    <th class="d-none d-lg-table-cell">F. Matrícula</th>
                    <th>Estado</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $e)
                <tr>
                    <td>
                        <a href="{{ route('students.show', $e->student_id) }}"
                           class="text-decoration-none">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                     style="width:34px;height:34px;background:var(--brand-secondary);font-size:0.8rem">
                                    {{ strtoupper(substr($e->student->apellido_paterno ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $e->student->nombre_completo }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">{{ $e->student->codigo }}</div>
                                </div>
                            </div>
                        </a>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:0.84rem">{{ $e->course->nombre }}</div>
                        <div class="text-muted" style="font-size:0.72rem">{{ $e->course->codigo }}</div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-light text-dark border" style="font-size:0.75rem">{{ $e->periodo }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell text-muted" style="font-size:0.83rem">{{ $e->turno }}</td>
                    <td class="d-none d-lg-table-cell text-muted" style="font-size:0.83rem">{{ $e->fecha_matricula->format('d/m/Y') }}</td>
                    <td>{!! $e->estado_badge !!}</td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('enrollments.show', $e) }}" class="btn-act blue" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('editar matriculas')
                            <a href="{{ route('enrollments.edit', $e) }}" class="btn-act slate" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('crear cobros')
                            <a href="{{ route('payments.create', ['student_id' => $e->student_id]) }}"
                               class="btn-act green" title="Registrar cobro">
                                <i class="bi bi-cash-coin"></i>
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div style="font-size:2.5rem;color:#e2e8f0"><i class="bi bi-clipboard-x"></i></div>
                        <div class="text-muted mt-2">No se encontraron matrículas</div>
                        @can('crear matriculas')
                        <a href="{{ route('enrollments.create') }}" class="btn btn-sm btn-primary mt-3">
                            <i class="bi bi-plus-circle me-1"></i>Crear matrícula
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
        {{ $enrollments->links() }}
    </div>
    @endif
</div>
@endsection
