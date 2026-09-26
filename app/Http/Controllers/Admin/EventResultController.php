<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Voter;
use Illuminate\Http\Request;

class EventResultController extends Controller
{
    public function show(Event $event)
    {
        $event->checkAndUpdateAutoStatus();
        $event->load(['candidates.votes', 'participations.voter']);

        $totalDpt = Voter::where('is_active', true)->count();
        $totalVotes = $event->votes()->count();
        $totalParticipated = $event->participations()->whereNotNull('voted_at')->count();
        $turnoutRate = $totalDpt > 0 ? round(($totalParticipated / $totalDpt) * 100, 1) : 0;

        // Calculate candidate percentage
        $candidates = $event->candidates->map(function ($candidate) use ($totalVotes) {
            $votesCount = $candidate->votes->count();
            $percentage = $totalVotes > 0 ? round(($votesCount / $totalVotes) * 100, 1) : 0;
            $candidate->votes_count = $votesCount;
            $candidate->percentage = $percentage;
            return $candidate;
        })->sortByDesc('votes_count');

        // Recent voter participations (showing voter name and type, but NOT exposing which candidate they voted for)
        $recentParticipations = $event->participations()
            ->whereNotNull('voted_at')
            ->with('voter')
            ->latest('voted_at')
            ->take(10)
            ->get();

        return view('admin.events.results', compact(
            'event',
            'candidates',
            'totalDpt',
            'totalVotes',
            'totalParticipated',
            'turnoutRate',
            'recentParticipations'
        ));
    }

    public function apiData(Event $event)
    {
        $event->checkAndUpdateAutoStatus();
        $event->load(['candidates.votes', 'participations']);

        $totalDpt = Voter::where('is_active', true)->count();
        $totalVotes = $event->votes()->count();
        $totalParticipated = $event->participations()->whereNotNull('voted_at')->count();
        $turnoutRate = $totalDpt > 0 ? round(($totalParticipated / $totalDpt) * 100, 1) : 0;

        $candidatesData = $event->candidates->map(function ($c) use ($totalVotes) {
            $count = $c->votes->count();
            return [
                'id' => $c->id,
                'candidate_number' => $c->candidate_number,
                'name' => $c->name,
                'votes_count' => $count,
                'percentage' => $totalVotes > 0 ? round(($count / $totalVotes) * 100, 1) : 0,
            ];
        })->sortByDesc('votes_count')->values();

        return response()->json([
            'status' => $event->status,
            'total_dpt' => $totalDpt,
            'total_votes' => $totalVotes,
            'total_participated' => $totalParticipated,
            'turnout_rate' => $turnoutRate,
            'candidates' => $candidatesData,
            'timestamp' => now()->format('d/m/Y H:i:s'),
        ]);
    }
}
