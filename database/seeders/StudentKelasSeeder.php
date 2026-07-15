<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\StudentKelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class StudentKelasSeeder extends Seeder
{
    public function run(): void
    {
        $ta = TahunAjaran::where('status', 'Aktif')->first();

        $students = Student::all();

        foreach ($students as $index => $student) {

            $kelas = Kelas::skip($index)->first();

            if (!$kelas) {
                continue;
            }

            StudentKelas::firstOrCreate([
                'student_id' => $student->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran_id' => $ta->id,
            ], [
                'aktif' => true,
            ]);
        }
    }
}
