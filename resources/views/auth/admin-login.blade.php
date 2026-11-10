@extends('layouts.auth')

@section('title', 'Login Pengelola & Administrator')
@section('body_class', 'bg-[#140206] text-rose-100')
@section('title_color', 'text-white')
@section('subtitle_color', 'text-rose-300/80')
@section('status_color', 'text-rose-300')
@section('footer_color', 'text-rose-400/60')

@section('header_badge')
<div class="w-20 h-20 flex items-center justify-center mb-0">
    <img src="{{ asset('images/logo_votingyuk.png') }}" alt="Logo VOTINGYUK" class="w-full h-full object-contain drop-shadow-md">
</div>
@endsection

@section('header_subtitle', 'Portal Pengelola & Administrator')

@section('content')
<div class="space-y-4 w-full">
    <!-- Tab Navigasi Pemisah Jenis Login (Tema Merah Marun) -->
    <div class="grid grid-cols-2 p-1 bg-[#24060e] border border-rose-950/80 rounded-xl text-xs font-medium shadow-inner">
        <a href="{{ route('voter.login') }}" class="py-2.5 px-3 text-center rounded-lg text-rose-300/70 hover:text-rose-100 transition-colors">
            Bilik Suara (Pemilih)
        </a>
        <a href="{{ route('admin.login') }}" class="py-2.5 px-3 text-center rounded-lg bg-[#800020] text-white font-semibold shadow-sm transition-colors">
            Petugas & Admin
        </a>
    </div>

    <!-- Card Form Login Pengelola (Tema Merah Marun) -->
    <div class="bg-[#1e050b] border border-rose-900/40 rounded-2xl p-6 sm:p-7 shadow-2xl">
        <div class="mb-5">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-rose-950/80 text-rose-300 border border-rose-900/60 mb-2.5">

                <span>PANEL PENGELOLA</span>
            </div>
            <h2 class="text-xl font-bold text-white tracking-tight">Login Pengelola</h2>
            <p class="text-xs text-rose-300/80 mt-1">
                Akses panel administrasi Super Admin dan Petugas Panitia Event.
            </p>
        </div>

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-rose-200 mb-1.5">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="w-full pl-10 pr-4 py-3 bg-[#100104] border @error('email') border-rose-500 @else border-rose-900/60 @enderror rounded-xl text-white placeholder:text-rose-400/30 text-sm focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-900/30 transition-all shadow-inner"
                    >
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-rose-200 mb-1.5">
                    Password Akun
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="w-full pl-10 pr-11 py-3 bg-[#100104] border @error('password') border-rose-500 @else border-rose-900/60 @enderror rounded-xl text-white placeholder:text-rose-400/30 text-sm focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-900/30 transition-all shadow-inner"
                    >
                    <!-- Tombol Toggle Lihat / Sembunyikan Password -->
                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-rose-400/70 hover:text-white focus:outline-none transition-colors cursor-pointer"
                        title="Tampilkan / Sembunyikan Password"
                        aria-label="Tampilkan atau sembunyikan kata sandi"
                    >
                        <!-- Icon Mata Terbuka -->
                        <svg id="eyeOpenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Icon Mata Tertutup -->
                        <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkbox Remember Me -->
            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    value="1"
                    {{ old('remember') ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-rose-900/60 bg-[#100104] text-rose-600 focus:ring-rose-500 focus:ring-offset-0 focus:ring-2 cursor-pointer transition-colors"
                >
                <label for="remember" class="ml-2 text-sm text-rose-200 cursor-pointer select-none">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 bg-[#881337] hover:bg-[#9f1239] active:bg-[#700f2b] text-white font-semibold rounded-xl text-sm shadow-lg shadow-rose-950/60 transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <span>Login</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpenIcon = document.getElementById('eyeOpenIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                if (isPassword) {
                    eyeOpenIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    eyeOpenIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush
