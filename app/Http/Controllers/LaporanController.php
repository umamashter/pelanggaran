<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function rekapPeriode(Request $request)
    {
        $tahunAjaran = $request->input('tahun_ajaran') ?? '2024/2025';
        $bulan = $request->input('bulan');
        $nisn = $request->input('nisn'); 
        $kelasId = $request->input('kelas_id');

        $query = History::query()->with(['siswa', 'rule', 'kelasSnapshot']);

        // Pecah tahun ajaran jadi rentang
        [$startYear, $endYear] = explode('/', $tahunAjaran);

        // Filter rentang tahun ajaran
        $query->whereBetween('tanggal', ["$startYear-07-01", "$endYear-06-30"]);

        // Kalau ada filter bulan, override
        if ($bulan) {
            $query->where('tanggal', 'like', "$bulan%");
        }
        // Filter nama siswa
        if ($kelasId) {
            $query->where('kelas_saat_pelanggaran', $kelasId);
        }
        // Filter NISN
        if ($nisn) {
            $query->whereHas('siswa', function ($q) use ($nisn) {
                $q->where('nisn', 'like', "%$nisn%");
            });
        }

        $histories = $query->orderBy('tanggal', 'asc')->get();

        return view('admin.page.laporan.rekap-periode', [
            'histories' => $histories,
            'tahunAjaran' => $tahunAjaran,
            'bulan' => $bulan,
            'nama' => $kelasId,
            'nisn' => $nisn
        ]);
    }

    public function exportPdf(Request $request)
    {
        $tahunAjaran = $request->input('tahun_ajaran') ?? '2024/2025';
        $bulan = $request->input('bulan');
        $kelasId = $request->input('kelas_id');
        $nisn = $request->input('nisn');

        $query = History::query()->with(['siswa', 'rule', 'kelasSnapshot']);

        [$startYear, $endYear] = explode('/', $tahunAjaran);

        $query->whereBetween('tanggal', ["$startYear-07-01", "$endYear-06-30"]);

        if ($bulan) {
            $query->where('tanggal', 'like', "$bulan%");
        }
        if ($kelasId) {
            $query->where('kelas_saat_pelanggaran', $kelasId);
        }
        if ($nisn) {
            $query->whereHas('siswa', function ($q) use ($nisn) {
                $q->where('nisn', 'like', "%$nisn%");
            });
        }

        $histories = $query->orderBy('tanggal', 'asc')->get();

        $pdf = Pdf::loadView('admin.page.laporan.rekap-periode-pdf', [
            'histories' => $histories,
            'tahunAjaran' => $tahunAjaran,
            'bulan' => $bulan,
            'kelasId' => $kelasId,
            'nisn' => $nisn
        ])->setPaper('A4', 'landscape');

        return $pdf->download('Rekap_Pelanggaran.pdf');
    }
}
