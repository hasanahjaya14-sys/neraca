@extends('layouts.app')

@section('title', 'Data')
@section('page-title', 'Data')

@section('content')

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Data Indikator</h2>
        <p class="text-slate-400 text-sm mt-1">Pilih indikator untuk melihat data detail per kabupaten/kota.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        @foreach($indikator as $i => $item)

            <a href="{{ route('data.show', $item['kode']) }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md hover:-translate-y-0.5 transition-all group">

                <div class="flex items-start gap-4">

                    <div
                        class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold
                        {{ $item['kode'] === 'penduduk_miskin' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $i + 1 <= 17 ? str_pad($i + 1, 2, '0', STR_PAD_LEFT) : '★' }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-700 group-hover:text-blue-600 transition leading-snug">
                            {{ $item['nama'] }}
                        </div>
                        <div class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                            @if($item['kode'] === 'penduduk_miskin')
                                <span
                                    class="inline-flex items-center gap-1 bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Data tersedia
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 bg-slate-50 text-slate-400 px-2 py-0.5 rounded-full text-xs">
                                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                    Belum ada data
                                </span>
                            @endif
                        </div>
                    </div>

                    <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-400 transition flex-shrink-0 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>

                </div>

            </a>

        @endforeach

    </div>

@endsection