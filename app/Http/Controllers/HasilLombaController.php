<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HasilLomba;
use App\Models\Lomba;
use App\Models\PesertaLomba;
use App\Models\PenilaianLomba;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class HasilLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $tab = in_array($request->input('tab', 'individu'), ['individu', 'kelompok']) ? $request->input('tab', 'individu') : 'individu';

        $query = HasilLomba::with([
                'lomba',
                'pesertaLomba.student.user',
                'pesertaLomba.kelompokLomba.anggota.student.user',
            ])
            ->select('hasil_lombas.*')
            ->selectSub(function ($q) {
                $q->from('penilaian_lombas')
                    ->whereColumn('penilaian_lombas.peserta_lomba_id', 'hasil_lombas.peserta_lomba_id')
                    ->selectRaw('COALESCE(SUM(nilai), 0)');
            }, 'total_dari_penilaian')
            ->whereHas('pesertaLomba', function ($q) use ($tab) {
                if ($tab === 'individu') {
                    $q->whereNotNull('student_id');
                } else {
                    $q->whereNotNull('kelompok_lomba_id');
                }
            });

        if ($request->filled('lomba_id')) {
            $query->where('lomba_id', $request->lomba_id);
        }

        if ($request->filled('juara')) {
            $query->where('juara', $request->juara);
        }

        if ($search = trim($request->nama ?? '')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('lomba', function ($l) use ($search) {
                    $l->where('nama', 'like', "%{$search}%");
                })->orWhereHas('pesertaLomba.student.user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('pesertaLomba.kelompokLomba', function ($k) use ($search) {
                    $k->where('nama_kelompok', 'like', "%{$search}%");
                });
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $hasilLombas = $query->latest()->paginate($perPage)->withQueryString();

        $lombas = Lomba::whereHas('hasil')->orderBy('nama')->get();
        $juaraList = HasilLomba::select('juara')->distinct()->whereNotNull('juara')->orderBy('juara')->pluck('juara');

        return view('admin.hasil-lomba.index', compact('hasilLombas', 'lombas', 'juaraList', 'perPage', 'tab'));
    }

    public function create()
    {
        $lombas = Lomba::whereHas('peserta.penilaian')->orderBy('nama')->get();

        return view('admin.hasil-lomba.create', compact('lombas'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'peserta_lomba_id' => 'required|array|min:1',
            'peserta_lomba_id.*' => 'exists:peserta_lombas,id',
        ]);

        $totals = PenilaianLomba::whereIn('peserta_lomba_id', $request->peserta_lomba_id)
            ->groupBy('peserta_lomba_id')
            ->select('peserta_lomba_id', DB::raw('COALESCE(SUM(nilai), 0) as total'))
            ->pluck('total', 'peserta_lomba_id');

        $sorted = collect($request->peserta_lomba_id)->sortByDesc(function ($id) use ($totals) {
            return $totals[$id] ?? 0;
        })->values();

        $count = 0;
        foreach ($sorted as $i => $pesertaId) {
            $peringkat = $i + 1;
            HasilLomba::updateOrCreate(
                ['lomba_id' => $request->lomba_id, 'peserta_lomba_id' => $pesertaId],
                [
                    'total_nilai' => $totals[$pesertaId] ?? 0,
                    'peringkat' => $peringkat,
                    'juara' => "Juara $peringkat",
                ]
            );
            $count++;
        }

        $all = HasilLomba::where('lomba_id', $request->lomba_id)
            ->orderByDesc('total_nilai')
            ->get();

        foreach ($all as $i => $h) {
            $p = $i + 1;
            $h->update([
                'peringkat' => $p,
                'juara' => "Juara $p",
            ]);
        }

        return redirect()->route('hasil-lomba.index')
            ->with('success', "$count hasil lomba berhasil disimpan.");
    }

    public function show($id)
    {
        $hasilLomba = HasilLomba::with([
                'lomba.sesiLomba',
                'lomba.juri.guru',
                'pesertaLomba.student.user',
                'pesertaLomba.kelompokLomba.anggota.student.user',
            ])
            ->select('hasil_lombas.*')
            ->selectSub(function ($q) {
                $q->from('penilaian_lombas')
                    ->whereColumn('penilaian_lombas.peserta_lomba_id', 'hasil_lombas.peserta_lomba_id')
                    ->selectRaw('COALESCE(SUM(nilai), 0)');
            }, 'total_dari_penilaian')
            ->findOrFail($id);

        return view('admin.hasil-lomba.show', compact('hasilLomba'));
    }

    public function edit($id)
    {
        $hasilLomba = HasilLomba::with([
                'lomba',
                'pesertaLomba.student.user',
                'pesertaLomba.kelompokLomba.anggota.student.user',
            ])->findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($hasilLomba->haflah_id)) {
            return $redirect;
        }

        return view('admin.hasil-lomba.edit', compact('hasilLomba'));
    }

    public function update(Request $request, $id)
    {
        $hasilLomba = HasilLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($hasilLomba->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'peserta_lomba_id' => 'required|exists:peserta_lombas,id',
        ]);

        $totalNilai = PenilaianLomba::where('peserta_lomba_id', $request->peserta_lomba_id)
            ->sum('nilai');

        HasilLomba::where('id', $id)->update([
            'lomba_id' => $request->lomba_id,
            'peserta_lomba_id' => $request->peserta_lomba_id,
            'total_nilai' => $totalNilai,
        ]);

        $all = HasilLomba::where('lomba_id', $request->lomba_id)
            ->orderByDesc('total_nilai')
            ->get();

        foreach ($all as $i => $h) {
            $p = $i + 1;
            $h->update([
                'peringkat' => $p,
                'juara' => "Juara $p",
            ]);
        }

        return redirect()->route('hasil-lomba.index')
            ->with('success', 'Hasil lomba berhasil diperbarui.');
    }

    public function getPesertaWithTotal($lombaId)
    {
        $peserta = PesertaLomba::with(['student.user', 'kelompokLomba'])
            ->where('lomba_id', $lombaId)
            ->withSum('penilaian as total_nilai', 'nilai')
            ->get()
            ->map(function ($p) {
                $nama = $p->isIndividu()
                    ? ($p->student->user->name ?? $p->student->nama ?? 'Peserta #' . $p->id)
                    : (optional($p->kelompokLomba)->nama_kelompok ?? 'Kelompok #' . $p->id);

                return [
                    'id' => $p->id,
                    'text' => $nama,
                    'total_nilai' => $p->total_nilai ?? 0,
                ];
            });

        return response()->json($peserta);
    }

    public function destroy($id)
    {
        $hasilLomba = HasilLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($hasilLomba->haflah_id)) {
            return $redirect;
        }

        $lombaId = $hasilLomba->lomba_id;
        $hasilLomba->delete();

        $all = HasilLomba::where('lomba_id', $lombaId)
            ->orderByDesc('total_nilai')
            ->get();

        foreach ($all as $i => $h) {
            $p = $i + 1;
            $h->update([
                'peringkat' => $p,
                'juara' => "Juara $p",
            ]);
        }

        return redirect()->route('hasil-lomba.index')
            ->with('success', 'Hasil lomba berhasil dihapus.');
    }
}
