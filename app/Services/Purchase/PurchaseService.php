<?php

namespace App\Services\Purchase;

use App\DTOs\Purchase\PurchaseDTO;
use App\Models\Purchase;
use App\Models\Input;
use App\Repositories\Purchase\PurchaseRepositoryInterface;
use App\Services\Input\InputService;
use DomainException;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly InputService $inputService
    ) {}

    /**
     * Crear compra e impactar stock inmediatamente
     */
    public function create(PurchaseDTO $dto): Purchase
    {
        return DB::transaction(function () use ($dto) {

            $purchase = $this->purchaseRepository->create($dto);

            foreach ($purchase->items as $item) {
                $input = Input::find($item->input_id);

                if (! $input) {
                    throw new DomainException('Insumo no encontrado.');
                }

                $this->inputService->increaseStock(
                    $input,
                    $item->quantity
                );
            }

            return $purchase;
        });
    }

    /**
     * Actualizar compra (revertir stock + aplicar nuevo)
     */
    public function update(Purchase $purchase, PurchaseDTO $dto): Purchase
    {
        if (! $purchase->canBeEdited()) {
            throw new DomainException('La compra no puede editarse en su estado actual.');
        }

        return DB::transaction(function () use ($purchase, $dto) {

            // 🔻 Revertimos stock anterior
            foreach ($purchase->items as $item) {
                $input = Input::find($item->input_id);

                if (! $input) {
                    throw new DomainException('Insumo no encontrado.');
                }

                $this->inputService->decreaseStock(
                    $input,
                    $item->quantity
                );
            }

            // 🔄 Actualizamos compra
            $updatedPurchase = $this->purchaseRepository->update($purchase, $dto);

            // 🔺 Aplicamos nuevo stock
            foreach ($updatedPurchase->items as $item) {
                $input = Input::find($item->input_id);

                if (! $input) {
                    throw new DomainException('Insumo no encontrado.');
                }

                $this->inputService->increaseStock(
                    $input,
                    $item->quantity
                );
            }

            return $updatedPurchase;
        });
    }

    /**
     * Cambiar estado (solo informativo)
     */
    public function changeStatus(Purchase $purchase, string $newStatus): Purchase
    {
        $allowedTransitions = [
            'pending'    => ['approved', 'cancelled'],
            'approved'   => ['in_transit', 'cancelled'],
            'in_transit' => ['completed', 'cancelled'],
        ];

        if (
            ! isset($allowedTransitions[$purchase->status]) ||
            ! in_array($newStatus, $allowedTransitions[$purchase->status])
        ) {
            throw new DomainException('Cambio de estado no permitido.');
        }

        $purchase->status = $newStatus;

        return $this->purchaseRepository->save($purchase);
    }

    /**
     * Cancelar compra y revertir stock
     */
    public function cancel(Purchase $purchase): Purchase
    {
        if ($purchase->status === 'completed') {
            throw new DomainException('No se puede cancelar una compra completada.');
        }

        return DB::transaction(function () use ($purchase) {

            foreach ($purchase->items as $item) {
                $input = Input::find($item->input_id);

                if (! $input) {
                    throw new DomainException('Insumo no encontrado.');
                }

                $this->inputService->decreaseStock(
                    $input,
                    $item->quantity
                );
            }

            $purchase->status = 'cancelled';

            return $this->purchaseRepository->save($purchase);
        });
    }
}
