# MIS Nurul Ulum

## Stack
- Laravel 8.75, PHP `^7.3|^8.0`, MySQL, Laravel Mix (no Vite).
- No CI workflows in repo.
- `.editorconfig`: 4-space indent, LF, UTF-8. StyleCI uses Laravel preset v8; `no_unused_imports` is disabled.

## Commands
- First setup: `cp .env.example .env && php artisan key:generate`.
- Frontend: `npm run dev` / `watch` / `hot` / `prod`.
- Tests: `vendor/bin/phpunit` (uses `.env`, not sqlite; suite is placeholder-only).
- Before retesting route/view changes: `php artisan route:clear && php artisan view:clear`.
- `php artisan storage:link` is required before avatar uploads.
- `php artisan security:prune` removes `login_histories` >90d and stale `device_fingerprints` >1y; `--dry` previews.
- `composer update` triggers `@php artisan vendor:publish --tag=laravel-assets --ansi --force`.

## Runtime / Env
- Defaults in `.env`: `DB_DATABASE=buku_penghubung`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=sync`.
- `FONNTE_API_KEY` is required for WhatsApp notifications (`WhatsAppHelper::kirim()`) — **not** present in `.env.example`; must be added manually.
- `RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET_KEY` are required (used in login/register forms); `.env.example` contains test values.
- `phpunit.xml` forces `APP_ENV=testing`, `BCRYPT_ROUNDS=4`, `CACHE_DRIVER=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array`, `TELESCOPE_ENABLED=false`.
- `WhatsAppHelper` lives in `app/Helpers/WhatsAppHelper.php` and is loaded via `autoload-dev.files` — it is **not** in production autoload. If it fails at runtime, check this.

## Routing / Middleware
- Web group order matters: `auth` -> `2fa` -> `require.2fa` (route-level, defined at `routes/web.php:96`).
- `2fa/challenge` and `2fa/verify` are defined **outside** the auth group (for unauthenticated access).
- Login (`POST /login`) and 2FA verify are throttled (`throttle:5,1`).
- `datasiswa` is applied per-controller, currently only `HomeController`.
- Roles are ints: `1=admin`, `2=guru`, `3=siswa`, `4=BK`. **BK (4) routes in `routes/web.php:365-377` are fully commented out.**
- `admin` middleware alias exists in Kernel but is unused — roles use the `role` middleware instead.
- `2fa.disable` is intentionally not allowlisted by `require.2fa`.
- `HaflahMiddleware` runs on every **authenticated** web request, flips Haflah status by date, writes DB, seeds `session('haflah_id')`, and shares `semuaHaflah`/`haflahAktif` (both eager-loaded with `tahunAjaran`) to all views.
- Route order matters for Jadwal Siswa: `/jadwal-siswa/cetak/{jenjang_id?}` must stay before `/jadwal-siswa/{kelas_id}`.
- Public NISN lookup lives at `GET /api/{nisn}` (defined in `routes/web.php`, not `api.php`).
- `Paginator::useBootstrap()` is called globally in `AppServiceProvider::boot()`.
- `Semester` model is observed by `SemesterObserver` (registered in `AppServiceProvider::boot()`).

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
- `Student::getRouteKeyName()` is `nisn`; `Penanganan::getRouteKeyName()` is `berkas`.
- `User::siswa()` uses `hasOne(Student::class, 'id')` (FK is `users.id`).
- `User` hides `google2fa_secret` and `recovery_codes`; casts `info` as bool, `preferences` as array.
- `BelongsToHaflah` trait: auto-fills `haflah_id` from session on create, applies `HaflahScope` global scope. Used by: `AspekPenilaian`, `AnggotaKelompok`, `HasilLomba`, `Lomba`, `KategoriLomba`, `JuriLomba`, `KelompokLomba`, `PenilaianLomba`, `PesertaLomba`, `SesiLomba`, `Sesi`.
- `PenilaianDetail::getNilaiAkhirAttribute()` uses weighted average: tugas 20%, UH 30%, PTS 20%, PAS 30%.
- Export/Import classes use Maatwebsite Excel (`app/Exports/`, `app/Imports/`).
- `Guru` model does not define a `belongsTo(User)` relationship despite having `user_id` FK — keep this in mind when building guru-related queries.

## Views / UI
- `layouts/main.blade.php` is the authenticated shell; it only renders for `Auth::user()->info == true` or `request()->is('profil-saya*')`.
- `<base href="../../">` makes asset paths relative.
- `@stack('css')` is in the layout `<head>` (after base CSS, before dark-mode.css).
- `@stack('scripts')` lives in `resources/views/component/script.blade.php` and loads **before** Bootstrap JS — stack scripts must not depend on Bootstrap globals.
- `@include('sweetalert::alert')` comes after the shared script include.
- Dark mode is CSS-only (`public/css/dark-mode.css`) and must stay last among `<link>` stylesheets in `<head>`.
- Avoid `@php(...)` shorthand in files that already use `@php ... @endphp` blocks.
- Do not combine Blade `paginate()` with DataTables paging on the same table.
- Global view shares from `AppServiceProvider::boot()`: `$profil`, `$pengumuman`, `$galery` (with `Schema::hasTable('galery')` safety check).
- Autoloaded helpers are in `app/Http/Helpers/helpers.php`: `format_uang()`, `terbilang()`, `tanggal_indonesia()`, `tambah_nol_didepan()`, `angka_romawi()`.
