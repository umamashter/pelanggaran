<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index()
    {
        $waliKelas = WaliKelas::with(['guru', 'kelas'])->latest()->paginate(10);
        $kelas = Kelas::doesnthave('waliKelas')->get();
        $gurus = Guru::whereNotIn('id', WaliKelas::pluck('guru_id'))->get();

        return view('admin.wali-kelas.index', compact('waliKelas', 'kelas', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id|unique:wali_kelas,kelas_id',
        ]);

        WaliKelas::create($request->only(['guru_id', 'kelas_id']));

        return response()->json([
            'success' => true,
            'message' => 'Wali Kelas berhasil ditambahkan!.'
        ]);
    }

    public function destroy($id)
    {
        $wali = WaliKelas::findOrFail($id);
        $wali->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wali Kelas berhasil dihapus!'
        ]);
    }
}
