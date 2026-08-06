@extends('layouts.main')
@section('title', 'Perangkat Aktif')

@push('css')
<style>
    .page-title-content { display:none !important; }
    .dev-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:16px; }
    .dev-card { background:linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,255,255,.72)); border:1px solid var(--jd-border); border-radius:18px; box-shadow:var(--jd-shadow); padding:18px; }
    html.dark-mode .dev-card { background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .dev-top { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:14px; }
    .dev-icon { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; background:var(--jd-bg); color:var(--jd-primary); font-size:20px; }
    .dev-name { font-size:15px; font-weight:800; color:var(--jd-text); }
    .dev-sub { font-size:12px; color:var(--jd-text-3); margin-top:2px; }
    .dev-badges { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .dev-meta { display:grid; gap:8px; margin:14px 0; }
    .dev-row { display:flex; justify-content:space-between; gap:10px; font-size:12px; padding-bottom:8px; border-bottom:1px solid var(--jd-border); }
    .dev-actions { display:flex; gap:8px; flex-wrap:wrap; }
    @media (max-width: 991.98px) { .dev-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
@include('component.admin.jadwal-module')
<div class="jd-mod">
    <div class="jd-hero mb-4">
        <div class="jd-hero-grid">
            <div class="jd-hero-left">
                <span class="jd-hero-icon"><i class="bi bi-laptop"></i></span>
                <div>
                    <h1 class="jd-hero-title">Trusted Devices Center</h1>
                    <p class="jd-hero-sub">Pantau perangkat aktif, tandai perangkat tepercaya, dan cabut akses bila diperlukan.</p>
                </div>
            </div>
            <div class="jd-hero-right">
                <form method="POST" action="{{ route('active-sessions.revoke-others') }}">@csrf<button type="submit" class="jd-btn jd-btn--light" onclick="return confirm('Logout semua perangkat lain kecuali perangkat Anda saat ini?')"><i class="bi bi-power"></i> Logout Perangkat Lain</button></form>
            </div>
        </div>
    </div>

    @if (session('success'))<div class="jd-alert jd-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>@endif
    @if (session('error'))<div class="jd-alert jd-alert--err"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>@endif

    @if (empty($sessions))
        <div class="jd-card"><div class="jd-empty"><div class="jd-empty-title">Tidak ada perangkat aktif</div></div></div>
    @else
        @php
            $deviceIcons = ['desktop' => 'bi-pc-display', 'tablet' => 'bi-tablet', 'mobile' => 'bi-phone', 'bot' => 'bi-robot'];
        @endphp
        <div class="dev-grid">
            @foreach ($sessions as $s)
            <div class="dev-card">
                <div class="dev-top">
                    <div class="d-flex gap-3">
                        <div class="dev-icon"><i class="bi {{ $deviceIcons[$s['device_kind']] ?? 'bi-pc-display' }}"></i></div>
                        <div>
                            <div class="dev-name">{{ $s['browser'] }}</div>
                            <div class="dev-sub">{{ $s['os'] }} • {{ $s['device'] }}</div>
                            <div class="dev-badges">
                                @if ($s['is_current'])<span class="jd-chip jd-chip--green"><i class="bi bi-check-circle"></i> Perangkat Ini</span>@endif
                                @if ($s['is_trusted'])<span class="jd-chip jd-chip--blue"><i class="bi bi-shield-check"></i> Tepercaya</span>@endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dev-meta">
                    <div class="dev-row"><span class="text-muted">IP</span><code>{{ $s['ip'] ?? '—' }}</code></div>
                    <div class="dev-row"><span class="text-muted">Aktivitas terakhir</span><span>{{ $s['last_activity']?->diffForHumans() }}</span></div>
                </div>
                <div class="dev-actions">
                    @if ($s['fingerprint_id'])
                        @if ($s['is_trusted'])
                            <form method="POST" action="{{ route('active-sessions.untrust', $s['fingerprint_id']) }}">@csrf<button type="submit" class="jd-btn jd-btn--outline jd-btn--sm"><i class="bi bi-shield-x"></i> Cabut Akses</button></form>
                        @else
                            <form method="POST" action="{{ route('active-sessions.trust', $s['fingerprint_id']) }}">@csrf<button type="submit" class="jd-btn jd-btn--soft jd-btn--sm"><i class="bi bi-shield-check"></i> Trust Device</button></form>
                        @endif
                    @endif
                    @if (!$s['is_current'])
                        <form method="POST" action="{{ route('active-sessions.revoke', $s['id']) }}" class="ms-auto">@csrf<button type="submit" class="jd-btn jd-btn--danger jd-btn--sm" onclick="return confirm('Logout perangkat ini?')"><i class="bi bi-power"></i> Logout Device</button></form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
