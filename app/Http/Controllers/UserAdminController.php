<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    public function daftar_user()
    {
        return view('admin.page.user.daftar-user', [
            'users' => User::paginate(100)
        ]);
    }

    public function edit($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $rules = [
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($id),
        ],
        'role'  => 'required|in:1,2,3', // 1 = admin, 2 = guru, 3 = siswa
        'info'  => 'required|in:0,1',   // 0 = belum terdaftar, 1 = terdaftar
    ];

    // Jika role siswa (3), NISN wajib dan harus 10 digit
    if ($request->role == 3) {
        $rules['nisn'] = [
            'required',
            'size:10',
            Rule::unique('users', 'nisn')->ignore($id),
        ];
    } else {
        // Jika role bukan siswa, NISN boleh kosong, tapi kalau diisi tetap harus unik
        $rules['nisn'] = [
            'nullable',
            'string',
            'size:10', // hanya divalidasi kalau diisi
            Rule::unique('users', 'nisn')->ignore($id),
        ];
    }

    $validatedData = $request->validate($rules);

    $user->update($validatedData);

    return response()->json(['success' => true]);

}


    public function update_pass(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make($request->post('password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password Berhasil diubah.'
        ]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }

    // ===================== GURU =====================

    public function daftar_guru()
    {
        $wali_kelas = WaliKelas::with('kelas')->latest()->paginate(10);
        $kelas = Kelas::doesnthave('wali')->get();
        $users = User::doesnthave('wali')->where('role', 2)->get();

        return view('admin.page.guru.daftar-guru', compact('wali_kelas', 'kelas', 'users'));
    }

    public function tambah_guru(Request $request)
    {
        $wali = WaliKelas::create([
            'name' => $request->post('name'),
            'user_id' => $request->post('user'),
            'kelas_id' => $request->post('kelas'),
        ]);

        User::where('id', $wali->user_id)->update([
            'info' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dibuat!.'
        ]);
    }

    public function hapus_guru($id)
    {
        WaliKelas::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus!'
        ]);
    }

    // ===================== USER TAMBAH =====================

    // Menampilkan form tambah user
    public function create()
    {
        return view('admin.page.user.create-user');
    }

    // Menyimpan data user baru
    public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:1,2,3',
        'nisn' => 'nullable|size:10|unique:users,nisn',
    ];

    // Jika role == 3 (siswa), maka NISN wajib diisi
    if ($request->role == 3) {
        $rules['nisn'] = 'required|size:10|unique:users,nisn';
    } 

    $validated = $request->validate($rules);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
        'nisn' => $validated['nisn'] ?? null,
        'password' => bcrypt('password'), // ganti dengan logika password kamu
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
}



}
