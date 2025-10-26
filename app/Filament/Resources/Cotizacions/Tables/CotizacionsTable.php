<?php

namespace App\Filament\Resources\Cotizacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\Cotizacion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CotizacionsTable
{


    
    public static function configure(Table $table): Table
    {

        
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_sin_igv')
                    ->label('Subtotal')
                    ->money('PEN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('igv_monto')
                    ->label('IGV')
                    ->money('PEN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_con_igv')
                    ->label('Total')
                    ->money('PEN')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'Pendiente',
                        'success' => 'Aceptado',
                        'danger'  => 'Anulado',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Aceptado'  => 'Aceptado',
                        'Anulado'   => 'Anulado',
                    ])

                  
                    ->form([
                        Select::make('month')
                            ->label('Mes')
                            ->options([
                                1 => 'Enero',
                                2 => 'Febrero',
                                3 => 'Marzo',
                                4 => 'Abril',
                                5 => 'Mayo',
                                6 => 'Junio',
                                7 => 'Julio',
                                8 => 'Agosto',
                                9 => 'Septiembre',
                                10 => 'Octubre',
                                11 => 'Noviembre',
                                12 => 'Diciembre',
                            ]),
                        Select::make('year')
                            ->label('Año')
                            ->options(
                                collect(range(Carbon::now()->year, Carbon::now()->year - 5))
                                    ->mapWithKeys(fn ($year) => [$year => $year])
                            ),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['month'], fn ($q, $month) => $q->whereMonth('created_at', $month))
                            ->when($data['year'], fn ($q, $year) => $q->whereYear('created_at', $year));
                    }),

                
            ])
            ->recordActions([
                // ACCION PARA CREAR VENTA Y REGISTRAR CRUD DE PAGO
                Action::make('crearVenta')
                ->label('Crear Venta')
                ->icon('heroicon-o-currency-dollar')
                ->form([
                    TextInput::make('abonado')
                        ->label('Monto abonado')
                        ->numeric()
                        ->required(),
                    Select::make('tipo_pago')
                        ->label('Método de pago')
                        ->options([
                            'efectivo'      => 'Efectivo',
                            'transferencia' => 'Transferencia',
                            'yape'          => 'Yape',
                            'tarjeta'       => 'Tarjeta',
                        ])
                        ->required(),


                    DatePicker::make('fecha_siguiente_pago')
                        ->label('Fecha siguiente pago'),
                ])
                ->action(function ($data, Cotizacion $record) {
                    // Crear la venta a partir de la cotización
                    $venta = \App\Models\Venta::create([
                        'cliente_id'          => $record->cliente_id,
                        'cotizacion_id'       => $record->id,
                        'fecha'               => now(),
                        'estado'              => 'en curso', // default
                        'total'               => $record->total_con_igv,
                        'abonado'             => $data['abonado'],
                        'saldo'               => $record->total_con_igv - $data['abonado'],
                        'fecha_siguiente_pago'=> $data['fecha_siguiente_pago'],
                    ]);

                    // Copiar detalles de la cotización
                    foreach ($record->detalles as $detalle) {
                        \App\Models\VentaDetalle::create([
                            'venta_id'        => $venta->id,
                            'producto_id'     => $detalle->producto_id, // ⚠️ aquí usamos productos, no catalogo
                            'cantidad'        => $detalle->cantidad,
                            'precio_unitario' => $detalle->precio_unitario,
                            'subtotal'        => $detalle->subtotal,
                        ]);

                    
                    }

                    // 👈 Registrar el pago inicial automáticamente
                    \App\Models\Pago::create([
                        'venta_id'   => $venta->id,
                        'monto'      => $data['abonado'],
                        'tipo_pago'  => $data['tipo_pago'],  // <-- aquí usamos la selección del usuario
                        'fecha_pago' => now(),
                        'nota'       => 'Pago inicial generado automáticamente desde la cotización',
                    ]);


                    // Actualizar cotización a aceptada
                    $record->update(['estado' => 'aceptada']);

                    // ✅ Mostrar notificación
                    Notification::make()
                        ->title('Venta creada correctamente')
                        ->success()
                        ->send();
    }),

              



                EditAction::make(),
                DeleteAction::make(),

                // 👇 Acción personalizada: Descargar PDF
                Action::make('pdf')
                    ->label('Generar PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn (Cotizacion $record) =>
                        URL::temporarySignedRoute(
                            'cotizaciones.preview',
                            now()->addMinutes(5),      // URL válida por 5 min
                            ['cotizacion' => $record->getKey()]
                        )
                    )
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
