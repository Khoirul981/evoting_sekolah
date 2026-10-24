@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')
@section('page_title', 'Beranda')

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         1. BANNER SELAMAT DATANG & DESKRIPSI
         ========================================================================= -->
    <div class="bg-[#1b050c] border border-rose-900/40 rounded-2xl p-6 shadow-xl relative overflow-hidden">
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
    </div>

    <!-- =========================================================================
         2. CARD RINGKASAN METRIK AKUN (TEMA MERAH MARUN)
         ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Total Super Admin -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-300/80">Total Super Admin</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalSuperAdmin }}</span>
                <span class="text-xs text-rose-400 font-medium">akun</span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Hak akses penuh pengelola</p>
        </div>

        <!-- Card 2: Total Petugas -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div>
                <span class="text-xs font-semibold text-rose-300/80">Total Petugas</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalPetugas }}</span>
                <span class="text-xs text-rose-400 font-medium">akun</span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Panitia pelaksana event</p>
        </div>

        <!-- Card 3: Total Pemilih Siswa (NIS) -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div>
                <span class="text-xs font-semibold text-rose-300/80">Pemilih Siswa (NIS)</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalStudentVoters }}</span>
                <span class="text-xs text-rose-400 font-medium">siswa</span>
            </div>
            <p class="text-[11px] text-rose-300/60 mt-1">Terdaftar dengan Nomor Induk Siswa</p>
        </div>

        <!-- Card 4: Total Pemilih Guru (NIP) -->
        <div class="bg-[#180409] border border-rose-900/50 hover:border-rose-700/70 rounded-2xl p-5 shadow-lg transition-all">
            <div>
                <span class="text-xs font-semibold text-rose-300/80">Pemilih Guru (NIP)</span>
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
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-950/80 text-cyan-300 border border-cyan-700/60">
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

                            <!-- Kolom Aksi (Badge Abu-abu Konsisten) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $log->action }}
                                </span>
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
