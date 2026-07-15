<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\JuriLomba;
use App\Models\Lomba;
use App\Models\Guru;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class JuriLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index()
    {
        $all = JuriLomba::with(['lomba', 'guru'])
            ->withCount('penilaian')
            ->get()
            ->groupBy(fn ($j) => $j->lomba_id);

        $grouped = $all->map(function ($items) {
            $first = $items->first();
            return (object) [
                'lomba_id'         => $first->lomba_id,
                'lomba'            => $first->lomba,
                'nama_juri'        => $items->pluck('guru.nama')->filter()->implode(', '),
                'jumlah_juri'      => $items->count(),
                'penilaian_count'  => $items->sum('penilaian_count'),
                'is_haflah_selesai'=> $first->is_haflah_selesai,
                'latest_id'        => $items->max('id'),
                'all_ids'          => $items->pluck('id')->toArray(),
            ];
        })->sortByDesc('latest_id')->values();

        $page = request()->input('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $grouped->slice(($page - 1) * $perPage, $perPage),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        $juriLombas = $paginated;

        return view('admin.juri-lomba.index', compact('juriLombas'));
    }

    public function create()
    {
        $lombas = Lomba::orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();

        return view('admin.juri-lomba.create', compact('lombas', 'gurus'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'guru_id' => 'required|array|min:1',
            'guru_id.*' => 'required|exists:gurus,id',
        ]);

        foreach ($request->guru_id as $guruId) {
            JuriLomba::create([
                'lomba_id' => $request->lomba_id,
                'guru_id'  => $guruId,
            ]);
        }

        return redirect()->route('juri-lomba.index')
            ->with('success', count($request->guru_id) . ' juri lomba berhasil ditambahkan.');
    }

    public function show($id)
    {
        $juriLomba = JuriLomba::with(['lomba', 'guru'])->findOrFail($id);
        $allJuri = JuriLomba::with('guru')
            ->where('lomba_id', $juriLomba->lomba_id)
            ->withCount('penilaian')
            ->get();

        return view('admin.juri-lomba.show', compact('juriLomba', 'allJuri'));
    }

    public function edit($id)
    {
        $juriLomba = JuriLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($juriLomba->haflah_id)) {
            return $redirect;
        }

        $lombas = Lomba::orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();
        $existingJuri = JuriLomba::where('lomba_id', $juriLomba->lomba_id)->pluck('guru_id')->toArray();

        return view('admin.juri-lomba.edit', compact('juriLomba', 'lombas', 'gurus', 'existingJuri'));
    }

    public function update(Request $request, $id)
    {
        $juriLomba = JuriLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($juriLomba->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'guru_id' => 'required|array|min:1',
            'guru_id.*' => 'required|exists:gurus,id',
        ]);

        JuriLomba::where('lomba_id', $juriLomba->lomba_id)->delete();

        foreach ($request->guru_id as $guruId) {
            JuriLomba::create([
                'lomba_id' => $request->lomba_id,
                'guru_id'  => $guruId,
            ]);
        }

        return redirect()->route('juri-lomba.index')
            ->with('success', count($request->guru_id) . ' juri lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $juriLomba = JuriLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($juriLomba->haflah_id)) {
            return $redirect;
        }

        $juriLomba->delete();

        return redirect()->route('juri-lomba.index')
            ->with('success', 'Juri lomba berhasil dihapus.');
    }
}
