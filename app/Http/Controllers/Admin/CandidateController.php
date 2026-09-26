<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates')->where(function ($q) use ($event) {
                    return $q->where('event_id', $event->id);
                })
            ],
            'name' => ['required', 'string', 'max:255'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

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

        AuditLog::record(
            'CREATE_CANDIDATE',
            "Menambahkan kandidat nomor urut {$candidate->candidate_number} '{$candidate->name}' pada event '{$event->title}'"
        );

        return redirect()->route('admin.events.show', $event->id)
            ->with('success', "Kandidat nomor urut {$candidate->candidate_number} ({$candidate->name}) berhasil ditambahkan.");
    }

    public function update(Request $request, Event $event, Candidate $candidate)
    {
        $validated = $request->validate([
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates')->where(function ($q) use ($event) {
                    return $q->where('event_id', $event->id);
                })->ignore($candidate->id)
            ],
            'name' => ['required', 'string', 'max:255'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $candidate->photo = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->candidate_number = $validated['candidate_number'];
        $candidate->name = $validated['name'];
        $candidate->vision = $validated['vision'] ?? null;
        $candidate->mission = $validated['mission'] ?? null;
        $candidate->description = $validated['description'] ?? null;
        $candidate->save();

        AuditLog::record(
            'UPDATE_CANDIDATE',
            "Memperbarui data kandidat nomor {$candidate->candidate_number} '{$candidate->name}' pada event '{$event->title}'"
        );

        return redirect()->route('admin.events.show', $event->id)
            ->with('success', "Data kandidat nomor {$candidate->candidate_number} berhasil diperbarui.");
    }

    public function destroy(Event $event, Candidate $candidate)
    {
        $number = $candidate->candidate_number;
        $name = $candidate->name;

        if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        AuditLog::record(
            'DELETE_CANDIDATE',
            "Menghapus kandidat nomor urut {$number} '{$name}' dari event '{$event->title}'"
        );

        return redirect()->route('admin.events.show', $event->id)
            ->with('success', "Kandidat nomor {$number} ({$name}) berhasil dihapus.");
    }
}
