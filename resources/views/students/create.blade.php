@extends('layouts.app')
@section('title', 'Nuevo Alumno')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-md-9">
<div class="card">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-person-plus me-2"></i>Registrar Nuevo Alumno
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="text-muted text-uppercase fw-semibold border-bottom pb-1" style="font-size:0.75rem;letter-spacing:1px">
                        Identificación
                    </h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Tipo de documento</label>
                    <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" id="tipoDoc">
                        <option value="1" {{ old('tipo_documento',1)==1?'selected':'' }}>DNI</option>
                        <option value="6" {{ old('tipo_documento')==6?'selected':'' }}>RUC</option>
                        <option value="0" {{ old('tipo_documento')==0?'selected':'' }}>Sin documento</option>
                    </select>
                    @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Número de documento</label>
                    <div class="input-group">
                        <input type="text" name="numero_documento" id="numDoc"
                               value="{{ old('numero_documento') }}"
                               class="form-control @error('numero_documento') is-invalid @enderror"
                               placeholder="12345678" maxlength="20">
                        <button type="button" class="btn btn-outline-secondary" id="btnBuscarDNI" title="Buscar en RENIEC">
                            <i class="bi bi-search"></i>
                        </button>
                        @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <small class="text-muted" id="dniBusquedaMsg"></small>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror">
                    @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <h6 class="text-muted text-uppercase fw-semibold border-bottom pb-1 mt-2" style="font-size:0.75rem;letter-spacing:1px">
                        Datos personales
                    </h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Apellido paterno <span class="text-danger">*</span></label>
                    <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                           id="apPat"
                           class="form-control @error('apellido_paterno') is-invalid @enderror"
                           placeholder="PÉREZ">
                    @error('apellido_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Apellido materno <span class="text-danger">*</span></label>
                    <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                           id="apMat"
                           class="form-control @error('apellido_materno') is-invalid @enderror"
                           placeholder="GARCIA">
                    @error('apellido_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Nombres <span class="text-danger">*</span></label>
                    <input type="text" name="nombres" value="{{ old('nombres') }}"
                           id="nombres"
                           class="form-control @error('nombres') is-invalid @enderror"
                           placeholder="JUAN CARLOS">
                    @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <h6 class="text-muted text-uppercase fw-semibold border-bottom pb-1 mt-2" style="font-size:0.75rem;letter-spacing:1px">
                        Contacto
                    </h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="alumno@email.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                           class="form-control @error('telefono') is-invalid @enderror"
                           placeholder="999 999 999">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}"
                           class="form-control @error('direccion') is-invalid @enderror">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <h6 class="text-muted text-uppercase fw-semibold border-bottom pb-1 mt-2" style="font-size:0.75rem;letter-spacing:1px">
                        Apoderado
                    </h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nombre del apoderado</label>
                    <input type="text" name="nombre_apoderado" value="{{ old('nombre_apoderado') }}"
                           class="form-control" placeholder="MARÍA GARCIA PEREZ">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Teléfono del apoderado</label>
                    <input type="text" name="telefono_apoderado" value="{{ old('telefono_apoderado') }}"
                           class="form-control" placeholder="999 999 999">
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
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
// Simulación búsqueda DNI - En producción conectar a API RENIEC
$('#btnBuscarDNI').click(function() {
    const dni = $('#numDoc').val().trim();
    if (dni.length !== 8) {
        $('#dniBusquedaMsg').text('El DNI debe tener 8 dígitos.').addClass('text-danger');
        return;
    }
    $('#dniBusquedaMsg').text('Buscando...').removeClass('text-danger text-success');
    // Demo: Solo muestra mensaje. Integrar API RENIEC real cuando esté disponible.
    setTimeout(() => {
        $('#dniBusquedaMsg').text('⚠️ Búsqueda automática no disponible en modo demo. Ingrese los datos manualmente.').addClass('text-warning');
    }, 800);
});
</script>
@endsection
