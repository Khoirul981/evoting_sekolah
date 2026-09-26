<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\User;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto update status event jika waktu sudah berakhir
        foreach (Event::where('status', 'PUBLISHED')->get() as $event) {
            $event->checkAndUpdateAutoStatus();
        }

        $superAdminCount = User::where('role', 'SUPER_ADMIN')->count();
        $petugasCount = User::where('role', 'PETUGAS')->count();
        $totalStaff = $superAdminCount + $petugasCount;

        $siswaCount = Voter::where('type', 'SISWA')->count();
        $guruCount = Voter::where('type', 'GURU')->count();
        $totalVoters = $siswaCount + $guruCount;
        $activeVoters = Voter::where('is_active', true)->count();
        $inactiveVoters = Voter::where('is_active', false)->count();

        $totalEvents = Event::count();
        $draftEvents = Event::where('status', 'DRAFT')->count();
        $publishedEvents = Event::where('status', 'PUBLISHED')->count();
        $closedEvents = Event::where('status', 'CLOSED')->count();

        $totalVotesCast = Vote::count();

        // Recent active events
        $recentEvents = Event::with(['candidates', 'participations'])
            ->latest()
            ->take(5)
            ->get();

        // Recent audit logs
        $recentLogs = AuditLog::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'superAdminCount',
            'petugasCount',
            'totalStaff',
            'siswaCount',
            'guruCount',
            'totalVoters',
            'activeVoters',
            'inactiveVoters',
            'totalEvents',
            'draftEvents',
            'publishedEvents',
            'closedEvents',
            'totalVotesCast',
            'recentEvents',
            'recentLogs'
        ));
    }
}
