<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 100, 50000);
        $cost  = $price * $this->faker->randomFloat(2, 0.5, 0.9);

        return [
            'name'        => $this->faker->unique()->words(3, true),

            'sku'         => strtoupper(Str::random(10)),

            'description' => $this->faker->optional()->paragraph(),

            'image'       => null, // luego podés simular imágenes

            'price'       => $price,
            'cost_price'  => $this->faker->boolean(80) ? $cost : null,

            'stock'       => $this->faker->numberBetween(0, 500),
            'min_stock'   => $this->faker->numberBetween(0, 20),

            'unit'        => $this->faker->randomElement([
                'unidad', 'kg', 'litro', 'pack'
            ]),

            'status'      => $this->faker->boolean(90), // 90% activos

            'category_id' => Category::inRandomOrder()->first()?->id
                ?? Category::factory(),
        ];
    }

    /* ===============================
     | Estados del Factory
     =============================== */

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => false,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn () => [
            'stock' => $this->faker->numberBetween(0, 5),
        ]);
    }
}
