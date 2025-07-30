<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAdminController extends Controller
{
    public function daftar_user()
    {
        return view('admin.page.user.daftar-user', [
            'users' => User::paginate(null)
        ]);
    }

    public function edit($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nisn' => 'unique:users,nisn,' . $id,
            'email' => 'unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->getMessageBag()
            ]);
        }

        $user->nisn = $request->post('nisn');
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->role = $request->post('role');
        $user->info = $request->post('info');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User Berhasil diubah.'
        ]);
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
        $request->validate([
            'nisn' => 'required|unique:users,nisn',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:1,2,3',
        ]);

        User::create([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'email' => $request->email,
            'role' => (int) $request->role, // CAST ke int (opsional tapi aman)
            'registrasi' => 'Sudah',
            'password' => bcrypt('default123'),
        ]);

        return redirect('/master-user')->with('success', 'User berhasil ditambahkan!');
    }
}
