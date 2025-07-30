<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Penanganan;
use App\Models\Peraturan;
use App\Models\Student;
use RealRashid\SweetAlert\Facades\Alert;

class PoinController extends Controller
{
    public $message = [
        'max' => ':attribute maksimal :max Karakter!',
        'min' => ':attribute minimal :min Karakter!',
        'image' => ':attribute harus berupa Foto!',
        'unique' => ':attribute sudah digunakan!',
        'required' => ':attribute harus di isi!',
        'numeric' => ':attribute harus berisi angka!',
        'digits' => ':attribute harus berisi 10 digit'
    ];

    public function tambah_view(Student $siswa)
    {
        return view('admin.page.poin.tambah-poin', [
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

        return redirect('/master-siswa')->with('success', 'Poin berhasil ditambahkan');
    }

    public function kurang_view(Student $siswa)
    {
        return view('admin.page.poin.kurang-poin', [
            'siswa' => $siswa,
            'rules' => Peraturan::all()
        ]);
    }

    public function kurang_poin(Request $request, $id)
    {
        $this->validate($request, [
            'poin' => 'required'
        ], $this->message);

        $siswa = Student::findOrFail($id);

        if ($siswa->poin < $request->poin) {
            return redirect()->back()->with('toast_error', 'Poin tidak valid!');
        } else {

            $siswa->update([
                'poin' => $siswa->poin - $request->poin
            ]);

            return redirect('master-siswa')->with('success', 'Poin berhasil dikurangi');
        }
    }
}