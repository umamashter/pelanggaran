<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriLomba;
use App\Models\HaflatulImtihan;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class KategoriLombaController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = KategoriLomba::withCount('lombas')->latest();

        if ($request->filled('haflah_id')) {
            $query->withoutGlobalScope(HaflahScope::class)
                  ->where('haflah_id', $request->haflah_id);
        }

        if ($search = trim($request->search ?? '')) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $kategoriLombas = $query->paginate($perPage)->withQueryString();

        $haflatuls = HaflatulImtihan::with('tahunAjaran')->orderBy('nama_acara')->get();

        return view('admin.kategori-lomba.index', compact('kategoriLombas', 'haflatuls', 'perPage'));
    }

    public function create()
    {
        return view('admin.kategori-lomba.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $request->validate([
            'nama' => 'required|max:255',
            'warna' => 'nullable|max:50',
            'icon' => 'nullable|max:255',
            'urutan' => 'required|integer|min:0',
        ]);

        KategoriLomba::create($request->all());

        return redirect()->route('kategori-lomba.index')
            ->with('success', 'Kategori lomba berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kategoriLomba = KategoriLomba::with('lombas')->findOrFail($id);

        return view('admin.kategori-lomba.show', compact('kategoriLomba'));
    }

    public function edit($id)
    {
        $kategoriLomba = KategoriLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($kategoriLomba->haflah_id)) {
            return $redirect;
        }

        return view('admin.kategori-lomba.edit', compact('kategoriLomba'));
    }

    public function update(Request $request, $id)
    {
        $kategoriLomba = KategoriLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($kategoriLomba->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'nama' => 'required|max:255',
            'warna' => 'nullable|max:50',
            'icon' => 'nullable|max:255',
            'urutan' => 'required|integer|min:0',
        ]);

        $kategoriLomba->update($request->all());

        return redirect()->route('kategori-lomba.index')
            ->with('success', 'Kategori lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategoriLomba = KategoriLomba::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($kategoriLomba->haflah_id)) {
            return $redirect;
        }

        $kategoriLomba->delete();

        return redirect()->route('kategori-lomba.index')
            ->with('success', 'Kategori lomba berhasil dihapus.');
    }
}
