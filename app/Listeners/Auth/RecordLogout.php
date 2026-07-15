<?php

namespace App\Listeners\Auth;

use App\Services\LoginHistoryService;
use Illuminate\Auth\Events\Logout;

/**
 * RecordLogout — tandai logout_at pada baris riwayat yang sesuai.
 *
 * Auto skip Logout yang terjadi akibat challenge 2FA
 * (LoginController:71 Auth::logout() sebelum redirect ke /2fa/challenge).
 */
class RecordLogout
{
    private LoginHistoryService $history;

    public function __construct(LoginHistoryService $history)
    {
        $this->history = $history;
    }

    public function handle(Logout $event): void
    {
        $this->history->recordLogout($event->user);
    }
}