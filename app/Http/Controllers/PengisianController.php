<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Kategori;
use App\Models\Region;
use App\Models\SubKategori;
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

        $allSubKategoriIds = collect();
        foreach ($kategori->subKategoris as $sub) {
            $allSubKategoriIds->push($sub->id);
            foreach ($sub->children as $child) {
                $allSubKategoriIds->push($child->id);
            }
        }

        $subSubKategoris = SubSubKategori::whereIn('sub_kategori_id', $allSubKategoriIds)
            ->where('region_id', $regionId)
            ->orderBy('urutan')
            ->get()
            ->groupBy('sub_kategori_id');

        $subKategoriValues = SubKategoriValue::whereIn('sub_kategori_id', $allSubKategoriIds)
            ->where('region_id', $regionId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('sub_kategori_id');

        $isPast = $tahun < $tahunAktif ||
            ($tahun === $tahunAktif && $triwulan < $triwulanAktif);

        return view('pengisian.show', compact(
            'kategori',
            'tahun',
            'triwulan',
            'subKategoriValues',
            'subSubKategoris',
            'isPast',
            'canEdit',
            'regions',
            'regionId',
            'role',
            'tahunMin',
            'tahunMax'
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

        foreach ($request->input('sub_kategori_values', []) as $subKategoriId => $value) {
            SubKategoriValue::updateOrCreate(
                [
                    'sub_kategori_id' => $subKategoriId,
                    'region_id' => $regionId,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                ],
                ['value' => is_numeric($value) ? (float) $value : null]
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

    public function showSub(Request $request, $id, $subKategoriId)
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

        $kategori = Kategori::findOrFail($id);
        $subKategori = SubKategori::findOrFail($subKategoriId);

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

        $subSubKategoris = SubSubKategori::where('sub_kategori_id', $subKategoriId)
            ->where('region_id', $regionId)
            ->orderBy('urutan')
            ->get();

        $subSubValues = SubSubKategoriValue::whereIn('sub_sub_kategori_id', $subSubKategoris->pluck('id'))
            ->where('region_id', $regionId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('sub_sub_kategori_id');

        $formula = Formula::where('region_id', $regionId)
            ->where('subject_type', 'sub_kategori')
            ->where('subject_id', $subKategoriId)
            ->first();

        $isPast = $tahun < $tahunAktif ||
            ($tahun === $tahunAktif && $triwulan < $triwulanAktif);

        return view('pengisian.sub', compact(
            'kategori',
            'subKategori',
            'subSubKategoris',
            'subSubValues',
            'formula',
            'tahun',
            'triwulan',
            'tahunMin',
            'tahunMax',
            'regionId',
            'role',
            'canEdit',
            'isPast'
        ));
    }

    public function storeSub(Request $request, $id, $subKategoriId)
    {
        $user = Auth::user();

        if ($user->role !== 'kabko') {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $regionId = $user->region_id;
        $tahun = (int) $request->input('tahun');
        $triwulan = (int) $request->input('triwulan');

        DB::transaction(function () use ($request, $regionId, $tahun, $triwulan, $subKategoriId) {

            // 1. Simpan sub-sub kategori baru
            // 1. Simpan sub-sub kategori baru
            $urutan = SubSubKategori::where('sub_kategori_id', $subKategoriId)
                ->where('region_id', $regionId)
                ->max('urutan') ?? 0;

            foreach ($request->input('new_sub_sub', []) as $item) {
                $name = trim($item['name'] ?? '');
                if (!empty($name)) {
                    $newSub = SubSubKategori::firstOrCreate([
                        'sub_kategori_id' => $subKategoriId,
                        'region_id' => $regionId,
                        'name' => $name,
                    ], ['urutan' => ++$urutan]);

                    // Simpan nilai jika ada
                    if (!empty($item['value']) && is_numeric($item['value'])) {
                        SubSubKategoriValue::updateOrCreate(
                            [
                                'sub_sub_kategori_id' => $newSub->id,
                                'region_id' => $regionId,
                                'tahun' => $tahun,
                                'triwulan' => $triwulan,
                            ],
                            ['value' => (float) $item['value']]
                        );
                    }
                }
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

            // 3. Simpan formula
            $formulaString = $request->input('formula_string', '');
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

            // 4. Hitung hasil formula dan simpan ke sub_kategori_values
            if (!empty(trim($formulaString))) {
                $allSubSubs = SubSubKategori::where('sub_kategori_id', $subKategoriId)
                    ->where('region_id', $regionId)
                    ->get();

                $subSubVals = SubSubKategoriValue::whereIn('sub_sub_kategori_id', $allSubSubs->pluck('id'))
                    ->where('region_id', $regionId)
                    ->where('tahun', $tahun)
                    ->where('triwulan', $triwulan)
                    ->get()
                    ->keyBy('sub_sub_kategori_id');

                $expr = $formulaString;
                $names = $allSubSubs->sortByDesc(fn($s) => strlen($s->name));
                foreach ($names as $ssub) {
                    $val = $subSubVals[$ssub->id]->value ?? 0;
                    $expr = str_replace($ssub->name, $val, $expr);
                }

                try {
                    $result = eval ('return ' . $expr . ';');
                    if (is_numeric($result)) {
                        SubKategoriValue::updateOrCreate(
                            [
                                'sub_kategori_id' => $subKategoriId,
                                'region_id' => $regionId,
                                'tahun' => $tahun,
                                'triwulan' => $triwulan,
                            ],
                            ['value' => (float) $result]
                        );
                    }
                } catch (\Throwable $e) {
                    // Formula error, skip
                }
            }
        });

        return redirect()
            ->route('pengisian.sub', [
                'id' => $id,
                'subKategoriId' => $subKategoriId,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
            ])
            ->with('success', 'Data berhasil disimpan.');
    }

    public function destroySubSub(Request $request, $subSubId)
    {
        $user = Auth::user();

        if ($user->role !== 'kabko') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $subSub = SubSubKategori::where('id', $subSubId)
            ->where('region_id', $user->region_id)
            ->firstOrFail();

        $subKategoriId = $subSub->sub_kategori_id;
        $regionId = $user->region_id;

        $subSub->delete();

        // Cek apakah masih ada sub-sub lain
        $sisaSubSub = SubSubKategori::where('sub_kategori_id', $subKategoriId)
            ->where('region_id', $regionId)
            ->count();

        // Kalau sudah tidak ada sub-sub, hapus formula juga
        if ($sisaSubSub === 0) {
            Formula::where('region_id', $regionId)
                ->where('subject_type', 'sub_kategori')
                ->where('subject_id', $subKategoriId)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'sisa_sub_sub' => $sisaSubSub,
        ]);
    }

    public function storeFormula(Request $request, $id, $subKategoriId)
    {
        $user = Auth::user();

        if ($user->role !== 'kabko') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $regionId = $user->region_id;
        $formulaString = $request->input('formula_string', '');

        if (empty(trim($formulaString))) {
            Formula::where('region_id', $regionId)
                ->where('subject_type', 'sub_kategori')
                ->where('subject_id', $subKategoriId)
                ->delete();

            return response()->json(['success' => true, 'deleted' => true]);
        }

        Formula::updateOrCreate(
            [
                'region_id' => $regionId,
                'subject_type' => 'sub_kategori',
                'subject_id' => $subKategoriId,
            ],
            ['formula_string' => trim($formulaString)]
        );

        // Hitung dan simpan hasil ke sub_kategori_values
        $tahun = $request->input('tahun');
        $triwulan = $request->input('triwulan');

        $allSubSubs = SubSubKategori::where('sub_kategori_id', $subKategoriId)
            ->where('region_id', $regionId)
            ->get();

        $subSubVals = SubSubKategoriValue::whereIn('sub_sub_kategori_id', $allSubSubs->pluck('id'))
            ->where('region_id', $regionId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('sub_sub_kategori_id');

        $expr = $formulaString;
        $names = $allSubSubs->sortByDesc(fn($s) => strlen($s->name));
        foreach ($names as $ssub) {
            $val = $subSubVals[$ssub->id]->value ?? 0;
            $expr = str_replace($ssub->name, $val, $expr);
        }

        try {
            $result = eval ('return ' . $expr . ';');
            if (is_numeric($result)) {
                SubKategoriValue::updateOrCreate(
                    [
                        'sub_kategori_id' => $subKategoriId,
                        'region_id' => $regionId,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ],
                    ['value' => (float) $result]
                );
            }
        } catch (\Throwable $e) {
            // Formula error, skip
        }

        return response()->json(['success' => true]);
    }
}