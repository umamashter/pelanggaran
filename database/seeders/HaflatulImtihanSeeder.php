<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;
use App\Models\HaflatulImtihan;

class HaflatulImtihanSeeder extends Seeder
{
    public function run(): void
    {
        $ta = TahunAjaran::where('status', 'Aktif')->first() ?? TahunAjaran::first();

        HaflatulImtihan::firstOrCreate(
            ['tahun_ajaran_id' => $ta->id],
            [
                'nama_acara' => 'Haflatul Imtihan dan Akhirussanah',
                'tanggal_mulai' => '2027-06-18',
                'tanggal_selesai' => '2027-06-20',
                'status' => 'Aktif',
            ]
        );
    }
}
