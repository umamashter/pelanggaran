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

        $absensiMap = Absensi::where('tanggal', now()->toDateString())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->pluck('id', 'kelas_id')
            ->toArray();

        return view('admin.absensi.index', compact(
            'kelasList',
            'absensiHariIni',
            'absensiMap',
            'tahunAktif'
        ));
    }

    public function create(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        if (!$request->filled('kelas_id') || !$request->filled('tanggal')) {
            $existingAbsensi = null;
            return view('admin.absensi.create', compact('tahunAktif', 'kelasList', 'existingAbsensi'));
        }

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
        ]);

        $tanggalAbsensi = \Carbon\Carbon::parse($request->tanggal);

        if ($tahunAktif->tanggal_mulai && $tanggalAbsensi->lt(\Carbon\Carbon::parse($tahunAktif->tanggal_mulai))) {
            return back()->withInput()->with('error', 'Tanggal absensi tidak boleh sebelum tanggal mulai tahun ajaran (' . \Carbon\Carbon::parse($tahunAktif->tanggal_mulai)->translatedFormat('d F Y') . ').');
        }
        if ($tahunAktif->tanggal_selesai && $tanggalAbsensi->gt(\Carbon\Carbon::parse($tahunAktif->tanggal_selesai))) {
            return back()->withInput()->with('error', 'Tanggal absensi tidak boleh setelah tanggal selesai tahun ajaran (' . \Carbon\Carbon::parse($tahunAktif->tanggal_selesai)->translatedFormat('d F Y') . ').');
        }

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
            'kelasList',
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

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        $kelas = null;
        $siswas = collect();
        $matrixData = [];
        $absensiByTanggal = [];
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $selectedKelasId = $request->input('kelas_id');

        if ($selectedKelasId) {
            $kelas = Kelas::findOrFail($selectedKelasId);

            $siswas = Student::whereHas('kelasAktif', function ($q) use ($kelas, $tahunAktif) {
                $q->where('kelas_id', $kelas->id)
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })->orderBy('nama')->get();

            $tanggalAwal = \Carbon\Carbon::parse($bulan . '-01');
            $hariDalamBulan = $tanggalAwal->daysInMonth;
            $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

            $absensis = Absensi::with(['details.student', 'user'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->whereBetween('tanggal', [$tanggalAwal->toDateString(), $tanggalAkhir->toDateString()])
                ->get();

            foreach ($absensis as $absensi) {
                $tgl = $absensi->tanggal->format('Y-m-d');
                $userId = $absensi->user_id;
                $userName = $absensi->user?->name ?? '-';

                foreach ($absensi->details as $detail) {
                    $studentId = $detail->student_id;
                    $matrixData[$studentId][$tgl] = $detail->status;

                    if (!isset($absensiByTanggal[$tgl])) {
                        $absensiByTanggal[$tgl] = [];
                    }
                    if (!isset($absensiByTanggal[$tgl][$studentId])) {
                        $absensiByTanggal[$tgl][$studentId] = [];
                    }
                    $absensiByTanggal[$tgl][$studentId][] = $userName;
                }
            }

            foreach ($siswas as $siswa) {
                $rekap = ['A' => 0, 'I' => 0, 'S' => 0];
                $pencatat = [];
                for ($d = 1; $d <= $hariDalamBulan; $d++) {
                    $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                    $status = $matrixData[$siswa->id][$tgl] ?? null;
                    if ($status === 'A') $rekap['A']++;
                    elseif ($status === 'I') $rekap['I']++;
                    elseif ($status === 'S') $rekap['S']++;

                    if (isset($absensiByTanggal[$tgl][$siswa->id])) {
                        foreach ($absensiByTanggal[$tgl][$siswa->id] as $name) {
                            if (!in_array($name, $pencatat)) {
                                $pencatat[] = $name;
                            }
                        }
                    }
                }
                $matrixData[$siswa->id]['_rekap'] = $rekap;
                $matrixData[$siswa->id]['_pencatat'] = $pencatat;
            }
        }

        return view('admin.absensi.riwayat', compact(
            'kelas',
            'kelasList',
            'siswas',
            'matrixData',
            'tahunAktif',
            'bulan',
            'selectedKelasId'
        ));
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
            $q->where('kelas_id', $absensi->kelas_id)
              ->where('tahun_ajaran_id', $absensi->tahun_ajaran_id);
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
                ->route('absensi.edit', $id)
                ->with('success', 'Absensi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }
    }

    public function riwayatPdf(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'bulan' => 'required',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $kelas = Kelas::findOrFail($request->kelas_id);
        $bulan = $request->input('bulan');

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($kelas, $tahunAktif) {
            $q->where('kelas_id', $kelas->id)
              ->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama')->get();

        $tanggalAwal = \Carbon\Carbon::parse($bulan . '-01');
        $hariDalamBulan = $tanggalAwal->daysInMonth;
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $absensis = Absensi::with(['details.student', 'user'])
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('tanggal', [$tanggalAwal->toDateString(), $tanggalAkhir->toDateString()])
            ->get();

        $matrixData = [];
        $absensiByTanggal = [];

        foreach ($absensis as $absensi) {
            $tgl = $absensi->tanggal->format('Y-m-d');
            $userName = $absensi->user?->name ?? '-';

            foreach ($absensi->details as $detail) {
                $studentId = $detail->student_id;
                $matrixData[$studentId][$tgl] = $detail->status;

                if (!isset($absensiByTanggal[$tgl])) {
                    $absensiByTanggal[$tgl] = [];
                }
                if (!isset($absensiByTanggal[$tgl][$studentId])) {
                    $absensiByTanggal[$tgl][$studentId] = [];
                }
                $absensiByTanggal[$tgl][$studentId][] = $userName;
            }
        }

        foreach ($siswas as $siswa) {
            $rekap = ['A' => 0, 'I' => 0, 'S' => 0];
            $pencatat = [];
            for ($d = 1; $d <= $hariDalamBulan; $d++) {
                $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                $status = $matrixData[$siswa->id][$tgl] ?? null;
                if ($status === 'A') $rekap['A']++;
                elseif ($status === 'I') $rekap['I']++;
                elseif ($status === 'S') $rekap['S']++;

                if (isset($absensiByTanggal[$tgl][$siswa->id])) {
                    foreach ($absensiByTanggal[$tgl][$siswa->id] as $name) {
                        if (!in_array($name, $pencatat)) {
                            $pencatat[] = $name;
                        }
                    }
                }
            }
            $matrixData[$siswa->id]['_rekap'] = $rekap;
            $matrixData[$siswa->id]['_pencatat'] = $pencatat;
        }

        $bulanLabel = $tanggalAwal->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.absensi.riwayat-pdf', compact(
            'kelas',
            'siswas',
            'matrixData',
            'tahunAktif',
            'bulanLabel',
            'hariDalamBulan',
            'tanggalAwal'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('rekap-absensi-' . $kelas->nama_kelas . '-' . $bulan . '.pdf');
    }

    public function rekap(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        $kelas = null;
        $siswas = collect();
        $rekapData = [];
        $bulan = $request->input('bulan');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if ($request->filled('kelas_id')) {
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
            ]);

            $kelas = Kelas::findOrFail($request->kelas_id);

            $siswas = Student::whereHas('kelasAktif', function ($q) use ($kelas, $tahunAktif) {
                $q->where('kelas_id', $kelas->id)
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })->orderBy('nama')->get();

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
        }

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
