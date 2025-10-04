<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'tipo_cliente',
        'ruc_dni',
        'nombre',
        'telefono',
        'email',
        'direccion',
    ];

    // Relaciones
    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class);
    }

    public function pagos()
    {
        return $this->hasManyThrough(
            \App\Models\Pago::class,
            \App\Models\Venta::class,
            'cliente_id',
            'venta_id',
            'id',
            'id'
        );
    }
}
