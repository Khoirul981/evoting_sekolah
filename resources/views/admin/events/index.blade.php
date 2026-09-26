@extends('layouts.admin')

@section('title', 'Kelola Event Pemilihan')
@section('page_title', 'Kelola Event Pemilihan')
@section('page_description', 'Buat event voting baru, kelola kandidat, atur jadwal pemilihan, dan pantau hasil perhitungan.')

@section('content')
<div class="space-y-6">

    <!-- Filters & Create Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-xl p-4">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul event, kode room..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Draft</option>
                <option value="PUBLISHED" {{ request('status') === 'PUBLISHED' ? 'selected' : '' }}>Published (Aktif)</option>
                <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>Closed (Selesai)</option>
            </select>

            <select name="category" onchange="this.form.submit()" class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                <option value="KANDIDAT" {{ request('category') === 'KANDIDAT' ? 'selected' : '' }}>Kandidat Paslon</option>
                <option value="OPSI" {{ request('category') === 'OPSI' ? 'selected' : '' }}>Polling Opsi</option>
            </select>

            <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'category']))
                <a href="{{ route('admin.events.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-200 text-xs">Reset</a>
            @endif
        </form>

        <!-- Tambah Event Button -->
        <a
            href="{{ route('admin.events.create') }}"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors whitespace-nowrap"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Event Baru</span>
        </a>
    </div>

    <!-- Event Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($events as $ev)
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-colors">
                <div>
                    <!-- Top Status & Code -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 bg-slate-950 border border-slate-800 rounded font-mono font-bold text-xs text-indigo-300">
                            #{{ $ev->room_code }}
                        </span>

                        <div>
                            @if($ev->status === 'PUBLISHED')
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    PUBLISHED
                                </span>
                            @elseif($ev->status === 'DRAFT')
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-amber-950 text-amber-400 border border-amber-800">
                                    DRAFT
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                    CLOSED
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Title & Description -->
                    <h3 class="font-bold text-base text-white leading-snug mb-1">
                        {{ $ev->title }}
                    </h3>
                    <p class="text-xs text-slate-400 line-clamp-2 mb-4">
                        {{ $ev->description ?: 'Tidak ada deskripsi tambahan untuk event ini.' }}
                    </p>

                    <!-- Meta Details -->
                    <div class="space-y-1.5 text-xs text-slate-400 border-t border-slate-800/80 pt-3">
                        <div class="flex justify-between">
                            <span>Kategori:</span>
                            <span class="text-slate-200 font-medium">{{ $ev->category === 'KANDIDAT' ? 'Kandidat Paslon' : 'Polling Opsi' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jumlah Pilihan:</span>
                            <span class="text-indigo-400 font-semibold">{{ $ev->candidates->count() }} Pilihan</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pembuat:</span>
                            <span class="text-slate-300">{{ $ev->creator ? $ev->creator->name : 'Sistem' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between gap-2">
                    <a
                        href="{{ route('admin.events.show', $ev->id) }}"
                        class="flex-1 py-2 text-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-colors"
                    >
                        Kelola Event
                    </a>

                    <a
                        href="{{ route('admin.events.results', $ev->id) }}"
                        class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700"
                        title="Lihat Quick Count"
                    >
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-slate-900 border border-slate-800 rounded-xl p-12 text-center text-slate-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <div class="text-sm font-semibold text-slate-300">Belum ada event pemilihan</div>
                <p class="text-xs text-slate-500 mt-1">Silakan klik tombol "Buat Event Baru" untuk membuat bilik suara.</p>
            </div>
        @endforelse
    </div>

    @if($events->hasPages())
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
            {{ $events->links() }}
        </div>
    @endif

</div>
@endsection
