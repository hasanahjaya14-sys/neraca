<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metadatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_kategori_id')->constrained('sub_kategoris')->cascadeOnDelete();
            $table->text('definisi')->nullable();
            $table->string('sumber_data')->nullable();
            $table->string('satuan')->nullable();
            $table->text('metode_perhitungan')->nullable();
            $table->text('rumus')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique('sub_kategori_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadatas');
    }
};