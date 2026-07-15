<?php

namespace App\Listeners\Auth;

use App\Events\TwoFactorDisabled;
use App\Models\AccountActivity;
use App\Services\SecurityNotifier;
use Illuminate\Support\Facades\Schema;

class NotifyTwoFactorDisabled
{
    private SecurityNotifier $notifier;

    public function __construct(SecurityNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(TwoFactorDisabled $event): void
    {
        $this->notifier->notify($event->user, '2fa_disabled');

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $event->user->id,
                'type' => '2fa_disabled',
                'title' => '2FA dinonaktifkan',
                'description' => 'Autentikasi dua faktor dinonaktifkan.',
                'occurred_at' => now(),
            ]);
        }
    }
}
