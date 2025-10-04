<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado',
        'total_sin_igv',
        'igv_incluido',
        'igv_monto',
        'total_con_igv',
        'descuento',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'igv_incluido' => 'boolean',
        'total_sin_igv' => 'decimal:2',
        'igv_monto' => 'decimal:2',
        'total_con_igv' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    // Relación con ventas (opcional pero útil)
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'cotizacion_id');
    }

    /**
     * Convierte la cotización en venta:
     * - crea la venta
     * - copia los detalles a venta_detalles
     * - marca la cotización como 'aceptada'
     *
     * Retorna la instancia Venta creada.
     *
     * Lanza Exception si la cotización ya está aceptada.
     */
    public function convertirAVenta(string $estadoVenta = 'en curso'): Venta
    {
        if ($this->estado === 'aceptada') {
            throw new \Exception('La cotización ya está aceptada.');
        }

        return DB::transaction(function () use ($estadoVenta) {
            // crear la venta
            /** @var Venta $venta */
            $venta = Venta::create([
                'cliente_id'    => $this->cliente_id,
                'cotizacion_id' => $this->id,
                'fecha'         => now(),
                'estado'        => $estadoVenta,
                'total'         => $this->total_con_igv,
                'abonado'       => 0,
                'saldo'         => $this->total_con_igv,
            ]);

            // copiar detalles
            foreach ($this->detalles as $detalle) {
                $venta->detalles()->create([
                    'producto_id'     => $detalle->producto_id,
                    'cantidad'        => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal'        => $detalle->subtotal,
                ]);
            }

            // marcar cotización como aceptada
            $this->update(['estado' => 'aceptada']);

            return $venta;
        });
    }
}
