<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.nombre')
                    ->numeric()
                    ->sortable(),
              
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado')
                    ->badge(),
                TextColumn::make('total')
                    ->label('Total de venta')
                    ->money('PEN', true)
                    
                    ->sortable(),
                TextColumn::make('abonado')
                    ->label('Saldo abonado')
                    ->money('PEN', true)
                   
                    ->sortable(),
                TextColumn::make('saldo')
                    ->money('PEN', true)
                    ->label('Saldo pendiente')
                
                    ->sortable(),
                TextColumn::make('fecha_siguiente_pago')
                    ->label('Siguiente pago')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Venta creada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
