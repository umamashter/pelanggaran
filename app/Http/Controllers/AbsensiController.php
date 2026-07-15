<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use App\Models\Absensi;
use App\Models\AbsensiDetail;

class AbsensiController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPelajaran::with([
            'kelas',
            'guru',
            'mapel'
        ])->get();
        $kelasSudahAbsen = Absensi::whereDate(
            'tanggal',
            now()->toDateString()
        )
            ->with('jadwal')
            ->get()
            ->pluck('jadwal.kelas_id')
            ->unique()
            ->toArray();

        return view(
            'admin.absensi.index',
            compact(
                'jadwals',
                'kelasSudahAbsen'
            )
        );
    }
    public function create($id)
    {
        $jadwal = JadwalPelajaran::with([
            'kelas',
            'guru',
            'mapel'
        ])->findOrFail($id);

        $siswas = Student::where(
            'kelas_id',
            $jadwal->kelas_id
        )->orderBy('nama')
            ->get();

        return view(
            'admin.absensi.create',
            compact(
                'jadwal',
                'siswas'
            )
        );
    }
    public function show($id)
    {
        $jadwal = JadwalPelajaran::with([
            'kelas',
            'guru',
            'mapel'
        ])->findOrFail($id);

        $students = Student::where(
            'kelas_id',
            $jadwal->kelas_id
        )->orderBy('nama')
            ->get();

        return view(
            'admin.absensi.show',
            compact(
                'jadwal',
                'students'
            )
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_pelajaran_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|array'
        ]);

        // Ambil data jadwal yang dipilih
        $jadwal = JadwalPelajaran::findOrFail(
            $request->jadwal_pelajaran_id
        );

        // Cek apakah kelas tersebut sudah diabsen pada tanggal yang sama
        $cek = Absensi::whereDate(
            'tanggal',
            $request->tanggal
        )
            ->whereHas('jadwal', function ($query) use ($jadwal) {
                $query->where(
                    'kelas_id',
                    $jadwal->kelas_id
                );
            })
            ->first();

        if ($cek) {
            return back()->with(
                'error',
                'Absensi pada tanggal tersebut sudah ada.'
            );
        }

        $absensi = Absensi::create([
            'jadwal_pelajaran_id' => $request->jadwal_pelajaran_id,
            'tanggal' => $request->tanggal,
        ]);

        foreach ($request->status as $studentId => $status) {

            AbsensiDetail::create([
                'absensi_id' => $absensi->id,
                'student_id' => $studentId,
                'status' => $status,
            ]);
        }

        return redirect()
            ->route('absensi.index')
            ->with(
                'success',
                'Absensi berhasil disimpan.'
            );
    }
    public function riwayat()
    {
        $absensis = Absensi::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.guru'
        ])
            ->latest()
            ->get();

        return view(
            'admin.absensi.riwayat',
            compact('absensis')
        );
    }
    public function detail($id)
    {
        $absensi = Absensi::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'details.student'
        ])->findOrFail($id);

        return view(
            'admin.absensi.detail',
            compact('absensi')
        );
    }
    public function edit($id)
    {
        $absensi = Absensi::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'details.student'
        ])->findOrFail($id);

        return view(
            'admin.absensi.edit',
            compact('absensi')
        );
    }
    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        foreach ($request->status as $detailId => $status) {

            AbsensiDetail::where(
                'id',
                $detailId
            )->update([
                'status' => $status
            ]);
        }

        return redirect()
            ->route('absensi.riwayat')
            ->with(
                'success',
                'Absensi berhasil diperbarui'
            );
    }
    public function rekap(Request $request)
    {
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();

        $siswas = Student::with('kelas');

        if ($request->kelas_id) {
            $siswas->where('kelas_id', $request->kelas_id);
        }

        $siswas = $siswas->orderBy('nama')->get();

        $bulan = $request->bulan;

        return view(
            'admin.absensi.rekap',
            compact(
                'siswas',
                'kelas',
                'bulan'
            )
        );
    }
    public function rekapPdf(Request $request)
    {
        $siswas = Student::with('kelas');

        if ($request->kelas_id) {
            $siswas->where('kelas_id', $request->kelas_id);
        }

        $siswas = $siswas
            ->orderBy('nama')
            ->get();

        $bulan = $request->bulan;

        $kelas = null;

        if ($request->kelas_id) {
            $kelas = \App\Models\Kelas::find($request->kelas_id);
        }

        $semester = 'Genap'; // sementara manual

        $tahunAjaran = '2025 / 2026'; // sementara manual

        $pdf = Pdf::loadView(
            'admin.absensi.rekap-pdf',
            compact(
                'siswas',
                'bulan',
                'kelas',
                'semester',
                'tahunAjaran'
            )
        );

        return $pdf->stream('rekap-absensi.pdf');
    }
}
