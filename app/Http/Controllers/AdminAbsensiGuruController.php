<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use App\Models\User;

class AdminAbsensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $query = AbsensiGuru::with(['user'])
            ->orderBy('tanggal', 'desc')
            ->latest('id');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $absensis = $query->get();

        $guruList = User::where('role', 2)->orderBy('name')->get();

        return view('admin.absensi-guru.index', compact('absensis', 'guruList'));
    }

    public function detail($id)
    {
        $absensi = AbsensiGuru::with(['user'])
            ->findOrFail($id);

        return view('admin.absensi-guru.detail', compact('absensi'));
    }
}
