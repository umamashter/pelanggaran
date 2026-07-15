<?php

namespace Database\Seeders;

use App\Models\SesiLomba;
use App\Models\KategoriLomba;
use App\Models\Lomba;
use App\Models\PesertaLomba;
use App\Models\KelompokLomba;
use App\Models\AnggotaKelompok;
use App\Models\JuriLomba;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            KelasSeeder::class,
            TahunAjaranSeeder::class,
            UserSeeder::class,
            WaliKelasSeeder::class,
            StudentSeeder::class,
            GuruBkSeeder::class,
            JenisPeraturanSeeder::class,
            PeraturanSeeder::class,
            TindakLanjutSeeder::class,
            JenjangSeeder::class,
        ]);

        $this->call(HaflatulImtihanSeeder::class);

        // Hapus data lama lomba dulu (biar idempotent)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        JuriLomba::truncate();
        AnggotaKelompok::truncate();
        KelompokLomba::truncate();
        PesertaLomba::truncate();
        Lomba::truncate();
        SesiLomba::truncate();
        KategoriLomba::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call([
            KategoriLombaSeeder::class,
            SesiLombaSeeder::class,
            LombaSeeder::class,
            PesertaLombaSeeder::class,
            KelompokLombaSeeder::class,
            AnggotaKelompokSeeder::class,
            JuriLombaSeeder::class,
        ]);
    }
}
