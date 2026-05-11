<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $kabko = $request->get('kabko', '');
        $tahun = $request->get('tahun', '');

        $query = DB::table('data_dummy')
            ->orderBy('kabko')
            ->orderBy('tahun');

        if ($kabko) {
            $query->where('kabko', $kabko);
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $data = $query->get();

        $kabkoList = DB::table('data_dummy')->distinct()->orderBy('kabko')->pluck('kabko');
        $tahunList = DB::table('data_dummy')->distinct()->orderBy('tahun')->pluck('tahun');

        return view('data.index', compact('data', 'kabko', 'tahun', 'kabkoList', 'tahunList'));
    }
}