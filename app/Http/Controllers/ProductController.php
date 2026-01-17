<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

//Las Validaciones las hacemos en el Request

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only('name', 'status', 'category_id');

        $products = $this->productService->search($filters);
        $categories = Category::all();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();

        return view('products.index', compact('products', 'categories', 'lowStockProducts'));
    }

    public function create()
    {
        return view('products.create', [
        'categories' => Category::all() // <--- esto es clave
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $dto = ProductDTO::fromRequest($request->validated());
        $this->productService->store($dto);

        return redirect()
        ->route('products.index')
        ->with('success', 'Producto creado correctamente');
    }
    public function edit(Product $product)
    {
        return view('products.edit', [
        'product' => $product,
        'categories' => Category::all()
    ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        
        $dto = ProductDTO::fromRequest($request->all(), $product->image);
        $this->productService->update($product, $dto);

        return redirect()
        ->route('products.index')
        ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()->back()->with('success', 'Producto eliminado correctamente');
    }
}

