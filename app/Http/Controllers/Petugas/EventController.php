<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Tampilkan daftar seluruh event pemilihan yang dikelola
     */
    public function index(Request $request): View
    {
        // 1. Cek & sinkronisasi otomatis status jadwal event yang aktif
        $allEvents = Event::whereNotNull('start_time')->orWhereNotNull('end_time')->get();
        foreach ($allEvents as $ev) {
            $ev->checkAndUpdateAutoStatus();
        }

        // 2. Query data dengan eager loading & filter
        $query = Event::with(['creator'])
            ->withCount(['candidates', 'participations', 'votes']);

        // Filter Pencarian (Judul / Kode Ruang)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('room_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status') && in_array($request->status, ['DRAFT', 'PUBLISHED', 'CLOSED'])) {
            $query->where('status', $request->status);
        }

        // Filter Kategori
        if ($request->filled('category') && in_array($request->category, ['KANDIDAT', 'OPSI'])) {
            $query->where('category', $request->category);
        }

        $events = $query->latest('id')->paginate(9)->withQueryString();

        // 3. Ringkasan Statistik Metrik Event
        $metrics = [
            'total' => Event::count(),
            'published' => Event::where('status', 'PUBLISHED')->count(),
            'draft' => Event::where('status', 'DRAFT')->count(),
            'closed' => Event::where('status', 'CLOSED')->count(),
        ];

        return view('petugas.events.index', compact('events', 'metrics'));
    }

    /**
     * Simpan event pemilihan baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalisasi format room_code jika diisi manual
        if ($request->filled('room_code')) {
            $request->merge(['room_code' => strtoupper(trim($request->room_code))]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:KANDIDAT,OPSI'],
            'room_code' => ['nullable', 'string', 'min:4', 'max:10', 'alpha_dash', 'unique:events,room_code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after:start_time'],
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED'],
        ], [
            'title.required' => 'Judul event wajib diisi.',
            'category.required' => 'Kategori pemilihan wajib dipilih.',
            'room_code.min' => 'Kode ruang minimal 4 karakter.',
            'room_code.max' => 'Kode ruang maksimal 10 karakter.',
            'room_code.unique' => 'Kode ruang tersebut sudah digunakan pada event lain.',
            'room_code.alpha_dash' => 'Kode ruang hanya boleh berisi huruf, angka, tanda strip, atau garis bawah.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'status.required' => 'Status event wajib ditentukan.',
        ]);

        // Jika kode ruang kosong, buat kode acak 6 karakter unik
        if (empty($validated['room_code'])) {
            do {
                $generatedCode = strtoupper(Str::random(6));
            } while (Event::where('room_code', $generatedCode)->exists());
            $validated['room_code'] = $generatedCode;
        }

        $validated['created_by'] = Auth::guard('web')->id();

        // Event baru yang belum memiliki kandidat wajib berstatus DRAFT sampai minimal 2 kandidat ditambahkan
        if ($validated['status'] === 'PUBLISHED') {
            $validated['status'] = 'DRAFT';
        }

        $event = Event::create($validated);

        // Catat jejak audit log
        AuditLog::record(
            'CREATE_EVENT',
            "Membuat event pemilihan baru '{$event->title}' dengan kode ruang {$event->room_code} (Kategori: {$event->category}, Status: {$event->status})."
        );

        // Langsung arahkan ke halaman kelola kandidat/opsi agar petugas segera menambahkan pilihan (minimal 2)
        return redirect()->route('petugas.candidates.index', $event)
            ->with('success', "Event '{$event->title}' (Kode: {$event->room_code}) berhasil dibuat! Silakan tambahkan kandidat/opsi pemilihan di bawah ini (minimal 2 pilihan).");
    }

    /**
     * Perbarui data event pemilihan
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        if ($request->filled('room_code')) {
            $request->merge(['room_code' => strtoupper(trim($request->room_code))]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:KANDIDAT,OPSI'],
            'room_code' => ['required', 'string', 'min:4', 'max:10', 'alpha_dash', 'unique:events,room_code,' . $event->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after:start_time'],
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED'],
        ], [
            'title.required' => 'Judul event wajib diisi.',
            'category.required' => 'Kategori pemilihan wajib dipilih.',
            'room_code.required' => 'Kode ruang wajib diisi.',
            'room_code.min' => 'Kode ruang minimal 4 karakter.',
            'room_code.max' => 'Kode ruang maksimal 10 karakter.',
            'room_code.unique' => 'Kode ruang tersebut sudah digunakan pada event lain.',
            'room_code.alpha_dash' => 'Kode ruang hanya boleh berisi huruf, angka, tanda strip, atau garis bawah.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'status.required' => 'Status event wajib ditentukan.',
        ]);

        // Validasi: Event tidak boleh dipublikasikan jika kandidat kurang dari 2
        if ($validated['status'] === 'PUBLISHED' && $event->candidates()->count() < 2) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Status tidak dapat diubah ke PUBLISHED! Minimal harus memiliki 2 kandidat/opsi terdaftar (saat ini: {$event->candidates()->count()}).");
        }

        $event->update($validated);

        // Catat jejak audit log
        AuditLog::record(
            'UPDATE_EVENT',
            "Memperbarui data event '{$event->title}' (Kode Ruang: {$event->room_code}, Status: {$event->status})."
        );

        return redirect()->route('petugas.events.index')
            ->with('success', "Data event '{$event->title}' berhasil diperbarui.");
    }

    /**
     * Ubah status event secara cepat (DRAFT, PUBLISHED, CLOSED)
     */
    public function updateStatus(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED'],
        ]);

        // Validasi: Event tidak boleh dipublikasikan jika kandidat kurang dari 2
        if ($validated['status'] === 'PUBLISHED' && $event->candidates()->count() < 2) {
            return redirect()->back()
                ->with('error', "Event tidak dapat dipublikasikan! Minimal harus memiliki 2 kandidat/opsi terdaftar (saat ini: {$event->candidates()->count()}).");
        }

        $oldStatus = $event->status;
        $event->update(['status' => $validated['status']]);

        // Catat jejak audit log
        AuditLog::record(
            'CHANGE_EVENT_STATUS',
            "Mengubah status event '{$event->title}' dari {$oldStatus} menjadi {$event->status}."
        );

        return redirect()->back()
            ->with('success', "Status event '{$event->title}' berhasil diubah menjadi {$event->status}.");
    }

    /**
     * Hapus event pemilihan dan seluruh data terkait
     */
    public function destroy(Event $event): RedirectResponse
    {
        $title = $event->title;
        $roomCode = $event->room_code;

        $event->delete();

        // Catat jejak audit log
        AuditLog::record(
            'DELETE_EVENT',
            "Menghapus event pemilihan '{$title}' (Kode Ruang: {$roomCode})."
        );

        return redirect()->route('petugas.events.index')
            ->with('success', "Event '{$title}' berhasil dihapus.");
    }
}
