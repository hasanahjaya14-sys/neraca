<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_dummy', function (Blueprint $table) {
            $table->id();
            $table->string('kabko');
            $table->string('variabel');
            $table->string('satuan');
            $table->year('tahun');
            $table->float('nilai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_dummy');
    }
};