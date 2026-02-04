<?php

namespace App\Repositories\Purchase;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\DTOs\Purchase\PurchaseDTO;
use Illuminate\Support\Facades\DB;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function create(PurchaseDTO $dto): Purchase
    {
        return DB::transaction(function () use ($dto) {

            $purchase = Purchase::create([
                'supplier_id'   => $dto->supplier_id,
                'purchase_date' => $dto->purchase_date,
                'status'        => 'pending',
                'subtotal'      => $dto->subtotal(),
                'total'         => $dto->subtotal(),
                'notes'         => $dto->notes,
            ]);

            foreach ($dto->items as $itemDTO) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'input_id'    => $itemDTO->input_id,
                    'quantity'    => $itemDTO->quantity,
                    'unit'        => $itemDTO->unit,
                    'unit_price'  => $itemDTO->unit_price,
                    'total_price' => $itemDTO->total(),
                ]);
            }

            return $purchase->load('items');
        });
    }

    public function update(Purchase $purchase, PurchaseDTO $dto): Purchase
    {
        return DB::transaction(function () use ($purchase, $dto) {

            $purchase->update([
                'supplier_id'   => $dto->supplier_id,
                'purchase_date' => $dto->purchase_date,
                'subtotal'      => $dto->subtotal(),
                'total'         => $dto->subtotal(),
                'notes'         => $dto->notes,
            ]);

            // Reemplazamos items
            $purchase->items()->delete();

            foreach ($dto->items as $itemDTO) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'input_id'    => $itemDTO->input_id,
                    'quantity'    => $itemDTO->quantity,
                    'unit'        => $itemDTO->unit,
                    'unit_price'  => $itemDTO->unit_price,
                    'total_price' => $itemDTO->total(),
                ]);
            }

            return $purchase->load('items');
        });
    }

    public function findById(int $id): ?Purchase
    {
        return Purchase::with('items')->find($id);
    }

    public function save(Purchase $purchase): Purchase
    {
        $purchase->save();

        return $purchase;
    }
}
