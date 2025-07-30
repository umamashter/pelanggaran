<?php

namespace Database\Seeders;

use App\Models\Peraturan;
use Illuminate\Database\Seeder;

class PeraturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Jenis SIKAP PERILAKU
        Peraturan::create([
            'nama' => 'Tidak membawa buku penghubung dan kartu pelajar.',
            'poin' => 10,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membuat kegaduhan di kelas atau di sekolah.',
            'poin' => 10,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Mencoret-coret dinding, pintu, meja, kursi, pagar, dan fasilitas sekolah.',
            'poin' => 10,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membawa atau bermain kartu remi dan domino di sekolah.',
            'poin' => 10,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Menghidupkan dan mengendarai sepeda motor di area tertentu dalam sekolah.',
            'poin' => 10,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Bermain bola di lapangan(tidak memakai baju OR), di koridor dan di kelas.',
            'poin' => 10,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Melindungi teman yang bersalah.',
            'poin' => 15,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Menghidupkan handphone waktu KBM.',
            'poin' => 20,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Berpacaran di Sekolah dan berduaan yang tidak pada mestinya.',
            'poin' => 30,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Berperilaku jorok atau asusila, baik di dalam maupun di luar sekolah.',
            'poin' => 40,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Merayakan ulang tahun secara berlebihan.',
            'poin' => 40,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membuang sampah tidak pada tempat sampah khusus yang ditentukan.',
            'poin' => 40,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Merusak taman dan tanaman yang ada di area sekolah.',
            'poin' => 40,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Menyalahgunakan uang SPP atau uang sekolah/kelas.',
            'poin' => 50,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membawa atau membunyikan petasan di sekolah.',
            'poin' => 50,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Memalsukan surat izin masuk/keluar sekolah.',
            'poin' => 75,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Meloncat jendela dan pagar sekolah.',
            'poin' => 80,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Merusak sarana dan prasarana sekolah.',
            'poin' => 80,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Mengancam / mengintimidasi / bullying teman sekelas/sekolah.',
            'poin' => 100,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Bertindak tidak sopan / melecehkan Kepala Sekolah, Guru dan Karyawan Sekolah.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Mengancam / mengintimidasi Kepala Sekolah, Guru dan Karyawan Sekolah.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Menyalahgunakan media sosial yang merugikan pihak lain yang berhubungan dengan sekolah.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Berjudi dalam bentuk apapun di sekolah.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membawa senjata tajam, senjata api dsb. di sekolah.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Terlibat langsung maupun tidak langsung perkelahian / tawuran di sekolah, di luar sekolah atau antar sekolah.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Mengikuti aliran / perkumpulan / geng terlarang / Komunitas LGBT dan radikalisme.',
            'poin' => 150,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membuat atau memakai tatto di tubuh.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Melakukan pelecehan seksual (pemerkosaan, dll).',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membawa, menggunakan atau mengedarkan miras dan narkoba.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Membawa atau membuat VCD Porno, buku porno, majalah porno atau sesuatu yang berbau pornografi dan pornoaksi.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Mencuri di sekolah dan di luar sekolah.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Memalsukan stempel sekolah, edaran sekolah atau tanda tangan Kepala Sekolah, guru dan karyawan sekolah.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Terlibat atau melakukan tindakan kriminal.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Mencemarkan nama baik sekolah.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Terbukti hamil /  menghamili.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);
        Peraturan::create([
            'nama' => 'Terbukti menikah.',
            'poin' => 250,
            'jenis_peraturan_id' => 1
        ]);

        // Jenis KERAJINAN
        Peraturan::create([
            'nama' => 'Datang Terlambat.',
            'poin' => 10,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Meninggalkan kelas tanpa izin.',
            'poin' => 10,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Di kantin saat jam pembelajaran.',
            'poin' => 10,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidak melaksanakan piket harian 7K dan Jumat bersih.',
            'poin' => 10,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidur di Kelas saat pelajaran berlangsung.',
            'poin' => 10,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Pulang sebelum waktunya, tanpa izin dari sekolah.',
            'poin' => 20,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidak mengikuti upacara.',
            'poin' => 20,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidak mengikuti kegiatan sekolah.',
            'poin' => 20,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidak mengikuti kegiatan ekstrakurikuler pramuka wajib.',
            'poin' => 20,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidak mengikuti pembiasaan membaca kitab suci agama.',
            'poin' => 20,
            'jenis_peraturan_id' => 2
        ]);
        Peraturan::create([
            'nama' => 'Tidak mengikuti kegiatan literasi.',
            'poin' => 20,
            'jenis_peraturan_id' => 2
        ]);

        // Jenis KERAPIAN
        Peraturan::create([
            'nama' => 'Tidak memakai seragam sesuai dengan ketentuan.',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Seragam dicoret-coret.',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Melipat lengan baju, baju tidak dikancingkan, tidak rapi.',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Berambut panjang tidak sesuai ketentuan (putra).',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Tidak memakai kaos kaki sesuai ketentuan.',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Atribut seragam tidak lengkap.',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Memakai perhiasan berlebihan / tidak sesuai ketentuan.',
            'poin' => 10,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Memakai make-up berlebihan (putri).',
            'poin' => 30,
            'jenis_peraturan_id' => 3
        ]);
        Peraturan::create([
            'nama' => 'Memakai tindik telinga lebih dari 1 (putri) dan tindik lidah.',
            'poin' => 30,
            'jenis_peraturan_id' => 3
        ]);
    }
}