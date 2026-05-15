@extends('layouts.app')

@section('title', 'Sub Kategori - ' . $subKategori->name)

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('pengisian.show', ['id' => $kategori->id, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                class="text-green-500 hover:text-green-700 text-sm">← Kembali</a>
            <div>
                <div class="text-xs text-gray-400">{{ $kategori->name }}</div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $subKategori->name }}</h1>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm">✅ {{ session('success') }}</div>
        @endif

        {{-- Formula Builder --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <h2 class="text-sm font-bold text-gray-700 uppercase mb-3">Formula Builder</h2>

            {{-- Display formula --}}
            <div class="font-mono text-sm bg-gray-50 border rounded-lg px-4 py-3 mb-3 min-h-[42px] flex items-center gap-1 flex-wrap"
                id="formula-display">
                @if ($formula)
                    <span class="text-gray-700">{{ $formula->formula_string }}</span>
                @else
                    <span class="text-gray-400 italic">Klik chip dan operator untuk menyusun formula...</span>
                @endif
            </div>
            <input type="hidden" id="formula-input" value="{{ $formula->formula_string ?? '' }}">

            {{-- Preview --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xs text-gray-500 font-semibold uppercase">Preview Hasil:</span>
                <span id="formula-preview" class="font-bold text-blue-700 text-sm">—</span>
            </div>

            @if ($canEdit && !$isPast)
                {{-- Chip sub-sub kategori --}}
                <div class="mb-3">
                    <div class="text-xs text-gray-400 mb-2">Sub-Sub Kategori:</div>
                    <div class="flex flex-wrap gap-2" id="chip-container">
                        @foreach ($subSubKategoris as $ssub)
                            <button type="button"
                                class="chip-btn px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition"
                                data-name="{{ $ssub->name }}">
                                {{ $ssub->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Operator buttons --}}
                <div class="mb-4">
                    <div class="text-xs text-gray-400 mb-2">Operator:</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['+', '-', '*', '/', '(', ')'] as $op)
                            <button type="button"
                                class="op-btn w-10 h-10 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 transition"
                                data-op="{{ $op }}">{{ $op }}</button>
                        @endforeach
                        <button type="button" id="btn-clear"
                            class="px-3 h-10 bg-red-100 text-red-600 rounded-lg text-sm font-bold hover:bg-red-200 transition">
                            Hapus Semua
                        </button>
                        <button type="button" id="btn-backspace"
                            class="px-3 h-10 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-bold hover:bg-yellow-200 transition">
                            ← Hapus
                        </button>
                    </div>
                </div>

                {{-- Tombol simpan formula --}}
                <div class="flex items-center gap-3">
                    <button type="button" id="btn-simpan-formula"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 font-semibold">
                        💾 Simpan Formula
                    </button>
                    <span id="formula-save-status" class="text-xs text-gray-400"></span>
                </div>
            @endif
        </div>

        {{-- Form Data Sub-Sub Kategori --}}
        <form method="POST"
            action="{{ route('pengisian.storeSub', ['id' => $kategori->id, 'subKategoriId' => $subKategori->id]) }}"
            id="form-sub">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="triwulan" value="{{ $triwulan }}">

            {{-- Filter --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
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

            {{-- Tabel Sub-Sub Kategori --}}
            <div class="bg-white rounded-xl shadow overflow-hidden mb-4">
                <div class="px-5 py-3 bg-gray-50 border-b flex items-center justify-between">
                    <h2 class="font-semibold text-gray-700">Data Sub-Sub Kategori</h2>
                    @if ($canEdit && !$isPast)
                        <button type="button" id="btn-tambah" class="text-sm text-green-600 hover:text-green-800 font-semibold">
                            + Tambah Sub-Sub
                        </button>
                    @endif
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b">
                        <tr>
                            <th class="px-5 py-3 text-left w-1/2">Nama</th>
                            <th class="px-5 py-3 text-right">Nilai</th>
                            @if ($canEdit && !$isPast)
                                <th class="px-5 py-3 text-center w-16">Hapus</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="tbody-sub-sub">

                        {{-- Sub-sub yang sudah ada --}}
                        @foreach ($subSubKategoris as $ssub)
                            <tr data-id="{{ $ssub->id }}">
                                <td class="px-5 py-2 text-gray-700">{{ $ssub->name }}</td>
                                <td class="px-5 py-2">
                                    @if ($canEdit && !$isPast)
                                        <input type="number" name="sub_sub_values[{{ $ssub->id }}]"
                                            value="{{ $subSubValues[$ssub->id]->value ?? '' }}"
                                            class="cell-input w-full border rounded px-2 py-1 text-right focus:outline-none focus:ring-2 focus:ring-green-400"
                                            placeholder="0" step="any" data-name="{{ $ssub->name }}">
                                    @else
                                        <span class="block text-right text-gray-700">
                                            {{ rtrim(rtrim(number_format($subSubValues[$ssub->id]->value ?? 0, 4, ',', '.'), '0'), ',') }}
                                        </span>
                                    @endif
                                </td>
                                @if ($canEdit && !$isPast)
                                    <td class="px-5 py-2 text-center">
                                        <button type="button" class="btn-hapus text-red-400 hover:text-red-600 text-xs"
                                            data-id="{{ $ssub->id }}">✕</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        {{-- Baris sub-sub baru akan diinsert di sini via JS --}}

                    </tbody>
                </table>
            </div>

            {{-- Hidden inputs untuk sub-sub baru --}}
            <div id="new-sub-inputs"></div>

            {{-- Action buttons --}}
            @if ($canEdit && !$isPast)
                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-green-700 font-semibold">
                        💾 Simpan Data
                    </button>
                </div>
            @endif

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

    {{-- Modal konfirmasi hapus --}}
    <div id="modal-hapus" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
            <h2 class="text-lg font-bold text-gray-800 mb-2">Hapus Sub-Sub Kategori?</h2>
            <p class="text-gray-600 text-sm mb-6">Data nilai yang sudah diinput juga akan ikut terhapus.</p>
            <div class="flex justify-end gap-3">
                <button id="hapus-batal"
                    class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm hover:bg-gray-200 font-semibold">Batal</button>
                <button id="hapus-ya"
                    class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm hover:bg-red-600 font-semibold">Ya,
                    Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // ── Filter redirect ────────────────────────────────────────────────
        const selTahun = document.getElementById('sel-tahun');
        const selTriwulan = document.getElementById('sel-triwulan');

        function redirectFilter() {
            const url = new URL(window.location.href);
            url.searchParams.set('tahun', selTahun.value);
            url.searchParams.set('triwulan', selTriwulan.value);
            window.location.href = url.toString();
        }
        selTahun?.addEventListener('change', redirectFilter);
        selTriwulan?.addEventListener('change', redirectFilter);

        // ── Formula builder ────────────────────────────────────────────────
        const formulaInput = document.getElementById('formula-input');
        const formulaDisplay = document.getElementById('formula-display');
        const formulaPreview = document.getElementById('formula-preview');
        const subValues = {};

        function updateDisplay() {
            const val = formulaInput.value.trim();
            formulaDisplay.innerHTML = val
                ? `<span class="text-gray-800">${val}</span>`
                : `<span class="text-gray-400 italic">Klik chip dan operator untuk menyusun formula...</span>`;
            calcPreview();
        }

        function calcPreview() {
            let expr = formulaInput.value;
            if (!expr.trim()) { formulaPreview.textContent = '—'; return; }
            const names = Object.keys(subValues).sort((a, b) => b.length - a.length);
            for (const name of names) expr = expr.replaceAll(name, subValues[name] ?? 0);
            try {
                const result = Function('"use strict"; return (' + expr + ')')();
                if (isFinite(result)) {
                    formulaPreview.textContent = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 4 }).format(result);
                    formulaPreview.classList.remove('text-red-500');
                    formulaPreview.classList.add('text-blue-700');
                } else {
                    formulaPreview.textContent = 'Error';
                    formulaPreview.classList.add('text-red-500');
                }
            } catch {
                formulaPreview.textContent = 'Error';
                formulaPreview.classList.add('text-red-500');
            }
        }

        // Init nilai dari input yang sudah ada
        document.querySelectorAll('.cell-input').forEach(input => {
            const name = input.dataset.name;
            subValues[name] = parseFloat(input.value) || 0;
            input.addEventListener('input', () => {
                subValues[name] = parseFloat(input.value) || 0;
                calcPreview();
            });
            // Paste dari Excel
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const text = e.clipboardData.getData('text');
                const lines = text.split(/\r?\n/).filter(l => l.trim() !== '');
                if (lines.length <= 1) {
                    input.value = lines[0]?.replace(/[^0-9.-]/g, '') ?? '';
                    input.dispatchEvent(new Event('input'));
                    return;
                }
                const allInputs = Array.from(document.querySelectorAll('.cell-input'));
                const startIdx = allInputs.indexOf(input);
                lines.forEach((line, i) => {
                    const target = allInputs[startIdx + i];
                    if (target) {
                        target.value = line.trim().replace(/[^0-9.-]/g, '');
                        target.dispatchEvent(new Event('input'));
                    }
                });
            });
        });

        calcPreview();
        updateDisplay();

        // Chip klik
        document.querySelectorAll('.chip-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.name;
                formulaInput.value += (formulaInput.value && !formulaInput.value.endsWith(' ') ? ' ' : '') + name + ' ';
                updateDisplay();
            });
        });

        // Operator klik
        document.querySelectorAll('.op-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                formulaInput.value += (formulaInput.value && !formulaInput.value.endsWith(' ') ? ' ' : '') + btn.dataset.op + ' ';
                updateDisplay();
            });
        });

        document.getElementById('btn-clear')?.addEventListener('click', () => {
            formulaInput.value = '';
            updateDisplay();
        });

        document.getElementById('btn-backspace')?.addEventListener('click', () => {
            const tokens = formulaInput.value.trimEnd().split(' ');
            tokens.pop();
            formulaInput.value = tokens.join(' ') + (tokens.length ? ' ' : '');
            updateDisplay();
        });

        // ── Simpan formula via AJAX ────────────────────────────────────────
        document.getElementById('btn-simpan-formula')?.addEventListener('click', async () => {
            const status = document.getElementById('formula-save-status');
            status.textContent = 'Menyimpan...';
            status.classList.remove('text-green-500', 'text-red-500');
            status.classList.add('text-gray-400');

            try {
                const res = await fetch('{{ route("pengisian.storeFormula", ["id" => $kategori->id, "subKategoriId" => $subKategori->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        formula_string: formulaInput.value,
                        tahun:          {{ $tahun }},
                        triwulan:       {{ $triwulan }},
                    })
                });
                const data = await res.json();
                if (data.success) {
                    status.textContent = '✅ Formula tersimpan';
                    status.classList.remove('text-gray-400', 'text-red-500');
                    status.classList.add('text-green-500');
                } else {
                    status.textContent = '❌ Gagal menyimpan';
                    status.classList.add('text-red-500');
                }
            } catch {
                status.textContent = '❌ Gagal menyimpan';
                status.classList.add('text-red-500');
            }
        });

        // ── Tambah sub-sub baru ────────────────────────────────────────────
        const btnTambah = document.getElementById('btn-tambah');
        const tbody = document.getElementById('tbody-sub-sub');
        const newSubInputs = document.getElementById('new-sub-inputs');
        const chipContainer = document.getElementById('chip-container');

        btnTambah?.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.classList.add('bg-green-50', 'new-row');
            newRow.innerHTML = `
            <td class="px-5 py-2">
                <input type="text"
                    class="new-name-input w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                    placeholder="Nama sub-sub kategori...">
            </td>
            <td class="px-5 py-2">
                <input type="number" step="any"
                    class="new-value-input w-full border rounded px-2 py-1 text-right focus:outline-none focus:ring-2 focus:ring-green-400"
                    placeholder="0">
            </td>
            <td class="px-5 py-2 text-center">
                <button type="button" class="btn-hapus-new text-red-400 hover:text-red-600 text-xs">✕</button>
            </td>
        `;
            tbody.appendChild(newRow);
            newRow.querySelector('.new-name-input').focus();

            // Hapus baris baru
            newRow.querySelector('.btn-hapus-new').addEventListener('click', () => newRow.remove());
        });

        // Submit form — kumpulkan baris baru ke hidden inputs
        document.getElementById('form-sub')?.addEventListener('submit', () => {
            // Hapus hidden inputs lama
            newSubInputs.innerHTML = '';

            document.querySelectorAll('.new-row').forEach((row, i) => {
                const name = row.querySelector('.new-name-input').value.trim();
                const value = row.querySelector('.new-value-input').value;
                if (!name) return;

                const hiddenName = document.createElement('input');
                hiddenName.type = 'hidden';
                hiddenName.name = `new_sub_sub[${i}][name]`;
                hiddenName.value = name;
                newSubInputs.appendChild(hiddenName);

                if (value) {
                    const hiddenVal = document.createElement('input');
                    hiddenVal.type = 'hidden';
                    hiddenVal.name = `new_sub_sub[${i}][value]`;
                    hiddenVal.value = value;
                    newSubInputs.appendChild(hiddenVal);
                }
            });
        });

        // ── Hapus sub-sub (modal) ──────────────────────────────────────────
        const modalHapus = document.getElementById('modal-hapus');
        const hapusBatal = document.getElementById('hapus-batal');
        const hapusYa = document.getElementById('hapus-ya');
        let pendingHapusId = null;
        let pendingHapusRow = null;

        tbody.addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-hapus');
            if (!btn) return;
            pendingHapusId = btn.dataset.id;
            pendingHapusRow = btn.closest('tr');
            modalHapus.classList.remove('hidden');
        });

        hapusBatal.addEventListener('click', () => {
            modalHapus.classList.add('hidden');
            pendingHapusId = null;
            pendingHapusRow = null;
        });

        hapusYa.addEventListener('click', async () => {
            modalHapus.classList.add('hidden');
            try {
                const res = await fetch(`/pengisian/sub-sub/${pendingHapusId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                pendingHapusRow.remove();

                if (data.sisa_sub_sub === 0) {
                    window.location.href = "{{ route('pengisian.show', ['id' => $kategori->id, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}";
                }
            } catch {
                alert('Gagal menghapus. Coba lagi.');
            }
            pendingHapusId = null;
            pendingHapusRow = null;
        });

        @if ($canEdit && $isPast)
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