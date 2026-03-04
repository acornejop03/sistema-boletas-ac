<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo', 'tipo_documento', 'numero_documento',
        'nombres', 'apellido_paterno', 'apellido_materno',
        'fecha_nacimiento', 'email', 'telefono',
        'telefono_apoderado', 'nombre_apoderado',
        'direccion', 'ubigeo', 'foto_path', 'activo',
    ];

    protected $casts = [
        'activo'           => 'boolean',
        'fecha_nacimiento' => 'date',
        'tipo_documento'   => 'integer',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->apellido_paterno} {$this->apellido_materno} {$this->nombres}");
    }

    public function getTipoDocNombreAttribute(): string
    {
        return match ($this->tipo_documento) {
            1  => 'DNI',
            6  => 'RUC',
            default => 'Sin documento',
        };
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public static function generarCodigo(): string
    {
        $year = date('Y');
        $last = static::where('codigo', 'like', "ALU-{$year}-%")->orderBy('id', 'desc')->first();
        $seq = $last ? ((int) substr($last->codigo, -4)) + 1 : 1;
        return "ALU-{$year}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
