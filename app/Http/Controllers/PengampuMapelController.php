<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Jenjang;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\PengampuMapel;
use Illuminate\Http\Request;

class PengampuMapelController extends Controller
{
    public function index(Request $request)
    {
        $query = PengampuMapel::with([
            'guru',
            'mapel.jenjang',
            'kelas',
            'tahunAjaran'
        ]);

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        if ($request->filled('jenjang_id')) {
            $query->whereHas('mapel', function ($q) use ($request) {
                $q->where('jenjang_id', $request->jenjang_id);
            });
        }

        $pengampus = $query->get()->map(function ($item) {
            $item->is_locked = JadwalPelajaran::where(
                'guru_id',
                $item->guru_id
            )
                ->where(
                    'mata_pelajaran_id',
                    $item->mata_pelajaran_id
                )
                ->exists();

            return $item;
        });

        $gurus = Guru::orderBy('nama')->get();
        $kelas = Kelas::orderBy('tingkat')->get();

        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        $jenjangs = Jenjang::orderBy('kode')->get();

        $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran')->get();

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        $existingPengampus = PengampuMapel::where('tahun_ajaran_id', $tahunAktif->id ?? 0)
            ->select('guru_id', 'mata_pelajaran_id', 'kelas_id')
            ->get();

        return view(
            'admin.pengampumapel.index',
            compact(
                'pengampus',
                'gurus',
                'mapels',
                'kelas',
                'jenjangs',
                'tahunAjarans',
                'tahunAktif',
                'existingPengampus'

            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'kelas_id' => 'required',
            'tahun_ajaran_id' => 'required',
        ]);
        $cek = PengampuMapel::where('guru_id', $request->guru_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('kelas_id', $request->kelas_id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->exists();

        if ($cek) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Guru tersebut sudah menjadi pengampu mata pelajaran ini.'
                );
        }
        PengampuMapel::create([
            'guru_id' => $request->guru_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);
        return back()->with(
            'success',
            'Pengampu mapel berhasil ditambahkan'
        );
    }

    public function destroy(PengampuMapel $pengampu_mapel)
    {
        $digunakan = JadwalPelajaran::where(
            'guru_id',
            $pengampu_mapel->guru_id
        )
            ->where(
                'mata_pelajaran_id',
                $pengampu_mapel->mata_pelajaran_id
            )
            ->exists();

        if ($digunakan) {

            return back()->with(
                'error',
                'Pengampu sudah digunakan pada jadwal pelajaran dan tidak dapat dihapus.'
            );
        }

        $pengampu_mapel->delete();

        return back()->with(
            'success',
            'Data berhasil dihapus'
        );
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'kelas_id' => 'required',
            'tahun_ajaran_id' => 'required',
        ]);

        $pengampu = PengampuMapel::findOrFail($id);
        $cek = PengampuMapel::where(
            'guru_id',
            $request->guru_id
        )
            ->where(
                'mata_pelajaran_id',
                $request->mata_pelajaran_id
            )
            ->where(
                'id',
                '!=',
                $id
            )
            ->exists();

        if ($cek) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Guru tersebut sudah menjadi pengampu mata pelajaran ini.'
                );
        }
        $digunakan = JadwalPelajaran::where(
            'guru_id',
            $pengampu->guru_id
        )
            ->where(
                'mata_pelajaran_id',
                $pengampu->mata_pelajaran_id
            )
            ->exists();

        if ($digunakan) {

            return back()->with(
                'error',
                'Pengampu sudah digunakan pada jadwal pelajaran dan tidak dapat diubah.'
            );
        }

        $pengampu->update([
            'guru_id' => $request->guru_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()
            ->route('pengampu-mapel.index')
            ->with(
                'success',
                'Data pengampu berhasil diperbarui.'
            );
    }
}
