<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9pt; color: #333; width: 200px; }
.header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 8px; margin-bottom: 8px; }
.company-name { font-size: 11pt; font-weight: bold; color: #1e3a5f; line-height: 1.3; }
.company-sub { font-size: 8pt; color: #555; }
.comprobante-box {
    border: 2px solid #1e3a5f;
    border-radius: 4px;
    text-align: center;
    padding: 6px;
    margin: 8px 0;
    background: #f0f5ff;
}
.comprobante-tipo { font-size: 10pt; font-weight: bold; color: #1e3a5f; }
.comprobante-num { font-size: 12pt; font-weight: bold; color: #e31212; }
.section-label { font-size: 7.5pt; font-weight: bold; color: #666; text-transform: uppercase; margin-top: 6px; margin-bottom: 2px; border-bottom: 1px dashed #ccc; }
.data-row { display: flex; justify-content: space-between; margin: 2px 0; font-size: 8.5pt; }
.data-label { color: #666; }
.data-value { font-weight: bold; text-align: right; max-width: 60%; }
table { width: 100%; border-collapse: collapse; margin: 6px 0; }
table th { background: #1e3a5f; color: white; font-size: 8pt; padding: 3px 4px; text-align: left; }
table td { font-size: 8pt; padding: 3px 4px; border-bottom: 1px solid #eee; }
table td.right { text-align: right; }
.totales { border-top: 2px solid #1e3a5f; padding-top: 4px; margin-top: 4px; }
.total-line { display: flex; justify-content: space-between; margin: 2px 0; font-size: 8.5pt; }
.total-final { font-size: 11pt; font-weight: bold; color: #1e3a5f; background: #f0f5ff; padding: 4px 6px; border-radius: 4px; }
.sunat-status { margin-top: 8px; text-align: center; font-size: 7.5pt; }
.hash { word-break: break-all; font-size: 7pt; color: #777; }
.footer { text-align: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #ccc; font-size: 7.5pt; color: #777; }
.qr-section { text-align: center; margin: 8px 0; }
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    @if($sale->company->logo_path)
        <img src="{{ public_path('storage/'.$sale->company->logo_path) }}" height="40" style="margin-bottom:4px"><br>
    @else
        <div style="font-size:20pt;color:#1e3a5f">🎓</div>
    @endif
    <div class="company-name">{{ $sale->company->nombre_comercial ?? $sale->company->razon_social }}</div>
    <div class="company-sub">{{ $sale->company->razon_social }}</div>
    <div class="company-sub">RUC: {{ $sale->company->ruc }}</div>
    <div class="company-sub">{{ $sale->company->direccion }}</div>
    <div class="company-sub">{{ $sale->company->distrito }}, {{ $sale->company->departamento }}</div>
    @if($sale->company->telefono)
    <div class="company-sub">Tel: {{ $sale->company->telefono }}</div>
    @endif
</div>

<!-- NÚMERO COMPROBANTE -->
<div class="comprobante-box">
    <div class="comprobante-tipo">{{ $sale->tipo_nombre }}</div>
    <div class="comprobante-tipo" style="font-size:8pt">Electrónica</div>
    <div class="comprobante-num">{{ $sale->numero_comprobante }}</div>
    <div style="font-size:8pt;color:#555">{{ $sale->fecha_emision->format('d/m/Y') }}</div>
</div>

<!-- DATOS CLIENTE -->
<div class="section-label">Datos del Alumno</div>
<div style="font-size:8.5pt; margin-bottom:4px">
    <div><strong>{{ $sale->student->nombre_completo }}</strong></div>
    <div>{{ $sale->student->tipo_doc_nombre }}: {{ $sale->student->numero_documento ?? '—' }}</div>
    <div style="color:#555">Código: {{ $sale->student->codigo }}</div>
</div>

<!-- ITEMS -->
<div class="section-label">Detalle</div>
<table>
    <thead>
        <tr>
            <th style="width:45%">Descripción</th>
            <th style="width:10%;text-align:center">Cant</th>
            <th style="width:20%;text-align:right">P.Unit</th>
            <th style="width:25%;text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->items as $item)
        <tr>
            <td>{{ $item->descripcion }}</td>
            <td style="text-align:center">{{ number_format($item->cantidad, 0) }}</td>
            <td style="text-align:right">{{ number_format($item->precio_unitario, 2) }}</td>
            <td style="text-align:right">{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- TOTALES -->
<div class="totales">
    @if($sale->mto_oper_exoneradas > 0)
    <div class="total-line">
        <span>Op. Exoneradas:</span>
        <span>S/ {{ number_format($sale->mto_oper_exoneradas, 2) }}</span>
    </div>
    @endif
    @if($sale->mto_oper_gravadas > 0)
    <div class="total-line">
        <span>Op. Gravadas:</span>
        <span>S/ {{ number_format($sale->mto_oper_gravadas, 2) }}</span>
    </div>
    <div class="total-line">
        <span>IGV (18%):</span>
        <span>S/ {{ number_format($sale->mto_igv, 2) }}</span>
    </div>
    @endif
    <div class="total-line total-final">
        <span>TOTAL A PAGAR:</span>
        <span>S/ {{ number_format($sale->mto_imp_venta, 2) }}</span>
    </div>
</div>

<!-- FORMA DE PAGO -->
@if($sale->payment)
<div style="margin-top:6px; font-size:8.5pt;">
    <div>Forma de pago: <strong>{{ $sale->payment->forma_pago }}</strong></div>
    @if($sale->payment->numero_operacion)
    <div>N° Operación: {{ $sale->payment->numero_operacion }}</div>
    @endif
</div>
@endif

<!-- ESTADO SUNAT -->
<div class="sunat-status">
    <div style="font-weight:bold;color:{{ $sale->estado_sunat === 'ACEPTADO' ? '#22c55e' : '#f59e0b' }}">
        {{ $sale->estado_sunat === 'ACEPTADO' ? '✓ COMPROBANTE ACEPTADO POR SUNAT' : '⏳ PENDIENTE DE ENVÍO A SUNAT' }}
    </div>
    @if($sale->hash_cpe)
    <div class="hash" style="margin-top:3px">Hash: {{ $sale->hash_cpe }}</div>
    @endif
</div>

<!-- QR (texto simulado si no hay imagen) -->
<div class="qr-section">
    @php
        $qrContent = "{$sale->company->ruc}|{$sale->tipo_comprobante}|{$sale->serie}|{$sale->correlativo}|{$sale->mto_igv}|{$sale->mto_imp_venta}|{$sale->fecha_emision->format('Y-m-d')}|{$sale->student->tipo_documento}|{$sale->student->numero_documento}";
    @endphp
    <div style="font-size:7pt;color:#aaa;word-break:break-all">[ QR: {{ $qrContent }} ]</div>
</div>

<!-- FOOTER -->
<div class="footer">
    <div style="font-weight:bold">¡Gracias por confiar en {{ $sale->company->nombre_comercial ?? $sale->company->razon_social }}!</div>
    <div>Representación impresa de comprobante electrónico</div>
    @if($sale->company->email)
    <div>{{ $sale->company->email }}</div>
    @endif
</div>

</body>
</html>
