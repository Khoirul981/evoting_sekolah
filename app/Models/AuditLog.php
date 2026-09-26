<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'action',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat aktivitas secara ringkas dan konsisten
     */
    public static function record(string $action, string $description, ?User $user = null): self
    {
        $user = $user ?? Auth::guard('web')->user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Sistem / Anonim',
            'role' => $user?->role ?? 'SYSTEM',
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip() ?? '127.0.0.1',
        ]);
    }
}
