<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $this->nisn = $this->findNisn();
    }

    public function findNisn()
    {
        $login = request()->input('nisn');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return $this->nisn;
    }

    public function login_view()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'nisn' => 'required|max:50',
            'password' => 'required|max:100'
        ]);

        $login_type = filter_var($request->input('nisn'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'nisn';

        $request->merge([
            $login_type => $request->input('nisn')
        ]);
        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt($request->only($login_type, 'password'), $remember)) {
            return redirect()->intended('/home')->with('toast_info', 'Welcome Back ' . auth()->user()->name . '!');
        }

        return back()->with('error', 'Login gagal!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}