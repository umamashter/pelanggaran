@extends('layouts.main')
@section('title', 'Aktifkan 2FA')

@push('css')
<style>
    .page-title-content { display:none !important; }
    .sec-wrap { max-width: 1100px; margin: 0 auto; }
    .sec-grid { display:grid; grid-template-columns:minmax(0,1.2fr) minmax(320px,.8fr); gap:20px; align-items:start; }
    .sec-card { background: var(--jd-card); border:1px solid var(--jd-border); border-radius:20px; box-shadow:var(--jd-shadow); overflow:hidden; }
    .sec-head { padding:20px; border-bottom:1px solid var(--jd-border); }
    .sec-body { padding:20px; }
    .sec-stepper { margin-bottom: 18px; }
    .sec-qr { display:flex; justify-content:center; align-items:center; min-height:280px; border-radius:18px; background:linear-gradient(135deg, rgba(37,99,235,.08), rgba(37,99,235,.03)); border:1px solid var(--jd-primary-border); }
    .sec-secret { margin-top:16px; padding:14px; border-radius:14px; background:var(--jd-bg); border:1px solid var(--jd-border); text-align:center; }
    .sec-secret code { font-size:16px; font-weight:800; letter-spacing:2px; color:var(--jd-text); user-select:all; }
    .sec-note { font-size:12px; color:var(--jd-text-3); margin-top:8px; text-align:center; }
    .sec-summary { padding:20px; border-radius:20px; background:linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,255,255,.74)); border:1px solid var(--jd-border); box-shadow:var(--jd-shadow); }
    html.dark-mode .sec-summary { background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .sec-summary-grid { display:grid; gap:12px; margin-top:14px; }
    .sec-summary-item { padding:14px; border-radius:14px; background:var(--jd-card); border:1px solid var(--jd-border); }
    .sec-summary-item .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .sec-summary-item .v { margin-top:4px; font-size:15px; font-weight:800; color:var(--jd-text); }
    @media (max-width: 991.98px) { .sec-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
@include('component.admin.jadwal-module')
<div class="jd-mod">
    <div class="sec-wrap">
        <div class="jd-hero mb-4">
            <div class="jd-hero-grid">
                <div class="jd-hero-left">
                    <span class="jd-hero-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <h1 class="jd-hero-title">2FA Setup Wizard</h1>
                        <p class="jd-hero-sub">Aktifkan autentikasi dua faktor untuk meningkatkan security score akun Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sec-grid">
            <div class="sec-card">
                <div class="sec-head">
                    <div class="jd-stepper sec-stepper">
                        <div class="jd-step active"><span class="jd-step-dot">1</span><div class="jd-step-txt"><b>Aktifkan</b><span>Mulai setup</span></div></div>
                        <div class="jd-step-line done"></div>
                        <div class="jd-step active"><span class="jd-step-dot">2</span><div class="jd-step-txt"><b>Scan QR</b><span>Authenticator</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step"><span class="jd-step-dot">3</span><div class="jd-step-txt"><b>Verifikasi</b><span>Kode OTP</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step"><span class="jd-step-dot">4</span><div class="jd-step-txt"><b>Recovery</b><span>Kode cadangan</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step"><span class="jd-step-dot">5</span><div class="jd-step-txt"><b>Selesai</b><span>Akun aman</span></div></div>
                    </div>
                    <h4 class="fw-bold mb-1">Scan QR Code</h4>
                    <p class="text-muted mb-0">Gunakan Google Authenticator atau aplikasi TOTP lain, lalu verifikasi kode 6 digit.</p>
                </div>
                <div class="sec-body">
                    @if (session()->has('warning'))<div class="jd-alert jd-alert--warn"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}</div>@endif
                    @if (session()->has('success'))<div class="jd-alert jd-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>@endif
                    @if (session()->has('error'))<div class="jd-alert jd-alert--err"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>@endif

                    <div class="sec-qr">{!! $qrSvg !!}</div>
                    <div class="sec-secret"><code>{{ $secret }}</code><div class="sec-note">Kode manual cadangan jika kamera tidak tersedia.</div></div>

                    <form method="POST" action="{{ route('2fa.setup') }}" class="mt-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Verifikasi Kode OTP</label>
                            <input type="text" name="one_time_password" class="form-control form-control-lg text-center" placeholder="000000" inputmode="numeric" maxlength="6" required autofocus style="letter-spacing: 6px; font-weight:700; font-size:24px; border-radius:14px;">
                        </div>
                        <button type="submit" class="jd-btn jd-btn--success w-100"><i class="bi bi-check2-circle"></i> Aktifkan 2FA</button>
                    </form>
                </div>
            </div>

            <div class="sec-summary">
                <div class="hl-summary-title"><i class="bi bi-shield-lock"></i> Security Benefit</div>
                <div class="sec-summary-grid">
                    <div class="sec-summary-item"><div class="k">Status</div><div class="v">2FA Belum Aktif</div></div>
                    <div class="sec-summary-item"><div class="k">Langkah Berikutnya</div><div class="v">Verifikasi OTP</div></div>
                    <div class="sec-summary-item"><div class="k">Recovery Codes</div><div class="v">Akan dibuat otomatis</div></div>
                    <div class="sec-summary-item"><div class="k">Security Score</div><div class="v">Naik setelah aktivasi</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
