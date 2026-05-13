<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetadataSeeder extends Seeder
{
    public function run(): void
    {
        $subKategoris = DB::table('sub_kategoris')->get();

        $satuan = ['Juta Rupiah', 'Ribu Rupiah', 'Persen', 'Indeks'];
        $sumber = ['BPS', 'BPKAD', 'Kementerian Keuangan', 'Bank Indonesia', 'OJK'];

        foreach ($subKategoris as $sub) {
            DB::table('metadatas')->insertOrIgnore([
                'sub_kategori_id' => $sub->id,
                'definisi' => 'Definisi dummy untuk sub kategori ' . $sub->name . '. Merupakan salah satu komponen pembentuk PDRB yang dihitung berdasarkan pendekatan produksi.',
                'sumber_data' => $sumber[array_rand($sumber)],
                'satuan' => $satuan[array_rand($satuan)],
                'metode_perhitungan' => 'Dihitung menggunakan metode ekstrapolasi berdasarkan indeks perkembangan variabel terkait pada periode berjalan terhadap tahun dasar.',
                'rumus' => 'NTB ADHK = NTB ADHB / Indeks Implisit × 100',
                'catatan' => 'Data bersifat sementara dan dapat berubah sesuai hasil rekonsiliasi akhir tahun.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}