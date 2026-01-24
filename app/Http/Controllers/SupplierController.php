<?php

namespace App\Http\Controllers;

use App\Services\Supplier\SupplierService;
use App\DTOs\Supplier\SupplierDTO;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct(
        private readonly SupplierService $supplierService
    ) {}

    /**
     * Listado de proveedores
     */
    public function index(): View
    {
        $suppliers = $this->supplierService->getAll();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Formulario de creación
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Guardar proveedor
     */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $dto = SupplierDTO::fromRequest($request);

        $this->supplierService->create($dto);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Proveedor creado correctamente');
    }

    /**
     * Formulario de edición
     */
    public function edit(int $id): View
    {
        $supplier = $this->supplierService->getById($id);

        if (!$supplier) {
            abort(404);
        }

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Actualizar proveedor
     */
    public function update(UpdateSupplierRequest $request, int $id): RedirectResponse
    {
        $dto = SupplierDTO::fromRequest($request);

        $this->supplierService->update($id, $dto);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Proveedor actualizado correctamente');
    }

    /**
     * Desactivar proveedor
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->supplierService->deactivate($id);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Proveedor desactivado correctamente');
    }
}
