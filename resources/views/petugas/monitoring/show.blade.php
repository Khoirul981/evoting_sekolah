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

        <!-- Action Button: Kelola Kandidat -->
        <div class="flex items-center gap-2 flex-wrap">
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
        </div>
    </div>

    <!-- =========================================================================
         2. EVENT OVERVIEW HEADER CARD
         ========================================================================= -->
    <div class="p-5 sm:p-6 rounded-2xl bg-[#071226] border border-cyan-900/60 shadow-xl relative overflow-hidden">
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
                                <span>PUBLISHED</span>
                            </span>
                        @elseif($event->status === 'DRAFT')
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/50 shadow-xs">
                                DRAFT
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase bg-rose-500/20 text-rose-300 border border-rose-500/50 shadow-xs">
                                CLOSED
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
                        <span>
                            Mulai: <strong class="text-slate-200">{{ $event->start_time ? $event->start_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Manual / Langsung' }}</strong>
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span>
                            Selesai: <strong class="text-slate-200">{{ $event->end_time ? $event->end_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Ditutup Manual' }}</strong>
                        </span>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- =========================================================================
         3. KEY PERFORMANCE METRIC - PILIHAN UNGGUL / PEMENANG
         ========================================================================= -->
    <div class="grid grid-cols-1 gap-4">
        <!-- Pilihan Unggul / Pemenang (dinamis berdasarkan status event) -->
        <div class="p-6 rounded-2xl bg-[#071226] border-2 shadow-lg relative overflow-hidden transition-all duration-300 {{ $isTied ? 'border-rose-500 hover:border-rose-400' : ($event->status === 'CLOSED' ? 'border-amber-500 hover:border-amber-400' : 'border-cyan-500 hover:border-cyan-400') }}">
            <div class="text-center">
                @if($isTied)
                    <!-- TAMPILAN SERI -->
                    <div class="space-y-3">
                        <h3 class="text-2xl sm:text-3xl font-black text-rose-400 mb-2">
                            HASIL SERI
                        </h3>

                        <div class="inline-block px-4 py-2 rounded-xl bg-rose-500/10 border border-rose-500/30">
                            <p class="text-sm text-rose-300 font-semibold">
                                {{ $leadingCandidates->count() }} {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }} Memiliki Suara Yang Sama
                            </p>
                        </div>

                        <div class="flex flex-wrap justify-center gap-2 mt-3">
                            @foreach($leadingCandidates as $tied)
                                <span class="px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-sm font-bold">
                                    {{ $tied->name }}: {{ $tied->votes_count }} Suara
                                </span>
                            @endforeach
                        </div>

                        <p class="text-xs text-slate-400 mt-3 max-w-md mx-auto">
                            @if($event->status === 'CLOSED')
                                <span class="text-rose-400 font-semibold">Status:</span> Menunggu keputusan dari Petugas Penyelenggara Event
                            @else
                                <span class="text-rose-400 font-semibold">Status:</span> Voting masih berlangsung, hasil dapat berubah sewaktu-waktu
                            @endif
                        </p>
                    </div>
                @else
                    <!-- TAMPILAN NORMAL (ADA PEMENANG JELAS) -->
                    <h3 id="statLeadingCandidate" class="text-2xl sm:text-3xl font-black {{ $event->status === 'CLOSED' ? 'text-amber-300' : 'text-cyan-300' }} mb-2">
                        {{ $leadingCandidate ? $leadingCandidate->name : 'Belum Ada Suara' }}
                    </h3>

                    <p id="statLeadingSubtitle" class="text-sm sm:text-base font-semibold {{ $event->status === 'CLOSED' ? 'text-amber-300/90' : 'text-cyan-300/90' }}">
                        @if($leadingCandidate && $totalVotes > 0)
                            <span class="inline-flex items-center gap-2">
                                <span class="text-white">{{ $leadingCandidate->votes_count }} Suara</span>
                                <span class="px-2.5 py-0.5 rounded-full {{ $event->status === 'CLOSED' ? 'bg-amber-500/30 text-amber-200' : 'bg-cyan-500/30 text-cyan-200' }} text-xs font-black">{{ $leadingCandidate->percentage }}%</span>
                            </span>
                        @else
                            <span class="text-slate-400">Menunggu suara masuk...</span>
                        @endif
                    </p>
                @endif
            </div>
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
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs text-slate-400 bg-[#050d1a] px-3 py-1.5 rounded-lg border border-cyan-950">
                    Total Pilihan: <strong class="text-cyan-300">{{ $candidates->count() }} Pilihan</strong>
                </span>
                <button
                    type="button"
                    onclick="fetchLiveMetrics()"
                    id="btnManualRefresh"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-xs"
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
                    class="p-4 sm:p-5 rounded-xl bg-[#050d1a] border border-cyan-950 hover:border-cyan-800/60 transition-all shadow-sm space-y-3"
                >
                    <div class="flex items-start sm:items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                        <!-- Identitas Kandidat / Opsi -->
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Nomor Urut Badge -->
                            <div class="w-10 h-10 rounded-xl bg-cyan-600/20 text-cyan-300 border border-cyan-400/50 flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                                {{ str_pad($candidate->candidate_number, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <!-- Foto Avatar / Placeholder -->
                            @if($candidate->photo)
                                <div class="w-32 aspect-4/3 rounded-xl overflow-hidden bg-slate-100 border border-slate-200/80 flex items-center justify-center relative shadow-inner shrink-0">
                                    <img
                                        src="{{ asset('storage/' . $candidate->photo) }}"
                                        alt="{{ $candidate->name }}"
                                        class="w-full h-full object-cover object-center"
                                    >
                                </div>
                            @else
                                <div class="w-32 aspect-4/3 rounded-xl bg-slate-100 border border-slate-200/80 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
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
                            class="h-full rounded-full bg-teal-500 transition-all duration-700 ease-out"
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

            // 1. Update Leading Candidate atau Status Seri
            const leadingCard = document.querySelector('.p-6.rounded-2xl.bg-\\[\\#071226\\].border-2');
            if (leadingCard) {
                const isClosed = data.event_status === 'CLOSED';
                let borderColor;
                if (data.is_tied) {
                    borderColor = 'border-rose-500 hover:border-rose-400';
                } else {
                    borderColor = isClosed ? 'border-amber-500 hover:border-amber-400' : 'border-cyan-500 hover:border-cyan-400';
                }
                leadingCard.className = `p-6 rounded-2xl bg-[#071226] border-2 shadow-lg relative overflow-hidden transition-all duration-300 ${borderColor}`;

                const centerDiv = leadingCard.querySelector('.text-center');
                if (centerDiv && data.is_tied) {
                    // SERI - Update tampilan
                    centerDiv.innerHTML = `
                        <div class="space-y-3">
                            <h3 class="text-2xl sm:text-3xl font-black text-rose-400 mb-2">⚠️ HASIL SERI</h3>
                            <div class="inline-block px-4 py-2 rounded-xl bg-rose-500/10 border border-rose-500/30">
                                <p class="text-sm text-rose-300 font-semibold">${data.leading_candidates.length} ${data.event_status === 'KANDIDAT' ? 'Kandidat' : 'Opsi'} Memiliki Suara Yang Sama</p>
                            </div>
                            <div class="flex flex-wrap justify-center gap-2 mt-3">
                                ${data.leading_candidates.map(c => `<span class="px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-sm font-bold">${c.name}: ${c.votes_count} Suara</span>`).join('')}
                            </div>
                            <p class="text-xs text-slate-400 mt-3 max-w-md mx-auto">
                                <span class="${isClosed ? 'text-amber-300' : 'text-cyan-300'} font-semibold">Status:</span> ${isClosed ? 'Menunggu keputusan dari Petugas Penyelenggara Event' : 'Voting masih berlangsung, hasil dapat berubah sewaktu-waktu'}
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
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full ${isClosed ? 'bg-amber-500/20 border border-amber-500/40' : 'bg-cyan-500/20 border border-cyan-500/40'} mb-3">
                            <span class="text-xs font-bold ${isClosed ? 'text-amber-300' : 'text-cyan-300'} uppercase tracking-wide">${isClosed ? '🏆 Pemenang / Hasil Akhir' : 'Pilihan Yang Sedang Unggul'}</span>
                        </div>
                        <h3 id="statLeadingCandidate" class="text-2xl sm:text-3xl font-black ${isClosed ? 'text-amber-300' : 'text-cyan-300'} mb-2">
                            ${leading ? leading.name : 'Belum Ada Suara'}
                        </h3>
                        <p id="statLeadingSubtitle" class="text-sm sm:text-base font-semibold ${isClosed ? 'text-amber-300/90' : 'text-cyan-300/90'}">
                            ${leading && data.total_votes > 0
                                ? `<span class="inline-flex items-center gap-2"><span class="text-white">${leading.votes_count} Suara</span><span class="px-2.5 py-0.5 rounded-full ${isClosed ? 'bg-amber-500/30 text-amber-200' : 'bg-cyan-500/30 text-cyan-200'} text-xs font-black">${leading.percentage}%</span></span>`
                                : '<span class="text-slate-400">Menunggu suara masuk...</span>'}
                        </p>
                    `;
                }
            }

            // 2. Update Progress Bar Tiap Kandidat
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

    // Salin Kode Room
    function copyRoomCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            const btn = document.getElementById('btnCopyRoomCode');
            const originalContent = btn.innerHTML;
            btn.innerHTML = `
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
