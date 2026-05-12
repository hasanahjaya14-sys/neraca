@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-5">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen User</h2>
            <p class="text-slate-400 text-sm mt-1">Kelola akun pengguna aplikasi.</p>
        </div>
        <button onclick="toggleModal('modalTambah')"
            class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
            + Tambah User
        </button>
    </div>

    {{-- TABEL USER --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">No</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama
                        </th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            Username</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Role
                        </th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition">

                            <td class="px-6 py-3 text-sm text-slate-400">{{ $i + 1 }}</td>

                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">{{ $user->name }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-3 text-sm text-slate-500 font-mono">{{ $user->username }}</td>

                            <td class="px-6 py-3">
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full
                                            {{ $user->role === 'provinsi' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="toggleModal('modalPassword{{ $user->id }}')"
                                        class="border border-slate-200 hover:bg-slate-50 text-slate-600 px-3 py-1.5 rounded-lg text-xs transition">
                                        Ganti Password
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                            onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="border border-red-200 hover:bg-red-50 text-red-500 px-3 py-1.5 rounded-lg text-xs transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>

                        {{-- MODAL GANTI PASSWORD PER USER --}}
                        <div id="modalPassword{{ $user->id }}"
                            class="hidden fixed inset-0 bg-black/30 flex items-center justify-center z-50"
                            onclick="toggleModal('modalPassword{{ $user->id }}')">
                            <div class="bg-white rounded-2xl shadow-lg p-6 w-80" onclick="event.stopPropagation()">
                                <h3 class="text-base font-bold text-slate-800 mb-1">Ganti Password</h3>
                                <p class="text-xs text-slate-400 mb-5">{{ $user->name }} ({{ $user->username }})</p>
                                <form method="POST" action="{{ route('users.password', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Password Baru</label>
                                        <input type="password" name="password" placeholder="Minimal 6 karakter"
                                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="toggleModal('modalPassword{{ $user->id }}')"
                                            class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 py-2.5 rounded-xl text-sm transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-xl text-sm transition">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Belum ada user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH USER --}}
    <div id="modalTambah" class="hidden fixed inset-0 bg-black/30 flex items-center justify-center z-50"
        onclick="toggleModal('modalTambah')">
        <div class="bg-white rounded-2xl shadow-lg p-6 w-96" onclick="event.stopPropagation()">

            <h3 class="text-base font-bold text-slate-800 mb-5">Tambah User</h3>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Nama lengkap"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Username</label>
                    <input type="text" name="username" placeholder="Username unik"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Password</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Role</label>
                    <select name="role"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="kabko">Kabko</option>
                        <option value="provinsi">Provinsi</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Kabupaten/Kota</label>
                    <select name="kabko"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Pilih Kabko (opsional) —</option>
                        @foreach(['Kotawaringin Barat', 'Kotawaringin Timur', 'Kapuas', 'Barito Selatan', 'Barito Utara', 'Katingan', 'Seruyan', 'Sukamara', 'Lamandau', 'Gunung Mas', 'Pulang Pisau', 'Murung Raya', 'Barito Timur', 'Palangka Raya'] as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="toggleModal('modalTambah')"
                        class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 py-2.5 rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-xl text-sm transition">
                        Tambah
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
@endpush