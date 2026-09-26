<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\Voter;
use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Auto update status event jika waktu sudah berakhir
        foreach (Event::where('status', 'PUBLISHED')->get() as $ev) {
            $ev->checkAndUpdateAutoStatus();
        }

        $query = Event::with(['creator', 'candidates', 'participations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('room_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['DRAFT', 'PUBLISHED', 'CLOSED'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && in_array($request->category, ['KANDIDAT', 'OPSI'])) {
            $query->where('category', $request->category);
        }

        $events = $query->latest()->paginate(9)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:KANDIDAT,OPSI'],
            'description' => ['nullable', 'string'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
        ]);

        // Generate unique 6-character room code
        do {
            $roomCode = strtoupper(Str::random(6));
        } while (Event::where('room_code', $roomCode)->exists());

        $event = Event::create([
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'room_code' => $roomCode,
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'status' => 'DRAFT',
        ]);

        AuditLog::record(
            'CREATE_EVENT',
            "Membuat event pemilihan baru '{$event->title}' (Kode Room: {$event->room_code})"
        );

        return redirect()->route('admin.events.show', $event->id)
            ->with('success', "Event '{$event->title}' (Kode Room: {$event->room_code}) berhasil dibuat. Silakan tambahkan kandidat/opsi.");
    }

    public function show(Event $event)
    {
        $event->checkAndUpdateAutoStatus();
        $event->load(['creator', 'candidates.votes', 'participations.voter']);

        $totalDpt = Voter::where('is_active', true)->count();
        $totalVotes = $event->votes()->count();
        $totalParticipated = $event->participations()->whereNotNull('voted_at')->count();
        $turnoutRate = $totalDpt > 0 ? round(($totalParticipated / $totalDpt) * 100, 1) : 0;

        return view('admin.events.show', compact('event', 'totalDpt', 'totalVotes', 'totalParticipated', 'turnoutRate'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:KANDIDAT,OPSI'],
            'description' => ['nullable', 'string'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
        ]);

        $event->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
        ]);

        AuditLog::record(
            'UPDATE_EVENT',
            "Memperbarui data event '{$event->title}' (Kode Room: {$event->room_code})"
        );

        return redirect()->route('admin.events.show', $event->id)
            ->with('success', "Data event '{$event->title}' berhasil diperbarui.");
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED'],
        ]);

        $newStatus = $request->status;

        // Validasi jika ingin publish, pastikan minimal ada 2 kandidat/opsi
        if ($newStatus === 'PUBLISHED' && $event->candidates()->count() < 2) {
            return redirect()->back()
                ->with('error', 'Event tidak dapat di-publish karena belum memiliki minimal 2 kandidat/opsi.');
        }

        $oldStatus = $event->status;
        $event->status = $newStatus;
        $event->save();

        AuditLog::record(
            'CHANGE_EVENT_STATUS',
            "Mengubah status event '{$event->title}' dari {$oldStatus} menjadi {$newStatus}"
        );

        return redirect()->back()
            ->with('success', "Status event '{$event->title}' berhasil diubah menjadi {$newStatus}.");
    }

    public function destroy(Event $event)
    {
        $title = $event->title;
        $roomCode = $event->room_code;

        $event->delete();

        AuditLog::record(
            'DELETE_EVENT',
            "Menghapus event '{$title}' (Kode Room: {$roomCode}) beserta data kandidat & voting terkait"
        );

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$title}' ({$roomCode}) berhasil dihapus.");
    }
}
