<?php

namespace App\Services\Input;

use App\DTOs\Input\InputDTO;
use App\Models\Input;
use App\Repositories\Input\InputRepositoryInterface;
use Illuminate\Support\Collection;
use RuntimeException;

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

    public function getById(int $id): Input
    {
        $input = $this->inputRepository->findById($id);

        if (!$input) {
            throw new \Exception("Insumo no encontrado");
        }

        return $input;
    }

    /**
     * Crear insumo
     */
    public function create(InputDTO $dto): Input
    {
        if (empty($dto->name)) {
            throw new RuntimeException('El nombre del insumo es obligatorio');
        }

        return $this->inputRepository->create($dto->toArray());
    }

    /**
     * Actualizar insumo
     */
    public function update(Input $input, InputDTO $dto): Input
    {
        return $this->inputRepository->update(
            $input,
            $dto->toArray()
        );
    }


    /**
     * Eliminar insumo (borrado físico)
     */
    public function delete(Input $input): void
    {
        $this->inputRepository->delete($input);
    }
    /**
     * ✔️ Validar stock disponible de un insumo
     */
    public function validateStockAvailability(int $inputId, float $quantity): void
    {
        $input = $this->getById($inputId);

        if (!$input || $input->stock < $quantity) {
            throw new \RuntimeException(
                "Stock insuficiente del insumo {$input->name}"
            );
        }
    }

    /**
     * 🔻 Disminuir stock de un insumo (Producción)
     */
    public function decreaseStock(Input $input, float $quantity): void
    {
        if ($input->stock < $quantity) {
            throw new \Exception("Stock insuficiente para el insumo {$input->name}");
        }

        $input->decrement('stock', $quantity);
    }


}
