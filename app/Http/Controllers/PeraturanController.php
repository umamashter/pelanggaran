<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use App\Models\JenisPeraturan;
use Illuminate\Http\Request;

class PeraturanController extends Controller
{
    public function index()
    {
        // Ambil semua peraturan & jenis_peraturan untuk kebutuhan modal.
        $peraturan = Peraturan::with('jenisPeraturan')->get();
        $jenisPeraturan = JenisPeraturan::all();

        return view('admin.peraturan.index', compact('peraturan', 'jenisPeraturan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|numeric',
            'jenis_peraturan_id' => 'required|exists:jenis_peraturan,id',
        ]);

        Peraturan::create([
            'nama' => $request->nama,
            'poin' => $request->poin,
            'jenis_peraturan_id' => $request->jenis_peraturan_id,
        ]);

        return redirect('/peraturan')->with('success', 'Peraturan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|numeric',
            'jenis_peraturan_id' => 'required|exists:jenis_peraturan,id',
        ]);

        $peraturan = Peraturan::findOrFail($id);
        $peraturan->update([
            'nama' => $request->nama,
            'poin' => $request->poin,
            'jenis_peraturan_id' => $request->jenis_peraturan_id,
        ]);

        return redirect('/peraturan')->with('success', 'Peraturan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->delete();

        return redirect('/peraturan')->with('success', 'Peraturan berhasil dihapus!');
    }
}
