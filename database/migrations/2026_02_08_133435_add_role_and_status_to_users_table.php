<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Role del usuario
            $table->enum('role', [
                'admin',
                'production_manager',
                'purchase_manager',
            ])->default('purchase_manager')->after('password');

            // Status del usuario
            $table->enum('status', [
                'active',
                'inactive',
                'pending',
            ])->default('pending')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status']);
        });
    }
};
