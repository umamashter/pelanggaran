<?php

namespace App\Http\Middleware;

use App\Models\RoleTwoFaRequirement;
use Closure;
use Illuminate\Http\Request;

/**
 * Require2FAMiddleware — paksa user role-wajib untuk setup 2FA sebelum akses dashboard.
 *
 * Berbeda dari Google2faMiddleware:
 *  - 2fa      = user PUNYA secret tapi belum verifikasi → challenge (OTP)
 *  - require.2fa = user TIDAK PUNYA secret tapi role-nya wajib → setup (QR)
 *
 * Urutan middleware (TDD §6): auth → 2fa → require.2fa → datasiswa → role
 *
 * Allowlist (route yang boleh diakses walau belum setup):
 *  - 2fa.setup*        : menyelesaikan setup
 *  - 2fa.recovery-codes: lihat kode recovery (post-setup)
 *  - 2fa.disable       : TIDAK di-allow → sekali disable, langsung ditendang setup lagi (konsisten)
 *  - logout, login     : keluar / kembali
 *  - post               : setup POST (tanpa name pattern)
 *
 * Anti-redirect-loop: target setup HARUS ada di allowlist middleware ini sendiri.
 */
class Require2FAMiddleware
{
    /** Pattern route name yang boleh diakses walau belum setup 2FA. */
    protected const ALLOWLIST = [
        '2fa.setup',
        '2fa.recovery-codes',
        'logout',
        'login',
        'login-history.index',
        'active-sessions.index',
    ];

    /** Pattern path prefix yang boleh diakses walau belum setup 2FA. */
    protected const ALLOWED_PREFIXES = [
        '2fa/setup',
        '2fa/recovery-codes',
        'riwayat-login',
        'perangkat',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Hanya berlaku untuk user yang sudah login
        if (!$user) {
            return $next($request);
        }

        // Skip bila user SUDAH punya 2FA secret (sudah setup)
        if (!empty($user->google2fa_secret)) {
            // Bersihkan flag peringatan bila sudah setup
            if (session('require_2fa_warned')) {
                session()->forget('require_2fa_warned');
            }
            return $next($request);
        }

        // Cek apakah role user diwajibkan 2FA
        if (!RoleTwoFaRequirement::roleRequires((int) $user->role)) {
            return $next($request);
        }

        // --- Role wajib 2FA tapi belum setup ---

        // Allowlist: route yang boleh diakses walau belum setup
        if ($this->isAllowed($request)) {
            return $next($request);
        }

        // Redirect hanya SEKALI per sesi — setelah itu izinkan navigasi bebas
        // agar user tidak terjebak redirect loop di sidebar
        if (session('require_2fa_warned')) {
            return $next($request);
        }

        session(['require_2fa_warned' => true]);

        return redirect()->route('2fa.setup')
            ->with('warning', 'Peran Anda mewajibkan Autentikasi Dua Faktor (2FA). Silakan aktifkan terlebih dahulu.');
    }

    /**
     * Apakah request ini berada di allowlist?
     */
    protected function isAllowed(Request $request): bool
    {
        // Cek via route name
        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::ALLOWLIST, true)) {
            return true;
        }
        // Untuk route POST tanpa name (mis. 2fa/setup POST), cocokkan pattern name
        if ($routeName && str_starts_with($routeName, '2fa.setup')) {
            return true;
        }

        // Cek via path prefix (cadangan bila route name tidak ada)
        $path = $request->path();
        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}