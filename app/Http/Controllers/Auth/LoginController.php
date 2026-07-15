<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    protected $nisn;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->nisn = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return 'username';
    }

    public function login_view()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:50',
            'password' => 'required|max:100'
        ]);

        $login_type = filter_var($request->username, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $login_type => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->google2fa_secret) {
                $remember = $request->filled('remember');
                Auth::logout();

                session()->put('2fa:user_id', $user->id);
                session()->put('2fa:remember', $remember);

                Log::info('Login (2FA)', ['user' => $user->email, 'ip' => $request->ip()]);

                return redirect()->route('2fa.challenge');
            }

            Log::info('Login success', ['user' => $user->email, 'ip' => $request->ip()]);

            return redirect()->intended('/home')
                ->with('toast_info', 'Welcome Back ' . $user->name . '!');
        }

        Log::warning('Login failed', ['username' => $request->username, 'ip' => $request->ip()]);

        return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
