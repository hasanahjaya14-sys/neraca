@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tren Penduduk Miskin</h2>
            <p class="text-slate-400 text-sm mt-1">Perbandingan Kabupaten/Kota vs Provinsi — 2020 sampai 2026</p>
        </div>

        <form method="GET" action="{{ route('dashboard') }}">
            <div class="flex items-center gap-2">
                <label class="text-sm text-slate-500">Kabupaten/Kota:</label>
                <select name="kabko" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($kabkoList as $k)
                        <option value="{{ $k }}" {{ $k == $selectedKabko ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- STATS --}}
    @php
        $latestKabko = $tren->last();
        $firstKabko = $tren->first();
        $latestProvinsi = $trenProvinsi->last();
        $change = ($latestKabko && $firstKabko) ? $latestKabko->nilai - $firstKabko->nilai : 0;
        $pct = ($firstKabko && $firstKabko->nilai > 0) ? ($change / $firstKabko->nilai) * 100 : 0;
        $kontribusi = ($latestKabko && $latestProvinsi && $latestProvinsi->nilai > 0)
            ? ($latestKabko->nilai / $latestProvinsi->nilai) * 100 : 0;
    @endphp

    <div class="grid grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">{{ $selectedKabko }} ({{ $latestKabko->tahun ?? '-' }})</div>
            <div class="text-2xl font-bold text-slate-800">
                {{ $latestKabko ? number_format($latestKabko->nilai, 0, ',', '.') : '-' }}
            </div>
            <div class="text-xs text-slate-400 mt-1">jiwa</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Provinsi ({{ $latestProvinsi->tahun ?? '-' }})</div>
            <div class="text-2xl font-bold text-slate-800">
                {{ $latestProvinsi ? number_format($latestProvinsi->nilai, 0, ',', '.') : '-' }}
            </div>
            <div class="text-xs text-slate-400 mt-1">jiwa</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Kontribusi ke Provinsi</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ number_format($kontribusi, 1) }}%
            </div>
            <div class="text-xs text-slate-400 mt-1">dari total provinsi</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="text-xs text-slate-400 mb-1">Perubahan
                ({{ $firstKabko->tahun ?? '-' }}–{{ $latestKabko->tahun ?? '-' }})</div>
            <div class="text-2xl font-bold {{ $change <= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 0, ',', '.') }}
            </div>
            <div class="text-xs {{ $change <= 0 ? 'text-green-500' : 'text-red-400' }} mt-1">
                {{ $pct >= 0 ? '+' : '' }}{{ number_format($pct, 1) }}%
            </div>
        </div>

    </div>

    {{-- LINE CHART --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">
                Tren — {{ $selectedKabko }} vs Provinsi
            </h3>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-4 h-0.5 bg-blue-500 rounded"></span>
                    {{ $selectedKabko }}
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-4 h-0.5 bg-slate-400 rounded"
                        style="border-top: 2px dashed #94a3b8; height:0"></span>
                    Provinsi
                </span>
            </div>
        </div>

        <canvas id="lineChart" height="80"></canvas>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($tren->pluck('tahun'));
        const valuesKabko = @json($tren->pluck('nilai'));
        const valuesProvinsi = @json($trenProvinsi->pluck('nilai'));
        const maxNilai = {{ $maxNilai }};

        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: '{{ $selectedKabko }}',
                        data: valuesKabko,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.07)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Provinsi',
                        data: valuesProvinsi,
                        borderColor: '#94a3b8',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [6, 4],
                        pointBackgroundColor: '#94a3b8',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: false,
                        tension: 0.3,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('id-ID') + ' jiwa';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: Math.ceil(maxNilai * 1.05),
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function (val) {
                                return val.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
@endpush