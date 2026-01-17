<?php
namespace App\DTOs;

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
        public int $status,
        public int $category_id,
        public UploadedFile|string|null $image = null, // flexible
    ) {
        $this->imagePath = $this->processImage($image);
    }

    /**
     * Procesa la imagen: guarda UploadedFile en storage y devuelve ruta,
     * o devuelve el string existente, o null.
     */
    protected function processImage(UploadedFile|string|null $image): ?string
    {
        if ($image instanceof UploadedFile) {
            // Guardar en storage/public/products y devolver ruta relativa
            return $image->store('products', 'public');
        }

        if (is_string($image)) {
            return $image; // Ruta existente (edit)
        }

        return null; // No se envió imagen y no hay existente
    }

    /**
     * Crear DTO desde request/array de datos
     * @param array $data Request o array de datos
     * @param string|null $currentImage Ruta de la imagen actual (para edit)
     */
    public static function fromRequest(array $data, ?string $currentImage = null): self
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
            status: (int) ($data['status'] ?? 1),
            category_id: (int) $data['category_id'],
            // Si no hay imagen en el request, usar la actual
            image: $data['image'] ?? $currentImage
        );
    }

    /**
     * Convierte DTO a array para crear o actualizar modelo
     */
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
            'image'       => $this->imagePath, // Mantiene la imagen existente si no hay nueva
        ];
    }
}
