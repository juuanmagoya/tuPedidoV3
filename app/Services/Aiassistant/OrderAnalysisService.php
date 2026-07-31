<?php

namespace App\Services\AiAssistant;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Services\AiAssistant\Contracts\AnalysisServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderAnalysisService implements AnalysisServiceInterface
{
    /**
     * Nombre del módulo.
     */
    public function getModuleName(): string
    {
        return 'orders';
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
     * Resumen general.
     */
    public function getSummary(): array
    {
        return [
            'today' => $this->getTodayOrdersSummary(),

            'week' => $this->getWeeklyOrdersSummary(),

            'month' => $this->getMonthlyOrdersSummary(),
        ];
    }

    /**
     * Estadísticas.
     */
    public function getStatistics(): array
    {
        return [
            'today_revenue' => $this->getTodayRevenue(),

            'monthly_revenue' => $this->getMonthlyRevenue(),

            'average_order' => $this->getAverageOrder(),

            'orders_by_status' => $this->getOrdersByStatus(),
        ];
    }

    /**
     * Alertas.
     */
    public function getAlerts(): array
    {
        return [
            'pending_orders' => $this->getPendingOrdersSummary(),
        ];
    }

    /**
     * Recomendaciones.
     */
    public function getRecommendations(): array
    {
        return [
            'best_selling_products' => $this->getBestSellingProducts(),

            'worst_selling_products' => $this->getWorstSellingProducts(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Motor del servicio
    |--------------------------------------------------------------------------
    */

    private function getTodayOrdersSummary(): array
    {
        $todayOrders = Order::whereDate('created_at', today());

        return [
            'total_orders' => $todayOrders->count(),
            'total_revenue' => (float) $todayOrders->sum('total'),
            'average_order' => round((float) $todayOrders->avg('total'), 2),
        ];
    }

    private function getWeeklyOrdersSummary(): array
    {
        $orders = Order::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => (float) $orders->sum('total'),
            'average_order' => round((float) $orders->avg('total'), 2),
        ];
    }

    private function getMonthlyOrdersSummary(): array
    {
        $orders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => (float) $orders->sum('total'),
            'average_order' => round((float) $orders->avg('total'), 2),
        ];
    }

    private function getPendingOrdersSummary(): array
    {
        $orders = Order::whereIn('status', [
            'received',
            'preparing',
            'on_the_way',
        ]);

        return [
            'total_pending' => $orders->count(),
            'orders' => $orders->latest()->get(),
        ];
    }

    private function getOrdersByStatus(): array
    {
        return [
            'received' => Order::where('status', 'received')->count(),

            'preparing' => Order::where('status', 'preparing')->count(),

            'on_the_way' => Order::where('status', 'on_the_way')->count(),

            'delivered' => Order::where('status', 'delivered')->count(),

            'canceled' => Order::where('status', 'canceled')->count(),
        ];
    }

    private function getTodayRevenue(): float
    {
        return (float) Order::whereDate('created_at', today())
            ->sum('total');
    }

    private function getMonthlyRevenue(): float
    {
        return (float) Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
    }

    private function getAverageOrder(): float
    {
        return round(
            (float) Order::avg('total'),
            2
        );
    }

    private function getBestSellingProducts(int $limit = 5): Collection
    {
        return OrderProduct::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }

    private function getWorstSellingProducts(int $limit = 5): Collection
    {
        return OrderProduct::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity')
            ->limit($limit)
            ->get();
    }
}