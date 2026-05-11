@extends('layouts.app')

@section('title', 'Rekonsiliasi Indikator')
@section('page-title', 'Rekonsiliasi Indikator')

@section('content')

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Tingkat Pengangguran Terbuka (TPT)
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Simulasi rekonsiliasi nilai kabupaten/kota terhadap target provinsi
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4">

                <div class="bg-slate-50 rounded-xl p-4 min-w-[140px]">
                    <div class="text-xs text-slate-400 mb-1">Target Provinsi</div>
                    <div class="text-2xl font-bold text-slate-800">5.4000</div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 min-w-[140px]">
                    <div class="text-xs text-slate-400 mb-1">Hasil Simulasi</div>
                    <div id="hasilProvinsi" class="text-2xl font-bold text-red-600">5.2100</div>
                </div>

                <div id="statusBox" class="rounded-xl p-4 min-w-[140px] bg-red-50">
                    <div class="text-xs text-red-400 mb-1">Status</div>
                    <div id="statusText" class="text-lg font-bold text-red-700">Belum Seimbang</div>
                </div>

            </div>

        </div>

    </div>

    {{-- INFO BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">

        <div class="text-xs text-slate-400">
            Terakhir diperbarui:
            <span class="font-semibold text-slate-600">14 Mei 2026 14:25:03</span>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-xs text-slate-400">
                Refresh otomatis dalam:
                <span id="countdown" class="font-bold text-slate-600">05:00</span>
            </div>
            <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl text-sm transition">
                Refresh Data
            </button>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed">

                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th
                            class="w-[30%] text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Kabupaten/Kota
                        </th>
                        <th
                            class="w-[15%] text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Nilai Saat Ini
                        </th>
                        <th
                            class="w-[20%] text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Simulasi Baru
                        </th>
                        <th
                            class="w-[15%] text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Selisih
                        </th>
                        <th
                            class="w-[20%] text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($kabkos as $kabko)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-medium text-slate-700 text-sm">
                                {{ $kabko['nama'] }}
                            </td>

                            <td class="px-6 py-4 text-center text-sm font-medium text-slate-600">
                                {{ number_format($kabko['nilai'], 4) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="number" step="0.0001" value="{{ $kabko['nilai'] }}"
                                    data-current="{{ $kabko['nilai'] }}"
                                    class="nilaiInput w-32 border border-slate-200 rounded-xl px-3 py-2 text-center text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </td>

                            <td class="selisihCell px-6 py-4 text-center text-slate-400 text-sm font-medium">
                                +0.0000
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm transition">
                                    Simpan
                                </button>
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const targetProvinsi = 5.4000;
            const inputs = document.querySelectorAll('.nilaiInput');

            inputs.forEach(input => {
                input.addEventListener('input', hitungProvinsi);
            });

            function hitungProvinsi() {

                let total = 0;

                inputs.forEach(input => {

                    const newValue = parseFloat(input.value) || 0;
                    const currentValue = parseFloat(input.dataset.current);
                    const diff = newValue - currentValue;

                    total += newValue;

                    const diffCell = input.closest('tr').querySelector('.selisihCell');
                    diffCell.innerText = (diff >= 0 ? '+' : '') + diff.toFixed(4);

                    if (diff === 0) {
                        diffCell.className = 'selisihCell px-6 py-4 text-center text-slate-400 text-sm font-medium';
                    } else if (diff > 0) {
                        diffCell.className = 'selisihCell px-6 py-4 text-center text-green-600 text-sm font-semibold';
                    } else {
                        diffCell.className = 'selisihCell px-6 py-4 text-center text-red-500 text-sm font-semibold';
                    }
                });

                const rata = total / inputs.length;
                const hasilProvinsi = document.getElementById('hasilProvinsi');
                const statusBox = document.getElementById('statusBox');
                const statusText = document.getElementById('statusText');
                const selisih = Math.abs(rata - targetProvinsi);

                hasilProvinsi.innerText = rata.toFixed(4);

                if (selisih <= 0.0001) {
                    statusBox.className = 'rounded-xl p-4 min-w-[140px] bg-green-50';
                    statusText.className = 'text-lg font-bold text-green-700';
                    hasilProvinsi.className = 'text-2xl font-bold text-green-600';
                    statusText.innerText = 'Sudah Seimbang';
                } else {
                    statusBox.className = 'rounded-xl p-4 min-w-[140px] bg-red-50';
                    statusText.className = 'text-lg font-bold text-red-700';
                    hasilProvinsi.className = 'text-2xl font-bold text-red-600';
                    statusText.innerText = 'Belum Seimbang';
                }
            }

            // countdown
            let totalSeconds = 300;
            setInterval(() => {
                totalSeconds--;
                if (totalSeconds <= 0) totalSeconds = 300;
                const m = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                const s = String(totalSeconds % 60).padStart(2, '0');
                document.getElementById('countdown').innerText = `${m}:${s}`;
            }, 1000);

        });
    </script>
@endpush