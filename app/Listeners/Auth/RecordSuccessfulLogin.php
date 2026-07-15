<?php

namespace App\Listeners\Auth;

use App\Services\LoginHistoryService;
use Illuminate\Auth\Events\Login;

/**
 * RecordSuccessfulLogin — catat login berhasil FINAL.
 *
 * Auto skip Login event ke-1 (intermediate) saat alur 2FA:
 * password benar tapi OTP belum selesai. Login final terjadi
 * di Auth::loginUsingId() setelah OTP valid (TwoFactorController::verify).
 */
class RecordSuccessfulLogin
{
    private LoginHistoryService $history;

    public function __construct(LoginHistoryService $history)
    {
        $this->history = $history;
    }

    public function handle(Login $event): void
    {
        $this->history->recordSuccess($event->user);
    }
}