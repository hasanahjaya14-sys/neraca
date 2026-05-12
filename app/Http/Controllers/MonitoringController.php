<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
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

    private function generateDummy($indikator, $tahun)
    {
        $data = [];
        foreach ($this->kabkoList as $kabko) {
            foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q) {
                // acak: kadang null (belum isi), kadang ada nilai
                $data[$kabko][$q] = rand(0, 3) > 0
                    ? rand(100000000, 9999999999)
                    : null;
            }
        }
        return $data;
    }

    public function index(Request $request)
    {
        $selectedIndikator = $request->get('indikator', $this->indikator[0]['kode']);
        $selectedTahun = $request->get('tahun', 2025);

        $indikatorAktif = collect($this->indikator)->firstWhere('kode', $selectedIndikator);
        $data = $this->generateDummy($selectedIndikator, $selectedTahun);
        $tahunList = range(2018, 2025);

        return view('monitoring.index', [
            'indikator' => $this->indikator,
            'kabkoList' => $this->kabkoList,
            'selectedIndikator' => $selectedIndikator,
            'selectedTahun' => $selectedTahun,
            'indikatorAktif' => $indikatorAktif,
            'data' => $data,
            'tahunList' => $tahunList,
        ]);
    }
}