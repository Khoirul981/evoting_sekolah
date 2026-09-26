@extends('layouts.admin')

@section('title', 'Audit Log & Jejak Digital')
@section('page_title', 'Audit Log & Jejak Digital Sistem')
@section('page_description', 'Pencatatan riwayat setiap aksi administratif dan perubahan data demi transparansi & integritas pemilu.')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-slate-900 border border-slate-800 rounded-xl p-4">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="relative flex-1 min-w-[220px]">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari aktivitas, nama pengguna, aksi, IP..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Role Filter -->
            <select name="role" onchange="this.form.submit()" class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Role</option>
                <option value="SUPER_ADMIN" {{ request('role') === 'SUPER_ADMIN' ? 'selected' : '' }}>Super Admin</option>
                <option value="PETUGAS" {{ request('role') === 'PETUGAS' ? 'selected' : '' }}>Petugas</option>
            </select>

            <!-- Action Filter -->
            <select name="action" onchange="this.form.submit()" class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Tipe Aksi</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'action']))
                <a href="{{ route('admin.audit-logs.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-200 text-xs">Reset</a>
            @endif
        </form>

        <div>
            <a
                href="{{ route('admin.audit-logs.export') }}"
                class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors border border-slate-700"
            >
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Ekspor Log (CSV)</span>
            </a>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4 whitespace-nowrap">Waktu Kejadian</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Pengguna & Role</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Aksi Sistem</th>
                        <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-4 whitespace-nowrap font-mono">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap font-mono">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-white whitespace-nowrap">
                                <div>{{ $log->user_name }}</div>
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-[10px] {{ $log->role === 'SUPER_ADMIN' ? 'bg-indigo-950 text-indigo-400 border border-indigo-800' : 'bg-sky-950 text-sky-400 border border-sky-800' }}">
                                    {{ $log->role }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-slate-950 border border-slate-800 rounded font-mono text-[11px] font-bold text-indigo-300">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-200">
                                {{ $log->description }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-400 whitespace-nowrap">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">
                                Tidak ada data log aktivitas yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
