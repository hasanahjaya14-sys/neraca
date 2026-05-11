<?php

namespace App\Http\Controllers;

class RekonsiliasiController extends Controller
{
    public function index()
    {
        $kabkos = [
            ['nama' => 'Kapuas', 'nilai' => 5.1000],
            ['nama' => 'Pulang Pisau', 'nilai' => 5.3500],
            ['nama' => 'Barito Selatan', 'nilai' => 5.1800],
        ];

        return view('rekonsiliasi.index', compact('kabkos'));
    }
}