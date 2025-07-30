<?php

namespace Database\Seeders;

use App\Models\TindakLanjut;
use Illuminate\Database\Seeder;

class TindakLanjutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Peringatan ke I',
                'tingkatan' => 'Ringan',
            ]
        );
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Peringatan ke II',
                'tingkatan' => 'Ringan',
            ]
        );
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Panggilan Orang tua I',
                'tingkatan' => 'Sedang',
            ]
        );
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Panggilan Orang tua II',
                'tingkatan' => 'Sedang',
            ]
        );
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Panggilan Orang tua III',
                'tingkatan' => 'Sedang',
            ]
        );
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Skorsing',
                'tingkatan' => 'Berat',
            ]
        );
        TindakLanjut::create(
            [
                'tindak_lanjut' => 'Dikembalikan Orang tua',
                'tingkatan' => 'Berat',
            ]
        );
    }
}