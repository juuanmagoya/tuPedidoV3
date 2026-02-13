<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use DomainException;
use Illuminate\Validation\ValidationException;

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
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages([
                'stock' => "Stock insuficiente para el producto {$product->name}"
            ]);
        }

        $product->decrement('stock', $quantity);
    }

    public function changeStatus(Product $product, string $newStatus): Product
    {
        $allowedTransitions = [
            Product::STATUS_INACTIVE => [
                Product::STATUS_ACTIVE
            ],

            Product::STATUS_ACTIVE => [
                Product::STATUS_PROMOTION,
                Product::STATUS_FEATURED,
            ],

            Product::STATUS_PROMOTION => [
                Product::STATUS_OUT_OF_STOCK,
            ],

            Product::STATUS_FEATURED => [
                Product::STATUS_OUT_OF_STOCK,
            ],

            Product::STATUS_OUT_OF_STOCK => [
                Product::STATUS_INACTIVE,
            ],
        ];

        // Validar que el estado exista
        if (!array_key_exists($newStatus, Product::STATUS_LABELS)) {
            throw new DomainException('Estado inválido.');
        }

        // Validar transición permitida
        if (
            !isset($allowedTransitions[$product->status]) ||
            !in_array($newStatus, $allowedTransitions[$product->status])
        ) {
            throw new DomainException('Cambio de estado no permitido.');
        }

        return $this->productRepository->updateStatus($product, $newStatus);
    }

}
