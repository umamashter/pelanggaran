<?php

namespace Database\Seeders;

use App\Models\JenisPeraturan;
use Illuminate\Database\Seeder;

class JenisPeraturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JenisPeraturan::create([
            'nama' => 'SIKAP PERILAKU'
        ]);
        JenisPeraturan::create([
            'nama' => 'KERAJINAN'
        ]);
        JenisPeraturan::create([
            'nama' => 'KERAPIAN'
        ]);
        JenisPeraturan::create([
            'nama' => 'TAMBAHAN'
        ]);
    }
}