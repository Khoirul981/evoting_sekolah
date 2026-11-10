@extends('layouts.voter')

@section('title', 'Dashboard Pemilih - Bilik Suara Digital')

@section('content')
<div class="w-full space-y-6">

    <!-- =========================================================================
         1. BANNER IDENTITAS PEMILIH
         ========================================================================= -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs">
        <div class="flex items-center gap-4">
            <!-- Profil Pemilih Terverifikasi -->
            <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white font-extrabold text-xl flex items-center justify-center shadow-xs shrink-0">
                {{ strtoupper(substr($voter->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase border {{ $voter->type === 'GURU' ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-cyan-50 text-cyan-700 border-cyan-200' }}">
                        {{ $voter->type }}
                    </span>
                </div>
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight mt-1">
                    Selamat Datang, {{ $voter->name }}
                </h1>
                <p class="text-xs text-slate-500 font-mono font-medium">
                    NIS/NIP: <span class="text-slate-800 font-bold">{{ $voter->identifier }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         2. PENCARIAN & FILTER EVENT PEMILIHAN
         ========================================================================= -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3.5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Kolom Pencarian Event -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    id="searchEventInput"
                    placeholder="Cari nama event, penyelenggara, atau kode room..."
                    autocomplete="off"
                    class="w-full pl-10 pr-9 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 rounded-xl text-slate-900 placeholder:text-slate-400 text-xs font-medium transition-all"
                >
                <button
                    type="button"
                    id="clearSearchBtn"
                    class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                    title="Hapus pencarian"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Tab / Pills Filter Status Partisipasi -->
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl shrink-0 overflow-x-auto self-start md:self-auto w-full md:w-auto">
                <button
                    type="button"
                    data-filter="all"
                    class="filter-tab px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center cursor-pointer flex-1 md:flex-initial bg-white text-slate-900 shadow-2xs"
                >
                    Semua
                </button>

                <button
                    type="button"
                    data-filter="unvoted"
                    class="filter-tab px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center cursor-pointer flex-1 md:flex-initial text-slate-600 hover:text-slate-900"
                >
                    Belum Memilih
                </button>

                <button
                    type="button"
                    data-filter="voted"
                    class="filter-tab px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center cursor-pointer flex-1 md:flex-initial text-slate-600 hover:text-slate-900"
                >
                    Sudah Memilih
                </button>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         3. DAFTAR SELURUH EVENT PEMILIHAN SEDANG BERLANGSUNG
         ========================================================================= -->
    <div class="space-y-4">
        <!-- Judul Bagian & Keterangan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Daftar Event Pemilihan Sedang Berlangsung
                    </h2>
                    <span id="visibleEventsBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $availableEvents->count() }} Event
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Seluruh event aktif dari semua petugas sekolah. Klik "Detail Event" untuk meninjau profil kandidat di bilik suara.
                </p>
            </div>
        </div>

        @if($availableEvents->isEmpty())
            <!-- Kondisi Kosong (Tidak ada event aktif) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-10 text-center shadow-xs">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">
                    Tidak Ada Event Pemilihan yang Sedang Berlangsung
                </h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    Saat ini belum ada event pemilihan yang dibuka oleh petugas. Silakan tanyakan kepada panitia pemilihan atau tunggu hingga jadwal pemilihan dimulai.
                </p>
            </div>
        @else
            <!-- Grid Daftar Kartu Event -->
            <div id="eventsGrid" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($availableEvents as $event)
                    @php
                        $hasVoted = in_array($event->id, $participatedEventIds);
                    @endphp

                    <div
                        class="event-card border rounded-2xl p-5 sm:p-6 shadow-xs hover:shadow-sm transition-all flex flex-col justify-between {{ $hasVoted ? 'bg-emerald-50/15 border-emerald-200' : 'bg-white border-slate-200/90 hover:border-slate-300' }}"
                        data-has-voted="{{ $hasVoted ? '1' : '0' }}"
                        data-title="{{ strtolower($event->title) }}"
                        data-creator="{{ strtolower($event->creator->name ?? '') }}"
                        data-category="{{ strtolower($event->category) }}"
                        data-room-code="{{ strtolower($event->room_code) }}"
                    >
                        <div>
                            <!-- Header Kartu: Kategori & Status Berlangsung -->
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $event->category === 'KANDIDAT' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}
                                </span>

                                @if($hasVoted)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Sudah Memilih</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Sedang Berlangsung</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Judul Event -->
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-3 tracking-tight leading-snug">
                                {{ $event->title }}
                            </h3>

                            <!-- Deskripsi Singkat -->
                            @if($event->description)
                                <p class="text-xs text-slate-600 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $event->description }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400 italic mt-1.5">
                                    Tidak ada keterangan deskripsi tambahan.
                                </p>
                            @endif

                            <!-- Meta Informasi Event (Penyelenggara & Pilihan) -->
                            <div class="mt-4 pt-3.5 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Penyelenggara</span>
                                    <span class="font-semibold text-slate-800 truncate block mt-0.5" title="{{ $event->creator->name ?? 'Petugas Panitia' }}">
                                        {{ $event->creator->name ?? 'Petugas Panitia' }}
                                    </span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Daftar Pilihan</span>
                                    <span class="font-semibold text-slate-800 block mt-0.5">
                                        {{ $event->candidates_count }} {{ $event->category === 'KANDIDAT' ? 'Kandidat Paslon' : 'Pilihan Opsi' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Jadwal Waktu Selesai -->
                            <div class="mt-2.5 text-[11px] text-slate-500 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    @if($event->end_time)
                                        Batas Waktu: <strong class="text-slate-700">{{ $event->end_time->translatedFormat('d M Y, H:i') }} WIB</strong>
                                    @else
                                        Batas Waktu: <strong class="text-slate-700">Ditutup manual oleh petugas</strong>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Footer Kartu: Verifikasi Kode Room atau Tanda Terima -->
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            @if($hasVoted)
                                <!-- Pemilih Telah Menggunakan Hak Suara: Live Perolehan Suara & Bukti Suara -->
                                <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-3 sm:p-3.5 space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 text-xs">
                                            <span class="font-bold text-emerald-900">Hak Suara Telah Digunakan</span>
                                        </div>
                                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-100 px-2 py-0.5 rounded-md border border-emerald-200">
                                            Tercatat Sah
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 pt-1 border-t border-emerald-200/60 flex-wrap sm:flex-nowrap">
                                        <a
                                            href="{{ route('voter.events.monitoring', $event->room_code) }}"
                                            class="flex-1 py-2 px-3 bg-cyan-700 hover:bg-cyan-800 active:bg-cyan-900 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <span>Live Perolehan Suara</span>
                                        </a>
                                        <a
                                            href="{{ route('voter.events.success', $event->room_code) }}"
                                            class="py-2 px-3 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 text-xs font-semibold rounded-xl border border-slate-200 shadow-2xs transition-colors shrink-0 flex items-center gap-1"
                                        >
                                            <span>Bukti Suara</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Pemilih Belum Memilih: Akses ke Detail Event & Bilik Suara -->
                                <div class="space-y-2">
                                    <a
                                        href="{{ route('voter.events.vote', $event->room_code) }}"
                                        class="w-full py-2.5 px-3.5 bg-slate-900 hover:bg-slate-800 active:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Detail Event</span>
                                    </a>
                                    <p class="text-[10px] text-slate-400 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Lihat kandidat & pantau live suara di dalam bilik suara.</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- State Jika Hasil Pencarian / Filter Kosong -->
            <div id="noResultsState" class="hidden bg-white border border-slate-200/90 rounded-2xl p-8 sm:p-10 text-center shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">
                    Tidak Ditemukan Event yang Sesuai
                </h3>
                <p id="noResultsText" class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    Tidak ada event pemilihan yang sesuai dengan kata kunci atau filter status yang dipilih.
                </p>
                <button
                    type="button"
                    onclick="resetSearchAndFilter()"
                    class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                >
                    <span>Tampilkan Semua Event</span>
                </button>
            </div>
        @endif
    </div>

    <!-- =========================================================================
         4. RIWAYAT PARTISIPASI PEMILIH
         ========================================================================= -->
    @if($participations->isNotEmpty())
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Riwayat Partisipasi Hak Suara Anda</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Daftar pemilihan yang telah Anda ikuti secara sah sesuai asas LUBER JURDIL.
                    </p>
                </div>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $participations->count() }} Partisipasi
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($participations as $participation)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <p class="font-bold text-slate-900 text-sm">
                                {{ $participation->event->title ?? 'Event Pemilihan' }}
                            </p>
                            <p class="text-slate-500 text-[11px] mt-0.5 flex items-center gap-2">
                                <span>Dicoblos pada: <strong>{{ $participation->voted_at ? $participation->voted_at->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</strong></span>
                                <span class="text-slate-300">•</span>
                                <span class="font-mono text-slate-600">Kode Room: {{ $participation->event->room_code ?? '-' }}</span>
                            </p>
                        </div>
                        @if($participation->event)
                            <div class="flex items-center gap-2 shrink-0 self-start sm:self-center flex-wrap">
                                <a
                                    href="{{ route('voter.events.monitoring', $participation->event->room_code) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyan-700 hover:bg-cyan-800 text-white font-semibold transition-colors shadow-2xs text-xs"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span>Lihat Perolehan Suara</span>
                                </a>
                                <a
                                    href="{{ route('voter.events.success', $participation->event->room_code) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-colors shadow-2xs text-xs"
                                >
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Bukti Suara</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    let currentFilter = 'all'; // 'all' | 'unvoted' | 'voted'

    function filterEvents() {
        const searchInput = document.getElementById('searchEventInput');
        const query = (searchInput?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.event-card');
        const clearBtn = document.getElementById('clearSearchBtn');
        const grid = document.getElementById('eventsGrid');
        const noResults = document.getElementById('noResultsState');
        const badge = document.getElementById('visibleEventsBadge');

        if (clearBtn) {
            clearBtn.classList.toggle('hidden', query.length === 0);
        }

        let visibleCount = 0;

        cards.forEach(card => {
            const hasVoted = card.getAttribute('data-has-voted');
            const title = card.getAttribute('data-title') || '';
            const creator = card.getAttribute('data-creator') || '';
            const category = card.getAttribute('data-category') || '';
            const roomCode = card.getAttribute('data-room-code') || '';

            // Cek filter tab status partisipasi
            let matchesFilter = true;
            if (currentFilter === 'unvoted') {
                matchesFilter = (hasVoted === '0');
            } else if (currentFilter === 'voted') {
                matchesFilter = (hasVoted === '1');
            }

            // Cek pencarian kata kunci
            let matchesSearch = true;
            if (query.length > 0) {
                matchesSearch = title.includes(query) ||
                                creator.includes(query) ||
                                category.includes(query) ||
                                roomCode.includes(query);
            }

            if (matchesFilter && matchesSearch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (badge) {
            badge.textContent = visibleCount + ' Event';
        }

        if (noResults && grid) {
            if (visibleCount === 0 && cards.length > 0) {
                noResults.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                grid.classList.remove('hidden');
            }
        }
    }

    function setFilter(filterType) {
        currentFilter = filterType;

        document.querySelectorAll('.filter-tab').forEach(tab => {
            const tabFilter = tab.getAttribute('data-filter');
            if (tabFilter === filterType) {
                tab.classList.remove('text-slate-600', 'hover:text-slate-900');
                tab.classList.add('bg-white', 'text-slate-900', 'shadow-2xs');
            } else {
                tab.classList.remove('bg-white', 'text-slate-900', 'shadow-2xs');
                tab.classList.add('text-slate-600', 'hover:text-slate-900');
            }
        });

        filterEvents();
    }

    function resetSearchAndFilter() {
        const searchInput = document.getElementById('searchEventInput');
        if (searchInput) {
            searchInput.value = '';
        }
        setFilter('all');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchEventInput');
        const clearBtn = document.getElementById('clearSearchBtn');

        searchInput?.addEventListener('input', filterEvents);

        clearBtn?.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            filterEvents();
        });

        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                const filter = tab.getAttribute('data-filter');
                if (filter) {
                    setFilter(filter);
                }
            });
        });

        // Otomatis ubah input kode room menjadi huruf besar (UPPERCASE) jika ada
        document.querySelectorAll('input[name="room_code"]').forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.toUpperCase();
            });
        });
    });
</script>
@endpush
