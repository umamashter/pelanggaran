<?php

namespace App\Services;

use App\Models\DeviceFingerprint;
use Illuminate\Support\Facades\DB;

/**
 * ActiveSessionService — manajemen sesi aktif di atas tabel `sessions` native.
 *
 * Tidak ada tabel `active_sessions` custom (TDD §7): pakai native `sessions`.
 * Revoke nyata (driver database, Fase 0) — hapus baris = perangkat logout.
 */
class ActiveSessionService
{
    /** Lifetime session (menit) dari config — sesi lebih lama dianggap expired */
    private int $lifetimeMinutes;

    public function __construct()
    {
        $this->lifetimeMinutes = (int) config('session.lifetime', 120);
    }

    /**
     * Daftar sesi aktif user, enrich dengan metadata device_fingerprints.
     *
     * @return array{list: array, current_session_id: string|null}
     */
    public function listForUser(int $userId): array
    {
        $threshold = now()->getTimestamp() - ($this->lifetimeMinutes * 60);

        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('last_activity', '>=', $threshold)
            ->orderByDesc('last_activity')
            ->get();

        $currentId = self::safeSessionId();

        // Enrich: parse UA + match fingerprint berdasar user_agent string
        $fingerprints = DeviceFingerprint::where('user_id', $userId)
            ->get()
            ->keyBy('user_agent');

        $list = $sessions->map(function ($s) use ($currentId, $fingerprints) {
            $parsed = LoginHistoryService::parseUserAgent($s->user_agent);
            $fp = $fingerprints->get($s->user_agent);

            return [
                'id'            => $s->id,
                'ip'            => $s->ip_address,
                'user_agent'    => $s->user_agent,
                'browser'       => $parsed['browser'],
                'os'            => $parsed['os'],
                'device'        => $parsed['device'],
                'device_kind'   => $parsed['device_kind'],
                'last_activity' => \Carbon\Carbon::createFromTimestamp($s->last_activity),
                'is_current'    => ($s->id === $currentId),
                'fingerprint_id'=> $fp->id ?? null,
                'is_trusted'   => $fp->is_trusted ?? false,
            ];
        })->values()->toArray();

        return ['list' => $list, 'current_session_id' => $currentId];
    }

    /**
     * Revoke satu sesi berdasarkan session ID (logout perangkat tertentu).
     * Tidak boleh revoke sesi saat ini lewat method ini.
     */
    public function revoke(int $userId, string $sessionId): bool
    {
        $current = self::safeSessionId();
        if ($sessionId === $current) {
            return false; // tidak boleh revoke sesi saat ini via ini
        }

        return DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    /**
     * Revoke semua sesi lain kecuali sesi saat ini.
     * Mengembalikan jumlah baris yang dihapus.
     */
    public function revokeOthers(int $userId): int
    {
        $current = self::safeSessionId();

        $query = DB::table('sessions')->where('user_id', $userId);
        if ($current) {
            $query->where('id', '!=', $current);
        }

        return $query->delete();
    }

    /**
     * Trust sebuah device_fingerprint milik user.
     */
    public function trustDevice(int $userId, int $fingerprintId): bool
    {
        return DeviceFingerprint::where('id', $fingerprintId)
            ->where('user_id', $userId)
            ->update(['is_trusted' => true]) > 0;
    }

    /**
     * Untrust sebuah device_fingerprint milik user.
     */
    public function untrustDevice(int $userId, int $fingerprintId): bool
    {
        return DeviceFingerprint::where('id', $fingerprintId)
            ->where('user_id', $userId)
            ->update(['is_trusted' => false]) > 0;
    }

    /**
     * Ambil session ID secara aman (null bila session store belum siap).
     */
    public static function safeSessionId(): ?string
    {
        try {
            $request = request();
            if (!$request) return null;
            return $request->session()->getId() ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}