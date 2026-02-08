<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // Nombre del cliente
            $table->string('phone')->nullable(); // Teléfono
            $table->string('email')->nullable(); // Email
            $table->string('address')->nullable(); // Dirección por defecto
            $table->text('notes')->nullable(); // Observaciones

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
