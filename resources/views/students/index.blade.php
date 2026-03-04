@extends('layouts.app')
@section('title', 'Alumnos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Alumnos</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-person-badge text-primary"></i> Alumnos</h5>
        <div class="page-subtitle">Gestión de alumnos registrados en el sistema</div>
    </div>
    <div class="page-header-actions">
        @can('crear alumnos')
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>Nuevo Alumno
        </a>
        @endcan
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       placeholder="Buscar por nombre, DNI o código...">
            </div>
        </div>
        <div class="col-md-3">
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado')=='activo'   ? 'selected' : '' }}>Activos</option>
                <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>
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
                    <th>Matrículas</th>
                    <th>Estado</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                 style="width:36px;height:36px;background:var(--brand-secondary);font-size:0.85rem">
                                {{ strtoupper(substr($s->apellido_paterno, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $s->nombre_completo }}</div>
                                @if($s->email)<div class="text-muted" style="font-size:0.76rem">{{ $s->email }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border" style="font-weight:500">{{ $s->tipo_doc_nombre }}</span>
                        <div class="text-muted" style="font-size:0.78rem;margin-top:2px">{{ $s->numero_documento ?? '—' }}</div>
                    </td>
                    <td><code class="text-primary fw-semibold">{{ $s->codigo }}</code></td>
                    <td class="text-muted" style="font-size:0.84rem">{{ $s->telefono ?? '—' }}</td>
                    <td>
                        @php $active = $s->enrollments->where('estado','ACTIVO')->count(); @endphp
                        @if($active > 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $active }} activo{{ $active>1?'s':'' }}</span>
                        @else
                            <span class="text-muted" style="font-size:0.8rem">Sin matrícula</span>
                        @endif
                    </td>
                    <td>
                        @if($s->activo)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('students.show', $s) }}" class="btn-act blue" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('editar alumnos')
                            <a href="{{ route('students.edit', $s) }}" class="btn-act slate" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('crear cobros')
                            <a href="{{ route('payments.create', ['student_id' => $s->id]) }}" class="btn-act green" title="Registrar cobro">
                                <i class="bi bi-cash-coin"></i>
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div style="font-size:2.5rem;color:#e2e8f0"><i class="bi bi-person-x"></i></div>
                        <div class="text-muted mt-2">No se encontraron alumnos</div>
                        @can('crear alumnos')
                        <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary mt-3">
                            <i class="bi bi-person-plus me-1"></i>Registrar primer alumno
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="card-footer">{{ $students->links() }}</div>
    @endif
</div>
@endsection
