<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\PengampuMapel;
use App\Models\ProfilMadrasah;
use App\Models\TahunAjaran;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;


class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $tahunAjaranAktif = TahunAjaran::with('semesterAktif')->where(
            'status',
            'Aktif'
        )->firstOrFail();

        $query = JadwalPelajaran::with([
            'kelas',
            'jenjang',
            'guru',
            'mapel',
            'tahunAjaran.semesterAktif'
        ]);

        if (request('jenjang_id')) {
            $query->where('jenjang_id', request('jenjang_id'));
        }

        if (request('kelas_id')) {
            $query->where('kelas_id', request('kelas_id'));
        }

        if (request('guru_id')) {
            $query->where('guru_id', request('guru_id'));
        }

        if (request('hari')) {
            $query->where('hari', request('hari'));
        }

        if (request('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', request('tahun_ajaran_id'));
        } else {
            $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        }

        $jadwals = $query
            ->orderByRaw("FIELD(hari,'Sabtu','Ahad','Senin','Selasa','Rabu','Kamis')")
            ->orderBy('jam_mulai')
            ->get();

        $kelasQuery = Kelas::with('jenjang');
        if (request('jenjang_id')) {
            $kelasQuery->where('jenjang_id', request('jenjang_id'));
        }
        $kelas = $kelasQuery->orderBy('nama_kelas')->get();

        $jenjangs = Jenjang::orderBy('tingkat_awal')->get();

        $gurus = Guru::orderBy('nama')->get();

        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        $pengampuMapels = PengampuMapel::where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get(['guru_id', 'mata_pelajaran_id', 'kelas_id']);

        $sudahDisalin = JadwalPelajaran::where('tahun_ajaran_id', $tahunAjaranAktif->id)->exists();

        // Group data for matrix view
        $jadwalPerJenjang = [];
        foreach ($jenjangs as $j) {
            $jadwalPerJenjang[$j->id] = $jadwals->where('jenjang_id', $j->id)->values();
        }

        $kelasPerJenjang = [];
        foreach ($jenjangs as $j) {
            $kelasPerJenjang[$j->id] = Kelas::where('jenjang_id', $j->id)
                ->orderBy('nama_kelas')
                ->get();
        }

        $hariList = ['Sabtu', 'Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis'];

        return view(
            'admin.jadwalpelajaran.index',
            compact(
                'jadwals',
                'kelas',
                'jenjangs',
                'gurus',
                'mapels',
                'tahunAjaranAktif',
                'tahunAjarans',
                'pengampuMapels',
                'sudahDisalin',
                'jadwalPerJenjang',
                'kelasPerJenjang',
                'hariList'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'           => 'required|exists:kelas,id',
            'guru_id'            => 'required|exists:gurus,id',
            'mata_pelajaran_id'  => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran_id'    => 'required|exists:tahun_ajaran,id',
            'hari'               => 'required',
            'jam_ke'             => 'required|integer|min:1|max:4',
        ]);

        // Mapping Jam Pelajaran
        $slotJam = [

            1 => [
                'mulai' => '07:30:00',
                'selesai' => '08:30:00'
            ],

            2 => [
                'mulai' => '08:30:00',
                'selesai' => '09:30:00'
            ],

            3 => [
                'mulai' => '10:00:00',
                'selesai' => '11:00:00'
            ],

            4 => [
                'mulai' => '11:00:00',
                'selesai' => '12:00:00'
            ],

        ];

        // Cek Bentrok Guru
        $bentrokGuru = JadwalPelajaran::where(
            'guru_id',
            $request->guru_id
        )
            ->where(
                'hari',
                $request->hari
            )
            ->where(
                'jam_ke',
                $request->jam_ke
            )
            ->exists();

        if ($bentrokGuru) {

            return back()->with(
                'error',
                'Guru sudah memiliki jadwal pada jam tersebut.'
            );
        }

        // Cek Bentrok Kelas
        $bentrokKelas = JadwalPelajaran::where(
            'kelas_id',
            $request->kelas_id
        )
            ->where(
                'hari',
                $request->hari
            )
            ->where(
                'jam_ke',
                $request->jam_ke
            )
            ->exists();

        if ($bentrokKelas) {

            return back()->with(
                'error',
                'Kelas sudah memiliki jadwal pada jam tersebut.'
            );
        }

        $jam = $slotJam[$request->jam_ke];

        $kelas = Kelas::findOrFail($request->kelas_id);

        JadwalPelajaran::create([

            'kelas_id' => $request->kelas_id,

            'jenjang_id' => $kelas->jenjang_id,

            'guru_id' => $request->guru_id,

            'mata_pelajaran_id' => $request->mata_pelajaran_id,

            'tahun_ajaran_id' => $request->tahun_ajaran_id,

            'hari' => $request->hari,

            'jam_ke' => $request->jam_ke,

            'jam_mulai' => $jam['mulai'],

            'jam_selesai' => $jam['selesai'],
        ]);

        return back()->with(
            'success',
            'Jadwal pelajaran berhasil ditambahkan'
        );
    }

    public function update(
        Request $request,
        JadwalPelajaran $jadwal_pelajaran
    ) {

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'hari' => 'required',
            'jam_ke' => 'required|integer|min:1|max:4',
        ]);

        $slotJam = [

            1 => [
                'mulai' => '07:30:00',
                'selesai' => '08:30:00'
            ],

            2 => [
                'mulai' => '08:30:00',
                'selesai' => '09:30:00'
            ],

            3 => [
                'mulai' => '10:00:00',
                'selesai' => '11:00:00'
            ],

            4 => [
                'mulai' => '11:00:00',
                'selesai' => '12:00:00'
            ],

        ];

        // Cek bentrok guru
        $bentrokGuru = JadwalPelajaran::where(
            'guru_id',
            $request->guru_id
        )
            ->where(
                'hari',
                $request->hari
            )
            ->where(
                'jam_ke',
                $request->jam_ke
            )
            ->where(
                'id',
                '!=',
                $jadwal_pelajaran->id
            )
            ->exists();

        if ($bentrokGuru) {

            return back()->with(
                'error',
                'Guru sudah memiliki jadwal pada jam tersebut.'
            );
        }

        // Cek bentrok kelas
        $bentrokKelas = JadwalPelajaran::where(
            'kelas_id',
            $request->kelas_id
        )
            ->where(
                'hari',
                $request->hari
            )
            ->where(
                'jam_ke',
                $request->jam_ke
            )
            ->where(
                'id',
                '!=',
                $jadwal_pelajaran->id
            )
            ->exists();

        if ($bentrokKelas) {

            return back()->with(
                'error',
                'Kelas sudah memiliki jadwal pada jam tersebut.'
            );
        }

        $jam = $slotJam[$request->jam_ke];

        $kelas = Kelas::findOrFail($request->kelas_id);

        $jadwal_pelajaran->update([

            'kelas_id' => $request->kelas_id,

            'jenjang_id' => $kelas->jenjang_id,

            'guru_id' => $request->guru_id,

            'mata_pelajaran_id' => $request->mata_pelajaran_id,

            'tahun_ajaran_id' => $request->tahun_ajaran_id,

            'hari' => $request->hari,

            'jam_ke' => $request->jam_ke,

            'jam_mulai' => $jam['mulai'],

            'jam_selesai' => $jam['selesai'],

        ]);

        return back()->with(
            'success',
            'Jadwal pelajaran berhasil diperbarui'
        );
    }

    public function destroy(JadwalPelajaran $jadwal_pelajaran)
    {
        $jadwal_pelajaran->delete();

        return back()->with(
            'success',
            'Jadwal pelajaran berhasil dihapus'
        );
    }
    public function perKelas($id)
    {
        $kelas = Kelas::with('jenjang')->findOrFail($id);

        $jadwals = JadwalPelajaran::with([
            'guru',
            'mapel',
            'tahunAjaran'
        ])
            ->where('kelas_id', $id)
            ->orderByRaw("FIELD(hari,'Sabtu','Ahad','Senin','Selasa','Rabu','Kamis')")
            ->orderBy('jam_mulai')
            ->get();

        $hariList = ['Sabtu', 'Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis'];

        $jamList = [
            1 => ['mulai' => '07:30', 'selesai' => '08:30'],
            2 => ['mulai' => '08:30', 'selesai' => '09:30'],
            3 => ['mulai' => '10:00', 'selesai' => '11:00'],
            4 => ['mulai' => '11:00', 'selesai' => '12:00'],
        ];

        return view(
            'admin.jadwalpelajaran.per-kelas',
            compact(
                'kelas',
                'jadwals',
                'hariList',
                'jamList'
            )
        );
    }
    public function cetakPerKelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $jadwals = JadwalPelajaran::with([
            'guru',
            'mapel'
        ])
            ->where('kelas_id', $id)
            ->orderByRaw("FIELD(hari,'Sabtu','Ahad','Senin','Selasa','Rabu','Kamis')")
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'admin.jadwalpelajaran.cetak',
            compact('kelas', 'jadwals')
        );
    }
    public function daftarKelas()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view(
            'admin.jadwalpelajaran.daftar-kelas',
            compact('kelas')
        );
    }
    public function exportPdf(Request $request)
    {
        $query = JadwalPelajaran::with([
            'kelas',
            'jenjang',
            'guru',
            'mapel',
            'tahunAjaran.semesterAktif'
        ]);

        // Filter Jenjang
        if ($request->filled('jenjang_id')) {
            $query->where('jenjang_id', $request->jenjang_id);
        }

        // Filter Kelas
        if ($request->filled('kelas_id')) {

            $query->where(
                'kelas_id',
                $request->kelas_id
            );
        }

        // Filter Guru
        if ($request->filled('guru_id')) {

            $query->where(
                'guru_id',
                $request->guru_id
            );
        }

        // Filter Tahun Ajaran
        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        } else {
            $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
            if ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            }
        }

        $jadwals = $query
            ->orderByRaw("FIELD(hari,'Sabtu','Ahad','Senin','Selasa','Rabu','Kamis')")
            ->orderBy('jam_mulai')
            ->get();

        $kelas = null;
        $guru = null;

        if ($request->filled('kelas_id')) {

            $kelas = Kelas::find(
                $request->kelas_id
            );
        }

        if ($request->filled('guru_id')) {

            $guru = Guru::find(
                $request->guru_id
            );
        }

        $pdf = Pdf::loadView(
            'admin.jadwalpelajaran.pdf',
            compact(
                'jadwals',
                'kelas',
                'guru'
            )
        )->setPaper(
            'Folio',
            'portrait'
        );

        return $pdf->download(
            'jadwal-pelajaran.pdf'
        );
    }
    public function grid(Request $request)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        $selectedKelas = $request->kelas_id;

        $jadwals = JadwalPelajaran::with(['guru', 'mapel'])
            ->when($selectedKelas, function ($q) use ($selectedKelas) {
                $q->where('kelas_id', $selectedKelas);
            })
            ->get();

        $hariList = ['Sabtu', 'Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis'];

        $jamList = JadwalPelajaran::select('jam_mulai', 'jam_selesai')
            ->distinct()
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwalpelajaran.grid', compact(
            'kelas',
            'selectedKelas',
            'jadwals',
            'hariList',
            'jamList'
        ));
    }
    public function jadwalJenjang(Request $request)
    {
        $jenjangKode = $request->jenjang;

        $kelas = Kelas::with('jenjang');

        if ($jenjangKode) {
            $jenjangRecord = Jenjang::where('kode', $jenjangKode)->first();
            if ($jenjangRecord) {
                $kelas->where('jenjang_id', $jenjangRecord->id);
            }
        }

        $kelas = $kelas
            ->orderBy('nama_kelas')
            ->get();

        $jenjangs = Jenjang::orderBy('nama_jenjang')->get();

        return view(
            'admin.jadwalpelajaran.jadwal-jenjang',
            compact('kelas', 'jenjangKode', 'jenjangs')
        );
    }
    public function detailJenjang($jenjang)
    {
        $jenjangRecord = Jenjang::where('kode', $jenjang)->firstOrFail();
        $jenjangId = $jenjangRecord->id;

        $kelas = Kelas::where('jenjang_id', $jenjangId)
            ->orderBy('nama_kelas')
            ->get();

        $jadwals = JadwalPelajaran::with([
            'kelas',
            'guru',
            'mapel'
        ])
            ->where('jenjang_id', $jenjangId)
            ->get();

        return view(
            'admin.jadwalpelajaran.jadwal-jenjang-detail',
            compact(
                'jenjang',
                'kelas',
                'jadwals'
            )
        );
    }

    public function jadwalSiswa()
    {
        $jenjangs = Jenjang::orderBy('nama_jenjang')->get();

        $kelasPerJenjang = [];
        foreach ($jenjangs as $j) {
            $kelasPerJenjang[$j->id] = Kelas::where('jenjang_id', $j->id)
                ->orderBy('nama_kelas')
                ->get();
        }

        return view(
            'admin.jadwalpelajaran.jadwal-siswa',
            compact('jenjangs', 'kelasPerJenjang')
        );
    }

    public function jadwalSiswaKelas($kelas_id)
    {
        $kelas = Kelas::with('jenjang')->findOrFail($kelas_id);

        $jadwals = JadwalPelajaran::with([
            'guru',
            'mapel',
            'tahunAjaran'
        ])
            ->where('kelas_id', $kelas_id)
            ->orderByRaw("FIELD(hari,'Sabtu','Ahad','Senin','Selasa','Rabu','Kamis')")
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'admin.jadwalpelajaran.jadwal-siswa-kelas',
            compact('kelas', 'jadwals')
        );
    }

    public function cetakJadwalSiswa($jenjang_id = null)
    {
        $tahunAjaran = TahunAjaran::with('semesterAktif')
            ->where('status', 'Aktif')
            ->firstOrFail();

        $jadwals = JadwalPelajaran::with([
            'guru',
            'mapel',
            'kelas'
        ])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->when($jenjang_id, fn($q) => $q->where('jenjang_id', $jenjang_id))
            ->get();

        $kelasQuery = Kelas::with('jenjang');
        if ($jenjang_id) {
            $kelasQuery->where('jenjang_id', $jenjang_id);
        } else {
            $kelasQuery->whereIn('id', $jadwals->pluck('kelas_id')->unique());
        }
        $kelasList = $kelasQuery
            ->orderBy('jenjang_id')
            ->orderBy('nama_kelas')
            ->get();

        // Urutkan guru alfabet untuk kode huruf
        $guruAlfa = Guru::whereIn('id', $jadwals->pluck('guru_id')->unique())
            ->orderBy('nama')
            ->get()
            ->values();

        $guruKodeMap = [];
        $no = 1;
        foreach ($guruAlfa as $g) {
            $guruKodeMap[$g->id] = $no;
            $no++;
        }

        // Bangun matrix [hari][jam_ke][kelas_id]
        $hariUrut = ['Sabtu', 'Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis'];
        $jamSlot = [
            1 => ['mulai' => '07:30', 'selesai' => '08:30'],
            2 => ['mulai' => '08:30', 'selesai' => '09:30'],
            3 => ['mulai' => '10:00', 'selesai' => '11:00'],
            4 => ['mulai' => '11:00', 'selesai' => '12:00'],
        ];

        $matrix = [];
        foreach ($hariUrut as $hari) {
            $matrix[$hari] = [];
            foreach (array_keys($jamSlot) as $jamKe) {
                $matrix[$hari][$jamKe] = [];
                foreach ($kelasList as $kelas) {
                    $j = $jadwals->first(function ($j) use ($hari, $jamKe, $kelas) {
                        return $j->hari === $hari
                            && $j->jam_ke == $jamKe
                            && $j->kelas_id == $kelas->id;
                    });
                    $matrix[$hari][$jamKe][$kelas->id] = $j ? [
                        'mapel' => $j->mapel->nama_mapel ?? '',
                        'kode_guru' => $guruKodeMap[$j->guru_id] ?? '',
                    ] : null;
                }
            }
        }

        $profil = ProfilMadrasah::first();

        $jenjangCetak = $jenjang_id ? Jenjang::find($jenjang_id) : null;

        $semesterGanjil = $tahunAjaran->semesters()->where('nama', 'Ganjil')->first();

        return view(
            'admin.jadwalpelajaran.cetak-jadwal-siswa',
            compact(
                'tahunAjaran',
                'kelasList',
                'matrix',
                'hariUrut',
                'jamSlot',
                'guruAlfa',
                'guruKodeMap',
                'profil',
                'jenjang_id',
                'jenjangCetak',
                'semesterGanjil'
            )
        );
    }

    public function salin()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        if (!$tahunAktif) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $tahunSebelumnya = TahunAjaran::where('id', '!=', $tahunAktif->id)
            ->orderByDesc('tahun_ajaran')
            ->first();

        if (!$tahunSebelumnya) {
            return back()->with('error', 'Tidak ada tahun ajaran sebelumnya untuk disalin.');
        }

        $dataLama = JadwalPelajaran::where('tahun_ajaran_id', $tahunSebelumnya->id)->get();

        if ($dataLama->isEmpty()) {
            return back()->with('error', 'Tidak ada jadwal pelajaran di tahun ajaran ' . $tahunSebelumnya->tahun_ajaran . '.');
        }

        $berhasil = 0;
        $dilewati = 0;

        $jamMap = [
            1 => ['mulai' => '07:30:00', 'selesai' => '08:30:00'],
            2 => ['mulai' => '08:30:00', 'selesai' => '09:30:00'],
            3 => ['mulai' => '10:00:00', 'selesai' => '11:00:00'],
            4 => ['mulai' => '11:00:00', 'selesai' => '12:00:00'],
        ];

        foreach ($dataLama as $item) {
            if (!$item->guru || !$item->mapel || !$item->kelas) {
                $dilewati++;
                continue;
            }

            $sudahAda = JadwalPelajaran::where('guru_id', $item->guru_id)
                ->where('mata_pelajaran_id', $item->mata_pelajaran_id)
                ->where('kelas_id', $item->kelas_id)
                ->where('hari', $item->hari)
                ->where('jam_ke', $item->jam_ke)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->exists();

            if ($sudahAda) {
                $dilewati++;
                continue;
            }

            $guruBentrok = JadwalPelajaran::where('guru_id', $item->guru_id)
                ->where('hari', $item->hari)
                ->where('jam_ke', $item->jam_ke)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->exists();

            if ($guruBentrok) {
                $dilewati++;
                continue;
            }

            $kelasBentrok = JadwalPelajaran::where('kelas_id', $item->kelas_id)
                ->where('hari', $item->hari)
                ->where('jam_ke', $item->jam_ke)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->exists();

            if ($kelasBentrok) {
                $dilewati++;
                continue;
            }

            $jam = $jamMap[$item->jam_ke] ?? ['mulai' => $item->jam_mulai, 'selesai' => $item->jam_selesai];

            JadwalPelajaran::create([
                'kelas_id' => $item->kelas_id,
                'jenjang_id' => $item->kelas->jenjang_id ?? $item->jenjang_id,
                'guru_id' => $item->guru_id,
                'mata_pelajaran_id' => $item->mata_pelajaran_id,
                'tahun_ajaran_id' => $tahunAktif->id,
                'hari' => $item->hari,
                'jam_ke' => $item->jam_ke,
                'jam_mulai' => $jam['mulai'],
                'jam_selesai' => $jam['selesai'],
            ]);
            $berhasil++;
        }

        $pesan = "Berhasil menyalin {$berhasil} jadwal pelajaran dari tahun ajaran {$tahunSebelumnya->tahun_ajaran}.";
        if ($dilewati > 0) {
            $pesan .= " {$dilewati} data dilewati (sudah ada, bentrok, atau data tidak ditemukan).";
        }

        return back()->with('success', $pesan);
    }
}
