<?php

namespace App\Services\AiAssistant;

use App\Models\Input;
use App\Services\AiAssistant\Contracts\AnalysisServiceInterface;
use Illuminate\Support\Collection;

class StockAnalysisService implements AnalysisServiceInterface
{
    /**
     * Nombre del módulo.
     */
    public function getModuleName(): string
    {
        return 'stock';
    }

    /**
     * Devuelve el análisis completo del módulo.
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
     * Resumen general del inventario.
     */
    public function getSummary(): array
    {
        return [
            'total_inputs' => Input::where('is_active', true)->count(),
            'critical_inputs' => $this->getCriticalStock()->count(),
            'without_stock' => $this->getInputsWithoutStock()->count(),
            'inventory_health' => $this->getStockHealthPercentage(),
        ];
    }

    /**
     * Estadísticas del inventario.
     */
    public function getStatistics(): array
    {
        $inputs = Input::where('is_active', true)->get();

        return [
            'inventory_value' => $this->calculateInventoryValue(),
            'estimated_restock_cost' => $this->estimateRestockCost(),

            'average_stock' => round($inputs->avg('stock'), 2),

            'average_cost' => round($inputs->avg('cost_price'), 2),

            'highest_stock' => $inputs->max('stock'),

            'lowest_stock' => $inputs->min('stock'),
        ];
    }

    /**
     * Alertas importantes.
     */
    public function getAlerts(): array
    {
        return [
            'critical_inputs' => $this->getCriticalStock(),

            'without_stock' => $this->getInputsWithoutStock(),

            'near_minimum' => $this->getInputsNearMinimum(),
        ];
    }

    /**
     * Recomendaciones del sistema.
     */
    public function getRecommendations(): array
    {
        return [
            'restock' => $this->getRestockSuggestions(),

            'top_critical_inputs' => $this->getTopCriticalInputs(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos privados (Motor del servicio)
    |--------------------------------------------------------------------------
    */

    private function getCriticalStock(): Collection
    {
        return Input::query()
            ->where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->get([
                'id',
                'name',
                'unit',
                'stock',
                'min_stock',
                'cost_price',
                'supplier_id',
            ]);
    }

    private function getInputsWithoutStock(): Collection
    {
        return Input::query()
            ->where('is_active', true)
            ->where('stock', '<=', 0)
            ->orderBy('name')
            ->get();
    }

    private function getInputsNearMinimum(float $margin = 0.10): Collection
    {
        return Input::query()
            ->where('is_active', true)
            ->get()
            ->filter(function ($input) use ($margin) {

                if ($input->stock <= $input->min_stock) {
                    return false;
                }

                $limit = $input->min_stock + ($input->min_stock * $margin);

                return $input->stock <= $limit;
            })
            ->values();
    }

    private function calculateInventoryValue(): float
    {
        return (float) Input::where('is_active', true)
            ->get()
            ->sum(function ($input) {

                return $input->stock * ($input->cost_price ?? 0);

            });
    }

    private function getRestockSuggestions(): Collection
    {
        return $this->getCriticalStock()
            ->map(function ($input) {

                $targetStock = $input->min_stock * 2;

                $quantityToBuy = max(
                    0,
                    $targetStock - $input->stock
                );

                return [

                    'id' => $input->id,

                    'name' => $input->name,

                    'unit' => $input->unit,

                    'current_stock' => $input->stock,

                    'minimum_stock' => $input->min_stock,

                    'target_stock' => $targetStock,

                    'quantity_to_buy' => $quantityToBuy,

                    'estimated_cost' => round(
                        $quantityToBuy * ($input->cost_price ?? 0),
                        2
                    ),
                ];
            });
    }

    private function estimateRestockCost(): float
    {
        return (float) $this->getRestockSuggestions()
            ->sum('estimated_cost');
    }

    private function getTopCriticalInputs(int $limit = 5): Collection
    {
        return $this->getCriticalStock()
            ->sortBy(function ($input) {

                if ($input->min_stock == 0) {
                    return 1;
                }

                return $input->stock / $input->min_stock;
            })
            ->take($limit)
            ->values();
    }

    private function getStockHealthPercentage(): float
    {
        $total = Input::where('is_active', true)->count();

        if ($total === 0) {
            return 100;
        }

        $critical = $this->getCriticalStock()->count();

        return round((($total - $critical) / $total) * 100, 2);
    }
}