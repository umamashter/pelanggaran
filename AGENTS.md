# MIS Nurul Ulum

School Management Information System (Sistem Informasi Akademik) for an Indonesian Islamic elementary school.

README.md is the default Laravel template — contains no project-specific info.

## Quick Start

```bash
cp .env.example .env && php artisan key:generate
# Add FONNTE_API_KEY to .env (required for WhatsApp notifications, not in .env.example)
php artisan migrate --seed
php artisan storage:link
npm install && npm run dev
php artisan serve
```

## Stack
- Laravel 8.75, PHP `^7.3|^8.0`, MySQL, Laravel Mix (no Vite).
- Auth scaffolding: `laravel/ui` (not Fortify/Breeze/Jetstream).
- Timezone `Asia/Jakarta`, locale `id` (Indonesian), Faker `id_ID`.
- No CI workflows. Only StyleCI (Laravel preset v8; `no_unused_imports` disabled).
- Global helpers: `format_uang()`, `terbilang()`, `tanggal_indonesia()`, `tambah_nol_didepan()`, `angka_romawi()` — loaded via `app/Http/Helpers/helpers.php` (composer autoload files).
- Config aliases: `Helper` (static class, same as global helpers except no `angka_romawi`), `Alert` (SweetAlert), `PDF` (DomPDF).

## Commands
- Frontend: `npm run dev` / `watch` / `hot` / `prod` (Laravel Mix 6, compiles `resources/js/app.js` + `resources/sass/app.scss`).
- Tests: `vendor/bin/phpunit` (uses `.env` MySQL, not sqlite; only `ExampleTest` exists).
- Before retesting route/view changes: `php artisan route:clear && php artisan view:clear`.
- `php artisan storage:link` required before avatar uploads.
- `php artisan security:prune` prunes `login_histories` >90d and stale `device_fingerprints` >1y; `--dry` previews. Scheduled daily at 03:00 (Kernel.php:19), output to `storage/logs/prune-security.log`.
- `composer update` triggers `vendor:publish --tag=laravel-assets`.

## Env
- Defaults in `.env.example`: `DB_DATABASE=buku_penghubung`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=sync`.
- `FONNTE_API_KEY` required for WhatsApp (`WhatsAppHelper::kirim()`) — **not** in `.env.example`; must be added manually.
- `RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET_KEY` required (login/register forms); `.env.example` has test values.
- `phpunit.xml` forces `APP_ENV=testing`, `BCRYPT_ROUNDS=4`, `CACHE_DRIVER=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array`, `TELESCOPE_ENABLED=false`. SQLite lines commented out — tests use MySQL from `.env`.
- `WhatsAppHelper` loaded via `autoload-dev.files` — **not** in production autoload. If it fails at runtime, check `composer.json`.

## Routing / Middleware
- Web group order: `auth` -> `2fa` -> `require.2fa` -> `datasiswa` (per-controller) -> `role`.
- `2fa/challenge` and `2fa/verify` are **outside** the auth group (unauthenticated access).
- Login (`POST /login`) and 2FA verify throttled (`throttle:5,1`).
- `datasiswa` middleware (currently only on `HomeController`): forces role 3 (`info=false`) to data-completion form; logs out guru without `waliKelas` or with `kelas_id == null`; logs out non-siswa with `info=false`.
- Roles are ints: `1=admin`, `2=guru`, `3=siswa`, `4=BK`. **BK (4) routes in `routes/web.php:384-397` are fully commented out** — but `layouts/main.blade.php` still renders sidebar/navbar for role 4.
- `admin` middleware alias exists in Kernel but is unused — use `role` middleware instead.
- `laravel/sanctum` installed, but its middleware is commented out in the `api` group (`Kernel.php:45`).
- `2fa.disable` not allowlisted by `require.2fa`. Allowlist also includes `login-history.index`, `active-sessions.index` (plus path prefixes `riwayat-login`, `perangkat`) so users with role-required 2FA can access those before setup.
- `RouteServiceProvider::$namespace` commented out — all routes use `[Controller::class, 'method']` syntax.
- `HaflahMiddleware` runs on every authenticated web request: flips Haflah status by date, seeds `session('haflah_id')`, shares `semuaHaflah`/`haflahAktif` (both eager-loaded with `tahunAjaran`) to all views.
- Route order matters for Jadwal Siswa: `/jadwal-siswa/cetak/{jenjang_id?}` must stay before `/jadwal-siswa/{kelas_id}`.
- Public NISN lookup at `GET /api/{nisn}` (in `routes/web.php`, not `api.php`).
- `Paginator::useBootstrap()` in `AppServiceProvider::boot()`.
- `Semester` model observed by `SemesterObserver` (registered in `AppServiceProvider::boot()`).
- Login auto-detects field: `FILTER_VALIDATE_EMAIL` -> `email` column, otherwise `username` (`LoginController`).
- Duplicate route definitions: `master-user` (lines 188 & 196), `jadwal-pelajaran/kelas/{id}` (lines 204 & 208), `alumni/pdf` (lines 278 & 279). Check both before touching.

## User / Guru
- `master-guru` is the resource route for `Guru` records.
- Add guru by selecting an existing `users.role = 2` user; `Guru.nama` comes from `users.name`.
- `kode_guru` auto-generated as `GR###` in `MasterGuruController`.
- `Guru.user_id` links to `users.id` (nullable, `onDelete set null`).
- Only `index`, `store`, `update`, `destroy` implemented in `MasterGuruController` — no `create/show/edit`.
- `master-user.store` must populate `username` (current logic copies `email` into `username`); `users.username` is NOT NULL.
- User creation role validation only allows `1,2,3` — role 4 (BK) cannot be created through admin.
- New users get default password `password` (bcrypt); admins cannot set `info=1` on students (must self-register).

