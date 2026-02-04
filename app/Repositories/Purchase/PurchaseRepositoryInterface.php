<?php

namespace App\Repositories\Purchase;

use App\Models\Purchase;
use App\DTOs\Purchase\PurchaseDTO;

interface PurchaseRepositoryInterface
{
    public function create(PurchaseDTO $dto): Purchase;

    public function update(Purchase $purchase, PurchaseDTO $dto): Purchase;

    public function findById(int $id): ?Purchase;

    public function save(Purchase $purchase): Purchase;
}
