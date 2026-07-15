<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['kelas', 'tahunAjaran'])
            ->where('status', 'alumni');

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        $alumni = $query->get();

        $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.alumni.index', compact(
            'alumni',
            'tahunAjarans'
        ));
    }
    public function exportPdf(Request $request)
    {
        $query = Student::with(['kelas', 'tahunAjaran'])
            ->where('status', 'alumni');

        if ($request->tahun_ajaran_id) {
            $query->where(
                'tahun_ajaran_id',
                $request->tahun_ajaran_id
            );
        }

        $alumni = $query->get();

        $tahunAjaran = TahunAjaran::find(
            $request->tahun_ajaran_id
        );

        $pdf = Pdf::loadView(
            'admin.alumni.pdf',
            compact('alumni', 'tahunAjaran')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-alumni.pdf');
    }
}
