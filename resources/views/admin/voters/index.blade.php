@extends('layouts.admin')

@section('title', 'Master Pemilih (DPT)')
@section('page_title', 'Master Data Pemilih (DPT)')
@section('page_description', 'Kelola daftar pemilih tetap (Siswa & Guru), aktivasi hak suara, dan impor massal CSV.')

@section('content')
<div class="space-y-6">

    <!-- Overview Stats Banner -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div>
            <div class="text-[11px] font-semibold text-slate-400 uppercase">Total DPT</div>
            <div class="text-xl font-bold text-white mt-0.5">{{ $totalCount }}</div>
        </div>
        <div>
            <div class="text-[11px] font-semibold text-slate-400 uppercase">Pemilih Siswa</div>
            <div class="text-xl font-bold text-indigo-400 mt-0.5">{{ $siswaCount }}</div>
        </div>
        <div>
            <div class="text-[11px] font-semibold text-slate-400 uppercase">Pemilih Guru</div>
            <div class="text-xl font-bold text-emerald-400 mt-0.5">{{ $guruCount }}</div>
        </div>
        <div>
            <div class="text-[11px] font-semibold text-slate-400 uppercase">Akun Aktif</div>
            <div class="text-xl font-bold text-slate-200 mt-0.5">{{ $activeCount }} <span class="text-xs font-normal text-slate-500">/ {{ $totalCount }}</span></div>
        </div>
    </div>

    <!-- Filters and Action Buttons -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-slate-900 border border-slate-800 rounded-xl p-4">
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.voters.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari NIS, NIP, atau Nama Pemilih..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Filter Type -->
            <select name="type" onchange="this.form.submit()" class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                <option value="SISWA" {{ request('type') === 'SISWA' ? 'selected' : '' }}>Siswa</option>
                <option value="GURU" {{ request('type') === 'GURU' ? 'selected' : '' }}>Guru & Staf</option>
            </select>

            <!-- Filter Status -->
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
            </select>

            <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700">
                Filter
            </button>
            @if(request()->hasAny(['search', 'type', 'status']))
                <a href="{{ route('admin.voters.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-200 text-xs">Reset</a>
            @endif
        </form>

        <!-- Actions: Import, Export, Tambah -->
        <div class="flex items-center gap-2 flex-wrap">
            <button
                type="button"
                onclick="document.getElementById('modalImportCsv').classList.remove('hidden')"
                class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors border border-slate-700 cursor-pointer"
            >
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Impor CSV</span>
            </button>

            <a
                href="{{ route('admin.voters.export') }}"
                class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors border border-slate-700"
            >
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Ekspor CSV</span>
            </a>

            <button
                type="button"
                onclick="document.getElementById('modalTambahVoter').classList.remove('hidden')"
                class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pemilih</span>
            </button>
        </div>
    </div>

    <!-- Voters Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">NIS / NIP</th>
                        <th class="py-3.5 px-4">Nama Lengkap Pemilih</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Status Hak Suara</th>
                        <th class="py-3.5 px-4">Terdaftar Sejak</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($voters as $voter)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-indigo-300">
                                {{ $voter->identifier }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-white">
                                {{ $voter->name }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($voter->type === 'SISWA')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-950 text-indigo-400 border border-indigo-800">
                                        SISWA
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-950 text-emerald-400 border border-emerald-800">
                                        GURU
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.voters.toggle', $voter->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($voter->is_active)
                                        <button
                                            type="submit"
                                            title="Klik untuk menonaktifkan hak suara"
                                            class="px-2.5 py-1 rounded text-[11px] font-semibold bg-emerald-950/80 text-emerald-300 border border-emerald-800 hover:bg-rose-950 hover:text-rose-300 hover:border-rose-800 transition-colors cursor-pointer"
                                        >
                                            Aktif &check;
                                        </button>
                                    @else
                                        <button
                                            type="submit"
                                            title="Klik untuk mengaktifkan kembali"
                                            class="px-2.5 py-1 rounded text-[11px] font-semibold bg-rose-950/80 text-rose-300 border border-rose-800 hover:bg-emerald-950 hover:text-emerald-300 hover:border-emerald-800 transition-colors cursor-pointer"
                                        >
                                            Nonaktif &cross;
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $voter->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        onclick="openEditVoterModal({{ $voter->id }}, '{{ addslashes($voter->identifier) }}', '{{ addslashes($voter->name) }}', '{{ $voter->type }}')"
                                        class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded text-[11px] font-medium transition-colors border border-slate-700"
                                    >
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.voters.destroy', $voter->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemilih \'{{ $voter->name }}\' ({{ $voter->identifier }})?')">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                Tidak ada data Pemilih (DPT) yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($voters->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $voters->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Voter -->
<div id="modalTambahVoter" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Tambah Data Pemilih Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambahVoter').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.voters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Induk (NIS / NIP)</label>
                <input type="text" name="identifier" placeholder="Contoh: 1009 atau 2010" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap Pemilih</label>
                <input type="text" name="name" placeholder="Contoh: Muhammad Budi Santoso" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Kategori Pemilih</label>
                <select name="type" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="SISWA">Siswa (Memiliki NIS)</option>
                    <option value="GURU">Guru & Tenaga Kependidikan (Memiliki NIP)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalTambahVoter').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Daftarkan Pemilih
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Voter -->
<div id="modalEditVoter" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Edit Data Pemilih</h3>
            <button type="button" onclick="document.getElementById('modalEditVoter').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formEditVoter" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Induk (NIS / NIP)</label>
                <input type="text" id="editVoterIdentifier" name="identifier" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap Pemilih</label>
                <input type="text" id="editVoterName" name="name" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Kategori Pemilih</label>
                <select id="editVoterType" name="type" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="SISWA">Siswa (Memiliki NIS)</option>
                    <option value="GURU">Guru & Tenaga Kependidikan (Memiliki NIP)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalEditVoter').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Impor CSV -->
<div id="modalImportCsv" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Impor DPT dari Berkas CSV</h3>
            <button type="button" onclick="document.getElementById('modalImportCsv').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.voters.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Pilih Berkas CSV (.csv)</label>
                <input type="file" name="csv_file" accept=".csv,text/csv" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
            </div>

            <div class="p-3 bg-slate-950 border border-slate-800 rounded-lg text-[11px] text-slate-400 space-y-1">
                <div class="font-semibold text-slate-300">Format Kolom Berkas CSV:</div>
                <div>Kolom 1: <code class="text-indigo-400 font-mono">identifier</code> (NIS atau NIP)</div>
                <div>Kolom 2: <code class="text-indigo-400 font-mono">name</code> (Nama Lengkap)</div>
                <div>Kolom 3: <code class="text-indigo-400 font-mono">type</code> (SISWA atau GURU)</div>
                <div class="pt-1 text-slate-500 italic">Contoh baris: <code>1009,Ahmad Fauzi,SISWA</code></div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalImportCsv').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Mulai Impor Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditVoterModal(id, identifier, name, type) {
        document.getElementById('formEditVoter').action = '/admin/voters/' + id;
        document.getElementById('editVoterIdentifier').value = identifier;
        document.getElementById('editVoterName').value = name;
        document.getElementById('editVoterType').value = type;
        document.getElementById('modalEditVoter').classList.remove('hidden');
    }
</script>
@endpush
@endsection
