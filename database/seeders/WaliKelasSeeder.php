<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Database\Seeder;

class WaliKelasSeeder extends Seeder
{
    public function run(): void
    {
        $ta = TahunAjaran::where('status', 'Aktif')->first() ?? TahunAjaran::first();
        $taId = $ta->id;

        $data = [
            [
                'guru_id' => 1,
                'kelas_id' => 7,
                'tahun_ajaran_id' => $taId,
            ],
            [
                'guru_id' => 2,
                'kelas_id' => 8,
                'tahun_ajaran_id' => $taId,
            ],
            [
                'guru_id' => 3,
                'kelas_id' => 9,
                'tahun_ajaran_id' => $taId,
            ],
        ];

        foreach ($data as $item) {
            WaliKelas::firstOrCreate($item);
        }
    }
}
