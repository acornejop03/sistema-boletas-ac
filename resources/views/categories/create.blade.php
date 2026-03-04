@extends('layouts.app')
@section('title', 'Nueva Categoría')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-tag me-2"></i>Nueva Categoría</div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label small fw-semibold">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror" required>
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label small fw-semibold">Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion') }}" class="form-control"></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Color</label>
                <input type="color" name="color" value="{{ old('color','#3B82F6') }}" class="form-control form-control-color"></div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Guardar</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
