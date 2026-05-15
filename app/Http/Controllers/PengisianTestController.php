<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Kategori;
use App\Models\Region;
use App\Models\SubKategoriValue;
use App\Models\SubSubKategori;
use App\Models\SubSubKategoriValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if ($role === 'kabko') {
            $regionId = $user->region_id;
            $canEdit = true;
        } else {
            $regionId = $request->input('region_id', 2);
            $canEdit = false;
        }

        $kategori = Kategori::with(['subKategoris.children'])->findOrFail($id);
        $regions = Region::where('tipe', '!=', 'provinsi')->orderBy('kode_bps')->get();

        $triwulanSekarang = (int) ceil(now()->month / 3);
        $tahunSekarang = (int) now()->year;

        if ($triwulanSekarang === 1) {
            $triwulanAktif = 4;
            $tahunAktif = $tahunSekarang - 1;
        } else {
            $triwulanAktif = $triwulanSekarang - 1;
            $tahunAktif = $tahunSekarang;
        }

        $tahunMin = config('pdrb.tahun_mulai');
        $tahunMax = config('pdrb.tahun_akhir');
        $tahun = (int) $request->input('tahun', $tahunAktif);
        $triwulan = (int) $request->input('triwulan', $triwulanAktif);

        // Kumpulkan semua sub kategori leaf (tidak punya children di sub_kategoris)
        $leafIds = collect();
        foreach ($kategori->subKategoris as $sub) {
            if ($sub->children->isEmpty()) {
                $leafIds->push($sub->id);
            }
        }

        // Ambil nilai sub kategori
        $subKategoriValues = SubKategoriValue::whereIn('sub_kategori_id', $leafIds)
            ->where('region_id', $regionId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('sub_kategori_id');

        // Ambil sub-sub kategori per sub kategori untuk region ini
        $allSubKategoriIds = $kategori->subKategoris->pluck('id');
        $subSubKategoris = SubSubKategori::whereIn('sub_kategori_id', $allSubKategoriIds)
            ->where('region_id', $regionId)
            ->orderBy('urutan')
            ->get()
            ->groupBy('sub_kategori_id');

        // Ambil nilai sub-sub kategori
        $subSubIds = $subSubKategoris->flatten()->pluck('id');
        $subSubValues = SubSubKategoriValue::whereIn('sub_sub_kategori_id', $subSubIds)
            ->where('region_id', $regionId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('sub_sub_kategori_id');

        // Ambil formula per sub kategori untuk region ini
        $formulas = Formula::where('region_id', $regionId)
            ->where('subject_type', 'sub_kategori')
            ->whereIn('subject_id', $allSubKategoriIds)
            ->get()
            ->keyBy('subject_id');

        $isPast = $tahun < $tahunAktif ||
            ($tahun === $tahunAktif && $triwulan < $triwulanAktif);

        return view('pengisian.show', compact(
            'kategori',
            'tahun',
            'triwulan',
            'regions',
            'regionId',
            'role',
            'canEdit',
            'isPast',
            'tahunMin',
            'tahunMax',
            'subKategoriValues',
            'subSubKategoris',
            'subSubValues',
            'formulas'
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

        DB::transaction(function () use ($request, $regionId, $tahun, $triwulan) {

            // 1. Simpan nilai sub kategori (yang tidak punya sub-sub)
            foreach ($request->input('sub_kategori_values', []) as $subKategoriId => $value) {
                SubKategoriValue::updateOrCreate(
                    [
                        'sub_kategori_id' => $subKategoriId,
                        'region_id' => $regionId,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ],
                    ['value' => is_numeric($value) ? (float) $value : null,]
                );
            }

            // 2. Simpan nilai sub-sub kategori
            foreach ($request->input('sub_sub_values', []) as $subSubId => $value) {
                SubSubKategoriValue::updateOrCreate(
                    [
                        'sub_sub_kategori_id' => $subSubId,
                        'region_id' => $regionId,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ],
                    ['value' => is_numeric($value) ? (float) $value : null]
                );
            }

            // 3. Simpan sub-sub kategori baru (yang ditambah user)
            foreach ($request->input('new_sub_sub', []) as $subKategoriId => $names) {
                $urutan = SubSubKategori::where('sub_kategori_id', $subKategoriId)
                    ->where('region_id', $regionId)
                    ->max('urutan') ?? 0;

                foreach ($names as $name) {
                    if (!empty(trim($name))) {
                        SubSubKategori::firstOrCreate([
                            'sub_kategori_id' => $subKategoriId,
                            'region_id' => $regionId,
                            'name' => trim($name),
                        ], ['urutan' => ++$urutan]);
                    }
                }
            }

            // 4. Simpan formula
            foreach ($request->input('formulas', []) as $subKategoriId => $formulaString) {
                if (!empty(trim($formulaString))) {
                    Formula::updateOrCreate(
                        [
                            'region_id' => $regionId,
                            'subject_type' => 'sub_kategori',
                            'subject_id' => $subKategoriId,
                        ],
                        ['formula_string' => trim($formulaString)]
                    );
                }
            }
        });

        return redirect()
            ->route('pengisian.show', [
                'id' => $id,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
            ])
            ->with('success', 'Data berhasil disimpan.');
    }
}