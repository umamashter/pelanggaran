@extends('layouts.main')
@section('title', 'Dashboard Keamanan')

@section('content')
@include('component.admin.ms-style')
<style>
    /* ---- Filter pill — model sama dengan login-history / anggota-kelompok ---- */
    .filter-lomba-wrap { position: relative; }
    .filter-lomba-wrap .form-select {
        height: 34px; border-radius: 18px; border: 1.5px solid #e2e8f0;
        font-size: 12px; padding: 0 30px 0 34px; background-color: #f8fafc;
        color: #475569; min-width: 150px; cursor: pointer; transition: all .25s;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center; background-size: 12px;
    }
    .filter-lomba-wrap .form-select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .filter-lomba-wrap .filter-icon-prepend { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px; pointer-events: none; z-index: 1; }
    .filter-lomba-wrap .form-select:hover { border-color: #cbd5e1; background-color: #fff; }

    .search-pill {
        height: 34px; border: 1.5px solid #e2e8f0; border-radius: 18px;
        font-size: 12px; padding: 0 16px 0 34px; background-color: #f8fafc;
        color: #475569; min-width: 240px; transition: all .25s; outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: 12px center; background-size: 14px;
    }
    .search-pill:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .search-pill::placeholder { color: #94a3b8; }

    .dt-toolbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 14px; }
    .dt-left, .dt-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .dt-length-group { display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#64748b; }

    .login-hist-table thead th { text-align:left; }
    .login-hist-table thead th:first-child,
    .login-hist-table tbody td:first-child { width:auto; text-align:left; }

    .pagination-ms { display:flex; justify-content:flex-end; }
    .pagination-ms .pagination { margin:0; gap:4px; }
    .pagination-ms .page-link {
        min-width:34px; height:34px; padding:0 10px; border-radius:8px;
        font-size:13px; font-weight:500; line-height:32px;
        color:#475569; background:#fff; border:1px solid var(--ms-border); box-shadow:none;
    }
    .pagination-ms .page-link:hover { border-color:var(--ms-primary); color:var(--ms-primary); background:var(--ms-primary-light); }
    .pagination-ms .page-item.active .page-link { background:var(--ms-primary); border-color:var(--ms-primary); color:#fff; box-shadow:0 2px 6px rgba(22,163,74,.25); }
    .pagination-ms .page-item.disabled .page-link { opacity:.4; background:#f8fafc; }

    @media (max-width: 768px) {
        .dt-toolbar { justify-content:flex-start; }
        .search-pill { min-width:100%; flex:1 1 100%; }
    }

    /* ── Glass Stat Cards (matching Haflatul Imtihan) ── */
    .glass-stat {
        position: relative;
        border-radius: 20px;
        padding: 20px 22px;
        background: linear-gradient(145deg, rgba(255,255,255,0.55) 0%, rgba(248,250,252,0.30) 100%);
        border: 1px solid rgba(255,255,255,0.70);
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        box-shadow:
            0 1px 2px rgba(0,0,0,0.03),
            0 4px 12px rgba(0,0,0,0.04),
            0 12px 32px -8px rgba(0,0,0,0.06),
            inset 0 1px 0 rgba(255,255,255,0.80);
        transition: all .4s cubic-bezier(.2,.8,.2,1);
        display: flex;
        align-items: flex-start;
        gap: 16px;
        overflow: hidden;
        height: 100%;
    }
    .glass-stat::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        padding: 1px;
        background: linear-gradient(160deg, rgba(255,255,255,0.95), rgba(255,255,255,0.20) 40%, transparent 70%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .glass-stat::after {
        content: '';
        position: absolute;
        top: -60%;
        right: -20%;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        opacity: 0.6;
    }
    .glass-stat:hover {
        transform: translateY(-4px);
        box-shadow:
            0 1px 2px rgba(0,0,0,0.03),
            0 8px 24px rgba(0,0,0,0.06),
            0 20px 48px -12px rgba(0,0,0,0.10),
            inset 0 1px 0 rgba(255,255,255,0.90);
        border-color: rgba(255,255,255,0.85);
    }

    .gs-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        z-index: 1;
    }
    .gs-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(145deg, rgba(255,255,255,0.90), rgba(255,255,255,0.40));
        box-shadow:
            0 2px 8px rgba(0,0,0,0.06),
            0 8px 24px -4px rgba(0,0,0,0.08),
            inset 0 1px 0 rgba(255,255,255,0.95),
            inset 0 -1px 0 rgba(0,0,0,0.04);
    }
    .gs-icon svg {
        width: 24px;
        height: 24px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));
    }

    .gs-chart {
        display: flex;
        align-items: flex-end;
        gap: 3px;
        height: 22px;
        z-index: 1;
    }
    .gs-chart .bar {
        width: 4px;
        border-radius: 3px 3px 1px 1px;
        transition: height .6s cubic-bezier(.2,.8,.2,1);
        position: relative;
    }
    .gs-chart .bar::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: inherit;
        filter: blur(4px);
        opacity: 0.5;
        transform: scaleY(1.4) scaleX(1.6);
        z-index: -1;
    }

    .gs-body {
        display: flex;
        flex-direction: column;
        flex: 1;
        z-index: 1;
    }
    .gs-body .gs-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -.5px;
    }
    .gs-body .gs-label {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 4px;
    }

    /* Per-card accent colors */
    .glass-stat:nth-child(1) .gs-label { color: #16a34a; }
    .glass-stat:nth-child(1)::after { background: radial-gradient(circle, rgba(22,163,74,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(1) .gs-chart .bar { background: linear-gradient(to top, #16a34a, #86efac); }

    .glass-stat:nth-child(2) .gs-label { color: #dc2626; }
    .glass-stat:nth-child(2)::after { background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(2) .gs-chart .bar { background: linear-gradient(to top, #dc2626, #fca5a5); }

    .glass-stat:nth-child(3) .gs-label { color: #2563eb; }
    .glass-stat:nth-child(3)::after { background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(3) .gs-chart .bar { background: linear-gradient(to top, #2563eb, #93c5fd); }

    .glass-stat:nth-child(4) .gs-label { color: #6366f1; }
    .glass-stat:nth-child(4)::after { background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%); }
    .glass-stat:nth-child(4) .gs-chart .bar { background: linear-gradient(to top, #6366f1, #a5b4fc); }

    html.dark-mode .glass-stat {
        background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 100%);
        border-color: rgba(255,255,255,0.10);
        box-shadow:
            0 1px 0 rgba(255,255,255,0.04) inset,
            0 4px 16px rgba(0,0,0,0.20),
            0 12px 32px -8px rgba(0,0,0,0.30),
            inset 0 1px 0 rgba(255,255,255,0.06);
    }
    html.dark-mode .glass-stat::before {
        background: linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03) 40%, transparent 70%);
    }
    html.dark-mode .glass-stat:hover {
        border-color: rgba(255,255,255,0.18);
        box-shadow:
            0 1px 0 rgba(255,255,255,0.06) inset,
            0 8px 24px rgba(0,0,0,0.25),
            0 24px 56px -12px rgba(0,0,0,0.35),
            inset 0 1px 0 rgba(255,255,255,0.08);
    }
    html.dark-mode .gs-icon {
        background: linear-gradient(145deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)) !important;
        box-shadow:
            0 2px 8px rgba(0,0,0,0.20),
            0 8px 24px -4px rgba(0,0,0,0.15),
            inset 0 1px 0 rgba(255,255,255,0.10) !important;
    }
    html.dark-mode .gs-icon svg { filter: drop-shadow(0 2px 6px rgba(0,0,0,0.30)); }
    html.dark-mode .gs-body .gs-number { color: #f1f5f9; }
    html.dark-mode .gs-body .gs-label { opacity: 0.85; }

    /* ── Glass Card (for chart & side stats) ── */
    .glass-card {
        position: relative;
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(255,255,255,0.55) 0%, rgba(248,250,252,0.30) 100%);
        border: 1px solid rgba(255,255,255,0.70);
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        box-shadow:
            0 1px 2px rgba(0,0,0,0.03),
            0 4px 12px rgba(0,0,0,0.04),
            0 12px 32px -8px rgba(0,0,0,0.06),
            inset 0 1px 0 rgba(255,255,255,0.80);
        overflow: hidden;
    }
    .glass-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        padding: 1px;
        background: linear-gradient(160deg, rgba(255,255,255,0.95), rgba(255,255,255,0.20) 40%, transparent 70%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .gc-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px 0;
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
        position: relative;
        z-index: 1;
    }
    .gc-body {
        padding: 14px 20px 20px;
        position: relative;
        z-index: 1;
    }

    /* ── Chart 7 Hari ── */
    .chart-7d-wrap {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        height: 200px;
        padding: 0 4px;
    }
    .chart-7d-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .chart-7d-bars {
        flex: 1;
        display: flex;
        flex-direction: column-reverse;
        align-items: center;
        width: 100%;
        gap: 3px;
        position: relative;
    }
    .c7d-bar {
        width: 100%;
        max-width: 28px;
        border-radius: 5px 5px 2px 2px;
        min-height: 3px;
        transition: height .5s cubic-bezier(.2,.8,.2,1), opacity .3s;
        position: relative;
        cursor: default;
    }
    .c7d-bar:hover { opacity: .8; }
    .c7d-bar .c7d-tooltip {
        position: absolute;
        top: -24px;
        left: 50%;
        transform: translateX(-50%);
        background: #0f172a;
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        opacity: 0;
        transition: opacity .2s;
        white-space: nowrap;
        pointer-events: none;
    }
    .c7d-bar:hover .c7d-tooltip { opacity: 1; }
    .c7d-success {
        background: linear-gradient(to top, #16a34a, #86efac);
        box-shadow: 0 0 12px rgba(22,163,74,0.25);
    }
    .c7d-failed {
        background: linear-gradient(to top, #dc2626, #fca5a5);
        box-shadow: 0 0 12px rgba(220,38,38,0.25);
    }
    .c7d-label {
        font-size: 10px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .c7d-legend {
        display: flex;
        gap: 18px;
        justify-content: center;
        margin-top: 12px;
        font-size: 11px;
        font-weight: 500;
        color: #475569;
    }
    .c7d-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: middle;
    }

    /* ── Side stats ── */
    .ss-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .ss-label {
        font-size: 13px;
        color: #475569;
    }
    .ss-value {
        font-weight: 600;
        color: #0f172a;
        font-size: 14px;
    }
    .ss-badge {
        font-size: 13px;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 20px;
    }
    .ss-badge.success { background: rgba(22,163,74,0.10); color: #16a34a; }
    .ss-badge.warning { background: rgba(245,158,11,0.10); color: #d97706; }
    .ss-badge.info    { background: rgba(37,99,235,0.10); color: #2563eb; }

    html.dark-mode .glass-card {
        background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 100%);
        border-color: rgba(255,255,255,0.10);
        box-shadow:
            0 1px 0 rgba(255,255,255,0.04) inset,
            0 4px 16px rgba(0,0,0,0.20),
            0 12px 32px -8px rgba(0,0,0,0.30),
            inset 0 1px 0 rgba(255,255,255,0.06);
    }
    html.dark-mode .glass-card::before {
        background: linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03) 40%, transparent 70%);
    }
    html.dark-mode .gc-header { color: #f1f5f9; }
    html.dark-mode .c7d-label { color: #94a3b8; }
    html.dark-mode .c7d-legend { color: #94a3b8; }
    html.dark-mode .ss-row { border-color: rgba(255,255,255,0.06); }
    html.dark-mode .ss-label { color: #94a3b8; }
    html.dark-mode .ss-value { color: #f1f5f9; }
</style>

<div class="master-siswa-page">

    {{-- Header --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-shield-halved"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Dashboard Keamanan</h4>
                    <span style="font-size: 13px; color: #64748b;">Ringkasan aktivitas autentikasi &amp; ancaman</span>
                </div>
            </div>
            <a href="{{ route('admin.login-history.index') }}" class="btn btn-header-ms btn-tambah-ms">
                <i class="fas fa-list"></i> Lihat Semua Riwayat
            </a>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="glass-stat">
                <div class="gs-left">
                    <div class="gs-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                    </div>
                    <div class="gs-chart">
                        <div class="bar" style="height:10px"></div>
                        <div class="bar" style="height:18px"></div>
                        <div class="bar" style="height:7px"></div>
                        <div class="bar" style="height:22px"></div>
                        <div class="bar" style="height:14px"></div>
                    </div>
                </div>
                <div class="gs-body">
                    <span class="gs-number">{{ $stats['logins_today'] }}</span>
                    <span class="gs-label">Login Berhasil Hari Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="glass-stat">
                <div class="gs-left">
                    <div class="gs-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="gs-chart">
                        <div class="bar" style="height:14px"></div>
                        <div class="bar" style="height:8px"></div>
                        <div class="bar" style="height:20px"></div>
                        <div class="bar" style="height:12px"></div>
                        <div class="bar" style="height:18px"></div>
                    </div>
                </div>
                <div class="gs-body">
                    <span class="gs-number">{{ $stats['failed_today'] }}</span>
                    <span class="gs-label">Login Gagal Hari Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="glass-stat">
                <div class="gs-left">
                    <div class="gs-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <div class="gs-chart">
                        <div class="bar" style="height:5px"></div>
                        <div class="bar" style="height:16px"></div>
                        <div class="bar" style="height:22px"></div>
                        <div class="bar" style="height:10px"></div>
                        <div class="bar" style="height:13px"></div>
                    </div>
                </div>
                <div class="gs-body">
                    <span class="gs-number">{{ $stats['active_sessions'] }}</span>
                    <span class="gs-label">Sesi Aktif</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="glass-stat">
                <div class="gs-left">
                    <div class="gs-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="gs-chart">
                        <div class="bar" style="height:12px"></div>
                        <div class="bar" style="height:20px"></div>
                        <div class="bar" style="height:6px"></div>
                        <div class="bar" style="height:16px"></div>
                        <div class="bar" style="height:10px"></div>
                    </div>
                </div>
                <div class="gs-body">
                    <span class="gs-number">{{ $twoFaPct }}%</span>
                    <span class="gs-label">2FA Adopsi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Chart 7 hari --}}
        <div class="col-lg-8">
            <div class="glass-card h-100">
                <div class="gc-header">
                    <i class="fas fa-chart-column" style="color:#2563eb;"></i>
                    <span>Login 7 Hari Terakhir</span>
                </div>
                <div class="gc-body">
                    <div class="chart-7d-wrap">
                        @php($max = max($days->max('success'), $days->max('failed'), 1))
                        @foreach ($days as $d)
                            <div class="chart-7d-col">
                                <div class="chart-7d-bars">
                                    <div class="c7d-bar c7d-success" style="height:{{ ($d['success']/$max)*160 }}px;" title="Berhasil: {{ $d['success'] }}">
                                        <span class="c7d-tooltip">{{ $d['success'] }}</span>
                                    </div>
                                    <div class="c7d-bar c7d-failed" style="height:{{ ($d['failed']/$max)*70 }}px;" title="Gagal: {{ $d['failed'] }}">
                                        <span class="c7d-tooltip">{{ $d['failed'] }}</span>
                                    </div>
                                </div>
                                <span class="c7d-label">{{ $d['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="c7d-legend">
                        <span><span class="c7d-dot" style="background:#16a34a;box-shadow:0 0 8px rgba(22,163,74,0.5);"></span>Berhasil</span>
                        <span><span class="c7d-dot" style="background:#dc2626;box-shadow:0 0 8px rgba(220,38,38,0.5);"></span>Gagal</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side stats --}}
        <div class="col-lg-4">
            <div class="glass-card h-100">
                <div class="gc-header">
                    <i class="fas fa-chart-pie" style="color:#16a34a;"></i>
                    <span>Statistik Tambahan</span>
                </div>
                <div class="gc-body">
                    <div class="ss-row">
                        <span class="ss-label">Perangkat baru hari ini</span>
                        <span class="ss-badge warning">{{ $stats['new_devices_today'] }}</span>
                    </div>
                    <div class="ss-row">
                        <span class="ss-label">IP baru hari ini</span>
                        <span class="ss-badge info">{{ $stats['new_ips_today'] }}</span>
                    </div>
                    <div class="ss-row">
                        <span class="ss-label">Total pengguna 2FA</span>
                        <span class="ss-value">{{ $stats['two_fa_users'] }} / {{ $stats['total_users'] }}</span>
                    </div>
                    <div class="ss-row">
                        <span class="ss-label">Fingerprint terdaftar</span>
                        <span class="ss-value">{{ $stats['fingerprints'] }}</span>
                    </div>
                    <div class="ss-row" style="border:none;">
                        <span class="ss-label">Login berhasil hari ini</span>
                        <span class="ss-badge success">{{ $stats['logins_today'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity feed --}}
    <div class="card table-card">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="color: var(--ms-text);">
                <i class="fas fa-rss me-1 text-info"></i> Aktivitas Terbaru
            </h6>

            {{-- Filter otomatis gaya DataTables, kontrol pill --}}
            <form id="securityFeedFilter" method="GET" class="dt-toolbar" autocomplete="off">
                <div class="dt-left">
                    <div class="dt-length-group">
                        <span>Tampilkan</span>
                        <div class="filter-lomba-wrap" style="min-width:80px;">
                            <i class="fas fa-list-ol filter-icon-prepend"></i>
                            <select name="per_page" class="form-select">
                                @foreach ([10, 15, 25, 50, 100] as $opt)
                                    <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span>entri</span>
                    </div>
                    <div class="filter-lomba-wrap" style="min-width:150px;">
                        <i class="fas fa-filter filter-icon-prepend"></i>
                        <select name="status" class="form-select">
                            <option value="">Semua status</option>
                            <option value="success" @if(request('status')==='success') selected @endif>Berhasil</option>
                            <option value="failed" @if(request('status')==='failed') selected @endif>Gagal</option>
                            <option value="throttled" @if(request('status')==='throttled') selected @endif>Terblokir</option>
                        </select>
                    </div>
                </div>
                <div class="dt-right">
                    <input type="search" name="search" value="{{ request('search') }}" class="search-pill" placeholder="Cari nama / username / email">
                </div>
            </form>

            @if ($recentLogins->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.4;"></i>
                    Tidak ada aktivitas yang cocok.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-ms login-hist-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Status</th>
                            <th>OTP</th>
                            <th>Perangkat</th>
                            <th>IP</th>
                            <th>Flag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentLogins as $h)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $h->login_at?->format('d M, H:i') }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $h->login_at?->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if ($h->user)
                                    <div class="fw-semibold">{{ $h->user->name }}</div>
                                    <div class="text-muted" style="font-size:11px;">@ {{ $h->user->username }}</div>
                                @else
                                    <span class="text-danger fst-italic" style="font-size:11px;">Tidak dikenal</span>
                                @endif
                            </td>
                            <td>{!! $h->login_status_badge !!}</td>
                            <td>{!! $h->otp_status_badge !!}</td>
                            <td>
                                <span class="fw-semibold">{{ $h->browser }}</span>
                                <span class="text-muted d-block" style="font-size:11px;">{{ $h->os }} · {{ $h->device }}</span>
                            </td>
                            <td><code>{{ $h->ip_address }}</code></td>
                            <td>
                                @if ($h->is_new_device)
                                    <span class="badge bg-warning-subtle text-warning" style="font-size:10px;"><i class="fas fa-mobile-screen me-1"></i>Device Baru</span>
                                @endif
                                @if ($h->is_new_ip)
                                    <span class="badge bg-info-subtle text-info" style="font-size:10px;"><i class="fas fa-location-dot me-1"></i>IP Baru</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
                <span class="text-muted" style="font-size:13px;">
                    Menampilkan {{ $recentLogins->firstItem() ?? 0 }}–{{ $recentLogins->lastItem() ?? 0 }} dari {{ $recentLogins->total() }} entri
                </span>
                <div class="pagination-ms">
                    {{ $recentLogins->onEachSide(1)->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const form = document.getElementById('securityFeedFilter');
    if (!form) return;

    function applyFilter() {
        const params = new URLSearchParams();
        const data = new FormData(form);
        for (const [k, v] of data.entries()) {
            if (v) params.append(k, v);
        }
        window.location.search = params.toString();
    }

    let debounce;
    form.querySelectorAll('select').forEach(function (el) {
        el.addEventListener('change', applyFilter);
    });
    form.querySelectorAll('input[type="search"], input[type="text"]').forEach(function (el) {
        el.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(applyFilter, 350);
        });
    });
})();
</script>
@endpush
@endsection
