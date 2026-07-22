<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\StudentKelas;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use App\Models\AbsensiDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::with('semesterAktif')
            ->where('status', 'Aktif')
            ->firstOrFail();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        $absensiHariIni = Absensi::where('tanggal', now()->toDateString())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->pluck('kelas_id')
            ->toArray();

        return view('admin.absensi.index', compact(
            'kelasList',
            'absensiHariIni',
            'tahunAktif'
        ));
    }

    public function create(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $kelas = Kelas::findOrFail($request->kelas_id);

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($request, $tahunAktif) {
            $q->where('kelas_id', $request->kelas_id)
              ->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama')->get();

        $existingAbsensi = Absensi::where('kelas_id', $request->kelas_id)
            ->where('tanggal', $request->tanggal)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->with('details')
            ->first();

        return view('admin.absensi.create', compact(
            'kelas',
            'siswas',
            'tahunAktif',
            'existingAbsensi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'required|in:H,I,S,A',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        DB::beginTransaction();

        try {
            $absensi = Absensi::updateOrCreate(
                [
                    'kelas_id' => $request->kelas_id,
                    'tanggal' => $request->tanggal,
                    'tahun_ajaran_id' => $tahunAktif->id,
                ],
                [
                    'user_id' => Auth::id(),
                ]
            );

            foreach ($request->status as $studentId => $status) {
                AbsensiDetail::updateOrCreate(
                    [
                        'absensi_id' => $absensi->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'status' => $status,
                        'keterangan' => $request->input("keterangan.{$studentId}"),
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('absensi.index')
                ->with('success', 'Absensi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    public function riwayat(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $absensis = Absensi::with(['kelas', 'user'])
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->when($request->kelas_id, function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            })
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        return view('admin.absensi.riwayat', compact('absensis', 'kelasList', 'tahunAktif'));
    }

    public function detail($id)
    {
        $absensi = Absensi::with(['details.student', 'kelas', 'user', 'tahunAjaran'])
            ->findOrFail($id);

        return view('admin.absensi.detail', compact('absensi'));
    }

    public function edit($id)
    {
        $absensi = Absensi::with(['details.student', 'kelas', 'tahunAjaran'])
            ->findOrFail($id);

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($absensi) {
            $q->where('kelas_id', $absensi->kelas_id);
        })->orderBy('nama')->get();

        $detailMap = $absensi->details->pluck('status', 'student_id');
        $keteranganMap = $absensi->details->pluck('keterangan', 'student_id');

        return view('admin.absensi.edit', compact(
            'absensi',
            'siswas',
            'detailMap',
            'keteranganMap'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|in:H,I,S,A',
        ]);

        $absensi = Absensi::findOrFail($id);

        DB::beginTransaction();

        try {
            $absensi->update(['user_id' => Auth::id()]);

            foreach ($request->status as $studentId => $status) {
                AbsensiDetail::updateOrCreate(
                    [
                        'absensi_id' => $absensi->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'status' => $status,
                        'keterangan' => $request->input("keterangan.{$studentId}"),
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('absensi.riwayat')
                ->with('success', 'Absensi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }
    }

    public function rekap(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $kelas = Kelas::findOrFail($request->kelas_id);

        $bulan = $request->input('bulan');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($kelas, $tahunAktif) {
            $q->where('kelas_id', $kelas->id)
              ->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama')->get();

        $rekapData = [];
        foreach ($siswas as $siswa) {
            $query = AbsensiDetail::where('student_id', $siswa->id)
                ->whereHas('absensi', function ($q) use ($kelas, $tahunAktif) {
                    $q->where('kelas_id', $kelas->id)
                      ->where('tahun_ajaran_id', $tahunAktif->id);
                });

            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereHas('absensi', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                    $q->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
                });
            } elseif ($bulan) {
                $query->whereHas('absensi', function ($q) use ($bulan) {
                    $q->whereYear('tanggal', date('Y', strtotime($bulan)))
                      ->whereMonth('tanggal', date('m', strtotime($bulan)));
                });
            }

            $details = $query->get();

            $rekapData[$siswa->id] = [
                'siswa' => $siswa,
                'hadir' => $details->where('status', 'H')->count(),
                'izin' => $details->where('status', 'I')->count(),
                'sakit' => $details->where('status', 'S')->count(),
                'alpa' => $details->where('status', 'A')->count(),
                'total' => $details->count(),
            ];
        }

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        return view('admin.absensi.rekap', compact(
            'kelas',
            'kelasList',
            'rekapData',
            'tahunAktif',
            'bulan',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }

    public function rekapPdf(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $kelas = Kelas::findOrFail($request->kelas_id);

        $bulan = $request->input('bulan');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($kelas, $tahunAktif) {
            $q->where('kelas_id', $kelas->id)
              ->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama')->get();

        $rekapData = [];
        foreach ($siswas as $siswa) {
            $query = AbsensiDetail::where('student_id', $siswa->id)
                ->whereHas('absensi', function ($q) use ($kelas, $tahunAktif) {
                    $q->where('kelas_id', $kelas->id)
                      ->where('tahun_ajaran_id', $tahunAktif->id);
                });

            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereHas('absensi', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                    $q->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
                });
            } elseif ($bulan) {
                $query->whereHas('absensi', function ($q) use ($bulan) {
                    $q->whereYear('tanggal', date('Y', strtotime($bulan)))
                      ->whereMonth('tanggal', date('m', strtotime($bulan)));
                });
            }

            $details = $query->get();

            $rekapData[$siswa->id] = [
                'siswa' => $siswa,
                'hadir' => $details->where('status', 'H')->count(),
                'izin' => $details->where('status', 'I')->count(),
                'sakit' => $details->where('status', 'S')->count(),
                'alpa' => $details->where('status', 'A')->count(),
                'total' => $details->count(),
            ];
        }

        $periodeLabel = 'Semua Bulan';
        if ($tanggalAwal && $tanggalAkhir) {
            $periodeLabel = \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y')
                . ' - ' . \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y');
        } elseif ($bulan) {
            $periodeLabel = \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y');
        }

        $pdf = Pdf::loadView('admin.absensi.rekap-pdf', compact(
            'kelas',
            'siswas',
            'rekapData',
            'tahunAktif',
            'periodeLabel'
        ));

        return $pdf->stream('rekap-absensi-' . $kelas->nama_kelas . '.pdf');
    }
}
