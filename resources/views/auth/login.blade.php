@extends('layouts.app')
@section('title', 'Iniciar Sesión')
@section('styles')
<style>
/* ══ RESET AUTH WRAPPER ══ */
.auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: stretch;
    padding: 0 !important;
    background: #fff !important;
    overflow: hidden;
}
.auth-wrapper::before, .auth-wrapper::after { display: none; }

/* ══ LEFT PANEL ══ */
.auth-left {
    flex: 0 0 52%;
    background: linear-gradient(160deg, #060e27 0%, #0d1f4e 40%, #1a3a6b 75%, #1e4799 100%);
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 3.5rem 4rem;
    overflow: hidden;
}

/* Mesh grid */
.auth-left::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
}

/* Glowing orbs */
.left-orb {
    position: absolute; border-radius: 50%;
    pointer-events: none; filter: blur(80px);
}
.left-orb-1 {
    width: 420px; height: 420px;
    background: radial-gradient(circle, rgba(37,99,235,0.4) 0%, transparent 65%);
    top: -120px; right: -80px;
    animation: orbFloat1 14s ease-in-out infinite;
}
.left-orb-2 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(245,158,11,0.25) 0%, transparent 65%);
    bottom: -60px; left: 40px;
    animation: orbFloat2 18s ease-in-out infinite;
}
.left-orb-3 {
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(139,92,246,0.3) 0%, transparent 65%);
    top: 50%; left: 30%;
    animation: orbFloat3 11s ease-in-out infinite;
}
@keyframes orbFloat1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-50px,60px)} }
@keyframes orbFloat2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(60px,-40px)} }
@keyframes orbFloat3 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-30px,50px)} }

/* Logo */
.left-logo {
    position: relative; z-index: 2;
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 3rem;
    animation: fadeSlideRight 0.7s cubic-bezier(0.34,1.56,0.64,1) both;
}
.left-logo-icon {
    width: 52px; height: 52px; border-radius: 16px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    box-shadow: 0 8px 24px rgba(245,158,11,0.5), 0 0 0 3px rgba(245,158,11,0.15);
}
.left-logo-text h4 {
    color: #fff; margin: 0; font-size: 1.05rem; font-weight: 800;
    letter-spacing: -0.01em; line-height: 1.2;
}
.left-logo-text span {
    color: rgba(255,255,255,0.5); font-size: 0.7rem;
    text-transform: uppercase; letter-spacing: 1.5px;
}

/* Headline */
.left-headline {
    position: relative; z-index: 2;
    margin-bottom: 2rem;
    animation: fadeSlideRight 0.7s 0.1s cubic-bezier(0.34,1.56,0.64,1) both;
}
.left-headline h1 {
    font-size: 2.4rem; font-weight: 900;
    line-height: 1.1; letter-spacing: -0.03em;
    color: #fff; margin: 0;
}
.left-headline h1 span {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}
.left-headline p {
    color: rgba(255,255,255,0.55); font-size: 0.9rem;
    margin: 0.75rem 0 0; line-height: 1.7; max-width: 360px;
}

