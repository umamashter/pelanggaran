<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        $ta = date('Y') . '/' . (date('Y') + 1);

        if (!TahunAjaran::where('tahun_ajaran', $ta)->exists()) {
            TahunAjaran::create([
                'tahun_ajaran' => $ta,
                'status' => 'Aktif',
            ]);
        }
    }
}
