<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::with('semesters')->orderBy('tahun_ajaran', 'desc')->get();
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();
        $semesterAktifTa = $tahunAktif?->semesters;

        return view('admin.semester.index', compact(
            'tahunAjaran',
            'tahunAktif',
            'semesterAktifTa'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $ta = TahunAjaran::findOrFail($request->tahun_ajaran_id);

        if ($ta->semesters()->where('nama', $request->nama)->exists()) {
            return back()->with('error', 'Semester ' . $request->nama . ' sudah ada.');
        }

        if ($request->nama === 'Genap' && !$ta->semesters()->where('nama', 'Ganjil')->exists()) {
            return back()->with('error', 'Semester Ganjil harus ditambahkan terlebih dahulu sebelum Genap.');
        }

        DB::transaction(function () use ($ta, $request) {
            $ta->semesters()->update(['aktif' => false]);

            $ta->semesters()->create([
                'nama' => $request->nama,
                'aktif' => true,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
        });

        return back()->with('success', 'Semester ' . $request->nama . ' berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $semester = Semester::findOrFail($id);

        if (!$semester->aktif) {
            return back()->with('error', 'Semester nonaktif tidak dapat diubah.');
        }

        $semester->update($request->only(['tanggal_mulai', 'tanggal_selesai']));

        return back()->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $ta = $semester->tahunAjaran;

        if ($semester->aktif) {
            return back()->with('error', 'Tidak dapat menghapus semester yang sedang aktif.');
        }

        if ($ta->semesters()->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus satu-satunya semester.');
        }

        DB::transaction(function () use ($semester) {
            $semester->delete();
        });

        return back()->with('success', 'Semester berhasil dihapus.');
    }

    public function gantiSemester($id)
    {
        $semester = Semester::findOrFail($id);

        if ($semester->aktif) {
            return back()->with('error', 'Semester ini sudah aktif.');
        }

        DB::transaction(function () use ($semester) {
            Semester::where('tahun_ajaran_id', $semester->tahun_ajaran_id)
                ->where('id', '!=', $semester->id)
                ->update(['aktif' => false]);

            $semester->update(['aktif' => true]);
        });

        return back()->with('success', 'Semester ' . $semester->nama . ' diaktifkan.');
    }
}
