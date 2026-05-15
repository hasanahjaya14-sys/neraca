<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sub_kategori_values', function (Blueprint $table) {
            $table->decimal('value', 20, 4)->nullable()->change();
        });

        Schema::table('sub_sub_kategori_values', function (Blueprint $table) {
            $table->decimal('value', 20, 4)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sub_kategori_values', function (Blueprint $table) {
            $table->bigInteger('value')->nullable()->change();
        });

        Schema::table('sub_sub_kategori_values', function (Blueprint $table) {
            $table->bigInteger('value')->nullable()->change();
        });
    }
};