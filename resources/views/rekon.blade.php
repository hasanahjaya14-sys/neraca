<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekonsiliasi Indikator</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen p-8">

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    Rekonsiliasi Indikator
                </h1>

                <p class="text-slate-500 mt-2">
                    Tingkat Pengangguran Terbuka (TPT)
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full lg:w-auto">

                <!-- TARGET -->
                <div class="bg-slate-100 rounded-xl p-4 min-w-[180px]">

                    <div class="text-sm text-slate-500">
                        Target Provinsi
                    </div>

                    <div class="text-3xl font-bold text-slate-800 mt-1">
                        5.4000
                    </div>

                </div>

                <!-- HASIL -->
                <div class="bg-slate-100 rounded-xl p-4 min-w-[180px]">

                    <div class="text-sm text-slate-500">
                        Hasil Simulasi
                    </div>

                    <div id="hasilProvinsi"
                        class="text-3xl font-bold text-red-600 mt-1">

                        5.2100
                    </div>

                </div>

                <!-- STATUS -->
                <div id="statusBox"
                    class="rounded-xl p-4 min-w-[180px] bg-red-100">

                    <div class="text-sm text-red-600">
                        Status
                    </div>

                    <div id="statusText"
                        class="text-xl font-bold text-red-700 mt-1">

                        Belum Seimbang
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- INFO -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

        <div class="text-sm text-slate-500">

            Terakhir diperbarui:
            <span class="font-semibold text-slate-700">
                14 Mei 2026 14:25:03
            </span>

        </div>

        <div class="flex items-center gap-3">

            <div class="text-sm text-slate-500">
                Refresh otomatis dalam:
                <span id="countdown" class="font-bold text-slate-700">
                    05:00
                </span>
            </div>

            <button
                class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl transition">

                Refresh Data

            </button>

        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full table-fixed">

                <thead class="bg-slate-50 border-b">

                    <tr>

                        <th class="w-[30%] text-left px-6 py-4 text-sm font-semibold text-slate-600">
                            Kabupaten/Kota
                        </th>

                        <th class="w-[15%] text-center px-6 py-4 text-sm font-semibold text-slate-600">
                            Nilai Saat Ini
                        </th>

                        <th class="w-[20%] text-center px-6 py-4 text-sm font-semibold text-slate-600">
                            Simulasi Baru
                        </th>

                        <th class="w-[15%] text-center px-6 py-4 text-sm font-semibold text-slate-600">
                            Selisih
                        </th>

                        <th class="w-[20%] text-center px-6 py-4 text-sm font-semibold text-slate-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <!-- ROW -->
                    <tr class="border-b">

                        <td class="px-6 py-4 font-medium text-slate-700">
                            Kapuas
                        </td>

                        <td class="px-6 py-4 text-center font-medium">
                            5.1000
                        </td>

                        <td class="px-6 py-4 text-center">

                            <input
                                type="number"
                                step="0.0001"
                                value="5.1000"
                                data-current="5.1000"
                                class="nilaiInput w-32 border rounded-xl px-3 py-2 text-center">

                        </td>

                        <td class="selisihCell px-6 py-4 text-center text-slate-500 font-medium">
                            +0.0000
                        </td>

                        <td class="px-6 py-4 text-center">

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition">

                                Simpan

                            </button>

                        </td>

                    </tr>

                    <!-- ROW -->
                    <tr class="border-b">

                        <td class="px-6 py-4 font-medium text-slate-700">
                            Pulang Pisau
                        </td>

                        <td class="px-6 py-4 text-center font-medium">
                            5.3500
                        </td>

                        <td class="px-6 py-4 text-center">

                            <input
                                type="number"
                                step="0.0001"
                                value="5.3500"
                                data-current="5.3500"
                                class="nilaiInput w-32 border rounded-xl px-3 py-2 text-center">

                        </td>

                        <td class="selisihCell px-6 py-4 text-center text-slate-500 font-medium">
                            +0.0000
                        </td>

                        <td class="px-6 py-4 text-center">

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition">

                                Simpan

                            </button>

                        </td>

                    </tr>

                    <!-- ROW -->
                    <tr>

                        <td class="px-6 py-4 font-medium text-slate-700">
                            Barito Selatan
                        </td>

                        <td class="px-6 py-4 text-center font-medium">
                            5.1800
                        </td>

                        <td class="px-6 py-4 text-center">

                            <input
                                type="number"
                                step="0.0001"
                                value="5.1800"
                                data-current="5.1800"
                                class="nilaiInput w-32 border rounded-xl px-3 py-2 text-center">

                        </td>

                        <td class="selisihCell px-6 py-4 text-center text-slate-500 font-medium">
                            +0.0000
                        </td>

                        <td class="px-6 py-4 text-center">

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition">

                                Simpan

                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="/js/rekon.js"></script>

</body>
</html>