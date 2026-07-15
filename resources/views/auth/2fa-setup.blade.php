@extends('layouts.main')
@section('title', 'Aktifkan 2FA')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-1" style="color: var(--ms-text);">
                        <i class="fas fa-shield-alt me-2" style="color: var(--ms-primary);"></i>
                        Aktifkan Google Authenticator
                    </h4>
                    <p class="text-muted mb-4" style="font-size: 14px;">
                        Lindungi akun Anda dengan autentikasi dua faktor.
                    </p>

                    @if (session()->has('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3" style="border-radius: 10px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                    @endif

                    @if (session()->has('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3" style="border-radius: 10px;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="bg-light d-inline-block p-3 rounded-3 mb-3">
                            {!! $qrSvg !!}
                        </div>

                        <p class="text-muted small mb-1">Atau masukkan kode berikut secara manual:</p>
                        <code class="badge bg-dark text-white px-3 py-2 fs-6"
                            style="font-family: 'Courier New', monospace; letter-spacing: 2px; user-select: all;">
                            {{ $secret }}
                        </code>
                    </div>

                    <hr>

                    <form method="POST" action="{{ route('2fa.setup') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 13px;">
                                Verifikasi Kode OTP
                            </label>
                            <p class="text-muted small mb-2">
                                Scan QR code di atas menggunakan aplikasi <strong>Google Authenticator</strong>,
                                lalu masukkan kode 6 digit yang muncul.
                            </p>
                            <input type="text" name="one_time_password"
                                class="form-control form-control-lg text-center"
                                placeholder="000000" inputmode="numeric"
                                maxlength="6" required autofocus
                                style="letter-spacing: 6px; font-weight: 700; font-size: 24px;">
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold"
                            style="border-radius: 10px;">
                            <i class="fas fa-check-circle me-1"></i>
                            Aktifkan 2FA
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
