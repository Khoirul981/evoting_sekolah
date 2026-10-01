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
    public static function record(string $action, string $description, mixed $user = null): self
    {
        if (!$user) {
            if (Auth::guard('voter')->check()) {
                $voter = Auth::guard('voter')->user();
                return self::create([
                    'user_id' => null,
                    'user_name' => $voter->name . ' (' . $voter->identifier . ')',
                    'role' => 'VOTER',
                    'action' => $action,
                    'description' => $description,
                    'ip_address' => Request::ip() ?? '127.0.0.1',
                ]);
            }
            $user = Auth::guard('web')->user();
        }

        return self::create([
            'user_id' => ($user instanceof User) ? $user->id : null,
            'user_name' => is_object($user) ? ($user->name ?? 'Sistem / Anonim') : 'Sistem / Anonim',
            'role' => is_object($user) ? ($user->role ?? 'SYSTEM') : 'SYSTEM',
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip() ?? '127.0.0.1',
        ]);
    }
}
