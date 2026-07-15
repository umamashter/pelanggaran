<?php

namespace App\Listeners\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use App\Services\DeviceFingerprintService;
use App\Services\LoginHistoryService;
use App\Services\SecurityNotifier;
use Illuminate\Auth\Events\Login;

/**
 * DetectNewDevice — resolusi fingerprint + tandai history.
 *
 * Berjalan SETELAH RecordSuccessfulLogin di event Login FINAL.
 *  - Skip intermediate (Auth::logout() saat 2FA challenge) via guard LoginHistoryService.
 *  - Update baris login_histories yang baru dibuat (match session_id) → is_new_device=true
 *    bila fingerprint belum pernah dilihat user.
 *
 * Notifikasi "new_device" akan diaktifkan di Fase 3 (SecurityNotifier) — listener ini
 * hanya menyiapkan flag + tabel device_fingerprints.
 */
class DetectNewDevice
{
    private DeviceFingerprintService $fingerprints;
    private SecurityNotifier $notifier;

    public function __construct(
        DeviceFingerprintService $fingerprints,
        SecurityNotifier $notifier
    ) {
        $this->fingerprints = $fingerprints;
        $this->notifier = $notifier;
    }

    public function handle(Login $event): void
    {
        $user = $event->user;
        if (!$user || !($user instanceof User)) return;

        // Skip intermediate login (password benar, OTP pending)
        if (LoginHistoryService::isIntermediateLogin($user)) {
            return;
        }

        $request = request();
        if (!$request) return;

        // --- Device fingerprint ---
        $resolved = $this->fingerprints->resolveOrCreate($user->id, $request);
        $fingerprint = $resolved['record'];

        // Ambil baris history FINAL yang baru dibuat RecordSuccessfulLogin
        $sessionId = LoginHistoryService::safeSessionId();
        $history = null;
        if ($sessionId) {
            $history = LoginHistory::where('session_id', $sessionId)
                ->where('user_id', $user->id)
                ->where('login_status', 'success')
                ->latest('id')
                ->first();
        }

        // Tandai is_new_device + kirim notif (bila perangkat baru DAN belum trusted)
        if ($resolved['is_new']) {
            if ($history) {
                $history->update(['is_new_device' => true]);
            }

            // Notifikasi new_device — skip bila perangkat sudah trusted (TDD §9)
            if (!$fingerprint->is_trusted) {
                $this->notifier->notify($user, 'new_device', [
                    'fingerprint' => $fingerprint->fingerprint,
                    'browser'     => $fingerprint->browser,
                    'os'          => $fingerprint->os,
                    'device'      => $fingerprint->device,
                    'ip'          => $request->ip(),
                    'time'        => now()->toDateTimeString(),
                ]);
            }
        }

        // --- Deteksi IP baru (TDD §9) ---
        $this->notifier->detectNewIp($user, $history);
    }
}