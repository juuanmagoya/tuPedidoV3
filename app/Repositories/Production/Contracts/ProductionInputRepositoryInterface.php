<?php

namespace App\Repositories\Production\Contracts;

use App\Models\ProductionInput;

interface ProductionInputRepositoryInterface
{
    /**
     * Registrar un input consumido en una producción
     */
    public function create(array $data): ProductionInput;

    /**
     * Registrar múltiples inputs consumidos
     * (uso típico dentro de una transacción)
     */
    public function createMany(int $productionId, array $inputs): void;

    /**
     * Obtener los inputs de una producción
     */
    public function findByProduction(int $productionId): array;
}