## Models / Data
- Most models use `$guarded = ['id']`; notable `$fillable` exceptions: `Jenjang`, `MataPelajaran`, `Semester`, `TahunAjaran`, `LoginHistory`, `DeviceFingerprint`, `Notifikasi`, `RoleTwoFaRequirement`, `AccountActivity`.
- `Semester` uses the `semesters` table. `TahunAjaran::saved` auto-creates Ganjil/Genap semester rows.
- `TahunAjaran::saved` auto-activates a semester by month (>=7 = Ganjil, else Genap) when status is `Aktif`; deactivates all when not `Aktif`. Fires only on creation or status change (`wasRecentlyCreated` / `wasChanged('status')`).
- `TahunAjaran::deleted` deletes associated semesters. `SemesterObserver` deactivates siblings in same tahun ajaran on `saving`.
- `Student::getRouteKeyName()` is `nisn`; `Penanganan::getRouteKeyName()` is `berkas`; `Poin::getRouteKeyName()` is `siswa_id`.
- `User::siswa()` uses `hasOne(Student::class, 'id')` (FK is `users.id`).
- `User` hides `google2fa_secret`, `recovery_codes`; casts `info` as bool, `preferences` as array.
- `RoleTwoFaRequirement` has non-incrementing int PK `role` (not `id`).
- `BelongsToHaflah` trait: auto-fills `haflah_id` from session on create, applies `HaflahScope` global scope. Used by: `AspekPenilaian`, `AnggotaKelompok`, `HasilLomba`, `Lomba`, `KategoriLomba`, `JuriLomba`, `KelompokLomba`, `PenilaianLomba`, `PesertaLomba`, `SesiLomba`, `Sesi`.
- `PenilaianDetail::getNilaiAkhirAttribute()` weighted average: tugas 20%, UH 30%, PTS 20%, PAS 30%.
- Export/Import via Maatwebsite Excel (`app/Exports/`, `app/Imports/`).
- `Guru` model has **no** `belongsTo(User)` relationship despite having `user_id` — build guru queries manually.
- `History` model: duplicate `siswa()`/`student()` (both -> `Student` via `student_id`), `rule()`/`pelanggaran()` (both -> `Peraturan` via `peraturan_id`). Also has `$with = ['siswa', 'rule', 'kelasSnapshot']` (auto-eager-loads).

## Controller Traits
- `ProtectsCompletedHaflah` (`app/Http/Controllers/Traits/`): `blockIfHaflahSelesai($haflahId)` and `blockStoreIfHaflahSelesai()` prevent mutations when haflah status is `Selesai`.

