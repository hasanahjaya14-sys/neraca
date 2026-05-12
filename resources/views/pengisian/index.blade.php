@extends('layouts.app')

@section('title', 'Pengisian Data')
@section('page-title', 'Pengisian Data')

@section('content')

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Pengisian Data</h2>
        <p class="text-slate-400 text-sm mt-1">Pilih indikator untuk mengisi data kabupaten/kota.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        @foreach($indikator as $i => $item)

            <a href="{{ route('pengisian.show', $item['kode']) }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md hover:-translate-y-0.5 transition-all group">

                <div class="flex items-start gap-4">

                    <div
                        class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold bg-slate-100 text-slate-500">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-700 group-hover:text-blue-600 transition leading-snug">
                            {{ $item['nama'] }}
                        </div>
                        <div class="text-xs text-slate-400 mt-1.5">
                            <span
                                class="inline-flex items-center gap-1 bg-slate-50 text-slate-400 px-2 py-0.5 rounded-full text-xs">
                                Klik untuk mengisi data
                            </span>
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