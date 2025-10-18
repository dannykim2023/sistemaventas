<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Venta;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VentasOverview extends BaseWidget
{
    protected array | int | null $columns = 4;
    
    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $currentMonth = Carbon::now()->month;
        
        // 1. Clientes Nuevos 
        $clientesNuevos = Cliente::where('created_at', '>=', $startOfMonth)->count();

        // 2. Ventas Realizadas (Este mes)
        $ventasRealizadas = Venta::whereMonth('created_at', $currentMonth)->count();
        
        // 3. Monto Cotizaciones Enviadas (Total)
        // CORREGIDO: Usamos 'total_con_igv' que es la columna real en cotizaciones 
        $montoCotizaciones = Cotizacion::where('estado', '=', 'enviada')->sum('total_con_igv'); 

        // 4. Saldo por Cobrar
        // CORREGIDO: Usamos 'total' que es la columna real en ventas 
        $saldoPorCobrar = Venta::where('estado', '=', 'en curso')->sum('total');
        
        return [
            Stat::make('Clientes Nuevos', $clientesNuevos)
                ->description('Registrados este mes')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'), 

            Stat::make('Ventas Realizadas', $ventasRealizadas)
                ->description('Transacciones completadas este mes')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Monto Cotizaciones Enviadas', 'S/ ' . number_format($montoCotizaciones, 2))
                ->description('Total en cotizaciones pendientes')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Saldo Por Cobrar', 'S/ ' . number_format($saldoPorCobrar, 2))
                ->description('Cuentas pendientes de pago')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}