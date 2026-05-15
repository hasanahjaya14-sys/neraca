<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Region;
use App\Models\SubKategoriValue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::orderBy('urutan')->get();
        $regions = Region::where('tipe', '!=', 'provinsi')->orderBy('kode_bps')->get();
        $tahunMin = config('pdrb.tahun_mulai');
        $tahunMax = config('pdrb.tahun_akhir');

        $kategoriId = $request->input('kategori_id', $kategoris->first()->id);
        $triwulanSekarang = (int) ceil(now()->month / 3);
        $tahunSekarang = (int) now()->year;

        if ($triwulanSekarang === 1) {
            $triwulanAktif = 4;
            $tahunAktif = $tahunSekarang - 1;
        } else {
            $triwulanAktif = $triwulanSekarang - 1;
            $tahunAktif = $tahunSekarang;
        }

        $tahun = (int) $request->input('tahun', $tahunAktif);
        $triwulan = (int) $request->input('triwulan', $triwulanAktif);
        $triwulan = (int) $request->input('triwulan', 1);

        $kategori = Kategori::with([
            'subKategoris.children'
        ])->findOrFail($kategoriId);

        // Kumpulkan semua leaf sub kategori (flat, dengan info parent)
        $rows = collect();
        foreach ($kategori->subKategoris as $sub) {
            if ($sub->children->isNotEmpty()) {
                foreach ($sub->children as $child) {
                    $rows->push([
                        'id' => $child->id,
                        'name' => $child->name,
                        'parent_name' => $sub->name,
                        'is_child' => true,
                    ]);
                }
                $rows->push([
                    'id' => 'total_' . $sub->id,
                    'name' => 'Total ' . $sub->name,
                    'parent_name' => null,
                    'is_total' => true,
                    'child_ids' => $sub->children->pluck('id'),
                ]);
            } else {
                $rows->push([
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'parent_name' => null,
                    'is_child' => false,
                ]);
            }
        }

        // Ambil semua leaf ids
        $leafIds = $rows->where('is_total', '!=', true)->pluck('id');

        // Ambil values: [sub_kategori_id][region_id] => value
        $values = SubKategoriValue::whereIn('sub_kategori_id', $leafIds)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->groupBy('sub_kategori_id')
            ->map(fn($g) => $g->keyBy('region_id'));

        return view('monitoring.index', compact(
            'kategoris',
            'regions',
            'kategori',
            'tahun',
            'triwulan',
            'rows',
            'values',
            'tahunMin',
            'tahunMax'
        ));
    }
}