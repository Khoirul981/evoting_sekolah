@extends('layouts.auth')

@section('title', 'Bilik Suara Pemilih')
@section('body_class', 'bg-slate-100 text-slate-900')
@section('title_color', 'text-slate-900')
@section('subtitle_color', 'text-slate-600')
@section('status_color', 'text-slate-700')
@section('footer_color', 'text-slate-500')

@section('header_badge')
<div class="w-12 h-12 rounded-xl bg-white border border-slate-300 flex items-center justify-center text-slate-900 shadow-sm">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
    </svg>
</div>
@endsection

@section('header_subtitle', 'Portal Bilik Suara Siswa & Guru')

@section('content')
<div class="space-y-4 w-full">
    <!-- Tab Navigasi Pemisah Jenis Login (Tema Putih Bersih) -->
    <div class="grid grid-cols-2 p-1 bg-slate-200/80 border border-slate-300 rounded-xl text-xs font-medium shadow-inner">
        <a href="{{ route('voter.login') }}" class="py-2.5 px-3 text-center rounded-lg bg-white text-slate-900 font-semibold shadow-sm transition-colors">
            Bilik Suara (Pemilih)
        </a>
        <a href="{{ route('admin.login') }}" class="py-2.5 px-3 text-center rounded-lg text-slate-600 hover:text-slate-900 transition-colors">
            Petugas & Admin
        </a>
    </div>

    <!-- Card Form Login Pemilih (Tema Putih Bersih) -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm">
        <div class="mb-5">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-300 mb-2.5">
                <span>AKSES PEMILIH</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Masuk Bilik Suara</h2>
            <p class="text-xs text-slate-600 mt-1">
                Silakan masukkan NIS untuk SISWA atau NIP untuk GURU.
            </p>
        </div>

        <form action="{{ route('voter.login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="identifier" class="block text-sm font-semibold text-slate-800 mb-2">
                    Nomor NIS / NIP
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="identifier"
                        name="identifier"
                        value="{{ old('identifier') }}"
                        placeholder="Ketik nomor NIS atau NIP..."
                        required
                        autofocus
                        autocomplete="off"
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 @error('identifier') border-rose-500 @else border-slate-300 hover:border-slate-400 @enderror rounded-xl text-slate-900 placeholder:text-slate-400 placeholder:text-sm text-base sm:text-lg font-mono font-semibold tracking-wider focus:outline-none focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all shadow-inner"
                    >
                </div>
                @error('identifier')
                    <p class="mt-2 text-xs text-rose-600 flex items-center gap-1 font-medium">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 active:bg-black text-white font-semibold rounded-xl text-base shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <span>Masuk ke Bilik Suara</span>
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-slate-100 text-center">
        </div>
    </div>
</div>
@endsection
