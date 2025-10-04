<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Tipo de documento
            Select::make('tipo_cliente')
                ->label('Tipo de documento')
                ->options([
                    'persona' => 'DNI',
                    'empresa' => 'RUC',
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('ruc_dni', '')),

            // Número de documento con validación según el tipo
            TextInput::make('ruc_dni')
                ->label('Número de documento')
                ->required()
                ->reactive()
                ->rule(function (callable $get) {
                    return function ($attribute, $value, $fail) use ($get) {
                        $tipo = $get('tipo_cliente');
                        if ($tipo === 'dni' && !preg_match('/^\d{8}$/', $value)) {
                            $fail('El DNI debe tener exactamente 8 dígitos.');
                        }
                        if ($tipo === 'ruc' && !preg_match('/^\d{11}$/', $value)) {
                            $fail('El RUC debe tener exactamente 11 dígitos.');
                        }
                    };
                }),

            // Nombre o Razón Social
            TextInput::make('nombre')
                ->label('Nombre / Razón Social')
                ->required(),

            // Teléfono
            TextInput::make('telefono')
                ->label('Teléfono')
                ->tel()
                ->required(),

            // Email
            TextInput::make('email')
                ->label('Correo electrónico')
                ->email()
                ->required(),

            // Dirección
            TextInput::make('direccion')
                ->label('Dirección'),
        ]);
    }
}
