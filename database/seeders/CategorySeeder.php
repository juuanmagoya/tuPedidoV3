<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Panificados',
            'Facturas',
            'Pastelería',
            'Dulces',
            'Salados',
            'Congelados',
            'Especiales',
            'Bebidas',
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
