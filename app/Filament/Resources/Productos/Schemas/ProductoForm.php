<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('titulo')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('descripcion')
                ->label('Descripción')
                ->nullable(),

            Forms\Components\Select::make('tipo')
                ->label('Tipo')
                ->options([
                    'producto' => 'Producto',
                    'servicio' => 'Servicio',
                ])
                ->default('producto')
                ->required(),

            Forms\Components\TextInput::make('precio_costo')
                ->label('Precio Costo')
                ->numeric()
                ->prefix('S/')
                ->default(0),

            Forms\Components\TextInput::make('precio_venta')
                ->label('Precio Venta')
                ->numeric()
                ->prefix('S/')
                ->default(0),

            Forms\Components\TextInput::make('margen')
                ->label('Margen (auto)')
                ->extraInputAttributes(['readonly' => 'readonly'])
                ->dehydrated(false)
                ->afterStateHydrated(fn ($state, $record) => $record?->margen ?? 0),
        ]);
    }
}
