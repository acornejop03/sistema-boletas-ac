<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Academia AC') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 265px;
            --brand-primary: #1a3a6b;
            --brand-secondary: #2563eb;
            --brand-accent: #f59e0b;
            --bg-main: #f0f4f8;
            --card-shadow: 0 1px 6px rgba(0,0,0,0.07), 0 0 1px rgba(0,0,0,0.06);
            --card-radius: 12px;
            --sidebar-transition: 0.3s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', 'Segoe UI', sans-serif; background: var(--bg-main); color: #1e293b; margin: 0; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(175deg, #1a3a6b 0%, #1e4faa 55%, #1d40af 100%);
            position: fixed; top: 0; left: 0; z-index: 1040;
            display: flex; flex-direction: column;
            box-shadow: 3px 0 20px rgba(0,0,0,0.15);
            transition: transform var(--sidebar-transition);
            overflow-y: auto;
            overflow-x: hidden;
        }
        #sidebar .sidebar-brand {
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 10px;
        }
        #sidebar .brand-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--brand-accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        #sidebar .brand-text h6 { color: #fff; margin: 0; font-size: 0.88rem; font-weight: 700; line-height: 1.2; }
        #sidebar .brand-text small { color: rgba(255,255,255,0.55); font-size: 0.7rem; }

        #sidebar .nav-section {
            font-size: 0.62rem; text-transform: uppercase; letter-spacing: 1.4px;
            color: rgba(255,255,255,0.35); padding: 1rem 1.1rem 0.25rem; font-weight: 600;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.75); padding: 0.5rem 0.85rem;
            border-radius: 9px; margin: 1px 8px;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.855rem; font-weight: 500; transition: all 0.18s;
            text-decoration: none; white-space: nowrap;
        }
        #sidebar .nav-link i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        #sidebar .nav-link:hover { background: rgba(255,255,255,0.13); color: #fff; transform: translateX(2px); }
        #sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff; font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.15);
        }
        #sidebar .nav-link .badge { margin-left: auto; font-size: 0.65rem; animation: pulse 2s infinite; }

        #sidebar .sidebar-footer {
            margin-top: auto; padding: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;
        }
        #sidebar .sidebar-footer .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--brand-accent); color: #1a3a6b;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
        }
        #sidebar .sidebar-footer .user-name { color: #fff; font-size: 0.82rem; font-weight: 600; line-height: 1.2; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ── MAIN CONTENT ── */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin-left var(--sidebar-transition);
        }

        /* ── TOPBAR ── */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            height: 58px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        #topbar .topbar-left { display: flex; align-items: center; gap: 12px; }
        #topbar #btn-menu { display: none; }
        #topbar .breadcrumb { margin: 0; font-size: 0.8rem; }
        #topbar .breadcrumb-item a { color: var(--brand-secondary); text-decoration: none; }
        #topbar .topbar-right { display: flex; align-items: center; gap: 10px; }
        #topbar .topbar-time { font-size: 0.78rem; color: #64748b; display: flex; align-items: center; gap: 4px; }

        /* ── CONTENT AREA ── */
        .content-area { padding: 1.5rem; flex: 1; }

        /* ── CARDS ── */
        .card { border: none; box-shadow: var(--card-shadow); border-radius: var(--card-radius); }
        .card-header { border-radius: var(--card-radius) var(--card-radius) 0 0 !important; border-bottom: 1px solid rgba(0,0,0,0.06); }

        /* ── STAT CARDS ── */
        .stat-card { border-radius: var(--card-radius); padding: 1.15rem 1.25rem; position: relative; overflow: hidden; }
        .stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .stat-card .stat-value { font-size: 1.55rem; font-weight: 700; line-height: 1.1; }
        .stat-card .stat-label { font-size: 0.76rem; color: #64748b; font-weight: 500; margin-top: 2px; }
        .stat-card::after { content:''; position:absolute; right:-15px; top:-15px; width:80px; height:80px; border-radius:50%; opacity:0.07; }
        .stat-blue   { background:#fff; border-left: 4px solid #3b82f6; }  .stat-blue .stat-icon   { background: #eff6ff; color: #2563eb; }
        .stat-green  { background:#fff; border-left: 4px solid #22c55e; }  .stat-green .stat-icon  { background: #f0fdf4; color: #16a34a; }
        .stat-amber  { background:#fff; border-left: 4px solid #f59e0b; }  .stat-amber .stat-icon  { background: #fffbeb; color: #d97706; }
        .stat-red    { background:#fff; border-left: 4px solid #ef4444; }  .stat-red .stat-icon    { background: #fef2f2; color: #dc2626; }
        .stat-purple { background:#fff; border-left: 4px solid #8b5cf6; }  .stat-purple .stat-icon { background: #f5f3ff; color: #7c3aed; }
        .stat-cyan   { background:#fff; border-left: 4px solid #06b6d4; }  .stat-cyan .stat-icon   { background: #ecfeff; color: #0891b2; }

        /* ── TABLES ── */
        .table th { font-size: 0.73rem; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; background: #f8fafc; font-weight: 600; padding: 0.7rem 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 0.65rem 0.75rem; vertical-align: middle; border-color: #f1f5f9; font-size: 0.855rem; }
        .table tbody tr:hover { background: #f8fafc; }
        .table-responsive { border-radius: 0 0 var(--card-radius) var(--card-radius); }

        /* ── BADGES ── */
        .badge { font-weight: 500; letter-spacing: 0.2px; }

        /* ── FORMS ── */
        .form-control, .form-select { border-color: #e2e8f0; border-radius: 8px; font-size: 0.875rem; }
        .form-control:focus, .form-select:focus { border-color: var(--brand-secondary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }

        /* ── BUTTONS ── */
        .btn { border-radius: 8px; font-size: 0.855rem; font-weight: 500; }
        .btn-primary { background: var(--brand-secondary); border-color: var(--brand-secondary); }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; }
        .btn-sm { font-size: 0.8rem; padding: 0.3rem 0.7rem; }

        /* ── PAGE HEADER ── */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; }
        .page-header h5 { margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; }
        .page-header .page-subtitle { font-size: 0.78rem; color: #64748b; margin-top: 1px; }

        /* ── ALERTS ── */
        .alert { border-radius: 10px; border: none; }
        .alert-success { background: #f0fdf4; color: #15803d; border-left: 4px solid #22c55e; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; }

        /* ── ANIMATIONS ── */
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.55} }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .content-area > * { animation: fadeInUp 0.25s ease; }

        /* ── OVERLAY (mobile) ── */
        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); z-index: 1039;
            backdrop-filter: blur(2px);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
            #topbar #btn-menu { display: flex; }
            #sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
            #sidebar-overlay.open { opacity: 1; pointer-events: auto; }
            .content-area { padding: 1rem; }
            #topbar { padding: 0 1rem; }
            .stat-value { font-size: 1.3rem !important; }
        }
        @media (max-width: 575.98px) {
            .page-header { flex-direction: column; align-items: flex-start; }
            .table th, .table td { font-size: 0.78rem; padding: 0.55rem 0.6rem; }
            .content-area { padding: 0.75rem; }
            .card { border-radius: 10px; }
        }

        /* ── SCROLLBAR ── */
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        /* ── LOGIN PAGE ── */
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%); padding: 1rem; }
        .auth-card { background: #fff; border-radius: 18px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.25); }
    </style>
    @yield('styles')
</head>
<body>
@auth
{{-- Sidebar overlay (mobile) --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🎓</div>
        @php $company = \App\Models\Company::first(); @endphp
        <div class="brand-text">
            <h6>{{ $company->nombre_comercial ?? config('app.name') }}</h6>
            <small>RUC {{ $company->ruc ?? '—' }}</small>
        </div>
    </div>

    <div class="mt-1 pb-3 flex-1">
        {{-- Principal --}}
        <span class="nav-section">Principal</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        {{-- Académico --}}
        @canany(['ver alumnos','ver cursos','ver matriculas'])
        <span class="nav-section">Académico</span>
        @endcanany
        @can('ver alumnos')
        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Alumnos
        </a>
        @endcan
        @can('ver cursos')
        <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Cursos
        </a>
        @endcan
        @can('ver matriculas')
        <a href="{{ route('enrollments.index') }}" class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check"></i> Matrículas
        </a>
        @endcan

        {{-- Caja --}}
        @canany(['crear cobros','ver cobros','ver comprobantes'])
        <span class="nav-section">Caja</span>
        @endcanany
        @can('crear cobros')
        <a href="{{ route('payments.create') }}" class="nav-link {{ request()->routeIs('payments.create') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Nuevo Cobro
        </a>
        @endcan
        @can('ver cobros')
        <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i> Cobros
        </a>
        @endcan
        @can('ver comprobantes')
        <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Comprobantes
            @php $pend = \App\Models\Sale::where('estado_sunat','PENDIENTE')->count(); @endphp
            @if($pend > 0)
                <span class="badge bg-danger">{{ $pend }}</span>
            @endif
        </a>
        @endcan

        {{-- Reportes --}}
        @canany(['ver reportes basicos','ver reportes completos'])
        <span class="nav-section">Reportes</span>
        @endcanany
        @can('ver reportes basicos')
        <a href="{{ route('reports.ingresos') }}" class="nav-link {{ request()->routeIs('reports.ingresos') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Ingresos
        </a>
        <a href="{{ route('reports.morosos') }}" class="nav-link {{ request()->routeIs('reports.morosos') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i> Morosos
        </a>
        <a href="{{ route('reports.pendientesSunat') }}" class="nav-link {{ request()->routeIs('reports.pendientesSunat') ? 'active' : '' }}">
            <i class="bi bi-cloud-slash"></i> Pend. SUNAT
        </a>
        @endcan
        @can('ver reportes completos')
        <a href="{{ route('reports.porCajero') }}" class="nav-link {{ request()->routeIs('reports.porCajero') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i> Por Cajero
        </a>
        <a href="{{ route('reports.porCurso') }}" class="nav-link {{ request()->routeIs('reports.porCurso') ? 'active' : '' }}">
            <i class="bi bi-pie-chart"></i> Por Curso
        </a>
        @endcan

        {{-- Admin --}}
        @role('superadmin')
        <span class="nav-section">Admin</span>
        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categorías
        </a>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Usuarios
        </a>
        <a href="{{ route('config.edit') }}" class="nav-link {{ request()->routeIs('config.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Configuración
        </a>
        @endrole
    </div>

    {{-- Footer usuario --}}
    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div style="min-width:0">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <span class="badge bg-warning text-dark" style="font-size:0.6rem;padding:2px 6px">
                    {{ strtoupper(auth()->user()->getRoleNames()->first() ?? 'sin rol') }}
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm w-100" style="background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.2)">
                <i class="bi bi-box-arrow-left me-1"></i> Cerrar sesión
            </button>
        </form>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div id="main-content">
    {{-- TOPBAR --}}
    <div id="topbar">
        <div class="topbar-left">
            <button id="btn-menu" class="btn btn-sm btn-outline-secondary" onclick="toggleSidebar()" style="border-radius:8px">
                <i class="bi bi-list" style="font-size:1.1rem"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bi bi-house"></i></a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <div class="topbar-time d-none d-md-flex">
                <i class="bi bi-clock"></i>
                <span id="topbar-clock">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    <div class="content-area pb-0 pt-3" style="padding-bottom:0!important">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <strong>Hay errores en el formulario:</strong>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="content-area">
        @yield('content')
    </div>
</div>

@else
{{-- AUTH PAGES (login/register) --}}
<div class="auth-wrapper">
    @yield('content')
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
// Sidebar toggle
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
}

// Reloj en tiempo real
(function clockTick() {
    const el = document.getElementById('topbar-clock');
    if (el) {
        const now = new Date();
        el.textContent = now.toLocaleDateString('es-PE') + ' ' + now.toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'});
    }
    setTimeout(clockTick, 30000);
})();

// Cerrar sidebar al navegar (mobile)
document.querySelectorAll('#sidebar .nav-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
    });
});
</script>
@yield('scripts')
</body>
</html>
