<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use App\Models\JenisPeraturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PeraturanController extends Controller
{
    public function index()
    {
        // Ambil semua peraturan & jenis_peraturan untuk kebutuhan modal.
        $peraturan = Peraturan::with('jenisPeraturan')->get();
        if (Auth::user()->role == 3) {
        return view('siswa.peraturan.index', compact('peraturan'));
    }
        $jenisPeraturan = JenisPeraturan::all();

        return view('admin.peraturan.index', compact('peraturan', 'jenisPeraturan'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role == 3) {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
    'nama' => [
        'required',
        'string',
        'max:255',
        'regex:/^[A-Za-z\s]+$/'
    ],
    'poin' => 'required|numeric|min:1|max:20',
    'jenis_peraturan_id' => 'required|exists:jenis_peraturan,id',
], [
    'nama.regex' => 'Nama tidak boleh mengandung angka atau karakter khusus.',
    'poin.max' => 'Poin maksimal hanya 20.',
    'poin.min' => 'Poin minimal adalah 1.',
]);
        $poin = $request->poin;
    $jenis = $request->jenis_peraturan_id;

    if ($poin < 11 && $jenis == 1) {
        return back()
            ->withErrors(['jenis_peraturan_id' => 'Jika jenis pelanggaran kerajinan poin harus 1-10.'])
            ->withInput();
    }

    if ($poin >= 11 && $jenis == 2) {
        return back()
            ->withErrors(['jenis_peraturan_id' => 'Jika jenis pelanggaran sikap perilaku poin 11–20.'])
            ->withInput();
    }

    // Cek duplikat berdasarkan nama + jenis_peraturan_id
    $cekDuplikat = Peraturan::where('nama', $request->nama)
        ->where('jenis_peraturan_id', $request->jenis_peraturan_id)
        ->exists();

    if ($cekDuplikat) {
        return back()
            ->withErrors(['nama' => 'Peraturan dengan nama dan jenis pelanggaran ini sudah ada.'])
            ->withInput();
    }

        Peraturan::create([
            'nama' => $request->nama,
            'poin' => $request->poin,
            'jenis_peraturan_id' => $request->jenis_peraturan_id,
        ]);

        return redirect('/peraturan')->with('success', 'Peraturan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role == 3) {
            abort(403, 'Akses ditolak');
        }
                $request->validate([
    'nama' => [
        'required',
        'string',
        'max:255',
        'regex:/^[A-Za-z\s]+$/'
    ],
    'poin' => 'required|numeric|min:1|max:20',
    'jenis_peraturan_id' => 'required|exists:jenis_peraturan,id',
], [
    'nama.regex' => 'Nama tidak boleh mengandung angka atau karakter khusus.',
    'poin.max' => 'Poin maksimal harus 20.',
    'poin.min' => 'Poin minimal adalah 1.',
]);
        // Logika tambahan
    $poin = $request->poin;
    $jenis = $request->jenis_peraturan_id;

    if ($poin < 11 && $jenis == 1) {
        return back()
            ->withErrors(['jenis_peraturan_id' => 'Jika jenis pelanggaran kerajinan poin harus 1-10.'])
            ->withInput();
    }

    if ($poin >= 11 && $jenis == 2) {
        return back()
            ->withErrors(['jenis_peraturan_id' => 'Jika jenis pelanggaran sikap perilaku poin 11–20.'])
            ->withInput();
    }

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
        // Cegah siswa melakukan DELETE
        if (Auth::user()->role == 3) {
            abort(403, 'Akses ditolak');
        }
        
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->delete();

        return redirect('/peraturan')->with('success', 'Peraturan berhasil dihapus!');
    }
}
