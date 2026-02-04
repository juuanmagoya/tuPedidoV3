<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla detalle de productos por pedido
        Schema::create('order_products', function (Blueprint $table) {
            $table->id(); // ID del detalle

            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade'); // FK al pedido

            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('restrict'); // FK al producto

            $table->integer('quantity')->default(1); // Cantidad pedida

            $table->decimal('unit_price', 10, 2); // Precio unitario al momento del pedido

            $table->decimal('subtotal', 10, 2); // quantity * unit_price

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
