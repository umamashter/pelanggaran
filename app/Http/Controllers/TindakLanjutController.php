<?php

namespace App\Http\Controllers;

use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller
{
    public function index()
    {
        // Mengambil semua data tindakan dan kirim ke view
        $data = TindakLanjut::all();
        return view('admin.tindak_lanjut.index', compact('data'));
    }

    public function create()
    {
        // (Tidak digunakan jika pakai modal untuk tambah)
        return view('admin.tindak_lanjut.create');
    }

    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'tindak_lanjut' => 'required|string|max:255',
            'tingkatan' => 'required|in:Ringan,Sedang,Berat',
        ]);

        // Simpan data
        TindakLanjut::create($request->all());

        return redirect()->route('tindak-lanjut.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Tidak digunakan jika pakai modal edit
        $data = TindakLanjut::findOrFail($id);
        return view('admin.tindak_lanjut.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'tindak_lanjut' => 'required|string|max:255',
            'tingkatan' => 'required|in:Ringan,Sedang,Berat',
        ]);

        // Update
        $tindakLanjut = TindakLanjut::findOrFail($id);
        $tindakLanjut->update($request->all());

        return redirect()->route('tindak-lanjut.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        // Hapus
        $tindakLanjut = TindakLanjut::findOrFail($id);
        $tindakLanjut->delete();

        return redirect()->route('tindak-lanjut.index')->with('success', 'Data berhasil dihapus.');
    }
}
