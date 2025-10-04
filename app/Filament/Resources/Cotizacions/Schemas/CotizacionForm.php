<?php

namespace App\Filament\Resources\Cotizacions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;

class CotizacionForm
{
    public static function configure($form)
    {
        return $form->schema([

            // Cliente con buscador
            Select::make('cliente_id')
                ->label('Cliente')
                ->relationship('cliente', 'nombre')
                ->searchable()
                ->createOptionForm([
                    TextInput::make('nombre')
                        ->required()
                        ->label('Nombre del Cliente'),
                    TextInput::make('email')
                        ->email()
                        ->label('Correo'),
                ])
                ->required(),

            Hidden::make('fecha')
                ->default(now())
                ->required(),

            Toggle::make('igv_incluido')
                ->label('Incluir IGV (18%)')
                ->default(false)
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set, $get) => self::recalcularTotales($set, $get)),

            // Repeater de productos/servicios
            Repeater::make('detalles')
                ->label('Productos/Servicios')
                ->relationship('detalles')
                ->schema([
                    Select::make('producto_id')
                        ->label('Producto/Servicio')
                        ->relationship('producto', 'titulo')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            if ($state) {
                                $producto = \App\Models\Producto::find($state);
                                if ($producto) {
                                    $set('precio_unitario', $producto->precio_venta);
                                }
                            }
                            self::recalcularItem($set, $get);
                        })
                        ->required(),

                    TextInput::make('cantidad')
                        ->label('Cantidad')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set, $get) => self::recalcularItem($set, $get)),

                    TextInput::make('precio_unitario')
                        ->label('Precio Unitario')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set, $get) => self::recalcularItem($set, $get)),

                    // Subtotal mostrado
                    Placeholder::make('subtotal_display')
                        ->label('Subtotal del Item')
                        ->content(fn($get) => 'S/ ' . number_format($get('subtotal') ?? 0, 2)),

                    // Subtotal guardado (campo real)
                    Hidden::make('subtotal')->default(0)->reactive(),
                ])
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set, $get) => self::recalcularTotales($set, $get))
                ->createItemButtonLabel('+ Agregar Item')
                ->defaultItems(1),

            // Descuento global
            TextInput::make('descuento_global')
                ->label('Descuento Global')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set, $get) => self::recalcularTotales($set, $get)),

            // Totales
            Placeholder::make('total_sin_igv_display')
                ->label('Subtotal (sin IGV)')
                ->content(fn($get) => 'S/ ' . number_format($get('total_sin_igv') ?? 0, 2)),

            Placeholder::make('igv_display')
                ->label('IGV (18%)')
                ->content(fn($get) => ($get('igv_incluido') ?? false) 
                    ? 'S/ ' . number_format($get('igv_monto') ?? 0, 2) 
                    : 'S/ 0.00'),

            Placeholder::make('total_con_igv_display')
                ->label('TOTAL FINAL')
                ->content(fn($get) => 'S/ ' . number_format($get('total_con_igv') ?? 0, 2)),

            Hidden::make('total_sin_igv')->default(0)->reactive(),
            Hidden::make('igv_monto')->default(0)->reactive(),
            Hidden::make('total_con_igv')->default(0)->reactive(),
        ]);
    }

    /**
     * Recalcula el subtotal de un ítem dentro del repeater
     */
    private static function recalcularItem(callable $set, $get): void
    {
        $precio = $get('precio_unitario') ?? 0;
        $cantidad = $get('cantidad') ?? 1;

        $subtotal = max(($precio * $cantidad), 0);
        $set('subtotal', $subtotal);
    }

    /**
     * Recalcula los totales generales de la cotización
     */
    private static function recalcularTotales(callable $set, $get): void
    {
        $detalles = $get('detalles') ?? [];
        $totalSinIgv = collect($detalles)->sum(fn($item) => $item['subtotal'] ?? 0);

        $descuentoGlobal = $get('descuento_global') ?? 0;
        $totalSinIgv = max($totalSinIgv - $descuentoGlobal, 0);

        $igvIncluido = $get('igv_incluido') ?? false;
        $igvMonto = $igvIncluido ? $totalSinIgv * 0.18 : 0;
        $totalConIgv = $totalSinIgv + $igvMonto;

        $set('total_sin_igv', $totalSinIgv);
        $set('igv_monto', $igvMonto);
        $set('total_con_igv', $totalConIgv);
    }
}
