<?php

namespace App\Filament\Resources\Clientes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\label\label;
use Filament\Actions\Action;


class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('ruc_dni')
                    ->label('RUC/DNI')
                    ->searchable(),
                TextColumn::make('telefono')
                    ->label('Telefono')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('tipo_cliente')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('fecha Registro')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
             ->recordActions([
                 Action::make('llamar')
                    ->label('Llamar')
                    ->icon('heroicon-o-phone')
                    ->color('primary')         // verde
                    ->button()                 // se ve como botón
                    ->url(fn ($record) => "tel:{$record->telefono}")
                    ->openUrlInNewTab(),

                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->color('primary')         // verde
                    ->button()
                    ->url(fn ($record) => "https://wa.me/51{$record->telefono}") // +51 Perú
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc'); // Mostrar últimos clientes primero
    }
}
