@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil')

@section('content')

    <div class="max-w-xl">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Info Akun --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-14 h-14 bg-slate-800 rounded-full flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="font-bold text-slate-800 text-lg">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-slate-400">{{ ucfirst(auth()->user()->role) }} — {{ auth()->user()->username }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="text-xs text-slate-400 mb-1">Username</div>
                    <div class="font-semibold text-slate-700">{{ auth()->user()->username }}</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="text-xs text-slate-400 mb-1">Role</div>
                    <div class="font-semibold text-slate-700">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 col-span-2">
                    <div class="text-xs text-slate-400 mb-1">Email</div>
                    <div class="font-semibold text-slate-700">{{ auth()->user()->email ?? '-' }}</div>
                </div>
            </div>

        </div>

        {{-- Edit Profil --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h3 class="text-base font-semibold text-slate-800 mb-5">Edit Profil</h3>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 my-5"></div>

                <p class="text-xs text-slate-400 mb-4">Kosongkan field password jika tidak ingin mengubah password.</p>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Password Baru</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

@endsection