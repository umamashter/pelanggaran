<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Services\LoginHistoryService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

/**
 * UpsertActiveSession — pastikan baris sessions.user_id terisi pada login FINAL.
 *
 * Laravel otomatis isi user_id saat Auth::login, namun listener ini jadi
 * safety net (mis. saat login via Auth::loginUsingId di verify()).
 * Idempotent: update saja, tidak insert.
 *
 * Berjalan di event Login FINAL (urutan: RecordSuccessfulLogin → DetectNewDevice → ini).
 */
class UpsertActiveSession
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (!$user || !($user instanceof User)) return;

        if (LoginHistoryService::isIntermediateLogin($user)) {
            return;
        }

        $sessionId = LoginHistoryService::safeSessionId();
        if (!$sessionId) return;

        // Pastikan user_id & last_activity terisi di tabel sessions
        DB::table('sessions')
            ->where('id', $sessionId)
            ->update([
                'user_id'       => $user->id,
                'last_activity' => now()->getTimestamp(),
            ]);
    }
}