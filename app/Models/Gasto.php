<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'proveedor_id','categoria_id','descripcion','monto_total',
        'estado_pago','metodo_pago','fecha_emision','fecha_pago',
        'comprobante_path','notas',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_pago'    => 'date',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function categoria()
    {
        return $this->belongsTo(GastoCategoria::class, 'categoria_id');
    }
}
