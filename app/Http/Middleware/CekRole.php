<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekRole
{
    /**
     * Handle an incoming request.
     * [1.admin, 2.guru, 3.siswa]
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        if (auth()->user() && in_array(auth()->user()->role, $role)) {
            return $next($request);
        }

        return back();
    }
}