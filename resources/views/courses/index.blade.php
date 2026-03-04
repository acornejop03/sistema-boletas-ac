@extends('layouts.app')
@section('title', 'Cursos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Cursos</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-book me-2"></i>Cursos</h5>
    @can('crear cursos')
    <a href="{{ route('courses.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Nuevo Curso</a>
    @endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Buscar por nombre o código...">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button class="btn btn-sm btn-primary">Filtrar</button>
            <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>
        </div>
    </form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead><tr><th>Código</th><th>Curso</th><th>Categoría</th><th>Nivel</th><th class="text-end">Matrícula</th><th class="text-end">Pensión</th><th>IGV</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($courses as $c)
                <tr>
                    <td><code>{{ $c->codigo }}</code></td>
                    <td>
                        <div class="fw-semibold">{{ $c->nombre }}</div>
                        <small class="text-muted">{{ $c->duracion_meses }} mes(es)</small>
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $c->category->color ?? '#6b7280' }}">
                            {{ $c->category->nombre }}
                        </span>
                    </td>
                    <td>{!! $c->nivel_badge !!}</td>
                    <td class="text-end">S/ {{ number_format($c->precio_matricula,2) }}</td>
                    <td class="text-end fw-bold">S/ {{ number_format($c->precio_pension,2) }}</td>
                    <td>
                        @if($c->afecto_igv)
                            <span class="badge bg-warning text-dark">Gravado</span>
                        @else
                            <span class="badge bg-light text-dark border">Exonerado</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            @can('editar cursos')
                            <a href="{{ route('courses.edit', $c) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('eliminar cursos')
                            <form method="POST" action="{{ route('courses.destroy', $c) }}" onsubmit="return confirm('¿Eliminar curso {{ $c->nombre }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No hay cursos registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($courses->hasPages())
    <div class="card-footer bg-white">{{ $courses->links() }}</div>
    @endif
</div>
@endsection
