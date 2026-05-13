<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua region
        $provinsi = DB::table('regions')->where('tipe', 'provinsi')->first();
        $kabkos = DB::table('regions')->where('tipe', '!=', 'provinsi')->get()->keyBy('kode_bps');

        // Hapus data lama
        DB::table('users')->truncate();

        // 1. Superadmin
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'region_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Akun Provinsi
        DB::table('users')->insert([
            'name' => 'Admin Provinsi',
            'username' => 'provinsi',
            'email' => 'provinsi@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'provinsi',
            'region_id' => $provinsi->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Akun Kabko (14 kabupaten/kota)
        $kabkoList = [
            ['kode' => '6201', 'name' => 'Admin Kotawaringin Barat', 'username' => 'kobar'],
            ['kode' => '6202', 'name' => 'Admin Kotawaringin Timur', 'username' => 'kotim'],
            ['kode' => '6203', 'name' => 'Admin Kapuas', 'username' => 'kapuas'],
            ['kode' => '6204', 'name' => 'Admin Barito Selatan', 'username' => 'barsel'],
            ['kode' => '6205', 'name' => 'Admin Barito Utara', 'username' => 'barut'],
            ['kode' => '6206', 'name' => 'Admin Sukamara', 'username' => 'sukamara'],
            ['kode' => '6207', 'name' => 'Admin Lamandau', 'username' => 'lamandau'],
            ['kode' => '6208', 'name' => 'Admin Seruyan', 'username' => 'seruyan'],
            ['kode' => '6209', 'name' => 'Admin Katingan', 'username' => 'katingan'],
            ['kode' => '6210', 'name' => 'Admin Pulang Pisau', 'username' => 'pulpis'],
            ['kode' => '6211', 'name' => 'Admin Gunung Mas', 'username' => 'gumas'],
            ['kode' => '6212', 'name' => 'Admin Barito Timur', 'username' => 'bartim'],
            ['kode' => '6213', 'name' => 'Admin Murung Raya', 'username' => 'murung'],
            ['kode' => '6271', 'name' => 'Admin Palangka Raya', 'username' => 'palangkaraya'],
        ];

        foreach ($kabkoList as $item) {
            DB::table('users')->insert([
                'name' => $item['name'],
                'username' => $item['username'],
                'email' => $item['username'] . '@bps.go.id',
                'password' => Hash::make('password'),
                'role' => 'kabko',
                'region_id' => $kabkos[$item['kode']]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}