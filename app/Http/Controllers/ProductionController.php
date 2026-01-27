<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Services\Production\ProductionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function __construct(
        private readonly ProductionService $productionService
    ) {}

    /**
     * Listado de producciones
     */
    public function index(): View
    {
        return view('productions.index', [
            'productions' => $this->productionService->getAll()
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
    public function store(StoreProductionRequest $request): RedirectResponse
    {
        $this->productionService->create(
            $request->validated()
        );


        return redirect()
            ->route('productions.index')
            ->with('success', 'Producción registrada correctamente');
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
}
