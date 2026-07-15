<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PesertaLomba;
use App\Models\Lomba;
use App\Models\Student;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\KelompokLomba;
use App\Models\HaflatulImtihan;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class PesertaLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = PesertaLomba::with([
            'lomba.sesiLomba',
            'student.user',
            'student.kelasAktif.kelas.jenjang',
        ])->withCount(['penilaian', 'hasil'])
          ->withoutGlobalScope(HaflahScope::class)
          ->whereNotNull('student_id')
          ->whereHas('lomba', function ($q) {
              $q->withoutGlobalScope(HaflahScope::class)
                ->whereNull('jenis')->orWhere('jenis', '!=', 'Tim');
          });

        if ($request->filled('haflah_id')) {
            $query->where('haflah_id', $request->haflah_id);
        } elseif (session('haflah_id')) {
            $query->where('haflah_id', session('haflah_id'));
        }

        if ($request->filled('lomba_id')) {
            $query->where('lomba_id', $request->lomba_id);
        }
        if ($request->filled('sesi_id')) {
            $query->whereHas('lomba', function ($q) use ($request) {
                $q->withoutGlobalScope(HaflahScope::class)
                  ->where('sesi_lomba_id', $request->sesi_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('nama')) {
            $nama = $request->nama;
            $query->whereHas('student.user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('lomba', function ($q) use ($search) {
                    $q->withoutGlobalScope(HaflahScope::class)
                      ->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $perPage = $request->input('per_page', 10);
        $pesertaLombas = $query->latest()->paginate($perPage)->withQueryString();

        $selectedHaflah = $request->filled('haflah_id') ? $request->haflah_id : session('haflah_id');

        $lombas = Lomba::withoutGlobalScope(HaflahScope::class)
            ->where(function ($q) {
                $q->whereNull('jenis')->orWhere('jenis', '!=', 'Tim');
            })->whereHas('peserta', function ($q) use ($selectedHaflah) {
                $q->withoutGlobalScope(HaflahScope::class)
                  ->whereNotNull('student_id');
                if ($selectedHaflah) {
                    $q->where('haflah_id', $selectedHaflah);
                }
            })->orderBy('nama')->get();
        $sesiLombas = \App\Models\SesiLomba::whereHas('lombas', function ($q) use ($selectedHaflah) {
            $q->withoutGlobalScope(HaflahScope::class)
              ->where(function ($q2) {
                $q2->whereNull('jenis')->orWhere('jenis', '!=', 'Tim');
            })->whereHas('peserta', function ($q3) use ($selectedHaflah) {
                $q3->withoutGlobalScope(HaflahScope::class)
                   ->whereNotNull('student_id');
                if ($selectedHaflah) {
                    $q3->where('haflah_id', $selectedHaflah);
                }
            });
        })->orderBy('nama')->get();
        $haflatuls = HaflatulImtihan::orderBy('nama_acara')->get();

        return view('admin.peserta-lomba.index', compact('pesertaLombas', 'lombas', 'sesiLombas', 'haflatuls'));
    }

    public function cetakPdf(Request $request)
    {
        $query = PesertaLomba::with([
            'lomba.sesiLomba',
            'student.user',
            'student.kelasAktif.kelas.jenjang',
        ])->withoutGlobalScope(HaflahScope::class)
          ->whereNotNull('student_id')
          ->whereHas('lomba', function ($q) {
              $q->withoutGlobalScope(HaflahScope::class)
                ->whereNull('jenis')->orWhere('jenis', '!=', 'Tim');
          });

        if ($request->filled('haflah_id')) {
            $query->where('haflah_id', $request->haflah_id);
        } elseif (session('haflah_id')) {
            $query->where('haflah_id', session('haflah_id'));
        }

        if ($request->filled('lomba_id')) {
            $query->where('lomba_id', $request->lomba_id);
        }
        if ($request->filled('sesi_id')) {
            $query->whereHas('lomba', function ($q) use ($request) {
                $q->withoutGlobalScope(HaflahScope::class)
                  ->where('sesi_lomba_id', $request->sesi_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('nama')) {
            $nama = $request->nama;
            $query->whereHas('student.user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('lomba', function ($q) use ($search) {
                    $q->withoutGlobalScope(HaflahScope::class)
                      ->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $pesertas = $query->get()->sort(function ($a, $b) {
            $sesiA = optional(optional($a->lomba)->sesiLomba);
            $sesiB = optional(optional($b->lomba)->sesiLomba);

            $cmp = ((int) ($sesiA->urutan ?? PHP_INT_MAX)) <=> ((int) ($sesiB->urutan ?? PHP_INT_MAX));
            if ($cmp !== 0) {
                return $cmp;
            }

            $cmp = strcmp((string) ($sesiA->tanggal ?? ''), (string) ($sesiB->tanggal ?? ''));
            if ($cmp !== 0) {
                return $cmp;
            }

            $cmp = strcmp((string) ($sesiA->jam_mulai ?? ''), (string) ($sesiB->jam_mulai ?? ''));
            if ($cmp !== 0) {
                return $cmp;
            }

            $cmp = strcmp((string) ($sesiA->nama ?? ''), (string) ($sesiB->nama ?? ''));
            if ($cmp !== 0) {
                return $cmp;
            }

            $lombaA = optional($a->lomba);
            $lombaB = optional($b->lomba);

            $cmp = strcmp((string) ($lombaA->nama ?? ''), (string) ($lombaB->nama ?? ''));
            if ($cmp !== 0) {
                return $cmp;
            }

            $namaA = $a->isIndividu()
                ? optional(optional($a->student)->user)->name ?? ''
                : optional($a->kelompokLomba)->nama_kelompok ?? '';
            $namaB = $b->isIndividu()
                ? optional(optional($b->student)->user)->name ?? ''
                : optional($b->kelompokLomba)->nama_kelompok ?? '';

            return strcmp($namaA, $namaB);
        })->values();

        $haflah = null;
        if (session()->has('haflah_id')) {
            $haflah = HaflatulImtihan::with('tahunAjaran.semesterAktif')->find(session('haflah_id'));
        }

        if (!$haflah && $pesertas->isNotEmpty()) {
            $haflah = $pesertas->first()->haflatulImtihan()->with('tahunAjaran.semesterAktif')->first();
        }

        $tahunAjaran = optional(optional($haflah)->tahunAjaran)->tahun_ajaran;
        $semester = optional(optional(optional($haflah)->tahunAjaran)->semesterAktif)->nama;

        $jadwal = $pesertas->groupBy(function ($item) {
            return optional(optional($item->lomba)->sesiLomba)->id ?: 'tanpa-sesi';
        })->map(function ($sesiItems) {
            $firstPeserta = $sesiItems->first();
            $sesi = optional(optional($firstPeserta->lomba)->sesiLomba);

            $lombas = $sesiItems->groupBy(function ($item) {
                return optional($item->lomba)->id ?: 'tanpa-lomba';
            })->map(function ($lombaItems) {
                $firstLombaPeserta = $lombaItems->first();
                $lomba = optional($firstLombaPeserta->lomba);

                return [
                    'nama' => $lomba->nama ?? '-',
                    'rowspan' => $lombaItems->count(),
                    'peserta' => $lombaItems->values(),
                ];
            })->values();

            return [
                'nama' => $sesi->nama ?? '-',
                'tanggal' => !empty($sesi->tanggal)
                    ? \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d F Y')
                    : '-',
                'rowspan' => $sesiItems->count(),
                'lombas' => $lombas,
            ];
        })->values();

        $pdf = Pdf::loadView('admin.peserta-lomba.pdf', compact('jadwal', 'haflah', 'tahunAjaran', 'semester'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('daftar-peserta-lomba.pdf');
    }

    public function massal(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($redirect = $this->blockStoreIfHaflahSelesai()) {
                return $redirect;
            }

            $request->validate([
                'lomba_id' => 'required|exists:lombas,id',
                'student_ids' => 'required|array|min:1',
                'student_ids.*' => 'exists:students,id',
            ]);

            $lomba = Lomba::find($request->lomba_id);
            if ($lomba && $lomba->jenis === 'Tim') {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['lomba_id' => 'Tambah massal hanya untuk lomba individu.']);
            }

            $sudahTerdaftar = PesertaLomba::where('lomba_id', $request->lomba_id)
                ->whereIn('student_id', $request->student_ids)
                ->pluck('student_id');

            $baru = collect($request->student_ids)->diff($sudahTerdaftar);

            if ($baru->isEmpty()) {
                return redirect()->route('peserta-lomba.index')
                    ->with('info', 'Siswa yang dipilih sudah terdaftar semua.');
            }

            $data = [];
            $haflahId = session('haflah_id');
            foreach ($baru as $studentId) {
                $data[] = [
                    'lomba_id' => $request->lomba_id,
                    'student_id' => $studentId,
                    'haflah_id' => $haflahId,
                    'status' => 'Terdaftar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PesertaLomba::insert($data);

            return redirect()->route('peserta-lomba.index')
                ->with('success', count($baru) . ' peserta berhasil ditambahkan.');
        }

        $lombas = Lomba::whereNull('jenis')->orWhere('jenis', '!=', 'Tim')->orderBy('nama')->get();
        $jenjangs = Jenjang::orderBy('nama_jenjang')->get();
        return view('admin.peserta-lomba.massal', compact('lombas', 'jenjangs'));
    }

    public function create()
    {
        $lombas = Lomba::orderBy('nama')->get();
        $jenjangs = Jenjang::orderBy('nama_jenjang')->get();
        $kelompokLombas = collect();

        return view('admin.peserta-lomba.create', compact('lombas', 'jenjangs', 'kelompokLombas'));
    }

    public function getKelas($jenjangId)
    {
        $kelas = Kelas::where('jenjang_id', $jenjangId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas', 'tingkat']);

        return response()->json($kelas);
    }

    public function getSiswa(Request $request, $kelasId)
    {
        $query = Student::whereHas('kelasAktif', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        })->with('user')->orderBy('nisn');

        $totalSiswa = $query->count();

        $sudahTerdaftarIds = collect([]);
        if ($request->filled('lomba_id')) {
            $studentIds = $query->pluck('id');
            $sudahTerdaftarIds = PesertaLomba::where('lomba_id', $request->lomba_id)
                ->whereIn('student_id', $studentIds)
                ->pluck('student_id');
            $query->whereNotIn('id', $sudahTerdaftarIds);
        }

        $siswa = $query->get(['id', 'nisn', 'nama', 'user_id']);

        return response()->json([
            'siswa' => $siswa,
            'total_siswa' => $totalSiswa,
            'sudah_terdaftar' => $sudahTerdaftarIds->count(),
            'eligible' => $siswa->count(),
        ]);
    }

    public function getKelompok($lombaId)
    {
        $sudahTerdaftarIds = PesertaLomba::where('lomba_id', $lombaId)
            ->whereNotNull('kelompok_lomba_id')
            ->pluck('kelompok_lomba_id');

        $kelompok = KelompokLomba::where('lomba_id', $lombaId)
            ->whereNotIn('id', $sudahTerdaftarIds)
            ->orderBy('nama_kelompok')
            ->get(['id', 'nama_kelompok', 'kode_kelompok'])
            ->map(function ($k) {
                return [
                    'id'   => $k->id,
                    'text' => ($k->kode_kelompok ? $k->kode_kelompok . ' - ' : '') . $k->nama_kelompok,
                ];
            });

        return response()->json($kelompok);
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $lomba = Lomba::findOrFail($request->lomba_id);

        $rules = [
            'lomba_id' => 'required|exists:lombas,id',
            'status' => 'required|in:Terdaftar,Hadir,Tidak Hadir,Diskualifikasi',
        ];

        if ($lomba->jenis === 'Tim') {
            $rules['kelompok_lomba_id'] = 'required|exists:kelompok_lombas,id';
        } else {
            $rules['student_id'] = 'required|exists:students,id';
        }

        $request->validate($rules);

        if ($lomba->jenis === 'Tim') {
            $exists = PesertaLomba::where('lomba_id', $request->lomba_id)
                ->where('kelompok_lomba_id', $request->kelompok_lomba_id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['kelompok_lomba_id' => 'Kelompok ini sudah terdaftar di lomba yang dipilih.']);
            }
        } else {
            $exists = PesertaLomba::where('lomba_id', $request->lomba_id)
                ->where('student_id', $request->student_id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['student_id' => 'Siswa ini sudah terdaftar di lomba yang dipilih.']);
            }
        }

        $maxUrut = PesertaLomba::where('lomba_id', $request->lomba_id)->max('nomor_urut') ?? 0;

        PesertaLomba::create([
            'lomba_id'          => $request->lomba_id,
            'student_id'        => $lomba->jenis === 'Tim' ? null : $request->student_id,
            'kelompok_lomba_id' => $lomba->jenis === 'Tim' ? $request->kelompok_lomba_id : null,
            'nomor_urut'        => $maxUrut + 1,
            'status'            => $request->status,
            'haflah_id'         => session('haflah_id'),
        ]);

        return redirect()->route('peserta-lomba.index')
            ->with('success', 'Peserta lomba berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pesertaLomba = PesertaLomba::with([
            'lomba',
            'student.user',
            'student.kelasAktif.kelas.jenjang',
            'kelompokLomba.anggota.student.user',
            'penilaian',
            'hasil',
        ])->findOrFail($id);

        return view('admin.peserta-lomba.show', compact('pesertaLomba'));
    }

    public function edit($id)
    {
        $pesertaLomba = PesertaLomba::with('lomba')->findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($pesertaLomba->haflah_id)) {
            return $redirect;
        }

        $lombas = Lomba::orderBy('nama')->get();
        $students = Student::with('user')->orderBy('nisn')->get();
        $kelompokLombas = $pesertaLomba->lomba && $pesertaLomba->lomba->jenis === 'Tim'
            ? KelompokLomba::where('lomba_id', $pesertaLomba->lomba_id)->orderBy('nama_kelompok')->get()
            : collect();

        return view('admin.peserta-lomba.edit', compact('pesertaLomba', 'lombas', 'students', 'kelompokLombas'));
    }

    public function update(Request $request, $id)
    {
        $pesertaLomba = PesertaLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($pesertaLomba->haflah_id)) {
            return $redirect;
        }

        $lomba = Lomba::findOrFail($request->lomba_id ?? $pesertaLomba->lomba_id);

        $rules = [
            'lomba_id' => 'required|exists:lombas,id',
            'status' => 'required|in:Terdaftar,Hadir,Tidak Hadir,Diskualifikasi',
        ];

        if ($lomba->jenis === 'Tim') {
            $rules['kelompok_lomba_id'] = 'required|exists:kelompok_lombas,id';
        } else {
            $rules['student_id'] = 'required|exists:students,id';
        }

        $request->validate($rules);

        if ($lomba->jenis === 'Tim') {
            $exists = PesertaLomba::where('lomba_id', $request->lomba_id)
                ->where('kelompok_lomba_id', $request->kelompok_lomba_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['kelompok_lomba_id' => 'Kelompok ini sudah terdaftar di lomba yang dipilih.']);
            }
        } else {
            $exists = PesertaLomba::where('lomba_id', $request->lomba_id)
                ->where('student_id', $request->student_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['student_id' => 'Siswa ini sudah terdaftar di lomba yang dipilih.']);
            }
        }

        $data = [
            'lomba_id' => $request->lomba_id,
            'status'   => $request->status,
        ];

        if ($lomba->jenis === 'Tim') {
            $data['kelompok_lomba_id'] = $request->kelompok_lomba_id;
            $data['student_id'] = null;
        } else {
            $data['student_id'] = $request->student_id;
            $data['kelompok_lomba_id'] = null;
        }

        $pesertaLomba->update($data);

        return redirect()->route('peserta-lomba.index')
            ->with('success', 'Peserta lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pesertaLomba = PesertaLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($pesertaLomba->haflah_id)) {
            return $redirect;
        }

        $pesertaLomba->delete();

        return redirect()->route('peserta-lomba.index')
            ->with('success', 'Peserta lomba berhasil dihapus.');
    }
}
