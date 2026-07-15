@extends('layouts.main')
@section('title', 'Keamanan 2FA')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-1" style="color: var(--ms-text);">
                        <i class="fas fa-shield-alt me-2" style="color: var(--ms-primary);"></i>
                        Keamanan 2FA
                    </h4>
                    <p class="text-muted mb-4" style="font-size: 14px;">
                        Kelola autentikasi dua faktor akun Anda.
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

                    <div class="bg-light rounded-3 p-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; flex-shrink: 0;">
                                <i class="fas fa-check text-white fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="color: var(--ms-text);">2FA Aktif</h6>
                                <p class="text-muted small mb-0">
                                    Akun Anda dilindungi dengan autentikasi dua faktor.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('2fa.recovery-codes') }}" class="btn btn-outline-success py-2 fw-semibold"
                            style="border-radius: 10px;">
                            <i class="fas fa-key me-1"></i>
                            Lihat Kode Recovery
                        </a>

                        <button type="button" class="btn btn-outline-danger py-2 fw-semibold"
                            style="border-radius: 10px;"
                            onclick="document.getElementById('disableForm').classList.toggle('d-none')">
                            <i class="fas fa-times-circle me-1"></i>
                            Nonaktifkan 2FA
                        </button>

                        <form id="disableForm" method="POST" action="{{ route('2fa.disable') }}" class="d-none mt-2">
                            @csrf
                            <div class="mb-2">
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan password untuk konfirmasi" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold"
                                style="border-radius: 10px;">
                                <i class="fas fa-shield-slash me-1"></i>
                                Nonaktifkan 2FA
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
