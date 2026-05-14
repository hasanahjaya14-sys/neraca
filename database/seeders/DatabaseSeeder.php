<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RegionSeeder::class,      // 1. regions dulu
            UserSeeder::class,        // 2. baru users (butuh region_id)
            KategoriSeeder::class,    // 3. kategori
            SubKategoriSeeder::class, // 4. sub kategori (butuh kategori_id)
            MetadataSeeder::class,    // 5. metadata (butuh sub_kategori_id)
            VariableValueSeeder::class, // 6. terakhir (butuh semua di atas)
        ]);
    }
}