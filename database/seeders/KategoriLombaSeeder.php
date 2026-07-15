<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\KategoriLomba;

class KategoriLombaSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        KategoriLomba::insert([
            ['haflah_id' => $haflahId, 'nama' => 'Permainan', 'warna' => '#FF6B6B', 'icon' => 'fas fa-gamepad', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Keagamaan', 'warna' => '#4ECDC4', 'icon' => 'fas fa-mosque', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Seni',       'warna' => '#FFE66D', 'icon' => 'fas fa-palette', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Bahasa',    'warna' => '#95E1D3', 'icon' => 'fas fa-language', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
