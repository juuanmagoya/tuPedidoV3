<?php

namespace App\Http\Controllers;

use App\DTOs\Purchase\PurchaseDTO;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\ChangePurchaseStatusRequest;
use App\Models\Purchase;
use App\Services\Purchase\PurchaseService;
use DomainException;
use App\Models\Supplier;
use App\Models\Input;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\DTOs\Purchase\PurchaseUpdateDTO;

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
        $query = Purchase::with(['supplier', 'items.input']);

        // Filtro por estado
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filtro desde fecha
        if (request()->filled('from')) {
            $query->whereDate('purchase_date', '>=', request('from'));
        }

        // Filtro hasta fecha
        if (request()->filled('to')) {
            $query->whereDate('purchase_date', '<=', request('to'));
        }

        $purchases = $query
            ->latest('purchase_date')
            ->get();

        return view('purchases.index', compact('purchases'));
    }


    /**
     * Formulario de creación de compra
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $inputs    = Input::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'inputs'));
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
        $purchase->load([
            'supplier',
            'items.input',
        ]);

        return view('purchases.show', [
            'purchase' => $purchase,
        ]);
    }

    /**
     * Formulario de edición de compra
     */
    public function edit(Purchase $purchase)
    {
        if (! in_array($purchase->status, ['pending', 'approved'])) {
            return redirect()
                ->route('purchases.show', $purchase)
                ->with('error', 'Esta compra no se puede editar en su estado actual.');
        }

        $suppliers = Supplier::orderBy('name')->get();
        $inputs = Input::orderBy('name')->get();

        return view('purchases.edit', compact(
            'purchase',
            'suppliers',
            'inputs'
        ));
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
