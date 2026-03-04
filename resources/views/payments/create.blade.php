@extends('layouts.app')
@section('title', 'Registrar Cobro')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Cobros</a></li>
    <li class="breadcrumb-item active">Nuevo Cobro</li>
@endsection
@section('styles')
<style>
.student-card { border: 2px solid #2d5a9b; border-radius: 10px; background: #f0f5ff; }
.resumen-card { position: sticky; top: 80px; }
.forma-btn { cursor: pointer; transition: all 0.2s; }
.forma-btn:hover { border-color: #2d5a9b !important; background: #f0f5ff !important; }
.forma-btn.selected { border-color: #2d5a9b !important; background: #2d5a9b !important; color: white !important; }
</style>
@endsection
@section('content')
<form method="POST" action="{{ route('payments.store') }}" id="formCobro">
@csrf
<div class="row g-3">

{{-- COLUMNA IZQUIERDA --}}
<div class="col-md-8">

{{-- 1. BUSCAR ALUMNO --}}
<div class="card mb-3">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-search me-2"></i>1. Buscar Alumno
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <input type="text" id="searchStudent" class="form-control form-control-lg"
                   placeholder="Buscar por DNI, código o nombre del alumno...">
            <button type="button" class="btn btn-primary" id="btnSearch">
                <i class="bi bi-search me-1"></i>Buscar
            </button>
        </div>
        <div id="searchResults" class="list-group mb-2" style="display:none"></div>
        <input type="hidden" name="student_id" id="studentId">
        <input type="hidden" name="enrollment_id" id="enrollmentId">

        <div id="studentCard" style="display:none">
            <div class="student-card p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width:52px;height:52px;background:#2d5a9b;font-size:1.2rem;flex-shrink:0"
                     id="studentAvatar"></div>
                <div class="flex-1">
                    <div class="fw-bold fs-5" id="studentName"></div>
                    <div class="text-muted small" id="studentInfo"></div>
                    <div class="text-muted small" id="studentEnrollment"></div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnClearStudent">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 2. DETALLE DEL COBRO --}}
<div class="card mb-3" id="sectionDetalle" style="display:none">
    <div class="card-header bg-success text-white fw-semibold">
        <i class="bi bi-list-check me-2"></i>2. Detalle del Cobro
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tipo de cobro <span class="text-danger">*</span></label>
                <select name="tipo_pago" id="tipoPago" class="form-select @error('tipo_pago') is-invalid @enderror">
                    <option value="">Seleccionar...</option>
                    <option value="MATRICULA" {{ old('tipo_pago')=='MATRICULA'?'selected':'' }}>📋 Matrícula</option>
                    <option value="PENSION" {{ old('tipo_pago','PENSION')=='PENSION'?'selected':'' }}>📅 Pensión mensual</option>
                    <option value="MATERIALES" {{ old('tipo_pago')=='MATERIALES'?'selected':'' }}>📚 Materiales</option>
                    <option value="OTRO" {{ old('tipo_pago')=='OTRO'?'selected':'' }}>📌 Otro</option>
                </select>
                @error('tipo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6" id="divPeriodo">
                <label class="form-label small fw-semibold">Periodo <span class="text-danger">*</span></label>
                <input type="month" name="periodo_pago" id="periodoPago"
                       value="{{ old('periodo_pago', now()->format('Y-m')) }}"
                       class="form-control @error('periodo_pago') is-invalid @enderror">
                @error('periodo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12" id="divDescripcion" style="display:none">
                <label class="form-label small fw-semibold">Descripción del cobro</label>
                <input type="text" name="descripcion_pago" value="{{ old('descripcion_pago') }}"
                       class="form-control" placeholder="Descripción adicional del concepto...">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Monto a cobrar (S/) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-bold">S/</span>
                    <input type="number" name="monto" id="monto" value="{{ old('monto') }}"
                           class="form-control form-control-lg fw-bold @error('monto') is-invalid @enderror"
                           step="0.01" min="1" placeholder="0.00">
                    @error('monto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Observaciones</label>
                <input type="text" name="observaciones" value="{{ old('observaciones') }}"
                       class="form-control" placeholder="Opcional...">
            </div>
        </div>
    </div>
</div>

{{-- 3. FORMA DE PAGO --}}
<div class="card mb-3" id="sectionFormaPago" style="display:none">
    <div class="card-header bg-info text-white fw-semibold">
        <i class="bi bi-credit-card me-2"></i>3. Forma de Pago
    </div>
    <div class="card-body">
        <input type="hidden" name="forma_pago" id="formaPago" value="{{ old('forma_pago', 'EFECTIVO') }}">
        <div class="d-flex flex-wrap gap-2 mb-3" id="formaPagoBtns">
            @foreach(['EFECTIVO' => '💵 Efectivo', 'TARJETA' => '💳 Tarjeta', 'TRANSFERENCIA' => '🏦 Transferencia', 'YAPE' => '📱 Yape', 'PLIN' => '📱 Plin'] as $val => $label)
            <button type="button" class="btn btn-outline-secondary forma-btn {{ old('forma_pago','EFECTIVO')==$val ? 'selected' : '' }}"
                    data-value="{{ $val }}" style="min-width:110px">
                {{ $label }}
            </button>
            @endforeach
        </div>
        <div id="divNumOp" style="display:{{ old('forma_pago')=='TRANSFERENCIA' ? 'block' : 'none' }}">
            <label class="form-label small fw-semibold">Número de operación <span class="text-danger">*</span></label>
            <input type="text" name="numero_operacion" value="{{ old('numero_operacion') }}"
                   id="numOperacion" class="form-control @error('numero_operacion') is-invalid @enderror"
                   placeholder="Ingrese el número de operación bancaria">
            @error('numero_operacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- 4. TIPO COMPROBANTE --}}
<div class="card mb-3" id="sectionComprobante" style="display:none">
    <div class="card-header bg-secondary text-white fw-semibold">
        <i class="bi bi-receipt me-2"></i>4. Tipo de Comprobante
    </div>
    <div class="card-body">
        <div class="d-flex gap-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tipo_comprobante" id="boleta"
                       value="03" {{ old('tipo_comprobante','03')=='03' ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="boleta">
                    <i class="bi bi-receipt me-1"></i>Boleta de Venta
                </label>
                <div class="text-muted small">Para personas naturales</div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tipo_comprobante" id="factura"
                       value="01" {{ old('tipo_comprobante')=='01' ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="factura">
                    <i class="bi bi-file-earmark-text me-1"></i>Factura
                </label>
                <div class="text-muted small">Requiere RUC del cliente</div>
            </div>
        </div>
    </div>
</div>

</div>

{{-- COLUMNA DERECHA: RESUMEN --}}
<div class="col-md-4">
    <div class="card resumen-card border-success" id="sectionResumen" style="display:none">
        <div class="card-header bg-success text-white fw-semibold text-center">
            <i class="bi bi-clipboard-check me-2"></i>Resumen del Cobro
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="text-muted small">Alumno</div>
                <div class="fw-semibold" id="resAlumno">—</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Concepto</div>
                <div class="fw-semibold" id="resCurso">—</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Tipo de cobro</div>
                <div class="fw-semibold" id="resTipo">—</div>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted small">Subtotal</span>
                <span id="resSubtotal">S/ 0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted small">IGV</span>
                <span id="resIgv">S/ 0.00</span>
            </div>
            <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2">
                <span class="text-success">TOTAL</span>
                <span class="text-success" id="resTotal">S/ 0.00</span>
            </div>
            <div class="mt-2">
                <span class="text-muted small">Forma de pago: </span>
                <span class="fw-semibold" id="resFormaPago">Efectivo</span>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" id="btnSubmit">
                <i class="bi bi-cash-coin me-2"></i>💰 REGISTRAR COBRO Y EMITIR BOLETA
            </button>
            <div class="text-center mt-2">
                <small class="text-muted">Se enviará automáticamente a SUNAT</small>
            </div>
        </div>
    </div>

    <div class="card mt-3" id="cardHelp">
        <div class="card-body text-center text-muted py-4">
            <i class="bi bi-arrow-left-circle fs-1 d-block mb-2"></i>
            <div class="fw-semibold">Busca un alumno para comenzar</div>
            <small>Puedes buscar por DNI, código o nombre</small>
        </div>
    </div>
</div>

</div>
</form>
@endsection
@section('scripts')
<script>
const IGV_RATE = 0.18;
let currentStudent = null;
let currentEnrollments = [];

// Búsqueda de alumno
function searchStudent(q) {
    if (!q.trim()) return;
    $.get('{{ route("api.students.buscar") }}', { q: q }, function(data) {
        if (data.length === 0) {
            $('#searchResults').html('<div class="list-group-item text-muted">No se encontraron resultados para "' + q + '"</div>').show();
            return;
        }
        let html = data.map(s => `
            <button type="button" class="list-group-item list-group-item-action student-result" data-student='${JSON.stringify(s)}'>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                         style="width:36px;height:36px;font-size:0.85rem;flex-shrink:0">
                        ${s.nombre_completo.charAt(0)}
                    </div>
                    <div>
                        <div class="fw-semibold">${s.nombre_completo}</div>
                        <small class="text-muted">${s.codigo} | ${s.tipo_doc_nombre}: ${s.numero_documento || '—'}</small>
                    </div>
                    ${s.enrollments.length > 0 ? `<span class="badge bg-success ms-auto">${s.enrollments.length} matrícula(s)</span>` : ''}
                </div>
            </button>
        `).join('');
        $('#searchResults').html(html).show();
    });
}

$('#btnSearch').click(() => searchStudent($('#searchStudent').val()));
$('#searchStudent').keypress(e => { if (e.which === 13) { e.preventDefault(); searchStudent($(this).val()); } });

$(document).on('click', '.student-result', function() {
    const s = $(this).data('student');
    selectStudent(s);
    $('#searchResults').hide();
});

function selectStudent(s) {
    currentStudent = s;
    currentEnrollments = s.enrollments;
    $('#studentId').val(s.id);
    $('#studentAvatar').text(s.nombre_completo.charAt(0));
    $('#studentName').text(s.nombre_completo);
    $('#studentInfo').text(`${s.tipo_doc_nombre}: ${s.numero_documento || '—'} | Código: ${s.codigo}`);

    let enrollText = s.enrollments.length > 0
        ? '📚 ' + s.enrollments.map(e => e.curso + ' (' + e.periodo + ')').join(', ')
        : '⚠️ Sin matrículas activas';
    $('#studentEnrollment').text(enrollText);

    $('#studentCard').show();
    $('#sectionDetalle, #sectionFormaPago, #sectionComprobante, #sectionResumen').show();
    $('#cardHelp').hide();

    // Si hay una sola matrícula activa, preseleccionar
    if (s.enrollments.length === 1) {
        const e = s.enrollments[0];
        $('#enrollmentId').val(e.id);
        $('#resCurso').text(e.curso);
        $('#tipoPago').trigger('change');
    }

    $('#resAlumno').text(s.nombre_completo);
    updateResumen();
}

$('#btnClearStudent').click(function() {
    currentStudent = null;
    $('#studentId, #enrollmentId').val('');
    $('#studentCard').hide();
    $('#sectionDetalle, #sectionFormaPago, #sectionComprobante, #sectionResumen').hide();
    $('#cardHelp').show();
    $('#searchStudent').val('').focus();
});

// Tipo de pago
$('#tipoPago').change(function() {
    const tipo = $(this).val();
    $('#divPeriodo').toggle(tipo === 'PENSION');
    $('#divDescripcion').toggle(tipo === 'OTRO');
    $('#resTipo').text($(this).find('option:selected').text());

    // Sugerir monto según tipo y matrícula activa
    if (currentEnrollments.length > 0) {
        const e = currentEnrollments[0];
        if (tipo === 'PENSION') {
            $('#monto').val(e.precio_pension || '');
        } else if (tipo === 'MATRICULA') {
            $('#monto').val(e.precio_matricula || '');
        } else if (tipo === 'MATERIALES') {
            $('#monto').val(e.precio_materiales || '');
        }
    }
    updateResumen();
});

// Monto
$('#monto').on('input', updateResumen);

// Forma de pago
$('.forma-btn').click(function() {
    $('.forma-btn').removeClass('selected');
    $(this).addClass('selected');
    const val = $(this).data('value');
    $('#formaPago').val(val);
    $('#divNumOp').toggle(val === 'TRANSFERENCIA');
    $('#resFormaPago').text($(this).text().trim());
    updateResumen();
});

function updateResumen() {
    const monto = parseFloat($('#monto').val()) || 0;
    // Para educación normalmente es exonerado
    // Simplificación: sin IGV por defecto (tipo 20 = exonerado)
    const subtotal = monto;
    const igv = 0;
    const total = monto;

    $('#resSubtotal').text('S/ ' + subtotal.toFixed(2));
    $('#resIgv').text('S/ ' + igv.toFixed(2));
    $('#resTotal').text('S/ ' + total.toFixed(2));

    const tipo = $('#tipoPago').find('option:selected').text();
    if (tipo) $('#resTipo').text(tipo);
}

// Inicializar
$('#tipoPago').trigger('change');

// Si viene con student_id precargado
@if(request('student_id'))
$.get('{{ route("api.students.buscar") }}', { q: '{{ request("student_id") }}' }, function(data) {
    const found = data.find(s => s.id == {{ request('student_id') }});
    if (found) selectStudent(found);
});
@endif
</script>
@endsection
