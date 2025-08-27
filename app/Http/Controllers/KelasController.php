<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar kelas.
     */
    public function index()
    {
        $kelas = Kelas::all();
        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Tampilkan form tambah kelas.
     */
    public function create()
    {
        return view('admin.kelas.create');
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
$request->validate([
        'nama_kelas' => [
            'required',
            'string',
            'unique:kelas,nama_kelas',
            'regex:/^[^0-9]*$/', // tidak boleh ada angka
        ],
    ], [
        'nama_kelas.required' => 'Nama kelas wajib diisi.',
        'nama_kelas.unique' => 'Nama kelas sudah terdaftar.',
        'nama_kelas.regex' => 'Nama kelas harus angka romawi.',
    ]);


        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kelas.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    /**
     * Update data kelas.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
        'nama_kelas' => [
            'required',
            'unique:kelas,nama_kelas,' . $id,
            'regex:/^[^0-9]*$/', // tidak boleh ada angka
        ],
    ], [
        'nama_kelas.required' => 'Nama kelas wajib diisi.',
        'nama_kelas.unique' => 'Nama kelas sudah digunakan.',
        'nama_kelas.regex' => 'Nama kelas harus angka romawi.',
    ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
