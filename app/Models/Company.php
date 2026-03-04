<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'ruc', 'razon_social', 'nombre_comercial', 'ubigeo',
        'departamento', 'provincia', 'distrito', 'direccion',
        'urbanizacion', 'pais', 'telefono', 'email', 'logo_path',
        'serie_boleta', 'serie_factura', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
