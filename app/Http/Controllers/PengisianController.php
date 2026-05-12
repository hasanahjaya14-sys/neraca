<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengisianController extends Controller
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

    private function getCurrentTriwulan()
    {
        $month = (int) date('n');
        if ($month <= 3)
            return ['tahun' => (int) date('Y'), 'q' => 1];
        if ($month <= 6)
            return ['tahun' => (int) date('Y'), 'q' => 2];
        if ($month <= 9)
            return ['tahun' => (int) date('Y'), 'q' => 3];
        return ['tahun' => (int) date('Y'), 'q' => 4];
    }

    private function isLocked($tahun, $q)
    {
        $current = $this->getCurrentTriwulan();
        $qNum = (int) str_replace('Q', '', $q);

        if ($tahun < $current['tahun'])
            return true;
        if ($tahun == $current['tahun'] && $qNum < $current['q'])
            return true;
        return false;
    }

    private function generateDummy($struktur)
    {
        $data = [];
        foreach ($struktur as $parent => $children) {
            foreach ($children as $child) {
                foreach (range(2018, 2026) as $tahun) {
                    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q) {
                        $data[$parent][$child][$tahun][$q] = rand(0, 3) > 0
                            ? rand(100000000, 9999999999)
                            : null;
                    }
                }
            }
        }
        return $data;
    }

    public function index()
    {
        return view('pengisian.index', ['indikator' => $this->indikator]);
    }

    public function show(Request $request, $kode)
    {
        $indikator = collect($this->indikator)->firstWhere('kode', $kode);
        if (!$indikator)
            abort(404);

        // Untuk sekarang semua indikator pakai struktur pemerintahan sebagai dummy
        $struktur = $this->strukturPemerintahan;
        $dummyData = $this->generateDummy($struktur);

        $tahunList = range(2018, 2026);
        $selectedTahun = $request->get('tahun', (int) date('Y'));
        $current = $this->getCurrentTriwulan();

        // Tentukan status lock per triwulan
        $lockStatus = [];
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q) {
            $lockStatus[$q] = $this->isLocked($selectedTahun, $q);
        }

        return view('pengisian.show', compact(
            'indikator',
            'struktur',
            'dummyData',
            'tahunList',
            'selectedTahun',
            'lockStatus',
            'current',
            'kode'
        ));
    }
}