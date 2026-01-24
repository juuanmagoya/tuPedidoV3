<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inputs', function (Blueprint $table) { //Tabla de insumos
            $table->id();

            $table->string('name');
            $table->string('unit', 20); // kg, g, l, unidad
            $table->decimal('stock', 10, 3)->default(0); // Cantidad en stock
            $table->decimal('min_stock', 10, 3)->default(0); // Stock mínimo para alertas

            $table->decimal('cost_price', 12, 2)->nullable(); // Precio de costo por unidad

            $table->foreignId('supplier_id') // Proveedor principal
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable(); // Notas adicionales

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inputs');
    }
};
