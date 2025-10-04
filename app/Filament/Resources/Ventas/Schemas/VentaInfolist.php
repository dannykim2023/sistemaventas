<?php

namespace App\Filament\Resources\Ventas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VentaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cliente_id')
                    ->numeric(),
                TextEntry::make('cotizacion_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('fecha')
                    ->date(),
                TextEntry::make('estado')
                    ->badge(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('abonado')
                    ->numeric(),
                TextEntry::make('saldo')
                    ->numeric(),
                TextEntry::make('fecha_siguiente_pago')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
