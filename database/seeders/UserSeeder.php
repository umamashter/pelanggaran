<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 1,
                'info' => true,
            ]
        );

        // Guru 1
        $guru1 = User::firstOrCreate(
            ['username' => 'guru001'],
            [
                'name' => 'Ahmad',
                'email' => 'guru001@gmail.com',
                'password' => Hash::make('password'),
                'role' => 2,
                'info' => true,
            ]
        );

        Guru::firstOrCreate(
            ['kode_guru' => 'GR001'],
            [
                'user_id' => $guru1->id,
                'nama' => 'Ahmad',
                'nip' => null,
                'no_hp' => '081234567890',
                'alamat' => 'Madura',
            ]
        );

        // Guru 2
        $guru2 = User::firstOrCreate(
            ['username' => 'guru002'],
            [
                'name' => 'Budi',
                'email' => 'guru002@gmail.com',
                'password' => Hash::make('password'),
                'role' => 2,
                'info' => true,
            ]
        );

        Guru::firstOrCreate(
            ['kode_guru' => 'GR002'],
            [
                'user_id' => $guru2->id,
                'nama' => 'Budi',
                'nip' => null,
                'no_hp' => '081234567891',
                'alamat' => 'Madura',
            ]
        );

        // Guru 3
        $guru3 = User::firstOrCreate(
            ['username' => 'guru003'],
            [
                'name' => 'Siti',
                'email' => 'guru003@gmail.com',
                'password' => Hash::make('password'),
                'role' => 2,
                'info' => true,
            ]
        );

        Guru::firstOrCreate(
            ['kode_guru' => 'GR003'],
            [
                'user_id' => $guru3->id,
                'nama' => 'Siti',
                'nip' => null,
                'no_hp' => '081234567892',
                'alamat' => 'Madura',
            ]
        );
    }
}
