<?php

namespace App\Filament\Resources\Cotizacions\Widgets;

use App\Models\Cotizacion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class CotizacionesStats extends BaseWidget
{
    protected function getStats(): array
    {
        $mes = Carbon::now()->month;

        $enviadas = Cotizacion::where('estado', 'enviada')
            ->whereMonth('created_at', $mes)
            ->count();

        $aceptadas = Cotizacion::where('estado', 'aceptada')
            ->whereMonth('created_at', $mes)
            ->count();

        $rechazadas = Cotizacion::where('estado', 'rechazada')
            ->whereMonth('created_at', $mes)
            ->count();

        return [
            Stat::make('Enviadas', $enviadas)
                ->description('Cotizaciones enviadas este mes')
                ->chart([0, $enviadas]) // mini línea decorativa
                ->color('primary'),

            Stat::make('Aceptadas', $aceptadas)
                ->description('Cotizaciones aceptadas este mes')
                ->chart([0, $aceptadas])
                ->color('success'),

            Stat::make('Rechazadas', $rechazadas)
                ->description('Cotizaciones rechazadas este mes')
                ->chart([0, $rechazadas])
                ->color('danger'),
        ];
    }
}
