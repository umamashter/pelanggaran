<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\JuriLomba;
use App\Models\Guru;

class JuriLombaSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        $gurus = Guru::pluck('id')->toArray();
        if (empty($gurus)) return;

        $lombaIds = range(1, 9);
        $data = [];

        foreach ($lombaIds as $i => $lombaId) {
            $guruId = $gurus[$i % count($gurus)];
            $data[] = [
                'haflah_id' => $haflahId,
                'lomba_id' => $lombaId,
                'guru_id' => $guruId,
                'ketua' => $i === 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        JuriLomba::insert($data);
    }
}
