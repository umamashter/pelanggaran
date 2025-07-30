@extends('layouts.data')
@section('title', 'Register')
@section('datas')
    <style>
        .float input[type="email"]:not(:placeholder-shown) ~ label {
            top: -43px;
            left: -10px;
            color: #ddd;
            font-size: 13px;
        }
    </style>
    <div class="container" style="margin-top: 3%; margin-bottom: 3%; height:100%;">
        <div class="animate__animated animate__fadeInDown login-container-wrapper clearfix" style="max-width: 600px;">
            <div class="welcome">Register</div>

            <form class="form-horizontal login-form" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group relative float">
                    @error('nisn')
                        <div class="alert alert-error text-right mb-1" style="padding: 0; font-size:12px;">
                            <strong>* {{ $message }}</strong>
                        </div>
                    @enderror
                    <input id="nisn" type="text" class="form-control input-lg mb-1 @error('nisn') is-invalid @enderror"
                        name="nisn" value="{{ old('nisn') }}" required autocomplete="nisn" autofocus>
                    <label>NISN</label>
                </div>

                <div class="form-group relative float">
                    @error('email')
                        <div class="alert alert-error text-right mb-1" style="padding: 0; font-size:12px;">
                            <strong>* {{ $message }}</strong>
                        </div>
                    @enderror
                    <input id="email" type="email" class="form-control input-lg mb-1 mt-4 @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" placeholder=" ">
                    <label>Email</label>
                </div>

                <div class="all-data" style="margin-top: -1rem;">
                    <div class="col-lg-6 col-sm-12 px-0 outer">
                        <div class="form-group relative float password one">
                            <input id="password" type="password" class="form-control input-lg mb-1 mt-4" name="password" required
                                autocomplete="new-password">
                            <label>Password</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 px-0 outer">
                        <div class="form-group relative float password two">
                            @error('password')
                                <div class="alert alert-error text-right mb-1" style="padding: 0; font-size:12px;">
                                    <strong>* {{ $message }}</strong>
                                </div>
                            @enderror
                            <input id="password-confirm" type="password"
                                class="form-control input-lg mb-1 mt-4 @error('password') is-invalid @enderror" name="password_confirmation"
                                required autocomplete="new-password">
                            <label>Konfirmasi Password</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group relative">
                    <div class="mb-1 mt-2">
                        <input name="g-recaptcha-response" type="hidden" required
                            class=" @error('g-recaptcha-response') is-invalid @enderror"/>
                        <div class="g-recaptcha" data-sitekey="6LeGFxMkAAAAABDvm6s7ilLmm_7oXrgEfa--l_P6" style="transform: scale(0.842); -webkit-transform: scale(0.842); transform-origin: 0 0; -webkit-transform-origin: 0 0;">
                        </div>
                    </div>
                    @error('g-recaptcha-response')
                        <text-small class="alert alert-error text-right mb-1" style="padding: 0; font-size:12px;">
                            <strong>* {{ $message }}</strong>
                        </text-small>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Register') }}</button>
                </div>

                <div class="text-center">
                    <label>sudah punya akun? <a href="/login">Login</a></label>
                </div>
            </form>
        </div>
    </div>

@endsection
