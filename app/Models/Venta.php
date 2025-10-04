<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $fillable = [
        'cliente_id',
        'cotizacion_id',
        'fecha',
        'estado',
        'total',
        'abonado',
        'saldo',
        'fecha_siguiente_pago',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }

    /**
     * Recalcula abonado y saldo; si saldo <= 0 marca la venta como finalizada.
     */
    public function actualizarSaldo(): void
    {
        $abonado = (float) $this->pagos()->sum('monto');
        $saldo = (float) $this->total - $abonado;

        // prevenir números negativos por problemas de redondeo
        $saldoRounded = round($saldo, 2);
        if ($saldoRounded < 0) {
            $saldoRounded = 0.00;
        }

        $nuevoEstado = $this->estado;
        if ($saldoRounded <= 0.0) {
            $nuevoEstado = 'finalizada';
        }

        $this->update([
            'abonado' => $abonado,
            'saldo'   => $saldoRounded,
            'estado'  => $nuevoEstado,
        ]);
    }
}
