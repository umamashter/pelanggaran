<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;

class MasterGuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::orderBy('nama')->get();

        $existingUserIds = Guru::whereNotNull('user_id')->pluck('user_id');
        $users = User::where('role', 2)
            ->whereNotIn('id', $existingUserIds)
            ->orderBy('name')
            ->get();

        return view('admin.guru.index', compact('gurus', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nip' => 'nullable',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $user = User::findOrFail($request->user_id);

        $lastGuru = Guru::max('id') ?? 0;
        $kodeGuru = 'GR' . str_pad($lastGuru + 1, 3, '0', STR_PAD_LEFT);

        Guru::create([
            'user_id' => $user->id,
            'kode_guru' => $kodeGuru,
            'nama' => $user->name,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Guru berhasil ditambahkan');
    }

    public function update(Request $request, Guru $master_guru)
    {
        $request->validate([
            'nip' => 'nullable',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $master_guru->update($request->only('nip', 'no_hp', 'alamat'));

        return back()->with('success', 'Guru berhasil diperbarui');
    }

    public function destroy(Guru $master_guru)
    {
        $master_guru->delete();

        return back()->with('success', 'Guru berhasil dihapus');
    }
}
