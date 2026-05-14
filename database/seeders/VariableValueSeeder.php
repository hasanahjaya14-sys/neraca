<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariableValueSeeder extends Seeder
{
    public function run(): void
    {
        $leafIds = DB::table('sub_kategoris as s')
            ->leftJoin('sub_kategoris as c', 'c.parent_id', '=', 's.id')
            ->whereNull('c.id')
            ->pluck('s.id');

        $regionIds = DB::table('regions')
            ->where('tipe', '!=', 'provinsi')
            ->pluck('id');

        $tahuns = range(2018, 2030);
        $triwulans = [1, 2, 3, 4];

        $batch = [];
        $now = now();

        foreach ($leafIds as $subKategoriId) {
            foreach ($regionIds as $regionId) {
                foreach ($tahuns as $tahun) {
                    foreach ($triwulans as $triwulan) {
                        $batch[] = [
                            'sub_kategori_id' => $subKategoriId,
                            'region_id' => $regionId,
                            'triwulan' => $triwulan,
                            'tahun' => $tahun,
                            'value' => rand(1000000, 999999999),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                        if (count($batch) >= 500) {
                            DB::table('variable_values')->insertOrIgnore($batch);
                            $batch = [];
                        }
                    }
                }
            }
        }

        if (!empty($batch)) {
            DB::table('variable_values')->insertOrIgnore($batch);
        }
    }
}