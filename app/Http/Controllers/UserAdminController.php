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

    // 🚨 Logika khusus siswa
    if ($request->role == 3 && $user->info == 0 && $request->info == 1) {
        return response()->json([
            'success' => false,
            'message' => 'Siswa harus mendaftar sendiri.'
        ], 422);
    }


    $rules = [
        'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
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
    $messages = [
        'name.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',      

    ];
    
    $validatedData = $request->validate($rules, $messages);

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
        'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-]+$/',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:1,2,3',
        'nisn' => 'nullable|size:10|unique:users,nisn|regex:/^[0-9]+$/',
    ];

    // Jika role == 3 (siswa), maka NISN wajib diisi
    if ($request->role == 3) {
        $rules['nisn'] = 'required|size:10|unique:users,nisn|regex:/^[0-9]+$/';
    } 
    $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'name.string'   => 'Nama lengkap harus berupa teks.',
        'name.max'      => 'Nama lengkap maksimal 255 karakter.',
        'name.regex'    => 'Nama lengkap hanya boleh huruf, spasi, titik, dan tanda hubung.',

        'email.required' => 'Email wajib diisi.',
        'email.email'    => 'Format email tidak valid.',
        'email.unique'   => 'Email sudah digunakan, silakan pilih yang lain.',

        'role.required' => 'Role wajib dipilih.',
        'role.in'       => 'Role tidak valid.',

        'nisn.required' => 'NISN wajib diisi untuk siswa.',
        'nisn.size'     => 'NISN harus tepat 10 digit.',
        'nisn.unique'   => 'NISN sudah terdaftar.',
        'nisn.regex'    => 'NISN hanya boleh berupa angka.',
    ];

    $validated = $request->validate($rules, $messages);

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
