<?php

namespace App\DTOs;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

class ProductDTO
{
    public ?string $imagePath;

    public function __construct(
        public string $name,
        public ?string $sku,
        public ?string $description,
        public string $unit,
        public float $price,
        public ?float $cost_price,
        public int $stock,
        public int $min_stock,
        public ?string $status, // 🔥 ahora string
        public int $category_id,
        public UploadedFile|string|null $image = null,
    ) {
        $this->imagePath = $this->processImage($image);
    }

    protected function processImage(UploadedFile|string|null $image): ?string
    {
        if ($image instanceof UploadedFile) {
            return $image->store('products', 'public');
        }

        if (is_string($image)) {
            return $image;
        }

        return null;
    }

    public static function fromRequest(array $data, ?string $currentImage = null, ?string $currentStatus = null)
    : self 
    {
        return new self(
        name: $data['name'],
        sku: $data['sku'] ?? null,
        description: $data['description'] ?? null,
        unit: $data['unit'] ?? 'unidad',
        price: (float) $data['price'],
        cost_price: isset($data['cost_price']) ? (float) $data['cost_price'] : null,
        stock: (int) ($data['stock'] ?? 0),
        min_stock: (int) ($data['min_stock'] ?? 0),
        status: $data['status'] ?? $currentStatus ?? Product::STATUS_ACTIVE, // 👈 IMPORTANTE
        category_id: (int) $data['category_id'],
        image: $data['image'] ?? $currentImage
        );
    }



    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'sku'         => $this->sku,
            'description' => $this->description,
            'unit'        => $this->unit,
            'price'       => $this->price,
            'cost_price'  => $this->cost_price,
            'stock'       => $this->stock,
            'min_stock'   => $this->min_stock,
            'status'      => $this->status,
            'category_id' => $this->category_id,
            'image'       => $this->imagePath,
        ];
    }
}
