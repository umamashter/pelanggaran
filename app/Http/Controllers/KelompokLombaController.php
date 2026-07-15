<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KelompokLomba;
use App\Models\Lomba;
use App\Models\HaflatulImtihan;
use App\Models\PesertaLomba;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class KelompokLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = KelompokLomba::with('lomba.sesiLomba')->withCount('anggota')->latest();
        $perPage = (int) $request->input('per_page', 10);

        if ($request->filled('kelas')) {
            $kelas = (int) $request->kelas;
            $query->whereHas('lomba', function ($q) use ($kelas) {
                $q->where(function ($sub) use ($kelas) {
                    $sub->where(function ($range) use ($kelas) {
                        $range->whereNull('kelas_min')
                            ->whereNull('kelas_max');
                    })->orWhere(function ($range) use ($kelas) {
                        $range->where(function ($minMax) use ($kelas) {
                            $minMax->where('kelas_min', '<=', $kelas)
                                   ->where('kelas_max', '>=', $kelas);
                        });
                    });
                });
            });
        }

        if ($request->filled('lomba_id')) {
            $query->where('lomba_id', $request->lomba_id);
        }

        $kelompokLombas = $query->paginate($perPage)->withQueryString();

        $haflatuls = HaflatulImtihan::with('tahunAjaran')->orderBy('nama_acara')->get();

        $lombas = Lomba::where('jenis', 'Tim')->orderBy('nama')->get();

        return view('admin.kelompok-lomba.index', compact('kelompokLombas', 'haflatuls', 'lombas', 'perPage'));
    }

    public function cetakPdf(Request $request)
    {
        $pdf = Pdf::loadView('admin.kelompok-lomba.pdf', $this->buildJadwalData($request))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('daftar-kelompok-lomba.pdf');
    }

    public function printPreview(Request $request)
    {
        return view('admin.kelompok-lomba.print', $this->buildJadwalData($request));
    }

    private function buildJadwalData(Request $request)
    {
        $query = KelompokLomba::with(['lomba.sesiLomba', 'anggota.student.user', 'anggota.student.kelasAktif.kelas.jenjang']);

        if ($request->filled('haflah_id')) {
            $query->withoutGlobalScope(HaflahScope::class)
                ->where('haflah_id', $request->haflah_id);
        }

        if ($request->filled('kelas')) {
            $kelas = (int) $request->kelas;
            $query->whereHas('lomba', function ($q) use ($kelas) {
                $q->where(function ($sub) use ($kelas) {
                    $sub->where(function ($range) use ($kelas) {
                        $range->whereNull('kelas_min')
                            ->whereNull('kelas_max');
                    })->orWhere(function ($range) use ($kelas) {
                        $range->where(function ($minMax) use ($kelas) {
                            $minMax->where('kelas_min', '<=', $kelas)
                                ->where('kelas_max', '>=', $kelas);
                        });
                    });
                });
            });
        }

        if ($request->filled('lomba_id')) {
            $query->where('lomba_id', $request->lomba_id);
        }

        $kelompoks = $query->get()->sort(function ($a, $b) {
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

            return strcmp((string) ($a->nama_kelompok ?? ''), (string) ($b->nama_kelompok ?? ''));
        })->values();

        $haflah = null;
        if ($request->filled('haflah_id')) {
            $haflah = HaflatulImtihan::with('tahunAjaran')->find($request->haflah_id);
        } elseif ($kelompoks->isNotEmpty()) {
            $haflah = $kelompoks->first()->haflatulImtihan()->with('tahunAjaran')->first();
        }

        $tahunAjaran = optional(optional($haflah)->tahunAjaran)->tahun_ajaran;
        $semester = optional(optional(optional($haflah)->tahunAjaran)->semesterAktif)->nama;

        $jadwal = $kelompoks->groupBy(function ($item) {
            return optional(optional($item->lomba)->sesiLomba)->id ?: 'tanpa-sesi';
        })->map(function ($sesiItems) {
            $first = $sesiItems->first();
            $sesi = optional(optional($first->lomba)->sesiLomba);

            $lombas = $sesiItems->groupBy(function ($item) {
                return optional($item->lomba)->id ?: 'tanpa-lomba';
            })->map(function ($lombaItems) {
                $firstLomba = $lombaItems->first();
                $lomba = optional($firstLomba->lomba);

                $kelompoks = $lombaItems->map(function ($kelompok) {
                    $anggota = $kelompok->anggota->sortBy(function ($item) {
                        return optional(optional($item->student)->user)->name ?? '';
                    })->values();

                    return [
                        'nama_kelompok' => $kelompok->nama_kelompok,
                        'anggota' => $anggota->isEmpty() ? collect([null]) : $anggota,
                        'rowspan' => max(1, $anggota->count()),
                    ];
                })->values();

                return [
                    'nama' => $lomba->nama ?? '-',
                    'rowspan' => $kelompoks->sum('rowspan'),
                    'kelompoks' => $kelompoks,
                ];
            })->values();

            return [
                'nama' => $sesi->nama ?? '-',
                'tanggal' => !empty($sesi->tanggal)
                    ? \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d F Y')
                    : '-',
                'rowspan' => $lombas->sum('rowspan'),
                'lombas' => $lombas,
            ];
        })->values();

        return compact('jadwal', 'haflah', 'tahunAjaran', 'semester');
    }

    public function create()
    {
        $lombas = Lomba::where('jenis', 'Tim')->orderBy('nama')->get();

        return view('admin.kelompok-lomba.create', compact('lombas'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'nama_kelompok' => 'required|max:255',
            'asal' => 'nullable|max:255',
        ]);

        $kelompok = KelompokLomba::create($request->all());

        $kelompok->kode_kelompok = 'KLP-' . str_pad($kelompok->id, 4, '0', STR_PAD_LEFT);
        $kelompok->save();

        $maxUrut = PesertaLomba::where('lomba_id', $kelompok->lomba_id)->max('nomor_urut') ?? 0;
        PesertaLomba::create([
            'lomba_id'          => $kelompok->lomba_id,
            'kelompok_lomba_id' => $kelompok->id,
            'student_id'        => null,
            'nomor_urut'        => $maxUrut + 1,
            'status'            => 'Terdaftar',
            'haflah_id'         => session('haflah_id'),
        ]);

        return redirect()->route('kelompok-lomba.index')
            ->with('success', 'Kelompok lomba berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kelompokLomba = KelompokLomba::with(['lomba.sesiLomba', 'anggota.student.user', 'anggota.student.kelasAktif.kelas.jenjang'])->findOrFail($id);

        return view('admin.kelompok-lomba.show', compact('kelompokLomba'));
    }

    public function edit($id)
    {
        $kelompokLomba = KelompokLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($kelompokLomba->haflah_id)) {
            return $redirect;
        }

        $lombas = Lomba::where('jenis', 'Tim')->orderBy('nama')->get();

        return view('admin.kelompok-lomba.edit', compact('kelompokLomba', 'lombas'));
    }

    public function update(Request $request, $id)
    {
        $kelompokLomba = KelompokLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($kelompokLomba->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'nama_kelompok' => 'required|max:255',
            'asal' => 'nullable|max:255',
        ]);

        $kelompokLomba->update($request->only(['lomba_id', 'nama_kelompok', 'asal']));

        return redirect()->route('kelompok-lomba.index')
            ->with('success', 'Kelompok lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelompokLomba = KelompokLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($kelompokLomba->haflah_id)) {
            return $redirect;
        }

        $kelompokLomba->delete();

        return redirect()->route('kelompok-lomba.index')
            ->with('success', 'Kelompok lomba berhasil dihapus.');
    }
}
