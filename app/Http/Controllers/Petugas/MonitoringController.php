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
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonitoringController extends Controller
{
    /**
     * Tampilkan halaman live monitoring dan rekapitulasi suara
     */
    public function show(Event $event): View
    {
        $event->checkAndUpdateAutoStatus();

        $totalVoters = Voter::where('is_active', true)->count();
        $totalVotes = $event->votes()->count();
        $participationRate = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0;

        $candidates = $event->candidates()
            ->withCount('votes')
            ->get()
            ->map(function ($candidate) use ($totalVotes) {
                $candidate->percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                return $candidate;
            });

        $leadingCandidate = $totalVotes > 0 ? $candidates->sortByDesc('votes_count')->first() : null;

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
            'recentParticipations'
        ));
    }

    /**
     * Endpoint API JSON untuk polling realtime suara (setiap 3-5 detik)
     */
    public function data(Event $event): JsonResponse
    {
        $event->checkAndUpdateAutoStatus();

        $totalVoters = Voter::where('is_active', true)->count();
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
            'recent_voters' => $recentVoters,
            'server_time' => Carbon::now()->translatedFormat('H:i:s') . ' WIB',
        ]);
    }

    /**
     * Ekspor Rekapitulasi Suara ke File CSV (Kompatibel Microsoft Excel & Spreadsheet)
     */
    public function exportCsv(Event $event): StreamedResponse
    {
        // Catat jejak audit log
        AuditLog::record(
            'EXPORT_REKAPITULASI',
            "Mengekspor rekapitulasi perolehan suara event '{$event->title}' (Kode Ruang: {$event->room_code})."
        );

        $totalVoters = Voter::where('is_active', true)->count();
        $totalVotes = $event->votes()->count();
        $participationRate = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 2) : 0;

        $filename = 'rekapitulasi_' . Str::slug($event->title) . '_' . $event->room_code . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($event, $totalVoters, $totalVotes, $participationRate) {
            $file = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM agar Excel dapat membuka karakter dengan benar
            fputs($file, "\xEF\xBB\xBF");

            // Bagian 1: Header Dokumen & Identitas Event
            fputcsv($file, ['REKAPITULASI HASIL PEMILIHAN DIGITAL - ' . config('app.name', 'VOTINGYUK')]);
            fputcsv($file, ['Waktu Unduh Data', Carbon::now()->translatedFormat('l, d F Y H:i:s') . ' WIB']);
            fputcsv($file, []);
            fputcsv($file, ['--- INFORMASI EVENT PEMILIHAN ---']);
            fputcsv($file, ['Judul Event', $event->title]);
            fputcsv($file, ['Kode Ruang (Room Code)', $event->room_code]);
            fputcsv($file, ['Kategori Pemilihan', $event->category]);
            fputcsv($file, ['Status Event', $event->status]);
            fputcsv($file, ['Waktu Mulai', $event->start_time ? $event->start_time->translatedFormat('d M Y H:i') . ' WIB' : 'Manual / Langsung']);
            fputcsv($file, ['Waktu Selesai', $event->end_time ? $event->end_time->translatedFormat('d M Y H:i') . ' WIB' : 'Ditutup Manual']);
            fputcsv($file, []);

            // Bagian 2: Statistik Partisipasi Pemilih
            fputcsv($file, ['--- STATISTIK PARTISIPASI PEMILIH ---']);
            fputcsv($file, ['Total DPT Terdaftar (Aktif)', $totalVoters]);
            fputcsv($file, ['Total Suara Masuk (Sah)', $totalVotes]);
            fputcsv($file, ['Tingkat Partisipasi Kehadiran (%)', $participationRate . '%']);
            fputcsv($file, []);

            // Bagian 3: Hasil Perolehan Suara Kandidat / Opsi
            $labelType = $event->category === 'KANDIDAT' ? 'KANDIDAT / PASLON' : 'OPSI PEMILIHAN';
            fputcsv($file, ["--- HASIL PEROLEHAN SUARA {$labelType} ---"]);
            fputcsv($file, ['No. Urut', 'Nama Pilihan', 'Total Perolehan Suara', 'Persentase Suara (%)']);

            $candidates = $event->candidates()->withCount('votes')->get();
            foreach ($candidates as $candidate) {
                $pct = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 2) : 0;
                fputcsv($file, [
                    $candidate->candidate_number,
                    $candidate->name,
                    $candidate->votes_count,
                    $pct . '%',
                ]);
            }
            fputcsv($file, []);

            // Bagian 4: Log Kehadiran Pemilih (LUBER JURDIL - Pilihan Tetap Dirahasiakan)
            fputcsv($file, ['--- LOG AKTIVITAS PEMILIH (ASAS LUBER & JURDIL) ---']);
            fputcsv($file, ['No', 'Nama Pemilih', 'NIS / NIP', 'Tipe Pemilih', 'Waktu Memberikan Suara']);

            $participations = $event->participations()
                ->with('voter')
                ->whereNotNull('voted_at')
                ->latest('voted_at')
                ->get();

            $no = 1;
            foreach ($participations as $part) {
                fputcsv($file, [
                    $no++,
                    $part->voter->name ?? '-',
                    $part->voter->identifier ?? '-',
                    $part->voter->type ?? 'SISWA',
                    $part->voted_at ? $part->voted_at->translatedFormat('d M Y H:i:s') . ' WIB' : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
