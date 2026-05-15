<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sub_sub_kategori_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_sub_kategori_id')->constrained('sub_sub_kategoris')->cascadeOnDelete();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->tinyInteger('triwulan');
            $table->smallInteger('tahun');
            $table->bigInteger('value')->nullable();
            $table->timestamps();

            $table->unique(['sub_sub_kategori_id', 'region_id', 'triwulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_sub_kategori_values');
    }
};