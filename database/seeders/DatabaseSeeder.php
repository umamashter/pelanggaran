<?php

namespace Database\Seeders;

use App\Models\GuruBk;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(5)->create();
        $this->call([
            KelasSeeder::class,
            UserSeeder::class,
            WaliKelasSeeder::class,
            StudentSeeder::class,
            GuruBkSeeder::class,
            JenisPeraturanSeeder::class,
            PeraturanSeeder::class,
            TindakLanjutSeeder::class,
        ]);
    }
}