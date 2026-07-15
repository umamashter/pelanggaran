<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use Illuminate\Database\Seeder;

class KurikulumSeeder extends Seeder
{
    public function run(): void
    {
        Kurikulum::firstOrCreate(
            ['nama_kurikulum' => 'Kurikulum Merdeka'],
            [
                'keterangan' => 'Kurikulum Merdeka Belajar',
                'aktif' => true,
            ]
        );

        Kurikulum::firstOrCreate(
            ['nama_kurikulum' => 'Kurikulum 2013'],
            [
                'keterangan' => 'Kurikulum 2013 (K13)',
                'aktif' => false,
            ]
        );
    }
}
