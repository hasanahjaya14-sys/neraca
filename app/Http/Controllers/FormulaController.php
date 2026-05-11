<?php

namespace App\Http\Controllers;

class FormulaController extends Controller
{
    public function index()
    {
        // sementara pakai data dummy dulu
        $variables = [
            ['kode' => 'jumlah_penduduk', 'nama' => 'Jumlah Penduduk', 'nilai' => 10000],
            ['kode' => 'jumlah_rt', 'nama' => 'Jumlah Rumah Tangga', 'nilai' => 2500],
            ['kode' => 'luas_wilayah', 'nama' => 'Luas Wilayah', 'nilai' => 500],
        ];

        return view('formula.index', compact('variables'));
    }
}