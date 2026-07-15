<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use App\Models\Kurikulum;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mi = Jenjang::where('kode', 'MI')->first();
        $merdeka = Kurikulum::where('aktif', true)->first();

        $mapel = [
            ['PAI', 'Pendidikan Agama Islam', 'Wajib'],
            ['PKN', 'Pendidikan Pancasila', 'Wajib'],
            ['BIN', 'Bahasa Indonesia', 'Wajib'],
            ['MTK', 'Matematika', 'Wajib'],
            ['IPAS', 'IPAS', 'Wajib'],
            ['SBK', 'Seni Budaya', 'Wajib'],
            ['PJOK', 'PJOK', 'Wajib'],
            ['BIG', 'Bahasa Inggris', 'Pilihan'],
        ];

        foreach ($mapel as $item) {
            MataPelajaran::firstOrCreate(
                [
                    'kode_mapel' => $item[0],
                    'jenjang_id' => $mi->id,
                    'kurikulum_id' => $merdeka->id,
                ],
                [
                    'nama_mapel' => $item[1],
                    'kelompok' => $item[2],
                    'status' => 'Aktif',
                ]
            );
        }
    }
}
