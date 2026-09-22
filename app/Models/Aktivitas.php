<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    protected $table = 'aktivitas';
    public $timestamps = false;

    protected $fillable = ['id_user', 'aksi', 'detail', 'terminal', 'ip_address', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Helper log aktivitas serbaguna
     */
    public static function log(string $aksi, string $detail, ?int $userId = null, ?string $terminal = null, ?string $ip = null): ?self
    {
        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            $resolvedUserId = $userId ?? ($user ? $user->id : 1);
            $resolvedIp = $ip ?? (request() ? request()->ip() : '127.0.0.1') ?? '127.0.0.1';
            $resolvedTerminal = $terminal ?? 'TERM-01';

            return self::create([
                'id_user' => $resolvedUserId,
                'aksi' => $aksi,
                'detail' => $detail,
                'terminal' => $resolvedTerminal,
                'ip_address' => $resolvedIp,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal mencatat aktivitas [{$aksi}]: " . $e->getMessage());
            return null;
        }
    }
}

