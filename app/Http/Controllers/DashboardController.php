<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $kabkoList = DB::table('data_dummy')->distinct()->orderBy('kabko')->pluck('kabko');

        $selectedKabko = $request->get('kabko', $kabkoList->first());

        $tren = DB::table('data_dummy')
            ->where('kabko', $selectedKabko)
            ->orderBy('tahun')
            ->get();

        // Data provinsi = total semua kabko per tahun
        $trenProvinsi = DB::table('data_dummy')
            ->selectRaw('tahun, SUM(nilai) as nilai')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        // Skala tetap: max dari semua data provinsi
        $maxNilai = $trenProvinsi->max('nilai');

        return view('dashboard.index', compact('kabkoList', 'selectedKabko', 'tren', 'trenProvinsi', 'maxNilai'));
    }
}