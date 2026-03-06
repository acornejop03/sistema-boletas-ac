@extends('layouts.app')
@section('title', 'Editar Categoría')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorías</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')

<div class="page-header">
    <div>
        <h5><i class="bi bi-pencil text-primary"></i> Editar Categoría</h5>
        <div class="page-subtitle">Modificar datos de "{{ $category->nombre }}"</div>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-hdr">
                <div class="card-hdr-icon amber"><i class="bi bi-pencil-square"></i></div>
                <div>
                    <div class="card-hdr-title">Editar: {{ $category->nombre }}</div>
                    <div class="card-hdr-sub">Modifique los campos necesarios</div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre', $category->nombre) }}"
                               class="form-control @error('nombre') is-invalid @enderror" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion', $category->descripcion) }}"
                               class="form-control" placeholder="Descripción breve (opcional)">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Color identificador</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color" value="{{ old('color', $category->color) }}"
                                   class="form-control form-control-color" style="width:60px;height:40px">
                            <div class="text-muted" style="font-size:0.8rem">
                                Color actual: <strong>{{ $category->color }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end pt-2 border-top">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
