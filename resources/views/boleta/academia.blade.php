<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9.5pt; color: #1a1a1a; background: #fff; }

/* LAYOUT */
.page { padding: 28px 32px; min-height: 100%; }

/* HEADER: empresa izq | comprobante der */
.header-row { display: table; width: 100%; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 14px; margin-bottom: 16px; }
.header-left  { display: table-cell; width: 60%; vertical-align: middle; }
.header-right { display: table-cell; width: 38%; vertical-align: middle; text-align: right; padding-left: 16px; }

.company-logo { height: 48px; margin-bottom: 6px; }
.company-name { font-size: 13pt; font-weight: bold; color: #1e3a5f; line-height: 1.25; margin-bottom: 2px; }
.company-rz   { font-size: 8.5pt; color: #444; }
.company-info { font-size: 8pt; color: #666; margin-top: 5px; line-height: 1.6; }

.cpe-box {
    border: 2.5px solid #1e3a5f;
    border-radius: 6px;
    padding: 10px 14px;
    background: #f0f5ff;
    display: inline-block;
    min-width: 200px;
    text-align: center;
}
.cpe-tipo { font-size: 11pt; font-weight: bold; color: #1e3a5f; text-transform: uppercase; }
.cpe-subtipo { font-size: 8pt; color: #666; margin-bottom: 4px; }
.cpe-num  { font-size: 14pt; font-weight: bold; color: #c0392b; letter-spacing: 1px; margin: 4px 0; }
.cpe-fecha { font-size: 8.5pt; color: #444; }

/* DATOS CLIENTE */
.section { margin-bottom: 14px; }
.section-title {
    font-size: 7.5pt; font-weight: bold; text-transform: uppercase;
    letter-spacing: 0.8px; color: #fff;
    background: #1e3a5f; padding: 3px 8px; margin-bottom: 0;
    border-radius: 3px 3px 0 0;
}
.section-body { border: 1px solid #d0d9e8; border-top: none; border-radius: 0 0 4px 4px; padding: 8px 10px; }

.client-row { display: table; width: 100%; }
.client-col  { display: table-cell; vertical-align: top; }
.client-col.w50 { width: 50%; }
.client-col.w35 { width: 35%; }
.client-col.w15 { width: 15%; text-align: right; }
.field-label { font-size: 7.5pt; color: #777; margin-bottom: 1px; }
.field-value { font-size: 9pt; font-weight: bold; color: #1a1a1a; }

/* TABLA ITEMS */
table.items { width: 100%; border-collapse: collapse; margin: 0; }
table.items thead tr th {
    background: #1e3a5f; color: #fff;
    font-size: 8pt; font-weight: bold; text-transform: uppercase;
    padding: 5px 8px; letter-spacing: 0.3px;
}
table.items tbody tr td { font-size: 8.5pt; padding: 5px 8px; border-bottom: 1px solid #e8edf5; }
table.items tbody tr:last-child td { border-bottom: none; }
table.items tbody tr:nth-child(even) td { background: #f7faff; }
.text-right { text-align: right; }
.text-center { text-align: center; }

/* TOTALES */
.totales-row { display: table; width: 100%; margin-top: 10px; }
.totales-left  { display: table-cell; width: 55%; vertical-align: top; padding-right: 16px; }
.totales-right { display: table-cell; width: 45%; vertical-align: top; }

.total-line { display: table; width: 100%; margin-bottom: 3px; font-size: 8.5pt; }
.total-line .tl-label { display: table-cell; color: #555; }
.total-line .tl-value { display: table-cell; text-align: right; font-weight: bold; width: 90px; }
.total-line.grand { font-size: 11pt; font-weight: bold; color: #fff; background: #1e3a5f; padding: 6px 10px; border-radius: 4px; margin-top: 5px; }
.total-line.grand .tl-value { color: #fff; }

/* FORMA DE PAGO */
.pago-info { font-size: 8pt; color: #555; margin-top: 6px; }

/* SUNAT STATUS */
.sunat-bar {
    margin-top: 14px;
    padding: 7px 12px;
    border-radius: 5px;
    text-align: center;
    font-size: 8.5pt;
    font-weight: bold;
}
.sunat-ok      { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.sunat-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
.sunat-nv      { background: #e0f2fe; color: #075985; border: 1px solid #7dd3fc; }
.hash-text { font-size: 7pt; color: #888; word-break: break-all; margin-top: 3px; font-weight: normal; }

/* QR PLACEHOLDER */
.qr-wrap { text-align: center; margin-top: 8px; }
.qr-box {
    display: inline-block; border: 1px dashed #aaa;
    padding: 8px; font-size: 7pt; color: #999;
    border-radius: 4px; text-align: center;
}

/* FOOTER */
.footer {
    margin-top: 18px; padding-top: 10px;
    border-top: 1px dashed #ccc;
    font-size: 7.5pt; color: #888; text-align: center;
    line-height: 1.7;
}
.footer strong { color: #1e3a5f; }

/* WATERMARK ANULADO */
.watermark {
    position: fixed; top: 38%; left: 10%;
    font-size: 72pt; font-weight: bold;
    color: rgba(220,38,38,0.12); transform: rotate(-30deg);
    letter-spacing: 8px; pointer-events: none;
}
</style>
</head>
<body>
<div class="page">

@if($sale->estado_sunat === 'ANULADO')
<div class="watermark">ANULADO</div>
@endif

{{-- ============ HEADER ============ --}}
<div class="header-row">
    <div class="header-left">
        @if($sale->company->logo_path)
            <img src="{{ public_path('storage/'.$sale->company->logo_path) }}" class="company-logo">
        @endif
        <div class="company-name">{{ $sale->company->nombre_comercial ?? $sale->company->razon_social }}</div>
        <div class="company-rz">{{ $sale->company->razon_social }}</div>
        <div class="company-info">
            RUC: <strong>{{ $sale->company->ruc }}</strong><br>
            {{ $sale->company->direccion }}@if($sale->company->distrito), {{ $sale->company->distrito }} — {{ $sale->company->departamento }}@endif<br>
            @if($sale->company->telefono)Tel: {{ $sale->company->telefono }}  @endif
            @if($sale->company->email){{ $sale->company->email }}@endif
        </div>
    </div>
    <div class="header-right">
        <div class="cpe-box">
            <div class="cpe-tipo">{{ $sale->tipo_nombre }}</div>
            @if($sale->tipo_comprobante !== 'NV')
            <div class="cpe-subtipo">Comprobante Electrónico</div>
            @endif
            <div class="cpe-num">{{ $sale->numero_comprobante }}</div>
            <div class="cpe-fecha">{{ $sale->fecha_emision->format('d/m/Y') }}</div>
        </div>
    </div>
</div>

{{-- ============ DATOS CLIENTE ============ --}}
<div class="section">
    <div class="section-title">Datos del Cliente</div>
    <div class="section-body">
        <div class="client-row">
            <div class="client-col w50">
                <div class="field-label">Apellidos y Nombres / Razón Social</div>
                <div class="field-value">{{ $sale->student->nombre_completo }}</div>
            </div>
            <div class="client-col w35">
                <div class="field-label">{{ $sale->student->tipo_doc_nombre }}</div>
                <div class="field-value">{{ $sale->student->numero_documento ?? '—' }}</div>
            </div>
            <div class="client-col w15">
                <div class="field-label">Cód. Alumno</div>
                <div class="field-value">{{ $sale->student->codigo }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ============ DETALLE ============ --}}
<div class="section">
    <div class="section-title">Detalle del Servicio</div>
    <table class="items">
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:8%">U.M.</th>
                <th style="width:42%">Descripción</th>
                <th style="width:8%;text-align:center">Cant.</th>
                <th style="width:17%;text-align:right">Valor Unit.</th>
                <th style="width:20%;text-align:right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->unidad_medida ?? 'ZZ' }}</td>
                <td>{{ $item->descripcion }}</td>
                <td class="text-center">{{ number_format($item->cantidad, 0) }}</td>
                <td class="text-right">S/ {{ number_format($item->valor_unitario, 2) }}</td>
                <td class="text-right">S/ {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ============ TOTALES + PAGO ============ --}}
<div class="totales-row">
    <div class="totales-left">
        {{-- Forma de pago --}}
        @if($sale->payment)
        <div class="section-title" style="border-radius:3px">Condición de Pago</div>
        <div class="pago-info" style="margin-top:5px">
            <strong>Forma:</strong> {{ $sale->payment->forma_pago }}<br>
            @if($sale->payment->numero_operacion)
            <strong>N° Operación:</strong> {{ $sale->payment->numero_operacion }}<br>
            @endif
            @if($sale->payment->periodo_pago)
            <strong>Período:</strong> {{ $sale->payment->periodo_pago }}<br>
            @endif
            @if($sale->payment->observaciones)
            <strong>Obs.:</strong> {{ $sale->payment->observaciones }}
            @endif
        </div>
        @endif

        {{-- IGV info --}}
        <div style="margin-top:10px;font-size:7.5pt;color:#888;line-height:1.7">
            @if($sale->mto_oper_exoneradas > 0)
            SON: S/ {{ number_format($sale->mto_imp_venta, 2) }} — OPERACIÓN EXONERADA DE IGV<br>
            @elseif($sale->mto_oper_inafectas > 0)
            SON: S/ {{ number_format($sale->mto_imp_venta, 2) }} — OPERACIÓN INAFECTA DE IGV<br>
            @else
            SON: S/ {{ number_format($sale->mto_imp_venta, 2) }} — INCLUYE IGV<br>
            @endif
            Moneda: SOLES (PEN)
        </div>
    </div>

    <div class="totales-right">
        @if($sale->mto_oper_gravadas > 0)
        <div class="total-line">
            <span class="tl-label">Op. Gravadas</span>
            <span class="tl-value">S/ {{ number_format($sale->mto_oper_gravadas, 2) }}</span>
        </div>
        <div class="total-line">
            <span class="tl-label">IGV (18%)</span>
            <span class="tl-value">S/ {{ number_format($sale->mto_igv, 2) }}</span>
        </div>
        @endif
        @if($sale->mto_oper_exoneradas > 0)
        <div class="total-line">
            <span class="tl-label">Op. Exoneradas</span>
            <span class="tl-value">S/ {{ number_format($sale->mto_oper_exoneradas, 2) }}</span>
        </div>
        @endif
        @if($sale->mto_oper_inafectas > 0)
        <div class="total-line">
            <span class="tl-label">Op. Inafectas</span>
            <span class="tl-value">S/ {{ number_format($sale->mto_oper_inafectas, 2) }}</span>
        </div>
        @endif
        <div class="total-line grand">
            <span class="tl-label">TOTAL A PAGAR</span>
            <span class="tl-value">S/ {{ number_format($sale->mto_imp_venta, 2) }}</span>
        </div>
    </div>
</div>

{{-- ============ ESTADO SUNAT / HASH ============ --}}
@if($sale->tipo_comprobante === 'NV')
<div class="sunat-bar sunat-nv">
    <i>Nota de Venta — documento interno, no requiere envío a SUNAT</i>
</div>
@elseif($sale->estado_sunat === 'ACEPTADO')
<div class="sunat-bar sunat-ok">
    ✓ COMPROBANTE ELECTRÓNICO ACEPTADO POR SUNAT
    @if($sale->hash_cpe)
    <div class="hash-text">Clave: {{ $sale->hash_cpe }}</div>
    @endif
</div>
@else
<div class="sunat-bar sunat-pending">
    ⏳ {{ $sale->sunat_descripcion ?? 'Pendiente de validación SUNAT' }}
</div>
@endif

{{-- ============ QR (representación textual) ============ --}}
@if($sale->tipo_comprobante !== 'NV')
@php
    $qrData = implode('|', [
        $sale->company->ruc,
        $sale->tipo_comprobante,
        $sale->serie,
        $sale->correlativo,
        number_format($sale->mto_igv, 2, '.', ''),
        number_format($sale->mto_imp_venta, 2, '.', ''),
        $sale->fecha_emision->format('Y-m-d'),
        $sale->student->tipo_documento ?? '1',
        $sale->student->numero_documento ?? '',
    ]);
@endphp
<div class="qr-wrap">
    <div class="qr-box">
        <div style="font-size:6pt;margin-bottom:4px;color:#aaa">REPRESENTACIÓN DEL CÓDIGO QR</div>
        <div style="font-size:6.5pt;color:#555;word-break:break-all;max-width:260px">{{ $qrData }}</div>
        <div style="font-size:6pt;margin-top:4px;color:#aaa">Consulte su comprobante en: www.sunat.gob.pe</div>
    </div>
</div>
@endif

{{-- ============ FOOTER ============ --}}
<div class="footer">
    <strong>¡Gracias por confiar en {{ $sale->company->nombre_comercial ?? $sale->company->razon_social }}!</strong><br>
    @if($sale->tipo_comprobante !== 'NV')
    Representación impresa de Comprobante de Pago Electrónico<br>
    @endif
    @if($sale->company->email){{ $sale->company->email }}  |  @endif
    @if($sale->company->telefono)Tel: {{ $sale->company->telefono }}  |  @endif
    Emitido por: {{ $sale->user->name ?? 'Sistema' }}
</div>

</div>
</body>
</html>
