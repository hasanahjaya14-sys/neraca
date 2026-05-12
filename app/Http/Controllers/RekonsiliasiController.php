<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekonsiliasiController extends Controller
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

    private function generateDummy($triwulan, $tahun)
    {
        $data = [];

        // Data per kabko
        foreach ($this->kabkoList as $kabko) {
            foreach ($this->indikator as $ind) {
                // acak: kadang null (belum isi)
                $data['kabko'][$kabko][$ind['kode']] = rand(0, 4) > 0
                    ? rand(100000000, 9999999999)
                    : null;
            }
        }

        // Data provinsi (acak, tidak selalu = sum kabko)
        foreach ($this->indikator as $ind) {
            $data['provinsi'][$ind['kode']] = rand(1000000000, 99999999999);
        }

        return $data;
    }

    public function index(Request $request)
    {
        $selectedTahun = $request->get('tahun', 2025);
        $selectedTriwulan = $request->get('triwulan', 'Q4');

        $tahunList = range(2018, 2025);
        $triwulanList = ['Q1', 'Q2', 'Q3', 'Q4'];

        $data = $this->generateDummy($selectedTriwulan, $selectedTahun);

        // Hitung status per indikator
        $status = [];
        foreach ($this->indikator as $ind) {
            $kode = $ind['kode'];
            $nilaiKabko = collect($this->kabkoList)->map(fn($k) => $data['kabko'][$k][$kode] ?? null);
            $adaKosong = $nilaiKabko->contains(null);
            $sumKabko = $nilaiKabko->filter()->sum();
            $nilaiProvinsi = $data['provinsi'][$kode];
            $selisih = $sumKabko - $nilaiProvinsi;
            $seimbang = !$adaKosong && abs($selisih) <= ($nilaiProvinsi * 0.001);

            $status[$kode] = [
                'ada_kosong' => $adaKosong,
                'sum_kabko' => $sumKabko,
                'nilai_provinsi' => $nilaiProvinsi,
                'selisih' => $selisih,
                'seimbang' => $seimbang,
            ];
        }

        return view('rekonsiliasi.index', compact(
            'data',
            'status',
            'tahunList',
            'triwulanList',
            'selectedTahun',
            'selectedTriwulan'
        ) + ['kabkoList' => $this->kabkoList, 'indikator' => $this->indikator]);
    }
}