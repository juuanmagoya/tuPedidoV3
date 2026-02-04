<?php

use App\Enums\PurchaseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('purchase_date');

            $table->enum('status', ['pending', 'approved', 'in_transit', 'completed', 'cancelled'])->default('pending');

            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('total', 12, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
