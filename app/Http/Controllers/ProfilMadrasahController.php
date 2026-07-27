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
            // Identitas Sekolah
            'nama_madrasah' => 'required|max:100',
            'npsn' => 'nullable|max:20',
            'nsm' => 'nullable|max:20',
            'nis_nss' => 'nullable|max:20',
            'jenjang' => 'nullable|max:50',
            'status_sekolah' => 'nullable|max:20',
            'status_akreditasi' => 'nullable|max:10',
            'tahun_berdiri' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'kurikulum' => 'nullable|max:50',
            'visi' => 'required',

            // Data Yayasan
            'nama_yayasan' => 'nullable|max:150',
            'nomor_akta_yayasan' => 'nullable|max:50',
            'nomor_sk_kemenkumham' => 'nullable|max:50',
            'tahun_berdiri_yayasan' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'alamat_yayasan' => 'nullable',
            'nama_ketua_yayasan' => 'nullable|max:100',

            // Alamat & Kontak
            'alamat' => 'required',
            'desa_kelurahan' => 'nullable|max:100',
            'kecamatan' => 'nullable|max:100',
            'kabupaten_kota' => 'nullable|max:100',
            'provinsi' => 'nullable|max:100',
            'kode_pos' => 'nullable|max:10',
            'telepon' => 'required|max:30',
            'email' => 'required|email|max:100',
            'website' => 'nullable|max:150',
            'whatsapp' => 'nullable|max:30',
            'map_embed' => 'nullable',

            // Data Kepala Sekolah
            'nama_kepala_sekolah' => 'nullable|max:100',
            'nip_niy' => 'nullable|max:30',
            'npk' => 'nullable|max:30',
            'nuptk' => 'nullable|max:30',
            'nomor_sk_pengangkatan' => 'nullable|max:50',
            'tanggal_sk' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|max:50',

            // Foto & Misi
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'misi_items' => 'required|array|min:1',
            'misi_items.*' => 'required|string|max:500',
        ]);

        $profil->update($request->only([
            // Identitas Sekolah
            'nama_madrasah', 'npsn', 'nsm', 'nis_nss', 'jenjang',
            'status_sekolah', 'status_akreditasi', 'tahun_berdiri', 'kurikulum', 'visi',
            // Data Yayasan
            'nama_yayasan', 'nomor_akta_yayasan', 'nomor_sk_kemenkumham',
            'tahun_berdiri_yayasan', 'alamat_yayasan', 'nama_ketua_yayasan',
            // Alamat & Kontak
            'alamat', 'desa_kelurahan', 'kecamatan', 'kabupaten_kota',
            'provinsi', 'kode_pos', 'telepon', 'email', 'website', 'whatsapp', 'map_embed',
            // Data Kepala Sekolah
            'nama_kepala_sekolah', 'nip_niy', 'npk', 'nuptk',
            'nomor_sk_pengangkatan', 'tanggal_sk', 'pendidikan_terakhir',
        ]));

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
