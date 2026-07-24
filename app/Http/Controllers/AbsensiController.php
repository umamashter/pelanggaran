<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use App\Models\AbsensiDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    private function isDateBlocked($date, TahunAjaran $tahunAktif)
    {
        if ($date->isFriday()) {
            return 'Hari Jumat adalah hari libur madrasah. Absensi siswa tidak dapat dilakukan pada hari ini.';
        }
        if ($date->isFuture()) {
            return 'Tanggal absensi tidak boleh tanggal yang belum terjadi.';
        }
        if ($tahunAktif->tanggal_mulai && $date->lt(\Carbon\Carbon::parse($tahunAktif->tanggal_mulai))) {
            return 'Tanggal absensi tidak boleh sebelum tanggal mulai tahun ajaran (' . \Carbon\Carbon::parse($tahunAktif->tanggal_mulai)->translatedFormat('d F Y') . ').';
        }
        if ($tahunAktif->tanggal_selesai && $date->gt(\Carbon\Carbon::parse($tahunAktif->tanggal_selesai))) {
            return 'Tanggal absensi tidak boleh setelah tanggal selesai tahun ajaran (' . \Carbon\Carbon::parse($tahunAktif->tanggal_selesai)->translatedFormat('d F Y') . ').';
        }
        return null;
    }

    private function getDisabledDates(TahunAjaran $tahunAktif)
    {
        $start = $tahunAktif->tanggal_mulai ? \Carbon\Carbon::parse($tahunAktif->tanggal_mulai) : now()->startOfYear();
        $end = $tahunAktif->tanggal_selesai ? \Carbon\Carbon::parse($tahunAktif->tanggal_selesai) : now()->endOfYear();
        $today = now()->startOfDay();
        $disabled = [];
        $d = $start->copy();
        while ($d->lte($end)) {
            if ($d->isFriday() || $d->gt($today)) {
                $disabled[] = $d->format('Y-m-d');
            }
            $d->addDay();
        }
        return $disabled;
    }

    private function countEffectiveDays($startDate, $endDate, TahunAjaran $tahunAktif)
    {
        $taStart = $tahunAktif->tanggal_mulai ? \Carbon\Carbon::parse($tahunAktif->tanggal_mulai) : null;
        $taEnd = $tahunAktif->tanggal_selesai ? \Carbon\Carbon::parse($tahunAktif->tanggal_selesai) : null;
        $effectiveStart = $startDate->copy();
        if ($taStart && $effectiveStart->lt($taStart)) {
            $effectiveStart = $taStart->copy();
        }
        $effectiveEnd = $endDate->copy();
        if ($taEnd && $effectiveEnd->gt($taEnd)) {
            $effectiveEnd = $taEnd->copy();
        }
        $count = 0;
        $d = $effectiveStart->copy();
        while ($d->lte($effectiveEnd)) {
            if (!$d->isFriday()) {
                $count++;
            }
            $d->addDay();
        }
        return $count;
    }

    public function index()
    {
        $tahunAktif = TahunAjaran::with('semesterAktif')
            ->where('status', 'Aktif')
            ->firstOrFail();

        $isJumat = now()->isFriday();

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
            'tahunAktif',
            'isJumat'
        ));
    }

    public function create(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        $disabledDates = $this->getDisabledDates($tahunAktif);

        if (!$request->filled('kelas_id') || !$request->filled('tanggal')) {
            $existingAbsensi = null;
            return view('admin.absensi.create', compact('tahunAktif', 'kelasList', 'existingAbsensi', 'disabledDates'));
        }

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
        ]);

        $tanggalAbsensi = \Carbon\Carbon::parse($request->tanggal);

        $blocked = $this->isDateBlocked($tanggalAbsensi, $tahunAktif);
        if ($blocked) {
            return back()->withInput()->with('error', $blocked);
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
            'existingAbsensi',
            'disabledDates'
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

        $blocked = $this->isDateBlocked(\Carbon\Carbon::parse($request->tanggal), $tahunAktif);
        if ($blocked) {
            return back()->withInput()->with('error', $blocked);
        }

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
        $detailMeta = [];
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

            $fridaySet = [];
            for ($d = 1; $d <= $hariDalamBulan; $d++) {
                $tgl = $tanggalAwal->copy()->day($d);
                if ($tgl->isFriday()) {
                    $fridaySet[$tgl->format('Y-m-d')] = true;
                }
            }

            foreach ($absensis as $absensi) {
                $tgl = $absensi->tanggal->format('Y-m-d');
                $userName = $absensi->user?->name ?? '-';

                foreach ($absensi->details as $detail) {
                    $studentId = $detail->student_id;
                    $matrixData[$studentId][$tgl] = $detail->status;
                    $detailMeta[$studentId][$tgl] = [
                        'absensi_id' => $absensi->id,
                        'user_name' => $userName,
                        'created_at' => $absensi->created_at->translatedFormat('d F Y, H:i'),
                        'keterangan' => $detail->keterangan ?? '-',
                    ];
                }
            }

            foreach ($siswas as $siswa) {
                $rekap = ['A' => 0, 'I' => 0, 'S' => 0];
                for ($d = 1; $d <= $hariDalamBulan; $d++) {
                    $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                    if (isset($fridaySet[$tgl])) continue;
                    $status = $matrixData[$siswa->id][$tgl] ?? null;
                    if ($status === 'A') $rekap['A']++;
                    elseif ($status === 'I') $rekap['I']++;
                    elseif ($status === 'S') $rekap['S']++;
                }
                $matrixData[$siswa->id]['_rekap'] = $rekap;
            }
        }

        $fridaySet = $fridaySet ?? [];

        return view('admin.absensi.riwayat', compact(
            'kelas',
            'kelasList',
            'siswas',
            'matrixData',
            'detailMeta',
            'tahunAktif',
            'bulan',
            'selectedKelasId',
            'fridaySet'
        ));
    }

    public function detail($id)
    {
        $absensi = Absensi::with(['details.student', 'kelas', 'user', 'tahunAjaran'])
            ->findOrFail($id);

        return view('admin.absensi.detail', compact('absensi'));
    }

    public function edit(Request $request, $id)
    {
        $absensi = Absensi::with(['details.student', 'kelas', 'tahunAjaran'])
            ->findOrFail($id);

        if ($absensi->tanggal->isFriday()) {
            return redirect()->route('absensi.index')
                ->with('error', 'Hari Jumat adalah hari libur madrasah. Absensi siswa tidak dapat diedit pada hari ini.');
        }

        if ($absensi->tanggal->isFuture()) {
            return redirect()->route('absensi.index')
                ->with('error', 'Tanggal absensi tidak boleh tanggal yang belum terjadi.');
        }

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($absensi) {
            $q->where('kelas_id', $absensi->kelas_id)
              ->where('tahun_ajaran_id', $absensi->tahun_ajaran_id);
        })->orderBy('nama')->get();

        $singleSiswaId = $request->input('siswa');
        if ($singleSiswaId) {
            $siswas = $siswas->filter(fn($s) => $s->id == $singleSiswaId)->values();
        }

        $detailMap = $absensi->details->pluck('status', 'student_id');
        $keteranganMap = $absensi->details->pluck('keterangan', 'student_id');

        return view('admin.absensi.edit', compact(
            'absensi',
            'siswas',
            'detailMap',
            'keteranganMap',
            'singleSiswaId'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|in:H,I,S,A',
        ]);

        $absensi = Absensi::findOrFail($id);
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $blocked = $this->isDateBlocked($absensi->tanggal, $tahunAktif);
        if ($blocked) {
            return back()->with('error', $blocked);
        }

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

        $fridaySet = [];
        for ($d = 1; $d <= $hariDalamBulan; $d++) {
            $tgl = $tanggalAwal->copy()->day($d);
            if ($tgl->isFriday()) {
                $fridaySet[$tgl->format('Y-m-d')] = true;
            }
        }

        foreach ($absensis as $absensi) {
            $tgl = $absensi->tanggal->format('Y-m-d');

            foreach ($absensi->details as $detail) {
                $studentId = $detail->student_id;
                $matrixData[$studentId][$tgl] = $detail->status;
            }
        }

        foreach ($siswas as $siswa) {
            $rekap = ['A' => 0, 'I' => 0, 'S' => 0];
            for ($d = 1; $d <= $hariDalamBulan; $d++) {
                $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                if (isset($fridaySet[$tgl])) continue;
                $status = $matrixData[$siswa->id][$tgl] ?? null;
                if ($status === 'A') $rekap['A']++;
                elseif ($status === 'I') $rekap['I']++;
                elseif ($status === 'S') $rekap['S']++;
            }
            $matrixData[$siswa->id]['_rekap'] = $rekap;
        }

        $bulanLabel = $tanggalAwal->translatedFormat('F Y');

        $waliKelasName = 'Mahbubah, S.Pd';
        $waliKelas = $kelas->waliKelas;
        if ($waliKelas && $waliKelas->guru && $waliKelas->guru->user_id) {
            $user = \App\Models\User::find($waliKelas->guru->user_id);
            if ($user) {
                $waliKelasName = $user->name;
            }
        }

        $pdf = Pdf::loadView('admin.absensi.riwayat-pdf', compact(
            'kelas',
            'siswas',
            'matrixData',
            'tahunAktif',
            'bulanLabel',
            'hariDalamBulan',
            'tanggalAwal',
            'fridaySet',
            'waliKelasName'
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
        $effectiveDays = 0;

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

            if ($tanggalAwal && $tanggalAkhir) {
                $effectiveDays = $this->countEffectiveDays(
                    \Carbon\Carbon::parse($tanggalAwal),
                    \Carbon\Carbon::parse($tanggalAkhir),
                    $tahunAktif
                );
            } elseif ($bulan) {
                $bulanStart = \Carbon\Carbon::parse($bulan . '-01');
                $effectiveDays = $this->countEffectiveDays(
                    $bulanStart->copy()->startOfMonth(),
                    $bulanStart->copy()->endOfMonth(),
                    $tahunAktif
                );
            }
        }

        return view('admin.absensi.rekap', compact(
            'kelas',
            'kelasList',
            'rekapData',
            'tahunAktif',
            'bulan',
            'tanggalAwal',
            'tanggalAkhir',
            'effectiveDays'
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

        $effectiveDays = 0;
        if ($tanggalAwal && $tanggalAkhir) {
            $effectiveDays = $this->countEffectiveDays(
                \Carbon\Carbon::parse($tanggalAwal),
                \Carbon\Carbon::parse($tanggalAkhir),
                $tahunAktif
            );
        } elseif ($bulan) {
            $bulanStart = \Carbon\Carbon::parse($bulan . '-01');
            $effectiveDays = $this->countEffectiveDays(
                $bulanStart->copy()->startOfMonth(),
                $bulanStart->copy()->endOfMonth(),
                $tahunAktif
            );
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
            'periodeLabel',
            'effectiveDays'
        ));

        return $pdf->stream('rekap-absensi-' . $kelas->nama_kelas . '.pdf');
    }
}
