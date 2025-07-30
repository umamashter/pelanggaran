<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Seeder;

class WaliKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'nisn' => '0000000002',
            'name' => 'AsmuIn',
            'email' => 'pakasmuin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 2,
        ]);
        User::create([
            'nisn' => '0000000003',
            'name' => 'Lukman Sholeh',
            'email' => 'paklukman@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 2,
        ]);
        User::create([
            'nisn' => '0000000004',
            'name' => 'Mochammad Arsyad',
            'email' => 'pakarsyad@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 2,
        ]);
        User::create([
            'nisn' => '0000000005',
            'name' => 'Kukuh Widodo',
            'email' => 'pakkukuh@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 2,
        ]);
        User::create([
            'nisn' => '0000000006',
            'name' => 'Reny Karlinawati',
            'email' => 'bureny@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 2,
        ]);
        User::create([
            'nisn' => '0000000007',
            'name' => 'Farahma Yuanita',
            'email' => 'buyuanita@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 2,
        ]);
        //


        WaliKelas::create([
            'user_id' => 2,
            'kelas_id' => 1,
            'name' => 'Asmuin'
        ]);
        WaliKelas::create([
            'user_id' => 3,
            'kelas_id' => 2,
            'name' => 'Lukman Sholeh'
        ]);
        WaliKelas::create([
            'user_id' => 4,
            'kelas_id' => 3,
            'name' => 'Mochammad Arsyad'
        ]);
        WaliKelas::create([
            'user_id' => 5,
            'kelas_id' => 4,
            'name' => 'Kukuh Widodo'
        ]);
        WaliKelas::create([
            'user_id' => 6,
            'kelas_id' => 5,
            'name' => 'Reny Karlinawati'
        ]);
        WaliKelas::create([
            'user_id' => 7,
            'kelas_id' => 6,
            'name' => 'Farahma Yuanita'
        ]);
    }
}