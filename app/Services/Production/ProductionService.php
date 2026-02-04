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
     * Obtener producciones con filtros
     */
    public function getFiltered(array $filters)
    {
        $query = Production::query()
            ->with(['inputs', 'products'])
            ->orderBy('production_date', 'desc');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('production_date', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('production_date', '<=', $filters['to']);
        }

        return $query->paginate(10)->withQueryString();
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

                $input = $this->inputService->getById((int) $row['inputs_id']);

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

    public function update(int $productionId, array $data): Production
{
    return DB::transaction(function () use ($productionId, $data) {

        /** 1️⃣ Obtener producción */
        $production = $this->productionRepository->findById($productionId);

        /** 2️⃣ Validaciones de negocio */
        if ($production->status === 'cancelled') {
            throw new \DomainException(
                'No se puede editar una producción cancelada.'
            );
        }

        /** 3️⃣ Revertir INSUMOS consumidos (sumar stock nuevamente) */
        $oldInputs = $this->productionInputRepository
            ->getByProduction($production->id);

        foreach ($oldInputs as $row) {
            $input = $this->inputService->getById($row->inputs_id);

            // 🔺 devolver stock
            $this->inputService->increaseStock(
                $input,
                (float) $row->quantity_used
            );
        }

        /** 4️⃣ Revertir PRODUCTOS generados (bajar stock) */
        $oldProducts = $this->productionProductRepository
            ->getByProduction($production->id);

        foreach ($oldProducts as $row) {
            $product = $this->productService->getById($row->product_id);

            // 🔻 quitar stock
            $this->productService->decreaseStock(
                $product,
                (float) $row->quantity_produced
            );
        }

        /** 5️⃣ Eliminar detalle anterior */
        $this->productionInputRepository
            ->deleteByProduction($production->id);

        $this->productionProductRepository
            ->deleteByProduction($production->id);

        /** 6️⃣ Actualizar cabecera */
        $production->update([
            'production_date' => $data['date'],
            'notes'           => $data['description'] ?? null,
        ]);

        $totalCost = 0;

        /** 7️⃣ Registrar NUEVOS insumos */
        foreach ($data['inputs'] as $row) {

            $input = $this->inputService->getById((int) $row['inputs_id']);

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

            // 🔻 bajar stock
            $this->inputService->decreaseStock($input, $quantity);

            $totalCost += $subtotal;
        }

        /** 8️⃣ Calcular costo unitario */
        $totalProduced = collect($data['products'])
            ->sum(fn ($row) => (float) $row['quantity']);

        $unitCost = $totalProduced > 0
            ? $totalCost / $totalProduced
            : 0;

        /** 9️⃣ Registrar NUEVOS productos */
        foreach ($data['products'] as $row) {

            $product  = $this->productService->getById((int) $row['product_id']);
            $quantity = (float) $row['quantity'];

            $this->productionProductRepository->create([
                'production_id'     => $production->id,
                'product_id'        => $product->id,
                'quantity_produced' => $quantity,
                'unit'              => $product->unit,
                'cost_price'        => $unitCost,
            ]);

            // 🔺 aumentar stock
            $this->productService->increaseStock($product, $quantity);
        }

        /** 🔟 Actualizar costo total */
        $production->update([
            'total_cost' => $totalCost,
        ]);

        return $production;
    });
}

}
