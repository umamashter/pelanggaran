<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('tanggal', 'desc')->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:Draft,Published',
        ]);

        Pengumuman::create($request->all());

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:Draft,Published',
        ]);

        $pengumuman->update($request->all());

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
