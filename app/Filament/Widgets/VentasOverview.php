<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Venta;
use App\Models\Pago; 
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
        
        // 3. total pagos (Total)
        $totalPagos = Pago::whereMonth('fecha_pago', $currentMonth)->sum('monto');

        // 4. Saldo por Cobrar
        // CORREGIDO: Usamos 'total' que es la columna real en ventas 
        $saldoPorCobrar = Venta::where('estado', '=', 'en curso')->sum('saldo');
        
        return [
            Stat::make('Clientes Nuevos', $clientesNuevos)
                ->description('Registrados este mes')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'), 

            Stat::make('Ventas Realizadas', $ventasRealizadas)
                ->description('Transacciones completadas este mes')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Monto Pagos recibidos', 'S/ ' . number_format($totalPagos, 2))
                ->description('Total pagos recibidos este mes')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Saldo Por Cobrar', 'S/ ' . number_format($saldoPorCobrar, 2))
                ->description('Cuentas pendientes de pago')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}