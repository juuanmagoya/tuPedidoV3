<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function updating(Product $product): void
    {
        // Si el stock cambió y ahora es 0
        if (
            $product->isDirty('stock') &&
            $product->stock == 0 &&
            in_array($product->status, [
                Product::STATUS_ACTIVE,
                Product::STATUS_PROMOTION,
                Product::STATUS_FEATURED
            ])
        ) {
            $product->status = Product::STATUS_OUT_OF_STOCK;
        }
    }
}