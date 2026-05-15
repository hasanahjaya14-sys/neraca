<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('variable_values', 'sub_kategori_values');
    }

    public function down(): void
    {
        Schema::rename('sub_kategori_values', 'variable_values');
    }
};