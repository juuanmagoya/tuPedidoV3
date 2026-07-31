<?php

namespace App\Services\AiAssistant;

use App\Models\Production;
use App\Models\ProductionInput;
use App\Models\ProductionProduct;
use App\Services\AiAssistant\Contracts\AnalysisServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductionSuggestionService implements AnalysisServiceInterface
{
    /**
     * Nombre del módulo.
     */
    public function getModuleName(): string
    {
        return 'production';
    }

    /**
     * Análisis completo del módulo.
     */
    public function analyze(): array
    {
        return [
            'module' => $this->getModuleName(),
            'summary' => $this->getSummary(),
            'statistics' => $this->getStatistics(),
            'alerts' => $this->getAlerts(),
            'recommendations' => $this->getRecommendations(),
        ];
    }

    /**
     * Resumen general de producción.
     */
    public function getSummary(): array
    {
        return [
            'today' => $this->getTodayProductionSummary(),
            'week' => $this->getWeeklyProductionSummary(),
            'month' => $this->getMonthlyProductionSummary(),
            'total_productions' => $this->getTotalProductions(),
            'total_products' => $this->getTotalProductsProduced(),
            'total_inputs_consumed' => $this->getTotalInputsConsumed(),
        ];
    }

    /**
     * Estadísticas de producción.
     */
    public function getStatistics(): array
    {
        return [
            'avg_daily_production' => $this->getAverageDailyProduction(),
            'avg_cost_per_production' => $this->getAverageCostPerProduction(),
            'most_produced_product' => $this->getMostProducedProduct(),
            'most_used_input' => $this->getMostUsedInput(),
            'total_production_cost' => $this->getTotalProductionCost(),
            'status_distribution' => $this->getStatusDistribution(),
            'efficiency_ratio' => $this->getEfficiencyRatio(),
        ];
    }

    /**
     * Alertas de producción.
     */
    public function getAlerts(): array
    {
        return [
            'productions_pending' => $this->getProductionsPending(),
            'high_cost_productions' => $this->getHighCostProductions(),
            'recent_cancellations' => $this->getRecentCancellations(),
            'productions_without_products' => $this->getProductionsWithoutProducts(),
        ];
    }

    /**
     * Recomendaciones.
     */
    public function getRecommendations(): array
    {
        return [
            'optimization_suggestions' => $this->getOptimizationSuggestions(),
            'cost_reduction_opportunities' => $this->getCostReductionOpportunities(),
            'products_to_increase' => $this->getProductsToIncrease(),
            'products_to_decrease' => $this->getProductsToDecrease(),
            'input_waste_alerts' => $this->getInputWasteAlerts(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos privados - Resúmenes
    |--------------------------------------------------------------------------
    */

    private function getTodayProductionSummary(): array
    {
        $productions = Production::whereDate('production_date', today())
            ->where('status', 'confirmed');

        return [
            'total_productions' => $productions->count(),
            'total_cost' => (float) $productions->sum('total_cost'),
            'avg_cost' => round((float) $productions->avg('total_cost'), 2),
            'total_products' => $this->getProductsCountForProductions($productions->pluck('id')),
            'total_inputs' => $this->getInputsCountForProductions($productions->pluck('id')),
        ];
    }

    private function getWeeklyProductionSummary(): array
    {
        $productions = Production::whereBetween('production_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->where('status', 'confirmed');

        return [
            'total_productions' => $productions->count(),
            'total_cost' => (float) $productions->sum('total_cost'),
            'avg_cost' => round((float) $productions->avg('total_cost'), 2),
            'total_products' => $this->getProductsCountForProductions($productions->pluck('id')),
            'total_inputs' => $this->getInputsCountForProductions($productions->pluck('id')),
        ];
    }

    private function getMonthlyProductionSummary(): array
    {
        $productions = Production::whereMonth('production_date', now()->month)
            ->whereYear('production_date', now()->year)
            ->where('status', 'confirmed');

        return [
            'total_productions' => $productions->count(),
            'total_cost' => (float) $productions->sum('total_cost'),
            'avg_cost' => round((float) $productions->avg('total_cost'), 2),
            'total_products' => $this->getProductsCountForProductions($productions->pluck('id')),
            'total_inputs' => $this->getInputsCountForProductions($productions->pluck('id')),
        ];
    }

    private function getTotalProductions(): int
    {
        return Production::where('status', 'confirmed')->count();
    }

    private function getTotalProductsProduced(): int
    {
        return ProductionProduct::query()
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->sum('quantity_produced');
    }

    private function getTotalInputsConsumed(): int
    {
        return ProductionInput::query()
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->sum('quantity_used');
    }

    private function getProductsCountForProductions(Collection $productionIds): int
    {
        if ($productionIds->isEmpty()) {
            return 0;
        }

        return ProductionProduct::whereIn('production_id', $productionIds)
            ->sum('quantity_produced');
    }

    private function getInputsCountForProductions(Collection $productionIds): int
    {
        if ($productionIds->isEmpty()) {
            return 0;
        }

        return ProductionInput::whereIn('production_id', $productionIds)
            ->sum('quantity_used');
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos privados - Estadísticas
    |--------------------------------------------------------------------------
    */

    private function getAverageDailyProduction(): float
    {
        $daysInMonth = Carbon::now()->daysInMonth;
        $monthlyTotal = $this->getTotalProductsProduced();

        if ($daysInMonth == 0) {
            return 0;
        }

        return round($monthlyTotal / $daysInMonth, 2);
    }

    private function getAverageCostPerProduction(): float
    {
        return round(
            (float) Production::where('status', 'confirmed')->avg('total_cost'),
            2
        );
    }

    private function getMostProducedProduct(): ?array
    {
        $product = ProductionProduct::query()
            ->select('product_id', DB::raw('SUM(quantity_produced) as total_quantity'))
            ->with('product')
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->first();

        if (!$product) {
            return null;
        }

        return [
            'id' => $product->product_id,
            'name' => $product->product->name ?? 'Desconocido',
            'total_quantity' => (int) $product->total_quantity,
            'percentage' => $this->calculateProductPercentage($product->total_quantity),
        ];
    }

    private function calculateProductPercentage(float $quantity): float
    {
        $total = $this->getTotalProductsProduced();
        
        if ($total == 0) {
            return 0;
        }

        return round(($quantity / $total) * 100, 2);
    }

    private function getMostUsedInput(): ?array
    {
        $input = ProductionInput::query()
            ->select('inputs_id', DB::raw('SUM(quantity_used) as total_quantity'))
            ->with('input')
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->groupBy('inputs_id')
            ->orderByDesc('total_quantity')
            ->first();

        if (!$input) {
            return null;
        }

        return [
            'id' => $input->inputs_id,
            'name' => $input->input->name ?? 'Desconocido',
            'total_quantity' => (float) $input->total_quantity,
            'unit' => $input->input->unit ?? 'unidad',
        ];
    }

    private function getTotalProductionCost(): float
    {
        return (float) Production::where('status', 'confirmed')->sum('total_cost');
    }

    private function getStatusDistribution(): array
    {
        $statuses = Production::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'draft' => (int) ($statuses['draft'] ?? 0),
            'confirmed' => (int) ($statuses['confirmed'] ?? 0),
            'cancelled' => (int) ($statuses['cancelled'] ?? 0),
        ];
    }

    private function getEfficiencyRatio(): float
    {
        // Relación entre productos producidos y insumos consumidos
        $totalProducts = $this->getTotalProductsProduced();
        $totalInputs = $this->getTotalInputsConsumed();

        if ($totalInputs == 0) {
            return 0;
        }

        return round($totalProducts / $totalInputs, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos privados - Alertas
    |--------------------------------------------------------------------------
    */

    private function getProductionsPending(): Collection
    {
        return Production::where('status', 'draft')
            ->whereDate('production_date', '<=', today())
            ->orderBy('production_date')
            ->get();
    }

    private function getHighCostProductions(float $threshold = null): Collection
    {
        if ($threshold === null) {
            $threshold = $this->getAverageCostPerProduction() * 1.5;
        }

        return Production::where('status', 'confirmed')
            ->where('total_cost', '>', $threshold)
            ->orderByDesc('total_cost')
            ->limit(5)
            ->get();
    }

    private function getRecentCancellations(int $days = 7): Collection
    {
        return Production::where('status', 'cancelled')
            ->where('production_date', '>=', Carbon::now()->subDays($days))
            ->orderByDesc('production_date')
            ->get();
    }

    private function getProductionsWithoutProducts(): Collection
    {
        return Production::where('status', 'confirmed')
            ->whereDoesntHave('products')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos privados - Recomendaciones
    |--------------------------------------------------------------------------
    */

    private function getOptimizationSuggestions(): array
    {
        $suggestions = [];
        $productions = Production::where('status', 'confirmed')
            ->whereMonth('production_date', now()->month)
            ->get();

        if ($productions->isEmpty()) {
            $suggestions[] = [
                'type' => 'no_data',
                'message' => 'No hay producciones registradas este mes. Comienza a registrar tus producciones para obtener recomendaciones.',
                'priority' => 'high',
            ];
            return $suggestions;
        }

        // Sugerencia 1: Costos elevados
        $avgCost = $productions->avg('total_cost');
        $highCostProductions = $productions->filter(function ($p) use ($avgCost) {
            return $p->total_cost > $avgCost * 1.5;
        });

        if ($highCostProductions->count() > 0) {
            $suggestions[] = [
                'type' => 'high_cost',
                'message' => "Hay {$highCostProductions->count()} producciones con costos superiores al promedio en un 50%. Revisa los insumos utilizados.",
                'priority' => 'high',
                'details' => $highCostProductions->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'code' => $p->code,
                        'cost' => $p->total_cost,
                    ];
                })->toArray(),
            ];
        }

        // Sugerencia 2: Producciones pendientes
        $pending = $this->getProductionsPending();
        if ($pending->count() > 0) {
            $suggestions[] = [
                'type' => 'pending_productions',
                'message' => "Tienes {$pending->count()} producciones pendientes de confirmar. Revisa y confirma las que ya estén completas.",
                'priority' => 'medium',
                'details' => $pending->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'code' => $p->code,
                        'date' => $p->production_date,
                    ];
                })->toArray(),
            ];
        }

        // Sugerencia 3: Eficiencia
        $efficiency = $this->getEfficiencyRatio();
        if ($efficiency < 1) {
            $suggestions[] = [
                'type' => 'low_efficiency',
                'message' => "La eficiencia de producción es baja ({$efficiency}). Se consume más insumo del que se produce. Revisa tus procesos.",
                'priority' => 'high',
            ];
        }

        return $suggestions;
    }

    private function getCostReductionOpportunities(): array
    {
        $opportunities = [];

        // Encontrar producciones con alto costo por producto
        $productions = Production::where('status', 'confirmed')
            ->whereHas('products')
            ->with('products')
            ->whereMonth('production_date', now()->month)
            ->get();

        foreach ($productions as $production) {
            $totalProducts = $production->products->sum('quantity_produced');
            if ($totalProducts > 0) {
                $costPerUnit = $production->total_cost / $totalProducts;
                
                if ($costPerUnit > 100) { // Umbral de costo alto por unidad
                    $opportunities[] = [
                        'production_id' => $production->id,
                        'code' => $production->code,
                        'cost_per_unit' => round($costPerUnit, 2),
                        'total_cost' => $production->total_cost,
                        'suggestion' => 'Considera optimizar la receta o buscar insumos más económicos.',
                    ];
                }
            }
        }

        return array_slice($opportunities, 0, 5);
    }

    private function getProductsToIncrease(): array
    {
        // Productos con mayor demanda y menor producción
        $products = ProductionProduct::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity_produced) as total_produced'),
                DB::raw('COUNT(DISTINCT production_id) as production_count')
            )
            ->with('product')
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed')
                    ->whereMonth('production_date', now()->month);
            })
            ->groupBy('product_id')
            ->having('production_count', '<', 3) // Poco frecuente
            ->orderBy('total_produced')
            ->limit(3)
            ->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->product_id,
                'name' => $product->product->name ?? 'Desconocido',
                'total_produced' => (int) $product->total_produced,
                'production_count' => (int) $product->production_count,
                'suggestion' => 'Considera aumentar la frecuencia de producción de este producto.',
            ];
        })->toArray();
    }

    private function getProductsToDecrease(): array
    {
        // Productos con alta producción pero baja rotación
        $products = ProductionProduct::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity_produced) as total_produced'),
                DB::raw('COUNT(DISTINCT production_id) as production_count')
            )
            ->with('product')
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed')
                    ->whereMonth('production_date', now()->month);
            })
            ->groupBy('product_id')
            ->having('production_count', '>', 5) // Muy frecuente
            ->orderByDesc('total_produced')
            ->limit(3)
            ->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->product_id,
                'name' => $product->product->name ?? 'Desconocido',
                'total_produced' => (int) $product->total_produced,
                'production_count' => (int) $product->production_count,
                'suggestion' => 'Considera reducir la producción o ajustar la cantidad para evitar sobreproducción.',
            ];
        })->toArray();
    }

    private function getInputWasteAlerts(): array
    {
        // Identificar insumos que se usan en grandes cantidades sin proporción con los productos
        $alerts = [];

        $inputs = ProductionInput::query()
            ->select(
                'inputs_id',
                DB::raw('SUM(quantity_used) as total_used')
            )
            ->with('input')
            ->whereHas('production', function ($query) {
                $query->where('status', 'confirmed')
                    ->whereMonth('production_date', now()->month);
            })
            ->groupBy('inputs_id')
            ->having('total_used', '>', 100) // Umbral de uso alto
            ->orderByDesc('total_used')
            ->limit(5)
            ->get();

        foreach ($inputs as $input) {
            $alerts[] = [
                'input_id' => $input->inputs_id,
                'name' => $input->input->name ?? 'Desconocido',
                'total_used' => (float) $input->total_used,
                'unit' => $input->input->unit ?? 'unidad',
                'suggestion' => 'Este insumo se está utilizando en grandes cantidades. Revisa si hay desperdicio o considera buscar alternativas.',
            ];
        }

        return $alerts;
    }
}