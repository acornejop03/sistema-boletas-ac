<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'course_id', 'user_id', 'periodo',
        'fecha_matricula', 'fecha_inicio', 'fecha_fin',
        'turno', 'estado', 'observaciones',
    ];

    protected $casts = [
        'fecha_matricula' => 'date',
        'fecha_inicio'    => 'date',
        'fecha_fin'       => 'date',
    ];

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'ACTIVO'     => '<span class="badge bg-success">Activo</span>',
            'CULMINADO'  => '<span class="badge bg-info">Culminado</span>',
            'RETIRADO'   => '<span class="badge bg-danger">Retirado</span>',
            'SUSPENDIDO' => '<span class="badge bg-warning text-dark">Suspendido</span>',
            default      => '<span class="badge bg-secondary">' . $this->estado . '</span>',
        };
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
