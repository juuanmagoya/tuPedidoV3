<?php

namespace App\Repositories\Production;

use App\Models\Production;
use Illuminate\Support\Collection;
use App\Repositories\Production\Contracts\ProductionRepositoryInterface;

class ProductionRepository implements ProductionRepositoryInterface
{
    public function getAll()
    {
        return Production::with('creator')
            ->orderByDesc('production_date')
            ->get();
    }

    public function findById(int $id): Production
    {
        return Production::with([
            'inputs.input',      // production_inputs → input
            'products.product',  // production_products → product
            'creator',
        ])->findOrFail($id);
    }
    public function create(array $data): Production
    {
        return Production::create($data);
    }

    public function update(Production $production, array $data): Production
    {
        $production->update($data);
        return $production;
    }

    public function updateStatus(
        Production $production,
        array $data
    ): Production {
        $production->update($data);
        return $production;
    }

}
