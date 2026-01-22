<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            // Filtros
            $table->index('sku');
            $table->index('status');
            $table->index('category_id');

            // Búsqueda
            $table->index('name');

            // Ordenamiento
            $table->index('created_at');

            // Alertas bajo stock (consulta stock <= min_stock)
            $table->index(['stock', 'min_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['sku']);
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['name']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['stock', 'min_stock']);
        });
    }
};
