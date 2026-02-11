<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\Production;

class KpiService
{
    public function main(): array
    {
        return [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'productions_today' => Production::whereDate('created_at', today())->count(),
        ];
    }
}
