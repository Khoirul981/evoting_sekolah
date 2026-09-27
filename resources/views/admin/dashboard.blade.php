@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')
@section('page_title', 'Beranda')

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. BANNER SELAMAT DATANG & DESKRIPSI
         ========================================================================= -->
    <div class="bg-linear-to-r from-[#1b050c] via-[#150308] to-[#0c1424] border border-rose-900/40 rounded-2xl p-6 shadow-xl relative overflow-hidden">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-[#800020]/60 text-rose-200 border border-rose-500/30 mb-3">
                <span>PANEL ADMINISTRATOR SISTEM</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xs text-rose-200/80 mt-1.5 max-w-3xl leading-relaxed">
                Sistem Informasi & Manajemen Autentikasi <span class="font-bold text-white">{{ config('app.name', 'VOTINGYUK') }}</span>.
                Anda memiliki hak akses administratif penuh untuk mengelola pengguna sistem (Super Admin, Petugas, dan Pemilih) serta memantau audit keamanan.
            </p>
        </div>
        <!-- Decorative Glow -->
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-[#800020]/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- =========================================================================
         2. CARD RINGKASAN METRIK AKUN (TEMA MERAH MARUN)
         ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Total Super Admin -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-300/80">Total Super Admin</span>
                <div class="w-8 h-8 rounded-lg bg-[#800020] text-rose-200 flex items-center justify-center border border-rose-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalSuperAdmin }}</span>
                <span class="text-xs text-rose-400 font-medium">akun</span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Hak akses penuh pengelola</p>
        </div>

        <!-- Card 2: Total Petugas -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-300/80">Total Petugas</span>
                <div class="w-8 h-8 rounded-lg bg-[#800020] text-rose-200 flex items-center justify-center border border-rose-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalPetugas }}</span>
                <span class="text-xs text-rose-400 font-medium">akun</span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Panitia pelaksana event</p>
        </div>

        <!-- Card 3: Total Pemilih Siswa (NIS) -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-300/80">Pemilih Siswa (NIS)</span>
                <div class="w-8 h-8 rounded-lg bg-[#800020] text-rose-200 flex items-center justify-center border border-rose-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalStudentVoters }}</span>
                <span class="text-xs text-rose-400 font-medium">siswa</span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Terdaftar dengan Nomor Induk Siswa</p>
        </div>

        <!-- Card 4: Total Pemilih Guru (NIP) -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-300/80">Pemilih Guru (NIP)</span>
                <div class="w-8 h-8 rounded-lg bg-[#800020] text-rose-200 flex items-center justify-center border border-rose-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalTeacherVoters }}</span>
                <span class="text-xs text-rose-400 font-medium">guru </span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Terdaftar dengan Nomor Induk Pegawai</p>
        </div>

    </div>

    <!-- =========================================================================
         3. BARIS WEWENANG ROLE & LINGKUNGAN (CARD BIRU GELAP)
         ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- KIRI: WEWENANG & FUNGSI ROLE SISTEM (Separation of Duties) -->
        <div class="bg-[#0c1424] border border-slate-800/90 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
            <div>
                <!-- Header Card -->
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-white tracking-tight">Wewenang & Fungsi Role Sistem</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pemisahan tugas pada ekosistem VOTINGYUK</p>
                </div>

                <!-- 3 Item Role -->
                <div class="space-y-3">
                    <!-- 1. Super Admin -->
                    <div class="p-3.5 bg-[#070e1c] border border-slate-800 rounded-xl">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#800020]/80 text-rose-200 border border-rose-600/30">
                                SUPER_ADMIN
                            </span>
                            <span class="text-xs font-semibold text-white">Administrator Sistem</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Mengelola seluruh akun pengguna (Super Admin, Petugas, dan Pemilih) serta memantau integritas jejak digital (Audit Log).
                        </p>
                    </div>

                    <!-- 2. Petugas -->
                    <div class="p-3.5 bg-[#070e1c] border border-slate-800 rounded-xl">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-950/80 text-blue-300 border border-blue-700/60">
                                PETUGAS
                            </span>
                            <span class="text-xs font-semibold text-white">Panitia Pemilihan Event</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Membuat dan mengelola event voting (Kandidat & Opsi), mengatur status event, serta memantau perolehan suara & ekspor laporan.
                        </p>
                    </div>

                    <!-- 3. Pemilih -->
                    <div class="p-3.5 bg-[#070e1c] border border-slate-800 rounded-xl">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white-950/80 text-white-300 border border-white-700/60">
                                PEMILIH
                            </span>
                            <span class="text-xs font-semibold text-white">Siswa & Guru</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Mengakses bilik suara melalui kode room & validasi NIS/NIP, memberikan hak suara secara langsung, bebas, rahasia, jujur, dan adil.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Ringkasan Pengguna -->
            <div class="pt-4 mt-4 border-t border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-2">
                <span class="text-slate-400">Total Pengguna Terdaftar</span>
                <span class="text-white font-semibold">
                    {{ $totalSuperAdmin + $totalPetugas + $totalVoters }} Akun
                    <span class="text-slate-400 text-[11px] font-normal">({{ $totalSuperAdmin + $totalPetugas }} Pengelola | {{ $totalVoters }} Pemilih)</span>
                </span>
            </div>
        </div>

        <!-- KANAN: STATUS & LINGKUNGAN (Sesuai Desain Gambar 1) -->
        <div class="bg-[#0c1424] border border-slate-800/90 rounded-2xl p-6 shadow-xl flex flex-col">
            <!-- Header Card -->
            <div class="mb-4">
                <h2 class="text-lg font-bold text-white tracking-tight">Status & Lingkungan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Spesifikasi infrastruktur sistem</p>
            </div>

            <!-- Daftar Info Spesifikasi (Merata Mengisi Tinggi Card) -->
            <div class="flex-1 flex flex-col justify-between text-xs py-1">
                <!-- Database Engine -->
                <div class="flex items-center justify-between py-3.5 border-b border-slate-800/60">
                    <span class="text-slate-400">Database Engine</span>
                    <div class="flex items-center gap-2 font-medium text-emerald-400 font-mono">
                        <span>{{ $systemInfo['db_engine'] }}</span>
                    </div>
                </div>

                <!-- PHP Version -->
                <div class="flex items-center justify-between py-3.5 border-b border-slate-800/60">
                    <span class="text-slate-400">PHP Version</span>
                    <span class="font-bold text-white font-mono">{{ $systemInfo['php_version'] }}</span>
                </div>

                <!-- Framework -->
                <div class="flex items-center justify-between py-3.5 border-b border-slate-800/60">
                    <span class="text-slate-400">Framework</span>
                    <div class="flex items-center gap-1.5 font-semibold">
                        <span class="text-rose-500 font-bold">Laravel</span>
                        <span class="text-white font-mono">{{ $systemInfo['laravel_version'] }}</span>
                    </div>
                </div>

                <!-- Enkripsi Suara -->
                <div class="flex items-center justify-between py-3.5 border-b border-slate-800/60">
                    <span class="text-slate-400">Enkripsi Suara</span>
                    <span class="font-mono text-white font-semibold">{{ $systemInfo['encryption'] }}</span>
                </div>

                <!-- Zona Waktu -->
                <div class="flex items-center justify-between py-3.5">
                    <span class="text-slate-400">Zona Waktu</span>
                    <span class="font-semibold text-white">{{ $systemInfo['timezone'] }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- =========================================================================
         4. LOG KEAMANAN & AKTIVITAS TERKINI (SESUAI DESAIN GAMBAR 1)
         ========================================================================= -->
    <div class="bg-[#0c1424] border border-slate-800/90 rounded-2xl p-6 shadow-xl">
        <!-- Header Tabel Log -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">Log Keamanan & Aktivitas Terkini</h2>
                <p class="text-xs text-slate-400 mt-0.5">Rekaman jejak aktivitas pengguna pada sistem</p>
            </div>
            <div class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-[#070e1c] border border-slate-800 text-xs text-slate-300 font-medium self-start sm:self-auto">
                <span>{{ $recentAuditLogs->count() }} Aktivitas Terakhir</span>
            </div>
        </div>

        <!-- Tabel Log Aktivitas -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">WAKTU</th>
                        <th class="py-3 px-4">PENGGUNA</th>
                        <th class="py-3 px-4">AKSI</th>
                        <th class="py-3 px-4">DESKRIPSI AKTIVITAS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($recentAuditLogs as $log)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <!-- Kolom Waktu -->
                            <td class="py-3.5 px-4 font-mono text-slate-300 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y  H:i') }}
                            </td>

                            <!-- Kolom Pengguna & Role -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-white">{{ $log->user_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $log->role }}</div>
                            </td>

                            <!-- Kolom Aksi (Badge Berwarna) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($log->action === 'CHANGE_EVENT_STATUS')
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-950/80 text-amber-400 border border-amber-800/60">
                                        CHANGE_EVENT_STATUS
                                    </span>
                                @elseif($log->action === 'CREATE_EVENT')
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-950/80 text-emerald-400 border border-emerald-800/60">
                                        CREATE_EVENT
                                    </span>
                                @elseif($log->action === 'UPDATE_EVENT')
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-sky-950/80 text-sky-400 border border-sky-800/60">
                                        UPDATE_EVENT
                                    </span>
                                @elseif($log->action === 'DELETE_EVENT')
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-950/80 text-rose-400 border border-rose-800/60">
                                        DELETE_EVENT
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ $log->action }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Deskripsi Aktivitas -->
                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">
                                Belum ada riwayat aktivitas yang tercatat pada sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
