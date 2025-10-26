<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Services\ApiPeruService;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tipo_cliente')
                ->label('Tipo de documento')
                ->options([
                    'persona' => 'DNI',
                    'empresa' => 'RUC',
                ])
                ->default('persona')
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('ruc_dni', '')),

            TextInput::make('ruc_dni')
                ->label('Número de documento')
                ->placeholder('Ingresa DNI (8) o RUC (11)')
                ->required()
                ->reactive()
                ->live(onBlur: true)
                ->rule(function (callable $get) {
                    return function ($attribute, $value, $fail) use ($get) {
                        $tipo = $get('tipo_cliente');
                        $v = (string) $value;
                        if ($tipo === 'persona' && !preg_match('/^\d{8}$/', $v)) $fail('El DNI debe tener 8 dígitos.');
                        if ($tipo === 'empresa' && !preg_match('/^\d{11}$/', $v)) $fail('El RUC debe tener 11 dígitos.');
                    };
                })
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $tipo = $get('tipo_cliente');
                    $doc  = trim((string) $state);
                    if ($tipo === 'persona' && strlen($doc) !== 8) return;
                    if ($tipo === 'empresa' && strlen($doc) !== 11) return;

                    /** @var ApiPeruService $api */
                    $api = app(ApiPeruService::class);

                    try {
                        if ($tipo === 'persona') {
                            $resp = $api->dni($doc);
                            if (($resp['success'] ?? false) && isset($resp['data'])) {
                                $d = $resp['data'];
                                $set('nombre', $d['nombre_completo'] ?? ($d['nombres'] ?? null));
                                Notification::make()->title('DNI validado')->body($d['nombre_completo'] ?? 'Consulta exitosa.')->success()->send();
                            } else {
                                Notification::make()->title('Sin resultados')->body('No se encontraron datos para este DNI.')->danger()->send();
                            }
                        } else {
                            $resp = $api->ruc($doc);
                            if (($resp['success'] ?? false) && isset($resp['data'])) {
                                $r = $resp['data'];
                                $set('nombre', $r['nombre_o_razon_social'] ?? null);
                                $set('direccion', $r['direccion_completa'] ?? ($r['direccion'] ?? null));
                                Notification::make()->title('RUC validado')->body($r['nombre_o_razon_social'] ?? 'Consulta exitosa.')->success()->send();
                            } else {
                                Notification::make()->title('Sin resultados')->body('No se encontraron datos para este RUC.')->danger()->send();
                            }
                        }
                    } catch (\Throwable $e) {
                        Notification::make()->title('Error al validar')->body($e->getMessage())->danger()->send();
                    }
                }),

            TextInput::make('nombre')->label('Nombre / Razón Social')->required(),
            TextInput::make('telefono')->label('Teléfono')->tel()->required(),
            TextInput::make('email')->label('Correo electrónico')->email()->required(),
            TextInput::make('direccion')->label('Dirección'),
        ]);
    }
}
