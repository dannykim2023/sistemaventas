<?php

namespace App\Filament\Resources\Ventas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->relationship('cliente', 'nombre') // muestra el nombre del cliente
                    ->searchable()
                    ->preload()
                    ->required(),
                    
               Select::make('cotizacion_id')
                    ->relationship('cotizacion', 'id') // usa el id real para la query
                    ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->cliente->nombre} - S/ {$record->total_con_igv} - {$record->estado}")
                    ->searchable()
                    ->preload()
                    ->label('Cotización')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $cotizacion = \App\Models\Cotizacion::find($state);
                            if ($cotizacion) {
                                $set('total', $cotizacion->total_con_igv); 
                            }
                        }
                    }),


            

                DatePicker::make('fecha')
                    ->required(),
                    
                Select::make('estado')
                    ->options([
                        'en curso' => 'En curso',
                        'finalizada' => 'Finalizada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->required(),
                    
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0)
                    //->disabled()
                    ->label('Total de la Venta'),
                    
                TextInput::make('abonado')
                    ->live(onBlur: true) 
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    //->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // $state es el abonado
                        $total = $get('total') ?? 0;
                        $saldo = $total - $state;
                        $set('saldo', max($saldo, 0)); // evita números negativos
                    }),
                    
                TextInput::make('saldo')
                    
                    ->required()
                    ->numeric()
                    ->default(0)
                 
                    ->extraInputAttributes(['readonly' => 'readonly'])
                    ->label('Saldo Pendiente'),
                    
                DatePicker::make('fecha_siguiente_pago'),
            ]);
    }
}
