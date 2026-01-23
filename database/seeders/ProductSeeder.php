<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 🔒 Traemos categorías (fallar rápido si falta alguna)
        $panaderia  = Category::where('name', 'Panificados')->firstOrFail();
        $facturas   = Category::where('name', 'Facturas')->firstOrFail();
        $pasteleria = Category::where('name', 'Pastelería')->firstOrFail();
        $bebidas    = Category::where('name', 'Bebidas')->firstOrFail();
        $salados    = Category::where('name', 'Salados')->firstOrFail();

        $now = now();

        $products = [

            // 🥖 PANADERÍA
            [
                'name' => 'Pan francés',
                'sku' => 'PAN-FRA',
                'description' => 'Pan francés fresco del día',
                'price' => 1200,
                'cost_price' => 700,
                'stock' => 60,
                'min_stock' => 15,
                'unit' => 'kg',
                'status' => true,
                'category_id' => $panaderia->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pan integral',
                'sku' => 'PAN-INT',
                'description' => 'Pan integral con semillas',
                'price' => 1600,
                'cost_price' => 950,
                'stock' => 25,
                'min_stock' => 5,
                'unit' => 'kg',
                'status' => true,
                'category_id' => $panaderia->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pan de molde',
                'sku' => 'PAN-MOL',
                'description' => 'Pan de molde blanco artesanal',
                'price' => 1800,
                'cost_price' => 1100,
                'stock' => 20,
                'min_stock' => 5,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $panaderia->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 🥐 FACTURAS
            [
                'name' => 'Medialuna de manteca',
                'sku' => 'FAC-MED-M',
                'description' => 'Medialuna de manteca artesanal',
                'price' => 350,
                'cost_price' => 180,
                'stock' => 120,
                'min_stock' => 30,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $facturas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Medialuna de grasa',
                'sku' => 'FAC-MED-G',
                'description' => 'Medialuna de grasa clásica',
                'price' => 300,
                'cost_price' => 150,
                'stock' => 100,
                'min_stock' => 25,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $facturas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Vigilante',
                'sku' => 'FAC-VIG',
                'description' => 'Vigilante relleno con dulce de membrillo',
                'price' => 380,
                'cost_price' => 200,
                'stock' => 60,
                'min_stock' => 15,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $facturas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Docena de facturas',
                'sku' => 'FAC-DOC',
                'description' => 'Docena surtida de facturas',
                'price' => 3800,
                'cost_price' => 2500,
                'stock' => 20,
                'min_stock' => 5,
                'unit' => 'docena',
                'status' => true,
                'category_id' => $facturas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 🎂 PASTELERÍA
            [
                'name' => 'Torta rogel',
                'sku' => 'PAS-ROG',
                'description' => 'Torta rogel con dulce de leche',
                'price' => 9500,
                'cost_price' => 6200,
                'stock' => 4,
                'min_stock' => 1,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $pasteleria->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Torta de chocolate',
                'sku' => 'PAS-CHO',
                'description' => 'Torta húmeda de chocolate',
                'price' => 8800,
                'cost_price' => 5800,
                'stock' => 3,
                'min_stock' => 1,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $pasteleria->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cheesecake',
                'sku' => 'PAS-CHE',
                'description' => 'Cheesecake clásico',
                'price' => 9200,
                'cost_price' => 6000,
                'stock' => 3,
                'min_stock' => 1,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $pasteleria->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 🥪 SALADOS
            [
                'name' => 'Empanada de jamón y queso',
                'sku' => 'SAL-EMP-JQ',
                'description' => 'Empanada horneada de jamón y queso',
                'price' => 650,
                'cost_price' => 350,
                'stock' => 40,
                'min_stock' => 10,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $salados->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tarta de verdura',
                'sku' => 'SAL-TAR-VER',
                'description' => 'Porción de tarta de verdura',
                'price' => 1800,
                'cost_price' => 1100,
                'stock' => 8,
                'min_stock' => 2,
                'unit' => 'porcion',
                'status' => true,
                'category_id' => $salados->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ☕ BEBIDAS
            [
                'name' => 'Café chico',
                'sku' => 'BEB-CAF-CH',
                'description' => 'Café caliente chico',
                'price' => 1200,
                'cost_price' => 500,
                'stock' => 999,
                'min_stock' => 50,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $bebidas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Café grande',
                'sku' => 'BEB-CAF-GR',
                'description' => 'Café caliente grande',
                'price' => 1600,
                'cost_price' => 700,
                'stock' => 999,
                'min_stock' => 50,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $bebidas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Jugo exprimido',
                'sku' => 'BEB-JUG-NAR',
                'description' => 'Jugo natural de naranja',
                'price' => 2200,
                'cost_price' => 1200,
                'stock' => 30,
                'min_stock' => 5,
                'unit' => 'unidad',
                'status' => true,
                'category_id' => $bebidas->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Product::insert($products);
    }
}
