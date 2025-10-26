<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;

    // 👇 fuerza a Eloquent a usar la tabla correcta
    protected $table = 'proveedores';

    protected $fillable = [
        'tipo','razon_social','nombre','documento_tipo','documento_numero',
        'telefono','email','pago_preferido','cuenta','notas',
    ];

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }

    public function getEtiquetaAttribute(): string
    {
        return $this->tipo === 'empresa'
            ? ($this->razon_social ?? '—')
            : ($this->nombre ?? '—');
    }
}
