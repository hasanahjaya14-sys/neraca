@extends('layouts.app')

@section('title', 'Data')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Data PDRB</h1>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($kategoris as $kategori)
                <a href="{{ route('data.show', $kategori->id) }}"
                    class="bg-white rounded-xl shadow p-5 hover:shadow-md hover:bg-blue-50 transition group">
                    <div class="text-xs font-semibold text-blue-400 mb-1">Kategori {{ $kategori->urutan }}</div>
                    <div class="font-semibold text-gray-800 group-hover:text-blue-600 leading-snug">
                        {{ $kategori->name }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection