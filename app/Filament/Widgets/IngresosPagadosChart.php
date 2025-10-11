<?php

namespace App\Filament\Widgets;

use App\Models\Pago; 
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IngresosPagadosChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos Pagados'; 
    protected int | string | array $columnSpan = 'full'; 
    protected ?string $maxHeight = '300px'; 

    // 🔴 CORRECCIÓN CLAVE: El valor por defecto debe ser uno de los que genera getFilters().
    // 'Y-m' (ej: '2025-10') asegura que el filtro de inicio coincida con el mes actual.
    public ?string $filter; 
    
    // Al iniciar el widget, establecemos el mes y año actual.
    public function mount(): void
    {
        $this->filter = Carbon::now()->format('Y-m');
    }

    /**
     * Define los filtros disponibles: Meses del año actual.
     */
    protected function getFilters(): ?array
    {
        $filters = [];
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Bucle para generar los filtros desde Enero hasta el mes actual
        for ($i = 1; $i <= $currentMonth; $i++) {
            $monthDate = Carbon::createFromDate($currentYear, $i, 1);
            
            // La clave es el formato 'YYYY-MM' (ej: '2025-10')
            $key = $monthDate->format('Y-m');
            
            // El valor visible es el nombre del mes
            $label = $monthDate->translatedFormat('F Y');

            $filters[$key] = $label;
        }

        // Invertimos el orden para que los meses más recientes aparezcan primero
        return array_reverse($filters, true);
    }
    
    protected function getData(): array
    {
        // ... (El resto de la lógica de fechas y consulta NO necesita cambios) ...

        // 1. Lógica para determinar el rango de fechas basado en el filtro
        // El mount() asegura que $this->filter siempre tendrá un valor 'YYYY-MM' al inicio.
        if ($this->filter && preg_match('/^(\d{4})-(\d{2})$/', $this->filter, $matches)) {
            $year = (int) $matches[1];
            $month = (int) $matches[2];
            
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            // Actualizar el título con el mes seleccionado
            $this->heading = 'Ingresos Pagados en ' . $startDate->translatedFormat('F Y');

        } else {
            // Este bloque solo se ejecutaría si el filtro falla, pero ya no debería.
            $now = Carbon::now();
            $startDate = $now->startOfMonth();
            $endDate = $now->endOfMonth();
            $this->heading = 'Ingresos Pagados Este Mes';
        }

        // 2. Consulta y tendencia (la misma lógica que ya teníamos)
        $pagosQuery = Pago::query();
        
        $data = Trend::query($pagosQuery) 
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->sum('monto'); 

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
            // 3. Etiquetas para el Eje X (días del mes/período)
            'labels' => $data->map(
                fn (TrendValue $value) => Carbon::parse($value->date)->format('d M')
            ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}