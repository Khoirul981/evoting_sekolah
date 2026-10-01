@extends('layouts.petugas')

@section('title', 'Live Monitoring & Rekapitulasi - ' . $event->title)
@section('page_title', 'Live Monitoring Suara')

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. BREADCRUMB & AKSI NAVIGASI
         ========================================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Breadcrumb Links -->
        <div class="flex items-center gap-2 text-xs text-slate-400 flex-wrap">
            <a href="{{ route('petugas.events.index') }}" class="hover:text-cyan-400 transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Daftar Event</span>
            </a>
            <span class="text-slate-600">/</span>
            <span class="text-white font-medium truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
            <span class="text-slate-600">/</span>
            <span class="text-cyan-400 font-bold">Live Monitoring</span>
        </div>

        <!-- Action Buttons: Export CSV, Kelola Kandidat, Kembali -->
        <div class="flex items-center gap-2 flex-wrap">
            <!-- Tombol Export CSV Rekapitulasi -->
            <a
                href="{{ route('petugas.events.export', $event) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600/20 hover:bg-emerald-600 text-emerald-300 hover:text-white text-xs font-bold border border-emerald-500/40 transition-all cursor-pointer shadow-xs"
                title="Unduh laporan lengkap rekapitulasi suara dalam format CSV / Excel"
            >
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export Rekapitulasi (CSV)</span>
            </a>

            <!-- Tombol Kelola Kandidat -->
            <a
                href="{{ route('petugas.candidates.index', $event) }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0a1832] hover:bg-cyan-950/70 text-slate-300 hover:text-white text-xs font-semibold border border-cyan-900/50 transition-all cursor-pointer shadow-xs"
            >
                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Kelola {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}</span>
            </a>

            <!-- Tombol Kembali -->
            <a
                href="{{ route('petugas.events.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0a1832] hover:bg-cyan-950/70 text-slate-300 hover:text-white text-xs font-semibold border border-cyan-900/50 transition-all cursor-pointer shadow-xs"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- =========================================================================
         2. EVENT OVERVIEW HEADER CARD
         ========================================================================= -->
    <div class="p-5 sm:p-6 rounded-2xl bg-linear-to-br from-[#071226] via-[#091b36] to-[#042438] border border-cyan-900/60 shadow-xl relative overflow-hidden">
        <!-- Glow Ambient -->
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Informasi Utama Event -->
            <div class="space-y-2.5 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Badge Kategori: KANDIDAT (Indigo) vs OPSI (Amber) -->
                    @if($event->category === 'KANDIDAT')
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-black uppercase tracking-wider bg-indigo-500/25 text-indigo-200 border border-indigo-400/80 shadow-xs">
                            KANDIDAT
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-black uppercase tracking-wider bg-amber-500/25 text-amber-200 border border-amber-400/80 shadow-xs">
                            OPSI
                        </span>
                    @endif

                    <!-- Badge Status Event -->
                    <span id="eventStatusBadge">
                        @if($event->status === 'PUBLISHED')
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/50 flex items-center gap-1.5 shadow-xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>PUBLISHED (SEDANG AKTIF)</span>
                            </span>
                        @elseif($event->status === 'DRAFT')
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/50 shadow-xs">
                                DRAFT (BELUM AKTIF)
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase bg-rose-500/20 text-rose-300 border border-rose-500/50 shadow-xs">
                                CLOSED (SELESAI)
                            </span>
                        @endif
                    </span>

                    <!-- Room Code Badge dengan Tombol Copy -->
                    <button
                        type="button"
                        onclick="copyRoomCode('{{ $event->room_code }}')"
                        id="btnCopyRoomCode"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#050d1a] hover:bg-cyan-950 text-cyan-300 font-mono text-xs font-bold border border-cyan-800/60 shadow-xs transition-colors cursor-pointer"
                        title="Klik untuk menyalin kode room"
                    >
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <span>ROOM: {{ $event->room_code }}</span>
                        <svg class="w-3 h-3 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    {{ $event->title }}
                </h1>

                @if($event->description)
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        {{ $event->description }}
                    </p>
                @endif

                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-400 pt-1">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>
                            Mulai: <strong class="text-slate-200">{{ $event->start_time ? $event->start_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Manual / Langsung' }}</strong>
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            Selesai: <strong class="text-slate-200">{{ $event->end_time ? $event->end_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Ditutup Manual' }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kontrol Realtime Live Stream & Timestamp -->
            <div class="flex flex-col items-start lg:items-end gap-3 shrink-0">
                <!-- Status Live Polling Indicator -->
                <div class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 shadow-xs">
                    <span id="livePulseDot" class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-200">Live Auto-Update</span>
                    <span class="text-slate-600">|</span>
                    <span id="pollingStatusText" class="text-[11px] font-mono text-cyan-400">Aktif (3s)</span>
                </div>

                <!-- Tombol Toggle Polling & Refresh Manual -->
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        onclick="toggleAutoRefresh()"
                        id="btnToggleAutoRefresh"
                        class="px-3 py-1.5 rounded-lg bg-[#0a1832] hover:bg-cyan-950 text-slate-300 hover:text-white text-xs font-semibold border border-cyan-900/60 transition-colors cursor-pointer shadow-xs"
                    >
                        <span>Jeda Auto-Refresh</span>
                    </button>

                    <button
                        type="button"
                        onclick="fetchLiveMetrics()"
                        id="btnManualRefresh"
                        class="p-1.5 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-xs"
                        title="Muat ulang data sekarang"
                    >
                        <svg id="refreshIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>

                <!-- Waktu Terakhir Diperbarui -->
                <p class="text-[10px] text-slate-400 font-mono">
                    Pembaruan terakhir: <span id="lastUpdatedTime" class="text-cyan-300 font-bold">{{ now()->translatedFormat('H:i:s') }} WIB</span>
                </p>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         3. KEY PERFORMANCE METRICS (KPI STATS CARDS)
         ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Suara Masuk (Sah) -->
        <div class="p-5 rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-lg relative overflow-hidden group hover:border-cyan-500/40 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400">Total Suara Masuk</p>
                    <h3 id="statTotalVotes" class="text-2xl sm:text-3xl font-black text-white mt-1">
                        {{ number_format($totalVotes, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center shadow-md shadow-cyan-950/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-cyan-300/80 mt-2.5 flex items-center gap-1 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-ping"></span>
                <span>Suara sah terenkripsi di sistem</span>
            </p>
        </div>

        <!-- 2. Total DPT Pemilih Terdaftar -->
        <div class="p-5 rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-lg relative overflow-hidden group hover:border-cyan-500/40 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400">Total DPT Aktif</p>
                    <h3 id="statTotalVoters" class="text-2xl sm:text-3xl font-black text-slate-100 mt-1">
                        {{ number_format($totalVoters, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center shadow-md shadow-indigo-950/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-indigo-300/80 mt-2.5 font-medium">
                Siswa & Guru berhak memilih
            </p>
        </div>

        <!-- 3. Tingkat Partisipasi (Turnout %) -->
        <div class="p-5 rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-lg relative overflow-hidden group hover:border-cyan-500/40 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400">Tingkat Partisipasi</p>
                    <h3 id="statParticipationRate" class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1">
                        {{ $participationRate }}%
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shadow-md shadow-emerald-950/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <!-- Mini Progress Bar Partisipasi -->
            <div class="w-full bg-[#042438] rounded-full h-1.5 mt-3 overflow-hidden border border-cyan-950">
                <div id="statParticipationBar" class="bg-linear-to-r from-emerald-500 to-cyan-400 h-1.5 rounded-full transition-all duration-700 ease-out" style="width: {{ min(100, $participationRate) }}%;"></div>
            </div>
        </div>

        <!-- 4. Pilihan Unggul Sementara -->
        <div class="p-5 rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-lg relative overflow-hidden group hover:border-cyan-500/40 transition-all">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 pr-2">
                    <p class="text-xs font-semibold text-slate-400">Pilihan Unggul</p>
                    <h3 id="statLeadingCandidate" class="text-base sm:text-lg font-black text-amber-300 mt-1 truncate">
                        {{ $leadingCandidate ? $leadingCandidate->name : 'Belum Ada Suara' }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center shrink-0 shadow-md shadow-amber-950/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
            </div>
            <p id="statLeadingSubtitle" class="text-[11px] text-amber-300/80 mt-2.5 truncate font-medium">
                @if($leadingCandidate && $totalVotes > 0)
                    {{ $leadingCandidate->votes_count }} Suara ({{ $leadingCandidate->percentage }}%)
                @else
                    Menunggu suara masuk
                @endif
            </p>
        </div>
    </div>

    <!-- =========================================================================
         4. REKAPITULASI HASIL & VISUALISASI PROGRESS PEROLEHAN SUARA
         ========================================================================= -->
    <div class="p-6 rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyan-950/80 pb-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Perolehan Suara {{ $event->category === 'KANDIDAT' ? 'Kandidat / Paslon' : 'Opsi Pemilihan' }}</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Grafik perolehan persentase dan akumulasi total suara sah masuk secara langsung.
                </p>
            </div>
            <span class="text-xs text-slate-400 bg-[#050d1a] px-3 py-1.5 rounded-lg border border-cyan-950">
                Total Pilihan: <strong class="text-cyan-300">{{ $candidates->count() }} Pilihan</strong>
            </span>
        </div>

        <!-- Container List Kandidat / Opsi dengan Progress Bar Visual -->
        <div id="candidatesResultsContainer" class="space-y-4">
            @forelse($candidates as $candidate)
                <div
                    id="candidateCard-{{ $candidate->id }}"
                    class="p-4 sm:p-5 rounded-xl bg-[#050d1a] border border-cyan-950 hover:border-cyan-800/60 transition-all shadow-sm space-y-3"
                >
                    <div class="flex items-start sm:items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                        <!-- Identitas Kandidat / Opsi -->
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Nomor Urut Badge -->
                            <div class="w-10 h-10 rounded-xl bg-cyan-600/20 text-cyan-300 border border-cyan-400/50 flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                                #{{ str_pad($candidate->candidate_number, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <!-- Foto Avatar / Placeholder -->
                            @if($candidate->photo)
                                <img
                                    src="{{ asset('storage/' . $candidate->photo) }}"
                                    alt="{{ $candidate->name }}"
                                    class="w-11 h-11 rounded-xl object-cover border border-cyan-900/60 shrink-0 shadow-xs"
                                >
                            @else
                                <div class="w-11 h-11 rounded-xl bg-[#071226] border border-cyan-900/60 text-cyan-400 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $candidate->candidate_number }}
                                </div>
                            @endif

                            <!-- Nama & Deskripsi -->
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-bold text-white truncate">
                                    {{ $candidate->name }}
                                </h3>
                                @if($candidate->vision)
                                    <p class="text-[11px] text-slate-400 truncate max-w-md sm:max-w-xl">
                                        {{ $candidate->vision }}
                                    </p>
                                @elseif($candidate->description)
                                    <p class="text-[11px] text-slate-400 truncate max-w-md sm:max-w-xl">
                                        {{ $candidate->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Angka Perolehan Suara & Persentase -->
                        <div class="flex items-center gap-3 shrink-0 sm:text-right w-full sm:w-auto justify-between sm:justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-cyan-950/60">
                            <div>
                                <span id="candidatePercentage-{{ $candidate->id }}" class="text-lg sm:text-xl font-black text-cyan-300">
                                    {{ $candidate->percentage }}%
                                </span>
                                <p id="candidateVotes-{{ $candidate->id }}" class="text-[11px] text-slate-400 font-medium">
                                    {{ number_format($candidate->votes_count, 0, ',', '.') }} Suara
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Animasi Perolehan Suara -->
                    <div class="w-full bg-[#071226] rounded-full h-3 overflow-hidden border border-cyan-950/80 p-0.5 shadow-inner">
                        <div
                            id="candidateBar-{{ $candidate->id }}"
                            class="h-full rounded-full bg-linear-to-r from-cyan-600 via-cyan-400 to-emerald-400 transition-all duration-700 ease-out"
                            style="width: {{ min(100, $candidate->percentage) }}%;"
                        ></div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center rounded-xl bg-[#050d1a] border border-cyan-950 text-slate-400 text-xs">
                    Belum ada kandidat/opsi yang didaftarkan pada event ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
         5. LOG AKTIVITAS SUARA MASUK (REAL-TIME STREAM & TRANSPARANSI)
         ========================================================================= -->
    <div class="p-6 rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-xl space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyan-950/80 pb-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Log Aktivitas Partisipasi Pemilih (LUBER & JURDIL)</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Menampilkan riwayat pemilih yang telah menggunakan hak suara secara transparan tanpa membocorkan pilihan rahasia.
                </p>
            </div>
            <span class="text-[11px] text-cyan-300 bg-[#050d1a] px-3 py-1.5 rounded-lg border border-cyan-950 font-medium">
                Live Audit Stream
            </span>
        </div>

        <!-- Tabel Log Suara Masuk -->
        <div class="overflow-x-auto rounded-xl border border-cyan-950/80">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-[#050d1a] text-cyan-400 font-bold border-b border-cyan-950">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">Nama Pemilih</th>
                        <th class="py-3 px-4">NIS / NIP</th>
                        <th class="py-3 px-4">Tipe Pemilih</th>
                        <th class="py-3 px-4">Waktu Suara Masuk</th>
                        <th class="py-3 px-4 text-center">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody id="recentVotersTableBody" class="divide-y divide-cyan-950/60 bg-[#071226]/60">
                    @forelse($recentParticipations as $index => $part)
                        <tr class="hover:bg-cyan-950/30 transition-colors">
                            <td class="py-3 px-4 text-center text-slate-500 font-mono">
                                {{ $recentParticipations->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 font-bold text-white">
                                {{ $part->voter->name ?? 'Pemilih' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-cyan-300">
                                {{ $part->voter->identifier ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                @if(($part->voter->type ?? 'SISWA') === 'GURU')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500/20 text-purple-300 border border-purple-500/40">
                                        GURU
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">
                                        SISWA
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-300">
                                <div class="font-medium">
                                    {{ $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i:s') . ' WIB' : '-' }}
                                </div>
                                <div class="text-[10px] text-slate-500">
                                    {{ $part->voted_at ? $part->voted_at->diffForHumans() : '-' }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Tercatat Sah</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyVotersRow">
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada suara pemilih yang masuk pada event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentParticipations->hasPages())
            <div class="pt-2">
                {{ $recentParticipations->links() }}
            </div>
        @endif
    </div>

</div>

<!-- =========================================================================
     6. JAVASCRIPT REAL-TIME POLLING & COPY HELPER
     ========================================================================= -->
@push('scripts')
<script>
    let isAutoRefreshActive = true;
    let pollingInterval = null;
    const POLLING_DELAY_MS = 3000; // Polling data setiap 3 detik

    // Inisialisasi polling saat dokumen siap
    document.addEventListener('DOMContentLoaded', function () {
        startAutoRefresh();
    });

    function startAutoRefresh() {
        if (pollingInterval) clearInterval(pollingInterval);
        isAutoRefreshActive = true;
        updatePollingUIState();

        pollingInterval = setInterval(function () {
            if (isAutoRefreshActive) {
                fetchLiveMetrics();
            }
        }, POLLING_DELAY_MS);
    }

    function stopAutoRefresh() {
        if (pollingInterval) clearInterval(pollingInterval);
        isAutoRefreshActive = false;
        updatePollingUIState();
    }

    function toggleAutoRefresh() {
        if (isAutoRefreshActive) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
            fetchLiveMetrics();
        }
    }

    function updatePollingUIState() {
        const btnToggle = document.getElementById('btnToggleAutoRefresh');
        const statusText = document.getElementById('pollingStatusText');
        const pulseDot = document.getElementById('livePulseDot');

        if (isAutoRefreshActive) {
            btnToggle.innerHTML = '<span>Jeda Auto-Refresh</span>';
            btnToggle.classList.remove('text-cyan-400', 'border-cyan-500/50');
            statusText.textContent = 'Aktif (3s)';
            statusText.className = 'text-[11px] font-mono text-cyan-400';
            pulseDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';
        } else {
            btnToggle.innerHTML = '<span>Lanjutkan Auto-Refresh</span>';
            btnToggle.classList.add('text-cyan-400', 'border-cyan-500/50');
            statusText.textContent = 'Dijeda';
            statusText.className = 'text-[11px] font-mono text-slate-400';
            pulseDot.className = 'w-2.5 h-2.5 rounded-full bg-slate-500';
        }
    }

    // Ambil metrik terbaru via AJAX tanpa refresh halaman
    async function fetchLiveMetrics() {
        const refreshIcon = document.getElementById('refreshIcon');
        if (refreshIcon) refreshIcon.classList.add('animate-spin');

        try {
            const response = await fetch('{{ route('petugas.events.monitor.data', $event) }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Gagal mengambil data live');

            const data = await response.json();

            // 1. Update KPI Stats
            const statTotalVotes = document.getElementById('statTotalVotes');
            const statTotalVoters = document.getElementById('statTotalVoters');
            const statParticipationRate = document.getElementById('statParticipationRate');
            const statParticipationBar = document.getElementById('statParticipationBar');
            const lastUpdatedTime = document.getElementById('lastUpdatedTime');

            if (statTotalVotes) statTotalVotes.textContent = Number(data.total_votes).toLocaleString('id-ID');
            if (statTotalVoters) statTotalVoters.textContent = Number(data.total_voters).toLocaleString('id-ID');
            if (statParticipationRate) statParticipationRate.textContent = data.participation_rate + '%';
            if (statParticipationBar) statParticipationBar.style.width = Math.min(100, data.participation_rate) + '%';
            if (lastUpdatedTime) lastUpdatedTime.textContent = data.server_time;

            // 2. Update Leading Candidate
            let leading = null;
            if (data.candidates && data.candidates.length > 0 && data.total_votes > 0) {
                leading = [...data.candidates].sort((a, b) => b.votes_count - a.votes_count)[0];
            }

            const statLeadingCandidate = document.getElementById('statLeadingCandidate');
            const statLeadingSubtitle = document.getElementById('statLeadingSubtitle');
            if (statLeadingCandidate) {
                statLeadingCandidate.textContent = leading ? leading.name : 'Belum Ada Suara';
            }
            if (statLeadingSubtitle) {
                statLeadingSubtitle.textContent = leading && data.total_votes > 0
                    ? `${leading.votes_count} Suara (${leading.percentage}%)`
                    : 'Menunggu suara masuk';
            }

            // 3. Update Progress Bar Tiap Kandidat
            if (data.candidates) {
                data.candidates.forEach(candidate => {
                    const pctEl = document.getElementById(`candidatePercentage-${candidate.id}`);
                    const votesEl = document.getElementById(`candidateVotes-${candidate.id}`);
                    const barEl = document.getElementById(`candidateBar-${candidate.id}`);

                    if (pctEl) pctEl.textContent = candidate.percentage + '%';
                    if (votesEl) votesEl.textContent = Number(candidate.votes_count).toLocaleString('id-ID') + ' Suara';
                    if (barEl) barEl.style.width = Math.min(100, candidate.percentage) + '%';
                });
            }

            // 4. Update Log Tabel Pemilih Terbaru
            if (data.recent_voters && data.recent_voters.length > 0) {
                const tbody = document.getElementById('recentVotersTableBody');
                if (tbody) {
                    tbody.innerHTML = '';
                    data.recent_voters.forEach((item, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-cyan-950/30 transition-colors';

                        const typeBadge = item.type === 'GURU'
                            ? '<span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500/20 text-purple-300 border border-purple-500/40">GURU</span>'
                            : '<span class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">SISWA</span>';

                        tr.innerHTML = `
                            <td class="py-3 px-4 text-center text-slate-500 font-mono">${index + 1}</td>
                            <td class="py-3 px-4 font-bold text-white">${escapeHtml(item.voter_name)}</td>
                            <td class="py-3 px-4 font-mono text-cyan-300">${escapeHtml(item.identifier)}</td>
                            <td class="py-3 px-4">${typeBadge}</td>
                            <td class="py-3 px-4 text-slate-300">
                                <div class="font-medium">${escapeHtml(item.voted_at)}</div>
                                <div class="text-[10px] text-slate-500">${escapeHtml(item.voted_at_human)}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Tercatat Sah</span>
                                </span>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            }

        } catch (error) {
            console.error('Polling error:', error);
        } finally {
            if (refreshIcon) {
                setTimeout(() => refreshIcon.classList.remove('animate-spin'), 400);
            }
        }
    }

    // Escape string helper untuk mencegah XSS di rendering JS
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Salin Kode Room
    function copyRoomCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            const btn = document.getElementById('btnCopyRoomCode');
            const originalContent = btn.innerHTML;
            btn.innerHTML = `
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-emerald-300">Tersalin!</span>
            `;
            setTimeout(() => {
                btn.innerHTML = originalContent;
            }, 2000);
        });
    }
</script>
@endpush
@endsection
