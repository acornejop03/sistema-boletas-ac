@extends('layouts.app')
@section('title', 'Cursos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Cursos</li>
@endsection

@section('styles')
<style>
/* ══ COURSES INDEX ══ */

/* ── Header ── */
.courses-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem;
}
.courses-title {
    font-size: 1.1rem; font-weight: 800; color: #0f172a;
    display: flex; align-items: center; gap: 10px;
}
.courses-title-icon {
    width: 38px; height: 38px; border-radius: 11px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem;
    box-shadow: 0 4px 12px rgba(37,99,235,.35);
}
.courses-subtitle { font-size: .775rem; color: #64748b; margin-top: 2px; }

/* ── Summary strips ── */
.course-strips {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .75rem; margin-bottom: 1.1rem;
}
@media(max-width:991px){ .course-strips{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:480px){ .course-strips{ grid-template-columns: repeat(2,1fr); } }

.cstrip {
    background: #fff; border-radius: 14px;
    padding: .85rem 1rem;
    border: 1px solid rgba(0,0,0,.05);
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    display: flex; align-items: center; gap: 10px;
    position: relative; overflow: hidden;
    transition: box-shadow .2s, transform .2s;
}
.cstrip::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    border-radius: 14px 14px 0 0;
}
.cstrip:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
.cs-blue ::before { background: linear-gradient(90deg,#3b82f6,#2563eb); }
.cs-purple::before { background: linear-gradient(90deg,#8b5cf6,#7c3aed); }
.cs-green ::before { background: linear-gradient(90deg,#22c55e,#16a34a); }
.cs-amber ::before { background: linear-gradient(90deg,#f59e0b,#d97706); }

.cstrip-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.cs-blue  .cstrip-icon { background: #eff6ff; color: #2563eb; }
.cs-purple.cstrip-icon { background: #f5f3ff; color: #7c3aed; }
.cs-purple .cstrip-icon { background: #f5f3ff; color: #7c3aed; }
.cs-green  .cstrip-icon { background: #f0fdf4; color: #16a34a; }
.cs-amber  .cstrip-icon { background: #fffbeb; color: #d97706; }

.cstrip-val { font-size: 1.25rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.1; }
.cstrip-lbl { font-size: .71rem; color: #64748b; font-weight: 500; margin-top: 1px; }
.cs-blue   .cstrip-val { color: #1d4ed8; }
.cs-purple .cstrip-val { color: #6d28d9; }
.cs-green  .cstrip-val { color: #15803d; }
.cs-amber  .cstrip-val { color: #b45309; }

/* ── Filter ── */
.course-filter {
    background: #fff; border-radius: 14px;
    border: 1px solid rgba(0,0,0,.05);
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    padding: .85rem 1.1rem;
    margin-bottom: 1rem;
}

/* ── View toggle ── */
.view-toggle {
    display: flex; gap: 4px;
    background: #f1f5f9; border-radius: 8px; padding: 3px;
}
.vt-btn {
    width: 30px; height: 30px; border-radius: 6px; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; color: #64748b; background: transparent;
    cursor: pointer; transition: all .15s;
}
.vt-btn.active { background: #fff; color: #2563eb; box-shadow: 0 1px 4px rgba(0,0,0,.1); }

/* ── Card grid ── */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
@media(max-width:991px){ .courses-grid{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:575px){ .courses-grid{ grid-template-columns: 1fr; } }

.course-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: transform .2s, box-shadow .2s;
}
.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.1);
}

/* Color bar top */
.cc-bar { height: 5px; }

/* Card header area */
.cc-head {
    padding: 1rem 1.1rem .75rem;
    display: flex; justify-content: space-between; align-items: flex-start; gap: .5rem;
}
.cc-code {
    font-family: 'Roboto Mono','Courier New', monospace;
    font-size: .68rem; font-weight: 700;
    background: #f1f5f9; color: #475569;
    padding: 2px 8px; border-radius: 20px;
    border: 1px solid #e2e8f0;
    white-space: nowrap;
}
.cc-level {
    font-size: .67rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; border: 1px solid;
    white-space: nowrap;
}
.level-basico     { background: #f0fdf4; color: #15803d; border-color: #86efac; }
.level-intermedio { background: #fffbeb; color: #b45309; border-color: #fcd34d; }
.level-avanzado   { background: #fef2f2; color: #b91c1c; border-color: #fca5a5; }

/* Name */
.cc-name {
    padding: 0 1.1rem .5rem;
    font-size: .95rem; font-weight: 700; color: #0f172a;
    line-height: 1.3;
}
.cc-cat {
    padding: 0 1.1rem .65rem;
    display: flex; align-items: center; gap: 6px;
    font-size: .75rem; color: #64748b;
}
.cc-cat-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }

/* Divider */
.cc-div { height: 1px; background: #f1f5f9; margin: 0 1.1rem; }

/* Prices */
.cc-prices {
    padding: .75rem 1.1rem;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: .5rem;
}
.cc-price-item { display: flex; flex-direction: column; }
.cc-price-lbl { font-size: .66rem; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: .04em; }
.cc-price-val { font-size: .95rem; font-weight: 800; color: #0f172a; margin-top: 1px; }
.cc-price-val.pension { color: #15803d; }

/* Stats row */
.cc-stats {
    padding: 0 1.1rem .75rem;
    display: flex; gap: .5rem; flex-wrap: wrap;
}
.cc-stat {
    display: flex; align-items: center; gap: 4px;
    font-size: .72rem; color: #64748b;
    background: #f8fafc; border: 1px solid #f1f5f9;
    border-radius: 6px; padding: 3px 8px;
}
.cc-stat i { font-size: .75rem; }

/* Card footer */
.cc-foot {
    margin-top: auto;
    padding: .65rem 1.1rem;
    background: #fafbfc;
    border-top: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.cc-igv {
    font-size: .68rem; font-weight: 600;
    padding: 2px 8px; border-radius: 20px; border: 1px solid;
}
.igv-exo { background: #f0fdf4; color: #15803d; border-color: #86efac; }
.igv-gra { background: #fffbeb; color: #b45309; border-color: #fcd34d; }

.cc-actions { display: flex; gap: 4px; }

/* ── Table (vista lista) ── */
.courses-table-wrap {
    background: #fff; border-radius: 16px;
    border: 1px solid rgba(0,0,0,.05);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    overflow: hidden;
}
.courses-table-hdr {
    padding: .75rem 1.2rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    flex-wrap: wrap;
}

/* ── Empty state ── */
.empty-courses {
    text-align: center; padding: 4rem 1rem;
    background: #fff; border-radius: 16px;
    border: 1px solid rgba(0,0,0,.05);
}
.empty-courses-icon { font-size: 3.5rem; color: #e2e8f0; margin-bottom: .75rem; }

/* ── Pagination ── */
.pagination { margin: 0; }
.page-link { border-radius: 8px!important; margin: 0 2px; font-size: .8rem; }
</style>
@endsection

@section('content')

{{-- ── HEADER ── --}}
<div class="courses-header">
    <div>
        <div class="courses-title">
            <div class="courses-title-icon"><i class="bi bi-book-fill"></i></div>
            Catálogo de Cursos
        </div>
        <div class="courses-subtitle">Gestión completa del catálogo de cursos de la academia</div>
    </div>
    @can('crear cursos')
    <a href="{{ route('courses.create') }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:.83rem;font-weight:600;border:1.5px solid #bfdbfe;background:#eff6ff;color:#2563eb;text-decoration:none;transition:all .2s"
       onmouseover="this.style.background='#2563eb';this.style.color='#fff'"
       onmouseout="this.style.background='#eff6ff';this.style.color='#2563eb'">
        <i class="bi bi-plus-circle-fill"></i> Nuevo Curso
    </a>
    @endcan
</div>

{{-- ── SUMMARY STRIPS ── --}}
@php
    $allCourses   = $courses->getCollection();
    $totalCursos  = $courses->total();
    $totalCats    = $categories->count();
    $totalMatric  = $allCourses->sum(fn($c) => $c->enrollments_count ?? $c->enrollments->count());
    $avgPension   = $courses->total() > 0 ? $allCourses->avg('precio_pension') : 0;
@endphp
<div class="course-strips">
    <div class="cstrip cs-blue">
        <div class="cstrip-icon"><i class="bi bi-book-fill"></i></div>
        <div>
            <div class="cstrip-val">{{ $totalCursos }}</div>
            <div class="cstrip-lbl">Cursos en catálogo</div>
        </div>
    </div>
    <div class="cstrip cs-purple">
        <div class="cstrip-icon"><i class="bi bi-tags-fill"></i></div>
        <div>
            <div class="cstrip-val">{{ $totalCats }}</div>
            <div class="cstrip-lbl">Categorías</div>
        </div>
    </div>
    <div class="cstrip cs-green">
        <div class="cstrip-icon"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="cstrip-val">{{ $totalMatric }}</div>
            <div class="cstrip-lbl">Matrículas (página)</div>
        </div>
    </div>
    <div class="cstrip cs-amber">
        <div class="cstrip-icon"><i class="bi bi-currency-dollar"></i></div>
        <div>
            <div class="cstrip-val">S/ {{ number_format($avgPension, 0) }}</div>
            <div class="cstrip-lbl">Pensión promedio</div>
        </div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<div class="course-filter">
    <form method="GET" id="filterForm">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted" style="font-size:.8rem"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control border-start-0 ps-0"
                           placeholder="Buscar por nombre o código...">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="nivel" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Todos los niveles</option>
                    @foreach(['BASICO'=>'Básico','INTERMEDIO'=>'Intermedio','AVANZADO'=>'Avanzado'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('nivel')==$v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2 ms-auto">
                {{-- View toggle --}}
                <div class="view-toggle">
                    <button type="button" class="vt-btn {{ request('view','grid')=='grid' ? 'active' : '' }}"
                            onclick="setView('grid')" title="Vista tarjetas">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                    <button type="button" class="vt-btn {{ request('view')=='list' ? 'active' : '' }}"
                            onclick="setView('list')" title="Vista lista">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-funnel me-1"></i>Filtrar
                </button>
                <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-secondary" title="Limpiar">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>
        <input type="hidden" name="view" id="viewInput" value="{{ request('view','grid') }}">
    </form>
</div>

@php
    $currentView = request('view','grid');
    $levelClass  = ['BASICO'=>'level-basico','INTERMEDIO'=>'level-intermedio','AVANZADO'=>'level-avanzado'];
@endphp

{{-- ══ VISTA TARJETAS ══ --}}
@if($currentView === 'grid')

@if($courses->count() > 0)
<div class="courses-grid">
    @foreach($courses as $c)
    @php
        $catColor = $c->category->color ?? '#3b82f6';
        $lvlClass = $levelClass[$c->nivel] ?? 'level-basico';
        $enrollCount = $c->enrollments->count();
    @endphp
    <div class="course-card">
        {{-- Barra color categoría --}}
        <div class="cc-bar" style="background:{{ $catColor }}"></div>

        {{-- Head: código + nivel --}}
        <div class="cc-head">
            <span class="cc-code">{{ $c->codigo }}</span>
            <span class="cc-level {{ $lvlClass }}">{{ ucfirst(strtolower($c->nivel)) }}</span>
        </div>

        {{-- Nombre --}}
        <div class="cc-name">{{ $c->nombre }}</div>

        {{-- Categoría --}}
        <div class="cc-cat">
            <span class="cc-cat-dot" style="background:{{ $catColor }}"></span>
            {{ $c->category->nombre }}
        </div>

        <div class="cc-div"></div>

        {{-- Precios --}}
        <div class="cc-prices">
            <div class="cc-price-item">
                <span class="cc-price-lbl">Matrícula</span>
                <span class="cc-price-val">S/ {{ number_format($c->precio_matricula, 2) }}</span>
            </div>
            <div class="cc-price-item">
                <span class="cc-price-lbl">Pensión</span>
                <span class="cc-price-val pension">S/ {{ number_format($c->precio_pension, 2) }}</span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="cc-stats">
            <span class="cc-stat">
                <i class="bi bi-clock"></i> {{ $c->duracion_meses }} mes(es)
            </span>
            <span class="cc-stat">
                <i class="bi bi-people"></i> {{ $enrollCount }} alumno{{ $enrollCount != 1 ? 's' : '' }}
            </span>
            @if($c->precio_materiales > 0)
            <span class="cc-stat">
                <i class="bi bi-box"></i> Mat. S/ {{ number_format($c->precio_materiales, 2) }}
            </span>
            @endif
        </div>

        {{-- Footer --}}
        <div class="cc-foot">
            <span class="cc-igv {{ $c->afecto_igv ? 'igv-gra' : 'igv-exo' }}">
                <i class="bi bi-{{ $c->afecto_igv ? 'receipt' : 'shield-check' }} me-1"></i>
                {{ $c->afecto_igv ? 'Gravado IGV' : 'Exonerado' }}
            </span>
            <div class="cc-actions">
                <a href="{{ route('courses.show', $c) }}" class="btn-act blue" title="Ver detalle">
                    <i class="bi bi-eye"></i>
                </a>
                @can('editar cursos')
                <a href="{{ route('courses.edit', $c) }}" class="btn-act slate" title="Editar">
                    <i class="bi bi-pencil"></i>
                </a>
                @endcan
                @can('eliminar cursos')
                <form method="POST" action="{{ route('courses.destroy', $c) }}"
                      onsubmit="return confirm('¿Eliminar el curso {{ addslashes($c->nombre) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-act red" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination en grid --}}
@if($courses->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $courses->appends(request()->except('page'))->links() }}
</div>
@endif

@else
<div class="empty-courses">
    <div class="empty-courses-icon"><i class="bi bi-book"></i></div>
    <div class="fw-semibold" style="color:#374151;margin-bottom:4px">No se encontraron cursos</div>
    <div style="font-size:.82rem;color:#94a3b8;margin-bottom:1rem">Prueba ajustando los filtros de búsqueda</div>
    @can('crear cursos')
    <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Crear primer curso
    </a>
    @endcan
</div>
@endif

{{-- ══ VISTA LISTA ══ --}}
@else

<div class="courses-table-wrap">
    <div class="courses-table-hdr">
        <div style="font-size:.85rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px">
            <div style="width:26px;height:26px;border-radius:7px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:.8rem">
                <i class="bi bi-list-ul"></i>
            </div>
            Listado de cursos
        </div>
        <span style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:600">
            {{ $courses->total() }} curso(s)
        </span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size:.84rem">
            <thead>
                <tr>
                    <th class="d-none d-sm-table-cell" style="width:100px">Código</th>
                    <th>Curso</th>
                    <th class="d-none d-md-table-cell">Categoría</th>
                    <th class="d-none d-lg-table-cell">Nivel</th>
                    <th class="d-none d-lg-table-cell text-end">Matrícula</th>
                    <th class="text-end">Pensión</th>
                    <th class="d-none d-md-table-cell">IGV</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                @php $catColor = $c->category->color ?? '#3b82f6'; @endphp
                <tr style="border-left: 3px solid {{ $catColor }}">
                    <td class="d-none d-sm-table-cell">
                        <code style="font-size:.78rem;color:#2563eb;font-weight:700">{{ $c->codigo }}</code>
                    </td>
                    <td>
                        <div class="fw-semibold" style="color:#0f172a">{{ $c->nombre }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:2px">
                            <i class="bi bi-clock me-1"></i>{{ $c->duracion_meses }} mes(es)
                            · {{ $c->enrollments->count() }} alumno(s)
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge" style="background:{{ $catColor }};font-size:.7rem;padding:3px 9px">
                            {{ $c->category->nombre }}
                        </span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @php $lvlClass = $levelClass[$c->nivel] ?? 'level-basico'; @endphp
                        <span class="cc-level {{ $lvlClass }}">{{ ucfirst(strtolower($c->nivel)) }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell text-end text-muted">
                        S/ {{ number_format($c->precio_matricula, 2) }}
                    </td>
                    <td class="text-end fw-bold" style="color:#15803d">
                        S/ {{ number_format($c->precio_pension, 2) }}
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($c->afecto_igv)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size:.7rem">Gravado</span>
                        @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem">Exonerado</span>
                        @endif
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('courses.show', $c) }}" class="btn-act blue" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('editar cursos')
                            <a href="{{ route('courses.edit', $c) }}" class="btn-act slate" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('eliminar cursos')
                            <form method="POST" action="{{ route('courses.destroy', $c) }}"
                                  onsubmit="return confirm('¿Eliminar {{ addslashes($c->nombre) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-act red" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-book d-block" style="font-size:2.5rem;color:#e2e8f0"></i>
                        <div class="text-muted mt-2">No se encontraron cursos</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($courses->hasPages())
    <div style="padding:.65rem 1.2rem;background:#f8fafc;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem">
        <span style="font-size:.78rem;color:#64748b">
            Mostrando <strong>{{ $courses->firstItem() }}–{{ $courses->lastItem() }}</strong> de <strong>{{ $courses->total() }}</strong>
        </span>
        {{ $courses->appends(request()->except('page'))->links() }}
    </div>
    @endif
</div>

@endif

@endsection

@section('scripts')
<script>
function setView(v) {
    document.getElementById('viewInput').value = v;
    document.getElementById('filterForm').submit();
}
</script>
@endsection
