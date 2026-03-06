@extends('layouts.app')
@section('title', 'Categorías')
@section('breadcrumb')
    <li class="breadcrumb-item active">Categorías</li>
@endsection
@section('styles')
<style>
.cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }

.cat-card {
    background: #fff; border-radius: 16px; overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform 0.2s cubic-bezier(.34,1.56,.64,1), box-shadow 0.2s;
    cursor: pointer;
}
.cat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(0,0,0,0.12); }

.cat-banner {
    height: 90px; position: relative;
    display: flex; align-items: center; justify-content: center;
}
.cat-banner::before {
    content: ''; position: absolute; inset: 0;
    background-image: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.18) 0%, transparent 50%),
                      radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
}
.cat-emoji { font-size: 2.4rem; position: relative; z-index: 1; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.15)); line-height: 1; }
.cat-status-dot {
    position: absolute; top: 10px; right: 12px;
    width: 9px; height: 9px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.8);
}
.cat-body { padding: 1rem 1.1rem 0.75rem; }
.cat-name { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
.cat-desc { font-size: 0.76rem; color: #64748b; min-height: 16px; }
.cat-color-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 2px 8px; border-radius: 20px;
    font-size: 0.68rem; font-weight: 600; margin-top: 6px;
}
.cat-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.6rem 1.1rem; border-top: 1px solid #f1f5f9; background: #fafbfc;
}
.cat-actions { display: flex; gap: 5px; }

