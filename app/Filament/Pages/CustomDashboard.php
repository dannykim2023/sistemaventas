<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BasePage;
use App\Filament\Widgets\VentasOverview;
use App\Filament\Widgets\IngresosPagadosChart;
use App\Filament\Widgets\UltimosPagosTable;

class CustomDashboard extends BasePage
{
    protected static ?string $title = 'Inicio'; 

    // SOLUCIÓN: ELIMINA LA PROPIEDAD $view. 
    // Al extender BasePage, Filament usará automáticamente la vista de Dashboard.
    // protected string $view = 'filament.pages.custom-dashboard'; 

    /**
     * Define explícitamente el orden de los widgets en esta página.
     */
    public function getWidgets(): array
    {
        return [
            VentasOverview::class, 
            IngresosPagadosChart::class, 
            UltimosPagosTable::class, 
        ];
    }
}
