@extends('layouts.app')

@section('title', 'Formula Builder')
@section('page-title', 'Formula Builder')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- VARIABEL --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h2 class="text-base font-semibold text-slate-800 mb-4">
                Variabel
            </h2>

            <div class="space-y-2">

                @foreach($variables as $var)

                    <button onclick="tambahVariable('{{ $var['kode'] }}')"
                        class="w-full text-left border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition">

                        <div class="font-bold text-blue-600 text-sm">
                            {{ $var['kode'] }}
                        </div>

                        <div class="text-sm text-slate-600 mt-0.5">
                            {{ $var['nama'] }}
                        </div>

                        <div class="text-xs text-slate-400 mt-1">
                            Nilai: {{ $var['nilai'] }}
                        </div>

                    </button>

                @endforeach

            </div>

        </div>

        {{-- FORMULA --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">

            <div class="flex items-center justify-between mb-6">

                <h2 class="text-base font-semibold text-slate-800">
                    Formula
                </h2>

                <button onclick="simpanFormula()"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
                    Simpan Formula
                </button>

            </div>

            <div class="mb-4">

                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Nama Formula
                </label>

                <input type="text" id="namaFormula" placeholder="Contoh: Rasio Penduduk"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">

            </div>

            <div class="mb-4">

                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Formula
                </label>

                <textarea id="formula" rows="5" oninput="previewHasil()"
                    placeholder="Contoh: (jumlah_penduduk + jumlah_rt) / luas_wilayah"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>

            </div>

            {{-- OPERATOR --}}
            <div class="grid grid-cols-4 md:grid-cols-8 gap-2 mb-6">

                @foreach(['+', '-', '*', '/', '(', ')', '^'] as $op)
                    <button onclick="tambahOperator('{{ $op }}')"
                        class="border border-slate-200 rounded-xl py-2 text-sm font-mono hover:bg-slate-50 transition">
                        {{ $op }}
                    </button>
                @endforeach

                <button onclick="hapusFormula()"
                    class="border border-slate-200 rounded-xl py-2 text-sm text-red-500 hover:bg-red-50 transition">
                    Hapus
                </button>

            </div>

            {{-- PREVIEW --}}
            <div class="bg-slate-50 rounded-2xl p-6">

                <div class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-2">
                    Preview Hasil
                </div>

                <div id="hasil" class="text-4xl font-bold text-slate-800">
                    —
                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/12.4.2/math.min.js"></script>
    <script>

        const variableValues = {
            @foreach($variables as $var)
                '{{ $var['kode'] }}': {{ $var['nilai'] }},
            @endforeach
        };

        function tambahVariable(variable) {
            const formula = document.getElementById('formula');
            formula.value += variable;
            previewHasil();
        }

        function tambahOperator(op) {
            const formula = document.getElementById('formula');
            formula.value += ' ' + op + ' ';
            previewHasil();
        }

        function hapusFormula() {
            document.getElementById('formula').value = '';
            previewHasil();
        }

        function previewHasil() {
            const formula = document.getElementById('formula').value;
            const hasil = document.getElementById('hasil');
            try {
                const result = math.evaluate(formula, variableValues);
                hasil.innerText = result;
                hasil.className = 'text-4xl font-bold text-slate-800';
            } catch (e) {
                hasil.innerText = 'Formula belum valid';
                hasil.className = 'text-xl font-medium text-slate-400';
            }
        }

        function simpanFormula() {
            const nama = document.getElementById('namaFormula').value;
            const formula = document.getElementById('formula').value;

            if (!nama || !formula) {
                alert('Nama dan formula tidak boleh kosong.');
                return;
            }

            alert('Formula berhasil disimpan!\n\nNama: ' + nama + '\nFormula: ' + formula);
        }

    </script>
@endpush