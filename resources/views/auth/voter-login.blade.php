@extends('layouts.auth')

@section('title', 'Bilik Suara Pemilih')

@section('content')
<div class="space-y-4">
    <!-- Tab Navigasi Pemisah Jenis Login -->
    <div class="grid grid-cols-2 p-1 bg-slate-900 border border-slate-800 rounded-xl text-xs font-medium">
        <a href="{{ route('voter.login') }}" class="py-2.5 px-3 text-center rounded-lg bg-slate-800 text-white font-semibold shadow-sm transition-colors">
            Bilik Suara (Pemilih)
        </a>
        <a href="{{ route('admin.login') }}" class="py-2.5 px-3 text-center rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
            Petugas & Admin
        </a>
    </div>

    <!-- Card Form Login Pemilih -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 sm:p-7 shadow-lg">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-white tracking-tight">Bilik Suara Pemilih</h2>
            <p class="text-xs text-slate-400 mt-1">
                Masukkan Nomor Induk Siswa (NIS) atau Nomor Induk Pegawai (NIP) Anda yang terdaftar.
            </p>
        </div>

        <form action="{{ route('voter.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="identifier" class="block text-xs font-medium text-slate-300 mb-1.5">
                    Nomor NIS / NIP
                </label>
                <input
                    type="text"
                    id="identifier"
                    name="identifier"
                    value="{{ old('identifier') }}"
                    placeholder="Masukkan nomor NIS atau NIP..."
                    required
                    autofocus
                    autocomplete="off"
                    class="w-full px-3.5 py-2.5 bg-slate-950 border @error('identifier') border-rose-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                >
                @error('identifier')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-sm"
            >
                <span>Masuk ke Bilik Suara</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
