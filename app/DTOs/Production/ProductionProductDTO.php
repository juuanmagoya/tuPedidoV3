<?php

namespace App\DTOs\Production;

class ProductionProductDTO
{
    public function __construct(
        public readonly int $product_id,   // Producto fabricado
        public readonly float $quantity,   // Cantidad producida
        public readonly string $unit,       // Unidad del producto
    ) {}

    /**
     * Crear DTO desde request validado
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            product_id: (int) $data['product_id'],
            quantity: (float) $data['quantity'],
            unit: $data['unit'],
        );
    }

    /**
     * Convertir DTO a array para persistencia
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity'   => $this->quantity,
            'unit'       => $this->unit,
        ];
    }
}