/* Summary strip */
.summary-strip {
    background: #fff; border-radius: 12px; padding: 0.85rem 1.25rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; margin-bottom: 1.25rem;
}
.summary-item { display: flex; align-items: center; gap: 10px; }
.summary-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.summary-val { font-size: 1.3rem; font-weight: 800; line-height: 1; }
.summary-lbl { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

/* Modal overrides */
.modal-cat-header { padding: 0; border: none; }
.modal-cat-banner { height: 110px; display: flex; align-items: center; justify-content: center; position: relative; border-radius: 12px 12px 0 0; }
.modal-cat-banner .close-btn {
    position: absolute; top: 10px; right: 12px;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,0.2); border: none; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; cursor: pointer; transition: background 0.15s;
}
.modal-cat-banner .close-btn:hover { background: rgba(255,255,255,0.35); }
.modal-body { padding: 1.25rem; }
.modal-course-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.55rem 0; border-bottom: 1px solid #f1f5f9; gap: 10px;
}
.modal-course-item:last-child { border-bottom: none; }
.modal-content { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
</style>
@endsection
@section('content')

<div class="page-header">
    <div>
        <h5><i class="bi bi-tags text-primary"></i> Categorías de Cursos</h5>
        <div class="page-subtitle">Organización temática del catálogo académico</div>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nueva Categoría
        </a>
    </div>
</div>

{{-- SUMMARY STRIP --}}
<div class="summary-strip">
    <div class="summary-item">
        <div class="summary-icon" style="background:#eff6ff;color:#2563eb"><i class="bi bi-tags-fill"></i></div>
        <div>
            <div class="summary-val" style="color:#1d4ed8">{{ $categories->total() }}</div>
            <div class="summary-lbl">Categorías</div>
        </div>
    </div>
    <div class="summary-item">
        <div class="summary-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="summary-val" style="color:#15803d">{{ $categories->where('activo', true)->count() }}</div>
            <div class="summary-lbl">Activas</div>
        </div>
    </div>
    <div class="summary-item">
        <div class="summary-icon" style="background:#f5f3ff;color:#7c3aed"><i class="bi bi-book-fill"></i></div>
        <div>
            <div class="summary-val" style="color:#6d28d9">{{ $categories->sum('courses_count') }}</div>
            <div class="summary-lbl">Cursos totales</div>
        </div>
    </div>
    <div class="summary-item">
        <div class="summary-icon" style="background:#fffbeb;color:#d97706"><i class="bi bi-bar-chart-fill"></i></div>
        <div>
            <div class="summary-val" style="color:#b45309">
                {{ $categories->total() > 0 ? round($categories->sum('courses_count') / $categories->total(), 1) : 0 }}
            </div>
            <div class="summary-lbl">Cursos / categ.</div>
        </div>
    </div>
</div>

{{-- CARD GRID --}}
@if($categories->isEmpty())
<div class="card">
    <div class="text-center py-5">
        <div style="font-size:3.5rem;color:#e2e8f0"><i class="bi bi-tags"></i></div>
        <div class="fw-semibold text-muted mt-2">No hay categorías registradas</div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
            <i class="bi bi-plus-circle me-1"></i>Crear primera categoría
        </a>
    </div>
</div>
@else
<div class="cat-grid">
    @foreach($categories as $cat)
    <div class="cat-card" onclick="openCatModal({{ $cat->id }})">

        <div class="cat-banner" style="background:linear-gradient(135deg,{{ $cat->color }},{{ $cat->color }}bb)">
            <div class="cat-emoji">{{ $cat->icono ?? '📚' }}</div>
            <div class="cat-status-dot" style="background:{{ $cat->activo ? '#4ade80' : '#94a3b8' }}"></div>
        </div>

        <div class="cat-body">
            <div class="cat-name">{{ $cat->nombre }}</div>
            <div class="cat-desc">{{ $cat->descripcion ?: 'Sin descripción' }}</div>
            <div class="cat-color-pill" style="background:{{ $cat->color }}18;color:{{ $cat->color }};border:1px solid {{ $cat->color }}44">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $cat->color }};display:inline-block;flex-shrink:0"></span>
                {{ $cat->color }}
            </div>
        </div>

        <div class="cat-footer" onclick="event.stopPropagation()">
            <div class="d-flex align-items-center gap-2" style="font-size:0.78rem;font-weight:600;color:#475569">
                <i class="bi bi-book" style="color:{{ $cat->color }}"></i>
                {{ $cat->courses_count }} curso{{ $cat->courses_count != 1 ? 's' : '' }}
            </div>
            <div class="cat-actions">
                <button type="button" class="btn-act blue" title="Ver detalle" onclick="openCatModal({{ $cat->id }})">
                    <i class="bi bi-eye"></i>
                </button>
                @can('editar cursos')
                <a href="{{ route('categories.edit', $cat->id) }}" class="btn-act slate" title="Editar">
                    <i class="bi bi-pencil"></i>
                </a>
                @endcan
                @can('eliminar cursos')
                <form method="POST" action="{{ route('categories.destroy', $cat->id) }}"
                      onsubmit="return confirm('¿Eliminar la categoría {{ addslashes($cat->nombre) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-act red" title="Eliminar"
                        {{ $cat->courses_count > 0 ? 'disabled' : '' }}>
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endcan
            </div>
        </div>

    </div>
    @endforeach
</div>

@if($categories->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $categories->links() }}
</div>
@endif
@endif

{{-- ═══════════════ MODAL DETALLE ═══════════════ --}}
<div class="modal fade" id="catModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px">
        <div class="modal-content">
            <div class="modal-cat-header">
                <div class="modal-cat-banner" id="modalBanner">
                    <span id="modalEmoji" style="font-size:3rem;filter:drop-shadow(0 2px 8px rgba(0,0,0,0.2))"></span>
                    <button class="close-btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h5 class="fw-bold mb-1" id="modalNombre"></h5>
                        <div class="text-muted" style="font-size:0.82rem" id="modalDesc"></div>
                    </div>
                    <div id="modalBadgeActivo"></div>
                </div>

                <div class="d-flex gap-2 mb-3" style="flex-wrap:wrap">
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:6px 12px;font-size:0.78rem">
                        <span class="text-muted">Color:</span>
                        <span id="modalColorPill" class="fw-semibold ms-1"></span>
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:6px 12px;font-size:0.78rem">
                        <span class="text-muted">Cursos:</span>
                        <span id="modalCursosCount" class="fw-bold ms-1"></span>
                    </div>
                </div>

                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;color:#94a3b8;margin-bottom:0.5rem">
                    <i class="bi bi-book me-1"></i> Cursos en esta categoría
                </div>
                <div id="modalCursosList" style="max-height:220px;overflow-y:auto;border:1px solid #f1f5f9;border-radius:8px;padding:0 0.75rem"></div>

                <div class="d-flex gap-2 mt-3" id="modalActions"></div>
            </div>
        </div>
    </div>
