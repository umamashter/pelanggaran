<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * SecurityNotifier — pengirim notifikasi keamanan dengan dedup Cache.
 *
 * Tujuan (TDD §6 & §10):
 *  - Mencegah spam: notif tipe sama untuk fingerprint/IP yang sama
 *    tidak dikirim ulang dalam 24 jam (dedup key di Cache).
 *  - Satu-satunya pintu keluar notifikasi → listener/controller cukup
 *    panggil notify() tanpa peduli dedup.
 *
 * Dapat dipanggil langsung (untuk event non-Login) atau dari listener
 * SendSecurityNotification (untuk event Login).
 */
class SecurityNotifier
{
    /** Dedup TTL: 24 jam (TDD §10.5) */
    public const DEDUP_TTL = 86400;

    /**
     * Kirim SecurityAlert ke user, kecuali sudah dikirim dalam window dedup.
     *
     * @param  User   $user
     * @param  string $type     Salah satu: new_device|new_ip|2fa_enabled|2fa_disabled|recovery_used|password_changed
     * @param  array  $payload  Data tambahan (browser, os, ip, fingerprint, time, ...)
     * @return bool   true bila dikirim, false bila di-skip dedup
     */
    public function notify(User $user, string $type, array $payload = []): bool
    {
        // Tambahan waktu untuk tampilan
        $payload['time'] = $payload['time'] ?? now()->toDateTimeString();

        // Dedup key: per-user, per-type, per-key identifier (fingerprint/ip/empty)
        $dedupKey = $this->dedupKey($user->id, $type, $payload);
        if ($dedupKey && Cache::has($dedupKey)) {
            Log::info('SecurityAlert skipped (dedup)', [
                'user' => $user->email, 'type' => $type, 'dedup_key' => $dedupKey,
            ]);
            return false;
        }

        // Spam guard: jangan notif bila email tidak valid (mis. user dummy)
        if (!$user->email) {
            Log::info('SecurityAlert skipped (no email)', ['user_id' => $user->id, 'type' => $type]);
            return false;
        }

        try {
            $user->notify(new \App\Notifications\SecurityAlert($type, $payload));
        } catch (\Throwable $e) {
            Log::warning('SecurityAlert failed', [
                'user' => $user->email, 'type' => $type, 'err' => $e->getMessage(),
            ]);
            return false;
        }

        if ($dedupKey) {
            Cache::put($dedupKey, true, self::DEDUP_TTL);
        }

        Log::info('SecurityAlert sent', ['user' => $user->email, 'type' => $type]);
        return true;
    }

    /**
     * Banggil dedup key berdasar tipe notif.
     *  - new_device  : berdasar fingerprint
     *  - new_ip      : berdasar IP
     *  - event-based : tanpa dedup (akan selalu kirim satu kali per event)
     */
    protected function dedupKey(int $userId, string $type, array $payload): ?string
    {
        switch ($type) {
            case 'new_device':
                $fp = $payload['fingerprint'] ?? null;
                return $fp ? "notif|{$userId}|new_device|{$fp}" : null;
            case 'new_ip':
                $ip = $payload['ip'] ?? null;
                return $ip ? "notif|{$userId}|new_ip|{$ip}" : null;
            default:
                // 2fa_enabled/2fa_disabled/recovery_used/password_changed = event-based, sekali per event
                return null;
        }
    }

    /**
     * Deteksi apakah IP saat ini belum pernah dipakai user sebelumnya.
     * Bila ya, kirim notifikasi new_ip + update history.is_new_ip.
     */
    public function detectNewIp(User $user, ?LoginHistory $currentHistory = null): ?bool
    {
        $request = request();
        if (!$request) return null;

        $ip = $request->ip();
        $ipHistoryExists = LoginHistory::where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->where('login_status', 'success')
            ->exists();

        $isNewIp = !$ipHistoryExists;

        if ($isNewIp && $currentHistory) {
            $currentHistory->update(['is_new_ip' => true]);

            // Ambil parsed UA untuk payload
            $parsed = LoginHistoryService::parseUserAgent($request->userAgent());

            $this->notify($user, 'new_ip', [
                'ip'      => $ip,
                'browser' => $parsed['browser'] . ' · ' . $parsed['os'],
                'time'    => now()->toDateTimeString(),
            ]);
        }

        return $isNewIp;
    }
}