<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HaflatulImtihan;
use App\Models\AnggotaKelompok;
use App\Models\Student;

class AnggotaKelompokSeeder extends Seeder
{
    public function run(): void
    {
        $haflah = HaflatulImtihan::where('status', 'Aktif')->first() ?? HaflatulImtihan::first();
        $haflahId = $haflah->id;

        $students = Student::pluck('id')->toArray();
        if (empty($students)) return;

        $data = [];
        foreach ($students as $studentId) {
            $data[] = [
                'haflah_id' => $haflahId,
                'kelompok_lomba_id' => 1,
                'student_id' => $studentId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AnggotaKelompok::insert($data);
    }
}
