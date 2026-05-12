@extends('layouts.app')

@section('title', 'Rekonsiliasi')
@section('page-title', 'Rekonsiliasi')

@section('content')

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Rekonsiliasi PDRB</h2>
        <p class="text-slate-400 text-sm mt-1">Pantau keseimbangan angka kabupaten/kota terhadap angka provinsi.</p>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('rekonsiliasi.index') }}" class="flex flex-wrap items-end gap-3">

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tahun</label>
                <select name="tahun"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $selectedTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Triwulan</label>
                <select name="triwulan"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($triwulanList as $q)
                        <option value="{{ $q }}" {{ $selectedTriwulan == $q ? 'selected' : '' }}>{{ $q }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
                Tampilkan
            </button>

        </form>
    </div>

    {{-- SUMMARY CARDS --}}
    @php
        $totalIndikator = count($indikator);
        $jmlSeimbang = collect($status)->filter(fn($s) => $s['seimbang'])->count();
        $jmlKosong = collect($status)->filter(fn($s) => $s['ada_kosong'])->count();
        $jmlTidak = $totalIndikator - $jmlSeimbang - $jmlKosong;
    @endphp

    <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Sudah Seimbang</div>
            <div class="text-2xl font-bold text-green-600">{{ $jmlSeimbang }}</div>
            <div class="text-xs text-slate-400 mt-1">dari {{ $totalIndikator }} indikator</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Data Tidak Lengkap</div>
            <div class="text-2xl font-bold text-yellow-500">{{ $jmlKosong }}</div>
            <div class="text-xs text-slate-400 mt-1">ada kabko belum mengisi</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Tidak Seimbang</div>
            <div class="text-2xl font-bold text-red-500">{{ $jmlTidak }}</div>
            <div class="text-xs text-slate-400 mt-1">perlu rekonsiliasi</div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-4 text-xs text-slate-400">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 bg-green-100 border border-green-300 rounded-sm"></span>
                Seimbang
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 bg-yellow-100 border border-yellow-300 rounded-sm"></span>
                Data belum lengkap
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 bg-red-100 border border-red-300 rounded-sm"></span>
                Tidak seimbang
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th
                            class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-[30%]">
                            Indikator
                        </th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Angka Provinsi
                        </th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Sum Kabko
                        </th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Selisih
                        </th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($indikator as $i => $ind)
                        @php
                            $s = $status[$ind['kode']];
                            $bg = $s['ada_kosong'] ? 'bg-yellow-50' : ($s['seimbang'] ? 'bg-green-50' : 'bg-red-50');
                        @endphp
                        <tr class="border-b border-slate-50 {{ $bg }}">

                            <td class="px-6 py-3 text-sm font-medium text-slate-700">
                                <span class="text-slate-400 mr-2">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}.</span>
                                {{ $ind['nama'] }}
                            </td>

                            <td class="px-4 py-3 text-sm text-right font-medium text-slate-700">
                                {{ number_format($s['nilai_provinsi'], 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-sm text-right font-medium text-slate-700">
                                @if($s['ada_kosong'])
                                    <span class="text-yellow-500 text-xs">Belum lengkap</span>
                                @else
                                    {{ number_format($s['sum_kabko'], 0, ',', '.') }}
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-right font-medium">
                                @if($s['ada_kosong'])
                                    <span class="text-slate-300">—</span>
                                @else
                                    <span
                                        class="{{ abs($s['selisih']) <= ($s['nilai_provinsi'] * 0.001) ? 'text-slate-500' : 'text-red-500' }}">
                                        {{ $s['selisih'] >= 0 ? '+' : '' }}{{ number_format($s['selisih'], 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if($s['ada_kosong'])
                                    <span
                                        class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                        Data Kosong
                                    </span>
                                @elseif($s['seimbang'])
                                    <span
                                        class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Seimbang
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Tidak Seimbang
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection