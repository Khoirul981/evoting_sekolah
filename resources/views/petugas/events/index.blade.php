@extends('layouts.petugas')

@section('title', 'Kelola Event & Pemilihan')
@section('page_title', 'Kelola Event')

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. WELCOME BANNER & HEADER PANEL PETUGAS (TEMA ELECTRIC CYAN)
         ========================================================================= -->
    <div class="bg-[#071226] border border-cyan-950 rounded-2xl p-6 sm:p-7 shadow-xl shadow-black/30 relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-5">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 mb-3">
                <span>PANEL MANAJEMEN PEMILIHAN</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xs sm:text-sm text-cyan-100/70 mt-1.5 max-w-2xl leading-relaxed">
                Kelola agenda pemilihan sekolah, daftarkan kandidat & opsi voting, publikasikan ruang pemilihan, dan pantau rekapitulasi suara secara real-time.
            </p>
        </div>

        <div class="relative z-10 shrink-0">
            <button
                type="button"
                onclick="openCreateModal()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs sm:text-sm shadow-lg border border-cyan-400/40 transition-all transform active:scale-95 cursor-pointer"
            >
                <span>Buat Event Baru</span>
            </button>
        </div>
    </div>

    <!-- =========================================================================
         2. PENCARIAN & FILTER EVENT
         ========================================================================= -->
    <div class="p-4 bg-[#071226] rounded-2xl border border-cyan-950/70 shadow-sm">
        <form action="{{ route('petugas.events.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Input Cari Kata Kunci -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul event, kode ruang, atau deskripsi..."
                    class="w-full pl-10 pr-4 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400"
                >
            </div>

            <!-- Filter Status -->
            <div>
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                >
                    <option value="">Semua Status</option>
                    <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Draf (DRAFT)</option>
                    <option value="PUBLISHED" {{ request('status') === 'PUBLISHED' ? 'selected' : '' }}>Sedang Aktif (PUBLISHED)</option>
                    <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>Selesai (CLOSED)</option>
                </select>
            </div>

            <!-- Filter Kategori & Tombol Cari/Reset -->
            <div class="flex items-center gap-2">
                <select
                    name="category"
                    onchange="this.form.submit()"
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                >
                    <option value="">Semua Kategori</option>
                    <option value="KANDIDAT" {{ request('category') === 'KANDIDAT' ? 'selected' : '' }}>KANDIDAT</option>
                    <option value="OPSI" {{ request('category') === 'OPSI' ? 'selected' : '' }}>OPSI</option>
                </select>

                <button
                    type="submit"
                    class="px-4 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer shadow-md"
                    title="Cari Data"
                >
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'category']))
                    <a
                        href="{{ route('petugas.events.index') }}"
                        class="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all shrink-0 cursor-pointer"
                        title="Reset Filter"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- =========================================================================
         3. DAFTAR KARTU EVENT
         ========================================================================= -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($events as $event)
                <div class="bg-[#071226] rounded-2xl border border-cyan-950/80 shadow-lg hover:border-cyan-500/40 transition-all flex flex-col justify-between overflow-hidden group">

                    <!-- Bagian Atas: Kategori, Status & Kode Ruang -->
                    <div class="p-5 space-y-4">
                        <!-- Baris Badge Kategori & Status Sesuai Contoh Foto -->
                        <div class="flex items-center justify-between gap-2">
                            <!-- Kategori Badge (KANDIDAT: Royal Indigo/Violet vs OPSI: Golden Amber) -->
                            @if($event->category === 'KANDIDAT')
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-black tracking-wider bg-indigo-500/25 text-indigo-200 border border-indigo-400/80 shadow-xs">
                                    KANDIDAT
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-black tracking-wider bg-amber-500/20 text-amber-300 border border-amber-400/80 shadow-xs">
                                    OPSI
                                </span>
                            @endif

                            <!-- Status Badge -->
                            @if($event->status === 'PUBLISHED')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/40">
                                    <span>Aktif / Dibuka</span>
                                </span>
                            @elseif($event->status === 'DRAFT')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/40">
                                    <span>Draf / Menunggu</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-700/60 text-slate-300 border border-slate-600/50">
                                    <span>Selesai / Ditutup</span>
                                </span>
                            @endif
                        </div>

                        <!-- Kotak Kode Ruang (1-Click Copy) -->
                        <div class="p-3 bg-[#042438]/60 rounded-xl border border-cyan-900/60 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-cyan-300/70 tracking-wider">Kode Ruang Voting</span>
                                <div class="text-lg font-black text-cyan-400 font-mono tracking-widest leading-none mt-1">
                                    {{ $event->room_code }}
                                </div>
                            </div>
                            <button
                                type="button"
                                onclick="copyRoomCode('{{ $event->room_code }}', this)"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-cyan-600/20 hover:bg-cyan-600 text-cyan-300 hover:text-white text-[11px] font-semibold border border-cyan-400/40 transition-all cursor-pointer"
                                title="Salin Kode Ruang"
                            >
                                <span>Salin</span>
                            </button>
                        </div>

                        <!-- Judul & Deskripsi Event -->
                        <div>
                            <h2 class="text-base font-bold text-white leading-snug line-clamp-2">
                                {{ $event->title }}
                            </h2>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                                {{ $event->description ?: 'Tidak ada deskripsi tambahan untuk event ini.' }}
                            </p>
                        </div>

                        <!-- Statistik Kandidat, Suara & Jadwal -->
                        <div class="pt-3 border-t border-cyan-950/70 grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2 rounded-lg bg-[#050d1a] border border-cyan-950/60">
                                <span class="text-[10px] text-slate-500 block">Kandidat / Opsi</span>
                                <span class="font-bold text-cyan-300">
                                    {{ $event->candidates_count }} {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Pilihan' }}
                                </span>
                            </div>
                            <div class="p-2 rounded-lg bg-[#050d1a] border border-cyan-950/60">
                                <span class="text-[10px] text-slate-500 block">Suara Masuk</span>
                                <span class="font-bold text-slate-200">{{ $event->votes_count }} Suara</span>
                            </div>
                        </div>

                        <!-- Jadwal Waktu Mulai & Selesai -->
                        <div class="space-y-1 text-[11px] text-slate-400 bg-[#050d1a]/80 p-2.5 rounded-lg border border-cyan-950/50">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Mulai:</span>
                                <span class="font-medium text-slate-300">
                                    {{ $event->start_time ? $event->start_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Manual / Langsung' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Selesai:</span>
                                <span class="font-medium text-slate-300">
                                    {{ $event->end_time ? $event->end_time->translatedFormat('d M Y, H:i') . ' WIB' : 'Ditutup Manual' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Bawah: Tombol Aksi, Status Action Button, Edit & Hapus -->
                    <div class="p-4 bg-[#050d1a] border-t border-cyan-950/80 space-y-2.5">
                        <!-- Baris 1: Tombol Kelola Kandidat & Live Monitor -->
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Tombol Kelola Kandidat / Opsi -->
                            <a
                                href="{{ route('petugas.candidates.index', $event) }}"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl {{ $event->candidates_count < 2 ? 'bg-amber-500/15 hover:bg-amber-600 text-amber-300 hover:text-white border-amber-500/30' : 'bg-cyan-600/15 hover:bg-cyan-600 text-cyan-300 hover:text-white border-cyan-500/30' }} text-xs font-bold border transition-all cursor-pointer"
                                title="{{ $event->candidates_count < 2 ? 'Pilihan kurang dari 2! Klik untuk melengkapi data kandidat/opsi.' : 'Kelola data kandidat/opsi' }}"
                            >
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="truncate">{{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }} ({{ $event->candidates_count }})</span>
                                @if($event->candidates_count < 2)
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0" title="Minimal 2 pilihan belum terpenuhi"></span>
                                @endif
                            </a>

                            <!-- Tombol Live Monitor & Rekapitulasi Suara -->
                            <a
                                href="{{ route('petugas.events.monitor', $event) }}"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-cyan-600/20 hover:bg-cyan-600 text-cyan-300 hover:text-white text-xs font-bold border border-cyan-500/40 transition-all cursor-pointer shadow-xs"
                                title="Pantau perolehan suara secara real-time & unduh rekapitulasi"
                            >
                                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Live Monitor</span>
                            </a>
                        </div>

                        <!-- Baris 2: Tombol Status Aksi (Jika waktu mulai & selesai tidak disetel), Edit & Hapus -->
                        <div class="flex items-center justify-between gap-2 pt-2 border-t border-cyan-950/70">
                            @if($event->start_time && $event->end_time)
                                <!-- Jika Waktu Mulai & Selesai Diatur: Status Mengikuti Jadwal Otomatis Sistem -->
                                <div class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-[#042438]/80 text-cyan-300 text-xs font-semibold border border-cyan-800/50 shadow-xs" title="Event ini berjalan otomatis sesuai waktu mulai dan selesai yang telah diatur">
                                    <span class="truncate">Otomatis Sesuai Jadwal</span>
                                </div>
                            @else
                                <!-- Jika Waktu Mulai & Selesai TIDAK Diatur: Berlaku Tombol Manual PUBLISH atau TUTUP EVENT -->
                                @if($event->status === 'DRAFT')
                                    <!-- Ketika Masih DRAFT: Tampilkan Tombol PUBLISH -->
                                    <form action="{{ route('petugas.events.status', $event) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="PUBLISHED">
                                        <button
                                            type="submit"
                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-md border border-cyan-400/40 transition-all cursor-pointer"
                                            title="Publikasikan Event & Buka Ruang Voting Sekarang"
                                        >
                                            <span>PUBLISH</span>
                                        </button>
                                    </form>
                                @elseif($event->status === 'PUBLISHED')
                                    <!-- Ketika Sedang PUBLISHED: Tampilkan Tombol TUTUP EVENT -->
                                    <form action="{{ route('petugas.events.status', $event) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menutup event pemilihan ini? Pemilih tidak akan dapat lagi memberikan suara setelah event ditutup.')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="CLOSED">
                                        <button
                                            type="submit"
                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md border border-rose-400/40 transition-all cursor-pointer"
                                            title="Tutup Ruang Voting & Selesaikan Event"
                                        >
                                            <span>TUTUP EVENT</span>
                                        </button>
                                    </form>
                                @else
                                    <!-- Ketika Sudah CLOSED: Status Terkunci (Selesai) -->
                                    <div class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800/80 text-slate-400 text-xs font-bold border border-slate-700/60">
                                        <span>EVENT DITUTUP</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Tombol Edit Modal -->
                            <button
                                type="button"
                                onclick="openEditModal({{ json_encode($event) }})"
                                class="p-2 rounded-xl bg-[#0a1832] hover:bg-cyan-950/70 text-slate-300 hover:text-white border border-cyan-900/40 transition-all cursor-pointer"
                                title="Edit Data Event"
                            >
                                <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <!-- Tombol Hapus Modal -->
                            <button
                                type="button"
                                onclick="openDeleteModal('{{ $event->id }}', '{{ addslashes($event->title) }}', '{{ $event->room_code }}')"
                                class="p-2 rounded-xl bg-rose-950/60 hover:bg-rose-900 text-rose-300 hover:text-white border border-rose-900/60 transition-all cursor-pointer"
                                title="Hapus Event"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Paginasi Event -->
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        <!-- =========================================================================
             STATE KOSONG (EMPTY STATE)
             ========================================================================= -->
        <div class="p-12 text-center bg-[#071226] rounded-2xl border border-cyan-950/80 shadow-md">
            <div class="w-16 h-16 rounded-2xl bg-cyan-600/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-white">Belum Ada Event Pemilihan</h3>
            <p class="text-xs sm:text-sm text-slate-400 max-w-md mx-auto mt-1">
                @if(request()->hasAny(['search', 'status', 'category']))
                    Tidak ada event yang cocok dengan kriteria filter pencarian Anda. Silakan reset filter.
                @else
                    Mulai buat agenda voting baru seperti Pemilihan Ketua OSIS, Ketua MPK, atau Polling Kegiatan Sekolah.
                @endif
            </p>
            <div class="mt-5">
                @if(request()->hasAny(['search', 'status', 'category']))
                    <a href="{{ route('petugas.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-all">
                        Reset Filter Pencarian
                    </a>
                @else
                    <button
                        type="button"
                        onclick="openCreateModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-lg border border-cyan-400/40 transition-all cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Event Pertama Sekarang</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

</div>

<!-- =========================================================================
     MODAL 1: BUAT EVENT BARU
     ========================================================================= -->
<div id="createEventModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 overflow-y-auto">
    <div class="bg-[#071226] border border-cyan-950/90 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden my-8">
        <!-- Modal Header -->
        <div class="p-5 border-b border-cyan-950/70 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-base font-bold text-white">Buat Event Pemilihan Baru</h3>
                    <p class="text-xs text-cyan-200/60">Atur rincian ruang voting dan jadwal pelaksanaan.</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Form -->
        <form action="{{ route('petugas.events.store') }}" method="POST" class="p-5 space-y-4">
            @csrf

            <!-- Judul Event -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    Judul Event Pemilihan <span class="text-rose-400">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    required
                    placeholder="Contoh: Pemilihan Ketua & Wakil Ketua OSIS 2026/2027"
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-400"
                >
            </div>

            <!-- Kategori & Kode Ruang -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Kategori Pemilihan <span class="text-rose-400">*</span>
                    </label>
                    <select
                        name="category"
                        required
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                    >
                        <option value="KANDIDAT">KANDIDAT</option>
                        <option value="OPSI">OPSI</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Kode Ruang Voting
                    </label>
                    <input
                        type="text"
                        name="room_code"
                        maxlength="10"
                        placeholder="Otomatis jika kosong (cth: OSIS26)"
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 uppercase font-mono placeholder-slate-500 focus:outline-none focus:border-cyan-400"
                    >
                    <p class="text-[10px] text-slate-500 mt-1">4-10 karakter huruf/angka unik.</p>
                </div>
            </div>

            <!-- Status Awal -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    Status Awal Event <span class="text-rose-400">*</span>
                </label>
                <select
                    name="status"
                    required
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                >
                    <option value="DRAFT">Draf (Disimpan dulu, belum dapat dipilih)</option>
                    <option value="PUBLISHED">Langsung Aktifkan (Pemilih bisa masuk & voting)</option>
                    <option value="CLOSED">Selesai / Ditutup</option>
                </select>
            </div>

            <!-- Waktu Mulai & Waktu Selesai (Opsional) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Waktu Mulai (Opsional)
                    </label>
                    <input
                        type="datetime-local"
                        name="start_time"
                        style="color-scheme: dark;"
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                    >
                    <p class="text-[10px] text-slate-500 mt-1">Kosongkan jika ingin dibuka secara manual.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Waktu Selesai (Opsional)
                    </label>
                    <input
                        type="datetime-local"
                        name="end_time"
                        style="color-scheme: dark;"
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                    >
                    <p class="text-[10px] text-slate-500 mt-1">Kosongkan jika ingin ditutup secara manual.</p>
                </div>
            </div>

            <!-- Deskripsi Event -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    Deskripsi / Petunjuk Pemilihan
                </label>
                <textarea
                    name="description"
                    rows="3"
                    placeholder="Tuliskan petunjuk atau keterangan tambahan bagi para pemilih..."
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-400"
                ></textarea>
            </div>

            <!-- Tombol Aksi Modal -->
            <div class="pt-3 border-t border-cyan-950/70 flex items-center justify-end gap-2.5">
                <button
                    type="button"
                    onclick="closeCreateModal()"
                    class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-lg shadow-cyan-950/50 border border-cyan-400/40 transition-all cursor-pointer"
                >
                    Simpan & Buat Event
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 2: EDIT EVENT
     ========================================================================= -->
<div id="editEventModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 overflow-y-auto">
    <div class="bg-[#071226] border border-cyan-950/90 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden my-8">
        <!-- Modal Header -->
        <div class="p-5 border-b border-cyan-950/70 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-base font-bold text-white">Edit Rincian Event</h3>
                    <p class="text-xs text-cyan-200/60">Perbarui informasi, jadwal, dan kode ruang voting.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Form -->
        <form id="editEventForm" action="" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <!-- Judul Event -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    Judul Event Pemilihan <span class="text-rose-400">*</span>
                </label>
                <input
                    type="text"
                    id="edit_title"
                    name="title"
                    required
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-400"
                >
            </div>

            <!-- Kategori & Kode Ruang -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Kategori Pemilihan <span class="text-rose-400">*</span>
                    </label>
                    <select
                        id="edit_category"
                        name="category"
                        required
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                    >
                        <option value="KANDIDAT">KANDIDAT</option>
                        <option value="OPSI">OPSI</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Kode Ruang Voting <span class="text-rose-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="edit_room_code"
                        name="room_code"
                        required
                        maxlength="10"
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 uppercase font-mono placeholder-slate-500 focus:outline-none focus:border-cyan-400"
                    >
                </div>
            </div>

            <!-- Status Event -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    Status Event <span class="text-rose-400">*</span>
                </label>
                <select
                    id="edit_status"
                    name="status"
                    required
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                >
                    <option value="DRAFT">Draf (DRAFT)</option>
                    <option value="PUBLISHED">Sedang Aktif (PUBLISHED)</option>
                    <option value="CLOSED">Selesai (CLOSED)</option>
                </select>
            </div>

            <!-- Waktu Mulai & Waktu Selesai (Opsional) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Waktu Mulai (Opsional)
                    </label>
                    <input
                        type="datetime-local"
                        id="edit_start_time"
                        name="start_time"
                        style="color-scheme: dark;"
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        Waktu Selesai (Opsional)
                    </label>
                    <input
                        type="datetime-local"
                        id="edit_end_time"
                        name="end_time"
                        style="color-scheme: dark;"
                        class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-cyan-400"
                    >
                </div>
            </div>

            <!-- Deskripsi Event -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    Deskripsi / Petunjuk Pemilihan
                </label>
                <textarea
                    id="edit_description"
                    name="description"
                    rows="3"
                    class="w-full px-3.5 py-2.5 bg-[#050d1a] border border-cyan-950 rounded-xl text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-cyan-400"
                ></textarea>
            </div>

            <!-- Tombol Aksi Modal -->
            <div class="pt-3 border-t border-cyan-950/70 flex items-center justify-end gap-2.5">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-lg shadow-cyan-950/50 border border-cyan-400/40 transition-all cursor-pointer"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 3: KONFIRMASI HAPUS EVENT
     ========================================================================= -->
<div id="deleteEventModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 overflow-y-auto">
    <div class="bg-[#071226] border border-rose-900/60 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden my-8">
        <div class="p-5 border-b border-rose-900/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-600/20 text-rose-400 border border-rose-500/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Hapus Event Pemilihan?</h3>
                <p class="text-xs text-rose-300/80">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            </div>
        </div>

        <div class="p-5 space-y-3">
            <p class="text-xs text-slate-300 leading-relaxed">
                Apakah Anda yakin ingin menghapus event <strong id="delete_event_title" class="text-white"></strong> (Kode: <span id="delete_event_code" class="font-mono text-cyan-400"></span>)?
            </p>
            <div class="p-3 bg-rose-950/40 border border-rose-900/40 rounded-xl text-[11px] text-rose-300/90 leading-relaxed">
                ⚠️ Seluruh data terkait seperti daftar kandidat/opsi dan riwayat perolehan suara pada event ini juga akan ikut terhapus.
            </div>
        </div>

        <form id="deleteEventForm" action="" method="POST" class="p-4 bg-[#050d1a] border-t border-cyan-950/70 flex items-center justify-end gap-2.5">
            @csrf
            @method('DELETE')
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all cursor-pointer"
            >
                Batal
            </button>
            <button
                type="submit"
                class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-lg transition-all cursor-pointer"
            >
                Ya, Hapus Permanen
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Salin Kode Ruang dengan Feedback Visual
    function copyRoomCode(code, buttonElement) {
        if (!navigator.clipboard) {
            const textarea = document.createElement('textarea');
            textarea.value = code;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        } else {
            navigator.clipboard.writeText(code);
        }

        const originalHTML = buttonElement.innerHTML;
        buttonElement.innerHTML = `
            <span class="text-emerald-300 font-bold">Tersalin!</span>
        `;
        buttonElement.classList.add('bg-emerald-950/80', 'border-emerald-700');

        setTimeout(() => {
            buttonElement.innerHTML = originalHTML;
            buttonElement.classList.remove('bg-emerald-950/80', 'border-emerald-700');
        }, 2000);
    }

    // 2. Modal Tambah Event
    function openCreateModal() {
        const modal = document.getElementById('createEventModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeCreateModal() {
        const modal = document.getElementById('createEventModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // 3. Modal Edit Event
    function openEditModal(event) {
        const modal = document.getElementById('editEventModal');
        const form = document.getElementById('editEventForm');
        form.action = `/petugas/events/${event.id}`;

        document.getElementById('edit_title').value = event.title || '';
        document.getElementById('edit_category').value = event.category || 'KANDIDAT';
        document.getElementById('edit_room_code').value = event.room_code || '';
        document.getElementById('edit_status').value = event.status || 'DRAFT';
        document.getElementById('edit_description').value = event.description || '';

        // Format datetime-local (YYYY-MM-DDTHH:mm)
        if (event.start_time) {
            const startDate = new Date(event.start_time);
            document.getElementById('edit_start_time').value = formatDateTimeLocal(startDate);
        } else {
            document.getElementById('edit_start_time').value = '';
        }

        if (event.end_time) {
            const endDate = new Date(event.end_time);
            document.getElementById('edit_end_time').value = formatDateTimeLocal(endDate);
        } else {
            document.getElementById('edit_end_time').value = '';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditModal() {
        const modal = document.getElementById('editEventModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Helper format Date ke string input datetime-local
    function formatDateTimeLocal(date) {
        const pad = (n) => String(n).padStart(2, '0');
        const year = date.getFullYear();
        const month = pad(date.getMonth() + 1);
        const day = pad(date.getDate());
        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // 4. Modal Hapus Event
    function openDeleteModal(id, title, code) {
        const modal = document.getElementById('deleteEventModal');
        const form = document.getElementById('deleteEventForm');
        form.action = `/petugas/events/${id}`;

        document.getElementById('delete_event_title').textContent = title;
        document.getElementById('delete_event_code').textContent = code;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteEventModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Close Modals on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endpush
