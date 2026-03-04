@extends('layouts.app')
@section('title', 'Categorías')
@section('breadcrumb')
    <li class="breadcrumb-item active">Categorías</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-tags me-2"></i>Categorías de Cursos</h5>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Nueva Categoría</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Color</th><th>Nombre</th><th>Descripción</th><th class="text-end">Cursos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td><div class="rounded" style="width:32px;height:32px;background:{{ $cat->color }}"></div></td>
                    <td class="fw-semibold">{{ $cat->nombre }}</td>
                    <td class="text-muted small">{{ $cat->descripcion ?? '—' }}</td>
                    <td class="text-end"><span class="badge bg-secondary">{{ $cat->courses_count }}</span></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('categories.edit', $cat) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No hay categorías</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
