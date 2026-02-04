<?php

namespace App\DTOs\Purchase;

class PurchaseDTO
{
    /**
     * @param PurchaseItemDTO[] $items
     */
    public function __construct(
        public readonly int $supplier_id,
        public readonly string $purchase_date,
        public readonly array $items,
        public readonly ?string $notes = null,
    ) {}

    /**
     * Crear DTO desde array (FormRequest)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            supplier_id: $data['supplier_id'],
            purchase_date: $data['purchase_date'],
            items: array_map(
                fn (array $item) => PurchaseItemDTO::fromArray($item),
                $data['items']
            ),
            notes: $data['notes'] ?? null
        );
    }

    /**
     * Calcular subtotal de la compra
     */
    public function subtotal(): float
    {
        return array_reduce(
            $this->items,
            fn ($carry, PurchaseItemDTO $item) => $carry + $item->total(),
            0
        );
    }
}
