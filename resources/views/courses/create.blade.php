@extends('layouts.app')
@section('title', 'Nuevo Curso')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection
@section('content')

<div class="page-header">
    <div>
        <h5><i class="bi bi-book text-primary"></i> Nuevo Curso</h5>
        <div class="page-subtitle">Registra un nuevo curso en el catálogo</div>
    </div>
    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-9">
<form method="POST" action="{{ route('courses.store') }}">
@csrf

{{-- Sección 1: Identificación --}}
<div class="card mb-3">
    <div class="card-hdr">
        <div class="card-hdr-icon blue"><i class="bi bi-card-text"></i></div>
        <div>
            <div class="card-hdr-title">Identificación del Curso</div>
            <div class="card-hdr-sub">Datos básicos de clasificación</div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Categoría <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">Seleccionar categoría...</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Código <span class="text-danger">*</span></label>
                <input type="text" name="codigo" value="{{ old('codigo') }}"
                       class="form-control @error('codigo') is-invalid @enderror"
                       placeholder="Ej: MAT-001" required>
                @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Nivel <span class="text-danger">*</span></label>
                <select name="nivel" class="form-select" required>
                    @foreach(['BASICO'=>'Básico','INTERMEDIO'=>'Intermedio','AVANZADO'=>'Avanzado'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('nivel','BASICO')==$val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-9">
                <label class="form-label">Nombre del curso <span class="text-danger">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       class="form-control @error('nombre') is-invalid @enderror"
                       placeholder="Ej: Matemáticas Básico" required>
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Duración (meses)</label>
                <input type="number" name="duracion_meses" value="{{ old('duracion_meses',1) }}"
                       class="form-control" min="1">
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="2"
                          placeholder="Descripción breve del curso (opcional)">{{ old('descripcion') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Sección 2: Precios --}}
<div class="card mb-3">
    <div class="card-hdr">
        <div class="card-hdr-icon green"><i class="bi bi-cash-coin"></i></div>
        <div>
            <div class="card-hdr-title">Precios y Tarifas</div>
            <div class="card-hdr-sub">Montos en Soles (S/)</div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Precio matrícula S/ <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold">S/</span>
                    <input type="number" name="precio_matricula" value="{{ old('precio_matricula',0) }}"
                           class="form-control" step="0.01" min="0" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio pensión S/ <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold">S/</span>
                    <input type="number" name="precio_pension" value="{{ old('precio_pension') }}"
                           class="form-control @error('precio_pension') is-invalid @enderror" step="0.01" min="0" required>
                </div>
                @error('precio_pension')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio materiales S/</label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold">S/</span>
                    <input type="number" name="precio_materiales" value="{{ old('precio_materiales',0) }}"
                           class="form-control" step="0.01" min="0">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección 3: Tributario --}}
<div class="card mb-3">
    <div class="card-hdr">
        <div class="card-hdr-icon purple"><i class="bi bi-file-earmark-text"></i></div>
        <div>
            <div class="card-hdr-title">Configuración Tributaria</div>
            <div class="card-hdr-sub">Para emisión de comprobantes SUNAT</div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <label class="form-label">Tipo afectación IGV</label>
                <select name="tipo_afectacion_igv" class="form-select" id="tipoAfeIgv">
                    <option value="20" {{ old('tipo_afectacion_igv','20')=='20' ? 'selected' : '' }}>20 — Exonerado (Educación)</option>
                    <option value="10" {{ old('tipo_afectacion_igv')=='10' ? 'selected' : '' }}>10 — Gravado con IGV</option>
                    <option value="30" {{ old('tipo_afectacion_igv')=='30' ? 'selected' : '' }}>30 — Inafecto</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end pb-1">
                <div class="form-check" style="margin-top:1.75rem">
                    <input type="hidden" name="afecto_igv" value="0">
                    <input class="form-check-input" type="checkbox" name="afecto_igv" id="afectoIgv"
                           value="1" {{ old('afecto_igv') ? 'checked' : '' }}>
                    <label class="form-check-label" for="afectoIgv">¿Afecto a IGV (18%)?</label>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-3 py-2 mb-0" style="font-size:0.8rem">
            <i class="bi bi-info-circle me-2"></i>
            Los cursos de educación generalmente son <strong>Exonerados (código 20)</strong> según la normativa SUNAT.
        </div>
    </div>
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-x me-1"></i>Cancelar
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check2 me-1"></i>Guardar Curso
    </button>
</div>

</form>
</div>
</div>
@endsection
