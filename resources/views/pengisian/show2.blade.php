@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Pengisian Data - Pertanian</h1>

        <div class="mb-4">
            <label for="kabupaten" class="block">Kabupaten/Kota:</label>
            <select id="kabupaten" class="form-select block w-full">
                <option value="palangka_raya">Palangka Raya</option>
                <option value="kotawaringin_timur">Kotawaringin Timur</option>
                <!-- Tambahkan opsi lainnya sesuai kabupaten -->
            </select>
        </div>

        <div class="mb-4">
            <label for="triwulan" class="block">Triwulan:</label>
            <select id="triwulan" class="form-select block w-full">
                <option value="Q1-2026">Q1 2026</option>
                <option value="Q2-2026">Q2 2026</option>
                <!-- Tambahkan opsi triwulan lainnya -->
            </select>
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Import Excel</button>
        <button class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>

        <table class="min-w-full mt-4 border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Sub Kategori</th>
                    <th class="border px-4 py-2">Output</th>
                    <th class="border px-4 py-2">Biaya Antara</th>
                    <th class="border px-4 py-2">NTB</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subCategories as $subCategory)
                    <tr>
                        <td class="border px-4 py-2">{{ $subCategory['name'] }}</td>
                        <td class="border px-4 py-2">
                            <input type="number" class="input-output border px-2 py-1" value="0" />
                        </td>
                        <td class="border px-4 py-2">
                            <input type="number" class="input-biaya border px-2 py-1" value="0" />
                        </td>
                        <td class="border px-4 py-2 ntb" data-ntb="0">0.00</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.input-output, .input-biaya').forEach(input => {
            input.addEventListener('input', e => {
                const row = e.target.closest('tr');
                const output = parseFloat(row.querySelector('.input-output').value) || 0;
                const biaya = parseFloat(row.querySelector('.input-biaya').value) || 0;
                row.querySelector('.ntb').textContent = (output - biaya).toFixed(2);
            });
        });
    </script>
@endsection