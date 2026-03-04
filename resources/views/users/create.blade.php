@extends('layouts.app')
@section('title', 'Nuevo Usuario')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-7">
<div class="card">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-person-plus me-2"></i>Crear Usuario</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Nombre completo *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Contraseña *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Confirmar contraseña *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Rol *</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">Seleccionar rol...</option>
                    @foreach($roles as $rol)
                    <option value="{{ $rol->name }}" {{ old('role')==$rol->name?'selected':'' }}>{{ ucfirst($rol->name) }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Crear Usuario</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
