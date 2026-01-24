<?php

namespace App\Services\Supplier;

use App\DTOs\Supplier\SupplierDTO;
use App\Repositories\Supplier\SupplierRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Support\Collection;

class SupplierService
{
    public function __construct(
        private readonly SupplierRepositoryInterface $supplierRepository
    ) {}

    /**
     * Obtener todos los proveedores
     */
    public function getAll(): Collection
    {
        return $this->supplierRepository->all();
    }

    /**
     * Obtener proveedor por ID
     */
    public function getById(int $id): ?Supplier
    {
        return $this->supplierRepository->findById($id);
    }

    /**
     * Crear proveedor
     */
    public function create(SupplierDTO $dto): Supplier
    {
        // Regla de negocio simple (ejemplo)
        if (empty($dto->name)) {
            throw new \Exception('Nombre del proveedor es obligatorio');
        }

        return $this->supplierRepository->create($dto->toArray());
    }

    /**
     * Actualizar proveedor
     */
    public function update(int $id, SupplierDTO $dto): Supplier
    {
        return $this->supplierRepository->update($id, $dto->toArray());
    }

    /**
     * Desactivar proveedor
     */
    public function deactivate(int $id): void
    {
        $this->supplierRepository->delete($id);
    }
}
