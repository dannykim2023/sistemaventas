<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PagoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Select::make('venta_id')
            ->label('Venta')
            ->options(
                \App\Models\Venta::with('cliente')
                    ->get()
                    ->mapWithKeys(fn ($venta) => [
                        $venta->id => "{$venta->cliente->nombre} | Total: S/{$venta->total}"
                    ])
            )
            ->searchable()
            ->required(),

                TextInput::make('monto')
                    ->required()
                    ->numeric(),
                Select::make('tipo_pago')
                    ->options([
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'yape' => 'Yape',
            'tarjeta' => 'Tarjeta',
        ])
                    ->required(),
                DatePicker::make('fecha_pago')
                    ->required(),
                Textarea::make('nota')
                    ->columnSpanFull(),
            ]);
    }
}
