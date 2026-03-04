@extends('layouts.app')
@section('title', 'Nueva Matrícula')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Matrículas</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-clipboard-plus text-success"></i> Nueva Matrícula</h5>
        <div class="page-subtitle">Asigne un alumno a un curso</div>
    </div>
    <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon green"><i class="bi bi-clipboard-plus"></i></div>
        <div>
            <div class="card-hdr-title">Registrar Matrícula</div>
            <div class="card-hdr-sub">Complete los datos para asignar el alumno al curso</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('enrollments.store') }}">
            @csrf

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">1</span>
                    <span class="fsec-label">Alumno y Curso</span>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Alumno <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">Seleccionar alumno...</option>
                            @foreach($students as $s)
                            <option value="{{ $s->id }}" {{ old('student_id')==$s->id?'selected':'' }}>
                                {{ $s->nombre_completo }} — {{ $s->codigo }}
                            </option>
                            @endforeach
                        </select>
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Curso <span class="text-danger">*</span></label>
                        <select name="course_id" id="courseSelect" class="form-select @error('course_id') is-invalid @enderror" required>
                            <option value="">Seleccionar curso...</option>
                            @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}
                                    data-pension="{{ $c->precio_pension }}"
                                    data-matricula="{{ $c->precio_matricula }}"
                                    data-materiales="{{ $c->precio_materiales }}">
                                {{ $c->nombre }}@if($c->category) · {{ $c->category->nombre }}@endif — S/ {{ number_format($c->precio_pension,2) }}/mes
                            </option>
                            @endforeach
                        </select>
                        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Turno <span class="text-danger">*</span></label>
                        <select name="turno" class="form-select" required>
                            @foreach(['MAÑANA','TARDE','NOCHE'] as $t)
                            <option value="{{ $t }}" {{ old('turno','MAÑANA')==$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="resumenPrecios" style="display:none" class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                <i class="bi bi-calendar-month me-1"></i>Pensión: S/ <span id="prPension">0.00</span>/mes
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                <i class="bi bi-file-plus me-1"></i>Matrícula: S/ <span id="prMatricula">0.00</span>
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                                <i class="bi bi-box me-1"></i>Materiales: S/ <span id="prMateriales">0.00</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">2</span>
                    <span class="fsec-label">Período y Fechas</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Periodo <span class="text-danger">*</span></label>
                        <input type="month" name="periodo" value="{{ old('periodo', now()->format('Y-m')) }}"
                               class="form-control @error('periodo') is-invalid @enderror" required>
                        @error('periodo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de matrícula <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_matricula" value="{{ old('fecha_matricula', now()->toDateString()) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-control">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Notas adicionales sobre la matrícula...">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="alert alert-info d-flex gap-2 align-items-center py-2" style="font-size:0.8rem">
                <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                Si el curso tiene precio de matrícula, se generará automáticamente un cobro al guardar.
            </div>

            <div class="d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-clipboard-check me-1"></i>Matricular Alumno
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
$('#courseSelect').on('change', function() {
    const opt = $(this).find('option:selected');
    if ($(this).val()) {
        $('#prPension').text(parseFloat(opt.data('pension')||0).toFixed(2));
        $('#prMatricula').text(parseFloat(opt.data('matricula')||0).toFixed(2));
        $('#prMateriales').text(parseFloat(opt.data('materiales')||0).toFixed(2));
        $('#resumenPrecios').show();
    } else {
        $('#resumenPrecios').hide();
    }
});
$('#courseSelect').trigger('change');
</script>
@endsection
