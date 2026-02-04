<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('input_id')
                ->constrained()
                ->restrictOnDelete();

            $table->decimal('quantity', 12, 3);
            $table->string('unit');

            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
