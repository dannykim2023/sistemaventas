<?php

namespace App\Filament\Resources\Proveedors\Tables;

use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ProveedorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'info' => 'empresa',
                        'warning' => 'empleado',
                        'success' => 'freelance',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('etiqueta')
                    ->label('Nombre / Razón Social')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('documento_tipo')
                    ->label('Doc. Tipo')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('documento_numero')
                    ->label('N° Documento')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pago_preferido')
                    ->label('Pago preferido')
                    ->badge()
                    ->colors([
                        'success' => 'efectivo',
                        'info' => 'transferencia',
                        'warning' => 'yape',
                        'danger' => 'plin',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('cuenta')
                    ->label('Cuenta / CCI / Yape')
                    ->wrap()
                    ->limit(20)
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'empresa' => 'Empresa',
                        'empleado' => 'Empleado',
                        'freelance' => 'Freelance',
                    ]),
                Tables\Filters\SelectFilter::make('pago_preferido')
                    ->label('Método de pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'yape' => 'Yape',
                        'plin' => 'Plin',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
