@extends('layouts.main')
@section('title', 'Keamanan 2FA')

@push('css')
<style>
    .page-title-content { display:none !important; }
    .sec-wrap { max-width: 980px; margin: 0 auto; }
    .sec-grid { display:grid; grid-template-columns:minmax(0,1fr) minmax(320px,.8fr); gap:20px; }
    .sec-card { background:var(--jd-card); border:1px solid var(--jd-border); border-radius:20px; box-shadow:var(--jd-shadow); overflow:hidden; }
    .sec-body { padding:20px; }
    .sec-status { padding:18px; border-radius:18px; background:linear-gradient(135deg, rgba(22,163,74,.12), rgba(22,163,74,.04)); border:1px solid var(--jd-green-border); }
    .sec-status h6 { font-size:16px; font-weight:800; color:var(--jd-text); }
    .sec-status p { margin:6px 0 0; font-size:12px; color:var(--jd-text-3); }
    .sec-mini { display:grid; gap:12px; }
    .sec-mini-item { padding:14px; border-radius:14px; background:var(--jd-bg); border:1px solid var(--jd-border); }
    .sec-mini-item .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .sec-mini-item .v { margin-top:4px; font-size:15px; font-weight:800; color:var(--jd-text); }
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
                    <span class="jd-hero-icon"><i class="bi bi-shield-lock-fill"></i></span>
                    <div>
                        <h1 class="jd-hero-title">2FA Control Center</h1>
                        <p class="jd-hero-sub">Kelola autentikasi dua faktor, recovery codes, dan policy akses akun Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sec-grid">
            <div class="sec-card">
                <div class="sec-body">
                    @if (session()->has('success'))<div class="jd-alert jd-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>@endif
                    @if (session()->has('error'))<div class="jd-alert jd-alert--err"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>@endif
                    <div class="sec-status mb-4">
                        <h6 class="mb-0">2FA Aktif</h6>
                        <p>Akun Anda dilindungi dengan autentikasi dua faktor dan siap digunakan untuk akses yang lebih aman.</p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('2fa.recovery-codes') }}" class="jd-btn jd-btn--soft"><i class="bi bi-key"></i> Lihat Recovery Codes</a>
                        @if (\App\Models\RoleTwoFaRequirement::roleRequires((int) Auth::user()->role))
                            <div class="jd-alert jd-alert--info mt-2"><i class="bi bi-info-circle-fill"></i> 2FA diwajibkan untuk peran Anda. Hubungi administrator untuk mengubah kebijakan.</div>
                        @else
                            <button type="button" class="jd-btn jd-btn--danger" onclick="document.getElementById('disableForm').classList.toggle('d-none')"><i class="bi bi-shield-x"></i> Nonaktifkan 2FA</button>
                            <form id="disableForm" method="POST" action="{{ route('2fa.disable') }}" class="d-none mt-2">
                                @csrf
                                <div class="mb-2"><input type="password" name="password" class="form-control" placeholder="Masukkan password untuk konfirmasi" required style="border-radius:12px;"></div>
                                <button type="submit" class="jd-btn jd-btn--danger w-100"><i class="bi bi-unlock"></i> Konfirmasi Nonaktifkan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="sec-mini">
                <div class="sec-mini-item"><div class="k">Policy</div><div class="v">{{ \App\Models\RoleTwoFaRequirement::roleRequires((int) Auth::user()->role) ? 'Mandatory' : 'Optional' }}</div></div>
                <div class="sec-mini-item"><div class="k">Recovery Codes</div><div class="v">Tersedia</div></div>
                <div class="sec-mini-item"><div class="k">Security Health</div><div class="v">Protected</div></div>
            </div>
        </div>
    </div>
</div>
@endsection
