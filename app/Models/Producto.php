<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'productos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'titulo',        // Ej: "Diseño página web institucional"
        'descripcion',   // Detalle del producto o servicio
        'tipo',          // Ej: "servicio", "producto físico"
        'precio_costo',  // Costo base
        'precio_venta',  // Precio al cliente
    ];

    /**
     * Accessor: Calcula el margen en tiempo real.
     * No existe en la base de datos, se genera al acceder a $producto->margen
     */
    public function getMargenAttribute(): float
    {
        return (float) $this->precio_venta - (float) $this->precio_costo;
    }

    /**
     * Accessor opcional: formato de dinero
     */
    public function getPrecioVentaFormattedAttribute(): string
    {
        return 'S/ ' . number_format($this->precio_venta, 2);
    }

    public function getPrecioCostoFormattedAttribute(): string
    {
        return 'S/ ' . number_format($this->precio_costo, 2);
    }

    public function getMargenFormattedAttribute(): string
    {
        return 'S/ ' . number_format($this->margen, 2);
    }
}
