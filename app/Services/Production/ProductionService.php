<?php

namespace App\Services\Production;

use App\DTOs\Production\ProductionDTO;
use App\Models\Production;
use App\Repositories\Production\Contracts\ProductionRepositoryInterface;
use App\Repositories\Production\Contracts\ProductionInputRepositoryInterface;
use App\Repositories\Production\Contracts\ProductionProductRepositoryInterface;
use App\Services\Input\InputService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class ProductionService
{
    public function __construct(
        protected ProductionRepositoryInterface $productionRepository,
        protected ProductionInputRepositoryInterface $productionInputRepository,
        protected ProductionProductRepositoryInterface $productionProductRepository,
        protected InputService $inputService,
        protected ProductService $productService,
    ) {}

    /**
     * Obtener todas las producciones
     */
    public function getAll()
    {
        return $this->productionRepository->all();
    }

    /**
     * Obtener una producción por ID
     */
    public function getById(int $id): Production
    {
        $production = $this->productionRepository->findById($id);

        if (! $production) {
            throw new RuntimeException('Producción no encontrada');
        }

        return $production;
    }

    /**
     * Registrar una producción completa
     * - Crea la cabecera
     * - Registra insumos consumidos
     * - Descuenta stock de insumos
     * - Registra productos generados
     * - Aumenta stock de productos
     */
    public function create(array $data): Production
    {
        $dto = ProductionDTO::fromRequest($data);

        return DB::transaction(function () use ($dto) {

            /** 1️⃣ Crear producción (cabecera) */
            $production = $this->productionRepository->create(
                $dto->toArray()
            );

            /** 2️⃣ Registrar insumos consumidos */
            $this->productionInputRepository->createMany(
                $production->id,
                $dto->inputs
            );

            /** 3️⃣ Descontar stock de insumos */
            foreach ($dto->inputs as $inputDTO) {
                $this->inputService->decreaseStock(
                    $inputDTO->input_id,
                    $inputDTO->quantity
                );
            }

            /** 4️⃣ Registrar productos generados */
            $this->productionProductRepository->createMany(
                $production->id,
                $dto->products
            );

            /** 5️⃣ Aumentar stock de productos */
            foreach ($dto->products as $productDTO) {
                $this->productService->increaseStock(
                    $productDTO->product_id,
                    $productDTO->quantity
                );
            }

            return $production;
        });
    }

    /**
     * Insumos disponibles para producción
     * (delegado al módulo de insumos)
     */
    public function getAvailableInputs()
    {
        return $this->inputService->getAll();
    }

    /**
     * Productos disponibles para producir
     * (delegado al módulo de productos)
     */
    public function getAvailableProducts()
    {
        return $this->productService->getAll();
    }
}
