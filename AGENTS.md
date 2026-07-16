# MIS Nurul Ulum

## Stack
- Laravel 8.75, PHP `^7.3|^8.0`, MySQL, Laravel Mix (no Vite).
- Auth scaffolding: `laravel/ui` (not Fortify/Breeze/Jetstream).
- Timezone `Asia/Jakarta`, locale `id` (Indonesian), Faker `id_ID`.
- No CI workflows in repo.
- `.editorconfig`: 4-space indent, LF, UTF-8. StyleCI uses Laravel preset v8; `no_unused_imports` is disabled.
- Global helpers: `format_uang()`, `terbilang()`, `tanggal_indonesia()`, `tambah_nol_didepan()`, `angka_romawi()` — loaded via composer autoload files (`app/Http/Helpers/helpers.php`).
- Config aliases: `Helper` (static class duplicate of global helpers except `angka_romawi`), `Alert` (SweetAlert), `PDF` (DomPDF).

## Commands
- First setup: `cp .env.example .env && php artisan key:generate`.
- Frontend: `npm run dev` / `watch` / `hot` / `prod` (Laravel Mix 6, compiles `resources/js/app.js` + `resources/sass/app.scss`).
- Tests: `vendor/bin/phpunit` (uses `.env` MySQL, not sqlite; suite is placeholder-only — `ExampleTest` only).
- Before retesting route/view changes: `php artisan route:clear && php artisan view:clear`.
- `php artisan storage:link` is required before avatar uploads.
- `php artisan security:prune` removes `login_histories` >90d and stale `device_fingerprints` >1y; `--dry` previews. Scheduled daily at 03:00 via `Console\Kernel`.
- `composer update` triggers `@php artisan vendor:publish --tag=laravel-assets --ansi --force`.

## Runtime / Env
- Defaults in `.env`: `DB_DATABASE=buku_penghubung`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=sync`.
- `FONNTE_API_KEY` is required for WhatsApp notifications (`WhatsAppHelper::kirim()`) — **not** present in `.env.example`; must be added manually.
- `RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET_KEY` are required (used in login/register forms); `.env.example` contains test values.
- `phpunit.xml` forces `APP_ENV=testing`, `BCRYPT_ROUNDS=4`, `CACHE_DRIVER=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array`, `TELESCOPE_ENABLED=false`. SQLite lines are commented out — tests use MySQL from `.env`.
- `WhatsAppHelper` lives in `app/Helpers/WhatsAppHelper.php` and is loaded via `autoload-dev.files` — it is **not** in production autoload. If it fails at runtime, check this.

## Routing / Middleware
- Web group order matters: `auth` -> `2fa` -> `require.2fa` (route-level, defined at `routes/web.php:96`).
- `2fa/challenge` and `2fa/verify` are defined **outside** the auth group (for unauthenticated access).
- Login (`POST /login`) and 2FA verify are throttled (`throttle:5,1`).
- `datasiswa` is applied per-controller, currently only `HomeController`. It forces siswa (role 3) with `info=false` to a data-completion form; logs out guru without a `waliKelas` assignment; logs out any non-siswa user with `info=false`.
- Roles are ints: `1=admin`, `2=guru`, `3=siswa`, `4=BK`. **BK (4) routes in `routes/web.php:365-377` are fully commented out.**
- `admin` middleware alias exists in Kernel but is unused — roles use the `role` middleware instead.
- BK (4) routes are commented out, but `layouts/main.blade.php` includes sidebar/navbar rendering for role 4 — the view layer is wired for BK even though the routing is not.
- `laravel/sanctum` is installed; its middleware is commented out in the `api` middleware group (`Kernel.php:45`).
- `2fa.disable` is intentionally not allowlisted by `require.2fa`.
- `HaflahMiddleware` runs on every **authenticated** web request, flips Haflah status by date, writes DB, seeds `session('haflah_id')`, and shares `semuaHaflah`/`haflahAktif` (both eager-loaded with `tahunAjaran`) to all views.
- Route order matters for Jadwal Siswa: `/jadwal-siswa/cetak/{jenjang_id?}` must stay before `/jadwal-siswa/{kelas_id}`.
- Public NISN lookup lives at `GET /api/{nisn}` (defined in `routes/web.php`, not `api.php`).
- `Paginator::useBootstrap()` is called globally in `AppServiceProvider::boot()`.
- `Semester` model is observed by `SemesterObserver` (registered in `AppServiceProvider::boot()`).
- Login auto-detects field type: `FILTER_VALIDATE_EMAIL` on input → uses `email` column, otherwise uses `username` column (`LoginController::findUsername()`).
- There are duplicate route definitions: `jadwal-pelajaran/kelas/{id}` (lines 195 & 199), `alumni/pdf` (lines 264-265). Avoid touching these without checking both.

## User / Guru Conventions
- `master-guru` is the resource route for `Guru` records.
- Add guru by selecting an existing `users.role = 2` user; `Guru.nama` comes from `users.name`.
- `kode_guru` is auto-generated in `MasterGuruController` as `GR###`.
- `Guru.user_id` links the guru row back to `users.id` (nullable, `onDelete set null`).
- Only `index`, `store`, `update`, and `destroy` are implemented in `MasterGuruController`; avoid using the resource `create/show/edit` URLs unless you add those methods.
- `master-user.store` must populate `username` (current logic copies `email` into `username`); `users.username` is NOT NULL.
- User creation role validation only allows `1,2,3` — role `4` (BK) cannot be created through the admin interface.
- New users get a default password of `password` (bcrypt hash); admins cannot set `info=1` on students — students must self-register.

