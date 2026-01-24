<?php

namespace App\DTOs\Input;

class InputDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $unit,
        public readonly float $stock,
        public readonly ?float $min_stock,
        public readonly bool $is_active,
        public readonly ?string $notes,
    ) {}

    /**
     * Crear DTO desde request validado
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            unit: $data['unit'],
            stock: (float) $data['stock'],
            min_stock: isset($data['min_stock'])
                ? (float) $data['min_stock']
                : null,
            is_active: (bool) $data['is_active'],
            notes: $data['notes'] ?? null,
        );
    }

    /**
     * Convertir DTO a array para persistencia
     */
    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'unit'       => $this->unit,
            'stock'      => $this->stock,
            'min_stock'  => $this->min_stock,
            'is_active'  => $this->is_active,
            'notes'      => $this->notes,
        ];
    }
}
