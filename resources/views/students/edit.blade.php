@extends('layouts.app')
@section('title', 'Editar Alumno')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->codigo }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-md-9">
<div class="card">
    <div class="card-header bg-warning text-dark fw-semibold">
        <i class="bi bi-pencil me-2"></i>Editar Alumno: {{ $student->nombre_completo }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Tipo de documento</label>
                    <select name="tipo_documento" class="form-select">
                        <option value="1" {{ $student->tipo_documento==1?'selected':'' }}>DNI</option>
                        <option value="6" {{ $student->tipo_documento==6?'selected':'' }}>RUC</option>
                        <option value="0" {{ $student->tipo_documento==0?'selected':'' }}>Sin documento</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Número de documento</label>
                    <input type="text" name="numero_documento" value="{{ old('numero_documento', $student->numero_documento) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $student->fecha_nacimiento?->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Apellido paterno *</label>
                    <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $student->apellido_paterno) }}" class="form-control @error('apellido_paterno') is-invalid @enderror" required>
                    @error('apellido_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Apellido materno *</label>
                    <input type="text" name="apellido_materno" value="{{ old('apellido_materno', $student->apellido_materno) }}" class="form-control @error('apellido_materno') is-invalid @enderror" required>
                    @error('apellido_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Nombres *</label>
                    <input type="text" name="nombres" value="{{ old('nombres', $student->nombres) }}" class="form-control @error('nombres') is-invalid @enderror" required>
                    @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $student->telefono) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Teléfono apoderado</label>
                    <input type="text" name="telefono_apoderado" value="{{ old('telefono_apoderado', $student->telefono_apoderado) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nombre apoderado</label>
                    <input type="text" name="nombre_apoderado" value="{{ old('nombre_apoderado', $student->nombre_apoderado) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $student->direccion) }}" class="form-control">
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-check2 me-1"></i>Actualizar</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
