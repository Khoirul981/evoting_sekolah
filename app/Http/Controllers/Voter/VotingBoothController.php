<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VotingBoothController extends Controller
{
    /**
     * Tampilkan Bilik Suara (Voting Booth) untuk event pemilihan tertentu
     */
    public function show(string $room_code): View|RedirectResponse
    {
        $normalizedCode = strtoupper(trim($room_code));
        $event = Event::where('room_code', $normalizedCode)->first();

        if (!$event) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Kode ruangan pemilihan tidak ditemukan.');
        }

        // Cek dan sinkronkan auto status waktu jika ada jadwal
        $event->checkAndUpdateAutoStatus();

        // Validasi status publikasi event
        if (!$event->isPublished()) {
            $msg = $event->isClosed()
                ? 'Sesi bilik suara untuk event ini telah resmi ditutup.'
                : 'Sesi bilik suara untuk event ini belum dibuka oleh panitia.';

            return redirect()->route('voter.dashboard')->with('error', $msg);
        }

        // Validasi jendela waktu pemilihan
        $now = Carbon::now();
        if ($event->start_time && $now->lessThan($event->start_time)) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Sesi bilik suara belum dimulai. Jadwal mulai: ' . $event->start_time->translatedFormat('d F Y, H:i') . ' WIB.');
        }

        if ($event->end_time && $now->greaterThan($event->end_time)) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Sesi bilik suara telah berakhir pada: ' . $event->end_time->translatedFormat('d F Y, H:i') . ' WIB.');
        }

        // Validasi jumlah kandidat minimal
        if ($event->candidates()->count() < 2) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Event pemilihan ini belum memiliki daftar kandidat yang lengkap (minimal 2 kandidat).');
        }

        $voter = Auth::guard('voter')->user();

        // Cek apakah pemilih sudah pernah memilih di event ini
        $participation = EventParticipation::where('event_id', $event->id)
            ->where('voter_id', $voter->id)
            ->first();

        if ($participation && $participation->hasVoted()) {
            return redirect()->route('voter.events.success', $event->room_code)
                ->with('info', 'Anda telah menyalurkan hak suara pada event pemilihan ini.');
        }

        $candidates = $event->candidates()->orderBy('candidate_number', 'asc')->get();

        return view('voter.voting-booth', compact('event', 'candidates', 'voter'));
    }

    /**
     * Eksekusi Pencatatan Suara secara Atomik dan Menjaga Kerahasiaan (LUBER)
     */
    public function castVote(Request $request, string $room_code): RedirectResponse
    {
        $normalizedCode = strtoupper(trim($room_code));
        $event = Event::where('room_code', $normalizedCode)->first();

        if (!$event) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Kode ruangan pemilihan tidak ditemukan.');
        }

        $request->validate([
            'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
            'room_code' => ['required', 'string'],
        ], [
            'candidate_id.required' => 'Silakan tentukan kandidat pilihan Anda terlebih dahulu.',
            'candidate_id.exists' => 'Kandidat yang dipilih tidak valid dalam sistem.',
            'room_code.required' => 'Silakan masukkan kode ruangan (room code) resmi event ini untuk mengirim suara.',
        ]);

        // Verifikasi kesesuaian kode ruangan yang diinput pemilih dengan event ini
        $inputRoomCode = strtoupper(trim((string) $request->room_code));
        if ($inputRoomCode !== strtoupper(trim($event->room_code))) {
            return back()->withInput()->with('error', 'Kode ruangan yang Anda masukkan tidak sesuai dengan event pemilihan ini.');
        }

        $event->checkAndUpdateAutoStatus();

        if (!$event->isPublished()) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Sesi bilik suara tidak aktif atau telah ditutup.');
        }

        $now = Carbon::now();
        if ($event->start_time && $now->lessThan($event->start_time)) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Sesi pemilihan belum dimulai.');
        }

        if ($event->end_time && $now->greaterThan($event->end_time)) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Sesi pemilihan telah berakhir.');
        }

        // Verifikasi kepemilikan kandidat pada event ini
        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('event_id', $event->id)
            ->first();

        if (!$candidate) {
            return back()->with('error', 'Kandidat yang dipilih tidak terdaftar pada event pemilihan ini.');
        }

        $voter = Auth::guard('voter')->user();

        try {
            DB::transaction(function () use ($event, $voter, $candidate) {
                // Lock atau ambil data partisipasi pemilih
                $participation = EventParticipation::where('event_id', $event->id)
                    ->where('voter_id', $voter->id)
                    ->lockForUpdate()
                    ->first();

                if ($participation && $participation->hasVoted()) {
                    throw new \DomainException('Anda sudah memberikan suara pada event pemilihan ini.');
                }

                // 1. Catat Suara ke tabel votes (TIDAK ADA voter_id -> RAHASIA & LUBER)
                Vote::create([
                    'event_id' => $event->id,
                    'candidate_id' => $candidate->id,
                ]);

                // 2. Tandai partisipasi pemilih pada event ini (Anti Double-Voting)
                if ($participation) {
                    $participation->update([
                        'voted_at' => Carbon::now(),
                    ]);
                } else {
                    EventParticipation::create([
                        'event_id' => $event->id,
                        'voter_id' => $voter->id,
                        'voted_at' => Carbon::now(),
                    ]);
                }

                // 3. Catat Audit Log Sistem (hanya mencatat voter telah berpartisipasi, bukan pilihannya)
                AuditLog::record(
                    'vote.cast',
                    "Pemilih {$voter->name} ({$voter->identifier}) telah menyalurkan hak suara secara sah pada event [{$event->room_code}] {$event->title}."
                );
            });
        } catch (\DomainException $e) {
            return redirect()->route('voter.events.success', $event->room_code)
                ->with('warning', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses surat suara. Silakan coba kembali atau hubungi panitia.');
        }

        return redirect()->route('voter.events.success', $event->room_code);
    }

    /**
     * Tampilkan Halaman Tanda Terima Partisipasi Sukses
     */
    public function success(string $room_code): View|RedirectResponse
    {
        $normalizedCode = strtoupper(trim($room_code));
        $event = Event::where('room_code', $normalizedCode)->first();

        if (!$event) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Kode ruangan pemilihan tidak ditemukan.');
        }

        $voter = Auth::guard('voter')->user();
        $participation = EventParticipation::where('event_id', $event->id)
            ->where('voter_id', $voter->id)
            ->first();

        // Jika belum vote, arahkan kembali ke bilik suara
        if (!$participation || !$participation->hasVoted()) {
            return redirect()->route('voter.events.vote', $event->room_code);
        }

        return view('voter.success', compact('event', 'voter', 'participation'));
    }
}
