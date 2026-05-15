@extends('layouts.app')

@section('title', $kategori->name)

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('data.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">← Kembali</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $kategori->name }}</h1>
        </div>

        {{-- Filter --}}
        <form method="GET" class="flex gap-3 mb-6">
            <select name="tahun" class="border rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                @foreach (range($tahunMin, $tahunMax) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <select name="triwulan" class="border rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                @foreach ([1, 2, 3, 4] as $tw)
                    <option value="{{ $tw }}" {{ $triwulan == $tw ? 'selected' : '' }}>Q{{ $tw }}</option>
                @endforeach
            </select>
        </form>
        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left sticky left-0 bg-gray-50 min-w-[200px]">Sub Kategori</th>
                        @foreach ($regions as $region)
                            <th class="px-3 py-3 text-right whitespace-nowrap">{{ $region->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($kategori->subKategoris as $sub)

                        @if ($sub->children->isNotEmpty())
                            {{-- Parent row --}}
                            <tr class="bg-gray-50">
                                <td class="px-4 py-3 font-semibold text-gray-700 sticky left-0 bg-gray-50">
                                    {{ $sub->name }}
                                </td>
                                @foreach ($regions as $region)
                                    <td class="px-3 py-3 text-right text-gray-400 italic text-xs">
                                        {{-- Hitung dari children --}}
                                        @php
                                            $total = 0;
                                            foreach ($sub->children as $child) {
                                                $total += $values[$child->id][$region->id]->value ?? 0;
                                            }
                                        @endphp
                                        {{ number_format($total) }}
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Children rows --}}
                            @foreach ($sub->children as $child)
                                <tr class="hover:bg-blue-50">
                                    <td class="px-4 py-3 pl-10 text-gray-600 sticky left-0 bg-white">
                                        — {{ $child->name }}
                                    </td>
                                    @foreach ($regions as $region)
                                        <td class="px-3 py-3 text-right text-gray-700">
                                            {{ number_format($values[$child->id][$region->id]->value ?? 0) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                        @else
                            {{-- Flat row --}}
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-3 font-medium text-gray-700 sticky left-0 bg-white">
                                    {{ $sub->name }}
                                </td>
                                @foreach ($regions as $region)
                                    <td class="px-3 py-3 text-right text-gray-700">
                                        {{ number_format($values[$sub->id][$region->id]->value ?? 0) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endif

                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection