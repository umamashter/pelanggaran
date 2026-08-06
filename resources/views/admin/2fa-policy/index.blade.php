@extends('layouts.main')
@section('title', 'Kebijakan 2FA per Role')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .sec-wrap { max-width: 1200px; margin: 0 auto; }
    .sec-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .sec-card { position: relative; overflow: hidden; background: linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,255,255,.72)); border: 1px solid var(--jd-border); border-radius: 18px; box-shadow: var(--jd-shadow); padding: 18px; }
    html.dark-mode .sec-card { background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .sec-card::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 4px; background: var(--jd-grad); opacity: .9; }
    .sec-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .sec-role { font-size: 15px; font-weight: 800; color: var(--jd-text); }
    .sec-sub { font-size: 11.5px; color: var(--jd-text-3); margin-top: 4px; }
    .sec-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .sec-badge.on { background: var(--jd-green-soft); color: var(--jd-green); border: 1px solid var(--jd-green-border); }
    .sec-badge.off { background: var(--jd-bg); color: var(--jd-text-2); border: 1px solid var(--jd-border); }
    .sec-metrics { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 16px; }
    .sec-metric { padding: 12px; border-radius: 14px; background: var(--jd-bg); border: 1px solid var(--jd-border); }
    .sec-metric .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--jd-text-3); font-weight: 700; }
    .sec-metric .v { margin-top: 4px; font-size: 17px; font-weight: 800; color: var(--jd-text); }
    .sec-actions { margin-top: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    @media (max-width: 991.98px) { .sec-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
@include('component.admin.jadwal-module')

@php
    $now = \Carbon\Carbon::now()->translatedFormat('d F Y • H:i');
@endphp

<div class="jd-mod">
    <div class="sec-wrap">
        <div class="jd-hero">
            <div class="jd-hero-grid">
                <div class="jd-hero-left">
                    <span class="jd-hero-icon"><i class="bi bi-shield-lock-fill"></i></span>
                    <div>
                        <h1 class="jd-hero-title">2FA Policy Center</h1>
                        <p class="jd-hero-sub">Atur kewajiban autentikasi dua faktor per role agar akses sistem mengikuti standar keamanan madrasah.</p>
                        <div class="jd-hero-badges">
                            <span class="jd-hero-badge"><i class="bi bi-clock"></i>{{ $now }}</span>
                            <span class="jd-hero-badge"><i class="bi bi-diagram-3"></i>{{ $policies->count() }} role dipantau</span>
                        </div>
                    </div>
                </div>
                <div class="jd-hero-right">
                    <a href="{{ route('admin.security-dashboard.index') }}" class="jd-btn jd-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="jd-alert jd-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="jd-alert jd-alert--err"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif

        <div class="sec-grid">
            @foreach ($policies as $policy)
            <div class="sec-card">
                <div class="sec-head">
                    <div>
                        <div class="sec-role">{{ $policy->role_label }}</div>
                        <div class="sec-sub">User role wajib akan diarahkan ke setup 2FA sebelum masuk dashboard.</div>
                    </div>
                    <span class="sec-badge {{ $policy->require_2fa ? 'on' : 'off' }}"><i class="bi {{ $policy->require_2fa ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>{{ $policy->require_2fa ? 'Wajib' : 'Opsional' }}</span>
                </div>
                <div class="sec-metrics">
                    <div class="sec-metric"><div class="k">Status</div><div class="v">{{ $policy->require_2fa ? 'Enforced' : 'Flexible' }}</div></div>
                    <div class="sec-metric"><div class="k">Adopsi</div><div class="v">Policy</div></div>
                </div>
                <div class="sec-actions">
                    <div class="text-muted small">Perubahan berlaku seketika.</div>
                    <form method="POST" action="{{ route('admin.2fa-policy.toggle', $policy->role) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="jd-btn {{ $policy->require_2fa ? 'jd-btn--outline' : 'jd-btn--success' }} jd-btn--sm">
                            <i class="bi {{ $policy->require_2fa ? 'bi-toggle-off' : 'bi-toggle-on' }}"></i>
                            {{ $policy->require_2fa ? 'Nonaktifkan Kewajiban' : 'Jadikan Wajib' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="jd-alert jd-alert--info mt-4"><i class="bi bi-info-circle-fill"></i> Perubahan policy ini terintegrasi langsung dengan middleware 2FA; user role wajib tanpa setup akan diarahkan ke wizard aktivasi.</div>
    </div>
</div>
@endsection
