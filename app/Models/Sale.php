<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'student_id', 'payment_id', 'user_id',
        'tipo_comprobante', 'serie', 'correlativo', 'fecha_emision', 'moneda',
        'mto_oper_gravadas', 'mto_oper_exoneradas', 'mto_oper_inafectas',
        'mto_igv', 'valorventa', 'total_impuestos', 'mto_imp_venta',
        'estado_sunat', 'sunat_descripcion', 'hash_cpe',
        'nombre_xml', 'ruta_xml', 'ruta_cdr', 'ruta_pdf', 'observaciones',
    ];

    protected $casts = [
        'fecha_emision'        => 'date',
        'mto_oper_gravadas'    => 'float',
        'mto_oper_exoneradas'  => 'float',
        'mto_oper_inafectas'   => 'float',
        'mto_igv'              => 'float',
        'valorventa'           => 'float',
        'total_impuestos'      => 'float',
        'mto_imp_venta'        => 'float',
    ];

    public function getNumeroComprobanteAttribute(): string
    {
        return $this->serie . '-' . str_pad($this->correlativo, 8, '0', STR_PAD_LEFT);
    }

    public function getTipoNombreAttribute(): string
    {
        return $this->tipo_comprobante === '01' ? 'FACTURA' : 'BOLETA';
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado_sunat) {
            'ACEPTADO'  => '<span class="badge bg-success">Aceptado</span>',
            'PENDIENTE' => '<span class="badge bg-warning text-dark">Pendiente</span>',
            'RECHAZADO' => '<span class="badge bg-danger">Rechazado</span>',
            'ANULADO'   => '<span class="badge bg-secondary">Anulado</span>',
            default     => '<span class="badge bg-info">' . $this->estado_sunat . '</span>',
        };
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function sunatResponses(): HasMany
    {
        return $this->hasMany(SunatResponse::class);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_sunat', 'PENDIENTE');
    }

    public function scopeAceptados($query)
    {
        return $query->where('estado_sunat', 'ACEPTADO');
    }

    public function scopeBoletas($query)
    {
        return $query->where('tipo_comprobante', '03');
    }

    public function scopeFacturas($query)
    {
        return $query->where('tipo_comprobante', '01');
    }

    public static function nextCorrelativo(int $companyId, string $serie): int
    {
        $last = static::where('company_id', $companyId)
            ->where('serie', $serie)
            ->max('correlativo');
        return ($last ?? 0) + 1;
    }
}
