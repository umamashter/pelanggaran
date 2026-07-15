<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PenilaianLomba;
use App\Models\PesertaLomba;
use App\Models\JuriLomba;
use App\Models\AspekPenilaian;
use App\Models\Lomba;
use App\Models\SesiLomba;
use App\Models\KelompokLomba;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class PenilaianLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    private function resolveTimJuriId(int $lombaId, $requestedJuriId = null)
    {
        if ($requestedJuriId) {
            $matched = JuriLomba::where('lomba_id', $lombaId)
                ->where('id', $requestedJuriId)
                ->value('id');

            if ($matched) {
                return $matched;
            }
        }

        return JuriLomba::where('lomba_id', $lombaId)->value('id');
    }

    public function index()
    {
        $all = PenilaianLomba::with([
                'pesertaLomba.lomba',
                'pesertaLomba.student.user',
                'pesertaLomba.kelompokLomba.anggota.student.user',
                'pesertaLomba.hasil',
                'juriLomba.guru',
                'aspekPenilaian',
            ])
            ->latest()
            ->get()
            ->groupBy('peserta_lomba_id')
            ->map(function ($items) {
                $first = $items->first();
                $first->jumlah_juri = $items->pluck('juri_lomba_id')->unique()->count();
                $first->total_nilai = $items->sum('nilai');
                $first->latest_id = $items->max('id');
                $first->lombaJenis = $first->pesertaLomba->lomba->jenis ?? null;
                $first->kelompok = null;
                if ($first->lombaJenis === 'Tim') {
                    $first->kelompok = $first->pesertaLomba->kelompokLomba;
                }
                return $first;
            })
            ->sortByDesc('latest_id');

        $individu = $all->where('lombaJenis', 'Individu')->values();
        $tim = $all->where('lombaJenis', 'Tim')->values();

        return view('admin.penilaian-lomba.index', compact('individu', 'tim'));
    }

    public function create(Request $request)
    {
        $sesiLombas = SesiLomba::with('lombas')->orderBy('nama')->get();
        $mode = $request->query('mode');
        if (!in_array($mode, ['individu', 'tim'], true)) {
            $mode = null;
        }

        return view('admin.penilaian-lomba.create', compact('sesiLombas', 'mode'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        if ($request->filled('kelompok_lomba_id')) {
            $kelompok = KelompokLomba::findOrFail($request->kelompok_lomba_id);
            $juriId = $this->resolveTimJuriId($kelompok->lomba_id, $request->input('juri_lomba_id'));
            if (!$juriId) {
                return back()->with('toast_error', 'Tidak ada juri untuk lomba ini. Tambahkan juri terlebih dahulu.')->withInput();
            }
            $peserta = $kelompok->pesertaLomba;
            if (!$peserta) {
                $maxUrut = PesertaLomba::where('lomba_id', $kelompok->lomba_id)->max('nomor_urut') ?? 0;
                $peserta = PesertaLomba::create([
                    'lomba_id'          => $kelompok->lomba_id,
                    'kelompok_lomba_id' => $kelompok->id,
                    'student_id'        => null,
                    'nomor_urut'        => $maxUrut + 1,
                    'status'            => 'Terdaftar',
                    'haflah_id'         => session('haflah_id'),
                ]);
            }
            $request->merge([
                'peserta_lomba_id' => $peserta->id,
                'juri_lomba_id'    => $juriId,
            ]);
        } elseif ($request->filled('lomba_id') && Lomba::find($request->lomba_id)?->jenis === 'Tim') {
            return back()->with('toast_error', 'Kelompok tidak terpilih. Silakan pilih kelompok terlebih dahulu.')->withInput();
        }

        $request->validate([
            'peserta_lomba_id' => 'required|exists:peserta_lombas,id',
            'juri_lomba_id' => 'required|exists:juri_lombas,id',
            'aspek_penilaian_id' => 'required|array|min:1',
            'aspek_penilaian_id.*' => 'required|exists:aspek_penilaians,id',
            'nilai' => 'required|array|min:1',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ]);

        $haflahId = session('haflah_id');

        $data = [];
        foreach ($request->aspek_penilaian_id as $i => $aspekId) {
            $data[] = [
                'haflah_id'          => $haflahId,
                'peserta_lomba_id'   => $request->peserta_lomba_id,
                'juri_lomba_id'      => $request->juri_lomba_id,
                'aspek_penilaian_id' => $aspekId,
                'nilai'              => $request->nilai[$i] ?? 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        PenilaianLomba::insertOrIgnore($data);

        $total = count($data);
        return redirect()->route('penilaian-lomba.index')
            ->with('success', "$total penilaian berhasil ditambahkan.");
    }

    public function show($id)
    {
        $penilaianLomba = PenilaianLomba::with([
                'pesertaLomba.student.user',
                'pesertaLomba.kelompokLomba.anggota.student.user',
                'pesertaLomba.lomba',
                'juriLomba.guru',
                'aspekPenilaian',
            ])
            ->findOrFail($id);

        $allPenilaian = PenilaianLomba::with(['aspekPenilaian', 'juriLomba.guru'])
            ->where('peserta_lomba_id', $penilaianLomba->peserta_lomba_id)
            ->get()
            ->groupBy('juri_lomba_id')
            ->map(function ($items) {
                $first = $items->first();
                return (object) [
                    'juri'      => $first->juriLomba,
                    'penilaian' => $items,
                    'total'     => $items->sum('nilai'),
                ];
            })
            ->values();

        $totalSemua = $allPenilaian->sum('total');
        $jumlahJuri = $allPenilaian->count();

        return view('admin.penilaian-lomba.show', compact('penilaianLomba', 'allPenilaian', 'totalSemua', 'jumlahJuri'));
    }

    public function edit($id)
    {
        $penilaianLomba = PenilaianLomba::with(['pesertaLomba.lomba', 'juriLomba'])->findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($penilaianLomba->haflah_id)) {
            return $redirect;
        }

        $lomba = $penilaianLomba->pesertaLomba->lomba;
        $lombaId = $lomba->id;
        $currentSesiId = $lomba->sesi_lomba_id ?? null;

        $sesiLombas = SesiLomba::orderBy('nama')->get();
        $lombas = Lomba::where('sesi_lomba_id', $currentSesiId)->orderBy('nama')->get();
        $pesertaLombas = PesertaLomba::with('student.user')->where('lomba_id', $lombaId)->get();
        $juriLombas = JuriLomba::with('guru')->where('lomba_id', $lombaId)->get();

        $aspekPenilaians = AspekPenilaian::where('lomba_id', $lombaId)->get();

        $allPenilaian = PenilaianLomba::where('peserta_lomba_id', $penilaianLomba->peserta_lomba_id)
            ->where('juri_lomba_id', $penilaianLomba->juri_lomba_id)
            ->get()
            ->keyBy('aspek_penilaian_id');

        $lombaJenis = $lomba->jenis;
        $kelompokLombas = collect();
        $currentKelompokId = null;
        if ($lombaJenis === 'Tim') {
            $kelompokLombas = KelompokLomba::where('lomba_id', $lombaId)->orderBy('nama_kelompok')->get();
            $currentKelompokId = $penilaianLomba->pesertaLomba->kelompok_lomba_id;
        }

        return view('admin.penilaian-lomba.edit', compact(
            'penilaianLomba', 'sesiLombas', 'currentSesiId', 'lombas',
            'pesertaLombas', 'juriLombas', 'aspekPenilaians', 'allPenilaian',
            'lombaJenis', 'kelompokLombas', 'currentKelompokId'
        ));
    }

    public function update(Request $request, $id)
    {
        $penilaianLomba = PenilaianLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($penilaianLomba->haflah_id)) {
            return $redirect;
        }

        if ($request->filled('kelompok_lomba_id')) {
            $kelompok = KelompokLomba::findOrFail($request->kelompok_lomba_id);
            $juriId = $this->resolveTimJuriId($kelompok->lomba_id, $request->input('juri_lomba_id'));
            if (!$juriId) {
                return back()->with('toast_error', 'Tidak ada juri untuk lomba ini. Tambahkan juri terlebih dahulu.')->withInput();
            }
            $peserta = $kelompok->pesertaLomba;
            if (!$peserta) {
                $maxUrut = PesertaLomba::where('lomba_id', $kelompok->lomba_id)->max('nomor_urut') ?? 0;
                $peserta = PesertaLomba::create([
                    'lomba_id'          => $kelompok->lomba_id,
                    'kelompok_lomba_id' => $kelompok->id,
                    'student_id'        => null,
                    'nomor_urut'        => $maxUrut + 1,
                    'status'            => 'Terdaftar',
                    'haflah_id'         => session('haflah_id'),
                ]);
            }
            $request->merge([
                'peserta_lomba_id' => $peserta->id,
                'juri_lomba_id'    => $juriId,
            ]);
        }

        $request->validate([
            'peserta_lomba_id' => 'required|exists:peserta_lombas,id',
            'juri_lomba_id' => 'required|exists:juri_lombas,id',
            'aspek_penilaian_id' => 'required|array|min:1',
            'aspek_penilaian_id.*' => 'required|exists:aspek_penilaians,id',
            'nilai' => 'required|array|min:1',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ]);

        PenilaianLomba::where('peserta_lomba_id', $penilaianLomba->peserta_lomba_id)
            ->where('juri_lomba_id', $penilaianLomba->juri_lomba_id)
            ->delete();

        $haflahId = session('haflah_id');
        $data = [];
        foreach ($request->aspek_penilaian_id as $i => $aspekId) {
            $data[] = [
                'haflah_id'          => $haflahId,
                'peserta_lomba_id'   => $request->peserta_lomba_id,
                'juri_lomba_id'      => $request->juri_lomba_id,
                'aspek_penilaian_id' => $aspekId,
                'nilai'              => $request->nilai[$i] ?? 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        PenilaianLomba::insert($data);

        return redirect()->route('penilaian-lomba.index')
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penilaianLomba = PenilaianLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($penilaianLomba->haflah_id)) {
            return $redirect;
        }

        $deleted = PenilaianLomba::where('peserta_lomba_id', $penilaianLomba->peserta_lomba_id)
            ->where('juri_lomba_id', $penilaianLomba->juri_lomba_id)
            ->delete();

        return redirect()->route('penilaian-lomba.index')
            ->with('success', "$deleted penilaian berhasil dihapus.");
    }

    public function destroyByPeserta($pesertaLombaId)
    {
        $sample = PenilaianLomba::where('peserta_lomba_id', $pesertaLombaId)->first();

        if (!$sample) {
            return redirect()->route('penilaian-lomba.index')
                ->with('error', 'Data penilaian tidak ditemukan.');
        }

        if ($redirect = $this->blockIfHaflahSelesai($sample->haflah_id)) {
            return $redirect;
        }

        $deleted = PenilaianLomba::where('peserta_lomba_id', $pesertaLombaId)->delete();

        return redirect()->route('penilaian-lomba.index')
            ->with('success', "$deleted penilaian berhasil dihapus.");
    }

    public function getPeserta(Request $request, $lombaId)
    {
        $juriId = $request->query('juri_lomba_id');

        $query = PesertaLomba::with('student.user')
            ->where('lomba_id', $lombaId)
            ->whereNotNull('student_id');

        if ($juriId) {
            $query->whereDoesntHave('penilaian', function ($q) use ($juriId) {
                $q->where('juri_lomba_id', $juriId);
            });
        } else {
            $query->whereDoesntHave('penilaian');
        }

        $peserta = $query->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'text' => $p->student->user->name ?? $p->student->nama ?? 'Peserta #' . $p->id,
                ];
            });

        return response()->json($peserta);
    }

    public function getLomba(Request $request, $sesiLombaId)
    {
        $query = Lomba::where('sesi_lomba_id', $sesiLombaId);

        $jenis = $request->query('jenis');
        if (in_array($jenis, ['Individu', 'Tim'], true)) {
            $query->where('jenis', $jenis);
        }

        $lomba = $query->orderBy('nama')
            ->get()
            ->map(function ($l) {
                $label = $l->nama . ' (' . $l->jenis . ')';
                return [
                    'id'    => $l->id,
                    'text'  => $label,
                    'jenis' => $l->jenis,
                ];
            });

        return response()->json($lomba);
    }

    public function getJuri($lombaId)
    {
        $juri = JuriLomba::with('guru')
            ->where('lomba_id', $lombaId)
            ->get()
            ->map(function ($j) {
                return [
                    'id' => $j->id,
                    'text' => $j->guru->nama ?? 'Juri #' . $j->id,
                ];
            });

        return response()->json($juri);
    }

    public function getAspek($lombaId)
    {
        $aspek = AspekPenilaian::where('lomba_id', $lombaId)
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id,
                    'text' => $a->nama_aspek,
                    'nama_aspek' => $a->nama_aspek,
                ];
            });

        return response()->json($aspek);
    }

    public function getKelompok(Request $request, $lombaId)
    {
        $juriId = $request->query('juri_lomba_id');

        $query = PesertaLomba::where('lomba_id', $lombaId)
            ->whereNotNull('kelompok_lomba_id');

        if ($juriId) {
            $query->whereHas('penilaian', function ($q) use ($juriId) {
                $q->where('juri_lomba_id', $juriId);
            });
        } else {
            $query->whereHas('penilaian');
        }

        $sudahDinilaiKelompokIds = $query->pluck('kelompok_lomba_id')
            ->toArray();

        $kelompok = KelompokLomba::where('lomba_id', $lombaId)
            ->whereNotIn('id', $sudahDinilaiKelompokIds)
            ->orderBy('nama_kelompok')
            ->get()
            ->map(function ($k) {
                $label = $k->nama_kelompok;
                if ($k->kode_kelompok) {
                    $label = $k->kode_kelompok . ' - ' . $label;
                }
                return [
                    'id'   => $k->id,
                    'text' => $label,
                ];
            });

        return response()->json($kelompok);
    }
}
