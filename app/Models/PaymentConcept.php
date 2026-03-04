<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConcept extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'monto_default',
        'tipo_afectacion_igv', 'activo',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'monto_default' => 'float',
    ];
}
