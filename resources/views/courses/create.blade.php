@extends('layouts.app')
@section('title', 'Nuevo Curso')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-10">
<div class="card">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-book me-2"></i>Nuevo Curso</div>
    <div class="card-body">
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label small fw-semibold">Categoría *</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Seleccionar...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>@error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-3"><label class="form-label small fw-semibold">Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control @error('codigo') is-invalid @enderror" required placeholder="MAT-001">
                    @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-3"><label class="form-label small fw-semibold">Nivel *</label>
                    <select name="nivel" class="form-select" required>
                        @foreach(['BASICO','INTERMEDIO','AVANZADO'] as $n)
                        <option value="{{ $n }}" {{ old('nivel','BASICO')==$n?'selected':'' }}>{{ ucfirst(strtolower($n)) }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-9"><label class="form-label small fw-semibold">Nombre del curso *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror" required placeholder="Ej: Matemáticas Básico">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-3"><label class="form-label small fw-semibold">Duración (meses)</label>
                    <input type="number" name="duracion_meses" value="{{ old('duracion_meses',1) }}" class="form-control" min="1"></div>
                <div class="col-md-12"><label class="form-label small fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion') }}</textarea></div>
                <div class="col-md-4"><label class="form-label small fw-semibold">Precio matrícula S/ *</label>
                    <input type="number" name="precio_matricula" value="{{ old('precio_matricula',0) }}" class="form-control" step="0.01" min="0" required></div>
                <div class="col-md-4"><label class="form-label small fw-semibold">Precio pensión S/ *</label>
                    <input type="number" name="precio_pension" value="{{ old('precio_pension') }}" class="form-control @error('precio_pension') is-invalid @enderror" step="0.01" min="0" required>
                    @error('precio_pension')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label small fw-semibold">Precio materiales S/</label>
                    <input type="number" name="precio_materiales" value="{{ old('precio_materiales',0) }}" class="form-control" step="0.01" min="0"></div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Tipo afectación IGV</label>
                    <select name="tipo_afectacion_igv" class="form-select" id="tipoAfeIgv">
                        <option value="20" {{ old('tipo_afectacion_igv','20')=='20'?'selected':'' }}>20 - Exonerado (Educación)</option>
                        <option value="10" {{ old('tipo_afectacion_igv')=='10'?'selected':'' }}>10 - Gravado con IGV</option>
                        <option value="30" {{ old('tipo_afectacion_igv')=='30'?'selected':'' }}>30 - Inafecto</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="hidden" name="afecto_igv" value="0">
                        <input class="form-check-input" type="checkbox" name="afecto_igv" id="afectoIgv" value="1" {{ old('afecto_igv') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="afectoIgv">¿Afecto a IGV?</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Guardar Curso</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
