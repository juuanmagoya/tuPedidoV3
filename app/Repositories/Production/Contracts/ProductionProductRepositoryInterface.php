<?php

namespace App\Repositories\Production\Contracts;

use App\Models\ProductionProduct;

interface ProductionProductRepositoryInterface
{
    /**
     * Registrar un producto generado en una producción
     */
    public function create(array $data): ProductionProduct;

    /**
     * Registrar múltiples productos generados
     */
    public function createMany(int $productionId, array $products): void;

    /**
     * Obtener los productos generados por una producción
     */
    public function findByProduction(int $productionId): array;
}
