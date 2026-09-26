@extends('layouts.auth')

@section('title', 'Login Pengelola & Administrator')

@section('content')
<div class="space-y-4">
    <!-- Tab Navigasi Pemisah Jenis Login -->
    <div class="grid grid-cols-2 p-1 bg-slate-900 border border-slate-800 rounded-xl text-xs font-medium">
        <a href="{{ route('voter.login') }}" class="py-2.5 px-3 text-center rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
            Bilik Suara (Pemilih)
        </a>
        <a href="{{ route('admin.login') }}" class="py-2.5 px-3 text-center rounded-lg bg-slate-800 text-white font-semibold shadow-sm transition-colors">
            Petugas & Admin
        </a>
    </div>

    <!-- Card Form Login Pengelola -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 sm:p-7 shadow-lg">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-white tracking-tight">Login Pengelola</h2>
            <p class="text-xs text-slate-400 mt-1">
                Akses panel administrasi untuk Super Administrator dan Petugas Panitia.
            </p>
        </div>

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">
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
                    class="w-full px-3.5 py-2.5 bg-slate-950 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full px-3.5 py-2.5 bg-slate-950 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                >
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs pt-0.5">
                <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-blue-600 focus:ring-blue-500">
                    <span>Ingat sesi saya</span>
                </label>
            </div>

            <button
                type="submit"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-sm"
            >
                <span>Masuk ke Panel Pengelola</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
