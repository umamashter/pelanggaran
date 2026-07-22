<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AbsensiGuru;
use App\Models\LokasiMadrasah;

class GuruAbsensiGuruController extends Controller
{
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function show()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $lokasi = LokasiMadrasah::aktif()->first();

        $absensiHariIni = AbsensiGuru::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        return view('guru.absensi-guru.show', compact('lokasi', 'absensiHariIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'akurasi_gps' => 'nullable|numeric|min:0',
            'foto' => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $user = Auth::user();
        $today = now()->toDateString();
        $jamSekarang = now()->format('H:i:s');

        $existing = AbsensiGuru::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini pada jam ' . $existing->jam_masuk . '.');
        }

        $lokasi = LokasiMadrasah::aktif()->first();
        if (!$lokasi) {
            return back()->with('error', 'Belum ada lokasi madrasah yang dikonfigurasi.');
        }

        $jarak = $this->haversine(
            $request->latitude,
            $request->longitude,
            $lokasi->latitude,
            $lokasi->longitude
        );

        if ($jarak > $lokasi->radius) {
            return back()->with('error', 'Anda berada di luar area absensi madrasah. Jarak Anda sekitar ' . round($jarak) . ' meter dari lokasi madrasah. Maksimal jarak yang diizinkan adalah ' . $lokasi->radius . ' meter.');
        }

        $fotoPath = $request->file('foto')->store('absensi-guru/foto', 'public');
        $fotoFilename = basename($fotoPath);

        DB::beginTransaction();
        try {
            AbsensiGuru::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'jam_masuk' => $jamSekarang,
                'foto_masuk' => $fotoFilename,
                'latitude_masuk' => $request->latitude,
                'longitude_masuk' => $request->longitude,
                'jarak_masuk' => $jarak,
                'akurasi_gps' => $request->akurasi_gps,
            ]);

            DB::commit();
            return redirect()->route('guru.absensi-guru.show')
                ->with('success', 'Absensi berhasil disimpan pada jam ' . $jamSekarang . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($fotoPath);
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    public function riwayat()
    {
        $user = Auth::user();

        $absensis = AbsensiGuru::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->latest('id')
            ->get();

        return view('guru.absensi-guru.riwayat', compact('absensis'));
    }
}
