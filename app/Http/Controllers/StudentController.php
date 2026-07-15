<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\History;
use App\Models\Student;
use App\Models\Penanganan;
use App\Models\TahunAjaran;
use App\Models\StudentKelas;
use App\Events\PasswordChanged;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with([
            'kelasAktif.kelas',
            'kelasAktif.tahunAjaran'
        ])
            ->latest()
            ->paginate(10);

        return view('admin.siswa.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load([
            'user',
            'kelasAktif.kelas',
            'kelasAktif.tahunAjaran',
            'riwayatKelas.kelas',
            'riwayatKelas.tahunAjaran'
        ]);

        return view('admin.siswa.show', compact('student'));
    }

    public function create()
    {
        $kelas = \App\Models\Kelas::all();
        return view('form-datasiswa', compact('kelas'));
    }

    public function view_ubah()
    {
        return view('siswa.ubah_pass');
    }

    public function updateKelas(Request $request, Student $student)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $semester = $tahunAktif->semesterAktif;

        $kelasAktif = $student->kelasAktif;
        if ($kelasAktif) {
            $kelasAktif->update(['aktif' => false]);
        }

        StudentKelas::create([
            'student_id' => $student->id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran_id' => $tahunAktif->id,
            'semester_id' => $semester?->id,
            'aktif' => true,
        ]);

        return back()->with('success', 'Kelas siswa berhasil diperbarui.');
    }

    public function update_pass(Request $request)
    {
        $message = [
            'max' => ':attribute maksimal :max karakter!',
            'min' => ':attribute minimal :min karakter!',
            'required' => ':attribute harus di isi!',
            'confirmed' => ':attribute tidak cocok!',
        ];

        $request->validate([
            'old_password' => 'required|min:8|max:255',
            'new_password' => 'required|confirmed|min:8|max:255',
        ], $message);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->with('error', 'Password lama tidak cocok!');
        }

        User::where('id', auth()->id())->update([
            'password' => Hash::make($request->new_password)
        ]);

        event(new PasswordChanged(auth()->user()));

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function history()
    {
        $siswa = Student::where('user_id', auth()->id())->firstOrFail();
        $histories = History::where('student_id', $siswa->id)
            ->latest()
            ->paginate(7);
        $tanggal = $histories->unique('tanggal')->pluck('tanggal');
        $nama = strtok($siswa->nama, " ");

        return view('siswa.history', compact('histories', 'nama', 'tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'nisn' => 'required|max:20|unique:students,nisn',
            'ttl' => 'required|max:255',
            'jk' => 'required|max:20',
            'agama' => 'required|max:20',
            'alamat' => 'required|max:255',
            'no_telp' => 'required|numeric|digits_between:8,13|unique:students',
            'n_ayah' => 'required|max:255',
            'n_ibu' => 'required|max:255',
            'alamat_ortu' => 'required|max:255',
            'no_telp_rumah' => 'required|numeric|digits_between:5,13',
            'kelas' => 'required|exists:kelas,id',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();

        if (!$tahunAktif) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif!');
        }

        $semester = $tahunAktif->semesterAktif;

        $ttl = $request->ttl . ', ' . $request->date;

        $student = Student::create([
            'user_id' => auth()->id(),
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'ttl' => $ttl,
            'jk' => $request->jk,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'n_ayah' => $request->n_ayah,
            'n_ibu' => $request->n_ibu,
            'alamat_ortu' => $request->alamat_ortu,
            'no_telp_rumah' => $request->no_telp_rumah,
            'status' => 'Aktif',
        ]);

        StudentKelas::create([
            'student_id' => $student->id,
            'kelas_id' => $request->kelas,
            'tahun_ajaran_id' => $tahunAktif->id,
            'semester_id' => $semester?->id,
            'aktif' => true,
        ]);

        User::where('id', auth()->id())->update([
            'name' => $request->nama,
            'info' => true,
        ]);

        return redirect('/home')->with('toast_info', 'Welcome ' . $request->nama . '!');
    }

    public function edit($id)
    {
        return view('siswa.detail.edit', [
            'siswa' => Student::findOrFail($id)
        ]);
    }

    public function pesan()
    {
        $siswa = Student::where('user_id', auth()->id())->firstOrFail();
        $pesan = Penanganan::where('student_id', $siswa->id)
            ->latest()
            ->paginate();
        $nama = strtok($siswa->nama, " ");

        return view('siswa.pesan', compact('pesan', 'siswa', 'nama'));
    }

    public function checkpesan($id)
    {
        $penanganan = Penanganan::findOrFail($id);
        $pdf = Pdf::loadView('public/surat/' . $penanganan->berkas);
        return $pdf->stream($penanganan->berkas);
    }
}
