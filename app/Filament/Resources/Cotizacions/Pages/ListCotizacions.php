<?php

namespace App\Filament\Resources\Cotizacions\Pages;

use App\Filament\Resources\Cotizacions\CotizacionResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Cotizacions\Widgets\CotizacionesStats;

class ListCotizacions extends ListRecords
{
    protected static string $resource = CotizacionResource::class;

    // filtros compartidos (opcional, si los usas)
    public ?int $filterMonth = null;
    public ?int $filterYear = null;

    protected function getHeaderWidgets(): array
    {
        return [
            CotizacionesStats::class,
        ];
    }
}
