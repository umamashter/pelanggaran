<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\History;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function daftar_siswa()
    {
        if (request('kelas')) {
            Kelas::firstWhere('nama_kelas', request('kelas'));
        };

        return view('admin.page.daftar-siswa', [
            'siswas' => Student::latest('poin')->filter(request(['search', 'kelas']))->paginate(null)->withQueryString(),
            'kelas' => Kelas::all()
        ]);
    }

    public function histori_index()
    {
        if (request('tanggal')) {
            $histories = History::with('siswa')->where('tanggal', request('tanggal'))->filter(request(['tanggal']))->paginate(7)->withQueryString();
            $tanggal = date('d-m-Y', strtotime(request('tanggal')));
        } else {
            $histories = History::latest()->with('siswa')->filter(request(['tanggal']))->paginate(7)->withQueryString();
            $tanggal = $histories->unique('tanggal')->pluck('tanggal');
        };

        return view('admin.page.histori.master-history', compact('histories', 'tanggal'));
    }

    public function histori_admin($id)
    {
        $siswa = Student::findOrFail($id);
        if (request('tanggal')) {
            $histories = History::with('siswa')->where('tanggal', request('tanggal'))->where('student_id', $id)->filter(request(['tanggal']))->paginate(7)->withQueryString();
            $tanggal = date('d-m-Y', strtotime(request('tanggal')));
        } else {
            $histories = History::latest()->with('siswa')->where('student_id', $id)->filter(request(['tanggal']))->paginate(7)->withQueryString();
            $tanggal = $histories->unique('tanggal')->pluck('tanggal');
        };
        return view('admin.page.histori.history', compact('histories', 'tanggal', 'siswa'));
    }
}