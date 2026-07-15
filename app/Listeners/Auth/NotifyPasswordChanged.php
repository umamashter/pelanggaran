<?php

namespace App\Listeners\Auth;

use App\Events\PasswordChanged;
use App\Models\AccountActivity;
use App\Services\SecurityNotifier;
use Illuminate\Support\Facades\Schema;

class NotifyPasswordChanged
{
    private SecurityNotifier $notifier;

    public function __construct(SecurityNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(PasswordChanged $event): void
    {
        $this->notifier->notify($event->user, 'password_changed');

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $event->user->id,
                'type' => 'password_changed',
                'title' => 'Password diperbarui',
                'description' => 'Password akun berhasil diganti.',
                'occurred_at' => now(),
            ]);
        }
    }
}
