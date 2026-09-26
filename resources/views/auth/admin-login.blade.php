@extends('layouts.auth')

@section('title', 'Login Pengelola & Administrator')

@section('content')
<div class="bg-slate-900 border border-slate-800 rounded-xl p-6 sm:p-7 shadow-sm">
    <div class="mb-5">
        <h2 class="text-xl font-bold text-white tracking-tight">Login Pengelola Sistem</h2>
        <p class="text-xs text-slate-400 mt-1">
            Silakan masukkan akun Super Administrator atau Petugas Pemilihan.
        </p>
    </div>

    <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Alamat Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@sekolah.sch.id"
                required
                autofocus
                class="w-full px-3.5 py-2.5 bg-slate-950 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-rose-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Kata Sandi
            </label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                required
                class="w-full px-3.5 py-2.5 bg-slate-950 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium"
            >
            @error('password')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between text-xs pt-0.5">
            <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900">
                <span>Ingat Sesi Saya</span>
            </label>
        </div>

        <button
            type="submit"
            class="w-full mt-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-lg text-sm transition-colors cursor-pointer"
        >
            Masuk ke Panel Pengelola &rarr;
        </button>
    </form>
