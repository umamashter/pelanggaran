<?php

namespace App\Listeners\Auth;

use App\Events\RecoveryCodeUsed;
use App\Models\AccountActivity;
use App\Services\SecurityNotifier;
use Illuminate\Support\Facades\Schema;

class NotifyRecoveryCodeUsed
{
    private SecurityNotifier $notifier;

    public function __construct(SecurityNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(RecoveryCodeUsed $event): void
    {
        $remaining = collect(json_decode($event->user->recovery_codes ?? '[]', true))->count();

        $this->notifier->notify($event->user, 'recovery_used', [
            'remaining' => $remaining,
        ]);

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $event->user->id,
                'type' => 'recovery_code_used',
                'title' => 'Recovery code dipakai',
                'description' => 'Satu recovery code digunakan. Sisa: ' . $remaining,
                'metadata' => ['remaining' => $remaining],
                'occurred_at' => now(),
            ]);
        }
    }
}
