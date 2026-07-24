<?php

namespace App\Http\Controllers;

use App\Models\Jenjang;
use App\Models\MataPelajaran;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\MataPelajaranImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MataPelajaranExport;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $mapel = MataPelajaran::with('kurikulum', 'jenjang')
            ->when($request->search, fn($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('nama_mapel', 'like', "%{$v}%")
                  ->orWhere('kode_mapel', 'like', "%{$v}%");
            }))
            ->when($request->jenjang_id, fn($q, $v) => $q->where('jenjang_id', $v))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->kurikulum_id, fn($q, $v) => $q->where('kurikulum_id', $v))
            ->orderBy('nama_mapel')
            ->paginate($perPage)
            ->withQueryString();

        $kurikulums = Kurikulum::all();
        $jenjangs = Jenjang::all();

        return view(
            'admin.matapelajaran.index',
            compact(
                'mapel',
                'kurikulums',
                'jenjangs',
                'perPage'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => [
                'required',
                'trim',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = MataPelajaran::whereRaw('LOWER(nama_mapel) = LOWER(?)', [$value])
                        ->where('jenjang_id', $request->jenjang_id)
                        ->exists();
                    if ($exists) {
                        $jenjang = Jenjang::find($request->jenjang_id);
                        $namaJenjang = $jenjang ? $jenjang->nama_jenjang : '-';
                        $fail("Mata pelajaran {$value} sudah terdaftar pada jenjang {$namaJenjang}.");
                    }
                },
            ],
            'kelompok'   => 'required|in:PAI,Umum,Muatan Lokal,Ekstrakurikuler',
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'jenjang_id' => 'required|exists:jenjangs,id',
            'status'     => 'required|in:Aktif,Nonaktif',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
            'jenjang_id.exists' => 'Jenjang tidak ditemukan.',
            'kelompok.required' => 'Kelompok wajib dipilih.',
            'kurikulum_id.required' => 'Kurikulum wajib dipilih.',
            'kurikulum_id.exists' => 'Kurikulum tidak ditemukan.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus Aktif atau Nonaktif.',
        ]);

        $lastMapel = MataPelajaran::latest('id')->first();

        if ($lastMapel) {
            $lastNumber = (int) substr($lastMapel->kode_mapel, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kodeMapel = 'MAP' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        MataPelajaran::create([
            'kode_mapel' => $kodeMapel,
            'nama_mapel' => $request->nama_mapel,
            'kurikulum_id' => $request->kurikulum_id,
            'jenjang_id' => $request->jenjang_id,
            'kelompok'   => $request->kelompok,
            'status'     => $request->status,
        ]);

        return redirect()
            ->route('mata-pelajaran.index')
            ->with('success', 'Data mata pelajaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_mapel' => [
                'required',
                'trim',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $exists = MataPelajaran::whereRaw('LOWER(nama_mapel) = LOWER(?)', [$value])
                        ->where('jenjang_id', $request->jenjang_id)
                        ->where('id', '!=', $id)
                        ->exists();
                    if ($exists) {
                        $jenjang = Jenjang::find($request->jenjang_id);
                        $namaJenjang = $jenjang ? $jenjang->nama_jenjang : '-';
                        $fail("Mata pelajaran {$value} sudah terdaftar pada jenjang {$namaJenjang}.");
                    }
                },
            ],
            'kurikulum_id' => 'nullable|exists:kurikulums,id',
            'jenjang_id' => 'required|exists:jenjangs,id',
            'kelompok' => 'required|in:PAI,Umum,Muatan Lokal,Ekstrakurikuler',
            'status' => 'required|in:Aktif,Nonaktif',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
            'jenjang_id.exists' => 'Jenjang tidak ditemukan.',
            'kurikulum_id.exists' => 'Kurikulum tidak ditemukan.',
            'kelompok.required' => 'Kelompok wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus Aktif atau Nonaktif.',
        ]);

        $mapel->update([
            'nama_mapel'   => $request->nama_mapel,
            'kurikulum_id' => $request->kurikulum_id,
            'jenjang_id'   => $request->jenjang_id,
            'kelompok'     => $request->kelompok,
            'status'       => $request->status,
        ]);

        return redirect()
            ->route('mata-pelajaran.index')
            ->with(
                'success',
                'Data mata pelajaran berhasil diubah'
            );
    }

    public function destroy($id)
    {
        MataPelajaran::findOrFail($id)->delete();

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Data mata pelajaran berhasil dihapus');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new MataPelajaranImport,
            $request->file('file')
        );

        return redirect()
            ->route('mata-pelajaran.index')
            ->with(
                'success',
                'Import mata pelajaran berhasil'
            );
    }
    public function export()
    {
        return Excel::download(
            new MataPelajaranExport,
            'mata-pelajaran.xlsx'
        );
    }
}
