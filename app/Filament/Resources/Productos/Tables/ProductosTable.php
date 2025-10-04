<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')->label('Título')->searchable()->sortable(),
                TextColumn::make('tipo')->label('Tipo')->sortable(),
                TextColumn::make('precio_costo')->label('Costo')->money('PEN', true)->sortable(),
                TextColumn::make('precio_venta')->label('Venta')->money('PEN', true)->sortable(),
                TextColumn::make('margen')->label('Margen')->money('PEN', true)->sortable(),
                BadgeColumn::make('estado')->label('Estado')->colors([
                    'success' => 'activo',
                    'danger' => 'inactivo',
                ]),
            ])
            ->filters([
                SelectFilter::make('tipo')->options([
                    'producto' => 'Producto',
                    'servicio' => 'Servicio',
                ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
