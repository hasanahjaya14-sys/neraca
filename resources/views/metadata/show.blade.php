@extends('layouts.app')

@section('title', 'Metadata - ' . $kategori->name)

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('metadata.index') }}" class="text-purple-500 hover:text-purple-700 text-sm">← Kembali</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $kategori->name }}</h1>
        </div>

        {{-- Sub Kategori --}}
        <div class="space-y-6">
            @foreach ($kategori->subKategoris as $sub)

                @php
                    $items = $sub->children->isNotEmpty() ? $sub->children : collect([$sub]);
                @endphp

                {{-- Group header kalau punya children --}}
                @if ($sub->children->isNotEmpty())
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wide mt-4">
                        {{ $sub->name }}
                    </div>
                @endif

                @foreach ($items as $item)
                    <div class="bg-white rounded-xl shadow p-5">
                        <h2 class="text-base font-semibold text-gray-800 mb-4">
                            {{ $item->name }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Definisi</div>
                                <p class="text-gray-700">{{ $item->metadata->definisi ?? '-' }}</p>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Sumber Data</div>
                                <p class="text-gray-700">{{ $item->metadata->sumber_data ?? '-' }}</p>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Satuan</div>
                                <p class="text-gray-700">{{ $item->metadata->satuan ?? '-' }}</p>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Metode Perhitungan</div>
                                <p class="text-gray-700">{{ $item->metadata->metode_perhitungan ?? '-' }}</p>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Rumus</div>
                                <p class="text-gray-700 font-mono bg-gray-50 px-3 py-2 rounded">
                                    {{ $item->metadata->rumus ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Catatan</div>
                                <p class="text-gray-700">{{ $item->metadata->catatan ?? '-' }}</p>
                            </div>

                        </div>
                    </div>
                @endforeach

            @endforeach
        </div>

    </div>
@endsection