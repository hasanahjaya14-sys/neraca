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
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm">✅ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-lg text-sm">❌ {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('pengisian.store', $kategori->id) }}" id="form-pengisian">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="triwulan" value="{{ $triwulan }}">

            {{-- Filter --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">
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
                    @foreach (range($tahunMin, $tahunMax) as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <select id="sel-triwulan" class="border rounded-lg px-3 py-2 text-sm">
                    @foreach ([1, 2, 3, 4] as $tw)
                        <option value="{{ $tw }}" {{ $triwulan == $tw ? 'selected' : '' }}>Q{{ $tw }}</option>
                    @endforeach
                </select>
                @if ($isPast)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">🔒 Triwulan
                        Lewat</span>
                @else
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">✅ Triwulan
                        Aktif</span>
                @endif
                @if (!$canEdit)
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">👁️ View Only</span>
                @endif
            </div>

            {{-- Tabel Sub Kategori --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 text-left">Sub Kategori</th>
                            <th class="px-5 py-3 text-right">Nilai</th>
                            <th class="px-5 py-3 text-center w-32">Sub-Sub</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($kategori->subKategoris as $sub)

                            @if ($sub->children->isNotEmpty())
                                {{-- Parent row header --}}
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-5 py-2 font-semibold text-gray-700">
                                        {{ $sub->name }}
                                    </td>
                                </tr>
                                {{-- Children --}}
                                @foreach ($sub->children as $child)
                                    @php $childSubSubs = $subSubKategoris[$child->id] ?? collect(); @endphp
                                    <tr class="hover:bg-green-50">
                                        <td class="px-5 py-3 pl-10 text-gray-600">— {{ $child->name }}</td>
                                        <td class="px-5 py-3 text-right">
                                            @if ($childSubSubs->isNotEmpty())
                                                @php $total = $subKategoriValues[$child->id]->value ?? 0; @endphp
                                                <span class="text-blue-700 font-semibold">
                                                    {{ rtrim(rtrim(number_format($total, 4, ',', '.'), '0'), ',') }}
                                                </span>
                                            @else
                                                @if ($canEdit && !$isPast)
                                                    <input type="text" inputmode="decimal" name="sub_kategori_values[{{ $child->id }}]"
                                                        value="{{ $subKategoriValues[$child->id]->value ?? '' }}"
                                                        class="num-input border rounded px-2 py-1 text-right w-40 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="0">
                                                @else
                                                    {{ rtrim(rtrim(number_format($subKategoriValues[$child->id]->value ?? 0, 4, ',', '.'), '0'), ',') }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <a href="{{ route('pengisian.sub', ['id' => $kategori->id, 'subKategoriId' => $child->id, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold transition {{ $childSubSubs->isNotEmpty() ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' }}">
                                                {{ $childSubSubs->isNotEmpty() ? '✏️ Edit Sub-Sub' : '+ Sub-Sub' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            @else
                                {{-- Flat row --}}
                                @php $subSubs = $subSubKategoris[$sub->id] ?? collect(); @endphp
                                <tr class="hover:bg-green-50">
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $sub->name }}</td>
                                    <td class="px-5 py-3 text-right">
                                        @if ($subSubs->isNotEmpty())
                                            @php $total = $subKategoriValues[$sub->id]->value ?? 0; @endphp
                                            <span class="text-blue-700 font-semibold">
                                                {{ rtrim(rtrim(number_format($total, 4, ',', '.'), '0'), ',') }}
                                            </span>
                                        @else
                                            @if ($canEdit && !$isPast)
                                                <input type="text" inputmode="decimal" name="sub_kategori_values[{{ $sub->id }}]"
                                                    value="{{ $subKategoriValues[$sub->id]->value ?? '' }}"
                                                    class="num-input border rounded px-2 py-1 text-right w-40 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                    placeholder="0">
                                            @else
                                                {{ rtrim(rtrim(number_format($subKategoriValues[$sub->id]->value ?? 0, 4, ',', '.'), '0'), ',') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <a href="{{ route('pengisian.sub', ['id' => $kategori->id, 'subKategoriId' => $sub->id, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold transition {{ $subSubs->isNotEmpty() ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' }}">
                                            {{ $subSubs->isNotEmpty() ? '✏️ Edit Sub-Sub' : '+ Sub-Sub' }}
                                        </a>
                                    </td>
                                </tr>
                            @endif

                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action buttons --}}
            <div class="mt-6 flex justify-end gap-3">
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
    @if ($canEdit && $isPast)
        <div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Edit Data Lama?</h2>
                <p class="text-gray-600 text-sm mb-6">Kamu akan mengedit data triwulan yang sudah lewat. Pastikan perubahan
                    sudah disetujui.</p>
                <div class="flex justify-end gap-3">
                    <button id="modal-batal"
                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm hover:bg-gray-200 font-semibold">Batal</button>
                    <button id="modal-ya"
                        class="px-4 py-2 rounded-lg bg-yellow-500 text-white text-sm hover:bg-yellow-600 font-semibold">Ya,
                        Edit</button>
                </div>
            </div>
        </div>
    @endif

    <script>
        // ── Filter redirect ────────────────────────────────────────────────
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

        // ── Format ribuan ──────────────────────────────────────────────────
        function formatRibuan(input) {
            let raw = input.value.replace(/\./g, '').replace(/[^0-9,]/g, '');
            let parts = raw.split(',');
            let integer = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            let decimal = parts[1] !== undefined ? ',' + parts[1] : '';
            input.value = integer + decimal;
        }

        document.querySelectorAll('.num-input').forEach(input => {
            // Format nilai awal
            if (input.value) {
                let num = parseFloat(input.value);
                if (!isNaN(num)) {
                    input.value = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 4 }).format(num);
                }
            }

            // Format waktu ketik
            input.addEventListener('input', () => formatRibuan(input));
        });

        // Bersihkan format sebelum submit
        document.getElementById('form-pengisian')?.addEventListener('submit', () => {
            document.querySelectorAll('.num-input').forEach(input => {
                input.value = input.value.replace(/\./g, '').replace(',', '.');
            });
        });

        @if ($canEdit && $isPast)
            // ── Modal edit data lama ───────────────────────────────────────────
            const btnEdit = document.getElementById('btn-edit');
            const btnSimpan = document.getElementById('btn-simpan');
            const btnBatal = document.getElementById('btn-batal');
            const modal = document.getElementById('modal-edit');
            const modalBatal = document.getElementById('modal-batal');
            const modalYa = document.getElementById('modal-ya');

            btnEdit.addEventListener('click', () => modal.classList.remove('hidden'));
            modalBatal.addEventListener('click', () => modal.classList.add('hidden'));
            btnBatal.addEventListener('click', () => {
                btnEdit.classList.remove('hidden');
                btnSimpan.classList.add('hidden');
                btnBatal.classList.add('hidden');
            });
            modalYa.addEventListener('click', () => {
                modal.classList.add('hidden');
                btnEdit.classList.add('hidden');
                btnSimpan.classList.remove('hidden');
                btnBatal.classList.remove('hidden');
            });
        @endif
    </script>
@endsection