<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Kelas;
use App\Models\Jurusan;
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

        // Role 2 (guru wali kelas) → wajib punya kelas_id
        if ($user->role == 2 && (!$user->waliKelas || $user->waliKelas->kelas_id == null)) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akses ditolak! Guru wali kelas belum memiliki kelas yang ditugaskan.');
        }

        // Role 1 (admin) dan Role 2 (guru wali kelas) → info wajib true
        // Nonaktifkan sementara validasi info
        if ($user->role != 3 && $user->info == false) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda belum valid. Hubungi administrator.');
        }
        return $next($request);
    }
}
