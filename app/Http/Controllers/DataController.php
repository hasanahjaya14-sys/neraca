<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Region;
use App\Models\VariableValue;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('urutan')->get();
        return view('data.index', compact('kategoris'));
    }

    public function show(Request $request, $id)
    {
        $kategori = Kategori::with([
            'subKategoris.children'
        ])->findOrFail($id);

        $regions = Region::where('tipe', '!=', 'provinsi')->orderBy('kode_bps')->get();
        $tahun = $request->input('tahun', 2025);
        $triwulan = $request->input('triwulan', 1);

        // Ambil semua leaf sub_kategori_id dalam kategori ini
        $leafIds = collect();
        foreach ($kategori->subKategoris as $sub) {
            if ($sub->children->isNotEmpty()) {
                foreach ($sub->children as $child) {
                    $leafIds->push($child->id);
                }
            } else {
                $leafIds->push($sub->id);
            }
        }

        // Ambil values: [sub_kategori_id][region_id] => value
        $values = VariableValue::whereIn('sub_kategori_id', $leafIds)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->groupBy('sub_kategori_id')
            ->map(fn($group) => $group->keyBy('region_id'));

        return view('data.show', compact('kategori', 'regions', 'tahun', 'triwulan', 'values'));
    }
}