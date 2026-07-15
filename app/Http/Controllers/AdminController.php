<?php

namespace App\Http\Controllers;

use App\Exports\MasterSiswaTemplateExport;
use App\Imports\MasterSiswaImport;
use App\Models\Semester;
use App\Models\StudentKelas;
use App\Models\Kelas;
use App\Models\History;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\TahunAjaran;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class AdminController extends Controller
{
    public function daftar_siswa()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        if (!$tahunAktif) {
            return redirect('/tahun-ajaran')->with('error', 'Belum ada tahun ajaran yang aktif. Silakan buat tahun ajaran terlebih dahulu.');
        }

        $semesterDipilih = $tahunAktif->semesterAktif;

        if (!$semesterDipilih) {
            return redirect('/semester')->with('error', 'Belum ada semester aktif. Silakan buat semester terlebih dahulu.');
        }
        $tahunAjaranId = $tahunAktif->id;
        $semesterId = $semesterDipilih?->id;

        if (request()->filled('semester_id')) {
            $semesterDipilih = Semester::find(request('semester_id'));
            $semesterId = $semesterDipilih?->id;
            $tahunAjaranId = $semesterDipilih?->tahun_ajaran_id;
        }

        $query = Student::with([
            'riwayatKelas.kelas.jenjang',
            'riwayatKelas.kelas',
            'riwayatKelas.tahunAjaran'
        ])
            ->whereHas('riwayatKelas', function ($q) use ($tahunAjaranId, $semesterId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('semester_id', $semesterId);

            });

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhereHas('riwayatKelas.kelas', fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"));
            });
        }

        $siswas = $query->latest('id')->get();

        foreach ($siswas as $siswa) {
            $siswa->riwayatDipilih = $siswa->riwayatKelas
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('semester_id', $semesterId)
                ->sortByDesc('id')
                ->first();
        }

        $filterOptions = Semester::with('tahunAjaran')
            ->get()
            ->map(fn($sem) => [
                'id' => $sem->id,
                'tahun_ajaran_id' => $sem->tahun_ajaran_id,
                'label' => $sem->tahunAjaran->tahun_ajaran . ' - Semester ' . $sem->nama,
            ])
            ->sortByDesc(fn($o) => $o['label'])
            ->values();

        $semesterAktif = $tahunAktif->semesterAktif;
        $semesterPertama = $semesterAktif && $tahunAktif->semesters()->where('id', '<', $semesterAktif->id)->doesntExist();

        $siswaPerluProses = Student::where(function ($q) use ($semesterAktif) {
            $q->whereDoesntHave('kelasAktif')
              ->orWhereHas('kelasAktif', fn($q) => $q->where('semester_id', '!=', $semesterAktif?->id));
        })->exists();

        $canPromote = $semesterPertama && $siswaPerluProses;
        $canMoveSemester = $semesterAktif && !$semesterPertama && $siswaPerluProses;
        $usersSiswa = User::where('role', 3)
            ->orderBy('name')
            ->get();
        $studentMap = Student::with(['kelasAktif.kelas', 'kelasAktif.semester'])
            ->whereIn('user_id', $usersSiswa->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $usersSiswa = $usersSiswa->map(function ($user) use ($studentMap) {
            $student = $studentMap->get($user->id);
            $user->studentProfile = $student;
            return $user;
        });

        return view('admin.page.daftar-siswa', [
            'siswas' => $siswas,
            'kelas' => Kelas::with('jenjang')->orderBy('tingkat')->get(),
            'usersSiswa' => $usersSiswa,
            'filterOptions' => $filterOptions,
            'semesterDipilih' => $semesterDipilih,
            'semesterId' => $semesterId,
            'tahunAktif' => $tahunAktif,
            'canPromote' => $canPromote,
            'canMoveSemester' => $canMoveSemester,
        ]);
    }

    public function store_siswa(Request $request)
    {
        $existingStudentId = Student::where('user_id', $request->user_id)->value('id');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nisn' => [
                'required',
                'size:10',
                'regex:/^[0-9]+$/',
                Rule::unique('students', 'nisn')->ignore($existingStudentId),
            ],
            'nama' => 'required|string|max:255',
            'ttl' => 'required|string|max:255',
            'date' => 'required|date',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'no_telp' => [
                'required',
                'numeric',
                'digits_between:8,13',
                Rule::unique('students', 'no_telp')->ignore($existingStudentId),
            ],
            'n_ayah' => 'required|string|max:255',
            'n_ibu' => 'required|string|max:255',
            'alamat_ortu' => 'required|string|max:255',
            'no_telp_rumah' => 'required|numeric|digits_between:5,13',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ((int) $user->role !== 3) {
            return back()->with('error', 'User yang dipilih harus ber-role siswa.')->withInput();
        }

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $semester = $tahunAktif->semesterAktif;

        if (!$semester) {
            return back()->with('error', 'Belum ada semester aktif. Silakan buat semester terlebih dahulu.')->withInput();
        }

        $ttl = $request->ttl . ', ' . $request->date;

        DB::transaction(function () use ($request, $user, $tahunAktif, $semester, $ttl) {
            $student = Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nisn' => $request->nisn,
                    'nama' => $request->nama,
                    'ttl' => $ttl,
                    'jk' => $request->jk,
                    'agama' => $request->agama,
                    'alamat' => $request->alamat,
                    'no_telp' => $request->no_telp,
                    'n_ayah' => $request->n_ayah,
                    'n_ibu' => $request->n_ibu,
                    'alamat_ortu' => $request->alamat_ortu,
                    'no_telp_rumah' => $request->no_telp_rumah,
                    'status' => 'Aktif',
                ]
            );

            $user->update([
                'name' => $request->nama,
                'info' => true,
            ]);

            $kelasAktif = StudentKelas::where('student_id', $student->id)
                ->where('aktif', true)
                ->first();

            if ($kelasAktif
                && (int) $kelasAktif->kelas_id === (int) $request->kelas_id
                && (int) $kelasAktif->tahun_ajaran_id === (int) $tahunAktif->id
                && (int) $kelasAktif->semester_id === (int) $semester->id
            ) {
                return;
            }

            if ($kelasAktif) {
                $kelasAktif->update(['aktif' => false]);
            }

            StudentKelas::create([
                'student_id' => $student->id,
                'kelas_id' => $request->kelas_id,
                'tahun_ajaran_id' => $tahunAktif->id,
                'semester_id' => $semester->id,
                'aktif' => true,
            ]);
        });

        return back()->with('success', 'Siswa berhasil ditempatkan ke master siswa.');
    }

    public function import_siswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new MasterSiswaImport();
            Excel::import($import, $request->file('file'));

            if ($import->createdCount() === 0) {
                return back()->with('error', 'Tidak ada data yang berhasil diimport. Pastikan kolom Excel sesuai dan minimal berisi nama, NISN, serta kelas.');
            }

            return back()->with('success', 'Import siswa berhasil. Berhasil: ' . $import->createdCount() . ', dilewati: ' . $import->skippedCount() . '.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Import gagal karena data Excel tidak sesuai atau ada nilai kosong yang konflik dengan data unik.');
        }
    }

    public function template_siswa()
    {
        return Excel::download(new MasterSiswaTemplateExport, 'template-import-siswa.xlsx');
    }

    public function histori_index()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        if (request('tanggal')) {
            $histories = History::with('siswa')
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('tanggal', request('tanggal'))
                ->filter(request(['tanggal']))
                ->paginate(7)
                ->withQueryString();
            $tanggal = date('d-m-Y', strtotime(request('tanggal')));
        } else {
            $histories = History::where('tahun_ajaran_id', $tahunAktif->id)
                ->latest()
                ->with('siswa')
                ->filter(request(['tanggal']))
                ->paginate(7)
                ->withQueryString();
            $tanggal = $histories->unique('tanggal')->pluck('tanggal');
        }

        return view('admin.page.histori.master-history', compact('histories', 'tanggal'));
    }

    public function histori_admin($id)
    {
        $siswa = Student::findOrFail($id);
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        if (request('tanggal')) {
            $histories = History::with('siswa', 'rule', 'kelasSnapshot')
                ->where('student_id', $id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->whereDate('tanggal', request('tanggal'))
                ->orderBy('tanggal', 'asc')
                ->get();
            $tanggal = date('d-m-Y', strtotime(request('tanggal')));
        } else {
            $histories = History::with('siswa', 'rule', 'kelasSnapshot')
                ->where('student_id', $id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->orderBy('tanggal', 'asc')
                ->get();
            $tanggal = $histories->unique('tanggal')->pluck('tanggal');
        }

        return view('admin.page.histori.history', compact('histories', 'tanggal', 'siswa'));
    }

    public function update_siswa(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Student::findOrFail($id);
        $kelasAktif = $siswa->kelasAktif;

        if (!$kelasAktif) {
            return back()->with('error', 'Siswa tidak memiliki kelas aktif.');
        }

        $tahunAktif = TahunAjaran::find($kelasAktif->tahun_ajaran_id);
        $semester = $tahunAktif?->semesterAktif;

        $kelasAktif->update(['aktif' => false]);

        StudentKelas::create([
            'student_id' => $siswa->id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran_id' => $kelasAktif->tahun_ajaran_id,
            'semester_id' => $semester?->id,
            'aktif' => true,
        ]);

        return back()->with('success', 'Kelas siswa berhasil diperbarui.');
    }

    public function kenaikanKelas()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $semesterAktif = $tahunAktif->semesterAktif;
        $semesterId = $semesterAktif?->id;

        if (!$semesterAktif) {
            alert()->error('Gagal', 'Tidak ada semester aktif.');
            return back();
        }

        $siswaAktif = Student::with('kelasAktif.kelas', 'kelasAktif.semester', 'riwayatKelas.kelas')->get();
        $proses = 0;
        $alumni = 0;
        $skipped = 0;

        foreach ($siswaAktif as $siswa) {
            $kelasAktif = $siswa->kelasAktif;

            if ($kelasAktif && $kelasAktif->semester_id == $semesterId) {
                $skipped++;
                continue;
            }

            if ($kelasAktif) {
                $kelasSekarang = $kelasAktif->kelas;
            } else {
                $lastSk = $siswa->riwayatKelas()->latest('id')->first();
                if (!$lastSk) continue;
                $kelasSekarang = $lastSk->kelas;
            }

            if ($kelasSekarang->tingkat == 6) {
                $siswa->update(['status' => 'Alumni']);
                if ($kelasAktif) $kelasAktif->update(['aktif' => false]);
                $alumni++;
                continue;
            }

            $kelasBaru = Kelas::where('tingkat', $kelasSekarang->tingkat + 1)
                ->where('nama_kelas', $kelasSekarang->nama_kelas)
                ->first();

            if (!$kelasBaru) {
                $kelasBaru = Kelas::where('tingkat', $kelasSekarang->tingkat + 1)->first();
            }

            if (!$kelasBaru) continue;

            if ($kelasAktif) $kelasAktif->update(['aktif' => false]);

            StudentKelas::create([
                'student_id' => $siswa->id,
                'kelas_id' => $kelasBaru->id,
                'tahun_ajaran_id' => $tahunAktif->id,
                'semester_id' => $semesterId,
                'aktif' => true,
            ]);

            $proses++;
        }

        if ($proses == 0 && $alumni == 0) {
            $msg = "Tidak ada siswa yang diproses.";
            if ($skipped > 0) $msg .= " {$skipped} siswa sudah diproses sebelumnya.";
            alert()->info('Informasi', $msg);
            return back();
        }

        $msg = "Kenaikan kelas berhasil diproses. ({$proses} naik, {$alumni} lulus";
        if ($skipped > 0) $msg .= ", {$skipped} sudah diproses";
        $msg .= ')';

        alert()->success('Berhasil', $msg);
        return back();
    }

    public function perpindahanSemester()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $semesterAktif = $tahunAktif->semesterAktif;

        if (!$semesterAktif) {
            alert()->error('Gagal', 'Tidak ada semester aktif.');
            return back();
        }

        $daftarSk = StudentKelas::with('kelas')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->where('aktif', true)
            ->where('semester_id', '!=', $semesterAktif->id)
            ->get();

        $siswaTanpaKelas = Student::whereDoesntHave('kelasAktif')
            ->whereHas('riwayatKelas')
            ->get();

        $proses = 0;

        foreach ($daftarSk as $sk) {
            $sk->update(['aktif' => false]);

            StudentKelas::create([
                'student_id' => $sk->student_id,
                'kelas_id' => $sk->kelas_id,
                'tahun_ajaran_id' => $tahunAktif->id,
                'semester_id' => $semesterAktif->id,
                'aktif' => true,
            ]);

            $proses++;
        }

        foreach ($siswaTanpaKelas as $siswa) {
            $lastSk = $siswa->riwayatKelas()->latest('id')->first();
            if (!$lastSk) continue;

            StudentKelas::create([
                'student_id' => $siswa->id,
                'kelas_id' => $lastSk->kelas_id,
                'tahun_ajaran_id' => $tahunAktif->id,
                'semester_id' => $semesterAktif->id,
                'aktif' => true,
            ]);

            $proses++;
        }

        if ($proses == 0) {
            alert()->info('Informasi', 'Tidak ada siswa yang perlu dipindahkan semester.');
            return back();
        }

        alert()->success('Berhasil', "Perpindahan semester berhasil. ({$proses} siswa diproses)");
        return back();
    }

    public function detail_siswa($id)
    {
        $siswa = Student::with([
            'user',
            'kelasAktif.kelas',
            'kelasAktif.tahunAjaran',
            'riwayatKelas.kelas',
            'riwayatKelas.tahunAjaran'
        ])->findOrFail($id);

        return view('admin.page.user.detail', compact('siswa'));
    }

    public function aktifkanTahunAjaran($id)
    {
        $target = TahunAjaran::findOrFail($id);

        if ($target->status === 'Arsip') {
            return back()->with('error', 'Tahun ajaran yang sudah diarsipkan tidak dapat diaktifkan kembali.');
        }

        TahunAjaran::where('status', 'Aktif')->update(['status' => 'Arsip']);
        TahunAjaran::where('id', $id)->update(['status' => 'Aktif']);

        return back()->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }
}
