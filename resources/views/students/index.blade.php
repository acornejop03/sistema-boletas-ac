@extends('layouts.app')
@section('title', 'Alumnos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Alumnos</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Alumnos</h5>
    @can('crear alumnos')
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Nuevo Alumno
    </a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                           placeholder="Buscar por nombre, DNI o código...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado')=='activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100">Filtrar</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary w-100">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Documento</th>
                    <th>Código</th>
                    <th>Teléfono</th>
                    <th>Cursos activos</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                 style="width:36px;height:36px;background:#2d5a9b;font-size:0.85rem;flex-shrink:0">
                                {{ strtoupper(substr($s->apellido_paterno, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $s->nombre_completo }}</div>
                                @if($s->email)<small class="text-muted">{{ $s->email }}</small>@endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $s->tipo_doc_nombre }}</span>
                        <div class="small">{{ $s->numero_documento ?? '—' }}</div>
                    </td>
                    <td><code>{{ $s->codigo }}</code></td>
                    <td class="small">{{ $s->telefono ?? '—' }}</td>
                    <td>
                        @php $activeEnrollments = $s->enrollments->where('estado','ACTIVO')->count(); @endphp
                        @if($activeEnrollments > 0)
                            <span class="badge bg-success">{{ $activeEnrollments }} activo(s)</span>
                        @else
                            <span class="text-muted small">Sin matrícula</span>
                        @endif
                    </td>
                    <td>
                        @if($s->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('students.show', $s) }}" class="btn btn-outline-primary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('editar alumnos')
                            <a href="{{ route('students.edit', $s) }}" class="btn btn-outline-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('crear cobros')
                            <a href="{{ route('payments.create', ['student_id' => $s->id]) }}" class="btn btn-outline-success" title="Cobrar">
                                <i class="bi bi-cash-coin"></i>
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-person-x fs-1 d-block mb-2"></i>No se encontraron alumnos
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="card-footer bg-white">{{ $students->links() }}</div>
    @endif
</div>
@endsection
