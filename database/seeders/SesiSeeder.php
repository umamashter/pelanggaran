<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use Illuminate\Support\Facades\DB;

class SesiSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        $data = [
            ['nama' => 'Hari 1 Pagi',   'tanggal' => '2027-06-18', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'urutan' => 1],
            ['nama' => 'Hari 1 Siang',  'tanggal' => '2027-06-18', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'urutan' => 2],
            ['nama' => 'Hari 1 Malam',  'tanggal' => '2027-06-18', 'jam_mulai' => '19:00', 'jam_selesai' => '22:00', 'urutan' => 3],
            ['nama' => 'Hari 2 Pagi',   'tanggal' => '2027-06-19', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'urutan' => 4],
            ['nama' => 'Hari 2 Siang',  'tanggal' => '2027-06-19', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'urutan' => 5],
            ['nama' => 'Hari 2 Malam',  'tanggal' => '2027-06-19', 'jam_mulai' => '19:00', 'jam_selesai' => '22:00', 'urutan' => 6],
            ['nama' => 'Hari 3 Pagi',   'tanggal' => '2027-06-20', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'urutan' => 7],
        ];

        $now = now();
        foreach ($data as $item) {
            $item['haflah_id'] = $haflahId;
            $item['created_at'] = $now;
            $item['updated_at'] = $now;

            DB::table('sesis')->insert($item);
        }
    }
}
