<?php

namespace App\Listeners\Auth;

use App\Services\LoginHistoryService;
use Illuminate\Auth\Events\Failed;

/**
 * RecordFailedLogin — catat percobaan login gagal (password salah / user inexistent).
 */
class RecordFailedLogin
{
    private LoginHistoryService $history;

    public function __construct(LoginHistoryService $history)
    {
        $this->history = $history;
    }

    public function handle(Failed $event): void
    {
        $this->history->recordFailed($event);
    }
}