<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
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
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\LaporanController;
use App\Models\Poin;

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
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Input More Info
Route::post('/siswa/store', [StudentController::class, 'store'])->name('siswa');

//new
Route::get('/lengkapi-data', [StudentController::class, 'create'])->name('lengkapi-data');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

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
    });

    // Admin
    Route::group(['middleware' => ['role:1']], function () {

        // Daftar user
        Route::get('/master-user/create', [UserAdminController::class, 'create'])->name('user.create');
        Route::post('/master-user/store', [UserAdminController::class, 'store'])->name('user.store');
        Route::get('/master-siswa', [AdminController::class, 'daftar_siswa']);        
        Route::get('/master-user', [UserAdminController::class, 'daftar_user']);
        Route::get('/master-user/{id}/edit', [UserAdminController::class, 'edit']);
        Route::put('/master-user/{id}', [UserAdminController::class, 'update']);
        Route::put('/change-pass/{id}', [UserAdminController::class, 'update_pass']);
        Route::post('/master-user/{id}', [UserAdminController::class, 'destroy']);
        Route::get('/master-guru', [UserAdminController::class, 'daftar_guru']);
        Route::post('/master-guru/store', [UserAdminController::class, 'tambah_guru']);
        Route::post('/master-guru/{id}', [UserAdminController::class, 'hapus_guru']);
        Route::put('/master-siswa/{id}', [AdminController::class, 'update_siswa'])->name('siswa.update');


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
        

   


        // Penanganan
        Route::get('/penanganan', [PenangananController::class, 'index']);
        Route::post('/penanganan/{id}', [PenangananController::class, 'konfirmasi']);

        // Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Jenis Peraturan
        Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
        Route::get('/peraturan/tambah', [PeraturanController::class, 'create']);
        Route::post('/peraturan', [PeraturanController::class, 'store']);
        Route::get('/peraturan/{id}/edit', [PeraturanController::class, 'edit']);
        Route::put('/peraturan/{id}', [PeraturanController::class, 'update']);
        Route::delete('/peraturan/{id}', [PeraturanController::class, 'destroy']);

        Route::resource('tindak-lanjut', TindakLanjutController::class);

        //rekap History
        Route::get('/laporan/rekap-periode', [LaporanController::class, 'rekapPeriode'])->name('laporan.rekap-periode');
        Route::get('/laporan/rekap-periode/export', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
        

    });

    // Bk
    Route::group(['middleware' => ['role:4']], function () {

        // Tambah Poin
        Route::get('/bk/daftar-siswa', [GuruBkController::class, 'daftar_siswa']);
        Route::get('/bk/pelanggaran/tambah/{siswa:nisn}', [GuruBkController::class, 'tambah_view']);
        Route::get('/bk/pelanggaran/kurang/{siswa:nisn}', [GuruBkController::class, 'kurang_view']);
        Route::put('/bk/pelanggaran/{id}', [GuruBkController::class, 'tambah_poin']);
        Route::put('/bk/pelanggaran/kurang/{id}', [GuruBkController::class, 'kurang_poin']);

        // Penanganan
        Route::get('/bk/penanganan', [GuruBkController::class, 'index']);
        Route::post('/bk/penanganan/{id}', [GuruBkController::class, 'konfirmasi']);
    });
});