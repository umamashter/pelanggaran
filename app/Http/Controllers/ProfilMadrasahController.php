<?php

namespace App\Http\Controllers;

use App\Models\ProfilMadrasah;
use App\Models\Misi;
use Illuminate\Http\Request;

class ProfilMadrasahController extends Controller
{
    public function index()
    {
        $profil = ProfilMadrasah::with('misi')->first();

        if (!$profil) {
            $profil = ProfilMadrasah::create([
                'nama_madrasah' => 'MIS Nurul Ulum',
                'visi' => 'Terwujudnya generasi yang beriman, bertakwa, berakhlak mulia, cerdas, terampil, dan berwawasan lingkungan.',
                'alamat' => 'Jl. Datuk Idris, Patapan, Kec. Guluk Guluk, Sumenep, Jawa Timur 69463',
                'telepon' => '083848122859',
                'email' => 'misnurululum@sch.id',
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.123!2d113.6769496!3d-7.0746166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd9dfb82bc17cef%3A0x47791ac42d93e284!2sMadrasah%20Nurul%20Ulum%20Patapan!5e0!3m2!1sid!2sid!4v1',
            ]);

            Misi::insert([
                ['profil_madrasah_id' => $profil->id, 'item' => 'Menanamkan nilai-nilai keislaman dalam setiap proses pembelajaran.', 'urutan' => 1],
                ['profil_madrasah_id' => $profil->id, 'item' => 'Mengembangkan potensi akademik dan non-akademik siswa secara optimal.', 'urutan' => 2],
                ['profil_madrasah_id' => $profil->id, 'item' => 'Membudayakan perilaku disiplin, jujur, dan bertanggung jawab.', 'urutan' => 3],
                ['profil_madrasah_id' => $profil->id, 'item' => 'Menciptakan lingkungan madrasah yang bersih, nyaman, dan kondusif.', 'urutan' => 4],
            ]);

            $profil->load('misi');
        }

        return view('admin.profil-madrasah.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = ProfilMadrasah::with('misi')->firstOrFail();

        $request->validate([
            'nama_madrasah' => 'required|max:100',
            'visi' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|max:30',
            'email' => 'required|email|max:100',
            'map_embed' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'misi_items' => 'required|array|min:1',
            'misi_items.*' => 'required|string|max:500',
        ]);

        $profil->update([
            'nama_madrasah' => $request->nama_madrasah,
            'visi' => $request->visi,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'map_embed' => $request->map_embed,
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/profil');
            $profil->update(['foto' => str_replace('public/', '', $path)]);
        }

        // Sync misi
        $profil->misi()->delete();
        $items = [];
        foreach ($request->misi_items as $i => $item) {
            if (trim($item)) {
                $items[] = [
                    'profil_madrasah_id' => $profil->id,
                    'item' => trim($item),
                    'urutan' => $i + 1,
                ];
            }
        }
        Misi::insert($items);

        return redirect()->route('profil-madrasah.index')
            ->with('success', 'Profil madrasah berhasil diperbarui.');
    }
}
