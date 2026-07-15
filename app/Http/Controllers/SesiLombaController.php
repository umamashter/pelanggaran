<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiLomba;
use App\Models\HaflatulImtihan;
use App\Models\Sesi;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class SesiLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = SesiLomba::with('haflatulImtihan')->withCount('lombas');

        if ($request->filled('haflah_id')) {
            $query->withoutGlobalScope(HaflahScope::class)
                  ->where('haflah_id', $request->haflah_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('tanggal', 'like', '%' . $search . '%');
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $sesiLombas = $query->latest()->paginate($perPage)->withQueryString();

        $haflatuls = HaflatulImtihan::with('tahunAjaran')->orderBy('nama_acara')->get();

        return view('admin.sesi-lomba.index', compact('sesiLombas', 'haflatuls', 'perPage'));
    }

    public function create()
    {
        $haflahAktif = HaflatulImtihan::find(session('haflah_id'));
        $usedNames = SesiLomba::where('haflah_id', session('haflah_id'))->pluck('nama');
        $sesis = Sesi::whereNotIn('nama', $usedNames)->orderBy('nama')->get();

        return view('admin.sesi-lomba.create', compact('haflahAktif', 'sesis'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai($request->haflah_id)) {
            return $redirect;
        }

        $haflatul = HaflatulImtihan::findOrFail($request->haflah_id);

        $request->validate([
            'haflah_id' => 'required|exists:haflatul_imtihans,id',
            'nama' => 'required|max:255',
            'tanggal' => [
                'required', 'date',
                'after_or_equal:' . $haflatul->tanggal_mulai,
                'before_or_equal:' . $haflatul->tanggal_selesai,
            ],
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        SesiLomba::create($request->all());

        return redirect()->route('sesi-lomba.index')
            ->with('success', 'Sesi lomba berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sesiLomba = SesiLomba::with('haflatulImtihan', 'lombas')->findOrFail($id);

        return view('admin.sesi-lomba.show', compact('sesiLomba'));
    }

    public function edit($id)
    {
        $sesiLomba = SesiLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($sesiLomba->haflah_id)) {
            return $redirect;
        }

        $haflatuls = HaflatulImtihan::orderBy('nama_acara')->get();
        $sesis = Sesi::orderBy('nama')->get();

        return view('admin.sesi-lomba.edit', compact('sesiLomba', 'haflatuls', 'sesis'));
    }

    public function update(Request $request, $id)
    {
        $sesiLomba = SesiLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($sesiLomba->haflah_id)) {
            return $redirect;
        }

        $haflatul = HaflatulImtihan::findOrFail($request->haflah_id);

        $request->validate([
            'haflah_id' => 'required|exists:haflatul_imtihans,id',
            'nama' => 'required|max:255',
            'tanggal' => [
                'required', 'date',
                'after_or_equal:' . $haflatul->tanggal_mulai,
                'before_or_equal:' . $haflatul->tanggal_selesai,
            ],
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $sesiLomba->update($request->all());

        return redirect()->route('sesi-lomba.index')
            ->with('success', 'Sesi lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sesiLomba = SesiLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($sesiLomba->haflah_id)) {
            return $redirect;
        }

        $sesiLomba->delete();

        return redirect()->route('sesi-lomba.index')
            ->with('success', 'Sesi lomba berhasil dihapus.');
    }
}
