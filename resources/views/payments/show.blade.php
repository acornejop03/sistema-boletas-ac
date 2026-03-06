@extends('layouts.app')
@section('title', 'Detalle de Cobro #' . $payment->id)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Cobros</a></li>
    <li class="breadcrumb-item active">Cobro #{{ $payment->id }}</li>
@endsection

@section('styles')
<style>
/* ══ SHOW PAYMENT STYLES ══ */
.show-grid {
    display:grid;
    grid-template-columns: 1fr 380px;
    gap:1rem;
    align-items:start;
}
@media(max-width:991px){ .show-grid{ grid-template-columns:1fr; } }

/* ── Section card ── */
.sec-card {
    background:#fff; border-radius:16px;
    border:1px solid rgba(0,0,0,.05);
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    overflow:hidden; margin-bottom:1rem;
}
.sec-card:last-child { margin-bottom:0; }

.sec-hdr {
    padding:.8rem 1.2rem;
    border-bottom:1px solid #f1f5f9;
    background:linear-gradient(135deg,#fafbff,#fff);
    display:flex; align-items:center; gap:10px;
}
.sec-hdr-icon {
    width:30px; height:30px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:.85rem; flex-shrink:0;
}
.sec-hdr-title { font-weight:700; font-size:.85rem; color:#0f172a; }
.sec-hdr-sub   { font-size:.72rem; color:#64748b; margin-top:1px; }
.sec-hdr-badge { margin-left:auto; }

/* ── Info rows ── */
.info-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:0;
}
@media(max-width:575px){ .info-grid{ grid-template-columns:1fr; } }

.info-cell {
    padding:.85rem 1.2rem;
    border-bottom:1px solid #f8fafc;
    border-right:1px solid #f8fafc;
}
.info-cell:nth-child(even) { border-right:none; }
.info-lbl { font-size:.7rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; margin-bottom:3px; }
.info-val { font-size:.875rem; color:#1e293b; font-weight:500; }
.info-val.mono { font-family:'Courier New',monospace; }

/* ── Total box ── */
.total-box {
    background:linear-gradient(135deg,#f0fdf4,#dcfce7);
    border:1px solid #86efac;
    border-radius:12px;
    padding:1.1rem 1.25rem;
    margin:1rem 1.2rem;
    display:flex; align-items:center; justify-content:space-between;
}
.total-label { font-size:.78rem; color:#15803d; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
.total-amount { font-size:1.8rem; font-weight:900; color:#15803d; letter-spacing:-.03em; }
.total-detail { font-size:.72rem; color:#4ade80; margin-top:2px; }
.total-box.annulled {
    background:#f8fafc; border-color:#e2e8f0;
}
.total-box.annulled .total-label,
.total-box.annulled .total-amount { color:#94a3b8; }
.total-box.annulled .total-amount { text-decoration:line-through; }

/* ── Receipt card ── */
.receipt-card {
    background:#fff; border-radius:16px;
    border:1px solid rgba(0,0,0,.05);
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    overflow:hidden; margin-bottom:1rem;
}
.receipt-top {
    padding:1.25rem 1.25rem 1rem;
    border-bottom:1px dashed #e2e8f0;
    text-align:center;
    background:linear-gradient(135deg,#fafbff,#fff);
}
.receipt-company { font-size:.72rem; color:#94a3b8; text-transform:uppercase; letter-spacing:.1em; }
.receipt-num {
    font-family:'Courier New',monospace;
    font-size:1.5rem; font-weight:900; color:#1e293b;
    letter-spacing:.02em; margin:.25rem 0;
}
.receipt-type { font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; }

.receipt-status-bar {
    padding:.55rem 1.25rem;
    display:flex; align-items:center; justify-content:center; gap:8px;
    font-size:.78rem; font-weight:600;
}
.rsb-aceptado { background:#f0fdf4; color:#15803d; border-bottom:1px solid #bbf7d0; }
.rsb-pendiente{ background:#fffbeb; color:#b45309; border-bottom:1px solid #fde68a; }
.rsb-rechazado{ background:#fef2f2; color:#b91c1c; border-bottom:1px solid #fecaca; }
.rsb-anulado  { background:#f1f5f9; color:#475569; border-bottom:1px solid #e2e8f0; }

.receipt-body { padding:1rem 1.25rem; }
.receipt-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:.45rem 0; border-bottom:1px solid #f8fafc; font-size:.82rem;
}
.receipt-row:last-child { border-bottom:none; }
.receipt-row-key { color:#64748b; }
.receipt-row-val { font-weight:600; color:#1e293b; }

.receipt-actions { padding:.85rem 1.25rem 1.25rem; display:flex; flex-direction:column; gap:.5rem; }
.receipt-btn {
    display:flex; align-items:center; justify-content:center; gap:7px;
    padding:.6rem; border-radius:10px;
    font-size:.83rem; font-weight:600; text-decoration:none;
    transition:all .2s; cursor:pointer; border:none; width:100%;
}
.receipt-btn.pdf  { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
.receipt-btn.pdf:hover  { background:#dc2626; color:#fff; }
.receipt-btn.xml  { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
.receipt-btn.xml:hover  { background:#475569; color:#fff; }
.receipt-btn.send { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
.receipt-btn.send:hover { background:#d97706; color:#fff; }

.receipt-sunat-msg {
    margin:.75rem 1.25rem;
    padding:.6rem .85rem; border-radius:10px;
    font-size:.78rem; font-weight:500;
}
.msg-ok  { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
.msg-warn{ background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
.msg-err { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }

/* ── Timeline SUNAT ── */
.sunat-timeline { padding:.85rem 1.25rem; }
.tl-item {
    display:flex; gap:10px;
    padding:.55rem 0;
    border-bottom:1px solid #f8fafc;
    position:relative;
}
.tl-item:last-child { border-bottom:none; }
.tl-dot {
    width:24px; height:24px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:.65rem; margin-top:1px;
}
.tl-dot.ok  { background:#f0fdf4; color:#16a34a; }
.tl-dot.err { background:#fef2f2; color:#dc2626; }
.tl-msg  { font-size:.8rem; color:#374151; line-height:1.4; }
.tl-time { font-size:.68rem; color:#94a3b8; margin-top:2px; }

/* ── Danger zone ── */
.danger-zone {
    background:#fff; border-radius:16px;
    border:1.5px solid #fecaca;
    box-shadow:0 2px 8px rgba(239,68,68,.08);
    overflow:hidden;
}
.danger-hdr {
    padding:.7rem 1.2rem;
    background:linear-gradient(135deg,#fef2f2,#fff);
    border-bottom:1px solid #fecaca;
    display:flex; align-items:center; gap:8px;
    font-size:.82rem; font-weight:700; color:#b91c1c;
}
.danger-body { padding:1rem 1.25rem; }

/* ── Back btn ── */
.back-btn {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.8rem; font-weight:600; color:#64748b;
    text-decoration:none; margin-bottom:1rem;
    padding:5px 10px; border-radius:8px;
    background:#f8fafc; border:1px solid #e2e8f0;
    transition:all .15s;
}
.back-btn:hover { background:#f1f5f9; color:#374151; }
</style>
@endsection

@section('content')

<a href="{{ route('payments.index') }}" class="back-btn">
    <i class="bi bi-arrow-left"></i> Volver a cobros
</a>

<div class="show-grid">

    {{-- ══ COLUMNA IZQUIERDA ══ --}}
    <div>

        {{-- Datos del alumno --}}
        <div class="sec-card">
            <div class="sec-hdr">
                <div class="sec-hdr-icon" style="background:#eff6ff;color:#2563eb"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <div class="sec-hdr-title">Alumno</div>
                </div>
                <div class="sec-hdr-badge">
                    @if($payment->student_id)
                    <a href="{{ route('students.show', $payment->student_id) }}"
                       style="font-size:.75rem;font-weight:600;color:#2563eb;text-decoration:none;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;padding:3px 10px">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Ver perfil
                    </a>
                    @endif
                </div>
            </div>
            <div style="padding:.9rem 1.2rem;display:flex;align-items:center;gap:14px">
                <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#1a3a6b,#2563eb);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:800;flex-shrink:0">
                    {{ strtoupper(substr($payment->student->nombre_completo, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:1rem;font-weight:800;color:#0f172a">{{ $payment->student->nombre_completo }}</div>
                    <div style="font-size:.78rem;color:#64748b;margin-top:1px">
                        <span style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:6px;padding:2px 8px;font-family:monospace">{{ $payment->student->codigo }}</span>
                        @if($payment->student->numero_documento)
                        <span class="ms-2">DNI: {{ $payment->student->numero_documento }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Detalle del cobro --}}
        <div class="sec-card">
            <div class="sec-hdr">
                <div class="sec-hdr-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-receipt-cutoff"></i></div>
                <div>
                    <div class="sec-hdr-title">Cobro #{{ $payment->id }}</div>
                    <div class="sec-hdr-sub">{{ $payment->fecha_pago->format('d/m/Y') }}</div>
                </div>
                <div class="sec-hdr-badge">{!! $payment->estado_badge !!}</div>
            </div>
            <div class="info-grid">
                <div class="info-cell">
                    <div class="info-lbl">Tipo de cobro</div>
                    <div class="info-val">
                        @php $tbClass = match($payment->tipo_pago){ 'MATRICULA'=>'tb-matricula','PENSION'=>'tb-pension','MATERIALES'=>'tb-materiales',default=>'tb-otro' }; @endphp
                        <span class="tipo-badge {{ $tbClass }}">{{ $payment->tipo_pago }}</span>
                    </div>
                </div>
                <div class="info-cell">
                    <div class="info-lbl">Fecha de pago</div>
                    <div class="info-val">{{ $payment->fecha_pago->format('d/m/Y') }}</div>
                </div>
                @if($payment->enrollment)
                <div class="info-cell">
                    <div class="info-lbl">Curso</div>
                    <div class="info-val">{{ $payment->enrollment->course->nombre }}</div>
                </div>
                @endif
                @if($payment->periodo_pago)
                <div class="info-cell">
                    <div class="info-lbl">Periodo</div>
                    <div class="info-val">{{ $payment->periodo_pago }}</div>
                </div>
                @endif
                <div class="info-cell">
                    <div class="info-lbl">Forma de pago</div>
                    <div class="info-val">{{ $payment->forma_pago_icon }}</div>
                </div>
                @if($payment->numero_operacion)
                <div class="info-cell">
                    <div class="info-lbl">N° Operación</div>
                    <div class="info-val mono">{{ $payment->numero_operacion }}</div>
                </div>
                @endif
                <div class="info-cell">
                    <div class="info-lbl">Subtotal (sin IGV)</div>
                    <div class="info-val">S/ {{ number_format($payment->subtotal, 2) }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-lbl">IGV</div>
                    <div class="info-val">S/ {{ number_format($payment->igv, 2) }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-lbl">Registrado por</div>
                    <div class="info-val">{{ $payment->user->name }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-lbl">Fecha registro</div>
                    <div class="info-val">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if($payment->observaciones)
                <div class="info-cell" style="grid-column:span 2">
                    <div class="info-lbl">Observaciones</div>
                    <div class="info-val">{{ $payment->observaciones }}</div>
                </div>
                @endif
            </div>

            {{-- Total destacado --}}
            <div class="total-box {{ $payment->estado_pago === 'ANULADO' ? 'annulled' : '' }}">
                <div>
                    <div class="total-label">Total cobrado</div>
                    @if($payment->igv > 0)
                    <div class="total-detail">Sub: S/ {{ number_format($payment->subtotal,2) }} + IGV: S/ {{ number_format($payment->igv,2) }}</div>
                    @else
                    <div class="total-detail">Exonerado de IGV</div>
                    @endif
                </div>
                <div class="total-amount">S/ {{ number_format($payment->total, 2) }}</div>
            </div>
        </div>

        {{-- Zona de peligro --}}
        @can('anular cobros')
        @if($payment->estado_pago !== 'ANULADO')
        <div class="danger-zone">
            <div class="danger-hdr">
                <i class="bi bi-exclamation-triangle-fill"></i> Zona de riesgo
            </div>
            <div class="danger-body">
                <p style="font-size:.8rem;color:#64748b;margin-bottom:.75rem">
                    Anular este cobro también anulará el comprobante SUNAT asociado. Esta operación es irreversible.
                </p>
                <form method="POST" action="{{ route('payments.anular', $payment) }}"
                      onsubmit="return confirm('¿Confirma la anulación?')">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="motivo" class="form-control form-control-sm"
                               placeholder="Motivo requerido..." required style="flex:1">
                        <button class="btn btn-danger btn-sm fw-semibold px-3" type="submit">
                            <i class="bi bi-x-circle me-1"></i>Anular
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endcan

    </div>

    {{-- ══ COLUMNA DERECHA ══ --}}
    <div>

        @if($payment->sale)
        {{-- Comprobante electrónico --}}
        <div class="receipt-card">
            {{-- Header recibo --}}
            <div class="receipt-top">
                @php $company = \App\Models\Company::first(); @endphp
                <div class="receipt-company">{{ $company->nombre_comercial ?? config('app.name') }}</div>
                <div class="receipt-num">{{ $payment->sale->numero_comprobante }}</div>
                <div class="receipt-type" style="color:{{ $payment->sale->tipo_comprobante === '01' ? '#2563eb' : '#16a34a' }}">
                    {{ $payment->sale->tipo_nombre }}
                </div>
                <div style="font-size:.72rem;color:#94a3b8;margin-top:4px">
                    {{ $payment->sale->fecha_emision->format('d/m/Y') }}
                </div>
            </div>

            {{-- Estado SUNAT bar --}}
            @php
                $rsbClass = match($payment->sale->estado_sunat) {
                    'ACEPTADO'  => 'rsb-aceptado',
                    'PENDIENTE' => 'rsb-pendiente',
                    'RECHAZADO' => 'rsb-rechazado',
                    'NO_APLICA' => 'rsb-anulado',
                    default     => 'rsb-anulado',
                };
                $rsbIcon = match($payment->sale->estado_sunat) {
                    'ACEPTADO'  => 'bi-check-circle-fill',
                    'PENDIENTE' => 'bi-clock-fill',
                    'RECHAZADO' => 'bi-x-circle-fill',
                    'NO_APLICA' => 'bi-file-earmark-minus',
                    default     => 'bi-dash-circle',
                };
            @endphp
            <div class="receipt-status-bar {{ $rsbClass }}">
                <i class="bi {{ $rsbIcon }}"></i>
                SUNAT: {{ $payment->sale->estado_sunat }}
            </div>

            {{-- Detalle comprobante --}}
            <div class="receipt-body">
                @foreach($payment->sale->items as $item)
                <div class="receipt-row">
                    <span class="receipt-row-key" style="max-width:200px">{{ $item->descripcion }}</span>
                    <span class="receipt-row-val">S/ {{ number_format($item->total, 2) }}</span>
                </div>
                @endforeach
                <div class="receipt-row">
                    <span class="receipt-row-key">Subtotal</span>
                    <span class="receipt-row-val">S/ {{ number_format($payment->sale->valorventa, 2) }}</span>
                </div>
                @if($payment->sale->mto_igv > 0)
                <div class="receipt-row">
                    <span class="receipt-row-key">IGV (18%)</span>
                    <span class="receipt-row-val">S/ {{ number_format($payment->sale->mto_igv, 2) }}</span>
                </div>
                @endif
                @if($payment->sale->mto_oper_exoneradas > 0)
                <div class="receipt-row">
                    <span class="receipt-row-key" style="color:#16a34a;font-size:.7rem">Exonerado de IGV</span>
                    <span class="receipt-row-val" style="color:#16a34a">S/ {{ number_format($payment->sale->mto_oper_exoneradas, 2) }}</span>
                </div>
                @endif
                <div class="receipt-row" style="border-top:2px solid #f1f5f9;padding-top:.75rem;margin-top:.25rem">
                    <span class="receipt-row-key" style="font-weight:700;color:#0f172a">TOTAL</span>
                    <span style="font-size:1.1rem;font-weight:900;color:#15803d">S/ {{ number_format($payment->sale->mto_imp_venta, 2) }}</span>
                </div>
            </div>

            {{-- Mensaje SUNAT --}}
            @if($payment->sale->sunat_descripcion)
            <div class="receipt-sunat-msg {{ $payment->sale->estado_sunat === 'ACEPTADO' ? 'msg-ok' : ($payment->sale->estado_sunat === 'RECHAZADO' ? 'msg-err' : 'msg-warn') }}">
                <i class="bi bi-info-circle me-2"></i>{{ $payment->sale->sunat_descripcion }}
            </div>
            @endif

            {{-- Acciones --}}
            <div class="receipt-actions">
                @can('descargar pdf')
                <a href="{{ route('sales.pdf', $payment->sale) }}" class="receipt-btn pdf" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Descargar PDF
                </a>
                @endcan
                @can('descargar xml')
                @if($payment->sale->tipo_comprobante !== 'NV')
                <a href="{{ route('sales.xml', $payment->sale) }}" class="receipt-btn xml">
                    <i class="bi bi-file-earmark-code"></i> Descargar XML
                </a>
                @endif
                @endcan
                @can('reenviar comprobantes')
                @if(!in_array($payment->sale->estado_sunat, ['ACEPTADO', 'NO_APLICA']))
                <form method="POST" action="{{ route('sales.reenviar', $payment->sale) }}"
                      onsubmit="return confirm('¿Reenviar a SUNAT?')">
                    @csrf
                    <button type="submit" class="receipt-btn send">
                        <i class="bi bi-arrow-repeat"></i> Reenviar a SUNAT
                    </button>
                </form>
                @endif
                @endcan
            </div>
        </div>

        {{-- Timeline SUNAT --}}
        @if($payment->sale->sunatResponses->count() > 0)
        <div class="sec-card">
            <div class="sec-hdr">
                <div class="sec-hdr-icon" style="background:#f5f3ff;color:#7c3aed"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="sec-hdr-title">Historial SUNAT</div>
                    <div class="sec-hdr-sub">{{ $payment->sale->sunatResponses->count() }} intento{{ $payment->sale->sunatResponses->count() != 1 ? 's' : '' }}</div>
                </div>
            </div>
            <div class="sunat-timeline">
                @foreach($payment->sale->sunatResponses->sortByDesc('created_at') as $r)
                <div class="tl-item">
                    <div class="tl-dot {{ $r->exitoso ? 'ok' : 'err' }}">
                        <i class="bi {{ $r->exitoso ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                    </div>
                    <div style="flex:1">
                        <div class="tl-msg">{{ $r->descripcion_respuesta ?: ($r->exitoso ? 'Enviado correctamente' : 'Error de envío') }}</div>
                        <div class="tl-time">{{ $r->created_at->format('d/m/Y H:i:s') }} · {{ $r->accion }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @else
        {{-- Sin comprobante --}}
        <div class="sec-card">
            <div style="padding:2rem;text-align:center">
                <i class="bi bi-file-earmark-x" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:.75rem"></i>
                <div style="font-size:.85rem;font-weight:600;color:#64748b">Sin comprobante</div>
                <div style="font-size:.78rem;color:#94a3b8;margin-top:4px">Este cobro no tiene comprobante electrónico asociado</div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
