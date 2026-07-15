<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;
use App\Models\JadwalPelajaran;

class JadwalPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $ta = TahunAjaran::where('status', 'Aktif')->first() ?? TahunAjaran::first();
        $taId = $ta->id;

        $data = [

            // KELAS 1A
            [
                'kelas_id' => 7,
                'guru_id' => 1,
                'mata_pelajaran_id' => 1,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Senin',
                'jam_ke' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '07:35:00',
            ],
            [
                'kelas_id' => 7,
                'guru_id' => 2,
                'mata_pelajaran_id' => 4,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Senin',
                'jam_ke' => 2,
                'jam_mulai' => '07:35:00',
                'jam_selesai' => '08:10:00',
            ],
            [
                'kelas_id' => 7,
                'guru_id' => 3,
                'mata_pelajaran_id' => 3,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Senin',
                'jam_ke' => 3,
                'jam_mulai' => '08:10:00',
                'jam_selesai' => '08:45:00',
            ],

            // KELAS 2A
            [
                'kelas_id' => 8,
                'guru_id' => 1,
                'mata_pelajaran_id' => 1,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Selasa',
                'jam_ke' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '07:35:00',
            ],
            [
                'kelas_id' => 8,
                'guru_id' => 2,
                'mata_pelajaran_id' => 4,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Selasa',
                'jam_ke' => 2,
                'jam_mulai' => '07:35:00',
                'jam_selesai' => '08:10:00',
            ],

            // KELAS 3A
            [
                'kelas_id' => 9,
                'guru_id' => 3,
                'mata_pelajaran_id' => 8,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Rabu',
                'jam_ke' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '07:35:00',
            ],

            // KELAS 4A
            [
                'kelas_id' => 10,
                'guru_id' => 2,
                'mata_pelajaran_id' => 5,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Kamis',
                'jam_ke' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '07:35:00',
            ],

            // KELAS 5A
            [
                'kelas_id' => 11,
                'guru_id' => 1,
                'mata_pelajaran_id' => 7,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Jumat',
                'jam_ke' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '07:35:00',
            ],

            // KELAS 6A
            [
                'kelas_id' => 12,
                'guru_id' => 3,
                'mata_pelajaran_id' => 2,
                'tahun_ajaran_id' => $taId,
                'hari' => 'Sabtu',
                'jam_ke' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '07:35:00',
            ],
        ];

        foreach ($data as $jadwal) {
            JadwalPelajaran::create($jadwal);
        }
    }
}