## Views / UI
- `layouts/main.blade.php` is the authenticated shell; only renders for `Auth::user()->info == true` or `request()->is('profil-saya*')`.
- `layouts/app.blade.php` is the public homepage (self-contained). Loads `component.head`, `component.loading`, `component.footer`, `component.script`.
- `<base href="../../">` makes asset paths relative (authenticated pages only).
- `@stack('css')` in layout `<head>` (after base CSS, before dark-mode.css).
- `@stack('scripts')` in `resources/views/component/script.blade.php` — loads **before** jQuery Validation, SweetAlert, DataTables, Select2, Bootstrap JS. Stack scripts must not depend on those globals.
- `@include('sweetalert::alert')` comes after the shared script include.
- Dark mode is CSS-only (`public/css/dark-mode.css`) — place near end of `<head>`, just before `loading.css`.
- Avoid `@php(...)` shorthand in files that already use `@php ... @endphp` blocks.
- Do not combine Blade `paginate()` with DataTables paging on the same table.
- Global view shares from `AppServiceProvider::boot()`: `$profil`, `$pengumuman`, `$galery` (with `Schema::hasTable('galery')` safety check).

## Mobile / Responsive
- Shared admin styles in `component/admin/ms-style.blade.php` (included via `@include`).
- `ms-style.blade.php` `@media (max-width: 480px)` sets `.action-group-ms` to a 3-column grid — breaks pages with only 2 buttons. Every admin table page must override in its own inline `<style>`:
  ```css
  @media (max-width: 575.98px) {
    .action-group-ms { display: inline-flex !important; gap: 4px !important; grid-template-columns: unset !important; }
    .action-group-ms .btn { width: 28px !important; height: 28px !important; font-size: 11px !important; }
  }
  ```
- All admin DataTables: `scrollX: true` + `responsive: false` (never `table-responsive` wrapper) so only `<tbody>` scrolls.
- Homepage hides `.navbar`/`.mobile-nav-toggle` on <=991px; theme toggle duplicated outside navbar with `d-lg-none`.
- Hero font-size overrides on homepage need `!important`.

## Absensi Siswa (Stable)
- **Arsitektur**: Tahun Ajaran Aktif -> Kelas -> Tanggal -> Siswa Aktif -> Status (H/I/S/A) -> Rekap.
- **No `semester_id`** in attendance — scoping solely by `TahunAjaran` where `status='Aktif'`.
- `Absensi` model: `$guarded=['id']`, `$casts=['tanggal'=>'date']`. Relationships: `kelas()`, `tahunAjaran()`, `user()`, `details()`, `jadwal()`.
- `AbsensiDetail` model: `$guarded=['id']`. Relationships: `absensi()`, `student()`.
- DB schema (`absensis`): `jadwal_pelajaran_id` (nullable, legacy FK), `kelas_id`, `tahun_ajaran_id`, `user_id`, `tanggal`. Unique constraint: `(kelas_id, tahun_ajaran_id, tanggal)`.
- DB schema (`absensi_details`): `absensi_id`, `student_id`, `status` (enum H/I/S/A), `keterangan`. Unique constraint: `(absensi_id, student_id)`.
- `jadwal_pelajaran_id` is nullable for backward compatibility — old records have `kelas_id=NULL, tahun_ajaran_id=NULL, user_id=NULL`. Views use null-safe operators (`?->`) to handle these.
- **Route naming**: `absensi.index`, `absensi.create`, `absensi.store`, `absensi.detail`, `absensi.edit`, `absensi.update`, `absensi.riwayat`, `absensi.rekap`, `absensi.rekap.pdf`.
- `absensi.create` is a two-step flow: **Step 1** (no params) shows kelas dropdown + date picker; **Step 2** (with `kelas_id` + `tanggal`) shows student form. Date validated against `tahun_ajaran.tanggal_mulai`/`tanggal_selesai` when present.
- `absensi.index` has `+ Input Absensi` button linking to `absensi.create` (step 1). Per-row "Absen" button for quick today access preserved.
- All routes behind `CekRole:1` (admin only). Store/update wrapped in `DB::beginTransaction`.
- `updateOrCreate` pattern for both header and details — prevents duplicates on re-submit.
- Save/update forms use Bootstrap 5 modal confirmation — counts H/I/S/A from current dropdown values before submitting. Create view adapts modal text for existing absensi (update flow).
- Rekap controller: `kelas_id` validation is conditional (form shown without it, query only when filled). `rekapPdf` requires `kelas_id`.
- Students fetched via `Student::whereHas('kelasAktif')` scoped by `kelas_id` + `tahun_ajaran_id`.
- **Known UX gaps** (not bugs, future iterations): none.

