@extends('layouts.app')

@section('title', 'Monitoring')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">Monitoring Kabupaten/Kota</h1>

        {{-- Filter --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-6">
            <select name="kategori_id" class="border rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                @foreach ($kategoris as $k)
                    <option value="{{ $k->id }}" {{ $kategori->id == $k->id ? 'selected' : '' }}>
                        {{ $k->urutan }}. {{ $k->name }}
                    </option>
                @endforeach
            </select>

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
                        <th class="px-4 py-3 text-left sticky left-0 bg-gray-50 min-w-[220px]">Sub Kategori</th>
                        @foreach ($regions as $region)
                            <th class="px-3 py-3 text-right whitespace-nowrap">{{ $region->name }}</th>
                        @endforeach
                        <th class="px-3 py-3 text-right whitespace-nowrap bg-blue-50 text-blue-600">Total Provinsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    @php $grandTotal = array_fill_keys($regions->pluck('id')->toArray(), 0); @endphp

                    @foreach ($rows as $row)

                        @if (!empty($row['is_total']))
                            {{-- Baris total parent --}}
                            <tr class="bg-yellow-50 font-semibold text-gray-700">
                                <td class="px-4 py-2 sticky left-0 bg-yellow-50 italic">
                                    {{ $row['name'] }}
                                </td>
                                @php $rowTotal = 0; @endphp
                                @foreach ($regions as $region)
                                    @php
                                        $subtotal = 0;
                                        foreach ($row['child_ids'] as $cid) {
                                            $subtotal += $values[$cid][$region->id]->value ?? 0;
                                        }
                                        $rowTotal += $subtotal;
                                    @endphp
                                    <td class="px-3 py-2 text-right">{{ number_format($subtotal) }}</td>
                                @endforeach
                                <td class="px-3 py-2 text-right bg-blue-50 text-blue-700">
                                    {{ number_format($rowTotal) }}
                                </td>
                            </tr>

                        @else
                            {{-- Baris sub kategori biasa --}}
                            <tr class="{{ $row['is_child'] ? 'hover:bg-blue-50' : 'hover:bg-blue-50 font-medium' }}">
                                <td
                                    class="px-4 py-2 sticky left-0 bg-white {{ $row['is_child'] ? 'pl-10 text-gray-600' : 'text-gray-800' }}">
                                    {{ $row['is_child'] ? '— ' : '' }}{{ $row['name'] }}
                                </td>
                                @php $rowTotal = 0; @endphp
                                @foreach ($regions as $region)
                                    @php
                                        $val = $values[$row['id']][$region->id]->value ?? 0;
                                        $rowTotal += $val;
                                        $grandTotal[$region->id] += $val;
                                    @endphp
                                    <td class="px-3 py-2 text-right text-gray-700">
                                        {{ number_format($val) }}
                                    </td>
                                @endforeach
                                <td class="px-3 py-2 text-right bg-blue-50 text-blue-700 font-semibold">
                                    {{ number_format($rowTotal) }}
                                </td>
                            </tr>
                        @endif

                    @endforeach

                    {{-- Baris Grand Total --}}
                    <tr class="bg-blue-600 text-white font-bold">
                        <td class="px-4 py-3 sticky left-0 bg-blue-600">Total Kabko</td>
                        @php $totalProvinsi = 0; @endphp
                        @foreach ($regions as $region)
                            @php $totalProvinsi += $grandTotal[$region->id]; @endphp
                            <td class="px-3 py-3 text-right">{{ number_format($grandTotal[$region->id]) }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-right bg-blue-800">{{ number_format($totalProvinsi) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
@endsection