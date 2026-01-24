<?php

namespace App\Repositories\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Collection;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all(): Collection
    {
        return Supplier::orderBy('name')->get();
    }

    public function findById(int $id): ?Supplier
    {
        return Supplier::find($id);
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(int $id, array $data): Supplier
    {
        $supplier = $this->findById($id);

        if (!$supplier) {
            throw new \Exception('Supplier not found');
        }

        $supplier->update($data);

        return $supplier;
    }

    public function delete(int $id): void
    {
        $supplier = $this->findById($id);

        if (!$supplier) {
            throw new \Exception('Supplier not found');
        }

        // Soft delete lógico
        $supplier->update(['is_active' => false]);
    }
}
