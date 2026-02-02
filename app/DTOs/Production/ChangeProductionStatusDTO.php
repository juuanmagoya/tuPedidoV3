<?php

namespace App\DTOs\Production;

class ChangeProductionStatusDTO
{
    public function __construct(
        public readonly int $productionId,
        public readonly string $newStatus,
    ) {}
}
