<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->enum('subject_type', ['kategori', 'sub_kategori']);
            $table->unsignedBigInteger('subject_id');
            $table->text('formula_string');
            $table->timestamps();

            // Satu formula per region per subject
            $table->unique(['region_id', 'subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulas');
    }
};