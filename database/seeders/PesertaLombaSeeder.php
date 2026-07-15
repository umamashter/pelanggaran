<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\PesertaLomba;
use App\Models\Student;

class PesertaLombaSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        $students = Student::pluck('id')->toArray();

        if (empty($students)) return;

        $data = [];
        $lombaIds = [1, 3, 4, 5, 6, 7, 8, 9]; // lomba individu

        foreach ($lombaIds as $lombaId) {
            foreach ($students as $i => $studentId) {
                $data[] = [
                    'haflah_id' => $haflahId,
                    'lomba_id' => $lombaId,
                    'student_id' => $studentId,
                    'status' => 'Terdaftar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        PesertaLomba::insert($data);
    }
}
