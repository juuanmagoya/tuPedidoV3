<?php

namespace App\DTOs\Order;

class OrderProductDTO
{
    public function __construct(
        public readonly int $productId,     // ID del producto
        public readonly int $quantity,      // Cantidad pedida
        public readonly float $unitPrice,   // Precio unitario
        public readonly float $subtotal     // Subtotal calculado
    ) {}

    /**
     * Crear DTO desde un array (request validado)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int) $data['product_id'],
            quantity: (int) $data['quantity'],
            unitPrice: (float) $data['unit_price'],
            subtotal: (float) ($data['quantity'] * $data['unit_price'])
        );
    }

    /**
     * Convertir DTO a array (para persistencia)
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'subtotal' => $this->subtotal,
        ];
    }
}
