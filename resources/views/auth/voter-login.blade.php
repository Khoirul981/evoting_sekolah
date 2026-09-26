@extends('layouts.auth')

@section('title', 'Bilik Suara Pemilih')

@section('content')
<div class="bg-slate-900 border border-slate-800 rounded-xl p-6 sm:p-7 shadow-sm">
    <div class="mb-5">
        <h2 class="text-xl font-bold text-white tracking-tight">Masuk Bilik Suara</h2>
        <p class="text-xs text-slate-400 mt-1">
            Silakan masukkan Nomor Induk Siswa (NIS) atau Nomor Induk Pegawai (NIP) Anda.
        </p>
    </div>

    <form action="{{ route('voter.login.post') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="identifier" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Nomor NIS / NIP
            </label>
            <input
                type="text"
                id="identifier"
                name="identifier"
                value="{{ old('identifier') }}"
                placeholder="Masukkan nomor NIS atau NIP"
                required
                autofocus
                autocomplete="off"
                class="w-full px-3.5 py-2.5 bg-slate-950 border @error('identifier') border-rose-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium"
            >
            @error('identifier')
                <p class="mt-1.5 text-xs text-rose-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full mt-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-lg text-sm transition-colors cursor-pointer"
        >
            Masuk ke Bilik Suara &rarr;
        </button>
    </form>
