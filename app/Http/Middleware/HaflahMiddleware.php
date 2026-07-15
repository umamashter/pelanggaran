<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\HaflatulImtihan;
use Illuminate\Support\Facades\View;

class HaflahMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $today = now()->startOfDay();

            HaflatulImtihan::where('status', 'Persiapan')
                ->where('tanggal_mulai', '<=', $today)
                ->update(['status' => 'Aktif']);

            HaflatulImtihan::where('status', 'Aktif')
                ->where('tanggal_selesai', '<', $today)
                ->update(['status' => 'Selesai']);

            $haflahId = session('haflah_id');

            if (!$haflahId) {
                $aktif = HaflatulImtihan::where('status', 'Aktif')->first();
                if ($aktif) {
                    session(['haflah_id' => $aktif->id]);
                    $haflahId = $aktif->id;
                }
            }

            if ($haflahId) {
                $haflahAktif = HaflatulImtihan::with('tahunAjaran')->find($haflahId);
                View::share('haflahAktif', $haflahAktif);
            }

            $semuaHaflah = HaflatulImtihan::with('tahunAjaran')->orderBy('tanggal_mulai', 'desc')->get();
            View::share('semuaHaflah', $semuaHaflah);
        }

        return $next($request);
    }
}
