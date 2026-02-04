<?php

namespace App\DTOs\Purchase;

class PurchaseItemDTO
{
    public function __construct(
        public readonly int $input_id,
        public readonly float $quantity,
        public readonly float $unit_price,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            input_id: $data['input_id'],
            quantity: $data['quantity'],
            unit_price: $data['unit_price']
        );
    }

    public function total(): float
    {
        return $this->quantity * $this->unit_price;
    }
}
