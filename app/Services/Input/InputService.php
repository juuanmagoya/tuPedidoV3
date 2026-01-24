<?php

namespace App\Services\Input;

use App\DTOs\Input\InputDTO;
use App\Models\Input;
use App\Repositories\Input\InputRepositoryInterface;
use Illuminate\Support\Collection;

class InputService
{
    public function __construct(
        private readonly InputRepositoryInterface $inputRepository
    ) {}

    /**
     * Obtener todos los insumos
     */
    public function getAll(): Collection
    {
        return $this->inputRepository->all();
    }

    /**
     * Obtener insumo por ID
     */
    public function getById(int $id): ?Input
    {
        return $this->inputRepository->findById($id);
    }

    /**
     * Crear insumo
     */
    public function create(InputDTO $dto): Input
    {
        // Regla mínima de negocio (opcional, igual que Supplier)
        if (empty($dto->name)) {
            throw new \Exception('El nombre del insumo es obligatorio');
        }

        return $this->inputRepository->create($dto->toArray());
    }

    /**
     * Actualizar insumo
     */
    public function update(Input $input, InputDTO $dto): Input
    {
        return $this->inputRepository->update($input, $dto->toArray());
    }

    /**
     * Eliminar insumo (borrado físico)
     */
    public function delete(Input $input): void
    {
        $this->inputRepository->delete($input);
    }
}
