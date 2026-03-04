<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'codigo', 'nombre', 'descripcion', 'nivel',
        'duracion_meses', 'duracion_horas', 'precio_matricula',
        'precio_pension', 'precio_materiales', 'afecto_igv',
        'tipo_afectacion_igv', 'max_alumnos', 'activo',
    ];

    protected $casts = [
        'afecto_igv'       => 'boolean',
        'activo'           => 'boolean',
        'precio_matricula' => 'float',
        'precio_pension'   => 'float',
        'precio_materiales'=> 'float',
    ];

    public function getNivelBadgeAttribute(): string
    {
        return match ($this->nivel) {
            'BASICO'      => '<span class="badge bg-success">Básico</span>',
            'INTERMEDIO'  => '<span class="badge bg-warning text-dark">Intermedio</span>',
            'AVANZADO'    => '<span class="badge bg-danger">Avanzado</span>',
            default       => '<span class="badge bg-secondary">' . $this->nivel . '</span>',
        };
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
