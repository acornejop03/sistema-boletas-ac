@extends('layouts.app')
@section('title', 'Editar Alumno')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->codigo }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-pencil-square text-warning"></i> Editar Alumno</h5>
        <div class="page-subtitle">{{ $student->nombre_completo }} · <code>{{ $student->codigo }}</code></div>
    </div>
    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-xl-9 col-lg-10">
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon amber"><i class="bi bi-pencil"></i></div>
        <div>
            <div class="card-hdr-title">Actualizar datos del alumno</div>
            <div class="card-hdr-sub">Modifique los campos que necesita actualizar</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf @method('PUT')

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">1</span>
                    <span class="fsec-label">Identificación</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de documento</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="1" {{ $student->tipo_documento==1?'selected':'' }}>DNI</option>
                            <option value="6" {{ $student->tipo_documento==6?'selected':'' }}>RUC</option>
                            <option value="0" {{ $student->tipo_documento==0?'selected':'' }}>Sin documento</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">N° de documento</label>
                        <input type="text" name="numero_documento" value="{{ old('numero_documento', $student->numero_documento) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $student->fecha_nacimiento?->format('Y-m-d')) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">2</span>
                    <span class="fsec-label">Datos personales</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Apellido paterno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $student->apellido_paterno) }}" class="form-control @error('apellido_paterno') is-invalid @enderror" required>
                        @error('apellido_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido materno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno', $student->apellido_materno) }}" class="form-control @error('apellido_materno') is-invalid @enderror" required>
                        @error('apellido_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" value="{{ old('nombres', $student->nombres) }}" class="form-control @error('nombres') is-invalid @enderror" required>
                        @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">3</span>
                    <span class="fsec-label">Contacto</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $student->telefono) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $student->direccion) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">4</span>
                    <span class="fsec-label">Apoderado</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del apoderado</label>
                        <input type="text" name="nombre_apoderado" value="{{ old('nombre_apoderado', $student->nombre_apoderado) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono del apoderado</label>
                        <input type="text" name="telefono_apoderado" value="{{ old('telefono_apoderado', $student->telefono_apoderado) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">
                    <i class="bi bi-check2 me-1"></i>Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
