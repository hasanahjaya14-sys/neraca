@extends('layouts.app')

@section('title', 'Monitoring Kabko')
@section('page-title', 'Monitoring Kabko')

@section('content')

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Monitoring Pengisian Data</h2>
        <p class="text-slate-400 text-sm mt-1">Pantau status pengisian data kabupaten/kota per indikator dan triwulan.</p>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('monitoring.index') }}" class="flex flex-wrap items-end gap-3">

            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-slate-500 mb-1">Indikator</label>
                <select name="indikator"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($indikator as $item)
                        <option value="{{ $item['kode'] }}" {{ $selectedIndikator == $item['kode'] ? 'selected' : '' }}>
                            {{ $item['nama'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tahun</label>
                <select name="tahun"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $selectedTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
                Tampilkan
            </button>

        </form>
    </div>

    {{-- SUMMARY --}}
    @php
        $totalSel = count($kabkoList) * 4;
        $terisi = 0;
        foreach ($data as $kabko => $triwulan) {
            foreach ($triwulan as $q => $nilai) {
                if ($nilai !== null)
                    $terisi++;
            }
        }
        $belumTerisi = $totalSel - $terisi;
        $pct = $totalSel > 0 ? round(($terisi / $totalSel) * 100) : 0;
    @endphp

    <div class="grid grid-cols-3 gap-4 mb-5">

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Total Sel</div>
            <div class="text-2xl font-bold text-slate-800">{{ $totalSel }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ count($kabkoList) }} kabko × 4 triwulan</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Sudah Terisi</div>
            <div class="text-2xl font-bold text-green-600">{{ $terisi }}</div>
            <div class="text-xs text-green-500 mt-1">{{ $pct }}% selesai</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Belum Terisi</div>
            <div class="text-2xl font-bold text-red-500">{{ $belumTerisi }}</div>
            <div class="text-xs text-red-400 mt-1">{{ 100 - $pct }}% belum selesai</div>
        </div>

    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-slate-700">{{ $indikatorAktif['nama'] }}</span>
                <span class="text-sm text-slate-400 ml-2">— {{ $selectedTahun }}</span>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 bg-green-100 border border-green-300 rounded-sm"></span>
                    Sudah terisi
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 bg-red-50 border border-red-200 rounded-sm"></span>
                    Belum terisi
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th
                            class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-[35%]">
                            Kabupaten/Kota
                        </th>
                        @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                            <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                {{ $q }}
                            </th>
                        @endforeach
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kabkoList as $kabko)
                        @php
                            $row = $data[$kabko] ?? [];
                            $terisiRow = collect($row)->filter(fn($v) => $v !== null)->count();
                        @endphp
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition">

                            <td class="px-6 py-3 text-sm font-medium text-slate-700">
                                {{ $kabko }}
                            </td>

                            @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                                @php $nilai = $row[$q] ?? null; @endphp
                                <td class="px-6 py-3 text-sm text-right {{ $nilai !== null ? 'bg-green-50' : 'bg-red-50' }}">
                                    @if($nilai !== null)
                                        <span class="font-medium text-slate-700">
                                            {{ number_format($nilai, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-red-300 text-xs">—</span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-3 text-center">
                                @if($terisiRow === 4)
                                    <span
                                        class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Lengkap
                                    </span>
                                @elseif($terisiRow === 0)
                                    <span
                                        class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Kosong
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                        {{ $terisiRow }}/4
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