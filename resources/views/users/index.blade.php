@extends('layouts.app')
@section('title', 'Usuarios')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-people text-primary"></i> Usuarios del Sistema</h5>
        <div class="page-subtitle">Gestión de accesos y roles de usuarios</div>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                    <th class="text-end pe-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                 style="width:36px;height:36px;background:var(--brand-secondary);font-size:0.82rem">
                                {{ strtoupper(substr($u->name,0,2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $u->name }}</div>
                                @if($u->id === auth()->id())
                                <small class="text-primary" style="font-size:0.7rem"><i class="bi bi-person-check me-1"></i>Tú</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-muted" style="font-size:0.84rem">{{ $u->email }}</td>
                    <td>
                        @foreach($u->roles as $rol)
                        @php
                            $rolColors = ['superadmin'=>'danger','administrador'=>'primary','cajero'=>'success','consulta'=>'secondary'];
                            $rolColor = $rolColors[$rol->name] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $rolColor }}-subtle text-{{ $rolColor }} border border-{{ $rolColor }}-subtle">
                            {{ strtoupper($rol->name) }}
                        </span>
                        @endforeach
                    </td>
                    <td class="text-muted" style="font-size:0.84rem">{{ $u->telefono ?? '—' }}</td>
                    <td>
                        @if($u->activo)
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                        @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-muted" style="font-size:0.8rem">
                        {{ $u->ultimo_acceso?->format('d/m/Y H:i') ?? 'Nunca' }}
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('users.edit', $u) }}" class="btn-act slate" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('users.toggle', $u) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-act {{ $u->activo ? 'amber' : 'green' }}"
                                        title="{{ $u->activo ? 'Desactivar' : 'Activar' }}">
                                    <i class="bi bi-{{ $u->activo ? 'pause-circle' : 'play-circle' }}"></i>
                                </button>
                            </form>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $u) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($u->name) }}? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-act red" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div style="font-size:2.5rem;color:#e2e8f0"><i class="bi bi-people"></i></div>
                        <div class="text-muted mt-2">No hay usuarios registrados</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
