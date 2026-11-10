@extends('layouts.voter')

@section('title', 'Live Perolehan Suara - ' . $event->title)

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. BREADCRUMB & NAVIGASI AKSI
         ========================================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Breadcrumb Links -->
        <div class="flex items-center gap-2 text-xs text-slate-500 flex-wrap">
            <a href="{{ route('voter.dashboard') }}" class="hover:text-slate-900 transition-colors flex items-center gap-1 font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Dashboard Pemilih</span>
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
            <span class="text-slate-300">/</span>
            <span class="text-cyan-700 font-bold">Live Perolehan Suara</span>
        </div>

        <!-- Tombol Aksi: Bukti Suara / Coblos & Kembali -->
        <div class="flex items-center gap-2 flex-wrap">
            @if($participation && $participation->hasVoted())
                <a
                    href="{{ route('voter.events.success', $event->room_code) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200 transition-all shadow-2xs"
                >
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Lihat Bukti Suara Anda</span>
                </a>
            @else
            @endif
        </div>
    </div>

    <!-- =========================================================================
         2. EVENT OVERVIEW HEADER CARD
         ========================================================================= -->
    <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-2 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Badge Kategori: KANDIDAT vs OPSI -->
                    @if($event->category === 'KANDIDAT')
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs">
                            KANDIDAT
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                            OPSI
                        </span>
                    @endif

                    <!-- Badge Status Event -->
                    <span id="eventStatusBadge">
                        @if($event->status === 'PUBLISHED')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span>Sedang Berlangsung</span>
                            </span>
                        @elseif($event->status === 'CLOSED')
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                Pemilihan Telah Ditutup
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                                {{ $event->status }}
                            </span>
                        @endif
                    </span>

                    <!-- Kode Room -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-slate-100 text-slate-800 font-mono text-xs font-bold border border-slate-200 shadow-2xs">
                        ROOM: {{ $event->room_code }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    {{ $event->title }}
                </h1>

                @if($event->description)
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $event->description }}
                    </p>
                @endif

                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-500 pt-1">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Penyelenggara: <strong class="text-slate-800">{{ $event->creator->name ?? 'Panitia Sekolah' }}</strong></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            Batas: <strong class="text-slate-800">{{ $event->end_time ? $event->end_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Ditutup Manual oleh Panitia' }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Banner Partisipasi Pribadi Pemilih -->
            @if($participation && $participation->hasVoted())
                <div class="lg:w-72 bg-emerald-50/80 border border-emerald-200/90 rounded-2xl p-4 text-xs space-y-1.5 shrink-0">
                    <div class="flex items-center gap-1.5 text-emerald-800 font-bold">
                        <span>Status Hak Suara Anda</span>
                    </div>
                    <p class="text-emerald-950 font-bold text-sm">Tercatat Sah & Rahasia</p>
                    <p class="text-[11px] text-emerald-700">
                        Dicoblos pada: <strong class="font-mono text-emerald-900">{{ $participation->voted_at ? $participation->voted_at->translatedFormat('d/m/Y H:i') : '-' }} WIB</strong>
                    </p>
                </div>
            @else
                <div class="lg:w-72 bg-amber-50/80 border border-amber-200/90 rounded-2xl p-4 text-xs space-y-2 shrink-0">
                    <div class="flex items-center justify-between gap-1.5">
                        <span class="text-amber-800 font-bold">Status Hak Suara Anda</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-200 text-amber-900">BELUM MEMILIH</span>
                    </div>
                    <p class="text-[11px] text-amber-800 leading-snug">
                        Anda belum menggunakan hak suara untuk event ini. Masuk ke bilik suara jika ingin berpartisipasi.
                    </p>
                    <a
                        href="{{ route('voter.events.vote', $event->room_code) }}"
                        class="block text-center w-full py-2 px-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs shadow-2xs transition-all"
                    >
                        Masuk Bilik Suara
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- =========================================================================
         3. STATISTIK METRIK CEPAT (2 KARTU)
         ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Total Pemilih Terdaftar DPT -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Pemilih</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span id="statTotalVoters" class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">{{ number_format($totalVoters, 0, ',', '.') }}</span>
                <span class="text-xs text-slate-400 font-medium">Pemilih</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Siswa & Guru Terdaftar</p>
        </div>

        <!-- Total Suara Sah Masuk -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Suara Masuk</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span id="statTotalVotes" class="text-2xl sm:text-3xl font-black text-cyan-700 font-mono">{{ number_format($totalVotes, 0, ',', '.') }}</span>
                <span class="text-xs text-slate-400 font-medium">Suara Sah</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Telah disalurkan ke kotak suara</p>
        </div>
    </div>

    <!-- =========================================================================
         4. KEY PERFORMANCE METRIC - PILIHAN UNGGUL / PEMENANG
         ========================================================================= -->
    <div class="p-6 rounded-2xl bg-white border-2 shadow-xs relative overflow-hidden transition-all duration-300 {{ $isTied ? 'border-rose-400' : ($event->status === 'CLOSED' ? 'border-amber-400' : 'border-cyan-600') }}">
        <div class="text-center">
            <!-- Badge Label Hasil Sementara vs Hasil Akhir -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $event->status === 'CLOSED' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-cyan-50 text-cyan-800 border border-cyan-200' }} mb-3">
                <span class="text-xs font-bold uppercase tracking-wide">
                    {{ $event->status === 'CLOSED' ? 'Pemenang / Hasil Akhir' : ' Pilihan Yang Sedang Unggul (Hasil Sementara)' }}
                </span>
            </div>

            @if($isTied)
                <!-- TAMPILAN SERI -->
                <div class="space-y-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-rose-600 mb-2">
                        HASIL SERI
                    </h3>

                    <div class="inline-block px-4 py-2 rounded-xl bg-rose-50 border border-rose-200">
                        <p class="text-sm text-rose-800 font-semibold">
                            {{ $leadingCandidates->count() }} {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }} Memiliki Suara Yang Sama
                        </p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 mt-3">
                        @foreach($leadingCandidates as $tied)
                            <span class="px-3 py-1.5 rounded-lg bg-slate-100 border border-slate-300 text-slate-900 text-sm font-bold">
                                {{ $tied->name }}: {{ $tied->votes_count }} Suara
                            </span>
                        @endforeach
                    </div>

                    <p class="text-xs text-slate-500 mt-3 max-w-md mx-auto">
                        @if($event->status === 'CLOSED')
                            <span class="text-rose-700 font-semibold">Status:</span> Menunggu keputusan dari Petugas Penyelenggara Event
                        @else
                            <span class="text-rose-700 font-semibold">Status:</span> Pemilihan masih berlangsung, hasil dapat berubah sewaktu-waktu
                        @endif
                    </p>
                </div>
            @else
                <!-- TAMPILAN NORMAL (ADA PEMENANG JELAS) -->
                <h3 id="statLeadingCandidate" class="text-2xl sm:text-3xl font-black {{ $event->status === 'CLOSED' ? 'text-amber-800' : 'text-slate-900' }} mb-2">
                    {{ $leadingCandidate ? $leadingCandidate->name : 'Belum Ada Suara' }}
                </h3>

                <p id="statLeadingSubtitle" class="text-sm sm:text-base font-semibold {{ $event->status === 'CLOSED' ? 'text-amber-700' : 'text-slate-700' }}">
                    @if($leadingCandidate && $totalVotes > 0)
                        <span class="inline-flex items-center gap-2">
                            <span class="text-slate-900 font-bold">{{ $leadingCandidate->votes_count }} Suara</span>
                            <span class="px-2.5 py-0.5 rounded-full {{ $event->status === 'CLOSED' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-cyan-100 text-cyan-900 border border-cyan-300' }} text-xs font-black">{{ $leadingCandidate->percentage }}%</span>
                        </span>
                    @else
                        <span class="text-slate-400">Menunggu suara masuk...</span>
                    @endif
                </p>
            @endif
        </div>
    </div>

    <!-- =========================================================================
         5. REKAPITULASI HASIL & VISUALISASI PROGRESS PEROLEHAN SUARA
         ========================================================================= -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Perolehan Suara {{ $event->category === 'KANDIDAT' ? 'Kandidat / Paslon' : 'Opsi Pemilihan' }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Grafik persentase dan akumulasi total suara sah yang masuk secara real-time.
                </p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                    Total: <strong class="text-slate-900">{{ $candidates->count() }} Pilihan</strong>
                </span>
                <button
                    type="button"
                    onclick="fetchLiveMetrics()"
                    id="btnManualRefresh"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-xs"
                    title="Muat ulang data sekarang"
                >
                    <svg id="refreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Muat Ulang</span>
                </button>
            </div>
        </div>

        <!-- Container List Kandidat / Opsi dengan Progress Bar Visual -->
        <div id="candidatesResultsContainer" class="space-y-4">
            @forelse($candidates as $candidate)
                <div
                    id="candidateCard-{{ $candidate->id }}"
                    class="p-4 sm:p-5 rounded-xl bg-slate-50/70 border border-slate-200/90 hover:border-slate-300 transition-all shadow-2xs space-y-3"
                >
                    <div class="flex items-start sm:items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                        <!-- Identitas Kandidat / Opsi -->
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Nomor Urut Badge -->
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                                {{ str_pad($candidate->candidate_number, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <!-- Foto Avatar / Placeholder -->
                            @if($candidate->photo)
                                <div class="w-24 sm:w-28 aspect-4/3 rounded-xl overflow-hidden bg-white border border-slate-200 flex items-center justify-center relative shadow-inner shrink-0">
                                    <img
                                        src="{{ asset('storage/' . $candidate->photo) }}"
                                        alt="{{ $candidate->name }}"
                                        class="w-full h-full object-cover object-center"
                                    >
                                </div>
                            @else
                                <div class="w-24 sm:w-28 aspect-4/3 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center shrink-0">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Nama & Deskripsi -->
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 truncate">
                                    {{ $candidate->name }}
                                </h3>
                                @if($candidate->vision)
                                    <p class="text-[11px] text-slate-500 truncate max-w-md sm:max-w-xl">
                                        {{ $candidate->vision }}
                                    </p>
                                @elseif($candidate->description)
                                    <p class="text-[11px] text-slate-500 truncate max-w-md sm:max-w-xl">
                                        {{ $candidate->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Angka Perolehan Suara & Persentase -->
                        <div class="flex items-center gap-3 shrink-0 sm:text-right w-full sm:w-auto justify-between sm:justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200">
                            <div>
                                <span id="candidatePercentage-{{ $candidate->id }}" class="text-lg sm:text-xl font-black text-cyan-800">
                                    {{ $candidate->percentage }}%
                                </span>
                                <p id="candidateVotes-{{ $candidate->id }}" class="text-[11px] text-slate-500 font-medium">
                                    {{ number_format($candidate->votes_count, 0, ',', '.') }} Suara
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Animasi Perolehan Suara -->
                    <div class="w-full bg-slate-200/80 rounded-full h-3 overflow-hidden border border-slate-200 p-0.5 shadow-inner">
                        <div
                            id="candidateBar-{{ $candidate->id }}"
                            class="h-full rounded-full bg-cyan-600 transition-all duration-700 ease-out"
                            style="width: {{ min(100, $candidate->percentage) }}%;"
                        ></div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-xs">
                    Belum ada kandidat/opsi yang didaftarkan pada event ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
         6. LOG AKTIVITAS SUARA MASUK (REAL-TIME STREAM & TRANSPARANSI LUBER)
         ========================================================================= -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span>Log Aktivitas Partisipasi Pemilih (LUBER & JURDIL)</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan riwayat pemilih yang telah menggunakan hak suara secara transparan tanpa membocorkan pilihan rahasia.
                </p>
            </div>
            <span class="text-[11px] text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 font-semibold">
                Audit Stream Terbuka
            </span>
        </div>

        <!-- Tabel Log Suara Masuk -->
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-900 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Pemilih</th>
                        <th class="py-3 px-4">NIS / NIP</th>
                        <th class="py-3 px-4">Tipe Pemilih</th>
                        <th class="py-3 px-4">Waktu Suara Masuk</th>
                        <th class="py-3 px-4 text-center">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody id="recentVotersTableBody" class="divide-y divide-slate-100 bg-white">
                    @forelse($recentParticipations as $index => $part)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 text-center text-slate-400 font-mono">
                                {{ $recentParticipations->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $part->voter->name ?? 'Pemilih' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-cyan-800 font-semibold">
                                {{ $part->voter->identifier ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                @if(($part->voter->type ?? 'SISWA') === 'GURU')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200">
                                        GURU
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-50 text-cyan-800 border border-cyan-200">
                                        SISWA
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <div class="font-medium text-slate-800">
                                    {{ $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i:s') . ' WIB' : '-' }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $part->voted_at ? $part->voted_at->diffForHumans() : '-' }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
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
     7. JAVASCRIPT REAL-TIME POLLING
     ========================================================================= -->
@push('scripts')
<script>
    // Ambil metrik terbaru via AJAX tanpa refresh halaman
    async function fetchLiveMetrics() {
        const refreshIcon = document.getElementById('refreshIcon');
        if (refreshIcon) refreshIcon.classList.add('animate-spin');

        try {
            const response = await fetch('{{ route('voter.events.monitoring.data', $event->room_code) }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Gagal mengambil data live');

            const data = await response.json();

            // 1. Update Metrik Cepat
            const statTotalVotes = document.getElementById('statTotalVotes');
            if (statTotalVotes) statTotalVotes.textContent = Number(data.total_votes).toLocaleString('id-ID');

            // 2. Update Leading Candidate atau Status Seri
            const leadingCard = document.querySelector('.p-6.rounded-2xl.bg-white.border-2');
            if (leadingCard) {
                const isClosed = data.event_status === 'CLOSED';
                let borderColor;
                if (data.is_tied) {
                    borderColor = 'border-rose-400';
                } else {
                    borderColor = isClosed ? 'border-amber-400' : 'border-cyan-600';
                }
                leadingCard.className = `p-6 rounded-2xl bg-white border-2 shadow-xs relative overflow-hidden transition-all duration-300 ${borderColor}`;

                const centerDiv = leadingCard.querySelector('.text-center');
                if (centerDiv && data.is_tied) {
                    // SERI - Update tampilan
                    centerDiv.innerHTML = `
                        <div class="space-y-3">
                            <h3 class="text-2xl sm:text-3xl font-black text-rose-600 mb-2">⚠️ HASIL SERI</h3>
                            <div class="inline-block px-4 py-2 rounded-xl bg-rose-50 border border-rose-200">
                                <p class="text-sm text-rose-800 font-semibold">${data.leading_candidates.length} Pilihan Memiliki Suara Yang Sama</p>
                            </div>
                            <div class="flex flex-wrap justify-center gap-2 mt-3">
                                ${data.leading_candidates.map(c => `<span class="px-3 py-1.5 rounded-lg bg-slate-100 border border-slate-300 text-slate-900 text-sm font-bold">${c.name}: ${c.votes_count} Suara</span>`).join('')}
                            </div>
                            <p class="text-xs text-slate-500 mt-3 max-w-md mx-auto">
                                <span class="${isClosed ? 'text-amber-700' : 'text-slate-700'} font-semibold">Status:</span> ${isClosed ? 'Menunggu keputusan dari Petugas Penyelenggara Event' : 'Pemilihan masih berlangsung, hasil dapat berubah sewaktu-waktu'}
                            </p>
                        </div>
                    `;
                } else if (centerDiv) {
                    // TIDAK SERI - Tampilan normal
                    let leading = null;
                    if (data.candidates && data.candidates.length > 0 && data.total_votes > 0) {
                        leading = [...data.candidates].sort((a, b) => b.votes_count - a.votes_count)[0];
                    }

                    centerDiv.innerHTML = `
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full ${isClosed ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-cyan-50 text-cyan-800 border border-cyan-200'} mb-3">
                            <span class="text-xs font-bold uppercase tracking-wide">${isClosed ? 'Pemenang / Hasil Akhir' : 'Pilihan Yang Sedang Unggul'}</span>
                        </div>
                        <h3 id="statLeadingCandidate" class="text-2xl sm:text-3xl font-black ${isClosed ? 'text-amber-800' : 'text-slate-900'} mb-2">
                            ${leading ? leading.name : 'Belum Ada Suara'}
                        </h3>
                        <p id="statLeadingSubtitle" class="text-sm sm:text-base font-semibold ${isClosed ? 'text-amber-700' : 'text-slate-700'}">
                            ${leading && data.total_votes > 0
                                ? `<span class="inline-flex items-center gap-2"><span class="text-slate-900 font-bold">${leading.votes_count} Suara</span><span class="px-2.5 py-0.5 rounded-full ${isClosed ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-cyan-100 text-cyan-900 border border-cyan-300'} text-xs font-black">${leading.percentage}%</span></span>`
                                : '<span class="text-slate-400">Menunggu suara masuk...</span>'}
                        </p>
                    `;
                }
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

        } catch (error) {
            console.error('Polling error:', error);
        } finally {
            if (refreshIcon) {
                setTimeout(() => refreshIcon.classList.remove('animate-spin'), 400);
            }
        }
    }

    // Polling otomatis setiap 5 detik
    setInterval(fetchLiveMetrics, 5000);
</script>
@endpush
@endsection
