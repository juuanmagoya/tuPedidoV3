<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_products', function (Blueprint $table) {
            $table->id();

            // Relación con la producción
            $table->foreignId('production_id')
                ->constrained('productions')
                ->cascadeOnDelete();

            // Producto que se fabrica
            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            // Cantidad producida del producto final (ej: 50 panes)
            $table->decimal('quantity_produced', 12, 3);

            // Unidad del producto (un, kg, etc.)
            $table->string('unit', 20);

            // Precio costo unitario resultante del producto fabricado
            // Se calcula a partir del total_cost / quantity_produced
            $table->decimal('cost_price', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_products');
    }
};
