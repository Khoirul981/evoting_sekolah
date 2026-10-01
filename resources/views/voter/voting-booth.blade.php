@extends('layouts.voter')

@section('title', 'Bilik Suara - ' . $event->title)

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. HEADER BILIK SUARA & INFORMASI EVENT (Clean White / Slate Theme)
         ========================================================================= -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-7 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <!-- Navigasi & Info Kategori -->
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('voter.dashboard') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors shadow-2xs"
                    title="Kembali ke Lobi"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Ganti Ruangan</span>
                </a>

                <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $event->category === 'KANDIDAT' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                    {{ $event->category === 'KANDIDAT' ? 'Pemilihan Kandidat' : 'Jajak Pendapat / Opsi' }}
                </span>
            </div>

            <!-- Kode Ruangan & Status Bilik -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 text-white font-mono text-xs font-bold shadow-2xs">
                    <span class="text-slate-400 text-[10px] uppercase">KODE ROOM:</span>
                    <span class="text-emerald-400 tracking-wider">{{ $event->room_code }}</span>
                </div>

                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>SIAP MENCOBLOS</span>
                </span>
            </div>
        </div>

        <!-- Judul & Deskripsi Event -->
        <div class="pt-5">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                {{ $event->title }}
            </h1>

            @if($event->description)
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed max-w-4xl">
                    {{ $event->description }}
                </p>
            @endif

            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-4 pt-3.5 border-t border-slate-100 text-xs text-slate-500 font-medium">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Total <strong>{{ $candidates->count() }}</strong> {{ $event->category === 'KANDIDAT' ? 'Kandidat Paslon' : 'Pilihan Opsi' }}</span>
                </span>

                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Asas Pemilihan: Langsung, Umum, Bebas, <strong>Rahasia (LUBER)</strong></span>
                </span>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         2. DAFTAR KANDIDAT / OPSI (Interactive Voting Cards)
         ========================================================================= -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 tracking-tight">
                {{ $event->category === 'KANDIDAT' ? 'Tentukan Pilihan Kandidat Terbaik Anda' : 'Pilih Salah Satu Opsi di Bawah' }}
            </h2>
            <span class="text-xs text-slate-500">Klik tombol coblos untuk memilih</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($candidates as $candidate)
                <div class="bg-white border border-slate-200/90 hover:border-slate-400 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between gap-5 group">
                    <div class="space-y-4">
                        <!-- Top Header Card: No Urut & Label -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-slate-900 text-white font-extrabold text-sm flex items-center justify-center shadow-xs">
                                    #{{ $candidate->candidate_number }}
                                </span>
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    {{ $event->category === 'KANDIDAT' ? 'Kandidat ' . $candidate->candidate_number : 'Opsi ' . $candidate->candidate_number }}
                                </span>
                            </div>

                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded">
                                Surat Suara
                            </span>
                        </div>

                        <!-- Foto Kandidat / Placeholder -->
                        <div class="w-full aspect-4/3 rounded-xl overflow-hidden bg-slate-100 border border-slate-200/80 flex items-center justify-center relative shadow-inner">
                            @if($candidate->photo_url)
                                <img
                                    src="{{ $candidate->photo_url }}"
                                    alt="{{ $candidate->name }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="text-center p-4 text-slate-400">
                                    <div class="w-12 h-12 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center mx-auto mb-1.5">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <p class="text-[11px] font-medium text-slate-500">Tanpa Foto Lampiran</p>
                                </div>
                            @endif
                        </div>

                        <!-- Nama Kandidat -->
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                                {{ $candidate->name }}
                            </h3>
                        </div>

                        <!-- Visi & Misi atau Deskripsi Opsi -->
                        @if($event->category === 'KANDIDAT')
                            <div class="space-y-3 pt-2 text-xs border-t border-slate-100">
                                @if($candidate->vision)
                                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/70">
                                        <h4 class="font-bold text-slate-900 text-[11px] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-900"></span>
                                            Visi
                                        </h4>
                                        <p class="text-slate-600 leading-relaxed italic">
                                            "{{ $candidate->vision }}"
                                        </p>
                                    </div>
                                @endif

                                @if($candidate->mission)
                                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/70">
                                        <h4 class="font-bold text-slate-900 text-[11px] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-900"></span>
                                            Misi
                                        </h4>
                                        <div class="text-slate-600 leading-relaxed whitespace-pre-line">
                                            {{ $candidate->mission }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            @if($candidate->description)
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/70 text-xs">
                                    <h4 class="font-bold text-slate-900 text-[11px] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-900"></span>
                                        Keterangan Opsi
                                    </h4>
                                    <p class="text-slate-600 leading-relaxed">
                                        {{ $candidate->description }}
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Tombol Aksi Coblos -->
                    <div class="pt-2">
                        <button
                            type="button"
                            onclick="openVoteModal({{ $candidate->id }}, '{{ $candidate->candidate_number }}', '{{ addslashes($candidate->name) }}')"
                            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold rounded-xl text-xs sm:text-sm shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $event->category === 'KANDIDAT' ? 'COBLOS KANDIDAT #' . $candidate->candidate_number : 'PILIH OPSI #' . $candidate->candidate_number }}</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- =========================================================================
     3. MODAL KONFIRMASI PILIHAN SUARA (Clean White Theme)
     ========================================================================= -->
