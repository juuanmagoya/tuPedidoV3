<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('name'); //Nombre
            $table->string('tax_id')->nullable()->unique(); //Cuit o Ruc
            $table->string('phone')->nullable(); //Telefono
            $table->string('email')->nullable(); //Correo
            $table->string('address')->nullable(); //Direccion

            $table->string('contact_name')->nullable(); //Nombre del contacto y/o (vendedor, encargado)
            $table->string('payment_terms')->nullable(); //Terminos de pago (contado, 30 dias, etc)

            $table->text('notes')->nullable(); //Notas adicionales
            $table->boolean('is_active')->default(true); //Estado del proveedor

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
