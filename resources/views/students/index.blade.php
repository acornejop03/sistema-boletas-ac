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

{{-- STAT STRIPS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-left:4px solid #3b82f6!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#1d4ed8;line-height:1">{{ $stats['total'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Total alumnos</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#bfdbfe;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#1d4ed8">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-left:4px solid #22c55e!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#15803d;line-height:1">{{ $stats['activos'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Activos</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#bbf7d0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#15803d">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);border-left:4px solid #8b5cf6!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#6d28d9;line-height:1">{{ $stats['con_matricula'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Con matrícula</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#ddd6fe;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#6d28d9">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-left:4px solid #94a3b8!important">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:1.6rem;font-weight:800;color:#475569;line-height:1">{{ $stats['inactivos'] }}</div>
                        <div style="font-size:0.71rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;margin-top:3px">Inactivos</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:10px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#475569">
                        <i class="bi bi-person-dash-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTROS --}}
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       placeholder="Buscar por nombre, DNI o código...">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado')=='activo'   ? 'selected' : '' }}>✅ Activos</option>
                <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected' : '' }}>⛔ Inactivos</option>
            </select>
        </div>
        <div class="col-6 col-md-auto d-flex gap-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon blue"><i class="bi bi-person-badge"></i></div>
        <div>
            <div class="card-hdr-title">Directorio de Alumnos</div>
            <div class="card-hdr-sub">{{ $students->total() }} alumno(s) encontrado(s)</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th class="d-none d-sm-table-cell">Documento</th>
                    <th class="d-none d-md-table-cell">Código</th>
                    <th class="d-none d-md-table-cell">Teléfono</th>
                    <th class="d-none d-md-table-cell">Matrículas</th>
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
                                 style="width:38px;height:38px;background:var(--brand-secondary);font-size:0.85rem">
                                {{ strtoupper(substr($s->apellido_paterno, 0, 1)) }}{{ strtoupper(substr($s->nombres, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $s->nombre_completo }}</div>
                                @if($s->email)
                                <div class="text-muted" style="font-size:0.75rem"><i class="bi bi-envelope me-1"></i>{{ $s->email }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-sm-table-cell">
                        <span class="badge bg-light text-dark border" style="font-weight:500;font-size:0.7rem">{{ $s->tipo_doc_nombre }}</span>
                        <div class="text-muted" style="font-size:0.78rem;margin-top:2px;font-weight:600">{{ $s->numero_documento ?? '—' }}</div>
                    </td>
                    <td class="d-none d-md-table-cell"><code class="text-primary fw-semibold">{{ $s->codigo }}</code></td>
                    <td class="d-none d-md-table-cell text-muted" style="font-size:0.84rem">
                        @if($s->telefono)<i class="bi bi-phone me-1"></i>{{ $s->telefono }}@else —@endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        @php $active = $s->enrollments->where('estado','ACTIVO')->count(); @endphp
                        @if($active > 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-check-circle me-1"></i>{{ $active }} activo{{ $active>1?'s':'' }}
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Sin matrícula</span>
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
                        <div style="font-size:3rem;color:#e2e8f0"><i class="bi bi-person-x"></i></div>
                        <div class="fw-semibold text-muted mt-2">No se encontraron alumnos</div>
                        <div class="text-muted" style="font-size:0.8rem">Prueba con otros términos de búsqueda</div>
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
    <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
