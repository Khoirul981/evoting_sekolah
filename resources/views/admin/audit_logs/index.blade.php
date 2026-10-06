@extends('layouts.admin')

@section('title', 'Audit Log & Security')
@section('page_title', 'Audit Log')

@section('content')
<div class="space-y-6">

    <!-- =========================================================================
         HEADER HALAMAN AUDIT LOG
         ========================================================================= -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                <span>Audit Log & Security (Jejak Digital)</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                Log sistem otomatis mencatat seluruh aktivitas sensitif yang dilakukan oleh Petugas dan Super Admin demi keamanan & transparansi.
            </p>
        </div>
    </div>

    <!-- =========================================================================
         TOOLBAR: PENCARIAN & FILTER ROLE
         ========================================================================= -->
    <div class="p-4 rounded-2xl bg-[#0c1424] border border-slate-800/80 shadow-sm">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex-1 flex flex-wrap items-center gap-2.5">
                <!-- Search Box -->
                <div class="relative flex-1 min-w-55">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari aktivitas / nama pengguna / IP..."
                        class="w-full pl-9 pr-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-900/30 transition-all"
                    >
                </div>

                <!-- Dropdown Filter Role -->
                <select
                    name="role"
                    class="px-3 py-2 bg-[#070e1c] border border-slate-800 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-rose-500 transition-all"
                >
                    <option value="">Semua Role</option>
                    <option value="SUPER_ADMIN" {{ request('role') === 'SUPER_ADMIN' ? 'selected' : '' }}>Super Admin</option>
                    <option value="PETUGAS" {{ request('role') === 'PETUGAS' ? 'selected' : '' }}>Petugas</option>
                    <option value="VOTER" {{ request('role') === 'VOTER' ? 'selected' : '' }}>Pemilih</option>
                </select>

                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition-all cursor-pointer"
                >
                    Cari & Filter
                </button>

                @if(request('search') || request('role'))
                    <a
                        href="{{ route('admin.audit-logs.index') }}"
                        class="px-3.5 py-2 rounded-xl bg-rose-950/60 hover:bg-rose-900/80 text-rose-300 text-xs font-medium border border-rose-800/60 transition-all"
                    >
                        Reset
                    </a>
                @endif
            </div>

            <!-- Info Counter Hasil -->
            <div class="text-[11px] text-slate-400 font-medium whitespace-nowrap">
                Menampilkan <span class="text-white font-bold">{{ $logs->total() }}</span> rekaman jejak digital
            </div>
        </form>
    </div>

    <!-- =========================================================================
         TABEL AUDIT LOG
         ========================================================================= -->
    <div class="rounded-2xl bg-[#0c1424] border border-slate-800/80 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800/80 bg-[#070e1c]/60 text-slate-400 font-semibold tracking-wider uppercase text-[10px]">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Aksi</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3 px-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <!-- Kolom Waktu -->
                            <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap font-mono text-[11px]">
                                <div class="flex items-center gap-1.5">
                                    <span>{{ $log->created_at ? $log->created_at->translatedFormat('d/m/Y, H:i:s') : '-' }} WIB</span>
                                </div>
                            </td>

                            <!-- Kolom Pengguna -->
                            <td class="py-3.5 px-4 font-semibold text-white whitespace-nowrap">
                                {{ $log->user_name }}
                            </td>

                            <!-- Kolom Role -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($log->role === 'SUPER_ADMIN')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#800020]/80 text-rose-200 border border-rose-600/30">
                                        SUPER_ADMIN
                                    </span>
                                @elseif($log->role === 'PETUGAS')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-950/80 text-cyan-300 border border-cyan-700/60">
                                        PETUGAS
                                    </span>
                                @elseif($log->role === 'VOTER')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-white border border-white/30">
                                        PEMILIH
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ $log->role }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border bg-slate-800 text-slate-300 border-slate-700">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Kolom Deskripsi Aktivitas -->
                            <td class="py-3.5 px-4 text-slate-200 min-w-75">
                                {{ $log->description }}
                            </td>

                            <!-- Kolom IP Address -->
                            <td class="py-3.5 px-4 text-slate-400 font-mono text-[11px] whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#070e1c] border border-slate-800 text-slate-400">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <p class="font-semibold text-xs text-slate-400">Belum ada riwayat jejak digital (Audit Log) ditemukan.</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Semua aktivitas administratif akan otomatis tercatat di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($logs->hasPages())
            <div class="p-4 border-t border-slate-800/80">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