/* Features */
.left-features {
    position: relative; z-index: 2;
    display: flex; flex-direction: column; gap: 12px;
    animation: fadeSlideRight 0.7s 0.2s cubic-bezier(0.34,1.56,0.64,1) both;
}
.left-feature {
    display: flex; align-items: center; gap: 12px;
}
.left-feature-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; flex-shrink: 0;
}
.left-feature-icon.amber  { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
.left-feature-icon.blue   { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.2); }
.left-feature-icon.green  { background: rgba(34,197,94,0.15);  color: #86efac; border: 1px solid rgba(34,197,94,0.2);  }
.left-feature-icon.purple { background: rgba(139,92,246,0.15); color: #c4b5fd; border: 1px solid rgba(139,92,246,0.2); }
.left-feature-text strong { color: #fff; font-size: 0.82rem; font-weight: 600; display: block; line-height: 1.3; }
.left-feature-text span   { color: rgba(255,255,255,0.4); font-size: 0.73rem; }

/* Bottom tag */
.left-bottom {
    position: relative; z-index: 2;
    margin-top: auto;
    padding-top: 2rem;
    display: flex; align-items: center; gap: 8px;
    animation: fadeSlideRight 0.7s 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
}
.left-bottom-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.25);
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{box-shadow:0 0 0 3px rgba(34,197,94,0.25)} 50%{box-shadow:0 0 0 6px rgba(34,197,94,0.1)} }
.left-bottom span { color: rgba(255,255,255,0.4); font-size: 0.73rem; }
.left-bottom strong { color: rgba(255,255,255,0.7); font-size: 0.73rem; }

@keyframes fadeSlideRight {
    from { opacity:0; transform:translateX(-24px); }
    to   { opacity:1; transform:translateX(0); }
}

/* ══ RIGHT PANEL ══ */
.auth-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 3rem 3.5rem;
    background: #fafbfc;
    position: relative;
    overflow-y: auto;
}
.auth-right::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 60% at 80% 20%, rgba(37,99,235,0.04) 0%, transparent 60%);
    pointer-events: none;
}

