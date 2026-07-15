<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use Illuminate\Database\Seeder;

class JenjangSeeder extends Seeder
{
    public function run()
    {
        Jenjang::create([
            'kode' => 'MI',
            'nama_jenjang' => 'Madrasah Ibtidaiyah',
            'tingkat_awal' => 1,
            'tingkat_akhir' => 6
        ]);

        Jenjang::create([
            'kode' => 'MTS',
            'nama_jenjang' => 'Madrasah Tsanawiyah',
            'tingkat_awal' => 7,
            'tingkat_akhir' => 9
        ]);

        Jenjang::create([
            'kode' => 'MA',
            'nama_jenjang' => 'Madrasah Aliyah',
            'tingkat_awal' => 10,
            'tingkat_akhir' => 12
        ]);
    }
}
