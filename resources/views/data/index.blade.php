@extends('layouts.app')

@section('title', 'Data')
@section('page-title', 'Data')

@section('content')

    {{-- HEADER & FILTER --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">

            <div>
                <h2 class="text-xl font-bold text-slate-800">Data Mentah</h2>
                <p class="text-slate-400 text-sm mt-1">Jumlah Penduduk Miskin per Kabupaten/Kota</p>
            </div>

            <form method="GET" action="{{ route('data.index') }}" class="flex flex-wrap items-end gap-3">

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Kabupaten/Kota</label>
                    <select name="kabko"
                        class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kabko</option>
                        @foreach($kabkoList as $k)
                            <option value="{{ $k }}" {{ $k == $kabko ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Tahun</label>
                    <select name="tahun"
                        class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
                    Filter
                </button>

                @if($kabko || $tahun)
                    <a href="{{ route('data.index') }}"
                        class="border border-slate-200 hover:bg-slate-50 text-slate-600 px-5 py-2 rounded-xl text-sm font-medium transition">
                        Reset
                    </a>
                @endif

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100">
            <span class="text-sm text-slate-500">
                Menampilkan <span class="font-semibold text-slate-700">{{ $data->count() }}</span> data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">No</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Kabupaten/Kota</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Variabel</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Tahun
                        </th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nilai
                        </th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Satuan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                            <td class="px-6 py-3 text-sm text-slate-400">{{ $i + 1 }}</td>
                            <td class="px-6 py-3 text-sm font-medium text-slate-700">{{ $row->kabko }}</td>
                            <td class="px-6 py-3 text-sm text-slate-500">{{ str_replace('_', ' ', $row->variabel) }}</td>
                            <td class="px-6 py-3 text-sm text-slate-600 text-center">{{ $row->tahun }}</td>
                            <td class="px-6 py-3 text-sm text-slate-700 text-right font-medium">
                                {{ number_format($row->nilai, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-400">{{ $row->satuan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection