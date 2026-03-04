<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'orden', 'descripcion', 'unidad_medida',
        'cantidad', 'valor_unitario', 'precio_unitario',
        'mto_valor_venta', 'mto_base_igv', 'porcentaje_igv',
        'igv', 'tipo_afectacion_igv', 'total',
    ];

    protected $casts = [
        'cantidad'         => 'float',
        'valor_unitario'   => 'float',
        'precio_unitario'  => 'float',
        'mto_valor_venta'  => 'float',
        'mto_base_igv'     => 'float',
        'porcentaje_igv'   => 'float',
        'igv'              => 'float',
        'total'            => 'float',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
