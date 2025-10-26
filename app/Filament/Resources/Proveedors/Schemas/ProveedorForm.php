<?php

namespace App\Filament\Resources\Proveedors\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Notifications\Notification;
use App\Services\ApiPeruService;

class ProveedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Tipo de proveedor
                Forms\Components\Select::make('tipo')
                    ->label('Tipo de Proveedor')
                    ->options([
                        'empresa'   => 'Empresa',
                        'empleado'  => 'Empleado',
                        'freelance' => 'Freelance',
                    ])
                    ->default('empleado')
                    ->required()
                    ->native(false)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Limpia campos dependientes si cambia el tipo
                        $set('documento_numero', null);
                        $set('nombre', null);
                        $set('razon_social', null);
                    }),

                // Tipo de documento (simple)
                Forms\Components\Select::make('documento_tipo')
                    ->label('Tipo de Documento')
                    ->options([
                        'DNI' => 'DNI',
                        'RUC' => 'RUC',
                        'CE'  => 'Carnet Extranjería',
                    ])
                    ->default('DNI')
                    ->required()
                    ->native(false),

                // Número + validación + autocompletar DNI/RUC
                Forms\Components\TextInput::make('documento_numero')
                    ->label('Número de Documento')
                    ->placeholder(fn (callable $get) => $get('documento_tipo') === 'RUC' ? '11 dígitos' : '8 dígitos')
                    ->required()
                    ->reactive()
                    ->live(onBlur: true)
                    ->rule(function (callable $get) {
                        return function ($attribute, $value, $fail) use ($get) {
                            $tipo = $get('documento_tipo');
                            $v = (string) $value;
                            if ($tipo === 'DNI' && !preg_match('/^\d{8}$/', $v)) {
                                $fail('El DNI debe tener 8 dígitos.');
                            }
                            if ($tipo === 'RUC' && !preg_match('/^\d{11}$/', $v)) {
                                $fail('El RUC debe tener 11 dígitos.');
                            }
                            // Si usas CE, agrega validación según tu API.
                        };
                    })
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $tipo = $get('documento_tipo');
                        $num  = trim((string) $state);
                        if (! $tipo || $num === '') {
                            return;
                        }

                        // Evita llamadas antes de tiempo
                        if ($tipo === 'DNI' && strlen($num) !== 8)  return;
                        if ($tipo === 'RUC' && strlen($num) !== 11) return;

                        /** @var ApiPeruService $api */
                        $api = app(ApiPeruService::class);

                        try {
                            if ($tipo === 'DNI') {
                                $resp = $api->dni($num);
                                if (($resp['success'] ?? false) && isset($resp['data'])) {
                                    $d = $resp['data'];
                                    $set('nombre', $d['nombre_completo'] ?? ($d['nombres'] ?? null));
                                    Notification::make()
                                        ->title('DNI validado')
                                        ->body($d['nombre_completo'] ?? 'Consulta exitosa.')
                                        ->success()->send();
                                } else {
                                    Notification::make()
                                        ->title('Sin resultados')
                                        ->body('No se encontraron datos para este DNI.')
                                        ->danger()->send();
                                }
                            }

                            if ($tipo === 'RUC') {
                                $resp = $api->ruc($num);
                                if (($resp['success'] ?? false) && isset($resp['data'])) {
                                    $r = $resp['data'];
                                    $set('razon_social', $r['nombre_o_razon_social'] ?? null);
                                    // Si manejas dirección en tu tabla:
                                    if (isset($r['direccion_completa']) || isset($r['direccion'])) {
                                        $set('direccion', $r['direccion_completa'] ?? ($r['direccion'] ?? null));
                                    }
                                    Notification::make()
                                        ->title('RUC validado')
                                        ->body($r['nombre_o_razon_social'] ?? 'Consulta exitosa.')
                                        ->success()->send();
                                } else {
                                    Notification::make()
                                        ->title('Sin resultados')
                                        ->body('No se encontraron datos para este RUC.')
                                        ->danger()->send();
                                }
                            }
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Error al validar')
                                ->body($e->getMessage())
                                ->danger()->send();
                        }
                    }),

                // Campos visibles según tipo
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->placeholder('Para empleado o freelance')
                    ->visible(fn (callable $get) => $get('tipo') !== 'empresa'),

                Forms\Components\TextInput::make('razon_social')
                    ->label('Razón Social')
                    ->placeholder('Solo para empresa')
                    ->visible(fn (callable $get) => $get('tipo') === 'empresa'),

                // (Opcional) Dirección si la tienes en la BD
                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->nullable(),

                // Contacto
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20)
                    ->nullable(),

                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->nullable(),

                // Pago
                Forms\Components\Select::make('pago_preferido')
                    ->label('Método de Pago Preferido')
                    ->options([
                        'transferencia' => 'Transferencia',
                        'yape'          => 'Yape',
                        'plin'          => 'Plin',
                        'efectivo'      => 'Efectivo',
                    ])
                    ->required()
                    ->native(false),

                Forms\Components\TextInput::make('cuenta')
                    ->label('Cuenta / CCI / N° Yape / Plin')
                    ->nullable(),

                Forms\Components\Textarea::make('notas')
                    ->label('Notas adicionales')
                    ->nullable()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
