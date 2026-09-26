<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Voter extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'name',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipation::class, 'voter_id');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participations')
            ->withPivot('voted_at')
            ->withTimestamps();
    }
}
