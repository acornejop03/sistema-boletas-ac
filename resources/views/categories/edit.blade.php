@extends('layouts.app')
@section('title', 'Editar Categoría')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card">
    <div class="card-header bg-warning text-dark fw-semibold"><i class="bi bi-pencil me-2"></i>Editar: {{ $category->nombre }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label small fw-semibold">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre',$category->nombre) }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion',$category->descripcion) }}" class="form-control"></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Color</label>
                <input type="color" name="color" value="{{ old('color',$category->color) }}" class="form-control form-control-color"></div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-check2 me-1"></i>Actualizar</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
