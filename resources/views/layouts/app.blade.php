<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Academia AC') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 262px;
            --brand-primary: #1a3a6b;
            --brand-secondary: #2563eb;
            --brand-accent: #f59e0b;
            --bg-main: #f0f4f8;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 8px rgba(0,0,0,0.04);
            --card-shadow-md: 0 4px 16px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.05);
            --card-radius: 12px;
            --sidebar-transition: 0.28s cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; background: var(--bg-main); color: #1e293b; margin: 0; font-size: 0.875rem; -webkit-font-smoothing: antialiased; }

        /* ══ SIDEBAR ══ */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, #1a3a6b 0%, #1d40af 100%);
            position: fixed; top: 0; left: 0; z-index: 1040;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.12);
            transition: transform var(--sidebar-transition);
            overflow-y: auto; overflow-x: hidden;
        }
        #sidebar .sidebar-brand {
            padding: 1.1rem 1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        #sidebar .brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--brand-accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(245,158,11,0.4);
        }
        #sidebar .brand-text h6 { color: #fff; margin: 0; font-size: 0.875rem; font-weight: 700; line-height: 1.25; }
        #sidebar .brand-text small { color: rgba(255,255,255,0.5); font-size: 0.68rem; letter-spacing: 0.3px; }

        #sidebar .nav-section {
            font-size: 0.6rem; text-transform: uppercase; letter-spacing: 1.6px;
            color: rgba(255,255,255,0.3); padding: 1.1rem 1.1rem 0.3rem; font-weight: 700;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.7); padding: 0.48rem 0.85rem;
            border-radius: 8px; margin: 1px 8px;
            display: flex; align-items: center; gap: 9px;
            font-size: 0.845rem; font-weight: 500;
            transition: background 0.16s, color 0.16s, box-shadow 0.16s;
            text-decoration: none; white-space: nowrap; position: relative;
        }
        #sidebar .nav-link i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        #sidebar .nav-link:hover { background: rgba(255,255,255,0.11); color: #fff; }
        #sidebar .nav-link.active {
            background: rgba(255,255,255,0.16);
            color: #fff; font-weight: 600;
            box-shadow: inset 3px 0 0 var(--brand-accent), 0 2px 8px rgba(0,0,0,0.1);
        }
        #sidebar .nav-link .badge { margin-left: auto; font-size: 0.62rem; animation: pulse 2.5s infinite; }

        #sidebar .sidebar-footer {
            margin-top: auto; padding: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;
        }
        #sidebar .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--brand-accent); color: #1a3a6b;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.8rem; flex-shrink: 0;
        }
        #sidebar .user-name { color: #fff; font-size: 0.81rem; font-weight: 600; line-height: 1.25; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #sidebar .logout-btn {
            background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.18); border-radius: 7px;
            font-size: 0.8rem; width: 100%; padding: 0.4rem 0.75rem;
            cursor: pointer; transition: background 0.16s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        #sidebar .logout-btn:hover { background: rgba(255,255,255,0.18); }

        /* ══ MAIN CONTENT ══ */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin-left var(--sidebar-transition);
        }

        /* ══ TOPBAR ══ */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e8edf3;
            padding: 0 1.5rem;
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 0 rgba(0,0,0,0.05), 0 2px 8px rgba(0,0,0,0.03);
        }
        #topbar .topbar-left { display: flex; align-items: center; gap: 10px; }
        #topbar #btn-menu { display: none; border: none; background: #f1f5f9; color: #64748b; padding: 6px 9px; border-radius: 8px; cursor: pointer; line-height: 1; transition: background 0.15s; }
        #topbar #btn-menu:hover { background: #e2e8f0; }
        #topbar .breadcrumb { margin: 0; font-size: 0.79rem; }
        #topbar .breadcrumb-item a { color: var(--brand-secondary); text-decoration: none; }
        #topbar .breadcrumb-item.active { color: #374151; }
        #topbar .topbar-right { display: flex; align-items: center; gap: 8px; }
        #topbar .topbar-clock {
            font-size: 0.75rem; color: #64748b;
            display: flex; align-items: center; gap: 5px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            padding: 4px 10px; border-radius: 6px;
        }
        .topbar-divider { width: 1px; height: 28px; background: #e2e8f0; margin: 0 4px; }
        .topbar-profile {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 10px 4px 8px;
            border: 1px solid #e2e8f0; border-radius: 8px;
            background: #fafbfc; cursor: default;
            transition: background 0.15s;
        }
        .topbar-profile:hover { background: #f1f5f9; }
        .topbar-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--brand-secondary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.72rem; flex-shrink: 0;
        }
        .topbar-username { font-size: 0.78rem; font-weight: 600; color: #0f172a; line-height: 1.2; }
        .topbar-role { font-size: 0.66rem; color: #64748b; line-height: 1; }

        /* ══ CONTENT AREA ══ */
        .content-area { padding: 1.4rem; flex: 1; }

        /* ══ CARDS ══ */
        .card { border: none; box-shadow: var(--card-shadow); border-radius: var(--card-radius); overflow: hidden; }
        .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 0.85rem 1.1rem; }
        .card-footer { background: #fafbfc; border-top: 1px solid #f1f5f9; }

        /* Card header with icon — use .card-hdr class */
        .card-hdr {
            display: flex; align-items: center; gap: 12px;
            padding: 0.9rem 1.2rem;
            background: #fff; border-bottom: 1px solid #f1f5f9;
        }
        .card-hdr-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .card-hdr-icon.blue  { background: #eff6ff; color: #2563eb; }
        .card-hdr-icon.green { background: #f0fdf4; color: #16a34a; }
        .card-hdr-icon.amber { background: #fffbeb; color: #d97706; }
        .card-hdr-icon.red   { background: #fef2f2; color: #dc2626; }
        .card-hdr-icon.purple{ background: #f5f3ff; color: #7c3aed; }
        .card-hdr-icon.slate { background: #f1f5f9; color: #475569; }
        .card-hdr-icon.cyan  { background: #ecfeff; color: #0891b2; }
        .card-hdr-title { font-weight: 600; font-size: 0.88rem; color: #0f172a; line-height: 1.25; }
        .card-hdr-sub { font-size: 0.72rem; color: #64748b; margin-top: 1px; }
        .card-hdr-actions { margin-left: auto; display: flex; align-items: center; gap: 6px; }

        /* ══ STAT CARDS ══ */
        .stat-card { border-radius: var(--card-radius); padding: 1.15rem 1.2rem; }
        .stat-card .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1.15; letter-spacing: -0.02em; }
        .stat-card .stat-label { font-size: 0.74rem; color: #64748b; font-weight: 500; margin-top: 3px; }
        .stat-card .stat-change { font-size: 0.7rem; font-weight: 600; }
        .stat-blue   { background:#fff; border-left: 4px solid #3b82f6; } .stat-blue .stat-icon   { background:#eff6ff; color:#2563eb; }
        .stat-green  { background:#fff; border-left: 4px solid #22c55e; } .stat-green .stat-icon  { background:#f0fdf4; color:#16a34a; }
        .stat-amber  { background:#fff; border-left: 4px solid #f59e0b; } .stat-amber .stat-icon  { background:#fffbeb; color:#d97706; }
        .stat-red    { background:#fff; border-left: 4px solid #ef4444; } .stat-red .stat-icon    { background:#fef2f2; color:#dc2626; }
        .stat-purple { background:#fff; border-left: 4px solid #8b5cf6; } .stat-purple .stat-icon { background:#f5f3ff; color:#7c3aed; }
        .stat-cyan   { background:#fff; border-left: 4px solid #06b6d4; } .stat-cyan .stat-icon   { background:#ecfeff; color:#0891b2; }

        /* ══ FORM SECTIONS ══ */
        .fsec { margin-bottom: 1.75rem; }
        .fsec:last-of-type { margin-bottom: 0; }
        .fsec-hdr {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 1rem; padding-bottom: 0.65rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .fsec-num {
            width: 22px; height: 22px; border-radius: 50%;
            background: var(--brand-secondary); color: #fff;
            font-size: 0.67rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .fsec-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.1px; font-weight: 700; color: #475569; }
        .fsec-icon { font-size: 0.9rem; color: #94a3b8; flex-shrink: 0; }

        /* ══ TABLES ══ */
        .table th { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.7px; color: #64748b; background: #f8fafc; font-weight: 600; padding: 0.65rem 0.85rem; border-bottom: 1px solid #e8edf3; white-space: nowrap; }
        .table td { padding: 0.62rem 0.85rem; vertical-align: middle; border-color: #f4f6f9; font-size: 0.845rem; }
        .table tbody tr:hover { background: #fafbfd; }
        .table-responsive { border-radius: 0 0 var(--card-radius) var(--card-radius); }

        /* ══ ACTION BUTTONS ══ */
        .btn-act {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 28px; border-radius: 6px;
            border: 1px solid transparent; font-size: 0.8rem;
            cursor: pointer; transition: all 0.15s; text-decoration: none;
            background: none;
        }
        .btn-act.blue  { border-color: #bfdbfe; color: #2563eb; } .btn-act.blue:hover  { background: #eff6ff; border-color: #93c5fd; }
        .btn-act.green { border-color: #bbf7d0; color: #16a34a; } .btn-act.green:hover { background: #f0fdf4; border-color: #86efac; }
        .btn-act.red   { border-color: #fecaca; color: #dc2626; } .btn-act.red:hover   { background: #fef2f2; border-color: #fca5a5; }
        .btn-act.slate { border-color: #e2e8f0; color: #475569; } .btn-act.slate:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .btn-act.amber { border-color: #fde68a; color: #d97706; } .btn-act.amber:hover { background: #fffbeb; border-color: #fcd34d; }
        .btn-act.purple{ border-color: #ddd6fe; color: #7c3aed; } .btn-act.purple:hover{ background: #f5f3ff; border-color: #c4b5fd; }

        /* ══ BADGES ══ */
        .badge { font-weight: 500; letter-spacing: 0.15px; }

        /* ══ FORMS ══ */
        .form-control, .form-select {
            border: 1px solid #e2e8f0; border-radius: 8px;
            font-size: 0.865rem; color: #1e293b;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-secondary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
            outline: none;
        }
        .form-control:disabled, .form-control[readonly] { background: #f8fafc; color: #64748b; }
        .form-label { font-size: 0.79rem; font-weight: 600; color: #374151; margin-bottom: 0.3rem; }
        .input-group-text { background: #f8fafc; border-color: #e2e8f0; color: #64748b; font-size: 0.865rem; }

        /* ══ BUTTONS ══ */
        .btn { border-radius: 8px; font-size: 0.855rem; font-weight: 500; transition: all 0.15s; }
        .btn-primary { background: var(--brand-secondary); border-color: var(--brand-secondary); }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
        .btn-success:hover { box-shadow: 0 4px 12px rgba(34,197,94,0.3); }
        .btn-sm { font-size: 0.8rem; padding: 0.3rem 0.75rem; }
        .btn-lg { font-size: 0.95rem; padding: 0.65rem 1.5rem; }

        /* ══ PAGE HEADER ══ */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; }
        .page-header h5 { margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .page-header .page-subtitle { font-size: 0.775rem; color: #64748b; margin-top: 3px; }
        .page-header-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        /* ══ ALERTS ══ */
        .alert { border-radius: 10px; border: none; font-size: 0.855rem; }
        .alert-success { background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
        .alert-danger  { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-info    { background: #eff6ff; color: #1e40af; border-left: 4px solid #3b82f6; }
        .alert-warning { background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }

        /* ══ FILTER CARD ══ */
        .filter-card { background: #fff; border-radius: var(--card-radius); padding: 0.85rem 1.1rem; box-shadow: var(--card-shadow); margin-bottom: 1rem; }

        /* ══ MOBILE OVERLAY ══ */
        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.5); z-index: 1039;
            backdrop-filter: blur(3px);
        }

        /* ══ ANIMATIONS ══ */
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        .content-area > * { animation: fadeUp 0.22s ease; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
            #topbar #btn-menu { display: flex; align-items: center; }
            #sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity 0.28s; }
            #sidebar-overlay.open { opacity: 1; pointer-events: auto; }
            .content-area { padding: 1rem; }
            #topbar { padding: 0 1rem; }
            .topbar-clock { display: none !important; }
        }
        @media (max-width: 575.98px) {
            .page-header { flex-direction: column; align-items: flex-start; }
            .table th, .table td { font-size: 0.77rem; padding: 0.52rem 0.65rem; }
            .content-area { padding: 0.75rem; }
        }

        /* ══ SCROLLBAR ══ */
        #sidebar::-webkit-scrollbar { width: 3px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }

        /* ══ AUTH ══ */
        .auth-wrapper {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0f2a56 0%, #1a3a6b 40%, #2563eb 100%);
            padding: 1rem; position: relative; overflow: hidden;
        }
        .auth-wrapper::before {
            content: ''; position: absolute; width: 500px; height: 500px;
            border-radius: 50%; background: rgba(255,255,255,0.03);
            top: -150px; right: -100px;
        }
        .auth-wrapper::after {
            content: ''; position: absolute; width: 350px; height: 350px;
            border-radius: 50%; background: rgba(255,255,255,0.03);
            bottom: -100px; left: -80px;
        }
        .auth-card {
            background: #fff; border-radius: 18px; padding: 2.5rem 2.25rem;
            width: 100%; max-width: 430px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3), 0 8px 20px rgba(0,0,0,0.2);
            position: relative; z-index: 1;
        }
    </style>
    @yield('styles')
</head>
<body>
@auth
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ═══ SIDEBAR ═══ --}}
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
        <span class="nav-section">Principal</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

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

        @role('superadmin')
        <span class="nav-section">Administración</span>
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

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div style="min-width:0">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <span style="font-size:0.62rem;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.5px">
                    {{ auth()->user()->getRoleNames()->first() ?? 'sin rol' }}
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Cerrar sesión
            </button>
        </form>
    </div>
</nav>

{{-- ═══ MAIN CONTENT ═══ --}}
<div id="main-content">
    {{-- TOPBAR --}}
    <div id="topbar">
        <div class="topbar-left">
            <button id="btn-menu" onclick="toggleSidebar()">
                <i class="bi bi-list" style="font-size:1.15rem"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" style="color:var(--brand-secondary);text-decoration:none">
                            <i class="bi bi-house-door"></i>
                        </a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <div class="topbar-clock d-none d-md-flex">
                <i class="bi bi-clock" style="font-size:0.75rem"></i>
                <span id="topbar-clock">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="topbar-divider d-none d-md-block"></div>
            <div class="topbar-profile">
                <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <div class="d-none d-lg-block">
                    <div class="topbar-username">{{ auth()->user()->name }}</div>
                    <div class="topbar-role">{{ strtoupper(auth()->user()->getRoleNames()->first() ?? '') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    <div style="padding: 0.75rem 1.4rem 0">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <strong>Corrija los siguientes errores:</strong>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            <ul class="mb-0 ps-4" style="font-size:0.82rem">
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
{{-- AUTH PAGES --}}
<div class="auth-wrapper">
    @yield('content')
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
}
(function tick() {
    const el = document.getElementById('topbar-clock');
    if (el) {
        const now = new Date();
        el.textContent = now.toLocaleDateString('es-PE') + ' ' + now.toLocaleTimeString('es-PE',{hour:'2-digit',minute:'2-digit'});
    }
    setTimeout(tick, 30000);
})();
document.querySelectorAll('#sidebar .nav-link').forEach(l => {
    l.addEventListener('click', () => { if (window.innerWidth < 992) closeSidebar(); });
});
</script>
@yield('scripts')
</body>
</html>
