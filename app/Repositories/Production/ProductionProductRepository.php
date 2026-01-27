<?php

namespace App\Repositories\Production;

use App\Models\ProductionProduct;
use App\Repositories\Production\Contracts\ProductionProductRepositoryInterface;

class ProductionProductRepository implements ProductionProductRepositoryInterface
{
    public function create(array $data): ProductionProduct
    {
        return ProductionProduct::create($data);
    }

    public function createMany(int $productionId, array $products): void
    {
        $rows = [];

        foreach ($products as $product) {
            $rows[] = [
                'production_id' => $productionId,
                'product_id'    => $product['product_id'],
                'quantity'      => $product['quantity'],
                'unit'          => $product['unit'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        ProductionProduct::insert($rows);
    }

    public function findByProduction(int $productionId): array
    {
        return ProductionProduct::where('production_id', $productionId)->get()->toArray();
    }
}
