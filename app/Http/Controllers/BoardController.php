<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function unique()
    {
        $siswas = Student::with('user')->take(10)->get()->sortByDesc('poin');
        // $siswa = Student::where('nisn', auth()->user()->nisn)->first();
        // $nama = strtok($siswa['nama'], " ");
        return view('unique', compact('siswas'));
    }
}
