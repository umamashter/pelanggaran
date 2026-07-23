<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataSiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $kelas = Kelas::all();

        // Role 3 (siswa) → wajib isi data siswa
        if ($user->role == 3 && $user->info == false) {
            return response()->view('form-datasiswa', compact('kelas'));
        }

        return $next($request);
    }
}
