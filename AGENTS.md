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
- Roles are ints: `1=admin`, `2=guru`, `3=siswa`, `4=BK`. **BK (4) routes in `routes/web.php:366-379` are fully commented out** — but `layouts/main.blade.php` still renders sidebar/navbar for role 4.
- `admin` middleware alias exists in Kernel but is unused — use `role` middleware instead.
- `laravel/sanctum` installed, but its middleware is commented out in the `api` group (`Kernel.php:45`).
- `2fa.disable` not allowlisted by `require.2fa`. Allowlist also includes `login-history.index`, `active-sessions.index` (plus path prefixes `riwayat-login`, `perangkat`) so users with role-required 2FA can access those before setup.
- `RouteServiceProvider::$namespace` commented out — all routes use `[Controller::class, 'method']` syntax.
- `HaflahMiddleware` runs on every authenticated web request: flips Haflah status by date, seeds `session('haflah_id')`, shares `semuaHaflah`/`haflahAktif` (both eager-loaded with `tahunAjaran`) to all views.
- Route order matters for Jadwal Siswa: `/jadwal-siswa/cetak/{jenjang_id?}` must stay before `/jadwal-siswa/{kelas_id}`.
- Public NISN lookup at `GET /api/{nisn}` (in `routes/web.php`, not `api.php`).
- `Paginator::useBootstrap()` in `AppServiceProvider::boot()`.
- `Semester` model observed by `SemesterObserver` (registered in `AppServiceProvider::boot()`).
- Login auto-detects field: `FILTER_VALIDATE_EMAIL` → `email` column, otherwise `username` (`LoginController`).
- Duplicate route definitions: `jadwal-pelajaran/kelas/{id}` (lines 196 & 200), `alumni/pdf` (lines 266-267). Check both before touching.

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
- `History` model: duplicate `siswa()`/`student()` (both → `Student` via `student_id`), `rule()`/`pelanggaran()` (both → `Peraturan` via `peraturan_id`). Also has `$with = ['siswa', 'rule', 'kelasSnapshot']` (auto-eager-loads).

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
- Homepage hides `.navbar`/`.mobile-nav-toggle` on `≤991px`; theme toggle duplicated outside navbar with `d-lg-none`.
- Hero font-size overrides on homepage need `!important`.

## Admin Page Pattern
Every admin table page:
1. `@extends('layouts.main')` + `@push('css')` with `@include('component.admin.ms-style')` + inline `<style>`.
2. Header card with icon, title, badge, action button(s).
3. DataTable with `scrollX: true`, `responsive: false`, custom toolbar (filter dropdowns + search pill), `columnDefs` to disable ordering on Aksi column.
4. `@push('scripts')` with DataTables init, custom search/filter wiring, modal re-open logic for validation failures.
5. Action buttons: `.action-group-ms` with `btn-outline-warning` (edit) and `btn-outline-danger` (delete).
6. Edit/delete modals inside the `@foreach` loop (unique IDs per row); "add" modal outside the table card.