## Models / Data
- Most models use `protected $guarded = ['id']`; notable `$fillable` exceptions include `Jenjang`, `MataPelajaran`, `Semester`, `TahunAjaran`, `LoginHistory`, `DeviceFingerprint`, `Notifikasi`, `RoleTwoFaRequirement`, `AccountActivity`.
- `Semester` uses the `semesters` table (plural) and `TahunAjaran::saved` auto-creates its semester rows.
- `TahunAjaran::saved` also auto-activates a semester based on current month (>= 7 = Ganjil, else Genap) when status is `Aktif`; when status is not `Aktif`, it deactivates all semesters for that tahun ajaran.
- `TahunAjaran::deleted` deletes associated semesters.
- `SemesterObserver` deactivates siblings in the same tahun ajaran (fires on `saving`).
- `Student::getRouteKeyName()` is `nisn`; `Penanganan::getRouteKeyName()` is `berkas`; `Poin::getRouteKeyName()` is `siswa_id`.
- `User::siswa()` uses `hasOne(Student::class, 'id')` (FK is `users.id`).
- `User` hides `google2fa_secret` and `recovery_codes`; casts `info` as bool, `preferences` as array.
- `RoleTwoFaRequirement` has non-incrementing int PK `role` (not the standard `id`).
- `BelongsToHaflah` trait: auto-fills `haflah_id` from session on create, applies `HaflahScope` global scope. Used by: `AspekPenilaian`, `AnggotaKelompok`, `HasilLomba`, `Lomba`, `KategoriLomba`, `JuriLomba`, `KelompokLomba`, `PenilaianLomba`, `PesertaLomba`, `SesiLomba`, `Sesi`.
- `PenilaianDetail::getNilaiAkhirAttribute()` uses weighted average: tugas 20%, UH 30%, PTS 20%, PAS 30%.
- Export/Import classes use Maatwebsite Excel (`app/Exports/`, `app/Imports/`).
- `Guru` model does not define a `belongsTo(User)` relationship despite having `user_id` FK — keep this in mind when building guru-related queries.
- `History` model has duplicate relationships: both `siswa()` and `student()` point to `Student` via `student_id`; both `rule()` and `pelanggaran()` point to `Peraturan`.

## Controller Traits
- `ProtectsCompletedHaflah` (`app/Http/Controllers/Traits/`): use in controllers that manage Haflah/Lomba data. `blockIfHaflahSelesai($haflahId)` and `blockStoreIfHaflahSelesai()` prevent mutations when haflah status is 'Selesai'.

## Views / UI
- `layouts/main.blade.php` is the authenticated shell; it **only renders** for `Auth::user()->info == true` or `request()->is('profil-saya*')`.
- `layouts/app.blade.php` is the **public homepage** (self-contained, not extending `layouts.main`). It loads `component.head`, `component.loading`, `component.footer`, `component.script`.
- `<base href="../../">` makes asset paths relative (authenticated pages only).
- `@stack('css')` is in the layout `<head>` (after base CSS, before dark-mode.css).
- `@stack('scripts')` lives in `resources/views/component/script.blade.php` and loads **before** Bootstrap JS — stack scripts must not depend on Bootstrap globals.
- `@include('sweetalert::alert')` comes after the shared script include.
- Dark mode is CSS-only (`public/css/dark-mode.css`) and must stay last among `<link>` stylesheets in `<head>`.
- Avoid `@php(...)` shorthand in files that already use `@php ... @endphp` blocks.
- Do not combine Blade `paginate()` with DataTables paging on the same table.
- Global view shares from `AppServiceProvider::boot()`: `$profil`, `$pengumuman`, `$galery` (with `Schema::hasTable('galery')` safety check).

## Mobile / Responsive Conventions
- Shared admin styles live in `component/admin/ms-style.blade.php` (included by all admin pages via `@include`).
- `ms-style.blade.php` `@media (max-width: 480px)` turns `.action-group-ms` into a **3-column grid** — this breaks pages with only 2 action buttons. Every admin table page must override this in its own inline `<style>` block with `@media (max-width: 575.98px)`:
  ```css
  .action-group-ms { display: inline-flex !important; gap: 4px !important; grid-template-columns: unset !important; }
  .action-group-ms .btn { width: 28px !important; height: 28px !important; font-size: 11px !important; }
  ```
- All admin DataTables pages use `scrollX: true` + `responsive: false` (never `table-responsive` wrapper) so only `<tbody>` scrolls horizontally while search/entries/pagination stay fixed.
- The homepage (`layouts/app.blade.php`) hides `.navbar` and `.mobile-nav-toggle` on `≤991px`, showing only logo + theme toggle + login button. The theme toggle is duplicated outside the navbar with `d-lg-none` for mobile visibility.
- Login page (`auth/login.blade.php`) hides `.login-left` (hero image) on `≤768px` — form only.
- Homepage preloader (`#preloader`) is overridden to green (`#16a34a`) in `layouts/app.blade.php` — the base `style.css` uses orange (`#fb6340`).
- Font-size overrides on homepage hero elements require `!important` to override `style.css` and CDN-loaded Bootstrap.

## Admin Page Pattern
Every admin table page follows this pattern:
1. `@extends('layouts.main')` + `@push('css')` with `@include('component.admin.ms-style')` + inline `<style>` for page-specific overrides.
2. Header card with icon, title, badge, and action button(s).
3. DataTable with `scrollX: true`, `responsive: false`, custom toolbar (filter dropdowns + search pill), and `columnDefs` to disable ordering on the Aksi column.
4. `@push('scripts')` with DataTables init, custom search/filter wiring, and modal re-open logic for validation failures.
5. Action buttons use `.action-group-ms` with `btn-outline-warning` (edit) and `btn-outline-danger` (delete).
6. Edit/delete modals are placed **inside** the `@foreach` loop (unique IDs per row); the "add" modal is outside the table card.
