@extends('layouts.voter')

@section('title', 'Tanda Terima Partisipasi - ' . $event->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Card Utama Tanda Terima Partisipasi (Clean White Theme) -->
    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-9 shadow-sm text-center space-y-6">


        <!-- Header Status & Ucapan Terima Kasih -->
        <div class="space-y-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider">
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
            <div class="space-y-2 pb-3 sm:pb-0 border-b-2 sm:border-b-0 sm:border-r-2 border-slate-300 sm:pr-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Data Pemilih
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
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $voter->type === 'GURU' ? 'bg-orange-950/90 text-orange-400 border-orange-700/70' : 'bg-cyan-950/80 text-cyan-300 border-cyan-700/60' }}">
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
                    <p class="font-bold text-slate-900 leading-snug" title="{{ $event->title }}">{{ $event->title }}</p>
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

        <!-- Tombol Aksi Navigasi: Live Perolehan Suara & Kembali ke Lobi -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('voter.events.monitoring', $event->room_code) }}"
                class="w-full sm:w-auto px-6 py-3.5 bg-cyan-700 hover:bg-cyan-800 active:bg-cyan-900 text-white font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Lihat Live Perolehan Suara</span>
            </a>

            <a
                href="{{ route('voter.dashboard') }}"
                class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-2 border border-slate-200"
            >
                <span>Kembali ke Lobi Event</span>
            </a>
        </div>

    </div>

</div>
@endsection
