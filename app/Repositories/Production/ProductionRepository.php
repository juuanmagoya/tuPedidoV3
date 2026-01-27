<?php

namespace App\Repositories\Production;

use App\Models\Production;
use Illuminate\Support\Collection;
use App\Repositories\Production\Contracts\ProductionRepositoryInterface;

class ProductionRepository implements ProductionRepositoryInterface
{
    public function all()//: Collection
    {
        return Production::orderBy('production_date', 'desc')->get();
    }

    public function findById(int $id): ?Production
    {
        return Production::find($id);
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
}
