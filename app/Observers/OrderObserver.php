<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Sale;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if status changed AND is now delivered
        if ($order->wasChanged('status') && $order->status === 'delivered') {

            // Prevent duplicate sale creation
            if (!$order->sale) {

                Sale::create([
                    'order_id'      => $order->id,
                    'total'         => $order->total,
                    'payment_method'=> $order->payment_method,
                    'sold_at'       => now(),
                ]);
            }
        }
    }
}
