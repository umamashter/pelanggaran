@extends('layouts.data')
@section('title', 'Login')
@section('datas')
<div class="container" style="margin-top: 7.5%; margin-bottom: 7.5%; height:100%;">
    <div class="animate__animated animate__bounceIn login-container-wrapper clearfix" style="max-width: 325px;">
        <div class="welcome">Log In</div>

        <form class="form-horizontal login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group relative float">
                <input id="nisn" type="text" class="form-control input-lg"
                name="nisn" value="{{ old('nisn') }}" required
                autocomplete="nisn">
                <label>Nisn or Email</label>
            </div>

            <div class="form-group relative password float">
                <input id="password" class="form-control input-lg"
                name="password" type="password" required>
                <label>Password</label>
            </div>

            @if (session()->has('error'))
            <div class="alert alert-error text-right mb-1" style="padding: 0; font-size:12px;">
                <strong>* {{ session('error') }}</strong>
            </div>
            @endif

            <style>
                div.alert.alert-error {
                    right: 43px;
                    bottom: 105px;
                }
                @media(max-width: 420px) {
                    div.alert.alert-error {
                        right: 10px;
                        bottom: 100px;
                    }
                }
            </style>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Login') }}</button>
            </div>

            <div class="text-center">
                <label>belum punya akun? <a href="/register">Register</a></label>
            </div>

            <div class="text-center">
                <label><a href="/">Kembali ke Beranda</a></label>
            </div>
        </form>
    </div>
</div>
@endsection
