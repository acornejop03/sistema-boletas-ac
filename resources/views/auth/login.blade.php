@extends('layouts.app')
@section('title', 'Iniciar Sesión')
@section('content')

<div class="auth-card">
    {{-- Logo + título --}}
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3"
             style="width:72px;height:72px;font-size:2rem">🎓</div>
        <h4 class="fw-bold" style="color:#1a3a6b">Academia AC</h4>
        <p class="text-muted small mb-0">Sistema de Boletas Electrónicas</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger py-2 small mb-3">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                       placeholder="usuario@academia.pe" required autofocus
                       style="border-radius:0 8px 8px 0">
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-muted"></i>
                </span>
                <input type="password" name="password" id="pwd"
                       class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                       placeholder="••••••••" required
                       style="border-radius:0">
                <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePwd()"
                        style="border-radius:0 8px 8px 0;cursor:pointer" id="btnTogglePwd">
                    <i class="bi bi-eye text-muted" id="pwdIcon"></i>
                </button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted" for="remember">Recordarme</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius:10px;font-size:0.95rem">
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
        </button>
    </form>

    <div class="mt-4 pt-3 border-top text-center">
        <small class="text-muted d-block">Accesos de prueba:</small>
        <div class="d-flex flex-wrap justify-content-center gap-1 mt-1">
            @foreach(['superadmin@academia.com'=>'superadmin','cajero@academia.com'=>'cajero'] as $email => $rol)
            <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size:0.7rem;padding:2px 8px"
                    onclick="document.querySelector('[name=email]').value='{{ $email }}';document.querySelector('[name=password]').value='password'">
                {{ $rol }}
            </button>
            @endforeach
        </div>
    </div>
</div>

<p class="text-white-50 text-center small mt-3 mb-0">&copy; {{ date('Y') }} Academia AC — Todos los derechos reservados</p>

@endsection
@section('scripts')
<script>
function togglePwd() {
    const pwd = document.getElementById('pwd');
    const icon = document.getElementById('pwdIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash text-muted';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye text-muted';
    }
}
</script>
@endsection
