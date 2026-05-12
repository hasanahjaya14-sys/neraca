@extends('layouts.app')

@section('title', $indikator['nama'])
@section('page-title', 'Data')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">
        <a href="{{ route('data.index') }}" class="hover:text-slate-600 transition">Data</a>
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-600 font-medium">{{ $indikator['nama'] }}</span>
    </div>

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $indikator['nama'] }}</h2>
            <p class="text-slate-400 text-sm mt-1">Data per Kabupaten/Kota — Kalimantan Tengah</p>
        </div>
        <a href="{{ route('data.index') }}"
            class="border border-slate-200 hover:bg-slate-50 text-slate-600 px-4 py-2 rounded-xl text-sm transition">
            ← Kembali
        </a>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('data.show', $kode) }}" class="flex flex-wrap items-end gap-3">

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Kabupaten/Kota</label>
                <select name="kabko"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($kabkoList as $k)
                        <option value="{{ $k }}" {{ $selectedKabko == $k ? 'selected' : '' }}>{{ $k }}</option>
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

    {{-- TABEL HIERARKIS --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100">
            <span class="text-sm font-semibold text-slate-700">{{ $selectedKabko }}</span>
            <span class="text-sm text-slate-400 ml-2">— Tahun {{ $selectedTahun }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th
                            class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-[35%]">
                            Variabel
                        </th>
                        @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                {{ $q }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($struktur as $parent => $children)

                        {{-- PARENT ROW --}}
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <td colspan="5" class="px-6 py-2.5 text-xs font-bold text-slate-600 uppercase tracking-wide">
                                {{ $parent }}
                            </td>
                        </tr>

                        {{-- CHILD ROWS --}}
                        @foreach($children as $child)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="px-6 py-3 text-sm text-slate-600 pl-10">
                                    {{ $child }}
                                </td>
                                @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                                    <td class="px-4 py-3 text-sm text-right font-medium text-slate-700">
                                        {{ number_format($dummyData[$parent][$child][$selectedTahun][$q][$selectedKabko] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection