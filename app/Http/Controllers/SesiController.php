<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sesi;
use App\Models\SesiLomba;
use App\Models\HaflatulImtihan;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class SesiController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = Sesi::orderBy('nama');

        if ($request->filled('haflah_id')) {
            $query->withoutGlobalScope(HaflahScope::class)
                  ->where('haflah_id', $request->haflah_id);
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $sesis = $query->paginate($perPage)->withQueryString();

        $haflatuls = HaflatulImtihan::with('tahunAjaran')->orderBy('nama_acara')->get();

        $usedNames = SesiLomba::select('nama')->distinct()->pluck('nama')->toArray();

        return view('admin.sesi.index', compact('sesis', 'haflatuls', 'usedNames', 'perPage'));
    }

    public function create()
    {
        return view('admin.sesi.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $request->validate([
            'nama' => 'required|max:255',
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
        ]);

        Sesi::create($request->only(['nama', 'tanggal', 'jam_mulai', 'jam_selesai']));

        return redirect()->route('sesi.index')
            ->with('success', 'Nama sesi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sesi = Sesi::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($sesi->haflah_id)) {
            return $redirect;
        }

        if (SesiLomba::where('nama', $sesi->nama)->exists()) {
            return redirect()->route('sesi.index')
                ->with('error', 'Nama sesi "' . $sesi->nama . '" sedang digunakan di sesi lomba, tidak dapat diedit.');
        }

        return view('admin.sesi.edit', compact('sesi'));
    }

    public function update(Request $request, $id)
    {
        $sesi = Sesi::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($sesi->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'nama' => 'required|max:255',
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
        ]);

        $sesi->update($request->only(['nama', 'tanggal', 'jam_mulai', 'jam_selesai']));

        return redirect()->route('sesi.index')
            ->with('success', 'Nama sesi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sesi = Sesi::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($sesi->haflah_id)) {
            return $redirect;
        }

        if (SesiLomba::where('nama', $sesi->nama)->exists()) {
            return redirect()->route('sesi.index')
                ->with('error', 'Nama sesi "' . $sesi->nama . '" sedang digunakan di sesi lomba, tidak dapat dihapus.');
        }

        $sesi->delete();

        return redirect()->route('sesi.index')
            ->with('success', 'Nama sesi berhasil dihapus.');
    }
}
