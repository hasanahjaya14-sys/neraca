@extends('layouts.app')

@section('title', $indikator['nama'])
@section('page-title', 'Pengisian Data')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">
        <a href="{{ route('pengisian.index') }}" class="hover:text-slate-600 transition">Pengisian Data</a>
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-600 font-medium">{{ $indikator['nama'] }}</span>
    </div>

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $indikator['nama'] }}</h2>
            <p class="text-slate-400 text-sm mt-1">Isi data per variabel dan triwulan.</p>
        </div>
        <a href="{{ route('pengisian.index') }}"
            class="border border-slate-200 hover:bg-slate-50 text-slate-600 px-4 py-2 rounded-xl text-sm transition">
            ← Kembali
        </a>
    </div>

    {{-- FILTER TAHUN --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('pengisian.show', $kode) }}" class="flex items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tahun</label>
                <select name="tahun" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $selectedTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-400 pb-2">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Terkunci
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Bisa diisi/diedit
                </span>
            </div>
        </form>
    </div>

    {{-- TABEL PER TRIWULAN --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th
                            class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-[30%]">
                            Variabel
                        </th>
                        @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                            <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wide
                                {{ $lockStatus[$q] ? 'text-slate-400' : 'text-blue-600' }}">
                                <div class="flex items-center justify-center gap-1">
                                    {{ $q }}
                                    @if($lockStatus[$q])
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($struktur as $parent => $children)

                        {{-- PARENT ROW --}}
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <td colspan="6" class="px-6 py-2.5 text-xs font-bold text-slate-600 uppercase tracking-wide">
                                {{ $parent }}
                            </td>
                        </tr>

                        {{-- CHILD ROWS --}}
                        @foreach($children as $child)
                            @php $rowId = Str::slug($parent . '-' . $child); @endphp
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition" id="row-{{ $rowId }}">

                                <td class="px-6 py-3 text-sm text-slate-600 pl-10">
                                    {{ $child }}
                                </td>

                                @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                                    @php
                                        $nilai = $dummyData[$parent][$child][$selectedTahun][$q] ?? null;
                                        $locked = $lockStatus[$q];
                                    @endphp
                                    <td class="px-4 py-2 text-center {{ $locked ? 'bg-slate-50' : '' }}">
                                        {{-- VIEW MODE --}}
                                        <span class="view-{{ $rowId }}-{{ $q }} text-sm font-medium text-slate-700">
                                            {{ $nilai !== null ? number_format($nilai, 0, ',', '.') : '—' }}
                                        </span>
                                        {{-- EDIT MODE --}}
                                        <input type="number" value="{{ $nilai }}"
                                            class="edit-{{ $rowId }}-{{ $q }} hidden w-32 border border-blue-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            {{ $locked ? 'disabled' : '' }}>
                                    </td>
                                @endforeach

                                <td class="px-4 py-2 text-center">
                                    {{-- Tombol Edit (untuk triwulan terkunci) --}}
                                    <button onclick="toggleEdit('{{ $rowId }}')" id="btn-edit-{{ $rowId }}"
                                        class="border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-1.5 rounded-lg text-xs transition">
                                        Edit
                                    </button>
                                    {{-- Tombol Simpan (tersembunyi dulu) --}}
                                    <button onclick="simpan('{{ $rowId }}')" id="btn-simpan-{{ $rowId }}"
                                        class="hidden bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs transition">
                                        Simpan
                                    </button>
                                </td>

                            </tr>
                        @endforeach

                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        const lockStatus = @json($lockStatus);
        const triwulan = ['Q1', 'Q2', 'Q3', 'Q4'];

        function toggleEdit(rowId) {
            triwulan.forEach(q => {
                const view = document.querySelector(`.view-${rowId}-${q}`);
                const input = document.querySelector(`.edit-${rowId}-${q}`);
                if (view && input) {
                    view.classList.add('hidden');
                    input.classList.remove('hidden');
                    input.disabled = false;
                }
            });
            document.getElementById(`btn-edit-${rowId}`).classList.add('hidden');
            document.getElementById(`btn-simpan-${rowId}`).classList.remove('hidden');
        }

        function simpan(rowId) {
            triwulan.forEach(q => {
                const view = document.querySelector(`.view-${rowId}-${q}`);
                const input = document.querySelector(`.edit-${rowId}-${q}`);
                if (view && input) {
                    const val = input.value;
                    view.innerText = val ? parseInt(val).toLocaleString('id-ID') : '—';
                    view.classList.remove('hidden');
                    input.classList.add('hidden');
                    if (lockStatus[q]) input.disabled = true;
                }
            });
            document.getElementById(`btn-edit-${rowId}`).classList.remove('hidden');
            document.getElementById(`btn-simpan-${rowId}`).classList.add('hidden');

            // TODO: kirim ke backend via fetch/axios
        }
    </script>
@endpush