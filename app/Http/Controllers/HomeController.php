<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\LoginHistory;
use App\Models\Penanganan;
use App\Models\Pengumuman;
use App\Models\Peraturan;
use App\Models\Student;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'datasiswa']);
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // admin
        if (auth()->user()->role == 1) {
            return view('home', $this->adminDashboardData());
        }

        // wali kelas
        if (auth()->user()->role == 2) {
            $guru = \App\Models\Guru::where('user_id', auth()->user()->id)->first();
            $wali_kelas_id = $guru ? WaliKelas::where('guru_id', $guru->id)->first() : null;
            if (!$wali_kelas_id) {
                // Guru bukan wali kelas → arahkan ke absensi guru
                return redirect()->route('guru.absensi-guru.show');
            }

            $siswas = Student::whereHas('kelasAktif', function ($q) use ($wali_kelas_id) {
                $q->where('kelas_id', $wali_kelas_id->kelas_id);
            })->get();
            $peraturan = Peraturan::all();
            $points = Peraturan::all();

            $sis = Student::whereHas('penanganan', function ($q) use ($wali_kelas_id) {
                $q->where('kelas_id', $wali_kelas_id->kelas_id);
            })->get();
            $id_student = [];
            foreach ($sis as $siswa) {
                $id_student[] = $siswa->id;
            }
            $penanganan = Penanganan::whereIn('student_id', $id_student)
                ->latest()->paginate(10);

            return view('home', compact('siswas', 'peraturan', 'points', 'penanganan', 'wali_kelas_id'));
        }

        // siswa
        if (auth()->user()->role == 3) {
            $siswas = Student::with('user')->take(10)->get()->sortByDesc('poin');
            $siswa = Student::firstWhere('nisn', auth()->user()->nisn);
            return view('home', compact('siswas', 'siswa'));
        }

        // Bk
        if (auth()->user()->role == 4) {
            $guru_bk = GuruBk::firstWhere('user_id', auth()->user()->id);
            $siswas = Student::where('kelas_id', $guru_bk->kelas_id)->get();
            $peraturan = Peraturan::all();
            $points = Peraturan::all();

            $sis = Student::whereHas('penanganan', function ($q) use ($guru_bk) {
                $q->where('kelas_id', $guru_bk->kelas_id);
            })->get();

            $id_student = [];
            foreach ($sis as $siswa) {
                $id_student[] = $siswa->id;
            }
            $penanganan = Penanganan::whereIn('student_id', $id_student)->latest()->paginate(3);

            return view('home', compact('siswas', 'peraturan', 'points', 'penanganan', 'guru_bk'));
        }

        // Kepala Sekolah (read-only)
        if (auth()->user()->role == 5) {
            $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();
            $siswas = Student::all();
            $users = User::all();
            $walikelas = WaliKelas::all();
            $peraturan = Peraturan::all();
            $penanganan = Penanganan::latest()->take(10)->get();

            $dataPelanggaran = \App\Models\History::selectRaw('MONTH(tanggal) as month, count(*) as count')
                ->whereYear('tanggal', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month');

            $chartData = array_fill(1, 12, 0);
            foreach ($dataPelanggaran as $month => $count) {
                $chartData[$month] = $count;
            }

            return view('home', compact('siswas', 'users', 'walikelas', 'peraturan', 'penanganan', 'tahunAktif', 'chartData'));
        }
    }

    /**
     * Kumpulan data riil untuk dashboard admin.
     */
    private function adminDashboardData(): array
    {
        $tahunAktif = TahunAjaran::with('semesterAktif')->where('status', 'Aktif')->first();
        $taId = $tahunAktif?->id;
        $today = now()->toDateString();

        // ---------- Statistik utama ----------
        $totalSiswa = Student::where('status', 'aktif')->count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();

        $detailCounts = AbsensiDetail::whereHas('absensi', function ($q) use ($today) {
            $q->where('tanggal', $today);
        })
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $hadirHariIni = (int) ($detailCounts['H'] ?? 0);
        $totalAbsenHariIni = array_sum($detailCounts->toArray());
        $persenHadirHariIni = $totalAbsenHariIni > 0 ? round(($hadirHariIni / $totalAbsenHariIni) * 100) : 0;
        $sessionsHariIni = Absensi::where('tanggal', $today)->count();

        $komposisiHariIni = [
            'H' => (int) ($detailCounts['H'] ?? 0),
            'I' => (int) ($detailCounts['I'] ?? 0),
            'S' => (int) ($detailCounts['S'] ?? 0),
            'A' => (int) ($detailCounts['A'] ?? 0),
        ];

        // ---------- Perlu perhatian ----------
        $jumlahPenangananPending = Penanganan::where('status', 0)->count();
        $penangananPending = Penanganan::with(['siswa', 'pesan'])
            ->where('status', 0)
            ->latest()
            ->take(4)
            ->get();

        $jumlahAlphaHariIni = AbsensiDetail::where('status', 'A')
            ->whereHas('absensi', fn ($q) => $q->where('tanggal', $today))
            ->count();

        $totalGuruBerUser = Guru::whereNotNull('user_id')->count();
        $guruAbsenHariIni = AbsensiGuru::where('tanggal', $today)->distinct()->count('user_id');
        $guruBelumAbsen = max($totalGuruBerUser - $guruAbsenHariIni, 0);

        // ---------- Grafik kehadiran 30 hari ----------
        $daily = AbsensiDetail::join('absensis', 'absensis.id', '=', 'absensi_details.absensi_id')
            ->whereBetween('absensis.tanggal', [now()->subDays(29)->toDateString(), $today])
            ->select('absensis.tanggal as tanggal', 'absensi_details.status as status', DB::raw('count(*) as total'))
            ->groupBy('absensis.tanggal', 'absensi_details.status')
            ->get()
            ->groupBy('tanggal');

        $labels30 = [];
        $hadir30 = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels30[] = $date->format('d/m');
            $row = $daily->get($date->toDateString(), collect());
            $h = (int) $row->where('status', 'H')->sum('total');
            $tot = (int) $row->sum('total');
            $hadir30[] = $tot > 0 ? round(($h / $tot) * 100) : null;
        }

        // ---------- Ringkasan akademik ----------
        $siswaPerJenjang = DB::table('student_kelas as sk')
            ->join('kelas as k', 'k.id', '=', 'sk.kelas_id')
            ->join('jenjangs as j', 'j.id', '=', 'k.jenjang_id')
            ->where('sk.aktif', true)
            ->when($taId, fn ($q) => $q->where('sk.tahun_ajaran_id', $taId))
            ->selectRaw('j.nama_jenjang as nama, count(*) as total')
            ->groupBy('j.id', 'j.nama_jenjang')
            ->orderBy('j.id')
            ->get();

        $kelasPerJenjang = DB::table('kelas as k')
            ->join('jenjangs as j', 'j.id', '=', 'k.jenjang_id')
            ->selectRaw('j.nama_jenjang as nama, count(*) as total')
            ->groupBy('j.id', 'j.nama_jenjang')
            ->orderBy('j.id')
            ->get();

        $guruPerJenjang = DB::table('pengampu_mapels as pm')
            ->join('kelas as k', 'k.id', '=', 'pm.kelas_id')
            ->join('jenjangs as j', 'j.id', '=', 'k.jenjang_id')
            ->selectRaw('j.nama_jenjang as nama, count(distinct pm.guru_id) as total')
            ->groupBy('j.id', 'j.nama_jenjang')
            ->orderBy('j.id')
            ->get();

        // ---------- Aktivitas terbaru ----------
        $aktivitasTerbaru = LoginHistory::with('user')
            ->where('login_status', 'success')
            ->latest('login_at')
            ->take(6)
            ->get();

        // ---------- Pengumuman ----------
        $pengumumanDashboard = Pengumuman::where('status', 'Published')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // ---------- Sapaan & tanggal ----------
        $jam = (int) now()->format('G');
        $greeting = $jam < 11 ? 'Selamat Pagi' : ($jam < 15 ? 'Selamat Siang' : ($jam < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $namaAdmin = strtok(auth()->user()->name, ' ') ?: 'Admin';
        $tanggalSekarang = $this->tanggalIndonesia(now());

        // ---------- Kalender mini ----------
        $firstOfMonth = now()->copy()->startOfMonth();
        $kalender = [
            'nama_bulan' => $this->namaBulan[(int) $firstOfMonth->format('n')],
            'tahun'      => $firstOfMonth->format('Y'),
            'hari_awal'  => (int) $firstOfMonth->format('w'),
            'jumlah_hari'=> $firstOfMonth->daysInMonth,
            'hari_ini'   => (int) now()->format('j'),
        ];

        return compact(
            'tahunAktif',
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'hadirHariIni',
            'totalAbsenHariIni',
            'persenHadirHariIni',
            'sessionsHariIni',
            'komposisiHariIni',
            'jumlahPenangananPending',
            'penangananPending',
            'jumlahAlphaHariIni',
            'guruBelumAbsen',
            'labels30',
            'hadir30',
            'siswaPerJenjang',
            'kelasPerJenjang',
            'guruPerJenjang',
            'aktivitasTerbaru',
            'pengumumanDashboard',
            'greeting',
            'namaAdmin',
            'tanggalSekarang',
            'kalender'
        );
    }

    private array $namaBulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
        'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    private function tanggalIndonesia($date): string
    {
        $namaHari = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        $hari = $namaHari[(int) $date->format('w')];
        $tanggal = $date->format('j');
        $bulan = $this->namaBulan[(int) $date->format('n')];
        $tahun = $date->format('Y');

        return "$hari, $tanggal $bulan $tahun";
    }
}
