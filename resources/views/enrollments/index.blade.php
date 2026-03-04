@extends('layouts.app')
@section('title', 'Matrículas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Matrículas</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-clipboard-check me-2"></i>Matrículas</h5>
    @can('crear matriculas')
    <a href="{{ route('enrollments.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Nueva Matrícula</a>
    @endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar alumno..."></div>
        <div class="col-md-3">
            <select name="course_id" class="form-select form-select-sm">
                <option value="">Todos los cursos</option>
                @foreach($courses as $c)
                <option value="{{ $c->id }}" {{ request('course_id')==$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach(['ACTIVO','CULMINADO','RETIRADO','SUSPENDIDO'] as $e)
                <option value="{{ $e }}" {{ request('estado')==$e?'selected':'' }}>{{ $e }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><input type="month" name="periodo" value="{{ request('periodo') }}" class="form-control form-control-sm"></div>
        <div class="col-md-2 d-flex gap-1">
            <button class="btn btn-sm btn-primary">Filtrar</button>
            <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>
        </div>
    </form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead><tr><th>Alumno</th><th>Curso</th><th>Periodo</th><th>Turno</th><th>F.Matrícula</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($enrollments as $e)
                <tr>
                    <td><a href="{{ route('students.show', $e->student_id) }}" class="text-decoration-none fw-semibold">{{ $e->student->nombre_completo }}</a></td>
                    <td>{{ $e->course->nombre }}</td>
                    <td>{{ $e->periodo }}</td>
                    <td>{{ $e->turno }}</td>
                    <td>{{ $e->fecha_matricula->format('d/m/Y') }}</td>
                    <td>{!! $e->estado_badge !!}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('enrollments.show', $e) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                            @can('editar matriculas')
                            <a href="{{ route('enrollments.edit', $e) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('crear cobros')
                            <a href="{{ route('payments.create', ['student_id' => $e->student_id]) }}" class="btn btn-outline-success" title="Cobrar"><i class="bi bi-cash-coin"></i></a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No hay matrículas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
    <div class="card-footer bg-white">{{ $enrollments->links() }}</div>
    @endif
</div>
@endsection
