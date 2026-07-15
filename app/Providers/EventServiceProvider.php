<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\Auth\RecordSuccessfulLogin;
use App\Listeners\Auth\RecordFailedLogin;
use App\Listeners\Auth\RecordLogout;
use App\Listeners\Auth\DetectNewDevice;
use App\Listeners\Auth\UpsertActiveSession;
use App\Listeners\Auth\NotifyTwoFactorEnabled;
use App\Listeners\Auth\NotifyTwoFactorDisabled;
use App\Listeners\Auth\NotifyRecoveryCodeUsed;
use App\Listeners\Auth\NotifyPasswordChanged;
use App\Events\TwoFactorEnabled;
use App\Events\TwoFactorDisabled;
use App\Events\RecoveryCodeUsed;
use App\Events\PasswordChanged;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Keamanan enterprise — Login History (Fase 1) + Device Fingerprint (Fase 2)
        Login::class => [
            RecordSuccessfulLogin::class,   // 1. catat login berhasil (final)
            DetectNewDevice::class,          // 2. resolusi fingerprint + tandai is_new_device + notif
            UpsertActiveSession::class,      // 3. pastikan sessions.user_id terisi
        ],
        Failed::class => [
            RecordFailedLogin::class,
        ],
        Logout::class => [
            RecordLogout::class,            // logout nyata (challenge di-skip di service)
        ],

        // Keamanan enterprise — Security Notifications (Fase 3)
        TwoFactorEnabled::class => [
            NotifyTwoFactorEnabled::class,
        ],
        TwoFactorDisabled::class => [
            NotifyTwoFactorDisabled::class,
        ],
        RecoveryCodeUsed::class => [
            NotifyRecoveryCodeUsed::class,
        ],
        PasswordChanged::class => [
            NotifyPasswordChanged::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
