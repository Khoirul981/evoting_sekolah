@extends('layouts.auth')

@section('title', 'Login Pengelola & Administrator')

@section('content')
<div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden">
    <!-- Top Card Glow -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-600 via-indigo-600 to-sky-500"></div>

    <div class="text-center mb-6">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-violet-500/10 text-violet-400 border border-violet-500/20 mb-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            PORTAL PENGELOLA
        </span>
        <h2 class="text-2xl font-bold text-white tracking-tight">Login Pengelola Sistem</h2>
        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
            Khusus Super Administrator dan Petugas Panitia Pemilihan Sekolah.
        </p>
    </div>

    <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@sekolah.sch.id"
                    required
                    autofocus
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-950/70 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-150 text-sm font-medium"
                >
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Kata Sandi (Password)
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-950/70 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-150 text-sm font-medium"
                >
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-violet-600 focus:ring-violet-500 focus:ring-offset-slate-900">
                <span>Ingat Sesi Saya</span>
            </label>
            <span class="text-slate-500">Akses Terproteksi</span>
        </div>

        <button
            type="submit"
            class="w-full mt-2 py-3 px-4 bg-gradient-to-r from-violet-600 hover:from-violet-500 to-indigo-600 hover:to-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-violet-600/30 hover:shadow-violet-600/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Masuk ke Panel Pengelola
        </button>
    </form>

    <!-- Demo Quick Fill Helpers -->
    <div class="mt-5 pt-4 border-t border-slate-800">
        <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-2 text-center">Login Cepat Demo (Password: password):</p>
        <div class="flex flex-col sm:flex-row gap-2 text-xs">
            <button
                type="button"
                onclick="document.getElementById('email').value='admin@sekolah.sch.id'; document.getElementById('password').value='password'"
                class="flex-1 py-1.5 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 hover:border-slate-600 text-center transition-colors"
            >
                <div class="text-violet-400 font-semibold">Super Admin</div>
                <div class="text-[10px] text-slate-400 truncate">admin@sekolah.sch.id</div>
            </button>
            <button
                type="button"
                onclick="document.getElementById('email').value='petugas1@sekolah.sch.id'; document.getElementById('password').value='password'"
                class="flex-1 py-1.5 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 hover:border-slate-600 text-center transition-colors"
            >
                <div class="text-sky-400 font-semibold">Petugas OSIS</div>
                <div class="text-[10px] text-slate-400 truncate">petugas1@sekolah.sch.id</div>
            </button>
        </div>
    </div>

    <!-- Voter Portal Link -->
    <div class="mt-5 pt-3 border-t border-slate-800/80 text-center">
        <a href="{{ route('voter.login') }}" class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-indigo-400 transition-colors font-medium">
            <span>&larr; Kembali ke Bilik Suara Pemilih</span>
        </a>
    </div>
</div>
@endsection
