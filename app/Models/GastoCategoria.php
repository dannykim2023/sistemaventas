<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoCategoria extends Model
{
    protected $fillable = ['nombre','es_fijo'];

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'categoria_id');
    }
}
