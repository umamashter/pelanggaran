<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kelas::create([
            'nama_kelas' => '12 RPL 1'
        ]);
        Kelas::create([
            'nama_kelas' => '12 RPL 2'
        ]);
        Kelas::create([
            'nama_kelas' => '11 RPL 1'
        ]);
        Kelas::create([
            'nama_kelas' => '11 RPL 2'
        ]);
        Kelas::create([
            'nama_kelas' => '10 RPL 1'
        ]);
        Kelas::create([
            'nama_kelas' => '10 RPL 2'
        ]);

        Kelas::create([
            'nama_kelas' => '12 MM 1'
        ]);
        Kelas::create([
            'nama_kelas' => '12 MM 2'
        ]);
        Kelas::create([
            'nama_kelas' => '11 MM 1'
        ]);
        Kelas::create([
            'nama_kelas' => '11 MM 2'
        ]);
        Kelas::create([
            'nama_kelas' => '10 MM 1'
        ]);
        Kelas::create([
            'nama_kelas' => '10 MM 2'
        ]);

        Kelas::create([
            'nama_kelas' => '12 TKJ 1'
        ]);
        Kelas::create([
            'nama_kelas' => '12 TKJ 2'
        ]);
        Kelas::create([
            'nama_kelas' => '11 TKJ 1'
        ]);
        Kelas::create([
            'nama_kelas' => '11 TKJ 2'
        ]);
        Kelas::create([
            'nama_kelas' => '10 TKJ 1'
        ]);
        Kelas::create([
            'nama_kelas' => '10 TKJ 2'
        ]);
    }
}