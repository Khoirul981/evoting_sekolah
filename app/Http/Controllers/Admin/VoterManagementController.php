<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class VoterManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Voter::query();

        // Search by NIS/NIP or Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('identifier', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by Type (SISWA, GURU)
        if ($request->filled('type') && in_array($request->type, ['SISWA', 'GURU'])) {
            $query->where('type', $request->type);
        }

        // Filter by Status (active / inactive)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $voters = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        $totalCount = Voter::count();
        $siswaCount = Voter::where('type', 'SISWA')->count();
        $guruCount = Voter::where('type', 'GURU')->count();
        $activeCount = Voter::where('is_active', true)->count();

        return view('admin.voters.index', compact(
            'voters',
            'totalCount',
            'siswaCount',
            'guruCount',
            'activeCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:50', 'unique:voters,identifier'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:SISWA,GURU'],
        ]);

        $voter = Voter::create([
            'identifier' => trim($validated['identifier']),
            'name' => trim($validated['name']),
            'type' => $validated['type'],
            'is_active' => true,
        ]);

        AuditLog::record(
            'CREATE_VOTER',
            "Menambahkan Pemilih (DPT) baru: [{$voter->identifier}] {$voter->name} ({$voter->type})"
        );

        return redirect()->route('admin.voters.index')
            ->with('success', "Pemilih '{$voter->name}' ({$voter->identifier}) berhasil didaftarkan ke DPT.");
    }

    public function update(Request $request, Voter $voter)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:50', Rule::unique('voters')->ignore($voter->id)],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:SISWA,GURU'],
        ]);

        $oldIdentifier = $voter->identifier;
        $voter->update([
            'identifier' => trim($validated['identifier']),
            'name' => trim($validated['name']),
            'type' => $validated['type'],
        ]);

        AuditLog::record(
            'UPDATE_VOTER',
            "Memperbarui data Pemilih: [{$oldIdentifier} -> {$voter->identifier}] {$voter->name} ({$voter->type})"
        );

        return redirect()->route('admin.voters.index')
            ->with('success', "Data Pemilih '{$voter->name}' berhasil diperbarui.");
    }

    public function toggleStatus(Voter $voter)
    {
        $voter->is_active = !$voter->is_active;
        $voter->save();

        $statusText = $voter->is_active ? 'diaktifkan' : 'dinonaktifkan';

        AuditLog::record(
            'TOGGLE_VOTER_STATUS',
            "Mengubah status Pemilih [{$voter->identifier}] {$voter->name} menjadi {$statusText}"
        );

        return redirect()->back()
            ->with('success', "Status hak suara '{$voter->name}' ({$voter->identifier}) berhasil {$statusText}.");
    }

    public function destroy(Voter $voter)
    {
        $name = $voter->name;
        $identifier = $voter->identifier;

        $voter->delete();

        AuditLog::record(
            'DELETE_VOTER',
            "Menghapus Pemilih [{$identifier}] {$name} dari DPT"
        );

        return redirect()->route('admin.voters.index')
            ->with('success', "Pemilih '{$name}' ({$identifier}) berhasil dihapus dari DPT.");
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // max 5MB
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return redirect()->back()->with('error', 'Gagal membuka berkas CSV.');
        }

        $imported = 0;
        $updated = 0;
        $rowNumber = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNumber++;
            // Skip header if present
            if ($rowNumber === 1 && (strtolower(trim($row[0])) === 'nis' || strtolower(trim($row[0])) === 'nip' || strtolower(trim($row[0])) === 'identifier')) {
                continue;
            }

            if (empty($row[0]) || empty($row[1])) {
                continue;
            }

            $identifier = trim($row[0]);
            $name = trim($row[1]);
            $type = isset($row[2]) && strtoupper(trim($row[2])) === 'GURU' ? 'GURU' : 'SISWA';

            $voter = Voter::where('identifier', $identifier)->first();
            if ($voter) {
                $voter->update([
                    'name' => $name,
                    'type' => $type,
                ]);
                $updated++;
            } else {
                Voter::create([
                    'identifier' => $identifier,
                    'name' => $name,
                    'type' => $type,
                    'is_active' => true,
                ]);
                $imported++;
            }
        }

        fclose($handle);

        AuditLog::record(
            'IMPORT_VOTERS',
            "Melakukan impor massal DPT via CSV: {$imported} data baru ditambahkan, {$updated} diperbarui"
        );

        return redirect()->route('admin.voters.index')
            ->with('success', "Impor data DPT berhasil: {$imported} data baru ditambahkan, {$updated} data diperbarui.");
    }

    public function exportCsv()
    {
        $voters = Voter::orderBy('type', 'asc')->orderBy('name', 'asc')->get();

        $csvFileName = 'DPT_Voters_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$csvFileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($voters) {
            $handle = fopen('php://output', 'w');
            // Header baris CSV
            fputcsv($handle, ['identifier', 'name', 'type', 'is_active', 'created_at']);

            foreach ($voters as $v) {
                fputcsv($handle, [
                    $v->identifier,
                    $v->name,
                    $v->type,
                    $v->is_active ? '1' : '0',
                    $v->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        };

        AuditLog::record('EXPORT_VOTERS', "Mengunduh file ekspor DPT ({$voters->count()} pemilih)");

        return response()->stream($callback, 200, $headers);
    }
}
