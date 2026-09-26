@extends('layouts.admin')

@section('title', $event->title . ' - Kelola Event')
@section('page_title', 'Kelola Event: ' . $event->title)
@section('page_description', 'Manajemen kandidat, pengaturan status bilik suara, dan pemantauan suara masuk.')

@section('content')
<div class="space-y-6">

    <!-- Top Card: Event Summary & Controls -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-800">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="px-2.5 py-1 bg-slate-950 border border-slate-800 rounded font-mono font-bold text-xs text-indigo-300">
                        Kode Room: {{ $event->room_code }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                        {{ $event->category === 'KANDIDAT' ? 'Kandidat Paslon' : 'Polling Aspirasi' }}
                    </span>
                    @if($event->status === 'PUBLISHED')
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            PUBLISHED (Sedang Berjalan)
                        </span>
                    @elseif($event->status === 'DRAFT')
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-amber-950 text-amber-400 border border-amber-800">
                            DRAFT (Bilik Suara Belum Dibuka)
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                            CLOSED (Pemilihan Selesai)
                        </span>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-white tracking-tight">{{ $event->title }}</h2>
                <p class="text-xs text-slate-400 mt-1 max-w-3xl">
                    {{ $event->description ?: 'Tidak ada deskripsi tambahan.' }}
                </p>
            </div>

            <!-- Action Buttons: Status Changer, Edit, Results -->
            <div class="flex items-center gap-2 flex-wrap">
                <a
                    href="{{ route('admin.events.results', $event->id) }}"
                    class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors border border-slate-700"
                >
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Quick Count</span>
                </a>

                <a
                    href="{{ route('admin.events.edit', $event->id) }}"
                    class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700"
                >
                    Edit Event
                </a>

                <!-- Status Controller Form -->
                @if($event->status === 'DRAFT')
                    <form action="{{ route('admin.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Buka bilik suara sekarang? Pemilih akan dapat mulai memberikan suara.')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="PUBLISHED">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                            Buka Bilik Suara (Publish)
                        </button>
                    </form>
                @elseif($event->status === 'PUBLISHED')
                    <form action="{{ route('admin.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Tutup pemilihan sekarang? Setelah ditutup, pemilih tidak dapat memberikan suara lagi.')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="CLOSED">
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                            Tutup Pemilihan (Close)
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.events.status', $event->id) }}" method="POST" onsubmit="return confirm('Buka kembali pemilihan menjadi status Published?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="PUBLISHED">
                        <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700">
                            Buka Kembali
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Metrics bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5 text-xs">
            <div>
                <span class="text-slate-400">Total Suara Masuk:</span>
                <div class="text-lg font-bold text-white mt-0.5">{{ $totalVotes }} Suara</div>
            </div>
            <div>
                <span class="text-slate-400">Tingkat Partisipasi:</span>
                <div class="text-lg font-bold text-emerald-400 mt-0.5">{{ $turnoutRate }}% <span class="text-xs font-normal text-slate-500">({{ $totalParticipated }} / {{ $totalDpt }})</span></div>
            </div>
            <div>
                <span class="text-slate-400">Jadwal Mulai:</span>
                <div class="text-slate-200 font-medium mt-0.5">{{ $event->start_time ? $event->start_time->format('d/m/Y H:i') : 'Fleksibel' }}</div>
            </div>
            <div>
                <span class="text-slate-400">Jadwal Selesai:</span>
                <div class="text-slate-200 font-medium mt-0.5">{{ $event->end_time ? $event->end_time->format('d/m/Y H:i') : 'Fleksibel' }}</div>
            </div>
        </div>
    </div>

    <!-- Candidates Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Daftar {{ $event->category === 'KANDIDAT' ? 'Kandidat Paslon' : 'Opsi Polling' }}</h3>
                <p class="text-xs text-slate-400">Minimal 2 pilihan diperlukan agar bilik suara dapat di-publish.</p>
            </div>

            <button
                type="button"
                onclick="document.getElementById('modalTambahKandidat').classList.remove('hidden')"
                class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}</span>
            </button>
        </div>

        <!-- Candidate Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($event->candidates->sortBy('candidate_number') as $c)
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Candidate Header: Number & Photo -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl bg-slate-950 border border-slate-800 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if($c->photo)
                                    <img src="{{ $c->photo_url }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xl font-bold text-indigo-400 font-mono">{{ $c->candidate_number }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-950 text-indigo-400 border border-indigo-800 font-mono">
                                    NOMOR URUT {{ $c->candidate_number }}
                                </span>
                                <h4 class="font-bold text-sm text-white mt-1 leading-snug truncate">{{ $c->name }}</h4>
                                <div class="text-[11px] text-emerald-400 font-semibold mt-0.5">
                                    {{ $c->votes->count() }} Suara Terkumpul
                                </div>
                            </div>
                        </div>

                        <!-- Vision & Mission Preview -->
                        @if($c->vision)
                            <div class="mb-2">
                                <div class="text-[10px] font-bold uppercase text-slate-400">Visi:</div>
                                <p class="text-xs text-slate-300 line-clamp-2 italic">"{{ $c->vision }}"</p>
                            </div>
                        @endif

                        @if($c->mission)
                            <div class="mb-3">
                                <div class="text-[10px] font-bold uppercase text-slate-400">Misi:</div>
                                <p class="text-xs text-slate-300 line-clamp-2">{{ $c->mission }}</p>
                            </div>
                        @endif

                        @if($c->description && !$c->vision && !$c->mission)
                            <p class="text-xs text-slate-300 line-clamp-3 mb-3">{{ $c->description }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            onclick="openEditCandidateModal({{ $c->id }}, {{ $c->candidate_number }}, '{{ addslashes($c->name) }}', '{{ addslashes($c->vision ?? '') }}', '{{ addslashes($c->mission ?? '') }}', '{{ addslashes($c->description ?? '') }}')"
                            class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded text-[11px] font-medium transition-colors border border-slate-700"
                        >
                            Edit
                        </button>

                        <form action="{{ route('admin.events.candidates.destroy', [$event->id, $c->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat nomor urut {{ $c->candidate_number }} ({{ $c->name }})?')">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="px-2.5 py-1 bg-rose-950/60 hover:bg-rose-900 text-rose-300 rounded text-[11px] font-medium transition-colors border border-rose-800"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-slate-900 border border-dashed border-slate-800 rounded-xl p-8 text-center text-slate-500">
                    <p class="text-xs font-semibold text-slate-400">Belum ada {{ $event->category === 'KANDIDAT' ? 'kandidat' : 'opsi pilihan' }} yang ditambahkan.</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Klik tombol "Tambah {{ $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi' }}" di atas untuk menambahkan peserta pemilihan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Danger Zone: Delete Event (Super Admin or Owner) -->
    <div class="bg-slate-900 border border-rose-900/40 rounded-xl p-5 mt-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-400">Zona Berbahaya: Hapus Event</h4>
                <p class="text-xs text-slate-400 mt-0.5">Menghapus event akan melenyapkan seluruh data kandidat, partisipasi, dan suara yang telah masuk secara permanen.</p>
            </div>
            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('PERINGATAN! Anda akan menghapus event ini secara permanen beserta semua data suaranya. Ketik OK untuk melanjutkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-rose-950 hover:bg-rose-900 text-rose-300 border border-rose-800 rounded-lg text-xs font-semibold transition-colors cursor-pointer whitespace-nowrap">
                    Hapus Event Ini
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Modal Tambah Kandidat -->
<div id="modalTambahKandidat" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-lg w-full p-6 shadow-xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Tambah {{ $event->category === 'KANDIDAT' ? 'Kandidat Baru' : 'Opsi Pilihan' }}</h3>
            <button type="button" onclick="document.getElementById('modalTambahKandidat').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.events.candidates.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Urut <span class="text-rose-400">*</span></label>
                    <input type="number" name="candidate_number" value="{{ $event->candidates->count() + 1 }}" min="1" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kandidat / Judul Opsi <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: Paslon 01 - Budi & Siti" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Foto / Poster Kandidat (Opsional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
            </div>

            @if($event->category === 'KANDIDAT')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Visi</label>
                    <textarea name="vision" rows="2" placeholder="Tuliskan visi kandidat..." class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Misi</label>
                    <textarea name="mission" rows="3" placeholder="Tuliskan poin-poin misi kandidat..." class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            @else
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Keterangan / Deskripsi Opsi</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan detail dari opsi pilihan ini..." class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            @endif

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalTambahKandidat').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Simpan Kandidat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kandidat -->
<div id="modalEditKandidat" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-lg w-full p-6 shadow-xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Edit {{ $event->category === 'KANDIDAT' ? 'Data Kandidat' : 'Opsi Pilihan' }}</h3>
            <button type="button" onclick="document.getElementById('modalEditKandidat').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formEditKandidat" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Urut <span class="text-rose-400">*</span></label>
                    <input type="number" id="editCandidateNumber" name="candidate_number" min="1" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kandidat / Judul Opsi <span class="text-rose-400">*</span></label>
                    <input type="text" id="editCandidateName" name="name" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Ganti Foto / Poster (Opsional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
            </div>

            @if($event->category === 'KANDIDAT')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Visi</label>
                    <textarea id="editCandidateVision" name="vision" rows="2" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Misi</label>
                    <textarea id="editCandidateMission" name="mission" rows="3" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            @else
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Keterangan / Deskripsi Opsi</label>
                    <textarea id="editCandidateDesc" name="description" rows="3" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            @endif

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalEditKandidat').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditCandidateModal(id, number, name, vision, mission, desc) {
        document.getElementById('formEditKandidat').action = '/admin/events/{{ $event->id }}/candidates/' + id;
        document.getElementById('editCandidateNumber').value = number;
        document.getElementById('editCandidateName').value = name;
        if (document.getElementById('editCandidateVision')) {
            document.getElementById('editCandidateVision').value = vision;
        }
        if (document.getElementById('editCandidateMission')) {
            document.getElementById('editCandidateMission').value = mission;
        }
        if (document.getElementById('editCandidateDesc')) {
            document.getElementById('editCandidateDesc').value = desc;
        }
        document.getElementById('modalEditKandidat').classList.remove('hidden');
    }
</script>
@endpush
@endsection
