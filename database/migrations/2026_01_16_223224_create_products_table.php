<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();

            $table->string('image')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();

            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);

            $table->string('unit')->default('unidad');

            $table->boolean('status')->default(true);

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
?>