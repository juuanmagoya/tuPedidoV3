<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Category;
use App\Models\Input;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class DashboardController extends Controller
{
    
   public function index(Request $request)
{
    [$from, $to] = $this->resolveDates($request);

    $dashboard = [
        'kpis' => $this->kpis($from, $to),
        'quick' => $this->quickMetrics(),
        'recent_orders' => $this->recentOrders(),
        'payment_methods' => $this->paymentMethodChart($from, $to),
        'top_products_chart' => $this->topProductsChart($from, $to),
    ];

    $kpis = $dashboard['kpis'];
    $quick = $dashboard['quick'];
    $recent_orders = $dashboard['recent_orders'];
    $payment_methods = $dashboard['payment_methods'];
    $top_products_chart = $dashboard['top_products_chart'];
    

    return view('dashboard', compact(
        'dashboard', 'kpis', 'quick', 'recent_orders', 'payment_methods', 'top_products_chart'
    ));
}



    private function resolveDates(Request $request): array
    {
        $now = Carbon::now();

        if ($request->filled('period')) {
            return match ($request->period) {
                'today' => [$now->startOfDay(), $now->endOfDay()],
                '7'     => [$now->subDays(7)->startOfDay(), Carbon::now()],
                '30'    => [$now->subDays(30)->startOfDay(), Carbon::now()],
                'month' => [$now->startOfMonth(), $now->endOfMonth()],
                'prev'  => [
                    $now->subMonth()->startOfMonth(),
                    $now->endOfMonth()
                ],
                default => [null, null],
            };
        }

        if ($request->filled(['from', 'to'])) {
            return [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ];
        }

        // Por defecto: todo
        return [null, null];
    }



private function kpis($from = null, $to = null): array
{
    // Si no se pasa rango, no filtrar (tomar todos los pedidos)
    $ordersQuery = Order::query();
    if ($from && $to) {
        // Convertimos a Carbon para mayor seguridad
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        $ordersQuery->whereBetween('created_at', [$fromDate, $toDate]);
    }

    // Total ventas en el rango
    $salesTotal = (clone $ordersQuery)->sum('total');

    // Productos vendidos en el rango
    $productsSold = OrderProduct::whereHas('order', function ($q) use ($from, $to) {
        if ($from && $to) {
            $q->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }
    })->sum('quantity');

    // Productos comprados en el rango
    $productsBought = PurchaseItem::whereHas('purchase', function ($q) use ($from, $to) {
        if ($from && $to) {
            $q->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }
    })->sum('quantity');

    // Total compras en el rango
    $purchasesTotal = Purchase::when($from && $to, function($q) use ($from, $to) {
        $q->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
    })->sum('total');

    // Ganancia y porcentaje
    $profit = $salesTotal - $purchasesTotal;
    $profitPercent = $salesTotal > 0 ? round(($profit / $salesTotal) * 100, 2) : 0;

    // KPIs de estados de pedidos y conteos
    $orderStatusKpis = [
        'total_orders' => Order::count(),
        'orders_received' => Order::where('status', 'received')->count(),
        'orders_cancelled' => Order::where('status', 'canceled')->count(),
        'orders_preparing' => Order::where('status', 'preparing')->count(),
        'orders_on_the_way' => Order::where('status', 'on_the_way')->count(),
        'orders_delivered' => Order::where('status', 'delivered')->count(),
        'categories' => Category::count(),
        'products' => Product::count(),
        'completed_purchases' => Purchase::where('status', 'completed')->count(),
    ];

    return array_merge([
        'sales_total' => $salesTotal,
        'purchases_total' => $purchasesTotal,
        'profit' => $profit,
        'profit_percent' => $profitPercent,
        'products_sold' => $productsSold,
        'products_bought' => $productsBought,
        'payment_methods' => $this->paymentMethodChart($from, $to),
        'top_products_chart' => $this->topProductsChart($from, $to),
    ], $orderStatusKpis);
}

/**
 * Devuelve los datos para el gráfico de métodos de pago
 */
private function paymentMethodChart($from = null, $to = null): array
{
    $query = Order::selectRaw('payment_method, COUNT(*) as total');

    if ($from && $to) {
        $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
    }

    return $query->groupBy('payment_method')
                 ->pluck('total', 'payment_method')
                 ->toArray();
}

/**
 * Devuelve los datos para el gráfico de top productos vendidos
 */
private function topProductsChart($from = null, $to = null): array
{
    $query = OrderProduct::select('product_id')
        ->selectRaw('SUM(quantity) as total_sold')
        ->with('product:id,name');

    if ($from && $to) {
        $query->whereHas('order', function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        });
    }

    return $query->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->product->name ?? 'Desconocido' => $item->total_sold];
        })
        ->toArray();
}





private function quickMetrics(): array
{
    return [
        'Productos'   => Product::count(),
        'Categorías'  => Category::count(),
        'Insumos'     => Input::count(),
        'Pedidos'     => Order::count(),
        'Ventas/día'  => '$' . number_format(
            Order::whereDate('created_at', now())->sum('total'),
            0
        ),
    ];
}



private function recentOrders()
{
    return Order::whereDate('created_at', today())
        ->latest()
        ->limit(5)
        ->get();
}





}