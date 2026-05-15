<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Region;
use App\Models\SubKategoriValue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $tahunMin = config('pdrb.tahun_mulai');
        $tahunMax = config('pdrb.tahun_akhir');
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
        $values = SubKategoriValue::whereIn('sub_kategori_id', $leafIds)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->groupBy('sub_kategori_id')
            ->map(fn($group) => $group->keyBy('region_id'));

        return view('data.show', compact(
            'kategori',
            'regions',
            'tahun',
            'triwulan',
            'values',
            'tahunMin',
            'tahunMax'
        ));
    }
}