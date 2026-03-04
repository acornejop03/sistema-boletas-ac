@extends('layouts.app')
@section('title', 'Registrar Cobro')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Cobros</a></li>
    <li class="breadcrumb-item active">Nuevo Cobro</li>
@endsection
@section('styles')
<style>
.student-found {
    border: 2px solid #2563eb; border-radius: 10px;
    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
}
.payment-method-btn {
    cursor: pointer; transition: all 0.18s;
    border: 2px solid #e2e8f0; border-radius: 10px;
    padding: 0.6rem 1rem; font-size: 0.82rem; font-weight: 500;
    background: #fff; color: #475569; display: flex; flex-direction: column;
    align-items: center; gap: 4px; min-width: 80px;
}
.payment-method-btn .pm-icon { font-size: 1.4rem; line-height: 1; }
.payment-method-btn:hover { border-color: #2563eb; background: #eff6ff; color: #2563eb; }
.payment-method-btn.selected { border-color: #2563eb; background: #2563eb; color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
.resumen-card { position: sticky; top: 70px; }
.step-badge {
    width: 26px; height: 26px; border-radius: 50%;
    background: #2563eb; color: #fff;
    font-size: 0.7rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
</style>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-cash-stack text-success"></i> Registrar Cobro</h5>
        <div class="page-subtitle">Busque el alumno y complete los detalles del cobro</div>
    </div>
</div>

<form method="POST" action="{{ route('payments.store') }}" id="formCobro">
@csrf
<div class="row g-3">

{{-- COLUMNA IZQUIERDA --}}
<div class="col-lg-8">

{{-- PASO 1: BUSCAR ALUMNO --}}
<div class="card mb-3">
    <div class="card-hdr">
        <div class="step-badge">1</div>
        <div>
            <div class="card-hdr-title">Buscar Alumno</div>
            <div class="card-hdr-sub">Ingrese DNI, código o nombre del alumno</div>
        </div>
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="searchStudent" class="form-control"
                   style="font-size:0.9rem;height:44px"
                   placeholder="Buscar por DNI, código o nombre del alumno...">
            <button type="button" class="btn btn-primary" id="btnSearch" style="height:44px">
                <i class="bi bi-search me-1"></i>Buscar
            </button>
        </div>
        <div id="searchResults" class="list-group mb-2" style="display:none;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0"></div>
        <input type="hidden" name="student_id" id="studentId">
        <input type="hidden" name="enrollment_id" id="enrollmentId">

        <div id="studentCard" style="display:none">
            <div class="student-found p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                     style="width:50px;height:50px;background:var(--brand-secondary);font-size:1.15rem"
                     id="studentAvatar"></div>
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size:1rem" id="studentName"></div>
                    <div class="text-muted" style="font-size:0.8rem" id="studentInfo"></div>
                    <div class="text-primary" style="font-size:0.78rem;margin-top:2px" id="studentEnrollment"></div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnClearStudent" style="flex-shrink:0">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- PASO 2: DETALLE DEL COBRO --}}
<div class="card mb-3" id="sectionDetalle" style="display:none">
    <div class="card-hdr">
        <div class="step-badge">2</div>
        <div>
            <div class="card-hdr-title">Detalle del Cobro</div>
            <div class="card-hdr-sub">Especifique el concepto y monto</div>
        </div>
        <div class="card-hdr-actions">
            <div class="card-hdr-icon green ms-0"><i class="bi bi-list-check"></i></div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tipo de cobro <span class="text-danger">*</span></label>
                <select name="tipo_pago" id="tipoPago" class="form-select @error('tipo_pago') is-invalid @enderror">
                    <option value="">Seleccionar...</option>
                    <option value="PENSION"      {{ old('tipo_pago','PENSION')=='PENSION'   ?'selected':'' }}>📅 Pensión mensual</option>
                    <option value="MATRICULA"    {{ old('tipo_pago')=='MATRICULA'           ?'selected':'' }}>📋 Matrícula</option>
                    <option value="MATERIALES"   {{ old('tipo_pago')=='MATERIALES'          ?'selected':'' }}>📚 Materiales</option>
                    <option value="OTRO"         {{ old('tipo_pago')=='OTRO'                ?'selected':'' }}>📌 Otro concepto</option>
                </select>
                @error('tipo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6" id="divPeriodo">
                <label class="form-label">Periodo <span class="text-danger">*</span></label>
                <input type="month" name="periodo_pago" id="periodoPago"
                       value="{{ old('periodo_pago', now()->format('Y-m')) }}"
                       class="form-control @error('periodo_pago') is-invalid @enderror">
                @error('periodo_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12" id="divDescripcion" style="display:none">
                <label class="form-label">Descripción del concepto</label>
                <input type="text" name="descripcion_pago" value="{{ old('descripcion_pago') }}"
                       class="form-control" placeholder="Ingrese descripción del cobro...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Monto (S/) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-bold text-success">S/</span>
                    <input type="number" name="monto" id="monto" value="{{ old('monto') }}"
                           class="form-control fw-bold @error('monto') is-invalid @enderror"
                           style="font-size:1.1rem"
                           step="0.01" min="1" placeholder="0.00">
                    @error('monto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Observaciones</label>
                <input type="text" name="observaciones" value="{{ old('observaciones') }}"
                       class="form-control" placeholder="Opcional...">
            </div>
        </div>
    </div>
</div>

{{-- PASO 3: FORMA DE PAGO --}}
<div class="card mb-3" id="sectionFormaPago" style="display:none">
    <div class="card-hdr">
        <div class="step-badge">3</div>
        <div>
            <div class="card-hdr-title">Forma de Pago</div>
            <div class="card-hdr-sub">Seleccione cómo realiza el pago el cliente</div>
        </div>
        <div class="card-hdr-actions">
            <div class="card-hdr-icon cyan ms-0"><i class="bi bi-credit-card"></i></div>
        </div>
    </div>
    <div class="card-body">
        <input type="hidden" name="forma_pago" id="formaPago" value="{{ old('forma_pago', 'EFECTIVO') }}">
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach(['EFECTIVO'=>['💵','Efectivo'], 'TARJETA'=>['💳','Tarjeta'], 'TRANSFERENCIA'=>['🏦','Transferencia'], 'YAPE'=>['📱','Yape'], 'PLIN'=>['📱','Plin']] as $val => $info)
            <button type="button" class="payment-method-btn {{ old('forma_pago','EFECTIVO')==$val ? 'selected' : '' }}"
                    data-value="{{ $val }}">
                <span class="pm-icon">{{ $info[0] }}</span>
                <span>{{ $info[1] }}</span>
            </button>
            @endforeach
        </div>
        <div id="divNumOp" style="display:{{ old('forma_pago')=='TRANSFERENCIA' ? 'block' : 'none' }}">
            <label class="form-label">N° de operación <span class="text-danger">*</span></label>
            <input type="text" name="numero_operacion" value="{{ old('numero_operacion') }}"
                   id="numOperacion" class="form-control @error('numero_operacion') is-invalid @enderror"
                   placeholder="Número de operación bancaria">
            @error('numero_operacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- PASO 4: TIPO DE COMPROBANTE --}}
<div class="card mb-3" id="sectionComprobante" style="display:none">
    <div class="card-hdr">
        <div class="step-badge">4</div>
        <div>
            <div class="card-hdr-title">Tipo de Comprobante</div>
            <div class="card-hdr-sub">Seleccione el comprobante a emitir</div>
        </div>
        <div class="card-hdr-actions">
            <div class="card-hdr-icon slate ms-0"><i class="bi bi-receipt"></i></div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="d-flex align-items-start gap-2 p-3 rounded-3 border cursor-pointer" style="cursor:pointer;transition:all 0.15s"
                       id="lblBoleta" onclick="selectComprobante('03')">
                    <input class="form-check-input mt-0 flex-shrink-0" type="radio" name="tipo_comprobante" id="boleta"
                           value="03" {{ old('tipo_comprobante','03')=='03' ? 'checked' : '' }}>
                    <div>
                        <div class="fw-semibold"><i class="bi bi-receipt me-1 text-primary"></i>Boleta de Venta</div>
                        <div class="text-muted" style="font-size:0.78rem">Para personas naturales (DNI)</div>
                    </div>
                </label>
            </div>
            <div class="col-md-6">
                <label class="d-flex align-items-start gap-2 p-3 rounded-3 border cursor-pointer" style="cursor:pointer;transition:all 0.15s"
                       id="lblFactura" onclick="selectComprobante('01')">
                    <input class="form-check-input mt-0 flex-shrink-0" type="radio" name="tipo_comprobante" id="factura"
                           value="01" {{ old('tipo_comprobante')=='01' ? 'checked' : '' }}>
                    <div>
                        <div class="fw-semibold"><i class="bi bi-file-earmark-text me-1 text-dark"></i>Factura</div>
                        <div class="text-muted" style="font-size:0.78rem">Requiere RUC del cliente</div>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>

</div>

{{-- COLUMNA DERECHA: RESUMEN --}}
<div class="col-lg-4">
    <div class="card resumen-card border-0" id="sectionResumen" style="display:none;background:linear-gradient(145deg,#f0fdf4,#ecfeff)">
        <div class="card-hdr" style="background:transparent;border-bottom-color:#d1fae5">
            <div class="card-hdr-icon green"><i class="bi bi-clipboard-data"></i></div>
            <div class="card-hdr-title">Resumen del Cobro</div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:600">Alumno</div>
                <div class="fw-semibold" id="resAlumno">—</div>
            </div>
            <div class="mb-3">
                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:600">Curso</div>
                <div class="fw-semibold" id="resCurso">—</div>
            </div>
            <div class="mb-3">
                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:600">Concepto</div>
                <div class="fw-semibold" id="resTipo">—</div>
            </div>
            <div class="mb-2">
                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:600">Forma de pago</div>
                <div class="fw-semibold" id="resFormaPago">Efectivo</div>
            </div>
            <hr style="border-color:#d1fae5">
            <div class="d-flex justify-content-between mb-1" style="font-size:0.82rem">
                <span class="text-muted">Subtotal</span>
                <span id="resSubtotal">S/ 0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-1" style="font-size:0.82rem">
                <span class="text-muted">IGV (exonerado)</span>
                <span id="resIgv" class="text-muted">S/ 0.00</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top:2px solid #d1fae5">
                <span class="fw-bold text-success" style="font-size:1rem">TOTAL</span>
                <span class="fw-bold text-success" style="font-size:1.35rem" id="resTotal">S/ 0.00</span>
            </div>
        </div>
        <div class="card-footer" style="background:transparent;border-top-color:#d1fae5">
            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" id="btnSubmit">
                <i class="bi bi-cash-coin me-2"></i>REGISTRAR COBRO
            </button>
            <div class="text-center mt-2" style="font-size:0.72rem;color:#64748b">
                <i class="bi bi-shield-check me-1 text-success"></i>Se enviará automáticamente a SUNAT
            </div>
        </div>
    </div>

    <div class="card" id="cardHelp">
        <div class="card-body text-center py-5">
            <div style="font-size:3rem;color:#e2e8f0"><i class="bi bi-search"></i></div>
            <div class="fw-semibold text-muted mt-2">Busca un alumno para comenzar</div>
            <div class="text-muted mt-1" style="font-size:0.8rem">Puedes buscar por DNI, código o nombre</div>
        </div>
    </div>
</div>

</div>
</form>
@endsection
@section('scripts')
<script>
let currentStudent = null;
let currentEnrollments = [];

function searchStudent(q) {
    if (!q.trim()) return;
    $.get('{{ route("api.students.buscar") }}', { q }, function(data) {
        if (!data.length) {
            $('#searchResults').html('<div class="list-group-item text-muted py-3 text-center"><i class="bi bi-person-x d-block fs-3 mb-1"></i>No se encontraron resultados</div>').show();
            return;
        }
        const html = data.map(s => `
            <button type="button" class="list-group-item list-group-item-action student-result py-2" data-student='${JSON.stringify(s)}'>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:38px;height:38px;background:var(--brand-secondary);font-size:0.9rem">
                        ${s.nombre_completo.charAt(0)}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${s.nombre_completo}</div>
                        <small class="text-muted">${s.codigo} | DNI: ${s.numero_documento || '—'}</small>
                    </div>
                    ${s.enrollments.length > 0 ? `<span class="badge bg-success-subtle text-success border border-success-subtle">${s.enrollments.length} matrícula(s)</span>` : '<span class="badge bg-warning-subtle text-warning border border-warning-subtle">Sin matrícula</span>'}
                </div>
            </button>
        `).join('');
        $('#searchResults').html(html).show();
    });
}

$('#btnSearch').click(() => searchStudent($('#searchStudent').val()));
$('#searchStudent').keypress(e => { if (e.which === 13) { e.preventDefault(); searchStudent($('#searchStudent').val()); } });
$(document).on('click', '.student-result', function() {
    selectStudent($(this).data('student'));
    $('#searchResults').hide();
});

function selectStudent(s) {
    currentStudent = s;
    currentEnrollments = s.enrollments;
    $('#studentId').val(s.id);
    $('#studentAvatar').text(s.nombre_completo.charAt(0));
    $('#studentName').text(s.nombre_completo);
    $('#studentInfo').text(`${s.tipo_doc_nombre}: ${s.numero_documento || '—'} · Código: ${s.codigo}`);
    $('#studentEnrollment').text(s.enrollments.length > 0
        ? '📚 ' + s.enrollments.map(e => e.curso + ' (' + e.periodo + ')').join(', ')
        : '⚠️ Sin matrículas activas');
    $('#studentCard').show();
    $('#sectionDetalle,#sectionFormaPago,#sectionComprobante,#sectionResumen').show();
    $('#cardHelp').hide();
    if (s.enrollments.length === 1) {
        $('#enrollmentId').val(s.enrollments[0].id);
        $('#resCurso').text(s.enrollments[0].curso);
        $('#tipoPago').trigger('change');
    }
    $('#resAlumno').text(s.nombre_completo);
    updateResumen();
}

$('#btnClearStudent').click(() => {
    currentStudent = null;
    $('#studentId,#enrollmentId').val('');
    $('#studentCard').hide();
    $('#sectionDetalle,#sectionFormaPago,#sectionComprobante,#sectionResumen').hide();
    $('#cardHelp').show();
    $('#searchStudent').val('').focus();
});

$('#tipoPago').change(function() {
    const tipo = $(this).val();
    $('#divPeriodo').toggle(tipo === 'PENSION');
    $('#divDescripcion').toggle(tipo === 'OTRO');
    $('#resTipo').text($(this).find('option:selected').text());
    if (currentEnrollments.length > 0) {
        const e = currentEnrollments[0];
        if (tipo === 'PENSION')     $('#monto').val(e.precio_pension || '');
        if (tipo === 'MATRICULA')   $('#monto').val(e.precio_matricula || '');
        if (tipo === 'MATERIALES')  $('#monto').val(e.precio_materiales || '');
    }
    updateResumen();
});

$('#monto').on('input', updateResumen);

$('.payment-method-btn').click(function() {
    $('.payment-method-btn').removeClass('selected');
    $(this).addClass('selected');
    const val = $(this).data('value');
    $('#formaPago').val(val);
    $('#divNumOp').toggle(val === 'TRANSFERENCIA');
    $('#resFormaPago').text($(this).find('span:last').text().trim());
    updateResumen();
});

function selectComprobante(val) {
    $('input[name="tipo_comprobante"]').val([val]);
    document.querySelectorAll('#lblBoleta,#lblFactura').forEach(l => l.classList.remove('border-primary','bg-primary-subtle'));
    const target = val === '03' ? document.getElementById('lblBoleta') : document.getElementById('lblFactura');
    if (target) { target.classList.add('border-primary','bg-primary-subtle'); }
}

function updateResumen() {
    const monto = parseFloat($('#monto').val()) || 0;
    $('#resSubtotal').text('S/ ' + monto.toFixed(2));
    $('#resIgv').text('S/ 0.00');
    $('#resTotal').text('S/ ' + monto.toFixed(2));
    const tipo = $('#tipoPago').find('option:selected').text();
    if (tipo) $('#resTipo').text(tipo);
}

$('#tipoPago').trigger('change');
document.querySelectorAll('#lblBoleta,#lblFactura').forEach(lbl => {
    lbl.addEventListener('click', () => {
        document.querySelectorAll('#lblBoleta,#lblFactura').forEach(l => l.classList.remove('border-primary','bg-primary-subtle'));
        lbl.classList.add('border-primary','bg-primary-subtle');
    });
});
// Highlight default
setTimeout(() => selectComprobante('{{ old("tipo_comprobante","03") }}'), 100);

@if(request('student_id'))
$.get('{{ route("api.students.buscar") }}', { q: '{{ request("student_id") }}' }, function(data) {
    const found = data.find(s => s.id == {{ request('student_id') }});
    if (found) selectStudent(found);
});
@endif
</script>
@endsection
