<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jenjang;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Daftar kelas
     */
    public function index()
    {
        $kelas = Kelas::with([
            'jenjang',
            'waliKelas.guru',
            'siswaAktif'
        ])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $jenjangs = Jenjang::all();

        return view(
            'admin.kelas.index',
            compact(
                'kelas',
                'jenjangs'
            )
        );
    }


    /**
     * Form tambah kelas
     */
    public function create()
    {
        $jenjangs = Jenjang::all();

        return view(
            'admin.kelas.create',
            compact('jenjangs')
        );
    }

    /**
     * Simpan kelas
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenjang_id' => 'required|exists:jenjangs,id',
            'tingkat' => 'required|integer',
            'nama_kelas' => 'required|max:10',
        ]);

        $jenjang = Jenjang::findOrFail($request->jenjang_id);

        $request->validate([
            'tingkat' => 'integer|min:' . $jenjang->tingkat_awal . '|max:' . $jenjang->tingkat_akhir,
        ]);

        $cek = Kelas::where('jenjang_id', $request->jenjang_id)
            ->where('tingkat', $request->tingkat)
            ->exists();

        if ($cek) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kelas sudah terdaftar.'
                );
        }

        Kelas::create([
            'jenjang_id' => $request->jenjang_id,
            'tingkat' => $request->tingkat,
            'nama_kelas' => strtoupper($request->nama_kelas),
        ]);

        return redirect()
            ->route('kelas.index')
            ->with(
                'success',
                'Kelas berhasil ditambahkan.'
            );
    }

    /**
     * Form edit kelas
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $jenjangs = Jenjang::all();

        return view(
            'admin.kelas.edit',
            compact(
                'kelas',
                'jenjangs'
            )
        );
    }

    /**
     * Update kelas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenjang_id' => 'required|exists:jenjangs,id',
            'tingkat' => 'required|integer',
            'nama_kelas' => 'required|max:10',
        ]);

        $jenjang = Jenjang::findOrFail($request->jenjang_id);

        $request->validate([
            'tingkat' => 'integer|min:' . $jenjang->tingkat_awal . '|max:' . $jenjang->tingkat_akhir,
        ]);

        $kelas = Kelas::findOrFail($id);

        $cek = Kelas::where('jenjang_id', $request->jenjang_id)
            ->where('tingkat', $request->tingkat)
            ->where('id', '!=', $id)
            ->exists();

        if ($cek) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kelas sudah terdaftar.'
                );
        }

        $kelas->update([
            'jenjang_id' => $request->jenjang_id,
            'tingkat' => $request->tingkat,
            'nama_kelas' => strtoupper($request->nama_kelas),
        ]);

        return redirect()
            ->route('kelas.index')
            ->with(
                'success',
                'Kelas berhasil diperbarui.'
            );
    }

    /**
     * Hapus kelas
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->anggota()->count() > 0) {
            return back()->with(
                'error',
                'Kelas tidak dapat dihapus karena masih memiliki siswa.'
            );
        }

        $kelas->delete();

        return redirect()
            ->route('kelas.index')
            ->with(
                'success',
                'Kelas berhasil dihapus.'
            );
    }
    public function show($id)
    {
        $kelas = Kelas::with([
            'jenjang',
            'waliKelas.guru',
            'siswaAktif.student'
        ])->findOrFail($id);

        return view('admin.kelas.show', compact('kelas'));
    }
}
