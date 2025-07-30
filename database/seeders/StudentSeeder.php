<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ren = User::create([
            'nisn' => '0043846692',
            'name' => 'Renaldy Naufal',
            'email' => 'ren@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 3,
        ]);
        $san = User::create([
            'nisn' => '0051595487',
            'name' => 'Iksan Arya Dinata',
            'email' => 'san@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'info' => true,
            'role' => 3,
        ]);

        Student::create([
            'user_id' => $ren->id,
            'kelas_id' => 2,
            'nama' => 'Renaldy Naufal TA',
            'nisn' => '0043846692',
            'ttl' => 'Surabaya, 2004-04-04',
            'jk' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Pandugo',
            'no_telp' => '0823121231',
            'n_ayah' => 'Hendra',
            'n_ibu' => 'Putri',
            'alamat_ortu' => 'Pandugo',
            'no_telp_rumah' => '0281323',
        ]);
        Student::create([
            'user_id' => $san->id,
            'kelas_id' => 2,
            'nama' => 'Iksan Arya Dinata',
            'nisn' => '0051595487',
            'ttl' => 'Surabaya, 2005-05-01',
            'jk' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Rungkut Lor X makmur 63a kav.22',
            'no_telp' => '088235460449',
            'n_ayah' => 'Sunaryo',
            'n_ibu' => 'Sarniti',
            'alamat_ortu' => 'Rungkut Lor X makmur 63a kav.22',
            'no_telp_rumah' => '081331122643',
        ]);
    }
}