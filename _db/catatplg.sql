-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Okt 2024 pada 10.28
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catatplg`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru_bks`
--

CREATE TABLE `guru_bks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `guru_bks`
--

INSERT INTO `guru_bks` (`id`, `user_id`, `kelas_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 'Sri', '2024-10-08 04:02:58', '2024-10-08 04:02:58'),
(2, 11, 2, 'Yulistya', '2024-10-08 04:02:58', '2024-10-08 04:02:58'),
(3, 12, 3, 'Endang', '2024-10-08 04:02:58', '2024-10-08 04:02:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `histories`
--

CREATE TABLE `histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `peraturan_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_peraturan`
--

CREATE TABLE `jenis_peraturan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jenis_peraturan`
--

INSERT INTO `jenis_peraturan` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'SIKAP PERILAKU', '2024-10-08 04:02:58', '2024-10-08 04:02:58'),
(2, 'KERAJINAN', '2024-10-08 04:02:58', '2024-10-08 04:02:58'),
(3, 'KERAPIAN', '2024-10-08 04:02:58', '2024-10-08 04:02:58'),
(4, 'TAMBAHAN', '2024-10-08 04:02:58', '2024-10-08 04:02:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` char(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(1, '12 RPL 1', '2024-10-08 04:02:52', '2024-10-08 04:02:52'),
(2, '12 RPL 2', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(3, '11 RPL 1', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(4, '11 RPL 2', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(5, '10 RPL 1', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(6, '10 RPL 2', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(7, '12 MM 1', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(8, '12 MM 2', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(9, '11 MM 1', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(10, '11 MM 2', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(11, '10 MM 1', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(12, '10 MM 2', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(13, '12 TKJ 1', '2024-10-08 04:02:53', '2024-10-08 04:02:53'),
(14, '12 TKJ 2', '2024-10-08 04:02:54', '2024-10-08 04:02:54'),
(15, '11 TKJ 1', '2024-10-08 04:02:54', '2024-10-08 04:02:54'),
(16, '11 TKJ 2', '2024-10-08 04:02:54', '2024-10-08 04:02:54'),
(17, '10 TKJ 1', '2024-10-08 04:02:54', '2024-10-08 04:02:54'),
(18, '10 TKJ 2', '2024-10-08 04:02:54', '2024-10-08 04:02:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_08_16_044330_create_kelas_table', 1),
(6, '2022_08_27_053415_create_students_table', 1),
(7, '2022_11_01_108955_create_tindak_lanjut_table', 1),
(8, '2022_11_07_061029_create_penanganan_table', 1),
(9, '2023_01_02_131824_create_wali_kelas_table', 1),
(10, '2023_01_06_104717_create_jenis_peraturan_table', 1),
(11, '2023_01_06_105350_create_peraturan_table', 1),
(12, '2023_01_06_110354_create_histories_table', 1),
(13, '2023_02_16_213439_create_guru_bks_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penanganan`
--

CREATE TABLE `penanganan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `tindak_lanjut_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `berkas` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan`
--

CREATE TABLE `peraturan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(300) NOT NULL,
  `poin` int(11) NOT NULL,
  `jenis_peraturan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peraturan`
--

INSERT INTO `peraturan` (`id`, `nama`, `poin`, `jenis_peraturan_id`, `created_at`, `updated_at`) VALUES
(1, 'Tidak membawa buku penghubung dan kartu pelajar.', 10, 1, '2024-10-08 04:02:58', '2024-10-08 04:02:58'),
(2, 'Membuat kegaduhan di kelas atau di sekolah.', 10, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(3, 'Mencoret-coret dinding, pintu, meja, kursi, pagar, dan fasilitas sekolah.', 10, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(4, 'Membawa atau bermain kartu remi dan domino di sekolah.', 10, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(5, 'Menghidupkan dan mengendarai sepeda motor di area tertentu dalam sekolah.', 10, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(6, 'Bermain bola di lapangan(tidak memakai baju OR), di koridor dan di kelas.', 10, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(7, 'Melindungi teman yang bersalah.', 15, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(8, 'Menghidupkan handphone waktu KBM.', 20, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(9, 'Berpacaran di Sekolah dan berduaan yang tidak pada mestinya.', 30, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(10, 'Berperilaku jorok atau asusila, baik di dalam maupun di luar sekolah.', 40, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(11, 'Merayakan ulang tahun secara berlebihan.', 40, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(12, 'Membuang sampah tidak pada tempat sampah khusus yang ditentukan.', 40, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(13, 'Merusak taman dan tanaman yang ada di area sekolah.', 40, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(14, 'Menyalahgunakan uang SPP atau uang sekolah/kelas.', 50, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(15, 'Membawa atau membunyikan petasan di sekolah.', 50, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(16, 'Memalsukan surat izin masuk/keluar sekolah.', 75, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(17, 'Meloncat jendela dan pagar sekolah.', 80, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(18, 'Merusak sarana dan prasarana sekolah.', 80, 1, '2024-10-08 04:02:59', '2024-10-08 04:02:59'),
(19, 'Mengancam / mengintimidasi / bullying teman sekelas/sekolah.', 100, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(20, 'Bertindak tidak sopan / melecehkan Kepala Sekolah, Guru dan Karyawan Sekolah.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(21, 'Mengancam / mengintimidasi Kepala Sekolah, Guru dan Karyawan Sekolah.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(22, 'Menyalahgunakan media sosial yang merugikan pihak lain yang berhubungan dengan sekolah.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(23, 'Berjudi dalam bentuk apapun di sekolah.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(24, 'Membawa senjata tajam, senjata api dsb. di sekolah.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(25, 'Terlibat langsung maupun tidak langsung perkelahian / tawuran di sekolah, di luar sekolah atau antar sekolah.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(26, 'Mengikuti aliran / perkumpulan / geng terlarang / Komunitas LGBT dan radikalisme.', 150, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(27, 'Membuat atau memakai tatto di tubuh.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(28, 'Melakukan pelecehan seksual (pemerkosaan, dll).', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(29, 'Membawa, menggunakan atau mengedarkan miras dan narkoba.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(30, 'Membawa atau membuat VCD Porno, buku porno, majalah porno atau sesuatu yang berbau pornografi dan pornoaksi.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(31, 'Mencuri di sekolah dan di luar sekolah.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(32, 'Memalsukan stempel sekolah, edaran sekolah atau tanda tangan Kepala Sekolah, guru dan karyawan sekolah.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(33, 'Terlibat atau melakukan tindakan kriminal.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(34, 'Mencemarkan nama baik sekolah.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(35, 'Terbukti hamil /  menghamili.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(36, 'Terbukti menikah.', 250, 1, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(37, 'Datang Terlambat.', 10, 2, '2024-10-08 04:03:00', '2024-10-08 04:03:00'),
(38, 'Meninggalkan kelas tanpa izin.', 10, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(39, 'Di kantin saat jam pembelajaran.', 10, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(40, 'Tidak melaksanakan piket harian 7K dan Jumat bersih.', 10, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(41, 'Tidur di Kelas saat pelajaran berlangsung.', 10, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(42, 'Pulang sebelum waktunya, tanpa izin dari sekolah.', 20, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(43, 'Tidak mengikuti upacara.', 20, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(44, 'Tidak mengikuti kegiatan sekolah.', 20, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(45, 'Tidak mengikuti kegiatan ekstrakurikuler pramuka wajib.', 20, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(46, 'Tidak mengikuti pembiasaan membaca kitab suci agama.', 20, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(47, 'Tidak mengikuti kegiatan literasi.', 20, 2, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(48, 'Tidak memakai seragam sesuai dengan ketentuan.', 10, 3, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(49, 'Seragam dicoret-coret.', 10, 3, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(50, 'Melipat lengan baju, baju tidak dikancingkan, tidak rapi.', 10, 3, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(51, 'Berambut panjang tidak sesuai ketentuan (putra).', 10, 3, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(52, 'Tidak memakai kaos kaki sesuai ketentuan.', 10, 3, '2024-10-08 04:03:01', '2024-10-08 04:03:01'),
(53, 'Atribut seragam tidak lengkap.', 10, 3, '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(54, 'Memakai perhiasan berlebihan / tidak sesuai ketentuan.', 10, 3, '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(55, 'Memakai make-up berlebihan (putri).', 30, 3, '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(56, 'Memakai tindik telinga lebih dari 1 (putri) dan tindik lidah.', 30, 3, '2024-10-08 04:03:02', '2024-10-08 04:03:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `nisn` char(10) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `ttl` varchar(255) DEFAULT NULL,
  `jk` varchar(20) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_telp` char(20) DEFAULT NULL,
  `n_ayah` varchar(255) DEFAULT NULL,
  `n_ibu` varchar(255) DEFAULT NULL,
  `alamat_ortu` varchar(255) DEFAULT NULL,
  `no_telp_rumah` char(20) DEFAULT NULL,
  `poin` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `user_id`, `kelas_id`, `nisn`, `nama`, `ttl`, `jk`, `agama`, `alamat`, `no_telp`, `n_ayah`, `n_ibu`, `alamat_ortu`, `no_telp_rumah`, `poin`, `created_at`, `updated_at`) VALUES
(1, 8, 2, '0043846692', 'Renaldy Naufal TA', 'Surabaya, 2004-04-04', 'Laki-laki', 'Islam', 'Pandugo', '0823121231', 'Hendra', 'Putri', 'Pandugo', '0281323', 0, '2024-10-08 04:02:57', '2024-10-08 04:02:57'),
(2, 9, 2, '0051595487', 'Iksan Arya Dinata', 'Surabaya, 2005-05-01', 'Laki-laki', 'Islam', 'Rungkut Lor X makmur 63a kav.22', '088235460449', 'Sunaryo', 'Sarniti', 'Rungkut Lor X makmur 63a kav.22', '081331122643', 0, '2024-10-08 04:02:57', '2024-10-08 04:02:57'),
(3, 13, 3, '1234567890', 'Samsudin', 'Bogor, 2014-10-08', 'Laki-laki', 'Islam', 'Jl. Raya Mangga', '0817889999', 'Marsudin', 'Talita', 'Jl. Raya No. 3', '0817889999', 0, '2024-10-08 08:21:10', '2024-10-08 08:21:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tindak_lanjut`
--

CREATE TABLE `tindak_lanjut` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tindak_lanjut` varchar(255) NOT NULL,
  `tingkatan` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tindak_lanjut`
--

INSERT INTO `tindak_lanjut` (`id`, `tindak_lanjut`, `tingkatan`, `created_at`, `updated_at`) VALUES
(1, 'Peringatan ke I', 'Ringan', '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(2, 'Peringatan ke II', 'Ringan', '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(3, 'Panggilan Orang tua I', 'Sedang', '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(4, 'Panggilan Orang tua II', 'Sedang', '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(5, 'Panggilan Orang tua III', 'Sedang', '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(6, 'Skorsing', 'Berat', '2024-10-08 04:03:02', '2024-10-08 04:03:02'),
(7, 'Dikembalikan Orang tua', 'Berat', '2024-10-08 04:03:02', '2024-10-08 04:03:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nisn` char(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `info` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nisn`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `info`, `created_at`, `updated_at`, `role`) VALUES
(1, '0000000001', 'Admin', 'admin@gmail.com', '2024-10-08 04:02:54', '$2y$10$pLfvnG/bWXzVcU0mP34TM.ea7pAKDExb4wuAFYtPYEnHpFLHU09Ha', NULL, 1, '2024-10-08 04:02:54', '2024-10-08 04:02:54', 1),
(2, '0000000002', 'AsmuIn', 'pakasmuin@gmail.com', '2024-10-08 04:02:54', '$2y$10$F9SIOlMqoD.kGxgitKAeE.I1bmPuSj2xn5bFrh5kOmmi0Uhc6.2bu', NULL, 1, '2024-10-08 04:02:55', '2024-10-08 04:02:55', 2),
(3, '0000000003', 'Lukman Sholeh', 'paklukman@gmail.com', '2024-10-08 04:02:55', '$2y$10$4Z3CrhMi760jwsoRZwru1OwAQgnw0ijbmXHz0SCavzuzTzPeXe4Be', NULL, 1, '2024-10-08 04:02:55', '2024-10-08 04:02:55', 2),
(4, '0000000004', 'Mochammad Arsyad', 'pakarsyad@gmail.com', '2024-10-08 04:02:55', '$2y$10$/jjuyPQHbZ5/ks6MCdLM.OXEBl4QXjJtt/emjVVFBjwTO7bc0TYLe', NULL, 1, '2024-10-08 04:02:55', '2024-10-08 04:02:55', 2),
(5, '0000000005', 'Kukuh Widodo', 'pakkukuh@gmail.com', '2024-10-08 04:02:55', '$2y$10$WFVBpweD8TeSqdiF6ffyUOwccAgYdUe2s6tcqtGIs.Wz8Un4bEP3i', NULL, 1, '2024-10-08 04:02:55', '2024-10-08 04:02:55', 2),
(6, '0000000006', 'Reny Karlinawati', 'bureny@gmail.com', '2024-10-08 04:02:55', '$2y$10$pzmjxApZy/rXgb4oWz/09uuOW0mDvULmIazg.1PTdS6UbsB9uDVx6', NULL, 1, '2024-10-08 04:02:55', '2024-10-08 04:02:55', 2),
(7, '0000000007', 'Farahma Yuanita', 'buyuanita@gmail.com', '2024-10-08 04:02:55', '$2y$10$bS2dbrfTaoWsiRzlvkcIvult.FP4suRlbFct.4SjZ8KIzNzyk4Wye', NULL, 1, '2024-10-08 04:02:55', '2024-10-08 04:02:55', 2),
(8, '0043846692', 'Renaldy Naufal', 'ren@gmail.com', '2024-10-08 04:02:57', '$2y$10$L5J.HVhbECusOKZy5OcD7eu7X8FTcgKOznKFy6/W8fFHnqgdOW.Jq', NULL, 1, '2024-10-08 04:02:57', '2024-10-08 04:02:57', 3),
(9, '0051595487', 'Iksan Arya Dinata', 'san@gmail.com', '2024-10-08 04:02:57', '$2y$10$2JwLmuHxofN4OgJsy7BuCOF2XHwxXpWrwNAY.Z3rMyvxgRPPRgEAO', NULL, 1, '2024-10-08 04:02:57', '2024-10-08 04:02:57', 3),
(10, '452312322', 'Sri', 'sri@gmail.com', '2024-10-08 04:02:57', '$2y$10$2g.BN/RdTSLjvdWOrI4QG.72UtsSHfWkaFrOCm6CTHhjkCIT0GSRW', NULL, 1, '2024-10-08 04:02:58', '2024-10-08 04:02:58', 4),
(11, '231098392', 'Yulistya', 'yulistya@gmail.com', '2024-10-08 04:02:58', '$2y$10$H.a86r9wuSZs3inOeqrDFeEQl2TutMyCk2yN7SLjC8B5.oSOKZyKS', NULL, 1, '2024-10-08 04:02:58', '2024-10-08 04:02:58', 4),
(12, '973726811', 'Endang', 'endang@gmail.com', '2024-10-08 04:02:58', '$2y$10$HmEsIP49tKMEIJEeyxU8i.75W6DzVgBqDlVfwk0pNRwc.tHUy10pi', NULL, 1, '2024-10-08 04:02:58', '2024-10-08 04:02:58', 4),
(13, '1234567890', 'Samsudin', 'samsudin@gmail.com', NULL, '$2y$10$mQUh2JeD.E4BoDYXd8d7yuYF1PKN5SWDLUHE4r9CWjT0SfFi84Coq', NULL, 1, '2024-10-08 08:19:45', '2024-10-08 08:21:10', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wali_kelas`
--

CREATE TABLE `wali_kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `wali_kelas`
--

INSERT INTO `wali_kelas` (`id`, `user_id`, `kelas_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Asmuin', '2024-10-08 04:02:56', '2024-10-08 04:02:56'),
(2, 3, 2, 'Lukman Sholeh', '2024-10-08 04:02:56', '2024-10-08 04:02:56'),
(3, 4, 3, 'Mochammad Arsyad', '2024-10-08 04:02:56', '2024-10-08 04:02:56'),
(4, 5, 4, 'Kukuh Widodo', '2024-10-08 04:02:56', '2024-10-08 04:02:56'),
(5, 6, 5, 'Reny Karlinawati', '2024-10-08 04:02:56', '2024-10-08 04:02:56'),
(6, 7, 6, 'Farahma Yuanita', '2024-10-08 04:02:56', '2024-10-08 04:02:56');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `guru_bks`
--
ALTER TABLE `guru_bks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_bks_user_id_foreign` (`user_id`),
  ADD KEY `guru_bks_kelas_id_foreign` (`kelas_id`);

--
-- Indeks untuk tabel `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `histories_student_id_foreign` (`student_id`),
  ADD KEY `histories_peraturan_id_foreign` (`peraturan_id`);

--
-- Indeks untuk tabel `jenis_peraturan`
--
ALTER TABLE `jenis_peraturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `penanganan`
--
ALTER TABLE `penanganan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penanganan_student_id_foreign` (`student_id`),
  ADD KEY `penanganan_tindak_lanjut_id_foreign` (`tindak_lanjut_id`);

--
-- Indeks untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peraturan_jenis_peraturan_id_foreign` (`jenis_peraturan_id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_nisn_unique` (`nisn`),
  ADD UNIQUE KEY `students_no_telp_unique` (`no_telp`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_kelas_id_foreign` (`kelas_id`);

--
-- Indeks untuk tabel `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nisn_unique` (`nisn`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wali_kelas_user_id_foreign` (`user_id`),
  ADD KEY `wali_kelas_kelas_id_foreign` (`kelas_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guru_bks`
--
ALTER TABLE `guru_bks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `histories`
--
ALTER TABLE `histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jenis_peraturan`
--
ALTER TABLE `jenis_peraturan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `penanganan`
--
ALTER TABLE `penanganan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `wali_kelas`
--
ALTER TABLE `wali_kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `guru_bks`
--
ALTER TABLE `guru_bks`
  ADD CONSTRAINT `guru_bks_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guru_bks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `histories`
--
ALTER TABLE `histories`
  ADD CONSTRAINT `histories_peraturan_id_foreign` FOREIGN KEY (`peraturan_id`) REFERENCES `peraturan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `histories_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penanganan`
--
ALTER TABLE `penanganan`
  ADD CONSTRAINT `penanganan_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penanganan_tindak_lanjut_id_foreign` FOREIGN KEY (`tindak_lanjut_id`) REFERENCES `tindak_lanjut` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  ADD CONSTRAINT `peraturan_jenis_peraturan_id_foreign` FOREIGN KEY (`jenis_peraturan_id`) REFERENCES `jenis_peraturan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD CONSTRAINT `wali_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wali_kelas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
