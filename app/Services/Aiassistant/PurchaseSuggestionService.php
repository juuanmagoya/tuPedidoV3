<?php

namespace App\Services\AiAssistant;


use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Services\AiAssistant\Contracts\AnalysisServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PurchaseSuggestionService implements AnalysisServiceInterface
{
    public function __construct(
        private StockAnalysisService $stockAnalysisService
    ) {
    }

    /**
     * Nombre del módulo.
     */
    public function getModuleName(): string
    {
        return 'purchases';
    }

    /**
     * Análisis completo.
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
     * Resumen.
     */
    public function getSummary(): array
    {
        return [

            'pending_purchases' => Purchase::pending()->count(),

            'approved_purchases' => Purchase::approved()->count(),

            'monthly_purchase_cost' => $this->getMonthlyPurchaseCost(),
        ];
    }

    /**
     * Estadísticas.
     */
    public function getStatistics(): array
    {
        return [

            'average_purchase' => $this->getAveragePurchase(),

            'largest_purchase' => $this->getLargestPurchase(),

            'smallest_purchase' => $this->getSmallestPurchase(),

            'monthly_total' => $this->getMonthlyPurchaseCost(),
        ];
    }

    /**
     * Alertas.
     */
    public function getAlerts(): array
    {
        return [

            'pending_purchases' => $this->getPendingPurchases(),

            'critical_inputs_without_purchase' => $this->getInputsWithoutPendingPurchase(),
        ];
    }

    /**
     * Recomendaciones.
     */
    public function getRecommendations(): array
    {
        return [

            'purchase_draft' => $this->generatePurchaseDraft(),

            'estimated_purchase_cost' => $this->calculateEstimatedPurchaseCost(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Motor del servicio
    |--------------------------------------------------------------------------
    */

    private function getPendingPurchases(): Collection
    {
        return Purchase::query()
            ->with('supplier')
            ->pending()
            ->latest()
            ->get();
    }

    private function getMonthlyPurchaseCost(): float
    {
        return (float) Purchase::query()
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');
    }

    private function getAveragePurchase(): float
    {
        return round(
            (float) Purchase::avg('total'),
            2
        );
    }

    private function getLargestPurchase(): float
    {
        return (float) Purchase::max('total');
    }

    private function getSmallestPurchase(): float
    {
        return (float) Purchase::min('total');
    }

    /**
     * Insumos críticos que todavía NO tienen una compra pendiente.
     */
    private function getInputsWithoutPendingPurchase(): Collection
    {
        $criticalInputs = collect(
            $this->stockAnalysisService
                ->getAlerts()['critical_inputs']
        );

        $pendingInputIds = PurchaseItem::query()
            ->whereHas('purchase', function ($query) {

                $query->whereIn('status', [
                    'pending',
                    'approved',
                    'in_transit',
                ]);

            })
            ->pluck('input_id');

        return $criticalInputs
            ->whereNotIn('id', $pendingInputIds)
            ->values();
    }

    /**
     * Genera un borrador de compra.
     */
    private function generatePurchaseDraft(): Collection
    {
        return $this->getInputsWithoutPendingPurchase()
            ->map(function ($input) {

                $quantity = ($input->min_stock * 2) - $input->stock;

                return [

                    'input_id' => $input->id,

                    'input_name' => $input->name,

                    'current_stock' => $input->stock,

                    'minimum_stock' => $input->min_stock,

                    'suggested_quantity' => max(0, $quantity),

                    'estimated_cost' => round(
                        max(0, $quantity) * ($input->cost_price ?? 0),
                        2
                    ),
                ];
            });
    }

    /**
     * Costo estimado del borrador.
     */
    private function calculateEstimatedPurchaseCost(): float
    {
        return (float) $this->generatePurchaseDraft()
            ->sum('estimated_cost');
    }
}