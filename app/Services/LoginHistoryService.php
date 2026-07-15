<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

/**
 * LoginHistoryService — pencatat riwayat login & logout.
 *
 * Mencegah double-record (TDD §10):
 *  - Login intermediate (password benar, 2FA pending) TIDAK dicatat sebagai final.
 *  - Logout akibat challenge 2FA TIDAK dicatat sebagai logout sesungguhnya.
 *
 * Dipanggil oleh listener Auth event, BUKAN dari controller (controller tetap tipis).
 */
class LoginHistoryService
{
    /**
     * Ambil session ID secara aman (null bila session store belum siap,
     * mis. di CLI/queue/testing tanpa session middleware).
     */
    public static function safeSessionId(): ?string
    {
        try {
            $request = request();
            if (!$request) return null;
            $session = $request->session();
            return $session->getId() ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Deteksi: apakah Login event ini intermediate (password benar, OTP belum selesai)?
     * True → skip pencatatan final (user akan login kembali di verify() setelah OTP).
     */
    public static function isIntermediateLogin(User $user): bool
    {
        if (!$user->google2fa_secret) return false;
        try {
            return !session('2fa:user_id');           // absent = pre-challenge login
        } catch (\Throwable $e) {
            return false; // no session store → assume not intermediate (CLI/testing)
        }
    }

    /**
     * Deteksi: apakah Logout event ini akibat challenge 2FA (bukan logout nyata)?
     * True → skip.
     */
    public static function isChallengeLogout(User $user): bool
    {
        if (!$user->google2fa_secret) return false;
        try {
            return !session('2fa_passed');            // belum lolos OTP = baru password benar
        } catch (\Throwable $e) {
            return false; // no session store → assume not challenge (CLI/testing)
        }
    }

    /**
     * Parse User-Agent menjadi browser/os/device/device_kind konsisten.
     * Dipakai juga Fase 2 (device fingerprint) — satu sumber parser.
     */
    public static function parseUserAgent(string $userAgent = null): array
    {
        $ua = $userAgent ?? request()->userAgent() ?? '';
        if ($ua === '') {
            return [
                'browser'    => 'Unknown',
                'os'         => 'Unknown',
                'device'     => 'Desktop',
                'device_kind'=> 'desktop',
            ];
        }

        try {
            $agent = new Agent();
            $agent->setUserAgent($ua);

            $browser = $agent->browser() ?: 'Unknown';
            $os      = $agent->platform() ?: 'Unknown';
            $device  = $agent->device() ?: null;

            if ($agent->isDesktop()) {
                $kind = 'desktop';
                if (!$device) $device = 'Desktop';
            } elseif ($agent->isTablet()) {
                $kind = 'tablet';
                if (!$device) $device = 'Tablet';
            } elseif ($agent->isMobile()) {
                $kind = 'mobile';
                if (!$device) $device = 'Mobile';
            } else {
                $kind = 'desktop';
                if (!$device) $device = 'Desktop';
            }

            return [
                'browser'     => mb_substr($browser, 0, 50),
                'os'          => mb_substr($os, 0, 50),
                'device'      => mb_substr($device, 0, 50),
                'device_kind' => $kind,
            ];
        } catch (\Throwable $e) {
            return [
                'browser'     => 'Unknown',
                'os'          => 'Unknown',
                'device'      => 'Desktop',
                'device_kind' => 'desktop',
            ];
        }
    }

    /**
     * Catat login BERHASIL (final — terjadi pada Auth\Events\Login yang bukan intermediate).
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function recordSuccess($user): ?LoginHistory
    {
        if (!$user) return null;

        // Guard anti intermediate: jangan catat login pre-OTP sebagai final
        if ($user instanceof User && self::isIntermediateLogin($user)) {
            return null;
        }

        $request = request();
        $ua = $request ? $request->userAgent() : null;
        $parsed = self::parseUserAgent($ua);

        $otpStatus = $user->google2fa_secret ? 'success' : 'not_required';

        // session() helper throw di CLI/queue/testing tanpa session middleware
        $traceId = null;
        try {
            $traceId = session('login_trace_id') ?: null;
        } catch (\Throwable $e) { /* no session store */ }

        return LoginHistory::create([
            'user_id'        => $user->id ?? null,
            'trace_id'       => $traceId,
            'session_id'     => self::safeSessionId(),
            'login_at'       => now(),
            'ip_address'     => $request ? $request->ip() : '127.0.0.1',
            'user_agent'     => $ua,
            'browser'        => $parsed['browser'],
            'os'             => $parsed['os'],
            'device'         => $parsed['device'],
            'device_kind'    => $parsed['device_kind'],
            'login_status'   => 'success',
            'otp_status'     => $otpStatus,
            'is_new_device'  => false,   // diisi Fase 2
            'is_new_ip'      => false,   // diisi Fase 3
            'metadata'       => null,
        ]);
    }

    /**
     * Catat login GAGAL (password salah / user tak dikenal).
     */
    public function recordFailed(Failed $event): ?LoginHistory
    {
        $request = request();
        $ua = $request ? $request->userAgent() : null;
        $parsed = self::parseUserAgent($ua);

        $attempted = $event->credentials['username']
            ?? $event->credentials['email']
            ?? null;

        $metadata = $attempted ? ['attempted' => $attempted] : null;

        return LoginHistory::create([
            'user_id'        => $event->user->id ?? null,
            'trace_id'       => null,
            'session_id'     => null,
            'login_at'       => now(),
            'ip_address'     => $request ? $request->ip() : '127.0.0.1',
            'user_agent'     => $ua,
            'browser'        => $parsed['browser'],
            'os'             => $parsed['os'],
            'device'         => $parsed['device'],
            'device_kind'    => $parsed['device_kind'],
            'login_status'   => 'failed',
            'otp_status'     => null,
            'is_new_device'  => false,
            'is_new_ip'      => false,
            'metadata'       => $metadata,
        ]);
    }

    /**
     * Tandai waktu logout pada baris riwayat yang sesuai.
     * Mapping berdasarkan session_id (cukup unik). Bila tidak ketemu, lewati.
     */
    public function recordLogout($user): void
    {
        if (!$user) return;
        if ($user instanceof User && self::isChallengeLogout($user)) {
            return; // logout akibat challenge 2FA, bukan sesungguhnya
        }

        $request = request();
        if (!$request) return;

        $sessionId = self::safeSessionId();
        if (!$sessionId) return;

        LoginHistory::where('session_id', $sessionId)
            ->whereNull('logout_at')
            ->latest('id')
            ->limit(1)
            ->update(['logout_at' => now()]);
    }
}