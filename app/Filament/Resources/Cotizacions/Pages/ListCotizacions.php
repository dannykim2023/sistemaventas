<?php

namespace App\Filament\Resources\Cotizacions\Pages;

use App\Filament\Resources\Cotizacions\CotizacionResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Cotizacions\Widgets\CotizacionesStats;
use Filament\Actions;

class ListCotizacions extends ListRecords
{
    protected static string $resource = CotizacionResource::class;

    public ?int $filterMonth = null;
    public ?int $filterYear = null;

    protected function getHeaderWidgets(): array
    {
        return [
            CotizacionesStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Cotización')
                ->icon('heroicon-o-plus'),
        ];
    }
}
