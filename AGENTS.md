# MIS Nurul Ulum

Laravel 8 app. Match the repo's Indonesian (Bahasa) copy in UI text, validation messages, and seeded/test data.

## Commands
- Install: `composer install && npm install`
- First local bootstrap: `php artisan migrate --seed && php artisan storage:link && php artisan serve`
- Frontend: `npm run dev`, `npm run watch`, `npm run watch-poll`, `npm run hot`, `npm run prod`
- Tests: `vendor/bin/phpunit`
- Single test file: `vendor/bin/phpunit tests/Feature/FooTest.php` or `vendor/bin/phpunit tests/Unit/FooTest.php`
- `app/Http/Helpers/helpers.php` is loaded via Composer `autoload.files`; helper edits do not need `composer dump-autoload`.
- Seeded dev logins all use password `password`: `admin@gmail.com` (role 1), `guru001@gmail.com` (role 2), `ren@gmail.com` (role 3, siswa).

## Verification
- No repo-local lint, formatter, or typecheck script exists in `composer.json` or `package.json`; do not invent one.
- `phpunit.xml` leaves sqlite in-memory commented out, so tests hit the DB from `.env`.
- Prefer the narrowest PHPUnit target; run full `vendor/bin/phpunit` only when needed.
- Protected-route feature tests must set session `2fa_passed`; required-role flows also need `require_2fa_warned`.
- Rebuild assets with `npm run dev` after frontend changes.

## Architecture / gotchas
- The real request entrypoint is `routes/web.php`; `routes/api.php` only exposes Sanctum `/user`. Public NISN lookup is `GET /api/{nisn}` in `routes/web.php`.
- Route order in `routes/web.php` is fragile: keep literal/helper routes before parameterized routes and before `Route::resource(...)`. Absensi import routes must stay above `/absensi/{id}`.
- Authenticated web flow relies on middleware order `auth -> 2fa -> require.2fa -> (datasiswa/role)`. `require.2fa` must keep allowing `2fa/setup`, `riwayat-login`, and `perangkat`; `2fa.disable` is intentionally not allowlisted there.
- `auth/2fa-challenge.blade.php` is a self-contained view (inline CSS/JS, layout `layouts.data`) with a strict UI-only contract: `TwoFactorController::verify()` reads ONLY `one_time_password`, which the view syncs from the 6 OTP boxes into the hidden `#otpValue` input — preserve that input name when restyling. Dark mode comes from `html.dark-mode` (global toggle script in `resources/views/component/script.blade.php`, localStorage `theme-preference`) plus legacy cyan overrides in `public/css/dark-mode.css` for this page's classes.
- `datasiswa` is not global: only `HomeController`'s constructor applies it (`$this->middleware(['auth', 'datasiswa'])`). Keep `profil-saya*` reachable outside it so siswa with incomplete data aren't locked out.
- The BK (`role:4`) route group in `routes/web.php` is entirely commented out, though `HomeController` still renders a role-4 dashboard; don't assume role-4 web routes exist.
- Role IDs are hard-coded across controllers and Blade views: `1=admin`, `2=guru`, `3=siswa`, `4=BK`, `5=kepala sekolah`.
- `HaflahMiddleware` is in the global `web` stack. It updates `haflatul_imtihans.status`, stores `haflah_id` in session, and shares `haflahAktif` / `semuaHaflah` with views; many lomba models/controllers implicitly depend on that session state.
- `resources/views/layouts/main.blade.php` uses `<base href="../../">`; prefer absolute helpers/URLs and re-check nested relative links/forms.
- `webpack.mix.js` only compiles `resources/js/app.js` and `resources/sass/app.scss`; new asset entry files do nothing until added there.
- OCR absensi import path is `AbsensiImportController -> AttendanceImportPipelineService -> AIParserService/OpenRouterVisionService -> AttendanceImportAdapter -> AbsensiImportService`. Feature flags and provider order live in `config/ocr.php`; local fallback shells out to `scripts/ocr_attendance.py`.

## Environment quirks
- `.env.example` defaults to MySQL database `buku_penghubung` and `SESSION_DRIVER=database`; make sure the `sessions` table migration has run locally.
- OCR defaults to OpenRouter (`AI_PROVIDER=openrouter`); Gemini is fallback. Local OCR fallback needs valid `PYTHON_PATH`, `TESSERACT_PATH`, and `OCR_SCRIPT_PATH`.
- `app/Helpers/WhatsAppHelper.php` is only in Composer `autoload-dev`, but production code imports it in `app/Http/Controllers/PoinController.php`; do not assume `composer install --no-dev` is safe.
