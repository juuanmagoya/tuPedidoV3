<?php

namespace App\Http\Controllers;

use App\DTOs\Purchase\PurchaseDTO;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\ChangePurchaseStatusRequest;
use App\Models\Purchase;
use App\Services\Purchase\PurchaseService;
use DomainException;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseService $purchaseService
    ) {}

    /**
     * Listado de compras
     */
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'items.input'])
            ->latest()
            ->get();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Crear compra
     */
    public function store(StorePurchaseRequest $request)
    {
        try {
            $dto = PurchaseDTO::fromArray($request->validated());

            $this->purchaseService->create($dto);

            return redirect()
                ->route('purchases.index')
                ->with('success', 'Compra creada correctamente.');

        } catch (DomainException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Ver detalle de una compra
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.input']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Actualizar compra
     */
    public function update(
        StorePurchaseRequest $request,
        Purchase $purchase
    ) {
        try {
            $dto = PurchaseDTO::fromArray($request->validated());

            $this->purchaseService->update($purchase, $dto);

            return redirect()
                ->route('purchases.show', $purchase)
                ->with('success', 'Compra actualizada correctamente.');

        } catch (DomainException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cambiar estado de la compra
     */
    public function changeStatus(
        ChangePurchaseStatusRequest $request,
        Purchase $purchase
    ) {
        try {
            $this->purchaseService->changeStatus(
                $purchase,
                $request->validated('status')
            );

            return redirect()
                ->back()
                ->with('success', 'Estado de la compra actualizado correctamente.');

        } catch (DomainException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancelar compra
     */
    public function cancel(Purchase $purchase)
    {
        try {
            $this->purchaseService->cancel($purchase);

            return redirect()
                ->back()
                ->with('success', 'Compra cancelada correctamente.');

        } catch (DomainException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
