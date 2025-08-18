<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Penanganan;
use App\Models\TindakLanjut;
use App\Models\Peraturan;
use App\Models\Student;
use RealRashid\SweetAlert\Facades\Alert;
// WA API
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\WhatsAppHelper;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\Notifikasi;  // sesuaikan nama model







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
        if ($siswa->poin >= 100) {
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
            // Pastikan poin tidak melebihi 100
            if ($siswa->poin > 100) {
                $siswa->poin = 100;
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
        $siswa = Student::findOrFail($id);

        // Ambil poin input dari request
        $poinInput = $request->input('poin');

        // Jika poin input tidak sama dengan poin siswa
        if ($poinInput != $siswa->poin) {
            return redirect()->back()->with('warning', 'Poin yang diinput tidak sesuai dengan poin siswa.');
        }

        // Jika poin sudah 0, tidak perlu direset
        if ($siswa->poin == 0) {
            return redirect()->back()->with('info', 'Poin siswa sudah 0, tidak perlu direset.');
        }

        // Reset poin ke 0
        $siswa->update([
            'poin' => 0
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

        // Poin 10–20 → peringatan lisan (Ringan)
        if ($poin >= 10 && $poin <= 20 && !in_array('peringatan lisan', $tindak_lanjut)) {
            $tindak = TindakLanjut::where('tindak_lanjut', 'peringatan lisan')->first();
            if ($tindak) {
                Penanganan::create([
                    'student_id' => $siswa->id,
                    'tindak_lanjut_id' => $tindak->id,
                    'history_id' => $historyId
                ]);
            }
        }

        // Poin 21–40 → Tugas Reflektif (Ringan)
        if ($poin >= 21 && $poin <= 40 && !in_array('Tugas Reflektif', $tindak_lanjut)) {
            $tindak = TindakLanjut::where('tindak_lanjut', 'Tugas Reflektif')->first();
            if ($tindak) {
                Penanganan::create([
                    'student_id' => $siswa->id,
                    'tindak_lanjut_id' => $tindak->id,
                    'history_id' => $historyId
                ]);
            }
        }

        // Poin 41–60 → Mengaji 1 Juz (Sedang)
        if ($poin >= 41 && $poin <= 60 && !in_array('Mengaji 1 Juz', $tindak_lanjut)) {
            $tindak = TindakLanjut::where('tindak_lanjut', 'Mengaji 1 Juz')->first();
            if ($tindak) {
                Penanganan::create([
                    'student_id' => $siswa->id,
                    'tindak_lanjut_id' => $tindak->id,
                    'history_id' => $historyId
                ]);
            }
        }

        // Poin 61–80 → Pembinaan Wali Kelas (Sedang)
        if ($poin >= 61 && $poin <= 80 && !in_array('Pembinaan Wali Kelas', $tindak_lanjut)) {
            $tindak = TindakLanjut::where('tindak_lanjut', 'Pembinaan Wali Kelas')->first();
            if ($tindak) {
                Penanganan::create([
                    'student_id' => $siswa->id,
                    'tindak_lanjut_id' => $tindak->id,
                    'history_id' => $historyId
                ]);
            }
        }

        // Poin 81–100 → Surat Pemanggilan Orang Tua (Berat)
        if ($poin >= 81 && $poin <= 100 && !in_array('Surat Pemanggilan Orang Tua', $tindak_lanjut)) {
            $tindak = TindakLanjut::where('tindak_lanjut', 'Surat Pemanggilan Orang Tua')->first();
            if ($tindak) {
                Penanganan::create([
                    'student_id' => $siswa->id,
                    'tindak_lanjut_id' => $tindak->id,
                    'history_id' => $historyId
                ]);
                // Panggil fungsi kirim surat hanya untuk tingkatan berat
            }
        }
    }

    public function kirimNotifikasi($id)
    {
        // Konstanta / config
        $WA_THRESHOLD = 50;

        $history = History::with(['student.kelas', 'rule', 'student.user', 'penanganan'])->findOrFail($id);
        $siswa = $history->student;
        $pelanggaran = $history->rule;

        if (!$siswa || !$pelanggaran) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        // Hanya kirim jika penanganan masih 0 (belum dikonfirmasi)
        if ((int) optional($history->penanganan)->status !== 0) {
            return response()->json([
                'status'  => 'ignored',
                'message' => 'Penanganan sudah dikonfirmasi.'
            ], 422);
        }

        // Pastikan poin sudah mencapai ambang batas
        if (($siswa->poin ?? 0) < $WA_THRESHOLD) {
            return response()->json([
                'status'  => 'ignored',
                'message' => 'Poin belum mencapai ambang.'
            ], 422);
        }


        // Sanitasi nomor: hapus non-digit, lalu normalisasi ke '62...'
        $no = preg_replace('/\D+/', '', $siswa->no_telp ?? '');
        if (Str::startsWith($no, '0')) {
            $no = '62' . substr($no, 1);
        } elseif (!Str::startsWith($no, '62')) {
            // opsional: reject atau tambahkan handling lain
        }

        if (strlen($no) < 10) {
            return response()->json(['status' => 'error', 'message' => 'Nomor telepon tidak valid.'], 422);
        }

        $namaSiswa = optional($siswa->user)->name ?? $siswa->nama ?? 'Nama Tidak Diketahui';
        $pesan = "*Assalamualaikum: Notifikasi Pelanggaran Siswa:*\n\n"
            . "👤 Nama: $namaSiswa\n"
            . "🏫 Kelas: " . ($siswa->kelas->nama_kelas ?? '-') . "\n"
            . "🚫 Pelanggaran: " . ($pelanggaran->nama ?? '-') . "\n"
            . "⚠️ Total Poin: " . ($siswa->poin ?? 0) . "\n";

        try {
            // Disarankan: push ke queue. Contoh langsung untuk backward compatibility:
            WhatsAppHelper::kirim($no, $pesan);

            // Log / simpan audit
            Log::info('WA sent', ['history_id' => $id, 'to' => $no]);

            return response()->json(['status' => 'success', 'message' => 'Pesan berhasil dikirim.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to send WA', ['history_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim pesan.'], 500);
        }
    }
}
