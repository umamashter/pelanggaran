<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\Lomba;

class LombaSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        Lomba::insert([
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 1, 'kategori_lomba_id' => 1, 'nama' => 'Balap Karung',    'jenis' => 'Individu', 'lokasi' => 'Lapangan', 'deskripsi' => 'Lomba balap karung',              'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 1, 'kategori_lomba_id' => 1, 'nama' => 'Tarik Tambang',   'jenis' => 'Tim',     'lokasi' => 'Lapangan', 'deskripsi' => 'Lomba tarik tambang antar kelas', 'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 1, 'kategori_lomba_id' => 2, 'nama' => 'Tartil',          'jenis' => 'Individu', 'lokasi' => 'Aula',     'deskripsi' => 'Lomba membaca Al-Quran tartil',      'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 2, 'kategori_lomba_id' => 2, 'nama' => 'Adzan',           'jenis' => 'Individu', 'lokasi' => 'Masjid',   'deskripsi' => 'Lomba adzan',                        'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 2, 'kategori_lomba_id' => 2, 'nama' => 'Shalawat',        'jenis' => 'Individu', 'lokasi' => 'Aula',     'deskripsi' => 'Lomba shalawat',                     'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 3, 'kategori_lomba_id' => 3, 'nama' => 'Puisi',           'jenis' => 'Individu', 'lokasi' => 'Aula',     'deskripsi' => 'Lomba baca puisi',                   'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 3, 'kategori_lomba_id' => 3, 'nama' => 'MC',              'jenis' => 'Individu', 'lokasi' => 'Aula',     'deskripsi' => 'Lomba MC (Master of Ceremony)',      'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 4, 'kategori_lomba_id' => 4, 'nama' => 'Pidato',          'jenis' => 'Individu', 'lokasi' => 'Aula',     'deskripsi' => 'Lomba pidato bahasa Indonesia',      'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
            ['haflah_id' => $haflahId, 'sesi_lomba_id' => 4, 'kategori_lomba_id' => 4, 'nama' => 'Baca Kitab',      'jenis' => 'Individu', 'lokasi' => 'Aula',     'deskripsi' => 'Lomba baca kitab kuning',            'status' => 'Belum Mulai', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
