<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'category',
        'room_code',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class)->orderBy('candidate_number', 'asc');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(Voter::class, 'event_participations')
            ->withPivot('voted_at')
            ->withTimestamps();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'DRAFT';
    }

    public function isPublished(): bool
    {
        return $this->status === 'PUBLISHED';
    }

    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }

    /**
     * Otomatis cek dan perbarui status event jika start_time / end_time terpenuhi
     */
    public function checkAndUpdateAutoStatus(): void
    {
        $now = Carbon::now();

        // Jika DRAFT dan memiliki start_time yang sudah lewat -> PUBLISHED
        if ($this->status === 'DRAFT' && $this->start_time && $now->greaterThanOrEqualTo($this->start_time)) {
            // Cek apakah sudah lewat end_time juga
            if ($this->end_time && $now->greaterThanOrEqualTo($this->end_time)) {
                $this->status = 'CLOSED';
            } else {
                $this->status = 'PUBLISHED';
            }
            $this->save();
        }
        // Jika PUBLISHED dan memiliki end_time yang sudah lewat -> CLOSED
        elseif ($this->status === 'PUBLISHED' && $this->end_time && $now->greaterThanOrEqualTo($this->end_time)) {
            $this->status = 'CLOSED';
            $this->save();
        }
    }
}
