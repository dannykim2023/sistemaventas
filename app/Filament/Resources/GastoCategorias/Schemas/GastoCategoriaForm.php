<?php

namespace App\Filament\Resources\GastoCategorias\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class GastoCategoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre de la categoría')
                    ->placeholder('Ej. Servicios, Alquiler, Publicidad')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),

                Forms\Components\Toggle::make('es_fijo')
                    ->label('¿Es un gasto fijo?')
                    ->helperText('Por ejemplo: alquiler, luz, agua, etc.')
                    ->default(false),
            ])
            ->columns(1);
    }
}
