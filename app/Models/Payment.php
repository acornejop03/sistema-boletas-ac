<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'student_id', 'enrollment_id', 'user_id',
        'tipo_pago', 'periodo_pago', 'descripcion_pago', 'fecha_pago',
        'forma_pago', 'numero_operacion', 'subtotal', 'igv', 'total',
        'estado_pago', 'observaciones',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'subtotal'   => 'float',
        'igv'        => 'float',
        'total'      => 'float',
    ];

    public function getFormaPagoIconAttribute(): string
    {
        return match ($this->forma_pago) {
            'EFECTIVO'     => '💵 Efectivo',
            'TARJETA'      => '💳 Tarjeta',
            'TRANSFERENCIA'=> '🏦 Transferencia',
            'YAPE'         => '📱 Yape',
            'PLIN'         => '📱 Plin',
            default        => $this->forma_pago,
        };
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado_pago) {
            'PAGADO'   => '<span class="badge bg-success">Pagado</span>',
            'PENDIENTE'=> '<span class="badge bg-warning text-dark">Pendiente</span>',
            'ANULADO'  => '<span class="badge bg-danger">Anulado</span>',
            default    => '<span class="badge bg-secondary">' . $this->estado_pago . '</span>',
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

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }
}
