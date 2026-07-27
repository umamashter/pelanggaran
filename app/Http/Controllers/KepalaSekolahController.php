<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Guru;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\History;
use App\Models\ProfilMadrasah;
use App\Models\KepalaMadrasah;
use Illuminate\Http\Request;

class KepalaSekolahController extends Controller
{
    private function getJenjangId()
    {
        $km = KepalaMadrasah::where('user_id', auth()->id())->first();

        if (!$km) {
            abort(403, 'Akun Anda belum ditugaskan ke jenjang manapun.');
        }

        return $km->jenjang_id;
    }

    public function siswa(Request $request)
    {
        $jenjangId = $this->getJenjangId();
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        if (!$tahunAktif) {
            return redirect('/home')->with('error', 'Belum ada tahun ajaran yang aktif.');
        }

        $semesterDipilih = $tahunAktif->semesterAktif;

        $query = Student::with([
            'riwayatKelas.kelas.jenjang',
            'riwayatKelas.kelas',
            'riwayatKelas.tahunAjaran'
        ])
            ->whereHas('riwayatKelas', function ($q) use ($tahunAktif, $semesterDipilih, $jenjangId) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('semester_id', $semesterDipilih?->id)
                    ->whereHas('kelas', fn($q) => $q->where('jenjang_id', $jenjangId));
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhereHas('riwayatKelas.kelas', fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"));
            });
        }

        $siswas = $query->latest('id')->get();

        foreach ($siswas as $siswa) {
            $siswa->riwayatDipilih = $siswa->riwayatKelas
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('semester_id', $semesterDipilih?->id)
                ->sortByDesc('id')
                ->first();
        }

        $jenjang = \App\Models\Jenjang::find($jenjangId);

        return view('kepsek.siswa.index', compact('siswas', 'tahunAktif', 'semesterDipilih', 'jenjang'));
    }

    public function guru()
    {
        $jenjangId = $this->getJenjangId();

        $guruIds = \App\Models\PengampuMapel::whereHas('kelas', fn($q) => $q->where('jenjang_id', $jenjangId))
            ->pluck('guru_id')
            ->unique();

        $gurus = Guru::whereIn('id', $guruIds)->orderBy('nama')->get();

        $jenjang = \App\Models\Jenjang::find($jenjangId);

        return view('kepsek.guru.index', compact('gurus', 'jenjang'));
    }

    public function absensiSiswa()
    {
        $jenjangId = $this->getJenjangId();
        $tahunAktif = TahunAjaran::with('semesterAktif')
            ->where('status', 'Aktif')
            ->firstOrFail();

        $isJumat = now()->isFriday();

        $kelasList = Kelas::with('jenjang')
            ->where('jenjang_id', $jenjangId)
            ->whereHas('siswaAktif', function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id);
            })->orderBy('nama_kelas')->get();

        $absensiHariIni = Absensi::where('tanggal', now()->toDateString())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereIn('kelas_id', $kelasList->pluck('id'))
            ->pluck('kelas_id')
            ->toArray();

        $absensiMap = Absensi::where('tanggal', now()->toDateString())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereIn('kelas_id', $kelasList->pluck('id'))
            ->pluck('id', 'kelas_id')
            ->toArray();

        $jenjang = \App\Models\Jenjang::find($jenjangId);

        return view('kepsek.absensi-siswa.index', compact(
            'kelasList',
            'absensiHariIni',
            'absensiMap',
            'tahunAktif',
            'isJumat',
            'jenjang'
        ));
    }

    public function absensiGuru(Request $request)
    {
        $jenjangId = $this->getJenjangId();

        $guruIds = \App\Models\PengampuMapel::whereHas('kelas', fn($q) => $q->where('jenjang_id', $jenjangId))
            ->pluck('guru_id')
            ->unique();

        $userIds = Guru::whereIn('id', $guruIds)->whereNotNull('user_id')->pluck('user_id');

        $query = AbsensiGuru::with(['user'])
            ->whereIn('user_id', $userIds)
            ->orderBy('tanggal', 'desc')
            ->latest('id');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $absensis = $query->get();
        $guruList = User::whereIn('id', $userIds)->orderBy('name')->get();

        $jenjang = \App\Models\Jenjang::find($jenjangId);

        return view('kepsek.absensi-guru.index', compact('absensis', 'guruList', 'jenjang'));
    }

    public function laporanPelanggaran(Request $request)
    {
        $jenjangId = $this->getJenjangId();
        $tahunAjaran = $request->input('tahun_ajaran') ?? '2024/2025';
        $bulan = $request->input('bulan');
        $nisn = $request->input('nisn');

        $query = History::query()->with(['siswa', 'rule', 'kelasSnapshot']);

        [$startYear, $endYear] = explode('/', $tahunAjaran);
        $query->whereBetween('tanggal', ["$startYear-07-01", "$endYear-06-30"]);

        $query->whereHas('siswa', function ($q) use ($jenjangId) {
            $q->whereHas('riwayatKelas', function ($q) use ($jenjangId) {
                $q->whereHas('kelas', fn($q) => $q->where('jenjang_id', $jenjangId));
            });
        });

        if ($bulan) {
            $query->where('tanggal', 'like', "$bulan%");
        }
        if ($nisn) {
            $query->whereHas('siswa', function ($q) use ($nisn) {
                $q->where('nisn', 'like', "%$nisn%");
            });
        }

        $histories = $query->orderBy('tanggal', 'asc')->get();

        $jenjang = \App\Models\Jenjang::find($jenjangId);

        return view('kepsek.laporan.pelanggaran', compact(
            'histories', 'tahunAjaran', 'bulan', 'nisn', 'jenjang'
        ));
    }

    public function profilMadrasah()
    {
        $profil = ProfilMadrasah::with('misi')->first();

        if (!$profil) {
            return redirect('/home')->with('error', 'Profil madrasah belum tersedia.');
        }

        return view('kepsek.profil-madrasah.index', compact('profil'));
    }
}
