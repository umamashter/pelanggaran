<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\WaliKelas;
use App\Models\Penanganan;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenangananController extends Controller
{
    public function index()
    {
        $penanganan = Penanganan::with(['siswa', 'pesan'])->latest()->paginate(null);
        return view('admin.page.penanganan', compact('penanganan'));
    }

    public function konfirmasi(Request $request, $id)
{
    $penanganan = Penanganan::findOrFail($id);
    $tingkatan = $penanganan->pesan->tingkatan;

    // Jika RINGAN atau SEDANG → cukup update status
    if (in_array($tingkatan, ['Ringan', 'Sedang'])) {
        $penanganan->update([
            'status' => 1
        ]);
        return redirect()->back()->with('success', 'Penanganan ' . strtolower($tingkatan) . ' berhasil dikonfirmasi.');
    }

    // Jika BERAT → generate surat panggilan (PDF)
    $tgl = $request->input('date');
    $jam = $request->input('time');

    $nama_hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
    $nama_bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                   'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $tahun   = substr($tgl, 0, 4);
    $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $urutan_hari = date('w', strtotime($tgl));
    $hari = $nama_hari[$urutan_hari];

    $text = "$hari, $tanggal $bulan $tahun";

    $pdf = PDF::loadView('surat-panggilan', compact('jam', 'penanganan', 'hari', 'text'))->setOptions([
        'defaultFont' => 'helvetica'
    ]);

    $tindak_lanjut = str_replace(' ', '_', $penanganan->pesan->tindak_lanjut);
    $nama_siswa = strtok($penanganan->siswa->nama, " ");
    $fileName = $tindak_lanjut . '_' . $nama_siswa . '_' . time() . '.pdf';

    $content = $pdf->download($fileName)->getOriginalContent();
    Storage::put('public/surat/' . $fileName, $content);

    $penanganan->update([
        'status' => 1,
        'berkas' => $fileName,
    ]);

    return redirect()->back()->with('success', 'Penanganan berat dikonfirmasi dan surat berhasil dikirim.');
}

    public function guru_index()
    {
        $wali_kelas = WaliKelas::where('user_id', auth()->user()->id)->first();
        $siswas = Student::where('kelas_id', $wali_kelas->kelas_id)->get();

        $siswa = Student::whereHas('penanganan', function ($q) use ($wali_kelas) {
            $q->where('kelas_id', $wali_kelas->kelas_id);
        })->get();
        $id_student = [];
        foreach ($siswa as $item) {
            $id_student[] = $item->id;
        }
        $penanganan = Penanganan::with(['siswa', 'pesan'])
    ->whereIn('student_id', $id_student)
    ->latest()
    ->paginate(null);

        return view('guru.page.penanganan', compact('penanganan', 'siswas', 'wali_kelas'));
    }

    public function guru_konfirmasi(Request $request, $id)
{
    $penanganan = Penanganan::findOrFail($id);
    $tingkatan = $penanganan->pesan->tingkatan;

// RINGAN / SEDANG → langsung status 1
    if (in_array($tingkatan, ['Ringan', 'Sedang'])) {
        $penanganan->update(['status' => 1]);
        return back()->with('success', 'Penanganan ' . strtolower($tingkatan) . ' berhasil dikonfirmasi.');
    }
   // BERAT
    if ($tingkatan === 'Berat') {
        // Kalau status 0 → buat surat + status = 1
        if ($penanganan->status == 0) {
            $request->validate([
                'date' => 'required|date',
                'time' => 'required'
            ]);


    // Jika BERAT → generate surat
    $tgl = $request->input('date');
    $jam = $request->input('time');

    $nama_hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
    $nama_bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                   'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $tahun   = substr($tgl, 0, 4);
    $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $urutan_hari = date('w', strtotime($tgl));
    $hari = $nama_hari[$urutan_hari];

    $text = "$hari, $tanggal $bulan $tahun";

    $pdf = PDF::loadView('surat-panggilan', compact('jam', 'penanganan', 'hari', 'text'))->setOptions([
        'defaultFont' => 'helvetica'
    ]);

    $tindak_lanjut = str_replace(' ', '_', $penanganan->pesan->tindak_lanjut);
    $nama_siswa = strtok($penanganan->siswa->nama, " ");
    $fileName = $tindak_lanjut . '_' . $nama_siswa . '_' . time() . '.pdf';

    $content = $pdf->download($fileName)->getOriginalContent();
    Storage::put('public/surat/' . $fileName, $content);

    $penanganan->update([
        'status' => 1,
        'berkas' => $fileName,
    ]);

    return redirect()->back()->with('success', 'Penanganan berat dikonfirmasi dan surat berhasil dikirim.');
}
// Kalau status 1 → ubah jadi status 2 (selesai)
        if ($penanganan->status == 1) {
            $penanganan->update(['status' => 2]);
            return back()->with('success', 'Penanganan berat selesai dan terkonfirmasi.');
        }
    }

}
}