<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\MataPelajaran;
use App\Models\StrukturKurikulum;
use Illuminate\Database\Seeder;

class StrukturKurikulumSeeder extends Seeder
{
    public function run(): void
    {
        $kurikulum = Kurikulum::where('aktif', true)->first();

        if (!$kurikulum) {
            return;
        }

        $jamMapel = [
            'Pendidikan Agama Islam' => 4,
            'Pendidikan Pancasila'   => 4,
            'Bahasa Indonesia'       => 6,
            'Matematika'             => 5,
            'IPAS'                   => 5,
            'Seni Budaya'            => 3,
            'PJOK'                   => 3,
            'Bahasa Inggris'         => 2,
        ];

        $kelasList = Kelas::all();

        foreach ($kelasList as $kelas) {

            foreach ($jamMapel as $namaMapel => $jp) {

                $mapel = MataPelajaran::where(
                    'nama_mapel',
                    $namaMapel
                )->first();

                if (!$mapel) {
                    continue;
                }

                StrukturKurikulum::firstOrCreate(
                    [
                        'kurikulum_id'      => $kurikulum->id,
                        'kelas_id'          => $kelas->id,
                        'mata_pelajaran_id' => $mapel->id,
                    ],
                    [
                        'jam_pelajaran' => $jp,
                    ]
                );
            }
        }
    }
}
