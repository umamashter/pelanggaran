<?php

namespace App\Http\Middleware;

use Closure;

class Google2faMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->google2fa_secret && !session('2fa_passed')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}
