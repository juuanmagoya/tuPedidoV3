<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla principal de pedidos
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID del pedido

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // FK al cliente (puede ser null)

            $table->string('customer_name'); // Nombre del cliente

            $table->enum('status', [
                'received',     // Pedido recibido
                'preparing',    // En preparación
                'on_the_way',   // En camino
                'delivered',    // Entregado
                'canceled'      // Cancelado
            ])->default('received'); // Estado del pedido

            $table->enum('order_type', [
                'delivery',
                'in_store'
            ])->default('in_store'); // Tipo de pedido

            $table->string('address')->nullable(); // Dirección (solo delivery)

            $table->enum('payment_method', [
                'cash',
                'card',
                'qr',
                'other'
            ])->default('cash'); // Método de pago

            $table->text('notes')->nullable(); // Observaciones

            $table->decimal('total', 10, 2)->default(0); // Total del pedido

            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
