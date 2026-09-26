@extends('layouts.admin')

@section('title', 'Quick Count & Hasil - ' . $event->title)
@section('page_title', 'Perhitungan Suara (Quick Count)')
@section('page_description', 'Pantau perolehan suara kandidat dan laju partisipasi pemilih secara real-time.')

@section('content')
<div class="space-y-6">

    <!-- Header Event Info & Auto Refresh Controls -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
            <div>
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <span class="px-2.5 py-1 bg-slate-950 border border-slate-800 rounded font-mono font-bold text-xs text-indigo-300">
                        Room #{{ $event->room_code }}
                    </span>
                    @if($event->status === 'PUBLISHED')
                        <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 inline-flex items-center gap-1.5" id="eventStatusBadge">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            PUBLISHED (Sedang Berjalan)
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-slate-800 text-slate-400 border border-slate-700" id="eventStatusBadge">
                            {{ $event->status }}
                        </span>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-white tracking-tight">{{ $event->title }}</h2>
            </div>

            <!-- Auto refresh switcher -->
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    id="btnRefresh"
                    onclick="fetchLiveStats()"
                    class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors border border-slate-700 cursor-pointer"
                >
                    <svg id="refreshIcon" class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Refresh Data</span>
                </button>

                <div class="flex items-center gap-2 px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs">
                    <input type="checkbox" id="toggleAuto" checked class="w-4 h-4 rounded text-indigo-600 bg-slate-900 border-slate-700 focus:ring-indigo-500">
                    <label for="toggleAuto" class="text-slate-300 font-medium cursor-pointer">Auto (3s)</label>
                </div>

                <a href="{{ route('admin.events.show', $event->id) }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium border border-slate-700">
                    &larr; Detail Event
                </a>
            </div>
        </div>

        <!-- 3 Big Metric Highlights -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-5">
            <div class="p-4 rounded-lg bg-slate-950 border border-slate-800">
                <div class="text-[11px] font-semibold text-slate-400 uppercase">Total Suara Masuk</div>
                <div class="text-2xl font-bold text-white mt-1" id="statTotalVotes">{{ $totalVotes }} <span class="text-xs font-normal text-slate-500">Suara</span></div>
            </div>

            <div class="p-4 rounded-lg bg-slate-950 border border-slate-800">
                <div class="text-[11px] font-semibold text-slate-400 uppercase">Tingkat Partisipasi DPT</div>
                <div class="text-2xl font-bold text-emerald-400 mt-1" id="statTurnout">{{ $turnoutRate }}%</div>
                <div class="text-xs text-slate-500 mt-0.5" id="statTurnoutDetail">{{ $totalParticipated }} dari {{ $totalDpt }} pemilih aktif</div>
            </div>

            <div class="p-4 rounded-lg bg-slate-950 border border-slate-800">
                <div class="text-[11px] font-semibold text-slate-400 uppercase">Pembaruan Terakhir</div>
                <div class="text-sm font-mono font-bold text-slate-300 mt-1" id="statLastUpdate">{{ now()->format('d/m/Y H:i:s') }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Asas Langsung, Umum, Bebas, Rahasia</div>
            </div>
        </div>
    </div>

    <!-- Candidate Real-time Standings -->
    <div class="space-y-4">
        <h3 class="text-base font-bold text-white">Perolehan Suara Kandidat</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="candidateListContainer">
            @foreach($candidates as $c)
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm space-y-3" id="candidate-card-{{ $c->id }}">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-slate-950 border border-slate-800 flex items-center justify-center font-bold text-lg text-indigo-400 font-mono overflow-hidden">
                                @if($c->photo)
                                    <img src="{{ $c->photo_url }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ $c->candidate_number }}
                                @endif
                            </div>
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-950 text-indigo-400 border border-indigo-800 font-mono">
                                    NOMOR {{ $c->candidate_number }}
                                </span>
                                <h4 class="font-bold text-sm text-white mt-0.5">{{ $c->name }}</h4>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xl font-bold text-white font-mono" id="cand-votes-{{ $c->id }}">{{ $c->votes_count }} <span class="text-xs font-normal text-slate-400">Suara</span></div>
                            <div class="text-xs font-bold text-emerald-400" id="cand-pct-{{ $c->id }}">{{ $c->percentage }}%</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-950 rounded-full h-3 overflow-hidden border border-slate-800">
                        <div
                            id="cand-bar-{{ $c->id }}"
                            class="bg-indigo-600 h-3 rounded-full transition-all duration-300"
                            style="width: {{ $c->percentage }}%"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Participation Log (Anonymized) -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="mb-4">
            <h3 class="text-sm font-bold text-white">Log Aktivitas Bilik Suara (Daftar Hadir Pemilih)</h3>
            <p class="text-xs text-slate-400">Catatan pemilih yang telah memberikan hak suaranya (Pilihan kandidat tetap terenkripsi & anonim demi asas kerahasiaan).</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-3">Waktu Pencoblosan</th>
                        <th class="py-3 px-3">Nomor Identitas</th>
                        <th class="py-3 px-3">Nama Pemilih</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Status Suara</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60" id="participationTableBody">
                    @forelse($recentParticipations as $p)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3 font-mono text-slate-400 whitespace-nowrap">
                                {{ $p->voted_at ? $p->voted_at->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-indigo-300">
                                {{ $p->voter ? $p->voter->identifier : '-' }}
                            </td>
                            <td class="py-3 px-3 font-medium text-white">
                                {{ $p->voter ? $p->voter->name : 'Anonim' }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $p->voter && $p->voter->type === 'SISWA' ? 'bg-indigo-950 text-indigo-400 border border-indigo-800' : 'bg-emerald-950 text-emerald-400 border border-emerald-800' }}">
                                    {{ $p->voter ? $p->voter->type : '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-950 text-emerald-400 border border-emerald-800">
                                    Suara Sah & Masuk &check;
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">
                                Belum ada suara yang masuk di bilik pemilihan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let isFetching = false;

    async function fetchLiveStats() {
        if (isFetching) return;
        isFetching = true;
        const refreshIcon = document.getElementById('refreshIcon');
        if (refreshIcon) refreshIcon.classList.add('animate-spin');

        try {
            const res = await fetch("{{ route('admin.events.results.data', $event->id) }}");
            if (res.ok) {
                const data = await res.json();

                // Update total votes & turnout
                document.getElementById('statTotalVotes').innerHTML = `${data.total_votes} <span class="text-xs font-normal text-slate-500">Suara</span>`;
                document.getElementById('statTurnout').textContent = `${data.turnout_rate}%`;
                document.getElementById('statTurnoutDetail').textContent = `${data.total_participated} dari ${data.total_dpt} pemilih aktif`;
                document.getElementById('statLastUpdate').textContent = data.timestamp;

                // Update candidate bars and numbers
                data.candidates.forEach(c => {
                    const votesEl = document.getElementById(`cand-votes-${c.id}`);
                    const pctEl = document.getElementById(`cand-pct-${c.id}`);
                    const barEl = document.getElementById(`cand-bar-${c.id}`);

                    if (votesEl) votesEl.innerHTML = `${c.votes_count} <span class="text-xs font-normal text-slate-400">Suara</span>`;
                    if (pctEl) pctEl.textContent = `${c.percentage}%`;
                    if (barEl) barEl.style.width = `${c.percentage}%`;
                });
            }
        } catch (err) {
            console.error('Error fetching live stats:', err);
        } finally {
            if (refreshIcon) refreshIcon.classList.remove('animate-spin');
            isFetching = false;
        }
    }

    // Interval Auto-Refresh every 3 seconds
    setInterval(() => {
        const toggleAuto = document.getElementById('toggleAuto');
        if (toggleAuto && toggleAuto.checked) {
            fetchLiveStats();
        }
    }, 3000);
</script>
@endpush
@endsection
