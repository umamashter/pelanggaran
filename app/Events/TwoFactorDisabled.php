<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TwoFactorDisabled
{
    use Dispatchable;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}