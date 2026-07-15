<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentKelas;
use App\Models\History;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        $taTerakhir = TahunAjaran::orderBy('tahun_ajaran', 'desc')->first();
        $tahunMulaiBaru = date('Y');
        $dapatAutoBuat = $taTerakhir
            ? ((int)explode('/', $taTerakhir->tahun_ajaran)[0] + 1)
            : date('Y');

        return view(
            'admin.tahunajaran.index',
            compact('tahunAjaran', 'tahunMulaiBaru', 'dapatAutoBuat')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
        ]);

        $tahunAjaran = $request->tahun_mulai . '/' . ($request->tahun_mulai + 1);

        if (TahunAjaran::where('tahun_ajaran', $tahunAjaran)->exists()) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Tahun ajaran ' . $tahunAjaran . ' sudah ada.');
        }

        DB::transaction(function () use ($tahunAjaran) {
            $taAktif = TahunAjaran::where('status', 'Aktif')->first();
            if ($taAktif) {
                $taAktif->semesters()->update(['aktif' => false]);
            }

            TahunAjaran::create([
                'tahun_ajaran' => $tahunAjaran,
                'status' => 'Tidak Aktif',
            ]);
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        if ($tahun->status == 'Aktif') {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Tahun ajaran aktif tidak boleh dihapus');
        }

        DB::transaction(function () use ($tahun) {
            StudentKelas::where('tahun_ajaran_id', $tahun->id)->delete();
            $tahun->semesters()->delete();
            $tahun->delete();
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_mulai' => 'required|numeric|min:2000|max:2100',
        ]);

        $tahunAjaran = $request->tahun_mulai . '/' . ($request->tahun_mulai + 1);

        if (TahunAjaran::where('tahun_ajaran', $tahunAjaran)
            ->where('id', '!=', $id)->exists()) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Tahun ajaran sudah ada.');
        }

        TahunAjaran::findOrFail($id)->update([
            'tahun_ajaran' => $tahunAjaran,
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function aktifkan($id)
    {
        $target = TahunAjaran::findOrFail($id);

        if ($target->status === 'Arsip') {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Tahun ajaran yang sudah diarsipkan tidak dapat diaktifkan kembali.');
        }

        DB::transaction(function () use ($id) {
            TahunAjaran::where('status', 'Aktif')->update(['status' => 'Arsip']);
            TahunAjaran::findOrFail($id)->update(['status' => 'Aktif']);
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diaktifkan');
    }

    public function arsip()
    {
        $arsips = TahunAjaran::whereIn('status', ['Tidak Aktif', 'Arsip'])
            ->orderBy('tahun_ajaran', 'desc')
            ->get();

        return view('admin.tahunajaran.arsip', compact('arsips'));
    }

    public function detailArsip($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $siswaIds = StudentKelas::where('tahun_ajaran_id', $id)
            ->distinct()
            ->pluck('student_id');

        $jumlahSiswa = $siswaIds->count();
        $jumlahHistori = History::where('tahun_ajaran_id', $id)->count();
        $jumlahAlumni = Student::whereIn('id', $siswaIds)
            ->where('status', 'alumni')
            ->count();

        $siswas = Student::with('kelas')
            ->whereIn('id', $siswaIds)
            ->orderBy('nama')
            ->get();

        return view('admin.tahunajaran.detail-arsip', compact(
            'tahunAjaran', 'jumlahSiswa', 'jumlahHistori', 'jumlahAlumni', 'siswas'
        ));
    }
}
