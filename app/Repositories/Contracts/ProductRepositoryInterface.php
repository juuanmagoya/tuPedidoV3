<?php
namespace App\Repositories\Contracts;

use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Product;

    public function create(ProductDTO $dto): Product;

    public function update(Product $product, ProductDTO $dto): Product;

    public function delete(Product $product): void;
}
