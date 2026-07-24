<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PenangananController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GuruBkController;
use App\Http\Controllers\MasterGuruController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengampuMapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\StrukturKurikulumController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\HaflatulImtihanController;
use App\Http\Controllers\SesiLombaController;
use App\Http\Controllers\KategoriLombaController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\PesertaLombaController;
use App\Http\Controllers\KelompokLombaController;
use App\Http\Controllers\AnggotaKelompokController;
use App\Http\Controllers\JuriLombaController;
use App\Http\Controllers\AspekPenilaianController;
use App\Http\Controllers\PenilaianLombaController;
use App\Http\Controllers\HasilLombaController;
use App\Http\Controllers\SesiController;
use App\Models\Poin;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\ProfilMadrasahController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\ActiveSessionController;
use App\Http\Controllers\TwoFactorPolicyController;
use App\Http\Controllers\SecurityDashboardController;
use App\Http\Controllers\AccountCenterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GuruAbsensiGuruController;
use App\Http\Controllers\AdminAbsensiGuruController;
use App\Http\Controllers\LokasiMadrasahController;
use App\Http\Controllers\AbsensiImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FrontController::class, 'index']);
Route::get('/api/{nisn}', [FrontController::class, 'search_nisn']);

// leaderboard
Route::get('/you-cant-see-me', [BoardController::class, 'unique'])->name('unique')->middleware('guest');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 2FA
Route::get('/2fa/challenge', [TwoFactorController::class, 'challengeView'])->name('2fa.challenge');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->middleware('throttle:5,1')->name('2fa.verify');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Input More Info
Route::post('/siswa/store', [StudentController::class, 'store'])->name('siswa');

//new
Route::get('/lengkapi-data', [StudentController::class, 'create'])->name('lengkapi-data');


