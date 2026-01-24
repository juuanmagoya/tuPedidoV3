<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Input\InputService;
use App\DTOs\Input\InputDTO;
use App\Http\Requests\Input\StoreInputRequest;
use App\Http\Requests\Input\UpdateInputRequest;
use App\Models\Input;

class InputController extends Controller
{
    public function __construct(
        private readonly InputService $inputService
    ) {}

    /**
     * Listado de insumos
     */
    public function index()
    {
        $inputs = $this->inputService->getAll();

        $lowStockInputs = Input::whereColumn('stock', '<=', 'min_stock')->get();
        return view('inputs.index', compact('inputs', 'lowStockInputs'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('inputs.create');
    }

    /**
     * Guardar insumo
     */
    public function store(StoreInputRequest $request)
    {
        $dto = InputDTO::fromRequest($request->validated());

        $this->inputService->create($dto);

        return redirect()
            ->route('inputs.index')
            ->with('success', 'Insumo creado correctamente');
    }

    /**
     * Formulario de edición
     */
    public function edit(Input $input)
    {
        return view('inputs.edit', compact('input'));
    }

    /**
     * Actualizar insumo
     */
    public function update(UpdateInputRequest $request, Input $input)
    {
        $dto = InputDTO::fromRequest($request->validated());

        $this->inputService->update($input, $dto);

        return redirect()
            ->route('inputs.index')
            ->with('success', 'Insumo actualizado correctamente');
    }

    /**
     * Eliminar insumo (borrado físico)
     */
    public function destroy(Input $input)
    {
        $this->inputService->delete($input->id);

        return redirect()
            ->route('inputs.index')
            ->with('success', 'Insumo eliminado correctamente');
    }
}
