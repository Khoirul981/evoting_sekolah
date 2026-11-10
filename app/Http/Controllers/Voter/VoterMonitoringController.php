<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VoterMonitoringController extends Controller
{
    /**
     * Tampilkan halaman live perolehan suara (quick count) untuk pemilih
     * yang telah menyalurkan hak suaranya pada event terkait.
     */
    public function show(string $room_code): View|RedirectResponse
    {
        $normalizedCode = strtoupper(trim($room_code));
        $event = Event::where('room_code', $normalizedCode)->first();

        if (!$event) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Kode ruangan event pemilihan tidak ditemukan.');
        }

        $voter = Auth::guard('voter')->user();

        // Ambil data partisipasi pemilih jika sudah pernah memilih pada event ini
        $participation = EventParticipation::where('event_id', $event->id)
            ->where('voter_id', $voter->id)
            ->first();

        // Sinkronisasi status otomatis berdasarkan jadwal
        $event->checkAndUpdateAutoStatus();
        $event->load('creator');

        $totalVoters = Voter::count();
        $totalVotes = $event->votes()->count();
        $participationRate = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0;

        $candidates = $event->candidates()
            ->withCount('votes')
            ->orderBy('candidate_number', 'asc')
            ->get()
            ->map(function ($candidate) use ($totalVotes) {
                $candidate->percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                return $candidate;
            });

        // Deteksi SERI: cek apakah ada kandidat dengan suara tertinggi yang sama
        $maxVotes = $candidates->max('votes_count');
        $leadingCandidates = $candidates->where('votes_count', $maxVotes)->values();
        $isTied = $totalVotes > 0 && $maxVotes > 0 && $leadingCandidates->count() > 1;
        $leadingCandidate = ($totalVotes > 0 && !$isTied) ? $leadingCandidates->first() : null;

        $recentParticipations = $event->participations()
            ->with('voter')
            ->whereNotNull('voted_at')
            ->latest('voted_at')
            ->paginate(15);

        return view('voter.monitoring', compact(
            'event',
            'voter',
            'participation',
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
     * Endpoint API JSON untuk polling realtime suara oleh pemilih (setiap 3-5 detik)
     */
    public function data(string $room_code): JsonResponse
    {
        $normalizedCode = strtoupper(trim($room_code));
        $event = Event::where('room_code', $normalizedCode)->first();

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event pemilihan tidak ditemukan.',
            ], 404);
        }

        $voter = Auth::guard('voter')->user();

        $event->checkAndUpdateAutoStatus();

        $totalVoters = Voter::count();
        $totalVotes = $event->votes()->count();
        $participationRate = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0;

        $candidates = $event->candidates()
            ->withCount('votes')
            ->orderBy('candidate_number', 'asc')
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
        $isTied = $totalVotes > 0 && $maxVotes > 0 && $leadingCandidates->count() > 1;

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