// Anti-loop order (TDD §6): auth → 2fa → require.2fa → (datasiswa/role)
// 2fa: punya secret tapi belum verifikasi → /2fa/challenge
// require.2fa: role wajib TANPA secret → /2fa/setup
Route::group(['middleware' => ['auth', '2fa', 'require.2fa']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Riwayat login — milik sendiri (semua role)
    Route::get('/riwayat-login', [LoginHistoryController::class, 'index'])->name('login-history.index');

    // Notifikasi (in-app bell) — milik sendiri (semua role)
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Perangkat aktif (Active Sessions) — milik sendiri (semua role)
    Route::get('/perangkat', [ActiveSessionController::class, 'index'])->name('active-sessions.index');
    Route::post('/perangkat/revoke/{sessionId}', [ActiveSessionController::class, 'revoke'])->name('active-sessions.revoke');
    Route::post('/perangkat/revoke-others', [ActiveSessionController::class, 'revokeOthers'])->name('active-sessions.revoke-others');
    Route::post('/perangkat/trust/{fingerprintId}', [ActiveSessionController::class, 'trust'])->name('active-sessions.trust');
    Route::post('/perangkat/untrust/{fingerprintId}', [ActiveSessionController::class, 'untrust'])->name('active-sessions.untrust');

    // Profil Saya / Account Center
    Route::prefix('profil-saya')->name('profil-saya.')->controller(AccountCenterController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/profil', 'updateProfile')->name('profile');
        Route::post('/foto', 'updatePhoto')->name('photo');
        Route::delete('/foto', 'removePhoto')->name('photo.destroy');
        Route::post('/password', 'updatePassword')->name('password');
        Route::post('/preferensi', 'updatePreferences')->name('preferences');
    });

    // 2FA (authenticated)
    Route::get('/2fa/setup', [TwoFactorController::class, 'setupView'])->name('2fa.setup');
    Route::post('/2fa/setup', [TwoFactorController::class, 'setup']);
    Route::get('/2fa/recovery-codes', [TwoFactorController::class, 'recoveryCodes'])->name('2fa.recovery-codes');

    // 2FA disable — require.2fa TIDAK meng-allow ini (sekali disable → setup lagi, konsisten)
    // tapi 2fa middleware harus allow agar user bisa disable. Letakkan disable dengan pengecualian.
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Siswa
    Route::group(['middleware' => ['role:3']], function () {
        Route::get('/editsiswa', [StudentController::class, 'show']);
        Route::put('/updatesiswa/{id}', [StudentController::class, 'update']);
        Route::get('/ubah-pass', [StudentController::class, 'view_ubah']);
        Route::put('/ubah-pass/{id}', [StudentController::class, 'update_pass']);
        Route::get('/histori', [StudentController::class, 'history']);
        Route::get('/pesan', [StudentController::class, 'pesan']);
        Route::get('/pesan/{id}', [StudentController::class, 'checkpesan']);
        // Route::get('/tata-tertib', [StudentController::class, 'history']);
        Route::get('/tata-tertib', [PeraturanController::class, 'index'])->name('siswa.peraturan');
    });

    // Guru
    Route::group(['middleware' => ['role:2']], function () {
        Route::get('/guru/daftar-siswa', [GuruController::class, 'daftar_siswa']);
        Route::get('/guru/ubah-pass', [GuruController::class, 'view_ubah']);
        Route::put('/guru/ubah-pass/{id}', [GuruController::class, 'update_pass']);
        Route::get('/guru/histori', [GuruController::class, 'master_history']);
        Route::get('/guru/histori/{id}', [GuruController::class, 'history_siswa']);
        Route::get('/guru/penanganan', [PenangananController::class, 'guru_index']);
        Route::post('/guru/penanganan/{id}', [PenangananController::class, 'guru_konfirmasi']);

        // Absensi Guru
        Route::get('/guru/absensi-guru', [GuruAbsensiGuruController::class, 'show'])->name('guru.absensi-guru.show');
        Route::post('/guru/absensi-guru', [GuruAbsensiGuruController::class, 'store'])->name('guru.absensi-guru.store');
        Route::get('/guru/absensi-guru/riwayat', [GuruAbsensiGuruController::class, 'riwayat'])->name('guru.absensi-guru.riwayat');


        // Tambah Poin

    });

    // Admin
    Route::group(['middleware' => ['role:1']], function () {

        // Riwayat login — semua user (admin only)
        Route::get('/admin/riwayat-login', [LoginHistoryController::class, 'adminIndex'])->name('admin.login-history.index');

        // Dashboard keamanan (admin only)
        Route::get('/admin/keamanan', [SecurityDashboardController::class, 'index'])->name('admin.security-dashboard.index');

        // Kebijakan 2FA per role (admin only) — Fase 5
        Route::get('/admin/kebijakan-2fa', [TwoFactorPolicyController::class, 'index'])->name('admin.2fa-policy.index');
        Route::post('/admin/kebijakan-2fa/toggle/{role}', [TwoFactorPolicyController::class, 'toggle'])->name('admin.2fa-policy.toggle');

        // Daftar user
        Route::get('/master-user/create', [UserAdminController::class, 'create'])->name('user.create');
        Route::post('/master-user/store', [UserAdminController::class, 'store'])->name('user.store');
        Route::get('/master-siswa', [AdminController::class, 'daftar_siswa']);
        Route::post('/master-siswa/store', [AdminController::class, 'store_siswa'])->name('master-siswa.store');
        Route::get('/master-siswa/template', [AdminController::class, 'template_siswa'])->name('master-siswa.template');
        Route::post('/master-siswa/import', [AdminController::class, 'import_siswa'])->name('master-siswa.import');
        Route::get('/master-user', [UserAdminController::class, 'daftar_user']);
        Route::get('/master-user/{id}/edit', [UserAdminController::class, 'edit']);
        Route::put('/master-user/{id}', [UserAdminController::class, 'update']);
        Route::put('/change-pass/{id}', [UserAdminController::class, 'update_pass']);
        Route::post('/master-user/{id}', [UserAdminController::class, 'destroy']);
        Route::put('/master-siswa/{id}', [AdminController::class, 'update_siswa'])->name('siswa.update');
        Route::get('/master-siswa/detail/{id}', [AdminController::class, 'detail_siswa'])->name('master-siswa.detail');
        Route::post('/user/import', [UserAdminController::class, 'import'])->name('user.import');
        Route::get('/master-user', [UserAdminController::class, 'daftar_user'])->name('user.index');

        //jadwal pelajaran
        Route::get('/jadwal-pelajaran/export-pdf', [JadwalPelajaranController::class, 'exportPdf'])->name('jadwal-pelajaran.export-pdf');
        Route::post('/jadwal-pelajaran/salin', [JadwalPelajaranController::class, 'salin'])->name('jadwal-pelajaran.salin');
        Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);

        //jadwalPerkelas
        Route::get('/jadwal-pelajaran/kelas/{id}', [JadwalPelajaranController::class, 'perKelas'])->name('jadwal-pelajaran.perkelas');
        Route::get('/jadwal-pelajaran/cetak/{id}', [JadwalPelajaranController::class, 'cetakPerKelas'])->name('jadwal-pelajaran.cetak');
        Route::get('/jadwal-jenjang/{jenjang}/pdf',  [JadwalPelajaranController::class, 'cetakJenjang'])->name('jadwal-jenjang.pdf');
        Route::get('/jadwal-jenjang/{jenjang}', [JadwalPelajaranController::class, 'detailJenjang'])->name('jadwal-jenjang.detail');
        Route::get('/jadwal-pelajaran/kelas/{id}', [JadwalPelajaranController::class, 'perKelas'])->name('jadwal-pelajaran.per-kelas');
        Route::get('/jadwal-per-kelas', [JadwalPelajaranController::class, 'daftarKelas'])->name('jadwal-pelajaran.daftar-kelas');
        Route::get('/jadwal-jenjang', [JadwalPelajaranController::class, 'jadwalJenjang'])->name('jadwal-jenjang');
        Route::get('/jadwal-grid', [JadwalPelajaranController::class, 'grid'])->name('jadwal.grid');

        //jadwal siswa
        Route::get('/jadwal-siswa/cetak/{jenjang_id?}', [JadwalPelajaranController::class, 'cetakJadwalSiswa'])->name('jadwal-siswa.cetak');
        Route::get('/jadwal-siswa', [JadwalPelajaranController::class, 'jadwalSiswa'])->name('jadwal-siswa');
        Route::get('/jadwal-siswa/{kelas_id}', [JadwalPelajaranController::class, 'jadwalSiswaKelas'])->name('jadwal-siswa.kelas');
        //kurikulum
        Route::resource('kurikulum', KurikulumController::class);
        Route::resource('struktur-kurikulum', StrukturKurikulumController::class);
        Route::get('kurikulum/{id}/aktifkan', [KurikulumController::class, 'aktifkan'])->name('kurikulum.aktifkan');
        //pengamppu mata pelajaran
        Route::put('/pengampu-mapel/{id}', [PengampuMapelController::class, 'update'])->name('pengampu-mapel.update');
        Route::post('/pengampu-mapel/salin', [PengampuMapelController::class, 'salin'])->name('pengampu-mapel.salin');
        Route::resource('pengampu-mapel', PengampuMapelController::class);
        // Mata Pelajaran
        Route::get('/mata-pelajaran/export', [MataPelajaranController::class, 'export'])->name('mata-pelajaran.export');
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        Route::post('/mata-pelajaran/import', [MataPelajaranController::class, 'import'])->name('mata-pelajaran.import');
        Route::delete('mata-pelajaran/bulk-delete',    [MataPelajaranController::class, 'bulkDelete'])->name('mata-pelajaran.bulk-delete');

        //Guru Mata pelajaran
        Route::resource('master-guru', MasterGuruController::class);
        //Absensi
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

        // Absensi Import dari Foto (before {id} to avoid route conflict)
        Route::get('/absensi/import', [AbsensiImportController::class, 'showForm'])->name('absensi.import');
        Route::post('/absensi/import/process', [AbsensiImportController::class, 'processImage'])->name('absensi.import.process');
        Route::post('/absensi/import/confirm', [AbsensiImportController::class, 'confirmImport'])->name('absensi.import.confirm');

        // Diagnostic route — visit /absensi/import/diagnostic to check server capabilities
        Route::get('/absensi/import/diagnostic', function () {
            $results = [];
            $results['php_version'] = phpversion();
            $results['exec_enabled'] = function_exists('exec');
            $results['python_path'] = config('ocr.python_path');
            $results['python_exists'] = $results['python_path'] ? file_exists($results['python_path']) : false;
            $results['tesseract_path'] = config('ocr.tesseract_path');
            $results['tesseract_exists'] = $results['tesseract_path'] ? file_exists($results['tesseract_path']) : false;
            $results['script_path'] = config('ocr.script_path');
            $results['script_exists'] = $results['script_path'] ? file_exists($results['script_path']) : false;
            $results['upload_max_filesize'] = ini_get('upload_max_filesize');
            $results['post_max_size'] = ini_get('post_max_size');
            $results['upload_dir'] = config('ocr.upload_dir');
            $results['upload_disk'] = config('ocr.upload_disk');
            $disk = Storage::disk(config('ocr.upload_disk', 'local'));
            $results['disk_root'] = $disk->path('/');
            $uploadDir = config('ocr.upload_dir', 'absensi-import');
            $fullPath = rtrim($disk->path('/'), '/') . '/' . $uploadDir;
            $results['upload_dir_exists'] = is_dir($fullPath);
            $results['upload_dir_writable'] = is_writable($fullPath);
            $results['extensions_loaded'] = get_loaded_extensions();
            $results['opencv_support'] = in_array('imagick', get_loaded_extensions()) || class_exists('GdImage');

            if ($results['exec_enabled'] && $results['python_exists']) {
                $output = [];
                $exitCode = 0;
                exec('"' . $results['python_path'] . '" --version 2>&1', $output, $exitCode);
                $results['python_version'] = implode("\n", $output);
                $results['python_exit_code'] = $exitCode;
            }

            if ($results['tesseract_exists']) {
                $output = [];
                $exitCode = 0;
                exec('"' . $results['tesseract_path'] . '" --version 2>&1', $output, $exitCode);
                $results['tesseract_version'] = implode("\n", $output);
                $results['tesseract_exit_code'] = $exitCode;
            }

            header('Content-Type: application/json');
            return response()->json($results, 200, [], JSON_PRETTY_PRINT);
        });

        Route::get('/absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('/absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::get('/absensi/{id}', [AbsensiController::class, 'detail'])->name('absensi.detail');
        Route::get('/absensi-riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
        Route::get('/absensi-riwayat/pdf', [AbsensiController::class, 'riwayatPdf'])->name('absensi.riwayat.pdf');
        Route::get('/absensi-rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::get('/absensi-rekap/pdf', [AbsensiController::class, 'rekapPdf'])->name('absensi.rekap.pdf');

        // Absensi Guru (Admin)
        Route::get('/admin/absensi-guru', [AdminAbsensiGuruController::class, 'index'])->name('admin.absensi-guru.index');
        Route::get('/admin/absensi-guru/{id}', [AdminAbsensiGuruController::class, 'detail'])->name('admin.absensi-guru.detail');
        //Tahun Ajaran
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::put('/tahun-ajaran/{id}/aktifkan', [TahunAjaranController::class, 'aktifkan'])->name('tahun-ajaran.aktifkan');
        // =======================
        // Semester
        // =======================
        Route::prefix('semester')->name('semester.')->controller(SemesterController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::put('/{id}/ganti', 'gantiSemester')->name('ganti');
        });
        // Arsip Tahun Ajaran
        Route::get('/arsip-tahun-ajaran', [TahunAjaranController::class, 'arsip'])->name('arsip-tahun-ajaran');
        Route::get('/arsip-tahun-ajaran/{id}', [TahunAjaranController::class, 'detailArsip'])->name('arsip-tahun-ajaran.detail');
        //penilaians
        Route::resource('penilaian',  PenilaianController::class);
        Route::get('/penilaian/{id}/pdf', [PenilaianController::class, 'pdf'])->name('penilaian.pdf');
        Route::get('/penilaian/{id}/rekap', [PenilaianController::class, 'rekap'])->name('penilaian.rekap');
        Route::get('/penilaian/{id}/detail',  [PenilaianController::class, 'detail'])->name('penilaian.detail');
        Route::get('/penilaian-riwayat', [PenilaianController::class, 'riwayatAll'])->name('penilaian.riwayat.all');
        Route::get('/penilaian-hasil', [PenilaianController::class, 'hasil'])->name('penilaian.hasil');
        Route::get('/penilaian-hasil/{id}', [PenilaianController::class, 'detail'])->name('penilaian.detail');
        Route::get('/penilaian/{id}/edit',  [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::put('/penilaian/{id}', [PenilaianController::class, 'update'])->name('penilaian.update');
        //Kenaikan Kelas
        Route::post('/admin/kenaikan-kelas', [AdminController::class, 'kenaikanKelas']);
        Route::post('/admin/perpindahan-semester', [AdminController::class, 'perpindahanSemester']);
        //Alumni
        Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
        Route::get('/alumni/pdf', [AlumniController::class, 'exportPdf'])->name('alumni.pdf');
        Route::get('/alumni/pdf', [AlumniController::class, 'exportPdf'])->name('alumni.pdf');
        // Tambah Poin
        Route::get('/pelanggaran/tambah/{siswa:nisn}', [PoinController::class, 'tambah_view']);
        Route::get('/pelanggaran/kurang/{siswa:nisn}', [PoinController::class, 'kurang_view']);
        Route::put('/pelanggaran/{id}', [PoinController::class, 'tambah_poin']);
        Route::put('/pelanggaran/kurang/{id}', [PoinController::class, 'kurang_poin']);
        Route::delete('/pelanggaran/{id}', [PoinController::class, 'destroy'])->name('poin.destroy');


        // Histori
        Route::get('/master-histori', [AdminController::class, 'histori_index']);
        Route::get('/master-histori/{id}', [AdminController::class, 'histori_admin']);
        //API WA
        Route::post('/kirim-notifikasi/{id}', [PoinController::class, 'kirimNotifikasi'])->name('history.kirimNotif');


        // Haflah / Lomba
        Route::get('/haflatul-imtihan/aktifkan/{id}', [HaflatulImtihanController::class, 'aktifkan'])->name('haflah.aktifkan');
        Route::resource('haflatul-imtihan', HaflatulImtihanController::class);
        Route::resource('sesi-lomba', SesiLombaController::class);
        Route::resource('kategori-lomba', KategoriLombaController::class);
        Route::resource('lomba', LombaController::class);
        Route::get('/peserta-lomba/cetak-pdf', [PesertaLombaController::class, 'cetakPdf'])->name('peserta-lomba.cetak-pdf');
        Route::match(['get', 'post'], '/peserta-lomba/tambah-massal', [PesertaLombaController::class, 'massal'])->name('peserta-lomba.massal');
        Route::resource('peserta-lomba', PesertaLombaController::class);
        Route::get('/get-kelas/{jenjang}', [PesertaLombaController::class, 'getKelas']);
        Route::get('/get-siswa/{kelas}', [PesertaLombaController::class, 'getSiswa']);
        Route::get('/get-kelompok/{lomba}', [PesertaLombaController::class, 'getKelompok'])->name('peserta-lomba.get-kelompok');
        Route::get('/kelompok-lomba/cetak-pdf', [KelompokLombaController::class, 'cetakPdf'])->name('kelompok-lomba.cetak-pdf');
        Route::get('/kelompok-lomba/print-preview', [KelompokLombaController::class, 'printPreview'])->name('kelompok-lomba.print-preview');
        Route::resource('kelompok-lomba', KelompokLombaController::class);
        Route::resource('anggota-kelompok', AnggotaKelompokController::class);
        Route::get('/anggota-kelompok/get-siswa/{kelompokLomba}', [AnggotaKelompokController::class, 'getStudentsByKelompok'])->name('anggota-kelompok.get-siswa');
        Route::delete('/anggota-kelompok/hapus-semua/{kelompokLomba}', [AnggotaKelompokController::class, 'hapusSemua'])->name('anggota-kelompok.hapus-semua');
        Route::resource('juri-lomba', JuriLombaController::class);
        Route::get('/aspek-penilaian/export-excel/{lomba}', [AspekPenilaianController::class, 'exportForm'])->name('aspek-penilaian.export-excel');
        Route::get('/aspek-penilaian/cetak-pdf/{lomba}', [AspekPenilaianController::class, 'cetakPdf'])->name('aspek-penilaian.cetak-pdf');
        Route::delete('/aspek-penilaian/hapus-semua/{lomba}', [AspekPenilaianController::class, 'destroyByLomba'])->name('aspek-penilaian.hapus-semua');
        Route::resource('aspek-penilaian', AspekPenilaianController::class);
        Route::get('/penilaian-lomba/get-lomba/{sesi}', [PenilaianLombaController::class, 'getLomba'])->name('penilaian-lomba.get-lomba');
        Route::get('/penilaian-lomba/get-peserta/{lomba}', [PenilaianLombaController::class, 'getPeserta'])->name('penilaian-lomba.get-peserta');
        Route::get('/penilaian-lomba/get-juri/{lomba}', [PenilaianLombaController::class, 'getJuri'])->name('penilaian-lomba.get-juri');
        Route::get('/penilaian-lomba/get-aspek/{lomba}', [PenilaianLombaController::class, 'getAspek'])->name('penilaian-lomba.get-aspek');
        Route::get('/penilaian-lomba/get-kelompok/{lomba}', [PenilaianLombaController::class, 'getKelompok'])->name('penilaian-lomba.get-kelompok');
        Route::delete('/penilaian-lomba/hapus-semua/{pesertaLomba}', [PenilaianLombaController::class, 'destroyByPeserta'])->name('penilaian-lomba.hapus-semua');
        Route::resource('penilaian-lomba', PenilaianLombaController::class);
        Route::get('/hasil-lomba/get-peserta/{lomba}', [HasilLombaController::class, 'getPesertaWithTotal'])->name('hasil-lomba.get-peserta');
        Route::resource('hasil-lomba', HasilLombaController::class);
        Route::resource('sesi', SesiController::class);

        // Penanganan
        Route::get('/penanganan', [PenangananController::class, 'index']);
        Route::post('/penanganan/{id}', [PenangananController::class, 'konfirmasi']);

        // Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Wali Kelas
        Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas.index');
        Route::post('/wali-kelas/store', [WaliKelasController::class, 'store'])->name('wali-kelas.store');
        Route::delete('/wali-kelas/{id}', [WaliKelasController::class, 'destroy'])->name('wali-kelas.destroy');

        // Jenis Peraturan
        Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
        Route::get('/peraturan/tambah', [PeraturanController::class, 'create']);
        Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
        Route::post('/peraturan', [PeraturanController::class, 'store']);
        Route::get('/peraturan/{id}/edit', [PeraturanController::class, 'edit']);
        Route::put('/peraturan/{id}', [PeraturanController::class, 'update']);
        Route::delete('/peraturan/{id}', [PeraturanController::class, 'destroy']);


        Route::resource('tindak-lanjut', TindakLanjutController::class);

        //rekap History
        Route::get('/laporan/rekap-periode', [LaporanController::class, 'rekapPeriode'])->name('laporan.rekap-periode');
        Route::get('/laporan/rekap-periode/export', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');

        // Profil Madrasah
        Route::get('/profil-madrasah', [ProfilMadrasahController::class, 'index'])->name('profil-madrasah.index');
        Route::post('/profil-madrasah', [ProfilMadrasahController::class, 'update'])->name('profil-madrasah.update');

        // Lokasi Madrasah
        Route::get('/lokasi-madrasah', [LokasiMadrasahController::class, 'index'])->name('lokasi-madrasah.index');
        Route::post('/lokasi-madrasah', [LokasiMadrasahController::class, 'store'])->name('lokasi-madrasah.store');
        Route::put('/lokasi-madrasah', [LokasiMadrasahController::class, 'update'])->name('lokasi-madrasah.update');
        Route::post('/lokasi-madrasah/toggle', [LokasiMadrasahController::class, 'toggleAktif'])->name('lokasi-madrasah.toggle');

        // Pengumuman
        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
        Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
        Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
        Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

        // Galery
        Route::get('/galery', [GaleryController::class, 'index'])->name('galery.index');
        Route::post('/galery', [GaleryController::class, 'store'])->name('galery.store');
        Route::put('/galery/{id}', [GaleryController::class, 'update'])->name('galery.update');
        Route::delete('/galery/{id}', [GaleryController::class, 'destroy'])->name('galery.destroy');
    });

    // //
    // Route::group(['middleware' => ['role:4']], function () {

    //     // Tambah Poin
    //     Route::get('/bk/daftar-siswa', [GuruBkController::class, 'daftar_siswa']);
    //     Route::get('/bk/pelanggaran/tambah/{siswa:nisn}', [GuruBkController::class, 'tambah_view']);
    //     Route::get('/bk/pelanggaran/kurang/{siswa:nisn}', [GuruBkController::class, 'kurang_view']);
    //     Route::put('/bk/pelanggaran/{id}', [GuruBkController::class, 'tambah_poin']);
    //     Route::put('/bk/pelanggaran/kurang/{id}', [GuruBkController::class, 'kurang_poin']);

    //     // Penanganan
    //     Route::get('/bk/penanganan', [GuruBkController::class, 'index']);
    //     Route::post('/bk/penanganan/{id}', [GuruBkController::class, 'konfirmasi']);
    // });
});
