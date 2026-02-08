<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Obtener todos los productos
     */
    public function getAll(): Collection
    {
        return $this->productRepository->all();
    }

    /**
     * Buscar productos con filtros
     */
    public function search(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->search($filters);
    }

    /**
     * Crear un producto
     */
    public function store(ProductDTO $dto): Product
    {
        return $this->productRepository->create($dto);
    }

    /**
     * Actualizar un producto
     */
    public function update(Product $product, ProductDTO $dto): Product
    {
        return $this->productRepository->update($product, $dto);
    }

    /**
     * Eliminar un producto
     */
    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }

    /**
     * Obtener producto por ID
     */
    public function getById(int $id): Product
    {
        $product = $this->productRepository->find($id);

        if (! $product) {
            throw new \Exception('Producto no encontrado');
        }

        return $product;
    }

    /**
     * Aumentar stock de un producto
     */
    public function increaseStock(Product $product, float $quantity): void
    {
        $product->increment('stock', $quantity);
    }

    /**
     * Disminuir stock de un producto
     */
    public function decreaseStock(Product $product, float $quantity): void
    {
        $product->decrement('stock', $quantity);
    }
}
