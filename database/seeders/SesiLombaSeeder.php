<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\SesiLomba;

class SesiLombaSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        SesiLomba::insert([
            ['haflah_id' => $haflahId, 'nama' => 'Hari 1 Pagi',   'tanggal' => '2027-06-18', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Hari 1 Siang',  'tanggal' => '2027-06-18', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Hari 1 Malam',  'tanggal' => '2027-06-18', 'jam_mulai' => '19:00', 'jam_selesai' => '22:00', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Hari 2 Pagi',   'tanggal' => '2027-06-19', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Hari 2 Siang',  'tanggal' => '2027-06-19', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Hari 2 Malam',  'tanggal' => '2027-06-19', 'jam_mulai' => '19:00', 'jam_selesai' => '22:00', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'nama' => 'Hari 3 Pagi',   'tanggal' => '2027-06-20', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
