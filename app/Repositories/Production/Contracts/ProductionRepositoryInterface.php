<?php

namespace App\Repositories\Production\Contracts;

use App\Models\Production;

interface ProductionRepositoryInterface
{
    /**
     * Crear una producción (cabecera)
     */
    public function create(array $data): Production;

    /**
     * Buscar una producción por ID
     */
    public function findById(int $id): ?Production;

    /**
     * Actualizar una producción
     */
    public function update(Production $production, array $data): Production;

    /**
     * Obtener todas las producciones
     */
    public function all();
}
