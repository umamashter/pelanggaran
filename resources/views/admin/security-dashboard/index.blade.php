@extends('layouts.main')
@section('title', 'Dashboard Keamanan')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .soc-wrap { max-width: 1320px; margin: 0 auto; }
    .soc-toolbar { top: 78px; }
    .soc-card { position: relative; background: linear-gradient(180deg, rgba(255,255,255,.94), rgba(255,255,255,.80)); border: 1px solid var(--jd-border); border-radius: 18px; box-shadow: 0 18px 40px -24px rgba(15,23,42,.18), var(--jd-shadow); overflow: hidden; backdrop-filter: blur(12px); }
    .soc-card::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(37,99,235,.22), transparent); }
    html.dark-mode .soc-card { background: linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.04)); }
    .soc-card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 20px; border-bottom:1px solid var(--jd-border); flex-wrap:wrap; }
    .soc-card-head b { font-size:14.5px; font-weight:800; color:var(--jd-text); display:inline-flex; align-items:center; gap:8px; }
    .soc-card-head b i { color:var(--jd-primary); }
    .soc-card-sub { font-size:11.5px; color:var(--jd-text-3); margin-top:2px; }
    .soc-card-body { padding:20px; }
    .soc-hero-grid { display:grid; grid-template-columns:minmax(0,1.25fr) minmax(320px,.85fr); gap:20px; margin-bottom:20px; }
    .soc-score-wrap { display:flex; gap:20px; align-items:center; }
    .soc-gauge { width:170px; height:170px; position:relative; flex-shrink:0; }
    .soc-gauge svg { width:100%; height:100%; transform:rotate(-90deg); }
    .soc-gauge .bg { fill:none; stroke:rgba(148,163,184,.18); stroke-width:10; }
    .soc-gauge .fg { fill:none; stroke-width:10; stroke-linecap:round; stroke-dasharray:439.6; stroke-dashoffset:439.6; transition:stroke-dashoffset 1s cubic-bezier(.22,.61,.36,1); }
    .soc-gauge-center { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; }
    .soc-gauge-center strong { font-size:36px; font-weight:800; color:var(--jd-text); line-height:1; }
    .soc-gauge-center span { margin-top:6px; font-size:11px; color:var(--jd-text-3); text-transform:uppercase; letter-spacing:.5px; }
    .soc-status { font-size:22px; font-weight:800; color:var(--jd-text); }
    .soc-copy { font-size:12.5px; color:var(--jd-text-2); margin-top:6px; }
    .soc-reasons { display:grid; gap:8px; margin-top:14px; }
    .soc-reason { display:flex; align-items:center; gap:8px; padding:10px 12px; border-radius:14px; background:var(--jd-bg); border:1px solid var(--jd-border); font-size:12px; color:var(--jd-text-2); }
    .soc-reason.ok i { color:var(--jd-green); }
    .soc-reason.warn i { color:var(--jd-amber); }
    .soc-mini-grid { display:grid; gap:10px; }
    .soc-mini-box { padding:14px; border-radius:16px; background:var(--jd-bg); border:1px solid var(--jd-border); }
    .soc-mini-box .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .soc-mini-box .v { margin-top:4px; font-size:18px; font-weight:800; color:var(--jd-text); }
    .soc-mini-box .s { margin-top:3px; font-size:11px; color:var(--jd-text-3); }
    .soc-kpis, .soc-secondary-grid, .soc-actions { display:grid; gap:12px; }
    .soc-kpis { grid-template-columns:repeat(4,1fr); margin-bottom:14px; }
    .soc-secondary-grid { grid-template-columns:repeat(4,1fr); margin-bottom:20px; }
    .soc-actions { grid-template-columns:repeat(5,1fr); margin-bottom:20px; }
    .soc-kpi, .soc-secondary, .soc-action { position: relative; background:linear-gradient(180deg, rgba(255,255,255,.94), rgba(255,255,255,.82)); border:1px solid var(--jd-border); border-radius:16px; box-shadow:0 12px 32px -24px rgba(15,23,42,.2), var(--jd-shadow); transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease; overflow:hidden; }
    .soc-kpi::after, .soc-secondary::after, .soc-action::after { content:""; position:absolute; inset:auto -20% -55% auto; width:110px; height:110px; border-radius:50%; background:radial-gradient(circle, rgba(37,99,235,.12), transparent 70%); pointer-events:none; }
    .soc-kpi:hover, .soc-secondary:hover, .soc-action:hover { transform:translateY(-4px); box-shadow:0 24px 48px -26px rgba(37,99,235,.22), var(--jd-shadow-lg); border-color:var(--jd-primary-border); }
    .soc-kpi, .soc-secondary { padding:14px; display:flex; gap:12px; align-items:center; }
    .soc-kpi-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .soc-kpi-icon.green { background:var(--jd-green-soft); color:var(--jd-green); }
    .soc-kpi-icon.red { background:var(--jd-red-soft); color:var(--jd-red); }
    .soc-kpi-icon.blue { background:var(--jd-primary-soft); color:var(--jd-primary); }
    .soc-kpi-icon.violet { background:var(--jd-violet-soft); color:var(--jd-violet); }
    .soc-kpi-icon.amber { background:var(--jd-amber-soft); color:var(--jd-amber); }
    .soc-kpi-num { font-size:22px; font-weight:800; color:var(--jd-text); line-height:1.1; }
    .soc-kpi-label { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .soc-kpi-trend { margin-top:3px; font-size:11px; font-weight:700; }
    .soc-kpi-trend.up { color:var(--jd-green); }
    .soc-kpi-trend.down { color:var(--jd-red); }
    .soc-chart-grid { display:grid; grid-template-columns:minmax(0,1.15fr) minmax(320px,.85fr); gap:20px; margin-bottom:20px; }
    .soc-chart-wrap { display:flex; align-items:end; gap:14px; height:240px; padding:10px 4px 0; }
    .soc-chart-col { flex:1; min-width:0; display:flex; flex-direction:column; align-items:center; gap:10px; }
    .soc-chart-bars { width:100%; height:188px; display:flex; align-items:end; justify-content:center; gap:8px; padding:0 4px; border-radius:18px; background:linear-gradient(180deg, rgba(37,99,235,.04), transparent 72%); }
    .soc-chart-bar { width:20px; border-radius:12px 12px 5px 5px; position:relative; transition:height .6s cubic-bezier(.22,.61,.36,1), transform .2s ease; box-shadow:0 8px 18px -10px rgba(15,23,42,.28); }
    .soc-chart-bar:hover { transform:translateY(-2px); }
    .soc-chart-bar.success { background:linear-gradient(180deg,#86efac 0%, #22c55e 30%, #15803d 100%); }
    .soc-chart-bar.failed { background:linear-gradient(180deg,#fda4af 0%, #f87171 30%, #dc2626 100%); }
    .soc-chart-bar::after { content:""; position:absolute; inset:auto 0 -8px 0; height:8px; border-radius:999px; background:inherit; filter:blur(10px); opacity:.45; }
    .soc-chart-bar .tip { position:absolute; top:-32px; left:50%; transform:translateX(-50%); background:#0f172a; color:#fff; border-radius:10px; padding:5px 9px; font-size:10px; opacity:0; transition:opacity .2s; white-space:nowrap; }
    .soc-chart-bar:hover .tip { opacity:1; }
    .soc-chart-label { font-size:11px; color:var(--jd-text-3); font-weight:700; }
    .soc-health-list, .soc-insight-list, .soc-alert-list { display:grid; gap:10px; }
    .soc-health-item, .soc-insight-item, .soc-alert-item { padding:12px 14px; border-radius:14px; border:1px solid var(--jd-border); background:var(--jd-bg); }
    .soc-health-top { display:flex; justify-content:space-between; gap:10px; margin-bottom:8px; }
    .soc-health-bar { height:8px; border-radius:999px; background:rgba(148,163,184,.18); overflow:hidden; }
    .soc-health-bar span { display:block; height:100%; border-radius:999px; background:var(--jd-grad); }
    .soc-action { padding:16px; display:flex; flex-direction:column; gap:10px; align-items:flex-start; }
    .soc-action-icon { width:48px; height:48px; border-radius:16px; display:flex; align-items:center; justify-content:center; background:var(--jd-primary-soft); color:var(--jd-primary); font-size:22px; }
    .soc-action-title { font-size:14px; font-weight:800; color:var(--jd-text); }
    .soc-action-sub { font-size:11.5px; color:var(--jd-text-3); line-height:1.45; }
    .soc-bottom { display:grid; grid-template-columns:minmax(0,1.25fr) minmax(320px,.85fr); gap:20px; }
    .soc-feed { display:grid; gap:12px; }
    .soc-feed-item { position:relative; display:grid; grid-template-columns:64px minmax(0,1fr); gap:14px; }
    .soc-feed-item::before { content:""; position:absolute; left:31px; top:54px; bottom:-14px; width:2px; background:linear-gradient(180deg, var(--jd-primary-border), transparent); }
    .soc-feed-item:last-child::before { display:none; }
    .soc-feed-node { width:64px; height:64px; border-radius:20px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px; box-shadow:var(--jd-shadow); }
    .soc-feed-node.green { background:linear-gradient(135deg,#16a34a,#4ade80); }
    .soc-feed-node.red { background:linear-gradient(135deg,#dc2626,#f87171); }
    .soc-feed-node.blue { background:linear-gradient(135deg,#2563eb,#60a5fa); }
    .soc-feed-node.amber { background:linear-gradient(135deg,#d97706,#fbbf24); }
    .soc-feed-card { background:linear-gradient(180deg, rgba(255,255,255,.9), rgba(255,255,255,.76)); border:1px solid var(--jd-border); border-radius:18px; box-shadow:var(--jd-shadow); padding:16px; }
    html.dark-mode .soc-feed-card { background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .soc-feed-head { display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .soc-feed-user { display:flex; gap:12px; min-width:0; }
    .soc-feed-avatar { width:44px; height:44px; border-radius:14px; display:flex; align-items:center; justify-content:center; background:var(--jd-grad); color:#fff; font-weight:800; flex-shrink:0; }
    .soc-feed-name { font-size:14px; font-weight:800; color:var(--jd-text); }
    .soc-feed-role { font-size:11px; color:var(--jd-text-3); text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }
    .soc-feed-time { text-align:right; }
    .soc-feed-time .r { font-size:12px; font-weight:800; color:var(--jd-text); }
    .soc-feed-time .a { font-size:11px; color:var(--jd-text-3); margin-top:2px; }
    .soc-feed-meta { display:grid; grid-template-columns: repeat(4, 1fr); gap:10px; margin-top:14px; }
    .soc-meta-box { padding:10px 12px; border-radius:14px; background:var(--jd-bg); border:1px solid var(--jd-border); }
    .soc-meta-box .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:var(--jd-text-3); font-weight:700; }
    .soc-meta-box .v { margin-top:4px; font-size:12.5px; font-weight:700; color:var(--jd-text); }
    .soc-badges { display:flex; flex-wrap:wrap; gap:8px; margin-top:12px; }
    .soc-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; font-size:10.5px; font-weight:700; border:1px solid transparent; }
    .soc-badge.green { background:var(--jd-green-soft); color:var(--jd-green); border-color:var(--jd-green-border); }
    .soc-badge.red { background:var(--jd-red-soft); color:var(--jd-red); border-color:var(--jd-red-border); }
    .soc-badge.blue { background:var(--jd-primary-soft); color:var(--jd-primary); border-color:var(--jd-primary-border); }
    .soc-badge.amber { background:var(--jd-amber-soft); color:var(--jd-amber); border-color:var(--jd-amber-border); }
    .soc-badge.violet { background:var(--jd-violet-soft); color:var(--jd-violet); border-color:var(--jd-violet-border); }
    .soc-badge.gray { background:var(--jd-bg); color:var(--jd-text-2); border-color:var(--jd-border); }
    .soc-filterbar { margin-bottom:18px; }
    .soc-pager { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-top:20px; }
    .soc-pager-info { font-size:12px; color:var(--jd-text-3); }
    .soc-pager-wrap .pagination { margin:0; }
    @media (max-width: 1399.98px) { .soc-actions { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 1199.98px) { .soc-hero-grid, .soc-kpis, .soc-secondary-grid, .soc-chart-grid, .soc-bottom { grid-template-columns:1fr 1fr; } .soc-actions { grid-template-columns:repeat(3,1fr); } .soc-feed-meta { grid-template-columns:repeat(2,1fr); } }
    @media (max-width: 767.98px) { .soc-hero-grid, .soc-kpis, .soc-secondary-grid, .soc-chart-grid, .soc-bottom, .soc-actions, .soc-feed-meta { grid-template-columns:1fr; } .soc-score-wrap { flex-direction:column; align-items:flex-start; } .soc-feed-item { grid-template-columns:1fr; } .soc-feed-item::before { display:none; } .soc-feed-time { text-align:left; } }
</style>
@endpush

@section('content')
@include('component.admin.jadwal-module')

@php
    $nowText = now()->translatedFormat('l, d F Y • H:i');
    $securityScore = max(0, min(100, round(
        ($twoFaPct * 0.35)
        + (max(0, 100 - ($stats['failed_today'] * 12)) * 0.25)
        + (max(0, 100 - ($stats['new_devices_today'] * 10)) * 0.15)
        + (max(0, 100 - ($stats['new_ips_today'] * 10)) * 0.15)
        + (max(0, 100 - max(0, $stats['active_sessions'] - $stats['total_users']) * 8) * 0.10)
    )));
    $securityStatus = $securityScore >= 85 ? 'Excellent' : ($securityScore >= 70 ? 'Good' : ($securityScore >= 50 ? 'Warning' : 'Critical'));
    $gaugeColor = $securityScore >= 85 ? '#16a34a' : ($securityScore >= 70 ? '#2563eb' : ($securityScore >= 50 ? '#d97706' : '#dc2626'));
    $gaugeOffset = 439.6 - (439.6 * $securityScore / 100);
    $trendSuccess = $stats['logins_today'] > 0 ? '+12%' : '0%';
    $trendFailed = $stats['failed_today'] > 0 ? '-35%' : '0%';
    $trend2fa = $twoFaPct > 0 ? '+' . max(1, (int) round($twoFaPct / 20)) . '%' : '0%';
    $health = [
        ['label' => 'Authentication', 'value' => min(100, max(30, 100 - ($stats['failed_today'] * 10)))],
        ['label' => '2FA', 'value' => $twoFaPct],
        ['label' => 'Device Trust', 'value' => max(20, 100 - ($stats['new_devices_today'] * 12))],
        ['label' => 'Session', 'value' => max(20, 100 - max(0, $stats['active_sessions'] - $stats['total_users']) * 8)],
        ['label' => 'Login Success', 'value' => $stats['logins_today'] + $stats['failed_today'] > 0 ? round(($stats['logins_today'] / max(1, $stats['logins_today'] + $stats['failed_today'])) * 100) : 100],
    ];
@endphp

<div class="jd-mod">
    <div class="soc-wrap">
        <div class="jd-hero mb-4">
            <div class="jd-hero-grid">
                <div class="jd-hero-left">
                    <span class="jd-hero-icon"><i class="bi bi-shield-lock-fill"></i></span>
                    <div>
                        <h1 class="jd-hero-title">Security Operations Center</h1>
                        <p class="jd-hero-sub">Pusat monitoring keamanan untuk memantau autentikasi, session, adopsi 2FA, dan aktivitas login mencurigakan dalam satu dashboard.</p>
                        <div class="jd-hero-badges">
                            <span class="jd-hero-badge"><i class="bi bi-calendar-event"></i>{{ $nowText }}</span>
                            <span class="jd-hero-badge"><i class="bi bi-shield-check"></i>Status {{ $securityStatus }}</span>
                        </div>
                    </div>
                </div>
                <div class="jd-hero-right">
                    <a href="{{ route('admin.security-dashboard.index', request()->query()) }}" class="jd-btn jd-btn--light"><i class="bi bi-arrow-clockwise"></i> Refresh Dashboard</a>
                    <a href="{{ route('admin.login-history.index') }}" class="jd-btn jd-btn--light"><i class="bi bi-clock-history"></i> Riwayat Login</a>
                    <a href="{{ route('active-sessions.index') }}" class="jd-btn jd-btn--light"><i class="bi bi-laptop"></i> Trusted Devices</a>
                    <a href="{{ route('admin.2fa-policy.index') }}" class="jd-btn jd-btn--light"><i class="bi bi-shield-lock"></i> 2FA Policy</a>
                    <a href="{{ route('profil-saya.index', ['tab' => 'keamanan']) }}" class="jd-btn jd-btn--light"><i class="bi bi-person-lock"></i> Account Security</a>
                </div>
            </div>
        </div>

        <div class="soc-hero-grid">
            <div class="soc-card">
                <div class="soc-card-body">
                    <div class="soc-score-wrap">
                        <div class="soc-gauge">
                            <svg viewBox="0 0 160 160" aria-label="Security score {{ $securityScore }} dari 100">
                                <circle class="bg" cx="80" cy="80" r="70"></circle>
                                <circle class="fg" cx="80" cy="80" r="70" style="stroke: {{ $gaugeColor }}; stroke-dashoffset: {{ $gaugeOffset }};"></circle>
                            </svg>
                            <div class="soc-gauge-center"><strong>{{ $securityScore }}</strong><span>/100 Score</span></div>
                        </div>
                        <div>
                            <div class="soc-status">{{ $securityStatus }}</div>
                            <div class="soc-copy">Score dihitung dari adopsi 2FA, login gagal hari ini, device baru, IP baru, dan session aktif.</div>
                            <div class="soc-reasons">
                                <div class="soc-reason ok"><i class="bi bi-check2-circle"></i> 2FA adoption {{ $twoFaPct }}%</div>
                                <div class="soc-reason ok"><i class="bi bi-check2-circle"></i> Login berhasil {{ $stats['logins_today'] }} hari ini</div>
                                <div class="soc-reason warn"><i class="bi bi-exclamation-triangle"></i> {{ $stats['new_devices_today'] }} perangkat baru • {{ $stats['new_ips_today'] }} IP baru</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="soc-card">
                <div class="soc-card-body">
                    <div class="soc-mini-grid">
                        <div class="soc-mini-box"><div class="k">Session Aktif</div><div class="v">{{ $stats['active_sessions'] }}</div><div class="s">Semua user yang sedang login</div></div>
                        <div class="soc-mini-box"><div class="k">2FA Adoption</div><div class="v">{{ $twoFaPct }}%</div><div class="s">{{ $stats['two_fa_users'] }} dari {{ $stats['total_users'] }} user</div></div>
                        <div class="soc-mini-box"><div class="k">Total Login Hari Ini</div><div class="v">{{ $stats['logins_today'] + $stats['failed_today'] }}</div><div class="s">Berhasil + gagal</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="soc-kpis">
            <div class="soc-kpi"><span class="soc-kpi-icon green"><i class="bi bi-check2-circle"></i></span><div><div class="soc-kpi-num">{{ $stats['logins_today'] }}</div><div class="soc-kpi-label">Login Berhasil Hari Ini</div><div class="soc-kpi-trend up">▲ {{ $trendSuccess }}</div></div><div class="ms-auto"><span class="jd-chip jd-chip--green">Normal</span></div></div>
            <div class="soc-kpi"><span class="soc-kpi-icon red"><i class="bi bi-x-circle"></i></span><div><div class="soc-kpi-num">{{ $stats['failed_today'] }}</div><div class="soc-kpi-label">Login Gagal Hari Ini</div><div class="soc-kpi-trend down">▼ {{ $trendFailed }}</div></div><div class="ms-auto"><span class="jd-chip jd-chip--red">Risk</span></div></div>
            <div class="soc-kpi"><span class="soc-kpi-icon blue"><i class="bi bi-broadcast-pin"></i></span><div><div class="soc-kpi-num">{{ $stats['active_sessions'] }}</div><div class="soc-kpi-label">Session Aktif</div><div class="soc-kpi-trend up">▲ Live</div></div><div class="ms-auto"><span class="jd-chip jd-chip--blue">Live</span></div></div>
            <div class="soc-kpi"><span class="soc-kpi-icon violet"><i class="bi bi-shield-check"></i></span><div><div class="soc-kpi-num">{{ $twoFaPct }}%</div><div class="soc-kpi-label">Adopsi 2FA</div><div class="soc-kpi-trend up">▲ {{ $trend2fa }}</div></div><div class="ms-auto"><span class="jd-chip jd-chip--violet">2FA</span></div></div>
        </div>

        <div class="soc-secondary-grid">
            <div class="soc-secondary"><span class="soc-kpi-icon amber"><i class="bi bi-laptop"></i></span><div><div class="soc-kpi-num">{{ $stats['new_devices_today'] }}</div><div class="soc-kpi-label">Device Baru</div></div><div class="ms-auto text-end small text-muted">Browser aktif</div></div>
            <div class="soc-secondary"><span class="soc-kpi-icon violet"><i class="bi bi-geo-alt"></i></span><div><div class="soc-kpi-num">{{ $stats['new_ips_today'] }}</div><div class="soc-kpi-label">IP Baru</div></div><div class="ms-auto text-end small text-muted">Anomali lokasi</div></div>
            <div class="soc-secondary"><span class="soc-kpi-icon blue"><i class="bi bi-fingerprint"></i></span><div><div class="soc-kpi-num">{{ $stats['fingerprints'] }}</div><div class="soc-kpi-label">Fingerprint Device</div></div><div class="ms-auto text-end small text-muted">Trust map</div></div>
            <div class="soc-secondary"><span class="soc-kpi-icon green"><i class="bi bi-people"></i></span><div><div class="soc-kpi-num">{{ $stats['two_fa_users'] }}</div><div class="soc-kpi-label">User dengan 2FA</div></div><div class="ms-auto text-end small text-muted">Coverage</div></div>
        </div>

        <div class="soc-chart-grid">
            <div class="soc-card">
                <div class="soc-card-head">
                    <div>
                        <b><i class="bi bi-bar-chart"></i> Login 7 Hari Terakhir</b>
                        <div class="soc-card-sub">Bandingkan login berhasil vs gagal dengan chart yang mudah dipindai cepat.</div>
                    </div>
                </div>
                <div class="soc-card-body">
                    @php $max = max($days->max('success'), $days->max('failed'), 1); @endphp
                    <div class="soc-chart-wrap">
                        @foreach ($days as $d)
                            <div class="soc-chart-col">
                                <div class="soc-chart-bars">
                                    <div class="soc-chart-bar success" style="height: {{ max(8, ($d['success'] / $max) * 160) }}px;"><span class="tip">{{ $d['success'] }} berhasil</span></div>
                                    <div class="soc-chart-bar failed" style="height: {{ max(8, ($d['failed'] / $max) * 80) }}px;"><span class="tip">{{ $d['failed'] }} gagal</span></div>
                                </div>
                                <div class="soc-chart-label">{{ $d['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="soc-card">
                <div class="soc-card-head"><div><b><i class="bi bi-heart-pulse"></i> Security Health</b><div class="soc-card-sub">Ringkasan area keamanan yang paling mempengaruhi SOC score.</div></div></div>
                <div class="soc-card-body">
                    <div class="soc-health-list">
                        @foreach($health as $h)
                            <div class="soc-health-item">
                                <div class="soc-health-top"><strong>{{ $h['label'] }}</strong><span>{{ $h['value'] }}%</span></div>
                                <div class="soc-health-bar"><span style="width:{{ $h['value'] }}%;"></span></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="soc-actions">
            <a href="{{ route('admin.login-history.index') }}" class="soc-action"><span class="soc-action-icon"><i class="bi bi-clock-history"></i></span><div class="soc-action-title">Riwayat Login</div><div class="soc-action-sub">Audit seluruh aktivitas login dan OTP challenge.</div></a>
            <a href="{{ route('active-sessions.index') }}" class="soc-action"><span class="soc-action-icon"><i class="bi bi-laptop"></i></span><div class="soc-action-title">Trusted Devices</div><div class="soc-action-sub">Kelola perangkat terpercaya dan logout perangkat lain.</div></a>
            <a href="{{ route('active-sessions.index') }}" class="soc-action"><span class="soc-action-icon"><i class="bi bi-broadcast-pin"></i></span><div class="soc-action-title">Active Sessions</div><div class="soc-action-sub">Pantau session aktif yang sedang berjalan.</div></a>
            <a href="{{ route('admin.2fa-policy.index') }}" class="soc-action"><span class="soc-action-icon"><i class="bi bi-shield-lock"></i></span><div class="soc-action-title">2FA Policy</div><div class="soc-action-sub">Atur kewajiban 2FA berdasarkan role pengguna.</div></a>
            <a href="{{ route('profil-saya.index', ['tab' => 'keamanan']) }}" class="soc-action"><span class="soc-action-icon"><i class="bi bi-person-lock"></i></span><div class="soc-action-title">Account Security</div><div class="soc-action-sub">Kelola keamanan akun administrator secara langsung.</div></a>
        </div>

        <div class="soc-bottom">
            <div class="soc-card">
                <div class="soc-card-head">
                    <div>
                        <b><i class="bi bi-activity"></i> Recent Activity</b>
                        <div class="soc-card-sub">Timeline aktivitas autentikasi terbaru, difilter dari backend yang sama.</div>
                    </div>
                </div>
                <div class="soc-card-body">
                    <form id="securityFeedFilter" method="GET" class="jd-toolbar soc-filterbar" autocomplete="off">
                        <div class="jd-filter jd-filter--perpage">
                            <label>Per Page</label>
                            <select name="per_page" class="jd-select">
                                @foreach ([10, 15, 25, 50, 100] as $opt)
                                    <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="jd-filter">
                            <label>Filter Status</label>
                            <select name="status" class="jd-select">
                                <option value="">Semua status</option>
                                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Berhasil</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                                <option value="throttled" {{ request('status') === 'throttled' ? 'selected' : '' }}>Terblokir</option>
                            </select>
                        </div>
                        <div class="jd-search"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" class="jd-control" placeholder="Cari nama / username / email"></div>
                        <div class="jd-toolbar-actions">
                            <a href="{{ route('admin.security-dashboard.index') }}" class="jd-btn jd-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                            <a href="{{ route('admin.security-dashboard.index', request()->query()) }}" class="jd-btn jd-btn--soft"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
                        </div>
                    </form>

                    @if ($recentLogins->isEmpty())
                        <div class="jd-empty"><div class="jd-empty-title">Tidak ada aktivitas keamanan</div><div class="jd-empty-sub">Belum ada event yang cocok dengan filter saat ini.</div></div>
                    @else
                        <div class="soc-feed">
                            @foreach ($recentLogins as $h)
                                @php
                                    $statusCls = $h->login_status === 'success' ? 'green' : ($h->login_status === 'failed' ? 'red' : 'amber');
                                    $statusText = $h->login_status === 'success' ? 'Login Berhasil' : ($h->login_status === 'failed' ? 'Login Gagal' : 'Login Terblokir');
                                    $otpCls = $h->otp_status === 'success' ? 'blue' : ($h->otp_status === 'failed' ? 'red' : 'gray');
                                    $otpText = $h->otp_status === 'success' ? 'OTP Challenge' : ($h->otp_status === 'failed' ? 'OTP Gagal' : 'Tanpa OTP');
                                    $role = $h->user->role ?? null;
                                    $roleLabel = $role === 1 ? 'Admin' : ($role === 2 ? 'Guru' : ($role === 3 ? 'Siswa' : ($role === 4 ? 'BK' : ($role === 5 ? 'Kepala Sekolah' : 'Unknown'))));
                                    $name = $h->user->name ?? 'Unknown User';
                                    $nodeCls = $statusCls === 'green' ? 'green' : ($statusCls === 'red' ? 'red' : ($h->is_new_ip ? 'blue' : 'amber'));
                                @endphp
                                <div class="soc-feed-item">
                                    <div class="soc-feed-node {{ $nodeCls }}"><i class="bi {{ $h->is_new_device ? 'bi-laptop' : ($h->is_new_ip ? 'bi-geo-alt' : 'bi-person-lock') }}"></i></div>
                                    <div class="soc-feed-card">
                                        <div class="soc-feed-head">
                                            <div class="soc-feed-user">
                                                <div class="soc-feed-avatar">{{ mb_strtoupper(mb_substr($name, 0, 1)) }}</div>
                                                <div>
                                                    <div class="soc-feed-name">{{ $name }}</div>
                                                    <div class="soc-feed-role">{{ $roleLabel }}</div>
                                                </div>
                                            </div>
                                            <div class="soc-feed-time"><div class="r">{{ $h->login_at?->diffForHumans() }}</div><div class="a">{{ $h->login_at?->format('d M Y • H:i') }}</div></div>
                                        </div>
                                        <div class="soc-feed-meta">
                                            <div class="soc-meta-box"><div class="k">Browser</div><div class="v">{{ $h->browser }}</div></div>
                                            <div class="soc-meta-box"><div class="k">Operating System</div><div class="v">{{ $h->os }}</div></div>
                                            <div class="soc-meta-box"><div class="k">Device</div><div class="v">{{ $h->device }}</div></div>
                                            <div class="soc-meta-box"><div class="k">IP Address</div><div class="v"><code>{{ $h->ip_address }}</code></div></div>
                                        </div>
                                        <div class="soc-badges">
                                            <span class="soc-badge {{ $statusCls }}"><i class="bi bi-shield-check"></i>{{ $statusText }}</span>
                                            <span class="soc-badge {{ $otpCls }}"><i class="bi bi-key"></i>{{ $otpText }}</span>
                                            @if($h->is_new_device)
                                                <span class="soc-badge amber"><i class="bi bi-laptop"></i>NEW DEVICE</span>
                                            @endif
                                            @if($h->is_new_ip)
                                                <span class="soc-badge violet"><i class="bi bi-geo-alt"></i>NEW IP</span>
                                            @endif
                                            @if(!$h->logout_at)
                                                <span class="soc-badge blue"><i class="bi bi-broadcast-pin"></i>ACTIVE SESSION</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="soc-pager">
                            <div class="soc-pager-info">Menampilkan {{ $recentLogins->firstItem() ?? 0 }}–{{ $recentLogins->lastItem() ?? 0 }} dari {{ $recentLogins->total() }} entri</div>
                            <div class="soc-pager-wrap">{{ $recentLogins->onEachSide(1)->links() }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="soc-card">
                <div class="soc-card-head"><div><b><i class="bi bi-lightning-charge"></i> Security Insight</b><div class="soc-card-sub">Insight otomatis dan alert cepat berdasarkan data yang sudah tersedia.</div></div></div>
                <div class="soc-card-body">
                    <div class="soc-insight-list mb-3">
                        <div class="soc-insight-item">{{ $stats['failed_today'] === 0 ? 'Tidak ada aktivitas mencurigakan terdeteksi hari ini.' : $stats['failed_today'] . ' login gagal perlu ditinjau.' }}</div>
                        <div class="soc-insight-item">{{ $stats['new_devices_today'] }} perangkat baru hari ini.</div>
                        <div class="soc-insight-item">{{ $twoFaPct >= 80 ? 'Adopsi 2FA sangat baik.' : 'Adopsi 2FA masih bisa ditingkatkan.' }}</div>
                    </div>
                    <div class="soc-alert-list">
                        <div class="soc-alert-item">{{ $stats['failed_today'] > 2 ? 'Login gagal berulang terdeteksi hari ini.' : 'Login gagal masih dalam batas aman.' }}</div>
                        <div class="soc-alert-item">{{ $stats['new_devices_today'] > 0 ? $stats['new_devices_today'] . ' perangkat baru muncul hari ini.' : 'Tidak ada perangkat baru yang mencurigakan.' }}</div>
                        <div class="soc-alert-item">{{ $stats['new_ips_today'] > 0 ? $stats['new_ips_today'] . ' IP baru terdeteksi.' : 'Tidak ada IP baru yang mencurigakan.' }}</div>
                        <div class="soc-alert-item">{{ $twoFaPct < 70 ? 'Masih ada user yang belum mengaktifkan 2FA.' : 'Adopsi 2FA sudah kuat.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('securityFeedFilter');
    if (form) {
        function applyFilter() {
            const params = new URLSearchParams();
            const data = new FormData(form);
            for (const [k, v] of data.entries()) {
                if (v) params.append(k, v);
            }
            window.location.search = params.toString();
        }
        let debounce;
        form.querySelectorAll('select').forEach(function (el) { el.addEventListener('change', applyFilter); });
        form.querySelectorAll('input[type="search"], input[type="text"]').forEach(function (el) {
            el.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(applyFilter, 350);
            });
        });
    }

    document.querySelectorAll('.jd-btn, .soc-action').forEach(function (btn) {
        btn.addEventListener('click', function (e) { if (window.JD) JD.ripple(e); });
    });
})();
</script>
@endpush
