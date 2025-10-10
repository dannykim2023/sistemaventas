<?php

namespace App\Filament\Widgets;

use App\Models\Venta;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IngresosPagadosChart extends ChartWidget
{
    // Título (NO ESTÁTICO, para evitar el error 'Cannot redeclare...')
    protected ?string $heading = 'Ingresos Pagados Este Mes'; 
    
    // Ocupa el ancho completo
    protected int | string | array $columnSpan = 'full'; 
    protected ?string $maxHeight = '300px'; 

    protected function getData(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // 1. Crear la consulta de Eloquent FILTRADA por estado 'completada'
        $ventasPagadasQuery = Venta::query()->where('estado', 'completada');
        
        // 2. Usar Trend::query() para la tendencia
        $data = Trend::query($ventasPagadasQuery) 
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->perDay()
            ->sum('total'); // <-- ¡CORRECCIÓN FINAL: Cambiado a 'total'!

        // Color personalizado
        $primaryColor = '#4dfbdb'; 

        return [
            'datasets' => [
                [
                    'label' => 'Total Ingresos (S/)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => $primaryColor, 
                    'borderColor' => $primaryColor,
                    'tension' => 0.4, 
                    'fill' => true,
                ],
            ],
            // Etiquetas (eje X)
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Gráfico de línea
    }
}