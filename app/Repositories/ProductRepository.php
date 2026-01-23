<?php
namespace App\Repositories;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::with('category')->latest()->get();
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }
    

    public function create(ProductDTO $dto): Product
    {
        return Product::create($dto->toArray());
    }

    public function update(Product $product, ProductDTO $dto): Product
    {
        $product->update($dto->toArray());
        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
    public function search(array $filters = []):  LengthAwarePaginator
    {
        $query = Product::with('category')->latest();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->paginate(15)->withQueryString();
    }

}