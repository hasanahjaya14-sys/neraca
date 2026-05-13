<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['kode' => 'A', 'name' => 'Pertanian', 'urutan' => 1],
            ['kode' => 'B', 'name' => 'Pertambangan', 'urutan' => 2],
            ['kode' => 'C', 'name' => 'Industri Pengolahan', 'urutan' => 3],
            ['kode' => 'D', 'name' => 'Listrik', 'urutan' => 4],
            ['kode' => 'E', 'name' => 'Air', 'urutan' => 5],
            ['kode' => 'F', 'name' => 'Konstruksi', 'urutan' => 6],
            ['kode' => 'G', 'name' => 'Perdagangan', 'urutan' => 7],
            ['kode' => 'H', 'name' => 'Transportasi', 'urutan' => 8],
            ['kode' => 'I', 'name' => 'Akomodasi', 'urutan' => 9],
            ['kode' => 'J', 'name' => 'Informasi', 'urutan' => 10],
            ['kode' => 'K', 'name' => 'Keuangan', 'urutan' => 11],
            ['kode' => 'L', 'name' => 'Real Estate', 'urutan' => 12],
            ['kode' => 'M', 'name' => 'Jasa Perusahaan', 'urutan' => 13],
            ['kode' => 'N', 'name' => 'Administrasi Pemerintahan', 'urutan' => 14],
            ['kode' => 'O', 'name' => 'Pendidikan', 'urutan' => 15],
            ['kode' => 'P', 'name' => 'Kesehatan', 'urutan' => 16],
            ['kode' => 'Q', 'name' => 'Jasa Lainnya', 'urutan' => 17],
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategoris')->insert([
                ...$kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}