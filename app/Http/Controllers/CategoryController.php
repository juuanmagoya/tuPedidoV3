<?php

namespace App\Http\Controllers;

use App\DTOs\CategoryData;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use App\Models\Category;

/**
 * Controlador encargado de la gestión de categorías.
 *
 * Responsabilidades:
 * - Manejar las peticiones HTTP (CRUD)
 * - Validar datos de entrada
 * - Delegar la lógica de negocio al CategoryService
 *
 * Nota:
 * El controlador NO contiene lógica de negocio.
 * Solo coordina requests, servicios y respuestas.
 */
class CategoryController extends Controller
{
    /**
     * Servicio de dominio para categorías.
     * Se inyecta por constructor (Dependency Injection).
     */
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * Mostrar listado paginado de categorías.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Persistir una nueva categoría.
     *
     * Flujo:
     * 1. Validar request
     * 2. Mapear datos al DTO
     * 3. Delegar guardado al servicio
     * 4. Redirigir con flash message
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $categoryData = CategoryData::fromRequest($request);

        $this->categoryService->store($categoryData);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría creada correctamente');
    }

    /**
     * Mostrar formulario de edición.
     * Usa Route Model Binding.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualizar una categoría existente.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data = CategoryData::fromRequest($request);

        $this->categoryService->update($category, $data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría actualizada');
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría eliminada');
    }
}
