@extends('layouts.app')
@section('title', 'Editar Usuario')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-pencil-square text-warning"></i> Editar Usuario</h5>
        <div class="page-subtitle">{{ $user->name }} · {{ $user->email }}</div>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-xl-6 col-lg-7">
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon amber"><i class="bi bi-pencil"></i></div>
        <div>
            <div class="card-hdr-title">Editar: {{ $user->name }}</div>
            <div class="card-hdr-sub">Actualice los datos de acceso del usuario</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">1</span>
                    <span class="fsec-label">Datos personales</span>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name',$user->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email',$user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono',$user->telefono) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">2</span>
                    <span class="fsec-label">Contraseña <span class="text-muted fw-normal" style="font-size:0.68rem;letter-spacing:0">(dejar en blanco para no cambiar)</span></span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" id="pwd"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="8" placeholder="Mínimo 8 caracteres">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="pwd2" class="form-control" placeholder="Repita la nueva contraseña">
                        <div id="pwdMatch" class="form-text" style="font-size:0.72rem"></div>
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">3</span>
                    <span class="fsec-label">Rol</span>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Rol del usuario <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->name }}" {{ $user->hasRole($rol->name)?'selected':'' }}>
                                {{ ucfirst($rol->name) }}
                            </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">
                    <i class="bi bi-check2 me-1"></i>Actualizar Usuario
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
