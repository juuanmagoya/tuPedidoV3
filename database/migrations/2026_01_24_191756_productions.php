<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();

            // Código único de la producción (ej: PROD-0001)
            $table->string('code')->unique();

            // Fecha en la que se realiza la producción
            $table->date('production_date');

            // Costo total de la producción (suma de todos los insumos usados)
            $table->decimal('total_cost', 12, 2)->default(0);

            // Estado de la producción:
            // draft = borrador (no impacta stock)
            // confirmed = confirmada (impacta stock)
            // cancelled = anulada
            $table->enum('status', ['draft', 'confirmed', 'cancelled'])->default('draft');

            // Observaciones adicionales de la producción
            $table->text('notes')->nullable();

            // Usuario que registró la producción (opcional)
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
