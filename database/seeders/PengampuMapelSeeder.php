<?php

namespace Database\Seeders;

use App\Models\PengampuMapel;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class PengampuMapelSeeder extends Seeder
{
    public function run(): void
    {
        $ta = TahunAjaran::where('status', 'Aktif')->first() ?? TahunAjaran::first();
        $taId = $ta->id;

        $data = [

            // Ahmad = PAI
            ['guru_id' => 1, 'mata_pelajaran_id' => 1, 'kelas_id' => 7, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 1, 'mata_pelajaran_id' => 1, 'kelas_id' => 8, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 1, 'mata_pelajaran_id' => 1, 'kelas_id' => 9, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 1, 'mata_pelajaran_id' => 1, 'kelas_id' => 10, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 1, 'mata_pelajaran_id' => 1, 'kelas_id' => 11, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 1, 'mata_pelajaran_id' => 1, 'kelas_id' => 12, 'tahun_ajaran_id' => $taId],

            // Budi = Bahasa Indonesia
            ['guru_id' => 2, 'mata_pelajaran_id' => 3, 'kelas_id' => 7, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 2, 'mata_pelajaran_id' => 3, 'kelas_id' => 8, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 2, 'mata_pelajaran_id' => 3, 'kelas_id' => 9, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 2, 'mata_pelajaran_id' => 3, 'kelas_id' => 10, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 2, 'mata_pelajaran_id' => 3, 'kelas_id' => 11, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 2, 'mata_pelajaran_id' => 3, 'kelas_id' => 12, 'tahun_ajaran_id' => $taId],

            // Siti = Matematika
            ['guru_id' => 3, 'mata_pelajaran_id' => 4, 'kelas_id' => 7, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 3, 'mata_pelajaran_id' => 4, 'kelas_id' => 8, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 3, 'mata_pelajaran_id' => 4, 'kelas_id' => 9, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 3, 'mata_pelajaran_id' => 4, 'kelas_id' => 10, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 3, 'mata_pelajaran_id' => 4, 'kelas_id' => 11, 'tahun_ajaran_id' => $taId],
            ['guru_id' => 3, 'mata_pelajaran_id' => 4, 'kelas_id' => 12, 'tahun_ajaran_id' => $taId],
        ];

        foreach ($data as $item) {
            PengampuMapel::firstOrCreate($item);
        }
    }
}
