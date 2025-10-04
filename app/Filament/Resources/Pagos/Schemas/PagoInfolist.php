<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PagoInfolist
{
    public static function configure(Schema $schema): Schema
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
}
