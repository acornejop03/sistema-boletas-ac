@extends('layouts.app')
@section('title', 'Nuevo Alumno')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-person-plus text-primary"></i> Registrar Alumno</h5>
        <div class="page-subtitle">Complete los datos del nuevo alumno</div>
    </div>
    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-xl-9 col-lg-10">
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon blue"><i class="bi bi-person-badge"></i></div>
        <div>
            <div class="card-hdr-title">Datos del Alumno</div>
            <div class="card-hdr-sub">Los campos marcados con <span class="text-danger">*</span> son obligatorios</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf

            {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">1</span>
                    <span class="fsec-label">Identificación</span>
                    <i class="bi bi-fingerprint fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de documento</label>
                        <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" id="tipoDoc">
                            <option value="1" {{ old('tipo_documento',1)==1?'selected':'' }}>DNI</option>
                            <option value="6" {{ old('tipo_documento')==6?'selected':'' }}>RUC</option>
                            <option value="0" {{ old('tipo_documento')==0?'selected':'' }}>Sin documento</option>
                        </select>
                        @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">N° de documento</label>
                        <div class="input-group">
                            <input type="text" name="numero_documento" id="numDoc"
                                   value="{{ old('numero_documento') }}"
                                   class="form-control @error('numero_documento') is-invalid @enderror"
                                   placeholder="12345678" maxlength="20">
                            <button type="button" class="btn btn-outline-secondary" id="btnBuscarDNI" title="Buscar en RENIEC" style="border-radius:0 8px 8px 0">
                                <i class="bi bi-search"></i>
                            </button>
                            @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="text-muted" id="dniBusquedaMsg"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror">
                        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: DATOS PERSONALES --}}
            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">2</span>
                    <span class="fsec-label">Datos personales</span>
                    <i class="bi bi-person fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Apellido paterno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                               id="apPat"
                               class="form-control @error('apellido_paterno') is-invalid @enderror"
                               placeholder="PÉREZ">
                        @error('apellido_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido materno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                               id="apMat"
                               class="form-control @error('apellido_materno') is-invalid @enderror"
                               placeholder="GARCIA">
                        @error('apellido_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}"
                               id="nombres"
                               class="form-control @error('nombres') is-invalid @enderror"
                               placeholder="JUAN CARLOS">
                        @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: CONTACTO --}}
            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">3</span>
                    <span class="fsec-label">Contacto</span>
                    <i class="bi bi-telephone fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="alumno@email.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="form-control @error('telefono') is-invalid @enderror"
                               placeholder="999 999 999">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}"
                               class="form-control @error('direccion') is-invalid @enderror">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 4: APODERADO --}}
            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">4</span>
                    <span class="fsec-label">Apoderado <span class="text-muted fw-normal" style="font-size:0.68rem;letter-spacing:0">(opcional)</span></span>
                    <i class="bi bi-person-heart fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del apoderado</label>
                        <input type="text" name="nombre_apoderado" value="{{ old('nombre_apoderado') }}"
                               class="form-control" placeholder="MARÍA GARCIA PÉREZ">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono del apoderado</label>
                        <input type="text" name="telefono_apoderado" value="{{ old('telefono_apoderado') }}"
                               class="form-control" placeholder="999 999 999">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-person-check me-1"></i>Guardar Alumno
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
$('#btnBuscarDNI').click(function() {
    const dni = $('#numDoc').val().trim();
    if (dni.length !== 8) {
        $('#dniBusquedaMsg').html('<span class="text-danger">El DNI debe tener 8 dígitos.</span>');
        return;
    }
    $('#dniBusquedaMsg').html('<span class="text-muted">Buscando...</span>');
    setTimeout(() => {
        $('#dniBusquedaMsg').html('<span class="text-warning"><i class="bi bi-info-circle me-1"></i>Búsqueda automática no disponible en modo demo.</span>');
    }, 800);
});
</script>
@endsection
