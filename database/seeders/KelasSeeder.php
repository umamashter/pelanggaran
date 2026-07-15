<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Jenjang;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $mi = Jenjang::where('kode', 'MI')->first();

        for ($i = 1; $i <= 6; $i++) {

            Kelas::firstOrCreate(
                [
                    'jenjang_id' => $mi->id,
                    'tingkat' => $i,
                    'nama_kelas' => '1',
                ]
            );
        }
    }
}
