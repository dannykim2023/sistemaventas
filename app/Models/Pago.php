<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'venta_id',
        'monto',
        'tipo_pago',
        'fecha_pago',
        'nota',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    protected static function booted()
    {
        static::created(function ($pago) {
            $pago->venta?->actualizarSaldo();
        });

        static::deleted(function ($pago) {
            $pago->venta?->actualizarSaldo();
        });

        // Si permites editar el monto y quieres que la edición también recalcule:
        static::updated(function ($pago) {
            // comprobar si monto o venta_id cambiaron (por simplicidad, siempre recalculamos)
            $pago->venta?->actualizarSaldo();
        });
    }
}
