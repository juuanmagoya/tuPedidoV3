<?php

namespace App\Services\Production;

use App\Models\Product;
use App\Models\Production;
use Illuminate\Support\Facades\DB;
use App\Services\Input\InputService;
use App\Services\ProductService;
use App\Repositories\Production\ProductionRepository;
use App\Repositories\Production\ProductionInputRepository;
use App\Repositories\Production\ProductionProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\DTOs\Production\ChangeProductionStatusDTO;

class ProductionService
{
    public function __construct(
        protected ProductionRepository $productionRepository,
        protected ProductionInputRepository $productionInputRepository,
        protected ProductionProductRepository $productionProductRepository,
        protected InputService $inputService,
        protected ProductService $productService,
    ) {}

    private function generateCode(): string
    {
        $lastId = Production::max('id') + 1;

        return 'PRD-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
    }
    /**
     * Obtener todas las producciones
     */
    public function getAll()
    {
        return $this->productionRepository->getAll();
    }

    /**
     * Obtener una producción por ID
     */
    public function getById(int $id): Production
    {
        return $this->productionRepository->findById($id);
    }

    /**
     * Inputs disponibles para formularios
     */
    public function getAvailableInputs()
    {
        return $this->inputService->getAll();
    }

    /**
     * Productos disponibles para formularios
     */
    public function getAvailableProducts()
    {
        return $this->productService->getAll();
    }

    /**
     * Registrar una producción
     */
    
    public function create(array $data): Production
    {
        return DB::transaction(function () use ($data) {

            /** 1️⃣ Crear producción (costo inicial en 0) */
            $production = Production::create([
                'code'            => $this->generateCode(),
                'production_date' => $data['date'],
                'status'          => 'draft',
                'notes'           => $data['description'] ?? null,
                'created_by'      => Auth::id(),
                'total_cost'      => 0,
            ]);

            $totalCost = 0;

            /** 2️⃣ Registrar insumos consumidos + reducir stock */
            foreach ($data['inputs'] as $row) {

                $input = $this->inputService->getById((int) $row['input_id']);

                $quantity  = (float) $row['quantity'];
                $costPrice = (float) $input->cost_price;
                $subtotal  = $quantity * $costPrice;

                $this->productionInputRepository->create([
                    'production_id' => $production->id,
                    'inputs_id'     => $input->id,
                    'quantity_used' => $quantity,
                    'unit'          => $input->unit,
                    'cost_price'    => $costPrice,
                    'subtotal'      => $subtotal,
                ]);

                // 🔻 bajar stock del insumo
                $this->inputService->decreaseStock($input, $quantity);

                // 🔢 acumular costo total
                $totalCost += $subtotal;
            }

            /** 3️⃣ Calcular cantidad total producida */
            $totalProduced = collect($data['products'])
                ->sum(fn ($row) => (float) $row['quantity']);

            // Si por alguna razón es 0, evitamos división (no debería pasar por el Request)
            $unitCost = $totalProduced > 0
                ? $totalCost / $totalProduced
                : 0;

            /** 4️⃣ Registrar productos generados + aumentar stock */
            foreach ($data['products'] as $row) {

                $product  = $this->productService->getById((int) $row['product_id']);
                $quantity = (float) $row['quantity'];

                $this->productionProductRepository->create([
                    'production_id'     => $production->id,
                    'product_id'        => $product->id,
                    'quantity_produced' => $quantity,
                    'unit'              => $product->unit,
                    'cost_price'        => $unitCost, // ✅ costo real del lote
                ]);

                // 🔺 aumentar stock del producto
                $this->productService->increaseStock($product, $quantity);


            }

            /** 5️⃣ Actualizar costo total de la producción */
            $production->update([
                'total_cost' => $totalCost,
            ]);

            return $production;
        });
    }
    public function changeStatus(ChangeProductionStatusDTO $dto): void
    {
        $production = $this->productionRepository
            ->findById($dto->productionId);

        // 1️⃣ No permitir cambios si está cancelada
        if ($production->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'No se puede cambiar el estado de una producción cancelada.',
            ]);
        }

        // 2️⃣ Validar transición
        if (! $this->isValidTransition($production->status, $dto->newStatus)) {
            throw ValidationException::withMessages([
                'status' => 'Transición de estado no permitida.',
            ]);
        }

        // 3️⃣ Aplicar cambio (SOLO status)
        $this->productionRepository->updateStatus($production, [
            'status' => $dto->newStatus,
        ]);
    }


    /**
     * Validar transiciones de estado permitidas
     */
    private function isValidTransition(string $current, string $next): bool
    {
        $allowedTransitions = [
            'draft' => ['confirmed', 'cancelled'],
            'confirmed' => ['cancelled'],
            'cancelled' => [],
        ];

        return in_array($next, $allowedTransitions[$current] ?? [], true);
    }
}