## Absensi Guru (GPS + Selfie)

### Arsitektur
- **Database**: `lokasi_madrasah` (configurable GPS point) + `absensi_gurus` (attendance records)
- **Tabel terpisah** dari Absensi Siswa — tidak ada hubungan dengan `absensis` / `absensi_details`
- **Unique constraint**: `(user_id, tanggal)` — satu guru hanya boleh absen sekali per hari
- **Waktu server** (`Asia/Jakarta`) sebagai waktu resmi absensi — tidak mempercayai waktu perangkat
- **Haversine formula** untuk perhitungan jarak GPS di server-side
- **Foto selfie** disimpan di `storage/absensi-guru/foto/` dengan nama file unik

### Database
- **`lokasi_madrasah`**: `id`, `nama`, `latitude` (decimal 10,7), `longitude` (decimal 10,7), `radius` (unsigned int, default 40), `aktif` (boolean), timestamps
- **`absensi_gurus`**: `id`, `user_id` (FK -> users), `tanggal` (date), `jam_masuk` (time), `foto_masuk` (string), `latitude_masuk` (decimal 10,7), `longitude_masuk` (decimal 10,7), `jarak_masuk` (decimal 8,2), `akurasi_gps` (unsigned int, nullable), timestamps

### Models
- **`LokasiMadrasah`** (`app/Models/LokasiMadrasah.php`): `$table='lokasi_madrasah'`, `$guarded=['id']`, `scopeAktif()`, `getLokasiAktif()`
- **`AbsensiGuru`** (`app/Models/AbsensiGuru.php`): `$table='absensi_gurus'`, `$guarded=['id']`, relationships: `user()` -> User

### Controllers
- **`AdminAbsensiGuruController`** (`app/Http/Controllers/AdminAbsensiGuruController.php`):
  - `index()` — rekap absensi guru dengan filter (user_id, tanggal_mulai, tanggal_selesai)
  - `detail($id)` — detail absensi guru dengan foto, lokasi, jarak
- **`GuruAbsensiGuruController`** (`app/Http/Controllers/GuruAbsensiGuruController.php`):
  - `show()` — halaman absensi dengan GPS + kamera selfie
  - `store()` — validasi GPS (server-side Haversine), simpan foto + data
  - `riwayat()` — riwayat absensi guru
  - `haversine()` — helper function untuk perhitungan jarak

### Routes
- **Guru** (role:2): `GET /guru/absensi-guru` -> `guru.absensi-guru.show`, `POST /guru/absensi-guru` -> `guru.absensi-guru.store`, `GET /guru/absensi-guru/riwayat` -> `guru.absensi-guru.riwayat`
- **Admin** (role:1): `GET /admin/absensi-guru` -> `admin.absensi-guru.index`, `GET /admin/absensi-guru/{id}` -> `admin.absensi-guru.detail`

### Keamanan
- Semua route dilindungi autentikasi + role middleware
- `Auth::id()` sebagai sumber user_id — tidak menerima dari form
- Validasi GPS dilakukan ulang di server (Haversine) — tidak mempercayai nilai jarak dari JavaScript
- Validasi tipe/ukuran foto (image, jpeg/jpg/png, max 5MB)
- Database transaction saat menyimpan absensi + foto
- Jika insert gagal, foto yang sudah di-upload dihapus dari storage

### Guru Flow
1. Guru login -> buka halaman Absensi Guru
2. Browser meminta izin GPS -> sistem mendapatkan latitude/longitude
3. Sistem menghitung jarak via Haversine -> tampilkan status
4. Jika dalam radius -> aktifkan kamera selfie
5. Guru ambil foto -> preview -> klik "Ambil Absensi"
6. Sistem simpan: user_id, tanggal, jam_masuk, foto, lat, lng, jarak

