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
            $table->dropColumn('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->enum('status', [
                'inactive',
                'active',
                'promotion',
                'featured',
                'out_of_stock'
            ])->default('inactive')->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('price');
        });
    }

};
