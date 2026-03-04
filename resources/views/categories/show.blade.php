@extends('layouts.app')
@section('title', $category->nombre)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
    <li class="breadcrumb-item active">{{ $category->nombre }}</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        <span style="color:{{ $category->color }}">{{ $category->icono }}</span>
        {{ $category->nombre }}
    </h5>
    <div class="d-flex gap-2">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="rounded mb-3 mx-auto" style="width:64px;height:64px;background:{{ $category->color }}"></div>
                <h5 class="mb-1">{{ $category->nombre }}</h5>
                <p class="text-muted small">{{ $category->descripcion ?? 'Sin descripción' }}</p>
                <span class="badge {{ $category->activo ? 'bg-success' : 'bg-secondary' }}">
                    {{ $category->activo ? 'Activa' : 'Inactiva' }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-book me-2"></i>Cursos en esta categoría ({{ $category->courses->count() }})
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead><tr><th>Código</th><th>Nombre</th><th>Nivel</th><th>Pensión</th><th>Estado</th></tr></thead>
                    <tbody>
                        @forelse($category->courses as $course)
                        <tr>
                            <td><code>{{ $course->codigo }}</code></td>
                            <td>
                                <a href="{{ route('courses.show', $course) }}" class="text-decoration-none fw-semibold">
                                    {{ $course->nombre }}
                                </a>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $course->nivel }}</span></td>
                            <td>S/ {{ number_format($course->precio_pension, 2) }}</td>
                            <td>
                                <span class="badge {{ $course->activo ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $course->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sin cursos en esta categoría</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
