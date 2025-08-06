<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Penanganan;
use App\Models\Peraturan;
use App\Models\Student;
use RealRashid\SweetAlert\Facades\Alert;
// WA API
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\WhatsAppHelper;





class PoinController extends Controller
{
    public $message = [
        'max' => ':attribute maksimal :max Karakter!',
        'min' => ':attribute minimal :min Karakter!',
        'image' => ':attribute harus berupa Foto!',
        'unique' => ':attribute sudah digunakan!',
        'required' => ':attribute harus di isi!',
        'numeric' => ':attribute harus berisi angka!',
        'digits' => ':attribute harus berisi 10 digit'
    ];

    public function tambah_view(Student $siswa)
    {
        return view('admin.page.poin.tambah-poin', [
            'siswa' => $siswa,
            'rules' => Peraturan::oldest()->filter(request('search'))->get()
        ]);
    }

    public function tambah_poin(Request $request, $id)
    {
        $siswa = Student::findOrFail($id);
        $penanganan = Penanganan::where('student_id', $siswa->id)->get();
        // ✅ CEK: Apakah poin siswa sudah mencapai batas maksimum
        if ($siswa->poin >= 149) {
            return redirect()->back()->with('error', 'Siswa sudah mencapai batas maksimum poin. Tidak dapat menambahkan pelanggaran.');
        }

        if ($request->total == 0 || $request->total == '') {
            return redirect()->back()->with('error', 'Poin tidak valid!');
        }

        $tindak_lanjut = [];
        foreach ($penanganan as $item) {
            $tindak_lanjut[] = $item->pesan->tindak_lanjut;
        }

        $histories = $request->input('rule');

        foreach ($histories as $historyId) {
            $newHistory = History::create([
                'student_id' => $siswa->id,
                'peraturan_id' => $historyId,
                'tanggal' => date('Y-m-d', time()),
                'kelas_saat_pelanggaran' => $siswa->kelas_id // ✅ SNAPSHOT!
            ]);

            $siswa->poin += $newHistory->rule->poin;
            // Pastikan poin tidak melebihi 149
            if ($siswa->poin > 149) {
                $siswa->poin = 149;
            }
            $siswa->save();

            $this->syncPenanganan($siswa, $newHistory->id, $tindak_lanjut);

            // WhatsApp tidak langsung dikirim. Akan dikirim manual dari halaman riwayat.
            // $nomor = preg_replace('/^0/', '62', $siswa->no_telp);
            // $this->kirimNotifikasiWhatsApp($nomor, "Notifikasi: {$siswa->name} mendapat pelanggaran baru. Total poin saat ini: {$siswa->poin}.");
            
            }

        return redirect('/master-siswa')->with('success', 'Poin berhasil ditambahkan');
    }

    public function kurang_view(Student $siswa)
    {
        return view('admin.page.poin.kurang-poin', [
            'siswa' => $siswa,
            'rules' => Peraturan::all()
        ]);
    }

    public function kurang_poin(Request $request, $id)
    {
        $this->validate($request, [
            'poin' => 'required'
        ], $this->message);

        $siswa = Student::findOrFail($id);

        if ($siswa->poin < $request->poin) {
            return redirect()->back()->with('toast_error', 'Poin tidak valid!');
        }

        $siswa->update([
            'poin' => $siswa->poin - $request->poin
        ]);

        return redirect('master-siswa')->with('success', 'Poin berhasil dikurangi');
    }

    public function destroy($id)
    {
        $history = History::findOrFail($id);
        $poinPelanggaran = $history->rule->poin;

        $siswa = Student::findOrFail($history->student_id);
        $siswa->update([
            'poin' => max(0, $siswa->poin - $poinPelanggaran)
        ]);

        // Hapus penanganan yang terkait dengan histori ini
        Penanganan::where('history_id', $history->id)->delete();

        // Hapus histori
        $history->delete();

        return redirect()->back()->with('success', 'Histori dan penanganan berhasil dihapus.');
    }

    private function syncPenanganan($siswa, $historyId, $tindak_lanjut)
    {
        $poin = $siswa->poin;

        if ($poin >= 10 && $poin <= 35 && !in_array('Peringatan ke I', $tindak_lanjut)) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 1,
                'history_id' => $historyId
            ]);
        }

        if ($poin >= 36 && $poin <= 55 && !in_array('Peringatan ke II', $tindak_lanjut)) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 2,
                'history_id' => $historyId
            ]);
        }

        if ($poin >= 56 && $poin <= 75 && !in_array('Panggilan Orang tua I', $tindak_lanjut)) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 3,
                'history_id' => $historyId
            ]);
        }

        if ($poin >= 76 && $poin <= 95 && !in_array('Panggilan Orang tua II', $tindak_lanjut)) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 4,
                'history_id' => $historyId
            ]);
        }

        if ($poin >= 96 && $poin <= 149 && !in_array('Panggilan Orang tua III', $tindak_lanjut)) {
            Penanganan::create([
                'student_id' => $siswa->id,
                'tindak_lanjut_id' => 5,
                'history_id' => $historyId
            ]);
        }

        
        
    }

    // kontrol untuk notifikasi whatsapp
    public function kirimNotifikasi($id)
{
    // Ambil data history lengkap dengan relasi student, kelas, dan pelanggaran
    $history = History::with(['student.kelas', 'pelanggaran'])->findOrFail($id);
    $siswa = $history->student;
    $pelanggaran = $history->pelanggaran;

    if (!$siswa || !$pelanggaran) {
        return response()->json(['status' => 'error', 'message' => 'Data siswa atau pelanggaran tidak ditemukan.']);
    }

    // ✅ Tambahkan pengecekan poin minimal 56
    if ($siswa->poin < 56) {
        return response()->json([
            'status' => 'ignored',
            'message' => 'Poin siswa belum mencapai ambang notifikasi (minimal 56).'
        ]);
    }
    // Format nomor WA (dari 08xxx ke 628xxx)
    $nomor = preg_replace('/^08/', '62', $siswa->no_telp);

    // Susun isi pesan yang lebih informatif
    $namaSiswa = $siswa->nama ?? 'Tidak Diketahui';
    $namaKelas = $siswa->kelas->nama_kelas ?? 'Tidak Diketahui';
    $namaPelanggaran = $pelanggaran->nama ?? 'Tidak Diketahui';
    $poin = $siswa->poin ?? 0;

    $pesan = "Notifikasi: {$namaSiswa} (Kelas: {$namaKelas}) melakukan pelanggaran: {$namaPelanggaran}. Total poin: {$poin}.";

    try {
        WhatsAppHelper::kirim($nomor, $pesan);
        return response()->json(['status' => 'success', 'message' => 'Pesan berhasil dikirim.']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Gagal mengirim pesan.']);
    }
}


}
