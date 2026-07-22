<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LokasiMadrasah;

class LokasiMadrasahController extends Controller
{
    public function index()
    {
        $lokasi = LokasiMadrasah::first();
        return view('admin.lokasi-madrasah.index', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:1000',
        ]);

        DB::beginTransaction();
        try {
            LokasiMadrasah::updateOrCreate(
                ['id' => 1],
                [
                    'nama' => $request->nama,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'radius' => $request->radius,
                    'aktif' => true,
                ]
            );

            DB::commit();
            return redirect()->route('lokasi-madrasah.index')
                ->with('success', 'Lokasi madrasah berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan lokasi: ' . $e->getMessage());
        }
    }

    public function toggleAktif(Request $request)
    {
        $lokasi = LokasiMadrasah::first();

        if (!$lokasi) {
            return back()->with('error', 'Belum ada lokasi yang dikonfigurasi.');
        }

        $lokasi->update(['aktif' => !$lokasi->aktif]);

        $status = $lokasi->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Lokasi berhasil ' . $status . '.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:1000',
        ]);

        $lokasi = LokasiMadrasah::first();

        if (!$lokasi) {
            return back()->with('error', 'Belum ada lokasi yang dikonfigurasi.');
        }

        DB::beginTransaction();
        try {
            $lokasi->update([
                'nama' => $request->nama,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius,
            ]);

            DB::commit();
            return redirect()->route('lokasi-madrasah.index')
                ->with('success', 'Lokasi madrasah berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui lokasi: ' . $e->getMessage());
        }
    }
}
