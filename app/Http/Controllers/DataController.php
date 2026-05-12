<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    private $indikator = [
        ['kode' => 'pertanian', 'nama' => 'Pertanian, Kehutanan, dan Perikanan'],
        ['kode' => 'pertambangan', 'nama' => 'Pertambangan dan Penggalian'],
        ['kode' => 'industri', 'nama' => 'Industri Pengolahan'],
        ['kode' => 'listrik', 'nama' => 'Pengadaan Listrik dan Gas'],
        ['kode' => 'air', 'nama' => 'Pengadaan Air dan Pengelolaan Sampah'],
        ['kode' => 'konstruksi', 'nama' => 'Konstruksi'],
        ['kode' => 'perdagangan', 'nama' => 'Perdagangan Besar dan Eceran'],
        ['kode' => 'transportasi', 'nama' => 'Transportasi dan Pergudangan'],
        ['kode' => 'akomodasi', 'nama' => 'Penyediaan Akomodasi dan Makan Minum'],
        ['kode' => 'informasi', 'nama' => 'Informasi dan Komunikasi'],
        ['kode' => 'keuangan', 'nama' => 'Jasa Keuangan'],
        ['kode' => 'realestate', 'nama' => 'Real Estate'],
        ['kode' => 'perusahaan', 'nama' => 'Jasa Perusahaan'],
        ['kode' => 'pemerintahan', 'nama' => 'Administrasi Pemerintahan'],
        ['kode' => 'pendidikan', 'nama' => 'Jasa Pendidikan'],
        ['kode' => 'kesehatan', 'nama' => 'Jasa Kesehatan'],
        ['kode' => 'lainnya', 'nama' => 'Jasa Lainnya'],
        ['kode' => 'penduduk_miskin', 'nama' => 'Jumlah Penduduk Miskin'],
    ];

    private $strukturPemerintahan = [
        'Belanja Pegawai' => [
            'APBD',
            'APBN',
            'APBdes',
            'Belanja Pegawai Berlaku',
            'Indeks Upah',
            'Belanja Pegawai Konstan',
        ],
        'Belanja Modal' => [
            'APBD',
            'APBN',
            'APBdes',
            'Belanja Modal Berlaku',
            'Implisit PMTB',
            'Belanja Modal Konstan',
        ],
        'Penyusutan' => [
            'Penyusutan Berlaku',
            'Penyusutan Konstan',
        ],
        'NTB ADHB' => [
            'NTB ADHB',
            'NTB ADHK',
            'Q to Q',
            'Y on Y',
            'C to C',
            'Implisit',
            'Laju Implisit',
        ],
        'Rilis (juta rp)' => [
            'NTB ADHB',
            'NTB ADHK',
            'Q to Q',
            'Y on Y',
            'C to C',
            'Implisit',
            'Laju Implisit',
        ],
    ];

    private $kabkoList = [
        'Kotawaringin Barat',
        'Kotawaringin Timur',
        'Kapuas',
        'Barito Selatan',
        'Barito Utara',
        'Katingan',
        'Seruyan',
        'Sukamara',
        'Lamandau',
        'Gunung Mas',
        'Pulang Pisau',
        'Murung Raya',
        'Barito Timur',
        'Palangka Raya',
    ];

    private function generateDummyPemerintahan()
    {
        $data = [];
        foreach ($this->strukturPemerintahan as $parent => $children) {
            foreach ($children as $child) {
                foreach (range(2018, 2025) as $tahun) {
                    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q) {
                        $data[$parent][$child][$tahun][$q] = rand(100000000, 9999999999);
                    }
                }
            }
        }
        return $data;
    }

    public function index()
    {
        return view('data.index', ['indikator' => $this->indikator]);
    }

    public function show(Request $request, $kode)
    {
        $indikator = collect($this->indikator)->firstWhere('kode', $kode);

        if (!$indikator)
            abort(404);

        $data = collect();
        $kabkoList = collect();
        $tahunList = collect();
        $struktur = [];
        $dummyData = [];
        $selectedKabko = '';
        $selectedTahun = '';

        if ($kode === 'penduduk_miskin') {
            $selectedKabko = $request->get('kabko', '');
            $selectedTahun = $request->get('tahun', '');

            $query = DB::table('data_dummy')->orderBy('kabko')->orderBy('tahun');
            if ($selectedKabko)
                $query->where('kabko', $selectedKabko);
            if ($selectedTahun)
                $query->where('tahun', $selectedTahun);

            $data = $query->get();
            $kabkoList = DB::table('data_dummy')->distinct()->orderBy('kabko')->pluck('kabko');
            $tahunList = DB::table('data_dummy')->distinct()->orderBy('tahun')->pluck('tahun');

        } elseif ($kode === 'pemerintahan') {
            $struktur = $this->strukturPemerintahan;
            $dummyData = $this->generateDummyPemerintahan();
            $kabkoList = collect($this->kabkoList);
            $tahunList = collect(range(2018, 2025));
            $selectedKabko = $request->get('kabko', $this->kabkoList[0]);
            $selectedTahun = $request->get('tahun', 2025);
        }

        return view('data.show', compact(
            'indikator',
            'data',
            'kabkoList',
            'tahunList',
            'struktur',
            'dummyData',
            'kode',
            'selectedKabko',
            'selectedTahun'
        ));
    }
}