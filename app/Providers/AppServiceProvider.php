<?php

namespace App\Providers;

use App\Models\ProfilMadrasah;
use App\Models\Pengumuman;
use App\Models\Galery;
use App\Models\Semester;
use App\Observers\SemesterObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();

        Semester::observe(SemesterObserver::class);

        $profil = ProfilMadrasah::with('misi')->first();
        $pengumuman = Pengumuman::where('status', 'Published')
            ->orderBy('tanggal', 'desc')
            ->limit(6)
            ->get();

        $galery = Schema::hasTable('galery')
            ? Galery::where('status', 'Published')->orderBy('created_at', 'desc')->limit(8)->get()
            : collect();

        View::share('profil', $profil);
        View::share('pengumuman', $pengumuman);
        View::share('galery', $galery);
    }
}