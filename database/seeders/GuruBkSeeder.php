<?php

namespace Database\Seeders;

use App\Models\GuruBk;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuruBkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sri = User::create([
            'nisn' => '452312322',
            'name' => 'Sri',
            'email' => 'sri@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 4,
        ]);
        $yuli = User::create([
            'nisn' => '231098392',
            'name' => 'Yulistya',
            'email' => 'yulistya@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 4,
        ]);
        $endang = User::create([
            'nisn' => '973726811',
            'name' => 'Endang',
            'email' => 'endang@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 4,
        ]);


        GuruBk::create([
            'user_id' => $sri->id,
            'kelas_id' => 1,
            'name' => $sri->name
        ]);
        GuruBk::create([
            'user_id' => $yuli->id,
            'kelas_id' => 2,
            'name' => $yuli->name
        ]);
        GuruBk::create([
            'user_id' => $endang->id,
            'kelas_id' => 3,
            'name' => $endang->name
        ]);
    }
}