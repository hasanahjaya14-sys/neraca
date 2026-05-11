<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formula Builder</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/12.4.2/math.min.js"></script>
</head>
<body class="bg-slate-100 min-h-screen p-8">

<div class="max-w-7xl mx-auto">

    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-800">
            Formula Builder
        </h1>

        <p class="text-slate-500 mt-2">
            Susun rumus dinamis menggunakan variabel yang tersedia.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- VARIABLES -->
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h2 class="text-xl font-semibold mb-4 text-slate-800">
                Variabel
            </h2>

            <div class="space-y-3">

                @foreach($variables as $var)

                    <button
                        onclick="tambahVariable('{{ $var['kode'] }}')"
                        class="w-full text-left border rounded-xl p-4 hover:bg-slate-50 transition">

                        <div class="font-bold text-blue-600">
                            {{ $var['kode'] }}
                        </div>

                        <div class="text-sm text-slate-600">
                            {{ $var['nama'] }}
                        </div>

                        <div class="text-xs text-slate-400 mt-1">
                            Nilai: {{ $var['nilai'] }}
                        </div>

                    </button>

                @endforeach

            </div>

        </div>

        <!-- FORMULA -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">

                <h2 class="text-xl font-semibold text-slate-800">
                    Formula
                </h2>

                <button
                    onclick="simpanFormula()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">

                    Simpan Formula
                </button>

            </div>

            <div class="mb-4">

                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Nama Formula
                </label>

                <input
                    type="text"
                    id="namaFormula"
                    placeholder="Contoh: Rasio Penduduk"
                    class="w-full border rounded-xl px-4 py-3">

            </div>

            <div class="mb-4">

                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Formula
                </label>

                <textarea
                    id="formula"
                    rows="5"
                    oninput="previewHasil()"
                    placeholder="Contoh: (x1 + x2) / x4"
                    class="w-full border rounded-xl px-4 py-3 font-mono text-lg"></textarea>

            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">

                <button onclick="tambahOperator('+')" class="border rounded-xl py-2 hover:bg-slate-50">+</button>
                <button onclick="tambahOperator('-')" class="border rounded-xl py-2 hover:bg-slate-50">-</button>
                <button onclick="tambahOperator('*')" class="border rounded-xl py-2 hover:bg-slate-50">*</button>
                <button onclick="tambahOperator('/')" class="border rounded-xl py-2 hover:bg-slate-50">/</button>
                <button onclick="tambahOperator('(')" class="border rounded-xl py-2 hover:bg-slate-50">(</button>
                <button onclick="tambahOperator(')')" class="border rounded-xl py-2 hover:bg-slate-50">)</button>
                <button onclick="tambahOperator('^')" class="border rounded-xl py-2 hover:bg-slate-50">^</button>
                <button onclick="hapusFormula()" class="border rounded-xl py-2 hover:bg-red-50 text-red-600">
                    Hapus
                </button>

            </div>

            <div class="bg-slate-100 rounded-2xl p-6">

                <div class="text-sm text-slate-500 mb-2">
                    Preview Hasil
                </div>

                <div id="hasil"
                    class="text-4xl font-bold text-slate-800">

                    -
                </div>

            </div>

        </div>

    </div>

</div>

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

        try {

            const hasil = math.evaluate(formula, variableValues);

            document.getElementById('hasil').innerText = hasil;

        } catch (error) {

            document.getElementById('hasil').innerText = 'Formula belum valid';

        }

    }

    function simpanFormula() {

        const nama = document.getElementById('namaFormula').value;
        const formula = document.getElementById('formula').value;

        alert(
            'Formula berhasil disimpan!\n\n' +
            'Nama: ' + nama + '\n' +
            'Formula: ' + formula
        );

    }

</script>

</body>
</html>