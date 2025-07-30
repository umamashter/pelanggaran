<?php

namespace App\Http\Controllers;

use App\Models\GuruBk;
use App\Models\History;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Peraturan;
use App\Models\Penanganan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GuruBkController extends Controller
{

    public function daftar_siswa()
    {
        $guru_bk = GuruBk::firstWhere('user_id', auth()->user()->id);
        $siswas = Student::where('kelas_id', $guru_bk->kelas_id)->paginate(null)->withQueryString();
        return view('bk.daftar-siswa', compact('siswas', 'guru_bk'));
    }

    public function tambah_view(Student $siswa)
    {
        return view('bk.poin.tambah-poin', [
            'siswa' => $siswa,
            'rules' => Peraturan::oldest()->filter(request('search'))->get()
        ]);
    }

    public function tambah_poin(Request $request, $id)
    {
        $siswa = Student::findOrFail($id);
        $penanganan = Penanganan::where('student_id', '=', $siswa->id)->get();

        if ($request->total == 0 || $request->total == '') {
            return redirect()->back()->with('error', 'Poin tidak valid!');
        }
        // dd($penanganan->tindak_lanjut);

        $tindak_lanjut = [];
        foreach ($penanganan as $item) {
            $tindak_lanjut[] = $item->pesan->tindak_lanjut;
        }

        $histories = $request->input('rule');

        foreach ($histories as $history) {

            History::create([
                'student_id' => $siswa->id,
                'peraturan_id' => $history,
                'tanggal' => date('Y-m-d', time())
            ]);
        }

        $siswa->update([
            'poin' => $siswa->poin + $request->total
        ]);


        if ($siswa->poin >= 10 && $siswa->poin <= 35 && !(in_array('Peringatan ke I', $tindak_lanjut))) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 1
            ]);
        }
        if ($siswa->poin >= 36 && $siswa->poin <= 55 && !(in_array('Peringatan ke II', $tindak_lanjut))) {

            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 2
            ]);
        }
        if ($siswa->poin >= 56 && $siswa->poin <= 75 && !(in_array('Panggilan Orang tua I', $tindak_lanjut))) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 3
            ]);
        }
        if ($siswa->poin >= 76 && $siswa->poin <= 95 && !(in_array('Panggilan Orang tua II', $tindak_lanjut))) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 4
            ]);
        }
        if ($siswa->poin >= 96 && $siswa->poin <= 149 && !(in_array('Panggilan Orang tua III', $tindak_lanjut))) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 5
            ]);
        }
        if ($siswa->poin >= 150 && $siswa->poin <= 249 && !(in_array('Skorsing', $tindak_lanjut))) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 6
            ]);
        }
        if ($siswa->poin >= 250 && !(in_array('Dikembalikan Orang tua', $tindak_lanjut))) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 7
            ]);
        }

        return redirect('/bk/daftar-siswa')->with('success', 'Poin berhasil ditambahkan');
    }

    public function kurang_view(Student $siswa)
    {
        return view('bk.poin.kurang-poin', [
            'siswa' => $siswa,
            'rules' => Peraturan::all()
        ]);
    }

    public function kurang_poin(Request $request, $id)
    {

        $siswa = Student::findOrFail($id);

        if ($siswa->poin < $request->poin) {
            return redirect()->back()->with('toast_error', 'Poin tidak valid!');
        } else {

            $siswa->update([
                'poin' => $siswa->poin - $request->poin
            ]);

            return redirect('/bk/daftar-siswa')->with('success', 'Poin berhasil dikurangi');
        }
    }

    public function index()
    {
        $guru_bk = GuruBk::firstWhere('user_id', auth()->user()->id);
        $siswas = Student::firstWhere('kelas_id', $guru_bk->kelas_id);

        $siswa = Student::whereHas('penanganan', function ($q) use ($guru_bk) {
            $q->where('kelas_id', $guru_bk->kelas_id);
        })->get();

        $id_student = [];
        foreach ($siswa as $item) {
            $id_student[] = $item->id;
        }

        $penanganan = Penanganan::with(['siswa', 'pesan'])->whereIn('student_id', $id_student)->latest()->paginate(null);
        return view('bk.penanganan', compact('penanganan', 'guru_bk'));
    }

    public function konfirmasi(Request $request, $id)
    {

        $penanganan = Penanganan::findOrFail($id);
        if ($penanganan->pesan->tingkatan != 'Ringan') {
            // Final Konfirmasi
            if ($penanganan->status == 1) {
                $penanganan->update([
                    'status' => 2
                ]);
                return redirect()->back()->with('success', 'Terkonfirmasi');
            }

            // Atur Hari Indonesia
            $tgl = $request->input('date');
            $tampil_hari = true;
            $nama_hari  = array(
                'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
            );
            $nama_bulan = array(
                1 =>
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            );

            $tahun   = substr($tgl, 0, 4);
            $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
            $tanggal = substr($tgl, 8, 2);
            $text    = '';

            if ($tampil_hari) {
                $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
                $hari        = $nama_hari[$urutan_hari];
                $text       .= "$tanggal $bulan $tahun";
            } else {
                $text       .= "$tanggal $bulan $tahun";
            }
            $jam = $request->input('time');

            // $pdf = PDF::loadView('surat-panggilan', compact('tanggal', 'jam'));
            // return $pdf->output();
            // return $dompdf->loadHtml($html);
            $pdf = PDF::loadView('surat-panggilan', compact('jam', 'penanganan', 'hari', 'text'))->setOptions([
                'chroot' => '/public',
                'defaultFont' => 'helvetica'
            ]);
            // return $pdf->stream('surat-panggilan');

            $tindak_lanjut = str_replace(' ', '_', $penanganan->pesan->tindak_lanjut);
            $nama_siswa = strtok($penanganan->siswa->nama, " ");
            $fileName = $tindak_lanjut  . '_' . $nama_siswa . '_' . time() . '.' . 'pdf';
            // $pdf->save($path . '/' . $fileName);
            // return $pdf->download($fileName);

            // return $pdf->stream('surat-panggilan.pdf');
            // $pdf->render();
            $content = $pdf->download($fileName)->getOriginalContent();
            Storage::put('public/surat/' . $fileName, $content);

            $penanganan->update([
                'status' => 1,
                'berkas' => $fileName,
            ]);
            // return $pdf->stream($fileName);
            return redirect()->back()->with('success', 'Terkirim');
        }

        $penanganan->update([
            'status' => 1
        ]);
        return redirect()->back()->with('success', 'Terkonfirmasi');
    }
}