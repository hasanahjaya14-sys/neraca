<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Region;
use App\Models\VariableValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengisianController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('urutan')->get();
        return view('pengisian.index', compact('kategoris'));
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $role = $user->role;

        // Tentukan region_id yang akan ditampilkan
        if ($role === 'kabko') {
            $regionId = $user->region_id;
            $canEdit = true;
        } else {
            $regionId = $request->input('region_id', 2);
            $canEdit = false;
        }

        $kategori = Kategori::with(['subKategoris.children'])->findOrFail($id);
        $regions = Region::where('tipe', '!=', 'provinsi')->orderBy('kode_bps')->get();

        // Triwulan aktif = 1 triwulan sebelum sekarang
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

        // Kumpulkan leaf ids
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

        $values = VariableValue::whereIn('sub_kategori_id', $leafIds)
            ->where('region_id', $regionId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('sub_kategori_id');

        // Lewat = sebelum triwulan aktif
        $isPast = $tahun < $tahunAktif ||
            ($tahun === $tahunAktif && $triwulan < $triwulanAktif);

        return view('pengisian.show', compact(
            'kategori',
            'tahun',
            'triwulan',
            'values',
            'isPast',
            'canEdit',
            'regions',
            'regionId',
            'role'
        ));
    }

    public function store(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'kabko') {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $regionId = $user->region_id;
        $tahun = (int) $request->input('tahun');
        $triwulan = (int) $request->input('triwulan');
        $values = $request->input('values', []);

        foreach ($values as $subKategoriId => $value) {
            VariableValue::updateOrCreate(
                [
                    'sub_kategori_id' => $subKategoriId,
                    'region_id' => $regionId,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                ],
                [
                    'value' => is_numeric($value) ? (int) $value : null,
                ]
            );
        }

        return redirect()
            ->route('pengisian.show', [
                'id' => $id,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
            ])
            ->with('success', 'Data berhasil disimpan.');
    }
}