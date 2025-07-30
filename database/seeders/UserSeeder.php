<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nisn' => '0000000001',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 1,
        ]);
    }
}