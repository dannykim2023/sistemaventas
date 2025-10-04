<?php

namespace App\Filament\Resources\Clientes\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagosRelationManager extends RelationManager
{
    protected static string $relationship = 'pagos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('venta_id')
                    ->label('Venta')
                    ->options(function (RelationManager $livewire) {
                        // Solo ventas del cliente actual y que estén "en curso"
                        return \App\Models\Venta::where('cliente_id', $livewire->ownerRecord->id)
                            ->where('estado', 'en curso')
                            ->get()
                            ->mapWithKeys(function ($venta) {
                                return [$venta->id => "ID {$venta->id} | S/{$venta->total}"];
                            });
                    })
                  
                    ->required(),
                    
                TextInput::make('monto')
                    ->required()
                    ->numeric(),
                Select::make('tipo_pago')
                    ->options([
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'yape' => 'Yape',
            'tarjeta' => 'Tarjeta',
        ])
                    ->required(),
                DatePicker::make('fecha_pago')
                    ->required(),
                Textarea::make('nota')
                    ->columnSpanFull(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('venta.id')
                    ->label('Venta'),
                TextEntry::make('monto')
                    ->numeric(),
                TextEntry::make('tipo_pago')
                    ->badge(),
                TextEntry::make('fecha_pago')
                    ->date(),
                TextEntry::make('nota')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('venta.id')
                    ->searchable(),
                TextColumn::make('venta.total')
                    ->label('Total Venta')
                    ->money('PEN'),
                TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipo_pago')
                    ->badge(),
                TextColumn::make('fecha_pago')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
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
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
