@extends('layouts.admin')

@section('title', 'Kelola Akun')
@section('page_title', 'Kelola Akun')

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         HEADER HALAMAN & TAB NAVIGASI UTAMA
         ========================================================================= -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                <span>Kelola Akun Pengguna</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                Manajemen akun kredensial Super Admin, Petugas Pemilihan, dan Daftar Pemilih Tetap (Siswa & Guru)
            </p>
        </div>

        <!-- 3 Tab Navigasi -->
        <div class="grid grid-cols-3 w-full sm:w-auto sm:flex items-center p-1 rounded-xl bg-[#0b1120] border border-slate-800/80 shadow-inner">
            <!-- Tab 1: Super Admin -->
            <a
                href="{{ route('admin.users.index', ['tab' => 'super_admin']) }}"
                class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $activeTab === 'super_admin' ? 'bg-[#800020] text-white shadow-md shadow-rose-950/60 border border-rose-600/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}"
            >
                Super Admin
            </a>

            <!-- Tab 2: Petugas -->
            <a
                href="{{ route('admin.users.index', ['tab' => 'petugas']) }}"
                class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $activeTab === 'petugas' ? 'bg-[#800020] text-white shadow-md shadow-rose-950/60 border border-rose-600/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}"
            >
                Petugas
            </a>

            <!-- Tab 3: Pemilih -->
            <a
                href="{{ route('admin.users.index', ['tab' => 'pemilih']) }}"
                class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $activeTab === 'pemilih' ? 'bg-[#800020] text-white shadow-md shadow-rose-950/60 border border-rose-600/40' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}"
            >
                Pemilih
            </a>
        </div>
    </div>

    <!-- Alert Error Form Validation -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-200 text-xs shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-1 text-rose-300">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Terjadi kesalahan pada input data:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-300/90 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- =========================================================================
         TAB 1: KONTEN KELOLA SUPER ADMIN
         ========================================================================= -->
    @if ($activeTab === 'super_admin')
        <div class="space-y-4">
            <!-- Toolbar: Search & Tombol Tambah Super Admin -->
            <div class="p-4 rounded-2xl bg-[#0c1424] border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <!-- Form Pencarian Nama -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-1 max-w-md flex items-center gap-2">
                    <input type="hidden" name="tab" value="super_admin">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search_admin"
                            value="{{ request('search_admin') }}"
                            placeholder="Cari Super Admin berdasarkan nama..."
                            class="w-full pl-9 pr-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-900/30 transition-all"
                        >
                    </div>
                    <button
                        type="submit"
                        class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition-all cursor-pointer"
                    >
                        Cari
                    </button>
                    @if(request('search_admin'))
                        <a
                            href="{{ route('admin.users.index', ['tab' => 'super_admin']) }}"
                            class="px-3 py-2 rounded-xl bg-rose-950/60 hover:bg-rose-900/80 text-rose-300 text-xs font-medium border border-rose-800/60 transition-all"
                            title="Reset Pencarian"
                        >
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah Akun Super Admin -->
                <button
                    type="button"
                    onclick="openModal('modalAddSuperAdmin')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-xs font-bold border border-rose-600/40 shadow-md shadow-rose-950/60 transition-all cursor-pointer whitespace-nowrap"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Super Admin</span>
                </button>
            </div>

            <!-- Tabel Data Super Admin -->
            <div class="rounded-2xl bg-[#0c1424] border border-slate-800/80 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-800/80 bg-[#070e1c]/60 text-slate-400 font-semibold tracking-wider uppercase text-[10px]">
                                <th class="py-3 px-4">Nama Lengkap</th>
                                <th class="py-3 px-4">Alamat Email</th>
                                <th class="py-3 px-4">Tanggal Dibuat</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse ($superAdmins as $admin)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-white">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-[#800020] border border-rose-500/30 text-white font-bold text-[11px] flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span>{{ $admin->name }}</span>
                                                @if(Auth::id() === $admin->id)
                                                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-950 text-emerald-300 border border-emerald-700/60">
                                                        Anda
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-300">
                                        {{ $admin->email }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400">
                                        {{ $admin->created_at ? $admin->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Tombol Edit -->
                                            <button
                                                type="button"
                                                onclick="openEditUserModal({{ $admin->id }}, '{{ addslashes($admin->name) }}', '{{ addslashes($admin->email) }}', 'SUPER_ADMIN')"
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-[11px] font-semibold border border-slate-700 transition-all cursor-pointer"
                                            >
                                                Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            @if(Auth::id() === $admin->id)
                                                <button
                                                    type="button"
                                                    disabled
                                                    class="px-2.5 py-1.5 rounded-lg bg-slate-900 text-slate-600 text-[11px] font-semibold border border-slate-800 cursor-not-allowed opacity-50"
                                                    title="Tidak dapat menghapus akun Anda sendiri"
                                                >
                                                    Hapus
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    onclick="openDeleteUserModal({{ $admin->id }}, '{{ addslashes($admin->name) }}', 'Super Admin')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-rose-950/60 hover:bg-rose-900 text-rose-300 hover:text-white text-[11px] font-semibold border border-rose-900/60 transition-all cursor-pointer"
                                                >
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-slate-500">
                                        <svg class="w-8 h-8 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="font-medium text-xs text-slate-400">Belum ada data Super Admin ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($superAdmins->hasPages())
                    <div class="p-4 border-t border-slate-800/80">
                        {{ $superAdmins->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- =========================================================================
         TAB 2: KONTEN KELOLA PETUGAS
         ========================================================================= -->
    @if ($activeTab === 'petugas')
        <div class="space-y-4">
            <!-- Toolbar: Search & Tombol Tambah Petugas -->
            <div class="p-4 rounded-2xl bg-[#0c1424] border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <!-- Form Pencarian Nama -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-1 max-w-md flex items-center gap-2">
                    <input type="hidden" name="tab" value="petugas">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search_petugas"
                            value="{{ request('search_petugas') }}"
                            placeholder="Cari Petugas berdasarkan nama..."
                            class="w-full pl-9 pr-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-900/30 transition-all"
                        >
                    </div>
                    <button
                        type="submit"
                        class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition-all cursor-pointer"
                    >
                        Cari
                    </button>
                    @if(request('search_petugas'))
                        <a
                            href="{{ route('admin.users.index', ['tab' => 'petugas']) }}"
                            class="px-3 py-2 rounded-xl bg-rose-950/60 hover:bg-rose-900/80 text-rose-300 text-xs font-medium border border-rose-800/60 transition-all"
                            title="Reset Pencarian"
                        >
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah Akun Petugas -->
                <button
                    type="button"
                    onclick="openModal('modalAddPetugas')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-xs font-bold border border-rose-600/40 shadow-md shadow-rose-950/60 transition-all cursor-pointer whitespace-nowrap"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Petugas</span>
                </button>
            </div>

            <!-- Tabel Data Petugas -->
            <div class="rounded-2xl bg-[#0c1424] border border-slate-800/80 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-800/80 bg-[#070e1c]/60 text-slate-400 font-semibold tracking-wider uppercase text-[10px]">
                                <th class="py-3 px-4">Nama Petugas</th>
                                <th class="py-3 px-4">Alamat Email</th>
                                <th class="py-3 px-4">Tanggal Dibuat</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse ($petugasList as $petugas)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-white">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-blue-950 border border-blue-500/30 text-blue-300 font-bold text-[11px] flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($petugas->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $petugas->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-300">
                                        {{ $petugas->email }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400">
                                        {{ $petugas->created_at ? $petugas->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Tombol Edit -->
                                            <button
                                                type="button"
                                                onclick="openEditUserModal({{ $petugas->id }}, '{{ addslashes($petugas->name) }}', '{{ addslashes($petugas->email) }}', 'PETUGAS')"
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-[11px] font-semibold border border-slate-700 transition-all cursor-pointer"
                                            >
                                                Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button
                                                type="button"
                                                onclick="openDeleteUserModal({{ $petugas->id }}, '{{ addslashes($petugas->name) }}', 'Petugas')"
                                                class="px-2.5 py-1.5 rounded-lg bg-rose-950/60 hover:bg-rose-900 text-rose-300 hover:text-white text-[11px] font-semibold border border-rose-900/60 transition-all cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-slate-500">
                                        <svg class="w-8 h-8 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="font-medium text-xs text-slate-400">Belum ada data Petugas ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($petugasList->hasPages())
                    <div class="p-4 border-t border-slate-800/80">
                        {{ $petugasList->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- =========================================================================
         TAB 3: KONTEN KELOLA PEMILIH (SISWA & GURU)
         ========================================================================= -->
    @if ($activeTab === 'pemilih')
        <div class="space-y-4">
            <!-- Toolbar: Search, Filter Tipe & Tombol Tambah Pemilih -->
            <div class="p-4 rounded-2xl bg-[#0c1424] border border-slate-800/80 flex flex-col lg:flex-row lg:items-center justify-between gap-3 shadow-sm">
                <!-- Form Pencarian & Filter -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-1 flex flex-wrap items-center gap-2">
                    <input type="hidden" name="tab" value="pemilih">

                    <!-- Input Search Nama atau NIS/NIP -->
                    <div class="relative flex-1 min-w-55">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search_voter"
                            value="{{ request('search_voter') }}"
                            placeholder="Cari berdasarkan nama atau NIS / NIP..."
                            class="w-full pl-9 pr-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-900/30 transition-all"
                        >
                    </div>

                    <!-- Dropdown Filter Tipe Pemilih -->
                    <select
                        name="type_voter"
                        class="px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-rose-500 transition-all"
                    >
                        <option value="">Semua Tipe</option>
                        <option value="SISWA" {{ request('type_voter') === 'SISWA' ? 'selected' : '' }}>Siswa</option>
                        <option value="GURU" {{ request('type_voter') === 'GURU' ? 'selected' : '' }}>Guru & Tenaga Pendidik</option>
                    </select>

                    <button
                        type="submit"
                        class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition-all cursor-pointer"
                    >
                        Filter & Cari
                    </button>

                    @if(request('search_voter') || request('type_voter'))
                        <a
                            href="{{ route('admin.users.index', ['tab' => 'pemilih']) }}"
                            class="px-3 py-2 rounded-xl bg-rose-950/60 hover:bg-rose-900/80 text-rose-300 text-xs font-medium border border-rose-800/60 transition-all"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </form>

                <!-- Tombol Tambah Pemilih -->
                <button
                    type="button"
                    onclick="openModal('modalAddVoter')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-xs font-bold border border-rose-600/40 shadow-md shadow-rose-950/60 transition-all cursor-pointer whitespace-nowrap"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Pemilih</span>
                </button>
            </div>

            <!-- Tabel Data Pemilih -->
            <div class="rounded-2xl bg-[#0c1424] border border-slate-800/80 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-800/80 bg-[#070e1c]/60 text-slate-400 font-semibold tracking-wider uppercase text-[10px]">
                                <th class="py-3 px-4">NIS / NIP</th>
                                <th class="py-3 px-4">Nama Pemilih</th>
                                <th class="py-3 px-4">Tipe</th>
                                <th class="py-3 px-4">Tanggal Ditambahkan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse ($voters as $voter)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3.5 px-4 font-mono font-bold text-white">
                                        {{ $voter->identifier }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-white">
                                        {{ $voter->name }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($voter->type === 'SISWA')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-950/80 text-sky-300 border border-sky-700/60">
                                                Siswa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-950/80 text-amber-300 border border-amber-700/60">
                                                Guru
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400">
                                        {{ $voter->created_at ? $voter->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Tombol Edit -->
                                            <button
                                                type="button"
                                                onclick="openEditVoterModal({{ $voter->id }}, '{{ addslashes($voter->identifier) }}', '{{ addslashes($voter->name) }}', '{{ $voter->type }}')"
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-[11px] font-semibold border border-slate-700 transition-all cursor-pointer"
                                            >
                                                Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button
                                                type="button"
                                                onclick="openDeleteVoterModal({{ $voter->id }}, '{{ addslashes($voter->name) }}', '{{ addslashes($voter->identifier) }}')"
                                                class="px-2.5 py-1.5 rounded-lg bg-rose-950/60 hover:bg-rose-900 text-rose-300 hover:text-white text-[11px] font-semibold border border-rose-900/60 transition-all cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-500">
                                        <svg class="w-8 h-8 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="font-medium text-xs text-slate-400">Belum ada data Pemilih ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($voters->hasPages())
                    <div class="p-4 border-t border-slate-800/80">
                        {{ $voters->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>

<!-- =========================================================================
     MODAL 1: TAMBAH SUPER ADMIN
     ========================================================================= -->
<div id="modalAddSuperAdmin" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-md rounded-2xl bg-[#0c1424] border border-slate-800 p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                Tambah Super Admin Baru
            </h3>
            <button type="button" onclick="closeModal('modalAddSuperAdmin')" class="text-slate-400 hover:text-white text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="role" value="SUPER_ADMIN">

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Contoh: Ahmad Subagja" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                <input type="email" name="email" required placeholder="admin@sekolah.sch.id" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required minlength="6" placeholder="Ulangi password" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="closeModal('modalAddSuperAdmin')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-xs font-bold border border-rose-600/40 shadow-md transition-all cursor-pointer">Simpan Super Admin</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 2: TAMBAH PETUGAS
     ========================================================================= -->
<div id="modalAddPetugas" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-md rounded-2xl bg-[#0c1424] border border-slate-800 p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                Tambah Petugas Baru
            </h3>
            <button type="button" onclick="closeModal('modalAddPetugas')" class="text-slate-400 hover:text-white text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="role" value="PETUGAS">

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap Petugas</label>
                <input type="text" name="name" required placeholder="Contoh: Panitia Pemilihan OSIS" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                <input type="email" name="email" required placeholder="petugas@sekolah.sch.id" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required minlength="6" placeholder="Ulangi password" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="closeModal('modalAddPetugas')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-xs font-bold border border-rose-600/40 shadow-md transition-all cursor-pointer">Simpan Petugas</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 3: EDIT USER (SUPER ADMIN / PETUGAS)
     ========================================================================= -->
<div id="modalEditUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-md rounded-2xl bg-[#0c1424] border border-slate-800 p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                Edit Data Akun Pengguna
            </h3>
            <button type="button" onclick="closeModal('modalEditUser')" class="text-slate-400 hover:text-white text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form id="formEditUser" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" id="editUserName" name="name" required class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                <input type="email" id="editUserEmail" name="email" required class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div class="pt-2 border-t border-slate-800/80">
                <p class="text-[11px] text-slate-400 font-semibold mb-2">Ganti Password (Kosongkan jika tidak ingin diubah)</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Password Baru</label>
                        <input type="password" name="password" minlength="6" placeholder="Opsional (min. 6 karakter)" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" minlength="6" placeholder="Ulangi password baru" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="closeModal('modalEditUser')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition-all cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 4: HAPUS USER (SUPER ADMIN / PETUGAS)
     ========================================================================= -->
<div id="modalDeleteUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-sm rounded-2xl bg-[#0c1424] border border-rose-900/60 p-6 shadow-2xl space-y-4">
        <div class="w-10 h-10 rounded-xl bg-rose-950/80 border border-rose-600/40 text-rose-400 flex items-center justify-center mx-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>

        <div class="text-center space-y-1">
            <h3 class="text-sm font-bold text-white">Konfirmasi Hapus Akun</h3>
            <p class="text-xs text-slate-400">
                Apakah Anda yakin ingin menghapus akun <span id="deleteUserName" class="font-bold text-rose-300"></span> (<span id="deleteUserRole" class="text-slate-300"></span>)?
            </p>
        </div>

        <form id="formDeleteUser" action="" method="POST" class="pt-2 flex items-center justify-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modalDeleteUser')" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
            <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-all cursor-pointer">Ya, Hapus</button>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 5: TAMBAH PEMILIH
     ========================================================================= -->
<div id="modalAddVoter" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-md rounded-2xl bg-[#0c1424] border border-slate-800 p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                Tambah Pemilih Baru
            </h3>
            <button type="button" onclick="closeModal('modalAddVoter')" class="text-slate-400 hover:text-white text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.voters.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">NIS / NIP</label>
                <input type="text" name="identifier" required placeholder="Contoh: 20241001 atau 19850101..." class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 font-mono focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap Pemilih</label>
                <input type="text" name="name" required placeholder="Contoh: Muhammad Rizky" class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tipe Pemilih</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#070e1c] border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="radio" name="type" value="SISWA" checked class="text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-bold text-slate-200">Siswa</span>
                    </label>
                    <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#070e1c] border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="radio" name="type" value="GURU" class="text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-bold text-slate-200">Guru / Staff</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="closeModal('modalAddVoter')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-xs font-bold border border-rose-600/40 shadow-md transition-all cursor-pointer">Simpan Pemilih</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 6: EDIT PEMILIH
     ========================================================================= -->
<div id="modalEditVoter" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-md rounded-2xl bg-[#0c1424] border border-slate-800 p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                Edit Data Pemilih
            </h3>
            <button type="button" onclick="closeModal('modalEditVoter')" class="text-slate-400 hover:text-white text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form id="formEditVoter" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">NIS / NIP</label>
                <input type="text" id="editVoterIdentifier" name="identifier" required class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white font-mono focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap Pemilih</label>
                <input type="text" id="editVoterName" name="name" required class="w-full px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tipe Pemilih</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#070e1c] border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="radio" id="editVoterTypeSiswa" name="type" value="SISWA" class="text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-bold text-slate-200">Siswa</span>
                    </label>
                    <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#070e1c] border border-slate-800 hover:border-slate-700 cursor-pointer">
                        <input type="radio" id="editVoterTypeGuru" name="type" value="GURU" class="text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-bold text-slate-200">Guru / Staff</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="closeModal('modalEditVoter')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition-all cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL 7: HAPUS PEMILIH
     ========================================================================= -->
<div id="modalDeleteVoter" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
    <div class="w-full max-w-sm rounded-2xl bg-[#0c1424] border border-rose-900/60 p-6 shadow-2xl space-y-4">
        <div class="w-10 h-10 rounded-xl bg-rose-950/80 border border-rose-600/40 text-rose-400 flex items-center justify-center mx-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>

        <div class="text-center space-y-1">
            <h3 class="text-sm font-bold text-white">Konfirmasi Hapus Pemilih</h3>
            <p class="text-xs text-slate-400">
                Apakah Anda yakin ingin menghapus data pemilih <span id="deleteVoterName" class="font-bold text-rose-300"></span> (NIS/NIP: <span id="deleteVoterIdentifier" class="font-mono text-slate-300"></span>)?
            </p>
        </div>

        <form id="formDeleteVoter" action="" method="POST" class="pt-2 flex items-center justify-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modalDeleteVoter')" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all cursor-pointer">Batal</button>
            <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-all cursor-pointer">Ya, Hapus</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Utility Modal Open & Close
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }

    // Modal Edit User (Super Admin / Petugas)
    function openEditUserModal(userId, userName, userEmail, role) {
        const form = document.getElementById('formEditUser');
        form.action = `/admin/users/${userId}`;
        document.getElementById('editUserName').value = userName;
        document.getElementById('editUserEmail').value = userEmail;
        openModal('modalEditUser');
    }

    // Modal Hapus User (Super Admin / Petugas)
    function openDeleteUserModal(userId, userName, userRole) {
        const form = document.getElementById('formDeleteUser');
        form.action = `/admin/users/${userId}`;
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserRole').textContent = userRole;
        openModal('modalDeleteUser');
    }

    // Modal Edit Voter
    function openEditVoterModal(voterId, identifier, name, type) {
        const form = document.getElementById('formEditVoter');
        form.action = `/admin/voters/${voterId}`;
        document.getElementById('editVoterIdentifier').value = identifier;
        document.getElementById('editVoterName').value = name;
        if (type === 'GURU') {
            document.getElementById('editVoterTypeGuru').checked = true;
        } else {
            document.getElementById('editVoterTypeSiswa').checked = true;
        }
        openModal('modalEditVoter');
    }

    // Modal Hapus Voter
    function openDeleteVoterModal(voterId, name, identifier) {
        const form = document.getElementById('formDeleteVoter');
        form.action = `/admin/voters/${voterId}`;
        document.getElementById('deleteVoterName').textContent = name;
        document.getElementById('deleteVoterIdentifier').textContent = identifier;
        openModal('modalDeleteVoter');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modals = ['modalAddSuperAdmin', 'modalAddPetugas', 'modalEditUser', 'modalDeleteUser', 'modalAddVoter', 'modalEditVoter', 'modalDeleteVoter'];
            modals.forEach(id => closeModal(id));
        }
    });
</script>
@endpush