.auth-form-wrap {
    width: 100%; max-width: 380px;
    position: relative; z-index: 1;
    animation: fadeSlideUp 0.6s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes fadeSlideUp {
    from { opacity:0; transform:translateY(28px); }
    to   { opacity:1; transform:translateY(0); }
}

/* Header */
.form-header { margin-bottom: 2rem; }
.form-header h2 {
    font-size: 1.6rem; font-weight: 800;
    color: #0f172a; letter-spacing: -0.03em; margin: 0;
}
.form-header p { color: #64748b; font-size: 0.84rem; margin: 6px 0 0; }

/* Inputs */
.auth-input-group {
    position: relative; margin-bottom: 1.1rem;
}
.auth-input-group label {
    display: block; font-size: 0.78rem; font-weight: 600;
    color: #374151; margin-bottom: 5px;
}
.auth-input-group .inp-wrap {
    position: relative;
}
.auth-input-group .inp-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 0.95rem; pointer-events: none;
    transition: color 0.2s;
}
.auth-input-group input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.875rem;
    color: #1e293b;
    background: #fff;
    transition: all 0.2s;
    outline: none;
}
.auth-input-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.auth-input-group input:focus ~ .inp-icon,
.auth-input-group .inp-wrap:focus-within .inp-icon { color: #2563eb; }
.auth-input-group .inp-action {
    position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #94a3b8; cursor: pointer;
    font-size: 0.9rem; padding: 2px 4px; border-radius: 4px;
    transition: color 0.15s;
}
.auth-input-group .inp-action:hover { color: #475569; }
.auth-input-group input.has-action { padding-right: 38px; }

/* Remember row */
.auth-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 1.5rem; margin-top: 0.25rem;
}
.auth-check { display: flex; align-items: center; gap: 7px; }
.auth-check input[type=checkbox] {
    width: 15px; height: 15px; accent-color: #2563eb;
}
.auth-check label { font-size: 0.79rem; color: #64748b; cursor: pointer; }
.auth-secure {
    display: flex; align-items: center; gap: 5px;
    font-size: 0.73rem; color: #94a3b8;
}
.auth-secure i { color: #22c55e; }

/* Submit button */
.btn-submit {
    width: 100%;
    height: 46px;
    background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
    border: none; border-radius: 12px;
    color: #fff; font-size: 0.92rem; font-weight: 700;
    cursor: pointer; position: relative; overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 20px rgba(37,99,235,0.45), inset 0 1px 0 rgba(255,255,255,0.12);
    letter-spacing: 0.01em;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-submit::before {
    content: '';
    position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
    transition: left 0.6s ease;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(37,99,235,0.55); }
.btn-submit:hover::before { left: 100%; }
.btn-submit:active { transform: translateY(0); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

/* Divider */
.auth-divider {
    display: flex; align-items: center; gap: 10px;
    margin: 1.4rem 0 1rem;
}
.auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}
.auth-divider span { font-size: 0.72rem; color: #94a3b8; font-weight: 500; white-space: nowrap; }

/* Quick access */
.qa-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.qa-item {
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    padding: 9px 8px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; background: #fff;
    cursor: pointer; transition: all 0.18s;
    font-size: 0.73rem; font-weight: 600; color: #475569;
}
.qa-item:hover { border-color: #2563eb; background: #eff6ff; color: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(37,99,235,0.15); }
.qa-item i { font-size: 1.1rem; }
.qa-item span { font-size: 0.68rem; font-weight: 500; color: #94a3b8; }

/* Error */
.auth-error {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 10px; padding: 10px 14px;
    display: flex; align-items: flex-start; gap: 9px;
    margin-bottom: 1.2rem;
    font-size: 0.82rem; color: #991b1b;
    animation: fadeSlideUp 0.3s ease both;
}
.auth-error i { color: #ef4444; flex-shrink: 0; margin-top: 1px; }
/* Bloqueo temporal */
.auth-error.err-lock {
    background: #fff7ed; border-color: #fed7aa; color: #9a3412;
}
.auth-error.err-lock i { color: #f97316; }
/* Usuario inactivo */
.auth-error.err-inactive {
    background: #f0f9ff; border-color: #bae6fd; color: #075985;
}
.auth-error.err-inactive i { color: #0284c7; }

/* Copyright */
.auth-copyright {
    text-align: center; margin-top: 2rem;
    font-size: 0.71rem; color: #cbd5e1;
}

/* ══ RESPONSIVE ══ */
@media (max-width: 900px) {
    .auth-left { display: none; }
    .auth-right { padding: 2rem 1.5rem; background: linear-gradient(160deg, #060e27 0%, #1a3a6b 100%); }
    .auth-right::before { display: none; }
    .form-header h2, .auth-check label, .auth-secure { color: rgba(255,255,255,0.9) !important; }
    .form-header p { color: rgba(255,255,255,0.55) !important; }
    .auth-input-group label { color: rgba(255,255,255,0.8) !important; }
    .auth-input-group input { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); color: #fff; }
    .auth-input-group input::placeholder { color: rgba(255,255,255,0.3); }
    .qa-item { background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.12); color: rgba(255,255,255,0.75); }
    .qa-item span { color: rgba(255,255,255,0.4); }
    .qa-item:hover { background: rgba(37,99,235,0.3); border-color: rgba(37,99,235,0.5); color: #fff; }
    .auth-divider::before, .auth-divider::after { background: rgba(255,255,255,0.1); }
    .auth-divider span { color: rgba(255,255,255,0.3); }
    .auth-copyright { color: rgba(255,255,255,0.25); }
}
@media (max-width: 480px) {
    .auth-right { padding: 1.5rem 1rem; }
    .auth-form-wrap { max-width: 100%; }
    .qa-grid { grid-template-columns: 1fr 1fr; }
}
</style>
@endsection

@section('content')

{{-- ═══ LEFT PANEL ═══ --}}
<div class="auth-left">
    <div class="left-orb left-orb-1"></div>
    <div class="left-orb left-orb-2"></div>
    <div class="left-orb left-orb-3"></div>

    {{-- Logo --}}
    <div class="left-logo">
        <div class="left-logo-icon">🎓</div>
        <div class="left-logo-text">
            @php $company = \App\Models\Company::first(); @endphp
            <h4>{{ $company->nombre_comercial ?? 'Academia AC' }}</h4>
            <span>RUC {{ $company->ruc ?? '—' }}</span>
        </div>
    </div>

    {{-- Headline --}}
    <div class="left-headline">
        <h1>Sistema de<br><span>Boletas</span><br>Electrónicas</h1>
        <p>Gestiona cobros, emite comprobantes y sincroniza con SUNAT de forma automática y segura.</p>
    </div>

    {{-- Features --}}
    <div class="left-features">
        <div class="left-feature">
            <div class="left-feature-icon amber"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="left-feature-text">
                <strong>Emisión automática a SUNAT</strong>
                <span>Boletas y facturas electrónicas en tiempo real</span>
            </div>
        </div>
        <div class="left-feature">
            <div class="left-feature-icon blue"><i class="bi bi-people-fill"></i></div>
            <div class="left-feature-text">
                <strong>Gestión de alumnos y matrículas</strong>
                <span>Control completo del ciclo académico</span>
            </div>
        </div>
        <div class="left-feature">
            <div class="left-feature-icon green"><i class="bi bi-bar-chart-line-fill"></i></div>
            <div class="left-feature-text">
                <strong>Reportes financieros</strong>
                <span>Ingresos, morosos y estadísticas detalladas</span>
            </div>
        </div>
        <div class="left-feature">
            <div class="left-feature-icon purple"><i class="bi bi-shield-check-fill"></i></div>
            <div class="left-feature-text">
                <strong>Roles y permisos</strong>
                <span>Acceso controlado por perfil de usuario</span>
            </div>
        </div>
    </div>

    {{-- Bottom status --}}
    <div class="left-bottom">
        <div class="left-bottom-dot"></div>
        <span>Ambiente: </span>
        <strong>{{ strtoupper(config('sunat.ambiente', 'BETA')) }} — SUNAT</strong>
    </div>
</div>

{{-- ═══ RIGHT PANEL ═══ --}}
<div class="auth-right">
    <div class="auth-form-wrap">

        {{-- Header --}}
        <div class="form-header">
            <h2>Bienvenido de vuelta</h2>
            <p>Ingresa tus credenciales para acceder al sistema</p>
        </div>

        {{-- Mensaje de sesión (cuenta desactivada, etc.) --}}
        @if(session('error'))
        <div class="auth-error" style="background:#fef2f2;border-color:#fca5a5;color:#b91c1c">
            <i class="bi bi-shield-x-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Errores de login --}}
        @if($errors->any())
        @php
            $errMsg = $errors->first();
            $isBloqueado = str_contains($errMsg, 'bloqueada') || str_contains($errMsg, 'Bloqueada');
            $isInactivo  = str_contains($errMsg, 'desactivada');
        @endphp
        <div class="auth-error {{ $isBloqueado ? 'err-lock' : ($isInactivo ? 'err-inactive' : '') }}">
            <i class="bi bi-{{ $isBloqueado ? 'lock-fill' : ($isInactivo ? 'person-x-fill' : 'exclamation-triangle-fill') }}"></i>
            <span>{{ $errMsg }}</span>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="auth-input-group">
                <label for="email">Correo electrónico</label>
                <div class="inp-wrap">
                    <i class="bi bi-envelope inp-icon"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="usuario@academia.pe"
                           required autofocus autocomplete="email">
                </div>
            </div>

            <div class="auth-input-group">
                <label for="pwd">Contraseña</label>
                <div class="inp-wrap">
                    <i class="bi bi-lock inp-icon"></i>
                    <input type="password" id="pwd" name="password"
                           placeholder="••••••••" class="has-action"
                           required autocomplete="current-password">
                    <button type="button" class="inp-action" onclick="togglePwd()">
                        <i class="bi bi-eye" id="pwdIcon"></i>
                    </button>
                </div>
            </div>

            <div class="auth-row">
                <div class="auth-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordarme</label>
                </div>
                <div class="auth-secure">
                    <i class="bi bi-shield-lock-fill"></i> Conexión segura
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnLogin">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar Sesión
            </button>
        </form>

        <div class="auth-copyright">&copy; {{ date('Y') }} {{ $company->nombre_comercial ?? 'Academia AC' }} — Todos los derechos reservados</div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function togglePwd() {
    const el = document.getElementById('pwd');
    const ic = document.getElementById('pwdIcon');
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnLogin');
    if (!btn.disabled) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:14px;height:14px"></span> Ingresando...';
        btn.disabled = true;
    }
});
</script>
@endsection
