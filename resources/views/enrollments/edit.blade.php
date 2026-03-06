@extends('layouts.app')
@section('title', 'Editar Matrícula')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection
@section('content')

<div class="page-header">
    <div>
        <h5><i class="bi bi-pencil text-primary"></i> Editar Matrícula</h5>
        <div class="page-subtitle">Actualizar datos de inscripción #{{ $enrollment->id }}</div>
    </div>
    <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">

{{-- Info alumno/curso --}}
<div class="card mb-3" style="background:linear-gradient(135deg,#eff6ff,#f0fdf4);border:1px solid #bfdbfe">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                 style="width:44px;height:44px;background:var(--brand-secondary);font-size:1rem">
                {{ strtoupper(substr($enrollment->student->apellido_paterno ?? '?', 0, 1)) }}
            </div>
            <div>
                <div class="fw-bold" style="font-size:0.95rem">{{ $enrollment->student->nombre_completo }}</div>
                <div class="text-muted" style="font-size:0.78rem">
                    <i class="bi bi-book me-1"></i>{{ $enrollment->course->nombre }}
                    <span class="mx-2">·</span>
                    <i class="bi bi-calendar me-1"></i>{{ $enrollment->periodo }}
                </div>
            </div>
            <div class="ms-auto">{!! $enrollment->estado_badge !!}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon amber"><i class="bi bi-pencil-square"></i></div>
        <div>
            <div class="card-hdr-title">Datos de la Matrícula</div>
            <div class="card-hdr-sub">Modifique los campos necesarios</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('enrollments.update', $enrollment) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                    <select name="estado" class="form-select" required>
                        @foreach(['ACTIVO'=>'Activo','CULMINADO'=>'Culminado','RETIRADO'=>'Retirado','SUSPENDIDO'=>'Suspendido'] as $val => $lbl)
                        <option value="{{ $val }}" {{ $enrollment->estado==$val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Turno <span class="text-danger">*</span></label>
                    <select name="turno" class="form-select" required>
                        @foreach(['MAÑANA','TARDE','NOCHE'] as $t)
                        <option value="{{ $t }}" {{ $enrollment->turno==$t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio"
                           value="{{ $enrollment->fecha_inicio?->format('Y-m-d') }}"
                           class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin"
                           value="{{ $enrollment->fecha_fin?->format('Y-m-d') }}"
                           class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3"
                              placeholder="Notas adicionales (opcional)">{{ $enrollment->observaciones }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-3 pt-3 border-top">
                <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2 me-1"></i>Actualizar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
