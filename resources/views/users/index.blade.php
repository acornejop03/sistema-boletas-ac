@extends('layouts.app')
@section('title', 'Usuarios')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Usuarios del Sistema</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Nuevo Usuario</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Teléfono</th><th>Estado</th><th>Último acceso</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td><div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:#2d5a9b;font-size:0.85rem;flex-shrink:0">
                            {{ strtoupper(substr($u->name,0,1)) }}
                        </div>
                        <div><div class="fw-semibold">{{ $u->name }}</div></div>
                    </div></td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @foreach($u->roles as $rol)
                        <span class="badge bg-primary">{{ $rol->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $u->telefono ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $u->activo ? 'bg-success' : 'bg-secondary' }}">{{ $u->activo ? 'Activo' : 'Inactivo' }}</span>
                    </td>
                    <td class="small text-muted">{{ $u->ultimo_acceso?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('users.toggle', $u) }}">
                                @csrf
                                <button class="btn btn-outline-{{ $u->activo ? 'warning' : 'success' }}" title="{{ $u->activo ? 'Desactivar' : 'Activar' }}">
                                    <i class="bi bi-{{ $u->activo ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('¿Eliminar usuario {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No hay usuarios</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">{{ $users->links() }}</div>
    @endif
</div>
@endsection
