<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentKelas;
use App\Models\TahunAjaran;

class StudentKelasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'kelas_id' => 'required',
            'tahun_ajaran_id' => 'required',
        ]);

        $tahunAktif = TahunAjaran::find($request->tahun_ajaran_id);

        StudentKelas::where('student_id', $request->student_id)
            ->update(['aktif' => false]);

        $semester = $tahunAktif?->semesterAktif;

        StudentKelas::create([
            'student_id' => $request->student_id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'semester_id' => $semester?->id,
            'aktif' => true,
        ]);

        return back()->with('success', 'Siswa berhasil ditempatkan');
    }
}
