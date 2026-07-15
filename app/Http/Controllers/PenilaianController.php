<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Student;
use App\Models\Penilaian;
use App\Models\PenilaianDetail;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenilaianController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPelajaran::with([
            'kelas',
            'guru',
            'mapel'
        ])->get();

        return view(
            'admin.penilaian.index',
            compact('jadwals')
        );
    }
    public function show($id)
    {
        $jadwal = JadwalPelajaran::with([
            'kelas',
            'mapel'
        ])->findOrFail($id);

        $students = Student::where(
            'kelas_id',
            $jadwal->kelas_id
        )->orderBy('nama')
            ->get();

        return view(
            'admin.penilaian.show',
            compact(
                'jadwal',
                'students'
            )
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_pelajaran_id' => 'required'
        ]);

        $jadwal = JadwalPelajaran::findOrFail(
            $request->jadwal_pelajaran_id
        );
        $cek = Penilaian::where(
            'jadwal_pelajaran_id',
            $jadwal->id
        )->first();

        if ($cek) {

            return back()->with(
                'error',
                'Nilai untuk mapel ini sudah pernah dibuat.'
            );
        }

        $penilaian = Penilaian::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'tahun_ajaran_id' => $jadwal->tahun_ajaran_id,
        ]);

        foreach ($request->tugas as $studentId => $nilai) {

            PenilaianDetail::create([

                'penilaian_id' => $penilaian->id,

                'student_id' => $studentId,

                'tugas' => $request->tugas[$studentId] ?? 0,

                'uh' => $request->uh[$studentId] ?? 0,

                'pts' => $request->pts[$studentId] ?? 0,

                'pas' => $request->pas[$studentId] ?? 0,

            ]);
        }

        return redirect()
            ->route('penilaian.index')
            ->with(
                'success',
                'Nilai berhasil disimpan.'
            );
    }
    public function pdf($id)
    {
        $penilaian = Penilaian::with([
            'jadwal.kelas',
            'jadwal.mapel',

            'details.student'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'admin.penilaian.pdf',
            compact('penilaian')
        );

        return $pdf->download('rekap-nilai.pdf');
    }
    public function rekap($id)
    {
        $penilaian = Penilaian::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'details.student'
        ])->findOrFail($id);

        return view(
            'admin.penilaian.rekap',
            compact('penilaian')
        );
    }
    public function detail($id)
    {
        $penilaian = Penilaian::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'details.student'
        ])->findOrFail($id);

        return view(
            'admin.penilaian.detail',
            compact('penilaian')
        );
    }

    public function hasil()
    {
        $penilaians = Penilaian::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.guru',
            'details'
        ])->latest()->get();

        return view(
            'admin.penilaian.hasil',
            compact('penilaians')
        );
    }
    public function edit($id)
    {
        $penilaian = Penilaian::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'details.student'
        ])->findOrFail($id);

        return view(
            'admin.penilaian.edit',
            compact('penilaian')
        );
    }
    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::with('details')
            ->findOrFail($id);

        foreach ($penilaian->details as $detail) {

            $detail->update([

                'tugas' =>
                $request->tugas[$detail->id],

                'uh' =>
                $request->uh[$detail->id],

                'pts' =>
                $request->pts[$detail->id],

                'pas' =>
                $request->pas[$detail->id],

            ]);
        }

        return redirect()
            ->route(
                'penilaian.detail',
                $penilaian->id
            )
            ->with(
                'success',
                'Nilai berhasil diperbarui.'
            );
    }
}
