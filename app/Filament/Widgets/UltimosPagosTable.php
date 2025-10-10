<?php

namespace App\Filament\Widgets;

use App\Models\Pago; // <-- CORREGIDO: Importamos el modelo Pago
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget; // Usamos BaseWidget para compatibilidad
use Illuminate\Database\Eloquent\Builder;

class UltimosPagosTable extends BaseWidget // Nota: Extiende de BaseWidget o TableWidget
{
    protected static ?string $heading = 'Últimos 5 Pagos Realizados';
    protected int | string | array $columnSpan = 'full'; 

    // Usaremos getTableQuery() para definir la consulta base, que es el estándar de Filament.
    protected function getTableQuery(): Builder
    {
        // Traemos los últimos 5 pagos
        return Pago::query() // <-- CORREGIDO: Usamos Pago::query()
            ->latest() 
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            // ID del Pago
            TextColumn::make('id')
                ->label('ID Pago'),
            
            // Relación a Venta -> Cliente -> Nombre
            TextColumn::make('venta.cliente.nombre') 
                ->label('Cliente')
                ->searchable()
                ->sortable(),

            // Monto del Pago (Usamos el campo 'monto' del modelo Pago)
            TextColumn::make('monto') 
                ->label('Monto Pagado')
                ->money('PEN') // Moneda: Soles Peruanos
                ->color('success')
                ->sortable(),

            // Tipo de Pago
            TextColumn::make('tipo_pago')
                ->label('Tipo de Pago'),

            // Fecha del Pago
            TextColumn::make('fecha_pago')
                ->label('Fecha')
                ->dateTime('d M, Y')
                ->sortable(),
        ];
    }
}