<div
    id="voteModal"
    class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs items-center justify-center p-4 transition-opacity duration-200"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modalTitle"
>
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-150">

        <!-- Header Modal -->
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3.5 shadow-2xs">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h3 id="modalTitle" class="text-xl font-bold text-slate-900 tracking-tight">
                Konfirmasi Pilihan Suara
            </h3>
            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                Pastikan pilihan Anda sudah benar sebelum mengirimkan suara ke sistem.
            </p>
        </div>

        <!-- Box Kandidat Terpilih -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center space-y-1.5">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-slate-900 text-white font-mono text-[11px] font-extrabold uppercase">
                <span id="modalCandidateNumber">#1</span>
            </div>
            <h4 id="modalCandidateName" class="text-base font-extrabold text-slate-900">
                Nama Kandidat
            </h4>
            <p class="text-[11px] text-slate-500">
                Event: <span class="font-semibold text-slate-700">{{ $event->title }}</span>
            </p>
        </div>

        <!-- Warning Callout -->
        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-2.5">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <p class="leading-relaxed">
                Pilihan suara Anda bersifat <strong>rahasia</strong>, <strong>permanen</strong>, dan <strong>tidak dapat diubah</strong> setelah dikirimkan.
            </p>
        </div>

        <!-- Form Submit & Aksi Modal -->
        <form
            id="voteForm"
            action="{{ route('voter.events.vote.post', $event->room_code) }}"
            method="POST"
            class="space-y-2 pt-2"
        >
            @csrf
            <input type="hidden" name="candidate_id" id="modalCandidateId" value="">

            <div class="grid grid-cols-2 gap-3">
                <button
                    type="button"
                    onclick="closeVoteModal()"
                    class="w-full py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition-colors cursor-pointer"
                >
                    Periksa Ulang
                </button>

                <button
                    type="submit"
                    id="btnSubmitVote"
                    class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-sm transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <span>Ya, Coblos Sekarang!</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function openVoteModal(id, number, name) {
        document.getElementById('modalCandidateId').value = id;
        document.getElementById('modalCandidateNumber').textContent = '#' + number;
        document.getElementById('modalCandidateName').textContent = name;

        const modal = document.getElementById('voteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeVoteModal() {
        const modal = document.getElementById('voteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Tutup modal jika klik di luar area card
    document.getElementById('voteModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'voteModal') {
            closeVoteModal();
        }
    });

    // Cegah double submission
    document.getElementById('voteForm')?.addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmitVote');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Suara...</span>
            `;
        }
    });
</script>
@endpush
