<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = DB::table('kategoris')->orderBy('urutan')->get()->keyBy('urutan');

        // ── KATEGORI 1: Pertanian ──────────────────────────────────────────
        $k1 = $kategoris[1]->id;

        $parent11 = DB::table('sub_kategoris')->insertGetId([
            'kategori_id' => $k1,
            'parent_id' => null,
            'name' => 'Tanaman dan Hortikultura',
            'urutan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $children1 = [
            'Tanaman Pangan',
            'Tanaman Hortikultura Semusim',
            'Perkebunan Semusim',
            'Tanaman Hortikultura Tahunan',
            'Perkebunan Tahunan',
            'Peternakan',
            'Jasa Pertanian dan Perburuan',
        ];
        foreach ($children1 as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k1,
                'parent_id' => $parent11,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ([
            ['Kehutanan dan Penebangan Kayu', 2],
            ['Perikanan', 3],
        ] as [$name, $urutan]) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k1,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $urutan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 2: Pertambangan ──────────────────────────────────────
        $k2 = $kategoris[2]->id;
        foreach ([
            'Pertambangan Minyak dan Gas Bumi',
            'Pertambangan Batubara dan Lignit',
            'Pertambangan Bijih Logam',
            'Penggalian',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k2,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 3: Industri Pengolahan ───────────────────────────────
        $k3 = $kategoris[3]->id;
        foreach ([
            'Industri Makanan dan Minuman',
            'Industri Tekstil dan Pakaian Jadi',
            'Industri Kayu, Barang dari Kayu dan Gabus dan Barang Anyaman dari Bambu, Rotan dan Sejenisnya',
            'Industri Kertas dan Barang dari Kertas, Percetakan dan Reproduksi Media Rekaman',
            'Industri Kimia, Farmasi dan Obat Tradisional',
            'Industri Barang Galian bukan Logam',
            'Industri Alat Angkutan',
            'Industri Furnitur',
            'Industri Pengolahan Lainnya',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k3,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 4: Listrik ───────────────────────────────────────────
        $k4 = $kategoris[4]->id;
        foreach ([
            'Pengadaan Listrik',
            'Pengadaan Gas dan Produksi Es',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k4,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 5: Air ───────────────────────────────────────────────
        $k5 = $kategoris[5]->id;
        foreach ([
            'Indeks Produksi',
            'Indeks Harga',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k5,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 6: Konstruksi ────────────────────────────────────────
        $k6 = $kategoris[6]->id;
        foreach ([
            'Indeks Produksi',
            'Indeks Harga',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k6,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 7: Perdagangan ───────────────────────────────────────
        $k7 = $kategoris[7]->id;
        foreach ([
            'Perdagangan, Reparasi dan Perawatan Mobil dan Sepeda Motor',
            'Perdagangan Besar dan Eceran, Bukan Mobil dan Sepeda Motor',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k7,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 8: Transportasi ──────────────────────────────────────
        $k8 = $kategoris[8]->id;
        foreach ([
            'Angkutan Darat',
            'Angkutan Laut',
            'Angkutan Sungai dan Penyebrangan',
            'Angkutan Udara',
            'Pergudangan dan Jasa Penunjang Angkutan, Pos dan Kurir',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k8,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 9: Akomodasi ─────────────────────────────────────────
        $k9 = $kategoris[9]->id;
        foreach ([
            'Penyediaan Akomodasi',
            'Penyediaan Makan Minum',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k9,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 10: Informasi ────────────────────────────────────────
        $k10 = $kategoris[10]->id;
        foreach (['Indeks Produksi', 'Indeks Harga'] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k10,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 11: Keuangan ─────────────────────────────────────────
        $k11 = $kategoris[11]->id;
        foreach ([
            'Jasa Perantara Keuangan',
            'Asuransi dan Dana Pensiun',
            'Jasa Keuangan Lainnya',
            'Jasa Penunjang Keuangan',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k11,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 12: Real Estate ──────────────────────────────────────
        $k12 = $kategoris[12]->id;
        foreach (['Indeks Produksi', 'Indeks Harga'] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k12,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 13: Jasa Perusahaan ─────────────────────────────────
        $k13 = $kategoris[13]->id;
        foreach ([
            'Indeks Produksi',
            'Arsitektur',
            'Biro/Agen Perjalanan',
            'Indeks Harga',
            'Treatment Air Limbah',
            'Pengumpulan Limbah',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k13,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 14: Administrasi Pemerintahan ────────────────────────
        $k14 = $kategoris[14]->id;

        $parentBP = DB::table('sub_kategoris')->insertGetId([
            'kategori_id' => $k14,
            'parent_id' => null,
            'name' => 'Belanja Pegawai',
            'urutan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach ([
            'APBD',
            'APBN',
            'APBdes',
            'Belanja Pegawai Berlaku',
            'Indeks Upah',
            'Belanja Pegawai Konstan',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k14,
                'parent_id' => $parentBP,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $parentBM = DB::table('sub_kategoris')->insertGetId([
            'kategori_id' => $k14,
            'parent_id' => null,
            'name' => 'Belanja Modal',
            'urutan' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach ([
            'APBD',
            'APBN',
            'APBdes',
            'Belanja Modal Berlaku',
            'Implisit PMTB',
            'Belanja Modal Konstan',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k14,
                'parent_id' => $parentBM,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ([
            ['Penyusutan Berlaku', 3],
            ['Penyusutan Konstan', 4],
        ] as [$name, $urutan]) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k14,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $urutan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 15: Pendidikan ───────────────────────────────────────
        $k15 = $kategoris[15]->id;
        foreach ([
            'Pendidikan Pemerintah',
            'Pendidikan Swasta',
            'Ringkasan NTB',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k15,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 16: Kesehatan ────────────────────────────────────────
        $k16 = $kategoris[16]->id;
        foreach ([
            'Kesehatan Pemerintah',
            'Kesehatan Swasta',
            'Ringkasan NTB',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k16,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── KATEGORI 17: Jasa Lainnya ─────────────────────────────────────
        $k17 = $kategoris[17]->id;
        foreach ([
            'Rekreasi',
            'Reparasi',
        ] as $i => $name) {
            DB::table('sub_kategoris')->insert([
                'kategori_id' => $k17,
                'parent_id' => null,
                'name' => $name,
                'urutan' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}