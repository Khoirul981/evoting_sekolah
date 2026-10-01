@extends('layouts.voter')

@section('title', 'Tanda Terima Partisipasi - ' . $event->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Card Utama Tanda Terima Partisipasi (Clean White Theme) -->
    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-9 shadow-sm text-center space-y-6">

        <!-- Icon Centang Sukses Besar -->
        <div class="relative w-20 h-20 mx-auto">
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-xs">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center border-2 border-white shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>

        <!-- Header Status & Ucapan Terima Kasih -->
        <div class="space-y-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                <span>Hak Suara Berhasil Digunakan</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Terima Kasih Atas Partisipasi Anda!
            </h1>

            <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto leading-relaxed">
                Pilihan suara Anda telah dicatat secara sah ke dalam sistem e-voting. Sesuai asas <strong>LUBER</strong>, pilihan suara Anda <strong>100% rahasia</strong> dan telah dipisahkan dari identitas pemilih.
            </p>
        </div>

        <!-- Ringkasan Kartu Bukti Partisipasi (2 Kolom) -->
        <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-5 text-left grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <!-- Kolom 1: Identitas Pemilih -->
            <div class="space-y-2 pb-3 sm:pb-0 border-b sm:border-b-0 sm:border-r border-slate-200/80 sm:pr-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Data Pemilih (DPT)
                </p>
                <div>
                    <span class="text-slate-500 text-[11px]">Nama Lengkap:</span>
                    <p class="font-bold text-slate-900 text-sm truncate">{{ $voter->name }}</p>
                </div>
                <div>
                    <span class="text-slate-500 text-[11px]">Nomor Identitas (NIS/NIP):</span>
                    <p class="font-mono font-bold text-slate-800">{{ $voter->identifier }}</p>
                </div>
                <div>
                    <span class="text-slate-500 text-[11px]">Kategori Pemilih:</span>
                    <p>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $voter->type === 'GURU' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-sky-100 text-sky-700 border border-sky-200' }}">
                            {{ $voter->type }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Kolom 2: Detail Partisipasi Event -->
            <div class="space-y-2 sm:pl-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Detail Pemilihan
                </p>
                <div>
                    <span class="text-slate-500 text-[11px]">Event Pemilihan:</span>
                    <p class="font-bold text-slate-900 line-clamp-1" title="{{ $event->title }}">{{ $event->title }}</p>
                </div>
                <div>
                    <span class="text-slate-500 text-[11px]">Kode Ruangan:</span>
                    <p class="font-mono font-bold text-slate-800">{{ $event->room_code }}</p>
                </div>
                <div>
                    <span class="text-slate-500 text-[11px]">Waktu Pencatatan Suara:</span>
                    <p class="font-mono text-slate-800 font-semibold">
                        {{ $participation->voted_at ? $participation->voted_at->translatedFormat('d F Y, H:i:s') . ' WIB' : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Callout Jaminan Keamanan Sistem -->
        <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-2xl p-4 text-left flex items-start gap-3 text-xs text-emerald-900">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <div class="leading-relaxed">
                <strong class="font-bold">Integritas Suara Terjamin:</strong> Hak suara Anda telah terhitung 1 kali secara anonim. Anda tidak dapat melakukan pemilihan ulang pada event ini demi menjaga prinsip jujur dan adil.
            </div>
        </div>

        <!-- Tombol Aksi Navigasi -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('voter.dashboard') }}"
                class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs sm:text-sm transition-colors flex items-center justify-center gap-2 shadow-2xs"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Kembali ke Lobi</span>
            </a>

            <form action="{{ route('voter.logout') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button
                    type="submit"
                    class="w-full sm:w-auto px-6 py-3 bg-slate-900 hover:bg-slate-800 active:bg-black text-white font-bold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Selesai &amp; Keluar</span>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
