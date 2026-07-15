<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HaflatulImtihan;
use App\Models\Lomba;
use App\Models\PesertaLomba;
use App\Models\JuriLomba;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class HaflatulImtihanController extends Controller
{
    public function index()
    {
        $haflatuls = HaflatulImtihan::with('tahunAjaran')
            ->latest()
            ->paginate(10);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        $sudahAda = $tahunAktif
            ? HaflatulImtihan::where('tahun_ajaran_id', $tahunAktif->id)->exists()
            : false;

        $totalLombas = Lomba::count();
        $totalPesertas = PesertaLomba::count();
        $totalJuries = JuriLomba::count();

        return view(
            'admin.haflah.index',
            compact('haflatuls', 'sudahAda', 'totalLombas', 'totalPesertas', 'totalJuries')
        );
    }

    public function create()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        $sudahAda = $tahunAktif
            ? HaflatulImtihan::where('tahun_ajaran_id', $tahunAktif->id)->exists()
            : false;

        return view(
            'admin.haflah.create',
            compact('tahunAktif', 'sudahAda')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_acara' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if (HaflatulImtihan::where('tahun_ajaran_id', $request->tahun_ajaran_id)->exists()) {
            return redirect()
                ->route('haflatul-imtihan.create')
                ->withInput()
                ->with('error', 'Haflatul Imtihan untuk tahun ajaran ini sudah ada.');
        }

        HaflatulImtihan::create([
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'nama_acara' => $request->nama_acara,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'Persiapan',
        ]);

        return redirect()
            ->route('haflatul-imtihan.index')
            ->with('success', 'Data Haflatul Imtihan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $haflatul = HaflatulImtihan::with([
            'tahunAjaran',
        ])->findOrFail($id);

        return view(
            'admin.haflah.show',
            compact('haflatul')
        );
    }

    public function aktifkan($id)
    {
        $haflatul = HaflatulImtihan::findOrFail($id);

        if ($haflatul->status === 'Selesai') {
            return redirect()
                ->back()
                ->with('error', 'Haflah yang sudah selesai tidak dapat diaktifkan kembali.');
        }

        if ($haflatul->tanggal_mulai && $haflatul->tanggal_mulai->startOfDay()->isAfter(now()->startOfDay())) {
            return redirect()
                ->back()
                ->with('error', 'Haflah belum dapat diaktifkan karena tanggal mulai belum tiba (' . $haflatul->tanggal_mulai->translatedFormat('d M Y') . ').');
        }

        DB::transaction(function () use ($id) {
            HaflatulImtihan::where('status', 'Aktif')->update(['status' => 'Selesai']);
            HaflatulImtihan::where('id', $id)->update(['status' => 'Aktif']);
        });

        session(['haflah_id' => $id]);

        return redirect()->back()->with('success', 'Haflah aktif berhasil diganti.');
    }

    public function edit($id)
    {
        $haflatul = HaflatulImtihan::findOrFail($id);

        $tahunAjarans = TahunAjaran::all();

        return view(
            'admin.haflah.edit',
            compact(
                'haflatul',
                'tahunAjarans'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $haflatul = HaflatulImtihan::findOrFail($id);

        if ($haflatul->status === 'Selesai') {
            return redirect()
                ->back()
                ->with('error', 'Haflah yang sudah selesai tidak dapat diubah.');
        }

        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_acara' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($request->tahun_ajaran_id != $haflatul->tahun_ajaran_id
            && HaflatulImtihan::where('tahun_ajaran_id', $request->tahun_ajaran_id)->exists()) {
            return redirect()
                ->route('haflatul-imtihan.edit', $id)
                ->withInput()
                ->with('error', 'Tahun ajaran tersebut sudah memiliki Haflatul Imtihan.');
        }

        $haflatul->update([
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'nama_acara' => $request->nama_acara,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()
            ->route('haflatul-imtihan.index')
            ->with('success', 'Data Haflatul Imtihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $haflatul = HaflatulImtihan::findOrFail($id);

        if ($haflatul->status === 'Selesai') {
            return redirect()
                ->back()
                ->with('error', 'Haflah yang sudah selesai tidak dapat dihapus.');
        }

        $relasi = [
            'peserta lomba' => $haflatul->pesertaLombas(),
            'lomba' => $haflatul->lombas(),
            'sesi lomba' => $haflatul->sesiLombas(),
            'kategori lomba' => $haflatul->kategoriLombas(),
            'kelompok lomba' => $haflatul->kelompokLombas(),
            'juri lomba' => $haflatul->juriLombas(),
            'aspek penilaian' => $haflatul->aspekPenilaians(),
        ];

        foreach ($relasi as $nama => $rel) {
            if ($rel->exists()) {
                return redirect()
                    ->back()
                    ->with('error', "Haflah tidak dapat dihapus karena masih memiliki data {$nama}.");
            }
        }

        $haflatul->delete();

        return redirect()
            ->route('haflatul-imtihan.index')
            ->with('success', 'Data Haflatul Imtihan berhasil dihapus.');
    }
}
