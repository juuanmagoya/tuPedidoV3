<?php

namespace App\DTOs\Production;

class ProductionInputDTO
{
    public function __construct(
        public readonly int $input_id,      // ID del input
        public readonly float $quantity,    // Cantidad utilizada
        public readonly string $unit,        // Unidad
        public readonly float $cost_price,   // Precio costo unitario
    ) {}

    /**
     * Crear DTO desde request validado
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            input_id: (int) $data['input_id'],
            quantity: (float) $data['quantity'],
            unit: $data['unit'],
            cost_price: (float) $data['cost_price'],
        );
    }

    /**
     * Convertir DTO a array para persistencia
     */
    public function toArray(): array
    {
        return [
            'input_id'   => $this->input_id,
            'quantity'   => $this->quantity,
            'unit'       => $this->unit,
            'cost_price' => $this->cost_price,
        ];
    }
}
