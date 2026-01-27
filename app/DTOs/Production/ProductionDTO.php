<?php

namespace App\DTOs\Production;

class ProductionDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $production_date,
        public readonly string $status,        // draft | confirmed | cancelled
        public readonly ?string $notes,
        public readonly int $created_by,

        /** @var ProductionInputDTO[] */
        public readonly array $inputs,

        /** @var ProductionProductDTO[] */
        public readonly array $products,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            code: $data['code'],
            production_date: $data['production_date'],
            status: $data['status'],
            notes: $data['notes'] ?? null,
            created_by: (int) $data['created_by'],
            inputs: array_map(
                fn ($input) => ProductionInputDTO::fromRequest($input),
                $data['inputs']
            ),
            products: array_map(
                fn ($product) => ProductionProductDTO::fromRequest($product),
                $data['products']
            ),
        );
    }

    /**
     * Datos para tabla productions
     */
    public function toArray(): array
    {
        return [
            'code'            => $this->code,
            'production_date' => $this->production_date,
            'status'          => $this->status,
            'notes'           => $this->notes,
            'created_by'      => $this->created_by,
        ];
    }
}
