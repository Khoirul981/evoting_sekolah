<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VoterDashboardController extends Controller
{
    /**
     * Tampilan Lobi Utama Pemilih (Daftar Event & Riwayat Partisipasi)
     */
    public function index(): View
    {
        $voter = Auth::guard('voter')->user();

        // Otomatis sinkronkan status event berdasarkan jadwal waktu
        Event::whereIn('status', ['DRAFT', 'PUBLISHED'])->each(function (Event $event) {
            $event->checkAndUpdateAutoStatus();
        });

        $now = Carbon::now();

        // Ambil event dari seluruh petugas yang sedang berstatus PUBLISHED dan sedang berlangsung
        $availableEvents = Event::where('status', 'PUBLISHED')
            ->where(function ($query) use ($now) {
                $query->whereNull('start_time')->orWhere('start_time', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_time')->orWhere('end_time', '>=', $now);
            })
            ->with(['creator'])
            ->withCount('candidates')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil riwayat partisipasi pemilih saat ini
        $participations = EventParticipation::where('voter_id', $voter->id)
            ->with('event')
            ->whereNotNull('voted_at')
            ->orderBy('voted_at', 'desc')
            ->get();

        $participatedEventIds = $participations->pluck('event_id')->all();

        return view('voter.dashboard', compact('voter', 'availableEvents', 'participations', 'participatedEventIds'));
    }

    /**
     * Validasi Kode Ruangan dan Masuk ke Bilik Suara
     */
    public function joinRoom(Request $request): RedirectResponse
    {
        $request->validate([
            'room_code' => ['required', 'string', 'max:20'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
        ], [
            'room_code.required' => 'Kode Ruangan wajib diisi.',
        ]);

        $voter = Auth::guard('voter')->user();
        $roomCode = strtoupper(trim($request->input('room_code')));
        $eventId = $request->input('event_id');

        // Jika voter memasukkan kode dari kartu event tertentu (spesifik event_id)
        if ($eventId) {
            $targetEvent = Event::find($eventId);
            if ($targetEvent && strtoupper(trim($targetEvent->room_code)) !== $roomCode) {
                return back()->withErrors([
                    'room_code_' . $eventId => "Kode Room salah untuk event '{$targetEvent->title}'. Pastikan meminta kode resmi ke panitia/petugas.",
                    'room_code' => "Kode Room tidak sesuai untuk event '{$targetEvent->title}'.",
                ])->with('error', "Kode Room tidak sesuai untuk event '{$targetEvent->title}'. Silakan minta kode room yang benar kepada petugas pemilihan.")
                  ->with('failed_event_id', $eventId)
                  ->onlyInput('room_code');
            }
        }

        $event = Event::where('room_code', $roomCode)->first();

        if (!$event) {
            return back()->withErrors([
                'room_code' => "Kode Ruangan '{$roomCode}' tidak ditemukan. Pastikan kode yang diberikan petugas sudah benar.",
            ])->with('error', "Kode Ruangan '{$roomCode}' tidak ditemukan. Pastikan kode yang diberikan petugas sudah benar.")
              ->onlyInput('room_code');
        }

        // Cek update otomatis jika ada batas waktu
        $event->checkAndUpdateAutoStatus();

        // Cek status event
        if ($event->isDraft()) {
            return back()->withErrors([
                'room_code' => "Event '{$event->title}' belum dibuka untuk voting (masih berstatus DRAFT).",
            ])->with('error', "Event '{$event->title}' belum dibuka untuk voting (masih berstatus DRAFT).")
              ->onlyInput('room_code');
        }

        if ($event->isClosed()) {
            return back()->withErrors([
                'room_code' => "Event '{$event->title}' telah ditutup / waktu pemilihan telah berakhir.",
            ])->with('error', "Event '{$event->title}' telah ditutup / waktu pemilihan telah berakhir.")
              ->onlyInput('room_code');
        }

        $now = Carbon::now();
        if ($event->start_time && $now->lessThan($event->start_time)) {
            return back()->withErrors([
                'room_code' => "Waktu pemilihan '{$event->title}' belum dimulai (Jadwal: " . $event->start_time->format('d/m/Y H:i') . " WIB).",
            ])->with('error', "Waktu pemilihan belum dimulai.")
              ->onlyInput('room_code');
        }

        if ($event->end_time && $now->greaterThan($event->end_time)) {
            return back()->withErrors([
                'room_code' => "Waktu pemilihan '{$event->title}' telah berakhir pada " . $event->end_time->format('d/m/Y H:i') . " WIB.",
            ])->with('error', "Waktu pemilihan telah berakhir.")
              ->onlyInput('room_code');
        }

        // Cek apakah voter sudah pernah memilih pada event ini
        $hasVoted = EventParticipation::where('event_id', $event->id)
            ->where('voter_id', $voter->id)
            ->whereNotNull('voted_at')
            ->exists();

        if ($hasVoted) {
            return back()->withErrors([
                'room_code' => "Anda sudah menggunakan hak suara pada event '{$event->title}'. Hak suara hanya dapat digunakan 1 kali.",
            ])->with('error', "Anda sudah menggunakan hak suara pada event '{$event->title}'.")
              ->onlyInput('room_code');
        }

        // Cek apakah event memiliki minimal 2 kandidat
        if ($event->candidates()->count() < 2) {
            return back()->withErrors([
                'room_code' => "Event '{$event->title}' belum siap (belum memiliki minimal 2 pilihan). Hubungi petugas pemilihan eventnya.",
            ])->with('error', "Event belum memiliki minimal 2 kandidat/opsi.")
              ->onlyInput('room_code');
        }

        return redirect()->route('voter.events.vote', $event->room_code);
    }
}
