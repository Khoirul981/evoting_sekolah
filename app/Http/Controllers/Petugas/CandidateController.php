<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CandidateController extends Controller
{
    /**
     * Tampilkan daftar kandidat / opsi untuk event tertentu
     */
    public function index(Event $event): View
    {
        // Authorization: Pastikan petugas hanya bisa akses event miliknya sendiri
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        $event->load(['candidates' => function ($q) {
            $q->orderBy('candidate_number', 'asc');
        }]);

        $candidates = $event->candidates;
        $candidatesCount = $candidates->count();
        $nextNumber = ($candidates->max('candidate_number') ?? 0) + 1;
        $hasVotes = $event->votes()->exists();

        return view('petugas.candidates.index', compact('event', 'candidates', 'candidatesCount', 'nextNumber', 'hasVotes'));
    }

    /**
     * Simpan kandidat atau opsi baru untuk event
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates', 'candidate_number')->where('event_id', $event->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'vision' => ['nullable', 'string', 'max:4000'],
            'mission' => ['nullable', 'string', 'max:6000'],
            'description' => ['nullable', 'string', 'max:4000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'candidate_number.required' => 'Nomor urut wajib diisi.',
            'candidate_number.integer' => 'Nomor urut harus berupa angka.',
            'candidate_number.min' => 'Nomor urut minimal 1.',
            'candidate_number.unique' => "Nomor urut {$request->candidate_number} sudah digunakan oleh kandidat/opsi lain pada event ini.",
            'name.required' => $event->category === 'KANDIDAT' ? 'Nama kandidat / paslon wajib diisi.' : 'Label / nama opsi pilihan wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar valid.',
            'photo.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Upload Foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates', 'public');
        }

        $candidate = $event->candidates()->create([
            'candidate_number' => $validated['candidate_number'],
            'name' => $validated['name'],
            'vision' => $validated['vision'] ?? null,
            'mission' => $validated['mission'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo' => $photoPath,
        ]);

        // Catat jejak audit log
        AuditLog::record(
            'CREATE_CANDIDATE',
            "Menambahkan kandidat/opsi '{$candidate->name}' (No. Urut #{$candidate->candidate_number}) pada event '{$event->title}'."
        );

        $typeLabel = $event->category === 'KANDIDAT' ? 'Kandidat' : 'Opsi';

        return redirect()->route('petugas.candidates.index', $event)
            ->with('success', "{$typeLabel} '{$candidate->name}' (No. Urut #{$candidate->candidate_number}) berhasil ditambahkan.");
    }

    /**
     * Perbarui data kandidat atau opsi
     */
    public function update(Request $request, Event $event, Candidate $candidate): RedirectResponse
    {
        if ($candidate->event_id !== $event->id) {
            abort(404, 'Kandidat tidak ditemukan pada event ini.');
        }

        $validated = $request->validate([
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates', 'candidate_number')
                    ->where('event_id', $event->id)
                    ->ignore($candidate->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'vision' => ['nullable', 'string', 'max:4000'],
            'mission' => ['nullable', 'string', 'max:6000'],
            'description' => ['nullable', 'string', 'max:4000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'candidate_number.required' => 'Nomor urut wajib diisi.',
            'candidate_number.integer' => 'Nomor urut harus berupa angka.',
            'candidate_number.min' => 'Nomor urut minimal 1.',
            'candidate_number.unique' => "Nomor urut {$request->candidate_number} sudah digunakan oleh kandidat/opsi lain pada event ini.",
            'name.required' => $event->category === 'KANDIDAT' ? 'Nama kandidat / paslon wajib diisi.' : 'Label / nama opsi pilihan wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar valid.',
            'photo.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Upload & Ganti Foto jika ada file baru
        if ($request->hasFile('photo')) {
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $validated['photo'] = $request->file('photo')->store('candidates', 'public');
        } else {
            unset($validated['photo']);
        }

        $candidate->update($validated);

        // Catat jejak audit log
        AuditLog::record(
            'UPDATE_CANDIDATE',
            "Memperbarui data kandidat/opsi '{$candidate->name}' (No. Urut #{$candidate->candidate_number}) pada event '{$event->title}'."
        );

        return redirect()->route('petugas.candidates.index', $event)
            ->with('success', "Data '{$candidate->name}' berhasil diperbarui.");
    }

    /**
     * Hapus kandidat atau opsi
     */
    public function destroy(Event $event, Candidate $candidate): RedirectResponse
    {
        if ($candidate->event_id !== $event->id) {
            abort(404, 'Kandidat tidak ditemukan pada event ini.');
        }

        // Cek jika event sudah memiliki suara masuk
        if ($candidate->votes()->exists()) {
            return redirect()->back()->with('error', "Kandidat/opsi '{$candidate->name}' tidak dapat dihapus karena sudah memiliki data suara pemilih.");
        }

        // Hapus file foto dari storage
        if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $name = $candidate->name;
        $number = $candidate->candidate_number;

        $candidate->delete();

        // Catat jejak audit log
        AuditLog::record(
            'DELETE_CANDIDATE',
            "Menghapus kandidat/opsi '{$name}' (No. Urut #{$number}) dari event '{$event->title}'."
        );

        return redirect()->route('petugas.candidates.index', $event)
            ->with('success', "Kandidat/opsi '{$name}' (No. Urut #{$number}) berhasil dihapus.");
    }
}
