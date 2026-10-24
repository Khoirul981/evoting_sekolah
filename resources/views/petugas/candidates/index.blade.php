@extends('layouts.petugas')

@section('title', 'Kelola Kandidat & Opsi - ' . $event->title)
@section('page_title', 'Kelola ' . ($event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi'))

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. BREADCRUMB & NAVIGASI KEMBALI
         ========================================================================= -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('petugas.events.index') }}" class="hover:text-cyan-400 transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Daftar Event</span>
            </a>
            <span class="text-slate-600">/</span>
            <span class="text-white font-medium truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
            <span class="text-slate-600">/</span>
            <span class="text-cyan-400 font-bold">{{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}</span>
        </div>

        <a
            href="{{ route('petugas.events.monitor', $event) }}"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#0a1832] hover:bg-cyan-950/70 text-slate-300 hover:text-white text-xs font-semibold border border-cyan-900/50 transition-all cursor-pointer shadow-xs"
        >
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Live Monitoring Suara</span>
        </a>
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

                    <!-- Room Code Badge dengan Tombol Copy -->
                    <button
                        type="button"
                        id="btnCopyRoomCode"
                        onclick="copyRoomCode('{{ $event->room_code }}')"
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

            <!-- Tombol Aksi Tambah Kandidat / Opsi -->
            <div class="flex flex-col sm:flex-row lg:flex-col gap-2.5 shrink-0">
                <button
                    type="button"
                    onclick="openCreateCandidateModal()"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold shadow-lg border border-cyan-400/40 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer"
                >
                    <span>Tambah {{ $event->category === 'KANDIDAT' ? 'Kandidat Baru' : 'Opsi Baru' }}</span>
                </button>

                @if($event->status === 'DRAFT' && $candidatesCount >= 2 && !$event->start_time)
                    <form action="{{ route('petugas.events.status', $event) }}" method="POST" onsubmit="return confirm('Publikasikan event sekarang? Ruang pemilihan akan langsung aktif.')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="PUBLISHED">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md border border-emerald-400/40 transition-all cursor-pointer"
                        >
                            <span>Publikasikan Event</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- =========================================================================
             BANNER PERINGATAN JIKA PILIHAN KURANG DARI 2
             ========================================================================= -->
        @if($candidatesCount < 2)
            <div class="mt-5 pt-4 border-t border-cyan-950/80">
                <div class="p-3.5 rounded-xl bg-amber-500/15 border border-amber-500/40 text-center">
                    <div>
                        <p class="text-xs font-bold text-amber-200">
                            Syarat Minimal Belum Terpenuhi (Saat ini: {{ $candidatesCount }} / 2 Pilihan)
                        </p>
                        <p class="text-[11px] text-amber-300/80 mt-1">
                            Setiap event pemilihan wajib memiliki <strong>minimal 2 kandidat/opsi</strong> agar pemilih memiliki opsi pilihan dan event dapat dipublikasikan.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- =========================================================================
         4. GRID DAFTAR KANDIDAT / OPSI PEMILIHAN
         ========================================================================= -->
    @if($candidates->isEmpty())
        <!-- State Kosong (Belum Ada Kandidat) -->
        <div class="p-12 text-center rounded-2xl bg-[#071226] border border-cyan-950/80 shadow-xl space-y-4">
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="text-base font-bold text-white">
                    Belum Ada {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }} Terdaftar
                </h3>
                <p class="text-xs text-slate-400">
                    Event ini belum memiliki pilihan yang terdaftar. Klik tombol "Tambah Kandidat Baru" untuk menambahkan {{ $event->category === 'KANDIDAT' ? 'kandidat' : 'opsi pilihan' }} pertama.
                </p>
            </div>
        </div>
    @else
        <!-- Grid Card Kandidat / Opsi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($candidates as $candidate)
                <div class="rounded-2xl bg-[#071226] border border-cyan-950/80 hover:border-cyan-500/50 shadow-xl overflow-hidden flex flex-col justify-between transition-all group">

                    <div>
                        <!-- Header Card: Nomor Urut & Kategori Badge -->
                        <div class="p-4 bg-[#050d1a] border-b border-cyan-950/70 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-[#042438] border border-cyan-400/60 text-cyan-300 flex items-center justify-center font-black text-base">
                                    {{ str_pad($candidate->candidate_number, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div>
                                    <span class="text-[10px] text-cyan-400/80 uppercase font-bold tracking-wider block">
                                        Nomor Urut
                                    </span>
                                    <span class="text-xs font-extrabold text-white">
                                        Pilihan {{ $candidate->candidate_number }}
                                    </span>
                                </div>
                            </div>

                            @if($event->category === 'KANDIDAT')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">
                                    KANDIDAT
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                    OPSI
                                </span>
                            @endif
                        </div>

                        <!-- Foto Kandidat / Ilustrasi Opsi -->
                        <div class="p-5 flex flex-col items-center text-center">
                            <div class="w-full aspect-4/3 rounded-xl overflow-hidden bg-slate-100 border border-slate-200/80 shadow-inner relative flex items-center justify-center shrink-0">
                                @if($candidate->photo_url)
                                    <img
                                        src="{{ $candidate->photo_url }}"
                                        alt="{{ $candidate->name }}"
                                        class="w-full h-full object-cover object-center"
                                    >
                                @else
                                    <div class="flex flex-col items-center justify-center p-3 text-slate-500">
                                        <svg class="w-12 h-12 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($event->category === 'KANDIDAT')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            @endif
                                        </svg>
                                        <span class="text-[10px] font-semibold text-slate-500">Tanpa Foto</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Nama Kandidat / Label Opsi -->
                            <h3 class="mt-4 text-base font-extrabold text-white tracking-tight leading-snug line-clamp-2">
                                {{ $candidate->name }}
                            </h3>

                            <!-- Konten Ringkas Berdasarkan Kategori -->
                            @if($event->category === 'KANDIDAT')
                                @if($candidate->description)
                                    <p class="mt-1.5 text-xs text-slate-400 line-clamp-2">
                                        {{ $candidate->description }}
                                    </p>
                                @endif

                                <!-- Tombol Preview Visi & Misi -->
                                <button
                                    type="button"
                                    onclick="openVisionMissionModal({{ json_encode($candidate) }})"
                                    class="mt-3.5 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#042438] hover:bg-cyan-900/60 text-cyan-300 hover:text-white text-xs font-semibold border border-cyan-800/60 transition-colors cursor-pointer"
                                >
                                    <span>Lihat Visi & Misi</span>
                                </button>
                            @else
                                <!-- OPSI: Deskripsi Pilihan -->
                                @if($candidate->description)
                                    <p class="mt-2 text-xs text-slate-300 bg-[#050d1a] p-3 rounded-xl border border-cyan-950/70 text-left w-full line-clamp-4">
                                        {{ $candidate->description }}
                                    </p>
                                @else
                                    <p class="mt-2 text-xs italic text-slate-500">
                                        Tidak ada keterangan tambahan.
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Footer Card: Tombol Aksi (Edit & Hapus) -->
                    <div class="p-3.5 bg-[#050d1a] border-t border-cyan-950/70 flex items-center justify-between gap-2">
                        <!-- Tombol Edit -->
                        <button
                            type="button"
                            onclick="openEditCandidateModal({{ json_encode($candidate) }})"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-[#0a1832] hover:bg-cyan-950 text-cyan-300 hover:text-white text-xs font-bold border border-cyan-900/50 transition-all cursor-pointer"
                            title="Edit Data Kandidat/Opsi"
                        >
                            <span>Edit</span>
                        </button>

                        <!-- Tombol Hapus -->
                        <button
                            type="button"
                            onclick="openDeleteCandidateModal('{{ $candidate->id }}', '{{ addslashes($candidate->name) }}', '{{ $candidate->candidate_number }}')"
                            class="p-2 rounded-xl bg-rose-950/60 hover:bg-rose-900 text-rose-300 hover:text-white border border-rose-900/60 transition-all cursor-pointer"
                            title="Hapus Kandidat/Opsi"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>

<!-- =========================================================================
     MODAL 1: TAMBAH KANDIDAT / OPSI BARU
     ========================================================================= -->
<div
    id="createCandidateModal"
    class="fixed inset-0 z-50 bg-black/80 items-center justify-center p-4 hidden overflow-y-auto"
>
    <div class="relative w-full max-w-2xl bg-[#071226] border border-cyan-800/80 rounded-2xl shadow-2xl overflow-hidden my-8">
        <!-- Header Modal -->
        <div class="p-5 bg-[#071226] border-b border-cyan-900/70 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-base font-extrabold text-white">
                        Tambah {{ $event->category === 'KANDIDAT' ? 'Kandidat Baru' : 'Opsi Pilihan Baru' }}
                    </h3>
                    <p class="text-xs text-cyan-300/70">
                        Event: <strong class="text-white">{{ $event->title }}</strong>
                    </p>
                </div>
            </div>
            <button
                type="button"
                onclick="closeCreateCandidateModal()"
                class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800/60 transition-colors cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Tambah -->
        <form action="{{ route('petugas.candidates.store', $event) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Nomor Urut -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Nomor Urut <span class="text-rose-400">*</span>
                    </label>
                    <input
                        type="number"
                        name="candidate_number"
                        value="{{ old('candidate_number', $nextNumber) }}"
                        min="1"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white font-bold focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm"
                        placeholder="1"
                    >
                </div>

                <!-- Nama Kandidat / Label Opsi -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        {{ $event->category === 'KANDIDAT' ? 'Nama Kandidat' : 'Nama / Label Opsi' }} <span class="text-rose-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm"
                        placeholder="{{ $event->category === 'KANDIDAT' ? 'Contoh: Ahmad Rizki & Maya Safitri' : 'Contoh: Ekstrakurikuler Robotik' }}"
                    >
                </div>
            </div>

            <!-- Upload Foto / Gambar dengan Live Preview -->
            <div>
                <label class="block text-xs font-bold text-slate-200 mb-1.5">
                    {{ $event->category === 'KANDIDAT' ? 'Foto Kandidat' : 'Gambar / Ikon Ilustrasi' }} <span class="text-slate-500 font-normal">(Opsional, Max 2MB: JPG, PNG, WEBP)</span>
                </label>
                <div class="flex items-center gap-4 p-3.5 bg-[#050d1a] rounded-xl border border-cyan-950">
                    <div id="createImagePreviewContainer" class="w-20 h-20 rounded-xl bg-[#071226] border border-cyan-900/50 flex items-center justify-center overflow-hidden shrink-0">
                        <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <input
                            type="file"
                            id="createPhotoInput"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp,image/jpg"
                            onchange="previewImage(event, 'createImagePreviewContainer')"
                            class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-600/20 file:text-cyan-300 hover:file:bg-cyan-600/40 file:cursor-pointer cursor-pointer"
                        >
                        <p class="text-[11px] text-slate-500 mt-1">Disarankan menggunakan rasio persegi (1:1) atau potret (4:5).</p>
                    </div>
                </div>
            </div>

            @if($event->category === 'KANDIDAT')
                <!-- Visi Kandidat -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Visi Kandidat <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="vision"
                        rows="3"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm leading-relaxed"
                        placeholder="Tuliskan visi utama kandidat..."
                    >{{ old('vision') }}</textarea>
                </div>

                <!-- Misi Kandidat -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Misi Kandidat <span class="text-slate-500 font-normal">(Opsional, pisahkan dengan baris baru / poin)</span>
                    </label>
                    <textarea
                        name="mission"
                        rows="4"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm leading-relaxed"
                        placeholder="1. Meningkatkan kualitas kedisiplinan siswa&#10;2. Mengembangkan kegiatan ekstrakurikuler unggulan&#10;3. Menyelenggarakan kegiatan sosial dan peduli lingkungan"
                    >{{ old('mission') }}</textarea>
                </div>

                <!-- Deskripsi / Profil Singkat -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Biodata / Profil Singkat <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="description"
                        rows="2"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm"
                        placeholder="Contoh: Ketua: Kelas XI RPL 1, Wakil: Kelas X PPLG 2"
                    >{{ old('description') }}</textarea>
                </div>
            @else
                <!-- Deskripsi Lengkap untuk OPSI -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Deskripsi & Keterangan Opsi <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm leading-relaxed"
                        placeholder="Tuliskan rincian penjelasan mengenai opsi pilihan ini..."
                    >{{ old('description') }}</textarea>
                </div>
            @endif

            <!-- Tombol Aksi Modal -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-cyan-950">
                <button
                    type="button"
                    onclick="closeCreateCandidateModal()"
                    class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-md shadow-cyan-950/60 border border-cyan-400/40 transition-all cursor-pointer"
                >
                    Simpan {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 2: EDIT KANDIDAT / OPSI
     ========================================================================= -->
<div
    id="editCandidateModal"
    class="fixed inset-0 z-50 bg-black/80 items-center justify-center p-4 hidden overflow-y-auto"
>
    <div class="relative w-full max-w-2xl bg-[#071226] border border-cyan-800/80 rounded-2xl shadow-2xl overflow-hidden my-8">
        <!-- Header Modal -->
        <div class="p-5 bg-[#071226] border-b border-cyan-900/70 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-base font-extrabold text-white">
                        Edit {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi Pilihan' }}
                    </h3>
                    <p class="text-xs text-cyan-300/70" id="editCandidateSubtitle">
                        Perbarui informasi data pilihan
                    </p>
                </div>
            </div>
            <button
                type="button"
                onclick="closeEditCandidateModal()"
                class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800/60 transition-colors cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Edit -->
        <form id="editCandidateForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Nomor Urut -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Nomor Urut <span class="text-rose-400">*</span>
                    </label>
                    <input
                        type="number"
                        id="editCandidateNumber"
                        name="candidate_number"
                        min="1"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white font-bold focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm"
                    >
                </div>

                <!-- Nama Kandidat / Label Opsi -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        {{ $event->category === 'KANDIDAT' ? 'Nama Kandidat' : 'Nama / Label Opsi' }} <span class="text-rose-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="editCandidateName"
                        name="name"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm"
                    >
                </div>
            </div>

            <!-- Upload Ganti Foto -->
            <div>
                <label class="block text-xs font-bold text-slate-200 mb-1.5">
                    {{ $event->category === 'KANDIDAT' ? 'Ganti Foto Kandidat' : 'Ganti Gambar / Ikon' }} <span class="text-slate-500 font-normal">(Biarkan kosong jika tidak ingin mengubah foto)</span>
                </label>
                <div class="flex items-center gap-4 p-3.5 bg-[#050d1a] rounded-xl border border-cyan-950">
                    <div id="editImagePreviewContainer" class="w-20 h-20 rounded-xl bg-[#071226] border border-cyan-900/50 flex items-center justify-center overflow-hidden shrink-0">
                        <img id="editImageCurrent" src="" alt="" class="w-full h-full object-cover hidden">
                        <svg id="editImagePlaceholder" class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <input
                            type="file"
                            id="editPhotoInput"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp,image/jpg"
                            onchange="previewImage(event, 'editImagePreviewContainer')"
                            class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-600/20 file:text-cyan-300 hover:file:bg-cyan-600/40 file:cursor-pointer cursor-pointer"
                        >
                        <p class="text-[11px] text-slate-500 mt-1">Maksimal 2 MB (JPG, JPEG, PNG, WEBP).</p>
                    </div>
                </div>
            </div>

            @if($event->category === 'KANDIDAT')
                <!-- Visi Kandidat -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Visi Kandidat <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="editCandidateVision"
                        name="vision"
                        rows="3"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm leading-relaxed"
                    ></textarea>
                </div>

                <!-- Misi Kandidat -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Misi Kandidat <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="editCandidateMission"
                        name="mission"
                        rows="4"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm leading-relaxed"
                    ></textarea>
                </div>

                <!-- Deskripsi / Biodata -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Biodata / Profil Singkat <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="editCandidateDescription"
                        name="description"
                        rows="2"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm"
                    ></textarea>
                </div>
            @else
                <!-- Deskripsi OPSI -->
                <div>
                    <label class="block text-xs font-bold text-slate-200 mb-1.5">
                        Deskripsi & Keterangan Opsi <span class="text-slate-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="editCandidateDescription"
                        name="description"
                        rows="4"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[#050d1a] border border-cyan-900/60 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm leading-relaxed"
                    ></textarea>
                </div>
            @endif

            <!-- Tombol Aksi Modal -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-cyan-950">
                <button
                    type="button"
                    onclick="closeEditCandidateModal()"
                    class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-md shadow-cyan-950/60 border border-cyan-400/40 transition-all cursor-pointer"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 3: PREVIEW DETAIL VISI & MISI KANDIDAT
     ========================================================================= -->
<div
    id="visionMissionModal"
    class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs items-center justify-center p-4 hidden overflow-y-auto"
>
    <div class="relative w-full max-w-2xl bg-[#071226] border border-cyan-800/80 rounded-2xl shadow-2xl overflow-hidden my-8">
        <!-- Header Modal -->
        <div class="p-5 bg-[#071226] border-b border-cyan-900/70 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div id="vmCandidateNumberBadge" class="w-10 h-10 rounded-xl bg-[#042438] text-cyan-300 border border-cyan-400/60 flex items-center justify-center font-black text-base">
                    #01
                </div>
                <div>
                    <h3 id="vmCandidateName" class="text-base font-extrabold text-white">
                        Nama Kandidat
                    </h3>
                    <p class="text-xs text-cyan-300/70">
                        Visi & Misi
                    </p>
                </div>
            </div>
            <button
                type="button"
                onclick="closeVisionMissionModal()"
                class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800/60 transition-colors cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
            <!-- Profil / Biodata jika ada -->
            <div id="vmDescriptionContainer" class="p-3.5 rounded-xl bg-[#050d1a] border border-cyan-950 text-xs text-slate-300 hidden">
                <strong class="text-cyan-400 block mb-1">Profil Kandidat:</strong>
                <p id="vmDescriptionText" class="leading-relaxed"></p>
            </div>

            <!-- Visi -->
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <h4 class="text-xs font-black uppercase tracking-wider text-cyan-300">
                        VISI
                    </h4>
                </div>
                <div class="p-4 rounded-xl bg-[#050d1a] border border-cyan-950 text-slate-200 text-xs leading-relaxed">
                    <p id="vmVisionText" class="whitespace-pre-line"></p>
                </div>
            </div>

            <!-- Misi -->
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <h4 class="text-xs font-black uppercase tracking-wider text-indigo-300">
                        MISI
                    </h4>
                </div>
                <div class="p-4 rounded-xl bg-[#050d1a] border border-cyan-950 text-slate-200 text-xs leading-relaxed">
                    <p id="vmMissionText" class="whitespace-pre-line"></p>
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="p-4 bg-[#050d1a] border-t border-cyan-950/80 flex justify-end">
            <button
                type="button"
                onclick="closeVisionMissionModal()"
                class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition-colors cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL 4: KONFIRMASI HAPUS KANDIDAT / OPSI
     ========================================================================= -->
<div
    id="deleteCandidateModal"
    class="fixed inset-0 z-50 bg-black/80 items-center justify-center p-4 hidden"
>
    <div class="relative w-full max-w-md bg-[#071226] border border-rose-900/60 rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-950/60 border border-rose-800/80 text-rose-400 flex items-center justify-center mx-auto shadow-lg">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-white">
                    Hapus {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}?
                </h3>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin menghapus <strong id="deleteCandidateNameText" class="text-white"></strong> (No. Urut <span id="deleteCandidateNumberText"></span>)? File foto dan data terkait akan dihapus secara permanen.
                </p>
            </div>
        </div>

        <form id="deleteCandidateForm" method="POST" class="p-4 bg-[#050d1a] border-t border-cyan-950 flex items-center justify-end gap-3">
            @csrf
            @method('DELETE')
            <button
                type="button"
                onclick="closeDeleteCandidateModal()"
                class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold transition-all cursor-pointer"
            >
                Batal
            </button>
            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md border border-rose-400/40 transition-all cursor-pointer"
            >
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<!-- =========================================================================
     5. JAVASCRIPT LOGIC INTERAKTIF
     ========================================================================= -->
<script>
    // 1. Live Preview Image Upload Helper
    function previewImage(event, containerId) {
        const container = document.getElementById(containerId);
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }

    // 2. Modal Tambah Kandidat
    function openCreateCandidateModal() {
        const modal = document.getElementById('createCandidateModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeCreateCandidateModal() {
        const modal = document.getElementById('createCandidateModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // 3. Modal Edit Kandidat
    function openEditCandidateModal(candidate) {
        const form = document.getElementById('editCandidateForm');
        form.action = "{{ url('/petugas/events/' . $event->id . '/candidates') }}/" + candidate.id;

        document.getElementById('editCandidateSubtitle').textContent = `Perbarui data #${String(candidate.candidate_number).padStart(2, '0')} - ${candidate.name}`;
        document.getElementById('editCandidateNumber').value = candidate.candidate_number;
        document.getElementById('editCandidateName').value = candidate.name;

        const visionField = document.getElementById('editCandidateVision');
        if (visionField) visionField.value = candidate.vision || '';

        const missionField = document.getElementById('editCandidateMission');
        if (missionField) missionField.value = candidate.mission || '';

        const descField = document.getElementById('editCandidateDescription');
        if (descField) descField.value = candidate.description || '';

        // Handle Photo Preview
        const previewContainer = document.getElementById('editImagePreviewContainer');
        if (candidate.photo) {
            previewContainer.innerHTML = `<img src="/storage/${candidate.photo}" class="w-full h-full object-cover">`;
        } else {
            previewContainer.innerHTML = `
                <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>`;
        }

        document.getElementById('editPhotoInput').value = '';
        const modal = document.getElementById('editCandidateModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeEditCandidateModal() {
        const modal = document.getElementById('editCandidateModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // 4. Modal Visi & Misi Preview
    function openVisionMissionModal(candidate) {
        document.getElementById('vmCandidateNumberBadge').textContent = '#' + String(candidate.candidate_number).padStart(2, '0');
        document.getElementById('vmCandidateName').textContent = candidate.name;

        const descContainer = document.getElementById('vmDescriptionContainer');
        const descText = document.getElementById('vmDescriptionText');
        if (candidate.description && candidate.description.trim() !== '') {
            descText.textContent = candidate.description;
            descContainer.classList.remove('hidden');
        } else {
            descContainer.classList.add('hidden');
        }

        document.getElementById('vmVisionText').textContent = candidate.vision || 'Belum ada visi yang dicantumkan.';
        document.getElementById('vmMissionText').textContent = candidate.mission || 'Belum ada misi yang dicantumkan.';

        const modal = document.getElementById('visionMissionModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeVisionMissionModal() {
        const modal = document.getElementById('visionMissionModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // 5. Modal Delete Confirmation
    function openDeleteCandidateModal(candidateId, candidateName, candidateNumber) {
        const form = document.getElementById('deleteCandidateForm');
        form.action = "{{ url('/petugas/events/' . $event->id . '/candidates') }}/" + candidateId;

        document.getElementById('deleteCandidateNameText').textContent = candidateName;
        document.getElementById('deleteCandidateNumberText').textContent = '#' + candidateNumber;

        const modal = document.getElementById('deleteCandidateModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeDeleteCandidateModal() {
        const modal = document.getElementById('deleteCandidateModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // 6. Copy Room Code Helper
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
        }).catch(() => {
            // Fallback untuk browser lama
            prompt('Salin Kode Room:', code);
        });
    }

    // Close Modals on ESC Key or Backdrop click
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateCandidateModal();
            closeEditCandidateModal();
            closeVisionMissionModal();
            closeDeleteCandidateModal();
        }
    });

    ['createCandidateModal', 'editCandidateModal', 'visionMissionModal', 'deleteCandidateModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }
    });
</script>
@endsection
