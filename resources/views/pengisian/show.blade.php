@extends('layouts.app')

@section('title', 'Pengisian - ' . $kategori->name)

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('pengisian.index') }}" class="text-green-500 hover:text-green-700 text-sm">← Kembali</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $kategori->name }}</h1>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('pengisian.store', $kategori->id) }}" id="form-pengisian">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="triwulan" value="{{ $triwulan }}">

            {{-- Filter --}}
            {{-- Filter --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">

                {{-- Pilih kabko (hanya untuk provinsi & superadmin) --}}
                @if ($role !== 'kabko')
                    <select id="sel-region" class="border rounded-lg px-3 py-2 text-sm">
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ $regionId == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <select id="sel-tahun" class="border rounded-lg px-3 py-2 text-sm">
                    @foreach (range(2018, 2030) as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>

                <select id="sel-triwulan" class="border rounded-lg px-3 py-2 text-sm">
                    @foreach ([1, 2, 3, 4] as $tw)
                        <option value="{{ $tw }}" {{ $triwulan == $tw ? 'selected' : '' }}>Q{{ $tw }}</option>
                    @endforeach
                </select>

                {{-- Badge status --}}
                @if ($isPast)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                        🔒 Triwulan Lewat
                    </span>
                @else
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        ✅ Triwulan Aktif
                    </span>
                @endif

                {{-- Badge view only --}}
                @if (!$canEdit)
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                        👁️ View Only
                    </span>
                @endif

            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left w-1/2">Sub Kategori</th>
                            <th class="px-4 py-3 text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($kategori->subKategoris as $sub)

                            @if ($sub->children->isNotEmpty())
                                <tr class="bg-gray-50">
                                    <td colspan="2" class="px-4 py-2 font-semibold text-gray-700">
                                        {{ $sub->name }}
                                    </td>
                                </tr>
                                @foreach ($sub->children as $child)
                                    <tr class="hover:bg-green-50">
                                        <td class="px-4 py-3 pl-10 text-gray-600">— {{ $child->name }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="display-val text-gray-700">
                                                {{ number_format($values[$child->id]->value ?? 0) }}
                                            </span>
                                            <input type="number" name="values[{{ $child->id }}]"
                                                value="{{ $values[$child->id]->value ?? '' }}"
                                                class="input-val hidden border rounded-lg px-3 py-1.5 text-right w-48 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                placeholder="0">
                                        </td>
                                    </tr>
                                @endforeach

                            @else
                                <tr class="hover:bg-green-50">
                                    <td class="px-4 py-3 font-medium text-gray-700">{{ $sub->name }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="display-val text-gray-700">
                                            {{ number_format($values[$sub->id]->value ?? 0) }}
                                        </span>
                                        <input type="number" name="values[{{ $sub->id }}]"
                                            value="{{ $values[$sub->id]->value ?? '' }}"
                                            class="input-val hidden border rounded-lg px-3 py-1.5 text-right w-48 focus:outline-none focus:ring-2 focus:ring-green-400"
                                            placeholder="0">
                                    </td>
                                </tr>
                            @endif

                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action buttons --}}
            <div class="mt-4 flex justify-end gap-3">
                @if ($canEdit)
                    @if ($isPast)
                        <button type="button" id="btn-edit"
                            class="bg-yellow-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-yellow-600 font-semibold">
                            ✏️ Edit Data
                        </button>
                        <button type="submit" id="btn-simpan"
                            class="hidden bg-green-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-green-700 font-semibold">
                            💾 Simpan
                        </button>
                        <button type="button" id="btn-batal"
                            class="hidden bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300 font-semibold">
                            Batal
                        </button>
                    @else
                        <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-green-700 font-semibold">
                            💾 Simpan
                        </button>
                    @endif
                @endif
            </div>

        </form>
    </div>

    {{-- Modal konfirmasi edit --}}
    @if ($isPast)
        <div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Edit Data Lama?</h2>
                <p class="text-gray-600 text-sm mb-6">
                    Kamu akan mengedit data triwulan yang sudah lewat. Pastikan perubahan sudah disetujui sebelum disimpan.
                </p>
                <div class="flex justify-end gap-3">
                    <button id="modal-batal"
                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm hover:bg-gray-200 font-semibold">
                        Batal
                    </button>
                    <button id="modal-ya"
                        class="px-4 py-2 rounded-lg bg-yellow-500 text-white text-sm hover:bg-yellow-600 font-semibold">
                        Ya, Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Script --}}
    <script>
        const selTahun = document.getElementById('sel-tahun');
        const selTriwulan = document.getElementById('sel-triwulan');
        const selRegion = document.getElementById('sel-region');

        function redirectFilter() {
            const url = new URL(window.location.href);
            url.searchParams.set('tahun', selTahun.value);
            url.searchParams.set('triwulan', selTriwulan.value);
            if (selRegion) url.searchParams.set('region_id', selRegion.value);
            window.location.href = url.toString();
        }

        selTahun?.addEventListener('change', redirectFilter);
        selTriwulan?.addEventListener('change', redirectFilter);
        selRegion?.addEventListener('change', redirectFilter);

        @if ($canEdit && $isPast)
            const btnEdit = document.getElementById('btn-edit');
            const btnSimpan = document.getElementById('btn-simpan');
            const btnBatal = document.getElementById('btn-batal');
            const modal = document.getElementById('modal-edit');
            const modalBatal = document.getElementById('modal-batal');
            const modalYa = document.getElementById('modal-ya');
            const inputs = document.querySelectorAll('.input-val');
            const displays = document.querySelectorAll('.display-val');

            function masukModeEdit() {
                inputs.forEach(el => el.classList.remove('hidden'));
                displays.forEach(el => el.classList.add('hidden'));
                btnEdit.classList.add('hidden');
                btnSimpan.classList.remove('hidden');
                btnBatal.classList.remove('hidden');
            }

            function keluarModeEdit() {
                inputs.forEach(el => el.classList.add('hidden'));
                displays.forEach(el => el.classList.remove('hidden'));
                btnEdit.classList.remove('hidden');
                btnSimpan.classList.add('hidden');
                btnBatal.classList.add('hidden');
            }

            btnEdit.addEventListener('click', () => modal.classList.remove('hidden'));
            modalBatal.addEventListener('click', () => modal.classList.add('hidden'));
            modalYa.addEventListener('click', () => { modal.classList.add('hidden'); masukModeEdit(); });
            btnBatal.addEventListener('click', keluarModeEdit);

        @elseif ($canEdit && !$isPast)
            document.querySelectorAll('.input-val').forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll('.display-val').forEach(el => el.classList.add('hidden'));
        @endif
    </script>
@endsection