@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan')
@section('page_title', 'Ringkasan Sistem E-Voting')
@section('page_description', 'Statistik real-time partisipasi pemilih, distribusi DPT, dan status event pemilihan.')

@section('content')
<div class="space-y-6">

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Pengelola -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Pengelola Sistem</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-950/70 border border-indigo-800/60 text-indigo-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-white">{{ $totalStaff }} <span class="text-xs font-normal text-slate-400">Akun</span></div>
            <div class="mt-2 text-xs text-slate-400 flex items-center gap-2">
                <span class="text-indigo-400 font-medium">{{ $superAdminCount }} Super Admin</span>
                <span>•</span>
                <span class="text-sky-400 font-medium">{{ $petugasCount }} Petugas</span>
            </div>
        </div>

        <!-- Card 2: DPT Voters -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Total DPT (Pemilih)</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-950/70 border border-emerald-800/60 text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-white">{{ $totalVoters }} <span class="text-xs font-normal text-slate-400">Pemilih</span></div>
            <div class="mt-2 text-xs text-slate-400 flex items-center gap-2">
                <span class="text-emerald-400 font-medium">{{ $activeVoters }} Aktif</span>
                <span>•</span>
                <span class="text-slate-500 font-medium">{{ $inactiveVoters }} Nonaktif</span>
            </div>
        </div>

        <!-- Card 3: Total Event -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Event Pemilihan</span>
                <div class="w-8 h-8 rounded-lg bg-amber-950/70 border border-amber-800/60 text-amber-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-white">{{ $totalEvents }} <span class="text-xs font-normal text-slate-400">Event</span></div>
            <div class="mt-2 text-xs text-slate-400 flex items-center gap-2">
                <span class="text-emerald-400 font-medium">{{ $publishedEvents }} Berjalan</span>
                <span>•</span>
                <span class="text-amber-400 font-medium">{{ $draftEvents }} Draft</span>
                <span>•</span>
                <span class="text-slate-500 font-medium">{{ $closedEvents }} Selesai</span>
            </div>
        </div>

        <!-- Card 4: Total Suara -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Suara Masuk</span>
                <div class="w-8 h-8 rounded-lg bg-purple-950/70 border border-purple-800/60 text-purple-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-white">{{ $totalVotesCast }} <span class="text-xs font-normal text-slate-400">Suara</span></div>
            <div class="mt-2 text-xs text-slate-400">
                Tercatat anonim di seluruh bilik suara
            </div>
        </div>
    </div>

    <!-- DPT Breakdown & Active Events -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- DPT Breakdown Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-white mb-1">Distribusi Hak Suara (DPT)</h3>
            <p class="text-xs text-slate-400 mb-4">Komposisi Pemilih Terdaftar Berdasarkan Kategori</p>

            <div class="space-y-4">
                <!-- Siswa -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1.5">
                        <span class="text-slate-300">Siswa (NIS)</span>
                        <span class="text-white font-semibold">{{ $siswaCount }} Pemilih ({{ $totalVoters > 0 ? round(($siswaCount / $totalVoters) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-slate-950 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $totalVoters > 0 ? ($siswaCount / $totalVoters) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <!-- Guru -->
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1.5">
                        <span class="text-slate-300">Guru & Staf (NIP)</span>
                        <span class="text-white font-semibold">{{ $guruCount }} Pemilih ({{ $totalVoters > 0 ? round(($guruCount / $totalVoters) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-slate-950 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $totalVoters > 0 ? ($guruCount / $totalVoters) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800 flex justify-between text-xs text-slate-400">
                <span>Status Akun Aktif:</span>
                <span class="text-emerald-400 font-semibold">{{ $activeVoters }} dari {{ $totalVoters }}</span>
            </div>
        </div>

        <!-- Recent Events Table -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-white">Event Pemilihan Terkini</h3>
                    <p class="text-xs text-slate-400">Daftar pemilihan yang sedang berjalan dan baru dibuat</p>
                </div>
                <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-3">Judul Event</th>
                            <th class="py-3 px-3">Kode Room</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($recentEvents as $ev)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-3 font-medium text-white">
                                    {{ $ev->title }}
                                    <div class="text-[11px] text-slate-400 mt-0.5">Kategori: {{ $ev->category }} • {{ $ev->candidates->count() }} Pilihan</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-1 bg-slate-950 border border-slate-800 rounded font-mono font-bold text-indigo-300">
                                        {{ $ev->room_code }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    @if($ev->status === 'PUBLISHED')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-950 text-emerald-400 border border-emerald-800">
                                            PUBLISHED
                                        </span>
                                    @elseif($ev->status === 'DRAFT')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-950 text-amber-400 border border-amber-800">
                                            DRAFT
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-400 border border-slate-700">
                                            CLOSED
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('admin.events.show', $ev->id) }}" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded transition-colors text-[11px] font-medium border border-slate-700">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500">
                                    Belum ada data event pemilihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Audit Logs -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-white">Log Aktivitas Sistem Terkini</h3>
                <p class="text-xs text-slate-400">Jejak digital pengelola dan perubahan konfigurasi sistem</p>
            </div>
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                    Lihat Semua Log &rarr;
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-3">Waktu</th>
                        <th class="py-3 px-3">Pengguna</th>
                        <th class="py-3 px-3">Aksi</th>
                        <th class="py-3 px-3">Deskripsi</th>
                        <th class="py-3 px-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3 text-slate-400 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-3 font-medium text-white whitespace-nowrap">
                                {{ $log->user_name }}
                                <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] {{ $log->role === 'SUPER_ADMIN' ? 'bg-indigo-950 text-indigo-400 border border-indigo-800' : 'bg-sky-950 text-sky-400 border border-sky-800' }}">
                                    {{ $log->role }}
                                </span>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-bold text-slate-200">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-300">
                                {{ $log->description }}
                            </td>
                            <td class="py-3 px-3 font-mono text-slate-400 whitespace-nowrap">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">
                                Belum ada catatan aktivitas audit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
