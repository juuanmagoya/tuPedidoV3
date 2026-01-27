<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    /**
     * Tiempo de vida del cache (en minutos)
     */
    private int $cacheTtl = 10;

    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Obtener todos los productos (cacheado)
     */
    public function getAll(): Collection
    {
        return Cache::remember(
            'products.all',
            now()->addMinutes($this->cacheTtl),
            fn () => $this->productRepository->all()
        );
    }

    /**
     * Crear un producto y limpiar caché relacionada
     */
    public function store(ProductDTO $dto): Product
    {
        $product = $this->productRepository->create($dto);

        $this->clearCache();

        return $product;
    }

    /**
     * Actualizar un producto y limpiar caché relacionada
     */
    public function update(Product $product, ProductDTO $dto): Product
    {
        $updated = $this->productRepository->update($product, $dto);

        $this->clearCache();

        return $updated;
    }

    /**
     * Eliminar un producto y limpiar caché relacionada
     */
    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);

        $this->clearCache();
    }

    /**
     * Buscar productos con filtros (cacheado por combinación de filtros)
     */
    public function search(array $filters = []): LengthAwarePaginator
    {
        $page = request()->get('page', 1);

        $cacheKey = $this->makeSearchCacheKey($filters, $page);

        return Cache::remember(
            $cacheKey,
            now()->addMinutes($this->cacheTtl),
            fn () => $this->productRepository->search($filters)
        );
    }

    /**
     * Genera una clave única de caché según los filtros aplicados
     */
    protected function makeSearchCacheKey(array $filters, int $page): string
    {
        return 'products.search.'
            . md5(json_encode($filters))
            . '.page.' . $page;
    }


    /**
     * Limpia toda la caché relacionada a productos
     */
    private function clearCache(): void
    {
        Cache::forget('products.all');

        // Si usás Redis o Memcached podrías usar tags
        // Cache::tags('products')->flush();
    }
    
    /**
     * Aumentar stock de un producto
     */
    public function increaseStock(int $productId, float $quantity): void
    {
        $product = Product::findOrFail($productId);

        $product->increment('stock', $quantity);
    }
}
