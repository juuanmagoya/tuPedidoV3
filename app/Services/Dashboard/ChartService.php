<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use Carbon\Carbon;

class ChartService
{
    public function main(): array
    {
        $days = collect(range(6, 0))->map(function ($i) {
            $date = now()->subDays($i)->toDateString();

            return [
                'date' => $date,
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        });

        return [
            'orders_last_7_days' => $days
        ];
    }
}
