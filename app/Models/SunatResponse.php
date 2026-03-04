<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SunatResponse extends Model
{
    protected $fillable = [
        'sale_id', 'accion', 'endpoint_url', 'xml_enviado',
        'codigo_respuesta', 'descripcion_respuesta',
        'exitoso', 'intento',
    ];

    protected $casts = [
        'exitoso' => 'boolean',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
