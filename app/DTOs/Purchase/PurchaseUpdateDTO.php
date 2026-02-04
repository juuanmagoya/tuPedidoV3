<?php

namespace App\DTOs\Purchase;

use Illuminate\Http\Request;

class PurchaseUpdateDTO
{
    public function __construct(
        public readonly int $supplier_id,
        public readonly string $purchase_date,
        public readonly ?string $notes,
        /** @var PurchaseItemDTO[] */
        public readonly array $items,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            supplier_id: $request->supplier_id,
            purchase_date: $request->purchase_date,
            notes: $request->notes,
            items: array_map(
                fn ($item) => PurchaseItemDTO::fromArray($item),
                $request->items
            )
        );
    }
}
