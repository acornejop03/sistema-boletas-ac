@extends('layouts.app')
@section('title', 'Nueva Matrícula')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
<div class="card">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-clipboard-plus me-2"></i>Nueva Matrícula</div>
    <div class="card-body">
        <form method="POST" action="{{ route('enrollments.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label small fw-semibold">Alumno *</label>
                    <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                        <option value="">Seleccionar alumno...</option>
                        @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ old('student_id')==$s->id?'selected':'' }}>
                            {{ $s->nombre_completo }} — {{ $s->codigo }}
                        </option>
                        @endforeach
                    </select>
                    @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Curso *</label>
                    <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                        <option value="">Seleccionar curso...</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}>
                            {{ $c->nombre }} — S/ {{ number_format($c->precio_pension,2) }}/mes
                        </option>
                        @endforeach
                    </select>
                    @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Periodo * <small class="text-muted">(YYYY-MM)</small></label>
                    <input type="month" name="periodo" value="{{ old('periodo', now()->format('Y-m')) }}" class="form-control @error('periodo') is-invalid @enderror" required>
                    @error('periodo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha de matrícula *</label>
                    <input type="date" name="fecha_matricula" value="{{ old('fecha_matricula', now()->toDateString()) }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Turno *</label>
                    <select name="turno" class="form-select" required>
                        @foreach(['MAÑANA','TARDE','NOCHE'] as $t)
                        <option value="{{ $t }}" {{ old('turno','MAÑANA')==$t?'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha fin</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="alert alert-info mt-3 small">
                <i class="bi bi-info-circle me-2"></i>
                Si el curso tiene precio de matrícula, se generará automáticamente un cobro al guardar.
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Matricular Alumno</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
