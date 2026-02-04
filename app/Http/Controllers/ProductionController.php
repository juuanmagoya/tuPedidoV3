<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Services\Production\ProductionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\DTOs\Production\ChangeProductionStatusDTO;
use App\Http\Requests\Production\ChangeProductionStatusRequest;
use App\Http\Requests\Production\UpdateProductionRequest;
use Illuminate\Http\Request;


class ProductionController extends Controller
{
    public function __construct(
        private readonly ProductionService $productionService
    ) {}

    /**
     * Listado de producciones
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'status',
            'from',
            'to',
        ]);

        return view('productions.index', [
            'productions' => $this->productionService->getFiltered($filters),
            'filters'     => $filters, // opcional pero útil para la vista
        ]);
    }


    /**
     * Formulario de creación
     */
    public function create(): View
    {
        return view('productions.create', [
            'inputs' => $this->productionService->getAvailableInputs(),
            'products' => $this->productionService->getAvailableProducts(),
        ]);
    }

    /**
     * Registrar producción
     */
    public function store(StoreProductionRequest $request)
    {
        try {
            $this->productionService->create($request->validated());

            return redirect()
                ->route('productions.index')
                ->with('success', 'Producción registrada correctamente');

        } catch (\DomainException $e) {
            return back()
                ->withErrors(['business' => $e->getMessage()])
                ->withInput();
        }
    }
    /**
     * Ver detalle de producción
     */
    public function show(int $id): View
    {
        return view('productions.show', [
            'production' => $this->productionService->getById($id)
        ]);
    }
    
    public function changeStatus(
        ChangeProductionStatusRequest $request,
        int $production
    ) {
        $dto = new ChangeProductionStatusDTO(
            productionId: $production,
            newStatus: $request->validated('status'),
        );


        $this->productionService->changeStatus($dto);

        return redirect()
            ->back()
            ->with('success', 'Estado de la producción actualizado correctamente.');
    }
    public function edit(int $id)
    {
        $production = $this->productionService->getById($id);

        // ❌ Seguridad extra: no permitir editar canceladas
        if ($production->status === 'cancelled') {
            return redirect()
                ->route('productions.index')
                ->withErrors('No se puede editar una producción cancelada.');
        }

        return view('productions.edit', [
            'production' => $production,
            'inputs'     => $this->productionService->getAvailableInputs(),
            'products'   => $this->productionService->getAvailableProducts(),
        ]);
    }
    public function update(UpdateProductionRequest $request, int $id): RedirectResponse
    {
        try {
            $this->productionService->update($id, $request->validated());

            return redirect()
                ->route('productions.index')
                ->with('success', 'Producción actualizada correctamente');

        } catch (\DomainException $e) {

            return back()
                ->withErrors(['business' => $e->getMessage()])
                ->withInput();

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withErrors(['error' => 'Ocurrió un error inesperado al actualizar la producción'])
                ->withInput();
        }
    }

}
