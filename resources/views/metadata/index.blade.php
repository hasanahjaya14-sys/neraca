@extends('layouts.app')

@section('title', 'Metadata')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Metadata PDRB</h1>
        <p class="text-gray-500 text-sm mb-6">
            Informasi referensi setiap variabel pengisian data PDRB, mencakup definisi,
            sumber data, satuan, metode perhitungan, rumus, dan catatan teknis.
        </p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($kategoris as $kategori)
                <a href="{{ route('metadata.show', $kategori->id) }}"
                    class="bg-white rounded-xl shadow p-5 hover:shadow-md hover:bg-purple-50 transition group">
                    <div class="text-xs font-semibold text-purple-400 mb-1">Kategori {{ $kategori->urutan }}</div>
                    <div class="font-semibold text-gray-800 group-hover:text-purple-600 leading-snug">
                        {{ $kategori->name }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection