<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_inputs', function (Blueprint $table) {
            $table->id();

            // Relación con la producción
            $table->foreignId('production_id')
                ->constrained('productions')
                ->cascadeOnDelete();

            // Relación con el insumo utilizado
            $table->foreignId('inputs_id')
                ->constrained('inputs')
                ->restrictOnDelete();

            // Cantidad de insumo utilizada (ej: 10, 0.300, 4)
            $table->decimal('quantity_used', 12, 3);

            // Unidad del insumo al momento de la producción (kg, g, lt, un)
            // Se guarda para mantener histórico
            $table->string('unit', 20);

            // Precio costo unitario del insumo al momento de la producción
            // IMPORTANTE: no se debe tomar del insumo actual
            $table->decimal('cost_price', 12, 2);

            // Subtotal = quantity_used * cost_price
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_inputs');
    }
};
