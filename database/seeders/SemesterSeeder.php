<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semuaTa = TahunAjaran::all();

        foreach ($semuaTa as $ta) {
            $sudahGanjil = $ta->semesters()->where('nama', 'Ganjil')->exists();
            $sudahGenap = $ta->semesters()->where('nama', 'Genap')->exists();

            if (!$sudahGanjil) {
                $ta->semesters()->create([
                    'nama' => 'Ganjil',
                    'aktif' => !$sudahGenap,
                ]);
            }

            if (!$sudahGenap) {
                $ta->semesters()->create([
                    'nama' => 'Genap',
                    'aktif' => false,
                ]);
            }
        }

        $taAktif = TahunAjaran::where('status', 'Aktif')->first();
        if ($taAktif && !$taAktif->semesterAktif) {
            $ganjil = $taAktif->semesters()->where('nama', 'Ganjil')->first();
            if ($ganjil) {
                $ganjil->update(['aktif' => true]);
            }
        }
    }
}
