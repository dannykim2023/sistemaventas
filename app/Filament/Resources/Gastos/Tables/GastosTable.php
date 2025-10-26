<?php

namespace App\Filament\Resources\Gastos\Tables;

use Filament\Tables;
use Filament\Forms;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GastosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Fecha
                Tables\Columns\TextColumn::make('fecha_emision')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                // Categoría (relación)
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable()
                    ->searchable(),

                // Proveedor (relación)
                Tables\Columns\TextColumn::make('proveedor.etiqueta')
                    ->label('Proveedor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Descripción
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(30)
                    ->wrap()
                    ->searchable(),

                // Monto total
                Tables\Columns\TextColumn::make('monto_total')
                    ->label('Monto (S/)')
                    ->money('PEN', true)
                    ->sortable(),

                // Estado de pago
                Tables\Columns\BadgeColumn::make('estado_pago')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'pagado',
                    ])
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->sortable(),

                // Comprobante (si existe archivo)
                Tables\Columns\IconColumn::make('comprobante_path')
                    ->label('Comprobante')
                    ->icon(fn ($state) => $state ? 'heroicon-o-paper-clip' : 'heroicon-o-x-circle')
                    ->tooltip(fn ($state) => $state ? 'Ver comprobante' : 'Sin archivo')
                    ->url(fn ($record) => $record->comprobante_path ? asset('storage/'.$record->comprobante_path) : null)
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('fecha_emision', 'desc')
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre'),
                Tables\Filters\SelectFilter::make('estado_pago')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado'    => 'Pagado',
                    ]),
                Tables\Filters\Filter::make('rango_fechas')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Desde'),
                        Forms\Components\DatePicker::make('to')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('fecha_emision', '>=', $d))
                            ->when($data['to'] ?? null, fn ($q, $h) => $q->whereDate('fecha_emision', '<=', $h));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
