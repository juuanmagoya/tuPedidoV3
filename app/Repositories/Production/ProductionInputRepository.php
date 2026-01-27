<?php

namespace App\Repositories\Production;

use App\Models\ProductionInput;
use App\Repositories\Production\Contracts\ProductionInputRepositoryInterface;

class ProductionInputRepository implements ProductionInputRepositoryInterface
{
    public function create(array $data): ProductionInput
    {
        return ProductionInput::create($data);
    }

    public function createMany(int $productionId, array $inputs): void
    {
        $rows = [];

        foreach ($inputs as $input) {
            $rows[] = [
                'production_id' => $productionId,
                'input_id'      => $input['input_id'],
                'quantity'      => $input['quantity'],
                'unit'          => $input['unit'],
                'cost_price'    => $input['cost_price'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        ProductionInput::insert($rows);
    }

    public function findByProduction(int $productionId): array
    {
        return ProductionInput::where('production_id', $productionId)
            ->get()
            ->toArray();
    }
}
