<?php
namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getAll(): Collection
    {
        return $productRepository = $this->productRepository->all();
    }

    public function store(ProductDTO $dto): Product
    {
        return $this->productRepository->create($dto);
    }

    public function update(Product $product, ProductDTO $dto): Product
    {
        return $this->productRepository->update($product, $dto);
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }
     // ✅ Método search con filtros
    public function search(array $filters = [])
    {
        return $this->productRepository->search($filters);
    }
}
