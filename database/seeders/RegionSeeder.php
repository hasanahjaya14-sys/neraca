<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->insert([
            'kode_bps' => '62',
            'name' => 'Kalimantan Tengah',
            'tipe' => 'provinsi',
            'provinsi_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $provinsiId = DB::getPdo()->lastInsertId();

        $regions = [
            ['kode_bps' => '6201', 'name' => 'Kotawaringin Barat', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6202', 'name' => 'Kotawaringin Timur', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6203', 'name' => 'Kapuas', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6204', 'name' => 'Barito Selatan', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6205', 'name' => 'Barito Utara', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6206', 'name' => 'Sukamara', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6207', 'name' => 'Lamandau', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6208', 'name' => 'Seruyan', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6209', 'name' => 'Katingan', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6210', 'name' => 'Pulang Pisau', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6211', 'name' => 'Gunung Mas', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6212', 'name' => 'Barito Timur', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6213', 'name' => 'Murung Raya', 'tipe' => 'kabupaten'],
            ['kode_bps' => '6271', 'name' => 'Palangka Raya', 'tipe' => 'kota'],
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                ...$region,
                'provinsi_id' => $provinsiId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}