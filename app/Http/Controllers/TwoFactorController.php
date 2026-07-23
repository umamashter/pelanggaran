<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FALaravel\Google2FA;
use App\Events\TwoFactorEnabled;
use App\Events\TwoFactorDisabled;
use App\Events\RecoveryCodeUsed;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    public function setupView()
    {
        $user = auth()->user();

        if ($user->google2fa_secret) {
            return view('auth.2fa-manage');
        }

        $secret = session('2fa:secret') ?? $this->google2fa->generateSecretKey();
        session()->put('2fa:secret', $secret);

        $qrSvg = $this->google2fa->getQRCodeInline(
            'MIS Nurul Ulum',
            $user->email,
            $secret,
            200
        );

        return view('auth.2fa-setup', compact('secret', 'qrSvg'));
    }

    public function setup(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $secret = session('2fa:secret');

        if (!$secret) {
            return back()->with('error', 'Session 2FA expired. Silakan ulangi dari awal.');
        }

        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password);

        if (!$valid) {
            return back()->with('error', 'Kode OTP tidak valid. Coba lagi.');
        }

        $user = auth()->user();
        $user->google2fa_secret = $secret;

        $recoveryCodes = collect(range(1, 8))->map(function () {
            return bin2hex(random_bytes(5));
        });
        $user->recovery_codes = json_encode($recoveryCodes);
        $user->save();

        session()->forget('2fa:secret');
        session()->put('2fa_passed', true);
        session()->forget('require_2fa_warned');

        event(new TwoFactorEnabled($user));

        return redirect()->route('2fa.recovery-codes')->with('success', '2FA berhasil diaktifkan! Simpan kode recovery berikut.');
    }

    public function challengeView()
    {
        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session 2FA expired. Login ulang.');
        }

        $user = \App\Models\User::find($userId);
        if (!$user || !$user->google2fa_secret) {
            return redirect()->route('login')->with('error', 'User tidak valid.');
        }

        return view('auth.2fa-challenge', compact('user'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session 2FA expired. Login ulang.');
        }

        $user = \App\Models\User::find($userId);
        if (!$user || !$user->google2fa_secret) {
            return redirect()->route('login')->with('error', 'User tidak valid.');
        }

        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if (!$valid) {
            $codes = json_decode($user->recovery_codes ?? '[]', true);
            $found = array_search($request->one_time_password, $codes);
            if ($found !== false) {
                $valid = true;
                unset($codes[$found]);
                $user->recovery_codes = json_encode(array_values($codes));
                $user->save();

                event(new RecoveryCodeUsed($user));
            }
        }

        if (!$valid) {
            Log::warning('2FA failed', ['user' => $user->email, 'ip' => request()->ip()]);
            return back()->with('error', 'Kode OTP tidak valid.');
        }

        Log::info('2FA success', ['user' => $user->email, 'ip' => request()->ip()]);

        $remember = session('2fa:remember', false);
        Auth::loginUsingId($userId, $remember);

        session()->forget(['2fa:user_id', '2fa:remember']);
        session()->put('2fa_passed', true);

        return redirect()->intended('/home')
            ->with('toast_info', 'Welcome Back ' . $user->name . '!');
    }

    public function disable(Request $request)
    {
        $user = auth()->user();

        if (\App\Models\RoleTwoFaRequirement::roleRequires((int) $user->role)) {
            return back()->with('error', '2FA tidak dapat dinonaktifkan karena diwajibkan oleh kebijakan peran Anda. Hubungi administrator untuk mengubah kebijakan 2FA peran.');
        }

        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user->google2fa_secret = null;
        $user->recovery_codes = null;
        $user->save();

        session()->forget('2fa_passed');
        session()->forget('require_2fa_warned');

        event(new TwoFactorDisabled($user));

        return back()->with('success', '2FA berhasil dinonaktifkan.');
    }

    public function recoveryCodes()
    {
        $user = auth()->user();
        $codes = json_decode($user->recovery_codes ?? '[]', true);

        return view('auth.2fa-recovery-codes', compact('codes'));
    }
}
