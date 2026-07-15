<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GaleryController extends Controller
{
    public function index()
    {
        $galery = Galery::orderBy('created_at', 'desc')->paginate(12);

        return view('admin.galery.index', compact('galery'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|max:500',
            'status' => 'required|in:Draft,Published',
        ]);

        $file = $request->file('foto');
        $name = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/galery'), $name);
        $path = 'uploads/galery/' . $name;

        Galery::create([
            'judul' => $request->judul,
            'foto' => $path,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('galery.index')
            ->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $galery = Galery::findOrFail($id);

        $request->validate([
            'judul' => 'required|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|max:500',
            'status' => 'required|in:Draft,Published',
        ]);

        $data = $request->only('judul', 'deskripsi', 'status');

        if ($request->hasFile('foto')) {
            $old = public_path($galery->foto);
            if ($galery->foto && is_file($old)) {
                @unlink($old);
            }
            $file = $request->file('foto');
            $name = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galery'), $name);
            $data['foto'] = 'uploads/galery/' . $name;
        }

        $galery->update($data);

        return redirect()->route('galery.index')
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $galery = Galery::findOrFail($id);

        $old = public_path($galery->foto);
        if ($galery->foto && is_file($old)) {
            @unlink($old);
        }

        $galery->delete();

        return redirect()->route('galery.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }
}
