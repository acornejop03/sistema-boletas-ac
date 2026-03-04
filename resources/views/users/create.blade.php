@extends('layouts.app')
@section('title', 'Nuevo Usuario')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-person-plus text-primary"></i> Nuevo Usuario</h5>
        <div class="page-subtitle">Crear cuenta de acceso al sistema</div>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-xl-6 col-lg-7">
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon blue"><i class="bi bi-person-plus"></i></div>
        <div>
            <div class="card-hdr-title">Crear Usuario</div>
            <div class="card-hdr-sub">Ingrese los datos de acceso del nuevo usuario</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">1</span>
                    <span class="fsec-label">Datos personales</span>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Juan Pérez García" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="usuario@academia.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="form-control" placeholder="999 999 999">
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">2</span>
                    <span class="fsec-label">Seguridad y Rol</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="pwd"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="8" required placeholder="Mínimo 8 caracteres">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="pwd2"
                               class="form-control" required placeholder="Repita la contraseña">
                        <div id="pwdMatch" class="form-text" style="font-size:0.72rem"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Rol del usuario <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->name }}" {{ old('role')==$rol->name?'selected':'' }}>
                                {{ ucfirst($rol->name) }}
                            </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="mt-2 p-3 rounded-2" style="background:#f8fafc;font-size:0.75rem;color:#64748b">
                            <strong>Roles disponibles:</strong> superadmin (acceso total) · administrador (gestión) · cajero (cobros) · consulta (solo lectura)
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-person-check me-1"></i>Crear Usuario
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
function checkPwd() {
    const a = $('#pwd').val(), b = $('#pwd2').val();
    if (!b) { $('#pwdMatch').text('').css('color',''); return; }
    if (a === b) {
        $('#pwdMatch').html('<i class="bi bi-check-circle me-1 text-success"></i>Las contraseñas coinciden').css('color','#16a34a');
    } else {
        $('#pwdMatch').html('<i class="bi bi-x-circle me-1"></i>Las contraseñas no coinciden').css('color','#dc2626');
    }
}
$('#pwd, #pwd2').on('input', checkPwd);
</script>
@endsection
