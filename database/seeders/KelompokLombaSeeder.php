<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\KelompokLomba;

class KelompokLombaSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        $data = [
            ['haflah_id' => $haflahId, 'lomba_id' => 2, 'nama_kelompok' => 'Kelas 4A', 'asal' => 'Kelas 4A'],
            ['haflah_id' => $haflahId, 'lomba_id' => 2, 'nama_kelompok' => 'Kelas 4B', 'asal' => 'Kelas 4B'],
            ['haflah_id' => $haflahId, 'lomba_id' => 2, 'nama_kelompok' => 'Kelas 5A', 'asal' => 'Kelas 5A'],
        ];

        $ids = [];
        foreach ($data as $item) {
            $ids[] = KelompokLomba::insertGetId($item);
        }

        foreach ($ids as $id) {
            $kelompok = KelompokLomba::find($id);
            if ($kelompok) {
                $kelompok->kode_kelompok = 'KLP-' . str_pad($kelompok->id, 4, '0', STR_PAD_LEFT);
                $kelompok->save();
            }
        }
    }
}
