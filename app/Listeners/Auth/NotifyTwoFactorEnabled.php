<?php

namespace App\Listeners\Auth;

use App\Events\TwoFactorEnabled;
use App\Models\AccountActivity;
use App\Services\SecurityNotifier;
use Illuminate\Support\Facades\Schema;

class NotifyTwoFactorEnabled
{
    private SecurityNotifier $notifier;

    public function __construct(SecurityNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(TwoFactorEnabled $event): void
    {
        $this->notifier->notify($event->user, '2fa_enabled');

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $event->user->id,
                'type' => '2fa_enabled',
                'title' => '2FA diaktifkan',
                'description' => 'Autentikasi dua faktor berhasil diaktifkan.',
                'occurred_at' => now(),
            ]);
        }
    }
}