### Admin Flow
1. Admin buka `/admin/absensi-guru` -> lihat rekap semua guru
2. Filter: guru, tanggal mulai, tanggal selesai
3. Klik detail -> lihat foto selfie, koordinat, jarak, jam masuk

### Views
- `resources/views/guru/absensi-guru/show.blade.php` — halaman absensi GPS + kamera
- `resources/views/guru/absensi-guru/riwayat.blade.php` — riwayat absensi guru
- `resources/views/admin/absensi-guru/index.blade.php` — rekap admin dengan DataTables
- `resources/views/admin/absensi-guru/detail.blade.php` — detail absensi guru

### Sidebar
- **Admin**: Absensi Guru di bawah menu Akademik (setelah Absensi Siswa)
- **Guru**: Absensi Guru di sidebar guru (sebelum Penanganan)

## Lokasi Madrasah (GPS Config)

### Arsitektur
- **Database**: `lokasi_madrasah` table — single record (always id=1)
- **Constraint**: Hanya boleh ada **satu** lokasi aktif (`aktif=true` di seluruh tabel)
- **Controller**: `LokasiMadrasahController` (`app/Http/Controllers/LokasiMadrasahController.php`)

### Database
- **`lokasi_madrasah`**: `id`, `nama` (string), `latitude` (decimal 10,7), `longitude` (decimal 10,7), `radius` (unsigned int, default 300 meter), `aktif` (boolean), timestamps

### Models
- **`LokasiMadrasah`** (`app/Models/LokasiMadrasah.php`): `$table='lokasi_madrasah'`, `$guarded=['id']`, `scopeAktif()`, `getLokasiAktif()`

### Controllers
- **`LokasiMadrasahController`** (`app/Http/Controllers/LokasiMadrasahController.php`):
  - `index()` — tampilkan info lokasi aktif (GET `/lokasi-madrasah`)
  - `store()` — buat/update lokasi dengan `updateOrCreate` (POST)
  - `update()` — edit nama/lat/lng/radius (PUT)
  - `toggleAktif()` — toggle status aktif/nonaktif (POST `/lokasi-madrasah/toggle`)

### Routes
- **Admin** (role:1): `GET /lokasi-madrasah` -> `lokasi-madrasah.index`, `POST /lokasi-madrasah` -> `lokasi-madrasah.store`, `PUT /lokasi-madrasah` -> `lokasi-madrasah.update`, `POST /lokasi-madrasah/toggle` -> `lokasi-madrasah.toggle`
- **Note**: Routes are inside the admin `role:1` group in `routes/web.php:365-369` but use `/lokasi-madrasah` prefix (not `/admin/lokasi-madrasah`).

### Views
- `resources/views/admin/lokasi-madrasah/index.blade.php` — admin config page: info card (current location) + edit form + "Gunakan Lokasi Saya" GPS button

### Flow
1. Admin buka `/lokasi-madrasah`
2. Lihat info lokasi aktif: nama, koordinat, radius, status
3. Klik tombol GPS -> browser minta izin -> koordinat terisi otomatis
4. Klik "Simpan Perubahan"
5. Toggle aktif/nonaktif jika perlu (nonaktif = absensi guru tidak berfungsi)

### Keamanan
- Satu lokasi aktif: `updateOrCreate(['id' => 1])` — selalu record yang sama
- Admin harus masuk ke halaman ini manual untuk mengkonfigurasi lokasi
- Guru membaca lokasi aktif dari `LokasiMadrasah::aktif()->first()` — tidak bisa mengubah

## Admin Page Pattern
Every admin table page:
1. `@extends('layouts.main')` + `@push('css')` with `@include('component.admin.ms-style')` + inline `<style>`.
2. Header card with icon, title, badge, action button(s).
3. DataTable with `scrollX: true`, `responsive: false`, custom toolbar (filter dropdowns + search pill), `columnDefs` to disable ordering on Aksi column.
4. `@push('scripts')` with DataTables init, custom search/filter wiring, modal re-open logic for validation failures.
5. Action buttons: `.action-group-ms` with `btn-outline-warning` (edit) and `btn-outline-danger` (delete).
6. Edit/delete modals inside the `@foreach` loop (unique IDs per row); "add" modal outside the table card.
