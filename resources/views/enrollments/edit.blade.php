@extends('layouts.app')
@section('title', 'Editar Matrícula')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-7">
<div class="card">
    <div class="card-header bg-warning text-dark fw-semibold"><i class="bi bi-pencil me-2"></i>Editar Matrícula #{{ $enrollment->id }}</div>
    <div class="card-body">
        <div class="alert alert-light mb-3">
            <strong>{{ $enrollment->student->nombre_completo }}</strong> — {{ $enrollment->course->nombre }} — {{ $enrollment->periodo }}
        </div>
        <form method="POST" action="{{ route('enrollments.update', $enrollment) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['ACTIVO','CULMINADO','RETIRADO','SUSPENDIDO'] as $e)
                        <option value="{{ $e }}" {{ $enrollment->estado==$e?'selected':'' }}>{{ $e }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Turno *</label>
                    <select name="turno" class="form-select" required>
                        @foreach(['MAÑANA','TARDE','NOCHE'] as $t)
                        <option value="{{ $t }}" {{ $enrollment->turno==$t?'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ $enrollment->fecha_inicio?->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Fecha fin</label>
                    <input type="date" name="fecha_fin" value="{{ $enrollment->fecha_fin?->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ $enrollment->observaciones }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-check2 me-1"></i>Actualizar</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
