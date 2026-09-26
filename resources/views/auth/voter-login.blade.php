@extends('layouts.auth')

@section('title', 'Bilik Suara Pemilih')

@section('content')
<div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden">
    <!-- Top Card Glow -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-emerald-500"></div>

    <div class="text-center mb-6">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            BILIK SUARA PEMILIH
        </span>
        <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang Pemilih</h2>
        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
            Masukkan Nomor Induk Siswa (NIS) atau Nomor Induk Pegawai (NIP) Anda untuk memulai pemilihan.
        </p>
    </div>

    <form action="{{ route('voter.login.post') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="identifier" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Nomor NIS / NIP
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <input
                    type="text"
                    id="identifier"
                    name="identifier"
                    value="{{ old('identifier') }}"
                    placeholder="Contoh: 1001 atau 2001"
                    required
                    autofocus
                    autocomplete="off"
                    class="w-full pl-11 pr-4 py-3 bg-slate-950/70 border @error('identifier') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-150 text-sm font-medium"
                >
            </div>
            @error('identifier')
                <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 hover:from-indigo-500 to-violet-600 hover:to-violet-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Masuk Bilik Suara
        </button>
    </form>

    <!-- Demo Quick Fill Helpers -->
    <div class="mt-6 pt-5 border-t border-slate-800">
        <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-2 text-center">Akun Uji Coba Demo:</p>
        <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
            <button type="button" onclick="document.getElementById('identifier').value='1001'" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 hover:border-slate-600 transition-colors">
                <span class="text-indigo-400 font-semibold">1001</span> (Siswa)
            </button>
            <button type="button" onclick="document.getElementById('identifier').value='010109'" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 hover:border-slate-600 transition-colors">
                <span class="text-indigo-400 font-semibold">010109</span> (Siswa)
            </button>
            <button type="button" onclick="document.getElementById('identifier').value='2001'" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 hover:border-slate-600 transition-colors">
                <span class="text-indigo-400 font-semibold">2001</span> (Guru)
            </button>
        </div>
    </div>

    <!-- Staff Portal Link -->
    <div class="mt-6 pt-4 border-t border-slate-800/80 text-center">
        <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-indigo-400 transition-colors font-medium">
            <span>Login Petugas / Administrator?</span>
            <span class="underline">Masuk di sini &rarr;</span>
        </a>
    </div>
</div>
@endsection
