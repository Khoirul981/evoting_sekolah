<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    /**
     * Tampilkan halaman live monitoring dan rekapitulasi suara
     */
    public function show(Event $event): View
    {
        // Authorization: Pastikan petugas hanya bisa akses event miliknya sendiri
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        $event->checkAndUpdateAutoStatus();

        $totalVoters = Voter::count();
        $totalVotes = $event->votes()->count();
        $participationRate = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0;

        $candidates = $event->candidates()
            ->withCount('votes')
            ->get()
            ->map(function ($candidate) use ($totalVotes) {
                $candidate->percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                return $candidate;
            });

        // Deteksi SERI: cek apakah ada kandidat dengan suara tertinggi yang sama
        $maxVotes = $candidates->max('votes_count');
        $leadingCandidates = $candidates->where('votes_count', $maxVotes)->values();
        $isTied = $totalVotes > 0 && $leadingCandidates->count() > 1;
        $leadingCandidate = $totalVotes > 0 ? $leadingCandidates->first() : null;

        $recentParticipations = $event->participations()
            ->with('voter')
            ->whereNotNull('voted_at')
            ->latest('voted_at')
            ->paginate(15);

        return view('petugas.monitoring.show', compact(
            'event',
            'totalVoters',
            'totalVotes',
            'participationRate',
            'candidates',
            'leadingCandidate',
            'leadingCandidates',
            'isTied',
            'recentParticipations'
        ));
    }

    /**
     * Endpoint API JSON untuk polling realtime suara (setiap 3-5 detik)
     */
    public function data(Event $event): JsonResponse
    {
        $event->checkAndUpdateAutoStatus();

        $totalVoters = Voter::count();
        $totalVotes = $event->votes()->count();
        $participationRate = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0;

        $candidates = $event->candidates()
            ->withCount('votes')
            ->get()
            ->map(function ($candidate) use ($totalVotes) {
                return [
                    'id' => $candidate->id,
                    'candidate_number' => $candidate->candidate_number,
                    'name' => $candidate->name,
                    'photo_url' => $candidate->photo ? asset('storage/' . $candidate->photo) : null,
                    'votes_count' => (int) $candidate->votes_count,
                    'percentage' => $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0,
                ];
            });

        // Deteksi SERI untuk API
        $maxVotes = $candidates->max('votes_count');
        $leadingCandidates = $candidates->where('votes_count', $maxVotes)->values();
        $isTied = $totalVotes > 0 && $leadingCandidates->count() > 1;

        $recentVoters = $event->participations()
            ->with('voter')
            ->whereNotNull('voted_at')
            ->latest('voted_at')
            ->take(15)
            ->get()
            ->map(function ($part) {
                return [
                    'voter_name' => $part->voter->name ?? 'Pemilih',
                    'identifier' => $part->voter->identifier ?? '-',
                    'type' => $part->voter->type ?? 'SISWA',
                    'voted_at' => $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i:s') . ' WIB' : '-',
                    'voted_at_human' => $part->voted_at ? $part->voted_at->diffForHumans() : '-',
                ];
            });

        return response()->json([
            'status' => 'success',
            'event_status' => $event->status,
            'total_voters' => $totalVoters,
            'total_votes' => $totalVotes,
            'participation_rate' => $participationRate,
            'candidates' => $candidates,
            'is_tied' => $isTied,
            'leading_candidates' => $leadingCandidates,
            'recent_voters' => $recentVoters,
            'server_time' => Carbon::now()->translatedFormat('H:i:s') . ' WIB',
        ]);
    }
}
