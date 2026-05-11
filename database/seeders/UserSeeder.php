<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        // Akun Provinsi
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => "Provinsi Kalimantan Tengah $i",
                'username' => "kalteng$i",
                'email' => "kalteng$i@bps.go.id",
                'password' => Hash::make('password'),
                'role' => 'provinsi',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Kabupaten/Kota di Kalimantan Tengah
        $kabkos = [
            'Kotawaringin Barat' => 'kobar',
            'Kotawaringin Timur' => 'kotim',
            'Kapuas' => 'kapuas',
            'Barito Selatan' => 'barsel',
            'Barito Utara' => 'barut',
            'Katingan' => 'katingan',
            'Seruyan' => 'seruyan',
            'Sukamara' => 'sukamara',
            'Lamandau' => 'lamandau',
            'Gunung Mas' => 'gumas',
            'Pulang Pisau' => 'pulpis',
            'Murung Raya' => 'mura',
            'Barito Timur' => 'bartim',
            'Palangka Raya' => 'palangkaraya',
        ];

        foreach ($kabkos as $nama => $kode) {
            for ($i = 1; $i <= 2; $i++) {
                $users[] = [
                    'name' => "$nama $i",
                    'username' => "$kode$i",
                    'email' => "$kode$i@bps.go.id",
                    'password' => Hash::make('password'),
                    'role' => 'kabko',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('users')->insert($users);
    }
}