<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lomba;
use App\Models\HaflatulImtihan;
use App\Models\SesiLomba;
use App\Models\KategoriLomba;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class LombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = Lomba::with(['haflatulImtihan', 'sesiLomba', 'kategori'])->withCount(['peserta', 'kelompok', 'juri', 'aspekPenilaians', 'hasil']);

        if ($request->filled('haflah_id')) {
            $query->withoutGlobalScope(HaflahScope::class)
                  ->where('haflah_id', $request->haflah_id);
        }
        if ($request->filled('kategori_lomba_id')) {
            $query->where('kategori_lomba_id', $request->kategori_lomba_id);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }
        $lombas = $query->latest()->paginate($perPage)->withQueryString();

        $haflatuls = HaflatulImtihan::with('tahunAjaran')->orderBy('nama_acara')->get();
        $kategoriLombas = KategoriLomba::orderBy('urutan')->get();

        return view('admin.lomba.index', compact('lombas', 'haflatuls', 'kategoriLombas', 'perPage'));
    }

    public function create()
    {
        $sesiLombas = SesiLomba::orderBy('nama')->get();
        $kategoriLombas = KategoriLomba::orderBy('urutan')->get();
        $tingkatList = range(1, 12);

        return view('admin.lomba.create', compact('sesiLombas', 'kategoriLombas', 'tingkatList'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai($request->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'haflah_id' => 'required|exists:haflatul_imtihans,id',
            'sesi_lomba_id' => 'nullable|exists:sesi_lombas,id',
            'kategori_lomba_id' => 'nullable|exists:kategori_lombas,id',
            'nama' => 'required|max:255',
            'jenis' => 'required|in:Individu,Tim',
            'lokasi' => 'nullable|max:255',
            'deskripsi' => 'nullable',
            'status' => 'required|in:Belum Mulai,Berlangsung,Selesai',
            'kelas_min' => 'nullable|integer|min:1|max:12',
            'kelas_max' => 'nullable|integer|min:1|max:12|gte:kelas_min',
        ]);

        Lomba::create($request->all());

        return redirect()->route('lomba.index')
            ->with('success', 'Lomba berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lomba = Lomba::with([
            'haflatulImtihan', 'sesiLomba', 'kategori',
            'peserta.student', 'juri.guru'
        ])->findOrFail($id);

        $pesertaTerdaftar = $lomba->peserta->count();
        $totalJuri = $lomba->juri->count();

        if ($lomba->kelas_min && $lomba->kelas_max) {
            $totalPesertaLomba = \App\Models\StudentKelas::where('aktif', true)
                ->whereHas('kelas', function ($q) use ($lomba) {
                    $q->whereRaw('CAST(tingkat AS UNSIGNED) BETWEEN ? AND ?', [$lomba->kelas_min, $lomba->kelas_max]);
                })
                ->distinct('student_id')
                ->count('student_id');
        } else {
            $totalPesertaLomba = \App\Models\StudentKelas::where('aktif', true)
                ->distinct('student_id')
                ->count('student_id');
        }

        $pesertaBelumTerdaftar = max(0, $totalPesertaLomba - $pesertaTerdaftar);

        return view('admin.lomba.show', compact(
            'lomba',
            'totalPesertaLomba',
            'pesertaTerdaftar',
            'pesertaBelumTerdaftar',
            'totalJuri'
        ));
    }

    public function edit($id)
    {
        $lomba = Lomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($lomba->haflah_id)) {
            return $redirect;
        }

        $sesiLombas = SesiLomba::orderBy('nama')->get();
        $kategoriLombas = KategoriLomba::orderBy('urutan')->get();
        $tingkatList = range(1, 12);

        return view('admin.lomba.edit', compact('lomba', 'sesiLombas', 'kategoriLombas', 'tingkatList'));
    }

    public function update(Request $request, $id)
    {
        $lomba = Lomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($lomba->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'sesi_lomba_id' => 'nullable|exists:sesi_lombas,id',
            'kategori_lomba_id' => 'nullable|exists:kategori_lombas,id',
            'nama' => 'required|max:255',
            'jenis' => 'required|in:Individu,Tim',
            'lokasi' => 'nullable|max:255',
            'deskripsi' => 'nullable',
            'status' => 'required|in:Belum Mulai,Berlangsung,Selesai',
            'kelas_min' => 'nullable|integer|min:1|max:12',
            'kelas_max' => 'nullable|integer|min:1|max:12|gte:kelas_min',
        ]);

        $lomba->update($request->all());

        return redirect()->route('lomba.index')
            ->with('success', 'Lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lomba = Lomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($lomba->haflah_id)) {
            return $redirect;
        }

        $lomba->delete();

        return redirect()->route('lomba.index')
            ->with('success', 'Lomba berhasil dihapus.');
    }
}