</div>

{{-- Datos de categorías en JSON para el modal --}}
<script id="catData" type="application/json">
{!! json_encode($categories->map(function($cat) {
    return [
        'id'           => $cat->id,
        'nombre'       => $cat->nombre,
        'descripcion'  => $cat->descripcion,
        'color'        => $cat->color,
        'icono'        => $cat->icono ?? '📚',
        'activo'       => $cat->activo,
        'courses_count'=> $cat->courses_count,
        'courses'      => $cat->courses->map(fn($c) => [
            'id'             => $c->id,
            'codigo'         => $c->codigo,
            'nombre'         => $c->nombre,
            'nivel'          => $c->nivel,
            'precio_pension' => $c->precio_pension,
            'activo'         => $c->activo,
        ]),
        'edit_url'    => route('categories.edit', $cat->id),
        'courses_url' => route('courses.index', ['category_id' => $cat->id]),
    ];
})->keyBy('id')) !!}
</script>

@endsection
@section('scripts')
<script>
const catData = JSON.parse(document.getElementById('catData').textContent);

function openCatModal(id) {
    const c = catData[id];
    if (!c) return;

    // Banner
    document.getElementById('modalBanner').style.background =
        `linear-gradient(135deg, ${c.color}, ${c.color}bb)`;
    document.getElementById('modalEmoji').textContent = c.icono;

    // Nombre + desc
    document.getElementById('modalNombre').textContent = c.nombre;
    document.getElementById('modalDesc').textContent = c.descripcion || 'Sin descripción';

    // Badge activo
    document.getElementById('modalBadgeActivo').innerHTML = c.activo
        ? `<span class="badge bg-success-subtle text-success border border-success-subtle">Activa</span>`
        : `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactiva</span>`;

    // Color pill
    document.getElementById('modalColorPill').innerHTML =
        `<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${c.color};margin-right:4px;vertical-align:middle"></span>${c.color}`;

    // Cursos count
    document.getElementById('modalCursosCount').textContent = c.courses_count;

    // Lista de cursos
    const list = document.getElementById('modalCursosList');
    if (!c.courses.length) {
        list.innerHTML = `<div class="text-center text-muted py-3" style="font-size:0.82rem">Sin cursos en esta categoría</div>`;
    } else {
        list.innerHTML = c.courses.map(curso => `
            <div class="modal-course-item">
                <div style="min-width:0">
                    <div class="fw-semibold" style="font-size:0.85rem">${curso.nombre}</div>
                    <div style="font-size:0.72rem;color:#94a3b8"><code>${curso.codigo}</code> · ${curso.nivel}</div>
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="fw-bold text-success" style="font-size:0.82rem">S/ ${parseFloat(curso.precio_pension).toFixed(2)}</div>
                    <span class="badge ${curso.activo ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}" style="font-size:0.65rem">${curso.activo ? 'Activo' : 'Inactivo'}</span>
                </div>
            </div>
        `).join('');
    }

    // Acciones
    document.getElementById('modalActions').innerHTML = `
        <a href="${c.edit_url}" class="btn btn-primary btn-sm flex-grow-1">
            <i class="bi bi-pencil me-1"></i>Editar categoría
        </a>
        <a href="${c.courses_url}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-book me-1"></i>Ver cursos
        </a>
    `;

    new bootstrap.Modal(document.getElementById('catModal')).show();
}
</script>
@endsection
