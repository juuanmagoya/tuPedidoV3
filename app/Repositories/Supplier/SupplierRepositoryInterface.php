<?php

namespace App\Repositories\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Collection;

interface SupplierRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Supplier;

    public function create(array $data): Supplier;

    public function update(int $id, array $data): Supplier;

    public function delete(int $id): void;
}
