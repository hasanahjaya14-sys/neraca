<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Region;
use App\Models\VariableValue;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::orderBy('urutan')->get();
        $regions = Region::where('tipe', '!=', 'provinsi')->orderBy('kode_bps')->get();

        $kategoriId = $request->input('kategori_id', $kategoris->first()->id);
        $tahun = $request->input('tahun', 2025);
        $triwulan = $request->input('triwulan', 1);

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
                // Tambah baris total parent
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
        $values = VariableValue::whereIn('sub_kategori_id', $leafIds)
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
            'values'
        ));
    }
}