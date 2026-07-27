<?php

namespace App\Http\Controllers;

use App\Models\KepalaMadrasah;
use App\Models\Jenjang;
use App\Models\User;
use Illuminate\Http\Request;

class MasterKepalaMadrasahController extends Controller
{
    public function index()
    {
        $kepalaMadrasah = KepalaMadrasah::with(['user', 'jenjang'])->get();
        $jenjangs = Jenjang::all();
        $users = User::where('role', 5)->orderBy('name')->get();

        return view('admin.kepala-madrasah.index', compact('kepalaMadrasah', 'jenjangs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenjang_id' => 'required|exists:jenjangs,id|unique:kepala_madrasah,jenjang_id',
        ]);

        KepalaMadrasah::create([
            'user_id' => $request->user_id,
            'jenjang_id' => $request->jenjang_id,
        ]);

        return back()->with('success', 'Kepala Madrasah berhasil ditugaskan.');
    }

    public function destroy(KepalaMadrasah $kepalaMadrasah)
    {
        $kepalaMadrasah->delete();

        return back()->with('success', 'Penugasan Kepala Madrasah berhasil dihapus.');
    }
}
