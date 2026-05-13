<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bps', 10)->unique();
            $table->string('name');
            $table->enum('tipe', ['provinsi', 'kabupaten', 'kota']);
            $table->foreignId('provinsi_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};