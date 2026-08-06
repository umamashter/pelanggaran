@extends('layouts.main')
@section('title', 'Master Siswa')
@push('css')
<style>
    @include('component.admin.ms-style')
</style>
<style>
    .l-header .c-header-icon.lol.logo {
        display: none !important;
    }

    @media (min-width: 769px) {
        .l-header .js-hamburger {
            display: none !important;
        }
    }

    .smc-wrap { --smc-radius: 16px; }
    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 36px;
        padding: 0 8px;
        font-size: 10px;
        border-radius: 8px;
        gap: 3px;
    }

    .btn-header-ms.btn-simpan-ms.btn-compact i {
        font-size: 10px;
    }

    .smc-alert { display:flex; align-items:center; gap:12px; border-radius:14px; padding:13px 16px; font-size:13px; font-weight:600; margin-bottom:18px; border:1px solid var(--ms-border); background:#fff; box-shadow:0 10px 24px -18px rgba(15,23,42,.18); }
    .smc-alert.success { border-color: rgba(22,163,74,.25); background:#f0fdf4; color:#15803d; }
    .smc-alert.error { border-color: rgba(220,38,38,.2); background:#fef2f2; color:#b91c1c; }
    .smc-hero { position:relative; overflow:hidden; background:linear-gradient(135deg, #2563eb 0%, #1d4ed8 55%, #60a5fa 100%); color:#fff; border-radius:24px; padding:26px 28px; margin-bottom:20px; box-shadow:0 24px 55px -24px rgba(37,99,235,.42); }
    .smc-hero::before { content:""; position:absolute; inset:0; background-image:radial-gradient(rgba(255,255,255,.12) 1px, transparent 1px); background-size:22px 22px; opacity:.35; }
    .smc-hero::after { content:""; position:absolute; right:-90px; top:-100px; width:280px; height:280px; border-radius:50%; background:rgba(255,255,255,.10); }
    .smc-hero-grid { position:relative; display:flex; flex-wrap:wrap; gap:20px; justify-content:space-between; align-items:flex-start; }
    .smc-hero-left { display:flex; gap:16px; align-items:flex-start; min-width:0; }
    .smc-hero-icon { width:58px; height:58px; border-radius:18px; display:inline-flex; align-items:center; justify-content:center; background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.24); backdrop-filter:blur(10px); font-size:24px; flex-shrink:0; }
    .smc-hero-title { font-size:24px; font-weight:800; margin:0 0 4px; letter-spacing:-.4px; }
    .smc-hero-sub { margin:0; font-size:13px; line-height:1.55; max-width:640px; opacity:.9; }
    .smc-hero-badges { display:flex; flex-wrap:wrap; gap:8px; margin-top:12px; }
    .smc-hero-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.24); font-size:11.5px; font-weight:700; }
    .smc-kpi-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px; }
    .smc-kpi { position:relative; overflow:hidden; background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:18px 20px; box-shadow:0 14px 34px -26px rgba(15,23,42,.18); display:flex; align-items:center; gap:14px; transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
    .smc-kpi:hover { transform:translateY(-3px); box-shadow:0 24px 46px -26px rgba(15,23,42,.2); border-color:rgba(37,99,235,.18); }
    .smc-kpi-icon { width:46px; height:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:19px; flex-shrink:0; }
    .smc-kpi-icon.blue { background:#eff6ff; color:#2563eb; }
    .smc-kpi-icon.green { background:#f0fdf4; color:#16a34a; }
    .smc-kpi-icon.amber { background:#fffbeb; color:#d97706; }
    .smc-kpi-icon.violet { background:#f5f3ff; color:#7c3aed; }
    .smc-kpi-num { font-size:24px; font-weight:800; letter-spacing:-.5px; color:var(--ms-text); line-height:1.1; }
    .smc-kpi-label { font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#94a3b8; font-weight:700; margin-top:2px; }
    .smc-action-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:12px; margin-bottom:20px; }
    .smc-action-card { height: 42px; border: 1px solid rgba(255,255,255,.22); border-radius: 12px; background: rgba(255,255,255,.14); box-shadow: 0 12px 28px -18px rgba(15,23,42,.22); padding: 0 14px; text-decoration: none; color: #fff; transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, background .22s ease; display: inline-flex; align-items: center; gap: 8px; backdrop-filter: blur(12px); font-size: 12px; font-weight: 700; white-space: nowrap; }
    .smc-action-card:hover { transform: translateY(-2px); box-shadow: 0 18px 34px -18px rgba(15,23,42,.22); border-color: rgba(255,255,255,.4); background: rgba(255,255,255,.20); color: #fff; }
    .smc-action-card.is-disabled { opacity:.58; pointer-events:none; }
    .smc-action-icon { width: 28px; height: 28px; border-radius: 9px; display:flex; align-items:center; justify-content:center; font-size: 13px; background: rgba(255,255,255,.18); color:#fff; box-shadow: inset 0 1px 0 rgba(255,255,255,.35); }
    .smc-action-icon.green, .smc-action-icon.blue, .smc-action-icon.amber, .smc-action-icon.violet { background: rgba(255,255,255,.18); color:#fff; }
    .smc-action-title { font-size:12px; font-weight:800; color:#fff; }
    .smc-action-sub { display:none; }
    .smc-toolbar { position:sticky; top:78px; z-index:940; display:flex; flex-wrap:wrap; align-items:end; justify-content:space-between; gap:12px; background:rgba(255,255,255,.9); border:1px solid #e2e8f0; border-radius:18px; padding:14px 16px; box-shadow:0 12px 28px -24px rgba(15,23,42,.18); backdrop-filter:blur(12px); margin-bottom:18px; }
    .smc-toolbar-left, .smc-toolbar-right { display:flex; flex-wrap:wrap; align-items:end; gap:10px; }
    .smc-filter { display:flex; flex-direction:column; gap:5px; min-width:160px; }
    .smc-filter label { font-size:10.5px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; }
    .smc-grid-card { background:#fff; border:1px solid #e2e8f0; border-radius:20px; box-shadow:0 16px 38px -30px rgba(15,23,42,.18); overflow:hidden; }
    .smc-grid-head { padding:18px 20px 12px; display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap; }
    .smc-grid-head h5 { margin:0; font-size:17px; font-weight:800; color:var(--ms-text); }
    .smc-grid-head p { margin:4px 0 0; font-size:12px; color:var(--ms-text-soft); }
    .smc-student { display:flex; align-items:center; gap:12px; }
    .smc-student-avatar { width:42px; height:42px; border-radius:14px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#0f766e,#22c55e); color:#fff; font-size:15px; font-weight:800; flex-shrink:0; }
    .smc-student-name { font-size:13.5px; font-weight:800; color:var(--ms-text); }
    .smc-student-nisn { font-size:11px; color:#94a3b8; margin-top:2px; }
    .smc-pill { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; font-size:10.5px; font-weight:700; border:1px solid transparent; white-space:nowrap; }
    .smc-pill.class { background:#eff6ff; color:#2563eb; border-color:rgba(37,99,235,.16); }
    .smc-pill.level { background:#f0fdf4; color:#15803d; border-color:rgba(22,163,74,.16); }
    .smc-point { display:inline-flex; align-items:center; gap:6px; padding:6px 11px; border-radius:999px; font-size:11px; font-weight:800; text-decoration:none; }
    .smc-point.low { background:#dcfce7; color:#15803d; }
    .smc-point.mid { background:#fef3c7; color:#a16207; }
    .smc-point.high { background:#ffedd5; color:#c2410c; }
    .smc-point.danger { background:#fee2e2; color:#b91c1c; }
    .smc-row-actions { display:flex; justify-content:center; }
    .smc-row-actions .dropdown-toggle { width:40px; height:40px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; border:1px solid #e2e8f0; background:#fff; color:#475569; }
    .smc-row-actions .dropdown-toggle::after { display:none; }
    .smc-row-actions .dropdown-menu { border:none; border-radius:14px; box-shadow:0 18px 42px -24px rgba(15,23,42,.28); padding:8px; min-width:190px; }
    .smc-row-actions .dropdown-item { border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600; }
    .smc-sidepanel { position:fixed; top:0; right:0; width:min(420px, 100vw); height:100vh; background:#fff; box-shadow:-24px 0 60px -28px rgba(15,23,42,.28); z-index:1080; transform:translateX(100%); transition:transform .28s ease; display:flex; flex-direction:column; }
    .smc-sidepanel.is-open { transform:translateX(0); }
    .smc-sidepanel-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.35); z-index:1070; opacity:0; pointer-events:none; transition:opacity .25s ease; }
    .smc-sidepanel-backdrop.is-open { opacity:1; pointer-events:auto; }
    .smc-sidepanel-head { padding:20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; gap:10px; align-items:flex-start; }
    .smc-sidepanel-body { padding:20px; overflow:auto; display:grid; gap:12px; }
    .smc-sidepanel-stat { padding:14px; border-radius:16px; background:#f8fafc; border:1px solid #e2e8f0; }
    .smc-sidepanel-stat .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; font-weight:700; }
    .smc-sidepanel-stat .v { margin-top:4px; font-size:15px; font-weight:800; color:var(--ms-text); }
    .smc-modal-hero { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:20px 22px 0; }
    .smc-modal-hero h5 { font-size:20px; font-weight:800; color:var(--ms-text); margin:0; }
    .smc-modal-hero p { margin:4px 0 0; font-size:12px; color:var(--ms-text-soft); }
    .smc-modal-stepper { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; padding:18px 22px 0; }
    .smc-step { padding:12px; border-radius:14px; background:#f8fafc; border:1px solid #e2e8f0; }
    .smc-step .n { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; font-weight:700; }
    .smc-step .t { margin-top:4px; font-size:13px; font-weight:800; color:var(--ms-text); }
    .smc-step.active { background:#ecfdf5; border-color:rgba(22,163,74,.2); }
    .smc-modal-grid { display:grid; grid-template-columns:1.1fr .9fr; gap:16px; }
    .smc-modal-panel { background:#f8fafc; border:1px solid #e2e8f0; border-radius:18px; padding:16px; }
    .smc-modal-panel h6 { font-size:14px; font-weight:800; color:var(--ms-text); margin:0 0 12px; }
    .smc-upload-box { border:1.5px dashed #94a3b8; border-radius:18px; background:#f8fafc; padding:24px; text-align:center; }
    .smc-upload-box i { font-size:34px; color:#16a34a; margin-bottom:10px; }
    .smc-impact-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:14px; }
    .smc-impact-item { padding:14px; border-radius:16px; background:#fff; border:1px solid #e2e8f0; box-shadow:0 10px 26px -24px rgba(15,23,42,.16); }
    .smc-impact-item .k { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; font-weight:700; }
    .smc-impact-item .v { margin-top:4px; font-size:18px; font-weight:800; color:var(--ms-text); }
    @media (max-width: 1199.98px) { .smc-kpi-grid, .smc-action-grid { grid-template-columns:repeat(2, 1fr); } .master-siswa-actions { grid-template-columns:repeat(2, minmax(220px, 1fr)); min-width:100%; } }
    @media (max-width: 767.98px) { .smc-kpi-grid, .smc-action-grid { grid-template-columns:1fr; } .smc-toolbar { top:70px; } .master-siswa-actions { grid-template-columns:1fr; min-width:100%; } }

    .badge-semester {
        background: #f0fdf4;
        color: #16a34a;
    }

    .badge-ta {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .filter-select-group {
        position: relative;
    }

    .filter-select-group .filter-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
        pointer-events: none;
        font-size: 12px;
    }

    .filter-select-group .form-select {
        padding-left: 30px;
        border-radius: 8px;
        border: 1.5px solid var(--ms-border);
        font-size: 11px;
        height: 32px;
        background-color: #f8fafc;
        transition: all .2s;
        min-width: 170px;
        color: var(--ms-text);
    }

    .filter-select-group .form-select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .master-siswa-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: flex-end;
        flex: 1 1 560px;
        min-width: min(100%, 560px);
    }

    .master-siswa-header-left {
        flex: 0 1 auto;
        min-width: 0;
    }

    .master-siswa-header-left h4 {
        white-space: nowrap;
    }

    .master-siswa-semester-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        border: none;
    }

    .master-siswa-semester-filter label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ms-text-soft);
        margin: 0;
        padding: 0;
        white-space: nowrap;
    }

    .master-siswa-semester-filter select {
        height: 30px;
        min-width: 150px;
        border: 1.5px solid var(--ms-border);
        border-radius: 8px;
        background: #f8fafc;
        color: var(--ms-text);
        font-size: 12px;
        padding: 0 10px;
        cursor: pointer;
        margin: 0;
    }

    .master-siswa-semester-filter select:focus {
        outline: none;
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background: #fff;
    }

    /* ---- Hide default DataTables search ---- */
    #table_data_user_filter {
        display: none !important;
    }

    /* ---- Custom Toolbar ---- */
    .ms-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .ms-toolbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ms-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ms-search-box {
        position: relative;
    }

    .ms-search-box .ms-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .ms-search-box .ms-search-input {
        height: 32px;
        width: 240px;
        border: 1.5px solid var(--ms-border);
        border-radius: 10px;
        background: #f8fafc;
        color: var(--ms-text);
        font-size: 12px;
        padding: 0 12px 0 34px;
        outline: none;
        transition: all .2s;
    }

    .ms-search-box .ms-search-input::placeholder {
        color: #94a3b8;
    }

    .ms-search-box .ms-search-input:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .08);
        background: #fff;
    }

    @media (max-width: 768px) {
        .ms-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .ms-toolbar-left,
        .ms-toolbar-right {
            width: 100%;
        }

        .ms-search-box .ms-search-input {
            width: 100%;
        }
    }

    /* ---- Modal Tempatkan ---- */
    .modal-tempatkan .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .18);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .modal-tempatkan .modal-dialog {
        margin-top: 20px;
        margin-bottom: 20px;
        max-width: min(1140px, calc(100vw - 2rem));
    }

    .modal-tempatkan .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, .14);
    }

    .modal-tempatkan .modal-body {
        padding: 18px 18px 24px;
        background: #f8fafc;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }

    .modal-tempatkan .modal-footer {
        padding: 18px 24px 34px;
        border-top: 1px solid #e2e8f0;
        background: #fff;
        flex: 0 0 auto;
    }

    .modal-tempatkan .modal-footer .btn {
        min-width: 110px;
    }

    .form-card-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .form-grid-compact {
        display: flex;
        gap: 12px;
        align-items: stretch;
    }

    .form-grid-compact>.form-grid-col {
        flex: 1 1 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    @media (max-width: 991px) {
        .form-grid-compact {
            flex-direction: column;
        }

        .modal-tempatkan .modal-dialog {
            max-width: calc(100vw - 1rem);
            margin: 12px auto;
        }
    }

    @media (max-width: 576px) {
        .modal-tempatkan .modal-content {
            max-height: 92vh;
            border-radius: 16px;
        }

        .modal-tempatkan .modal-header,
        .modal-tempatkan .modal-body,
        .modal-tempatkan .modal-footer {
            padding-left: 14px;
            padding-right: 14px;
        }
    }

    @media (max-width: 575.98px) {
        .dataTables_scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .dataTables_scrollHead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .action-group-ms {
            display: inline-flex !important;
            gap: 4px !important;
            grid-template-columns: unset !important;
        }
        .action-group-ms .btn {
            width: 28px !important;
            height: 28px !important;
            font-size: 11px !important;
        }
    }

    .form-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .form-card-title i {
        color: #16a34a;
    }

    .form-tempatkan .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-tempatkan .form-control,
    .form-tempatkan .form-select {
        height: 40px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #fff;
        font-size: 13px;
        color: #1e293b;
        box-shadow: none;
    }

    .form-tempatkan .form-control:focus,
    .form-tempatkan .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .12);
    }

    .form-tempatkan .form-help {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .toggle-ortu {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #0f172a;
        font-size: 13px;
        font-weight: 600;
    }

    .toggle-ortu:hover {
        border-color: #16a34a;
        color: #16a34a;
        background: #f0fdf4;
    }

    .btn-filter {
        background: #fff;
        border: 1.5px solid var(--ms-border);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        transition: all .25s;
    }

    .btn-filter:hover {
        border-color: var(--ms-primary);
        color: var(--ms-primary);
        background: var(--ms-primary-light);
    }

    .dropdown-menu.filter-dropdown {
        border: 1px solid var(--ms-border);
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(0, 0, 0, .08);
        padding: 6px;
        max-height: 200px;
        overflow-y: auto;
    }

    .dropdown-menu.filter-dropdown .dropdown-item {
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 13px;
        color: #374151;
        transition: all .2s;
    }

    .dropdown-menu.filter-dropdown .dropdown-item:hover {
        background: var(--ms-primary-light);
        color: var(--ms-primary);
    }

    /* ---- Info Card ---- */
    .info-card-modern {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #1e40af;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
    }

    .info-card-modern .info-icon {
        font-size: 18px;
        color: #3b82f6;
        flex-shrink: 0;
    }

    /* ---- Poin Colors ---- */
    .poin-rendah {
        color: #16a34a;
        font-weight: 700;
    }

    .poin-sedang {
        color: #eab308;
        font-weight: 700;
    }

    .poin-tinggi {
        color: #f97316;
        font-weight: 700;
    }

    .poin-bahaya {
        color: #ef4444;
        font-weight: 700;
    }

    /* ---- Table Link ---- */
    #table_data_user tbody td a {
        text-decoration: none;
    }

    /* ---- Kelas Filter ---- */
    .kelas-filter-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kelas-filter-label {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--ms-text-soft);
        white-space: nowrap;
    }

    .kelas-filter-control {
        height: 32px;
        min-width: 150px;
        border: 1.5px solid var(--ms-border);
        border-radius: 8px;
        background: #f8fafc;
        color: var(--ms-text);
        font-size: 12px;
        padding: 0 10px;
    }

    .kelas-filter-control:focus {
        outline: none;
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background: #fff;
    }

    /* ---- Laravel Pagination ---- */
    .pagination .page-link {
        color: #16a34a;
        border-color: #d1fae5;
        box-shadow: none;
    }

    .pagination .page-link:hover {
        color: #15803d;
        background: #f0fdf4;
        border-color: #16a34a;
    }

    .pagination .page-item.active .page-link {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
        border-color: #e2e8f0;
        background: #fff;
    }

    /* ---- Modal (generic) ---- */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .12);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 24px;
    }

    .modal-header.bg-info {
        background: linear-gradient(135deg, #0ea5e9, #38bdf8) !important;
    }

    .modal-header .modal-title {
        font-weight: 600;
        font-size: 16px;
    }

    .modal-body {
        padding: 20px 24px;
    }

    .modal-body .row.ing {
        padding: 6px 0 !important;
    }

    .modal-body .dem {
        font-weight: 600;
        color: #475569;
        font-size: 14px;
    }

    .modal-body .pisah {
        color: #94a3b8;
        padding: 0 4px;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 14px 24px;
    }

    .modal-footer .btn {
        border-radius: 8px;
        font-size: 13px;
        padding: 8px 20px;
        font-weight: 500;
    }

    .modal-footer .btn-secondary {
        background: #f1f5f9;
        border: none;
        color: #475569;
    }

    .modal-footer .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-header-ms:disabled {
        opacity: .5;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
@php
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $totalSiswa = $siswas->count();
    $laki = $siswas->filter(fn($s) => strtolower((string) $s->jk) === 'laki-laki')->count();
    $perempuan = $siswas->filter(fn($s) => strtolower((string) $s->jk) === 'perempuan')->count();
    $totalKelas = $siswas->map(fn($s) => $s->riwayatDipilih->kelas->id ?? null)->filter()->unique()->count();
    $totalJenjang = $siswas->map(fn($s) => $s->riwayatDipilih->kelas->jenjang->id ?? null)->filter()->unique()->count();
@endphp
<div class="master-siswa-page smc-wrap">

    @if (session('success'))
        <div class="smc-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="smc-alert error"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
    @endif

    <div class="smc-hero">
        <div class="smc-hero-grid">
            <div class="smc-hero-left">
                <div class="smc-hero-icon"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <h1 class="smc-hero-title">Student Management Center</h1>
                    <p class="smc-hero-sub">Kelola data siswa dengan cepat tanpa perlu pelatihan: cari, filter, tempatkan, import, dan pindahkan semester dari satu pusat kerja yang mudah dipahami.</p>
                    <div class="smc-hero-badges">
                        <span class="smc-hero-badge"><i class="fas fa-calendar-alt"></i>{{ $tahunAktif->tahun_ajaran }}</span>
                        <span class="smc-hero-badge"><i class="fas fa-layer-group"></i>Semester {{ $semesterDipilih->nama }}</span>
                        <span class="smc-hero-badge"><i class="fas fa-clock"></i>{{ $today }}</span>
                    </div>
                </div>
            </div>
            <div class="master-siswa-actions">
                <button type="button" class="smc-action-card" data-bs-toggle="modal" data-bs-target="#modalTempatkanSiswa">
                    <span class="smc-action-icon green"><i class="fas fa-user-plus"></i></span>
                    <span class="smc-action-title">Tempatkan Siswa</span>
                </button>

                <button type="button" class="smc-action-card" data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                    <span class="smc-action-icon blue"><i class="fas fa-file-excel"></i></span>
                    <span class="smc-action-title">Import Excel</span>
                </button>

                <button type="button" class="smc-action-card {{ !$canPromote ? 'is-disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#modalKenaikanKelas" @if(!$canPromote) disabled @endif>
                    <span class="smc-action-icon amber"><i class="fas fa-level-up-alt"></i></span>
                    <span class="smc-action-title">Kenaikan Kelas</span>
                </button>

                <button type="button" class="smc-action-card {{ !$canMoveSemester ? 'is-disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#modalPerpindahanSemester" @if(!$canMoveSemester) disabled @endif>
                    <span class="smc-action-icon violet"><i class="fas fa-exchange-alt"></i></span>
                    <span class="smc-action-title">Perpindahan Semester</span>
                </button>
            </div>
        </div>
    </div>

    <div class="smc-kpi-grid">
        <div class="smc-kpi"><span class="smc-kpi-icon blue"><i class="fas fa-users"></i></span><div><div class="smc-kpi-num">{{ $totalSiswa }}</div><div class="smc-kpi-label">Total Siswa</div></div></div>
        <div class="smc-kpi"><span class="smc-kpi-icon violet"><i class="fas fa-school"></i></span><div><div class="smc-kpi-num">{{ $totalKelas }}</div><div class="smc-kpi-label">Total Kelas</div></div></div>
        <div class="smc-kpi"><span class="smc-kpi-icon amber"><i class="fas fa-venus"></i></span><div><div class="smc-kpi-num">{{ $perempuan }}</div><div class="smc-kpi-label">Perempuan</div></div></div>
        <div class="smc-kpi"><span class="smc-kpi-icon green"><i class="fas fa-mars"></i></span><div><div class="smc-kpi-num">{{ $laki }}</div><div class="smc-kpi-label">Laki-laki</div></div></div>
    </div>

    <div class="smc-toolbar">
        <div class="smc-toolbar-left">
            <form method="GET" action="/master-siswa" class="master-siswa-semester-filter smc-filter">
                <label>Semester Aktif</label>
                <select name="semester_id" onchange="this.form.submit()">
                    @foreach($filterOptions as $option)
                    <option value="{{ $option['id'] }}" {{ $semesterId == $option['id'] ? 'selected' : '' }}>
                        {{ $option['label'] }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="smc-toolbar-right">
            <div class="ms-search-box">
                <i class="fas fa-search ms-search-icon"></i>
                <input type="text" id="ms-custom-search" class="ms-search-input" placeholder="Cari nama, NISN, atau kelas...">
            </div>
            <a href="/master-siswa" class="btn btn-header-ms btn-tambah-ms">Reset Filter</a>
        </div>
    </div>

    <div class="smc-grid-card">
        <div class="smc-grid-head">
            <div>
                <h5>Data Siswa</h5>
                <p>{{ $tahunAktif->tahun_ajaran }} • Semester {{ $semesterDipilih->nama }} • Mudah dipindai dan cepat dikelola.</p>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="table_data_user" class="table table-ms display" cellspacing="0" width="100%">
                <thead class="thead-inverse">
                    <th>No.</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jenjang</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($siswas as $siswa)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $siswa->nisn }}</td>
                        <td>
                            <div class="smc-student">
                                <span class="smc-student-avatar">{{ mb_strtoupper(mb_substr($siswa->nama, 0, 1)) }}</span>
                                <div>
                                    <div class="smc-student-name">{{ $siswa->nama }}</div>
                                    <div class="smc-student-nisn">NISN {{ $siswa->nisn }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($siswa->riwayatDipilih)
                                <span class="smc-pill class"><i class="fas fa-school"></i>{{ $siswa->riwayatDipilih->kelas->tingkat }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($siswa->riwayatDipilih)
                                <span class="smc-pill level"><i class="fas fa-sitemap"></i>{{ $siswa->riwayatDipilih->kelas->jenjang->nama_jenjang ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $siswa->riwayatDipilih?->tahunAjaran?->tahun_ajaran ?? '-' }}
                        </td>
                        <td>
                            @if($siswa->riwayatDipilih)
                            {{ $siswa->riwayatDipilih->semester?->nama ?? '-' }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @php
                                $pointCls = $siswa->poin >= 150 ? 'danger' : ($siswa->poin >= 56 ? 'high' : ($siswa->poin > 0 ? 'mid' : 'low'));
                            @endphp
                            <a href="/master-histori/{{ $siswa->id }}" class="smc-point {{ $pointCls }}">
                                <i class="fas fa-bolt"></i>{{ $siswa->poin }} Poin
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="smc-row-actions dropdown">
                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item js-siswa-detail" href="#" data-id="{{ $siswa->id }}" data-nama="{{ $siswa->nama }}" data-nisn="{{ $siswa->nisn }}" data-kelas="{{ $siswa->riwayatDipilih->kelas->tingkat ?? '-' }}" data-jenjang="{{ $siswa->riwayatDipilih->kelas->jenjang->nama_jenjang ?? '-' }}" data-tahun="{{ $siswa->riwayatDipilih?->tahunAjaran?->tahun_ajaran ?? '-' }}" data-semester="{{ $siswa->riwayatDipilih->semester?->nama ?? '-' }}" data-poin="{{ $siswa->poin }}"><i class="fas fa-eye me-2 text-info"></i>Detail Siswa</a></li>
                                    <li><a class="dropdown-item" href="/pelanggaran/tambah/{{ $siswa->nisn }}"><i class="fas fa-plus me-2 text-danger"></i>Tambah Poin</a></li>
                                    <li><a class="dropdown-item" href="/pelanggaran/kurang/{{ $siswa->nisn }}"><i class="fas fa-minus me-2 text-success"></i>Kurang Poin</a></li>
                                    <li><a class="dropdown-item" href="#modalEdit{{ $siswa->id }}" data-bs-toggle="modal"><i class="fas fa-edit me-2 text-warning"></i>Edit Kelas</a></li>
                                    <li><a class="dropdown-item" href="{{ route('master-siswa.detail', $siswa->id) }}"><i class="fas fa-up-right-from-square me-2 text-primary"></i>Buka Halaman Detail</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Detail --}}
                    <div id="modalCenter{{ $siswa->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Detail Siswa
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">NISN</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->nisn }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">Nama</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->nama }}</div>
                                    </div>
                                    <hr class="my-2" style="border-color: #e2e8f0;">
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">TTL</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->ttl }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">JK</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->jk }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">Agama</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->agama }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">Alamat</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->alamat }}</div>
                                    </div>
                                    <hr class="my-2" style="border-color: #e2e8f0;">
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">No.Telp</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">
                                            <a href="tel:{{ $siswa->no_telp }}" style="color:#2563eb;text-decoration:none;">
                                                <i class="fas fa-phone-alt me-1" style="font-size:11px;"></i>
                                                {{ $siswa->no_telp }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">No.Telp Rumah</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">
                                            <a href="tel:{{ $siswa->no_telp_rumah }}" style="color:#2563eb;text-decoration:none;">
                                                <i class="fas fa-phone-alt me-1" style="font-size:11px;"></i>
                                                {{ $siswa->no_telp_rumah }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>
                                        Kembali
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Edit Siswa --}}
                    <div class="modal fade" id="modalEdit{{ $siswa->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kelas: {{ $siswa->nama }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kelas_id" class="form-label">Pilih Kelas Baru</label>
                                            <select name="kelas_id" class="form-select" required>
                                                @foreach($kelas as $k)
                                                <option value="{{ $k->id }}" {{ optional($siswa->kelasAktif)->kelas_id == $k->id ? 'selected' : '' }}>
                                                    {{ $k->tingkat }}{{ $k->nama_kelas }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="modalImportSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('master-siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="smc-modal-hero">
                    <div>
                        <h5><i class="fas fa-file-excel me-2 text-success"></i>Modern Import Wizard</h5>
                        <p>Download template, upload file, lalu lanjutkan import data siswa dengan alur yang mudah dipahami.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="smc-modal-stepper">
                    <div class="smc-step active"><div class="n">Langkah 1</div><div class="t">Download Template</div></div>
                    <div class="smc-step active"><div class="n">Langkah 2</div><div class="t">Upload File</div></div>
                    <div class="smc-step"><div class="n">Langkah 3</div><div class="t">Validasi</div></div>
                    <div class="smc-step"><div class="n">Langkah 4</div><div class="t">Import</div></div>
                </div>
                <div class="modal-body">
                    <div class="smc-modal-grid">
                        <div class="smc-modal-panel">
                            <h6>Mulai dari Template Resmi</h6>
                            <p class="text-muted small mb-3">Template sudah berisi header. Cukup isi baris data siswa di bawahnya.</p>
                            <a href="{{ route('master-siswa.template') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-download me-1"></i> Download Template Excel
                            </a>
                        </div>
                        <div class="smc-modal-panel">
                            <h6>Upload File</h6>
                            <div class="smc-upload-box">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div class="fw-bold mb-1">Pilih file Excel siswa</div>
                                <div class="text-muted small mb-3">Format didukung: .xlsx, .xls, .csv</div>
                                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-success px-4">Lanjut Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-tempatkan" id="modalTempatkanSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('master-siswa.store') }}" method="POST" id="formTempatkanSiswa" class="form-tempatkan">
                @csrf
                <div class="smc-modal-hero">
                    <div>
                        <h5><i class="fas fa-user-plus me-2 text-success"></i>Wizard Penempatan Siswa</h5>
                        <p>Pilih user siswa, cek data otomatis, tentukan kelas, lalu konfirmasi penyimpanan tanpa langkah yang membingungkan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="smc-modal-stepper">
                    <div class="smc-step active"><div class="n">Langkah 1</div><div class="t">Pilih User</div></div>
                    <div class="smc-step active"><div class="n">Langkah 2</div><div class="t">Auto Fill Data</div></div>
                    <div class="smc-step active"><div class="n">Langkah 3</div><div class="t">Pilih Kelas</div></div>
                    <div class="smc-step"><div class="n">Langkah 4</div><div class="t">Konfirmasi</div></div>
                </div>
                <div class="modal-body">
                    <div class="smc-modal-panel mb-3">
                        <h6>Petunjuk Cepat</h6>
                        <div class="text-muted small">Jika user sudah punya data siswa, field akan terisi otomatis. Admin cukup cek lalu simpan.</div>
                    </div>

                    <div class="form-grid-compact">
                        <div class="form-grid-col">
                            <div class="form-card-section">
                                <div class="form-card-title"><i class="fas fa-user-tag"></i> Akun & Penempatan</div>
                                <div class="row g-2">
                                    <div class="col-lg-7">
                                        <label class="form-label">User Siswa</label>
                                        <select name="user_id" id="user_id_siswa" class="form-select" required>
                                            <option value="">-- Pilih User --</option>
                                            @foreach ($usersSiswa as $user)
                                            <option value="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-nisn="{{ $user->nisn ?? '' }}"
                                                data-nama="{{ $user->studentProfile->nama ?? $user->name }}"
                                                data-ttl="{{ $user->studentProfile->ttl ?? '' }}"
                                                data-jk="{{ $user->studentProfile->jk ?? '' }}"
                                                data-agama="{{ $user->studentProfile->agama ?? '' }}"
                                                data-alamat="{{ $user->studentProfile->alamat ?? '' }}"
                                                data-no-telp="{{ $user->studentProfile->no_telp ?? '' }}"
                                                data-n-ayah="{{ $user->studentProfile->n_ayah ?? '' }}"
                                                data-n-ibu="{{ $user->studentProfile->n_ibu ?? '' }}"
                                                data-alamat-ortu="{{ $user->studentProfile->alamat_ortu ?? '' }}"
                                                data-no-telp-rumah="{{ $user->studentProfile->no_telp_rumah ?? '' }}"
                                                data-kelas-id="{{ $user->studentProfile?->kelasAktif?->kelas_id ?? '' }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }}){{ $user->studentProfile ? ' - sudah ada data' : '' }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="form-help">Hanya user dengan role siswa yang ditampilkan.</div>
                                    </div>
                                    <div class="col-lg-5">
                                        <label class="form-label">Kelas Aktif</label>
                                        <select name="kelas_id" id="kelas_id_siswa" class="form-select" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->jenjang?->nama_jenjang ?? 'Jenjang' }} - Kelas {{ $k->tingkat }}{{ $k->nama_kelas }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-card-section mt-2">
                                <div class="form-card-title"><i class="fas fa-people-roof"></i> Data Orang Tua</div>
                                <div class="row g-2">
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">Nama Ayah</label>
                                        <input type="text" name="n_ayah" id="n_ayah_siswa" class="form-control" value="{{ old('n_ayah') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">Nama Ibu</label>
                                        <input type="text" name="n_ibu" id="n_ibu_siswa" class="form-control" value="{{ old('n_ibu') }}" required>
                                    </div>
                                    <div class="col-lg-8 col-md-12">
                                        <label class="form-label">Alamat Orang Tua</label>
                                        <input type="text" name="alamat_ortu" id="alamat_ortu_siswa" class="form-control" value="{{ old('alamat_ortu') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">No. Telp Rumah</label>
                                        <input type="text" name="no_telp_rumah" id="no_telp_rumah_siswa" class="form-control" value="{{ old('no_telp_rumah') }}" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-grid-col">
                            <div class="form-card-section">
                                <div class="form-card-title"><i class="fas fa-id-card"></i> Data Siswa</div>
                                <div class="row g-2">
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">NISN</label>
                                        <input type="text" name="nisn" id="nisn_siswa" class="form-control" value="{{ old('nisn') }}" maxlength="10" required>
                                    </div>
                                    <div class="col-lg-8 col-md-6">
                                        <label class="form-label">Nama Siswa</label>
                                        <input type="text" name="nama" id="nama_siswa" class="form-control" value="{{ old('nama') }}" required>
                                    </div>

                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="ttl" id="ttl_siswa" class="form-control" value="{{ old('ttl') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="date" id="date_siswa" class="form-control" value="{{ old('date') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="jk" id="jk_siswa" class="form-select" required>
                                            <option value="">-- Pilih JK --</option>
                                            <option value="Laki-laki" {{ old('jk') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jk') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">Agama</label>
                                        <input type="text" class="form-control" value="Islam" disabled>
                                        <input type="hidden" name="agama" id="agama_siswa" value="Islam">
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <label class="form-label">No. Telp</label>
                                        <input type="text" name="no_telp" id="no_telp_siswa" class="form-control" value="{{ old('no_telp') }}" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" name="alamat" id="alamat_siswa" class="form-control" value="{{ old('alamat') }}" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-success px-4">Konfirmasi & Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalKenaikanKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="/admin/kenaikan-kelas" method="POST">
                @csrf
                <div class="smc-modal-hero">
                    <div>
                        <h5><i class="fas fa-level-up-alt me-2 text-warning"></i>Wizard Kenaikan Kelas</h5>
                        <p>Tinjau dampak proses sebelum siswa dipindahkan ke kelas berikutnya.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="smc-impact-grid">
                        <div class="smc-impact-item"><div class="k">Jumlah Siswa</div><div class="v">{{ $totalSiswa }}</div></div>
                        <div class="smc-impact-item"><div class="k">Kelas Asal</div><div class="v">{{ $totalKelas }}</div></div>
                        <div class="smc-impact-item"><div class="k">Semester Aktif</div><div class="v">{{ $semesterDipilih->nama }}</div></div>
                    </div>
                    <div class="smc-modal-panel mt-3">
                        <h6>Konfirmasi Proses</h6>
                        <div class="text-muted small">Proses ini akan menjalankan kenaikan kelas untuk data siswa yang memenuhi aturan sistem aktif.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4">Proses Kenaikan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPerpindahanSemester" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="/admin/perpindahan-semester" method="POST">
                @csrf
                <div class="smc-modal-hero">
                    <div>
                        <h5><i class="fas fa-exchange-alt me-2 text-primary"></i>Perpindahan Semester</h5>
                        <p>Lihat dampak perpindahan semester sebelum proses dijalankan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="smc-impact-grid">
                        <div class="smc-impact-item"><div class="k">Tahun Ajaran Aktif</div><div class="v">{{ $tahunAktif->tahun_ajaran }}</div></div>
                        <div class="smc-impact-item"><div class="k">Semester Sekarang</div><div class="v">{{ $semesterDipilih->nama }}</div></div>
                        <div class="smc-impact-item"><div class="k">Data Siswa Aktif</div><div class="v">{{ $totalSiswa }}</div></div>
                    </div>
                    <div class="smc-modal-panel mt-3">
                        <h6>Dampak Proses</h6>
                        <div class="text-muted small">Perpindahan semester akan memperbarui konteks semester aktif untuk pengelolaan data berikutnya.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Lanjut Perpindahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="smc-sidepanel-backdrop" id="smcDetailBackdrop"></div>
<aside class="smc-sidepanel" id="smcDetailPanel" aria-hidden="true">
    <div class="smc-sidepanel-head">
        <div>
            <div class="smc-student d-flex align-items-center gap-3">
                <span class="smc-student-avatar" id="smcPanelAvatar">S</span>
                <div>
                    <div class="smc-student-name" id="smcPanelName">Detail Siswa</div>
                    <div class="smc-student-nisn" id="smcPanelNisn">NISN -</div>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" id="smcPanelClose" aria-label="Tutup"></button>
    </div>
    <div class="smc-sidepanel-body">
        <div class="smc-sidepanel-stat"><div class="k">Kelas</div><div class="v" id="smcPanelKelas">-</div></div>
        <div class="smc-sidepanel-stat"><div class="k">Jenjang</div><div class="v" id="smcPanelJenjang">-</div></div>
        <div class="smc-sidepanel-stat"><div class="k">Tahun Ajaran</div><div class="v" id="smcPanelTahun">-</div></div>
        <div class="smc-sidepanel-stat"><div class="k">Semester</div><div class="v" id="smcPanelSemester">-</div></div>
        <div class="smc-sidepanel-stat"><div class="k">Poin</div><div class="v" id="smcPanelPoin">-</div></div>
    </div>
</aside>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#table_data_user').DataTable({
            pagingType: 'simple_numbers',
            responsive: false,
            scrollX: true,
            processing: true,
            pageLength: 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                paginate: {
                    first: '\u00ab',
                    previous: '\u2039',
                    next: '\u203a',
                    last: '\u00bb'
                },
                aria: {
                    paginate: {
                        first: 'First',
                        previous: 'Previous',
                        next: 'Next',
                        last: 'Last'
                    }
                },
            },
            "columnDefs": [{
                    "orderable": false,
                    "width": "30%",
                    "targets": 2
                },
                {
                    "orderable": false,
                    "targets": 5
                },
            ],
            lengthChange: false
        });

        // ===== Kelas filter → append into toolbar-left =====
        var $kelasSelect = $('<select/>', {
            class: 'kelas-filter-control',
            html: '<option value="">Semua Kelas</option>'
        });

        table.column(3).data().unique().sort().each(function(d) {
            var value = $('<div/>').html(d).text().trim();
            if (value && value !== '-') {
                $kelasSelect.append($('<option/>', {
                    value: value,
                    text: value
                }));
            }
        });

        var $kelasWrap = $('<div class="kelas-filter-wrap"></div>');
        $kelasWrap.append('<label class="kelas-filter-label">Kelas</label>');
        $kelasWrap.append($kelasSelect);
        $('.ms-toolbar-left').append($kelasWrap);

        $kelasSelect.on('change', function() {
            var value = $.fn.dataTable.util.escapeRegex($(this).val());
            table.column(3).search(value ? '^' + value + '$' : '', true, false).draw();
        });

        // ===== Custom search → wired to DataTables =====
        $('#ms-custom-search').on('keyup', function() {
            var keyword = $(this).val();
            table.search(keyword).draw();
        });

        function splitTtl(ttl) {
            if (!ttl) return {
                tempat: '',
                tanggal: ''
            };
            var parts = ttl.split(',');
            if (parts.length < 2) return {
                tempat: ttl.trim(),
                tanggal: ''
            };
            var tanggal = parts.pop().trim();
            return {
                tempat: parts.join(',').trim(),
                tanggal: tanggal
            };
        }

        function fillStudentFields(option) {
            if (!option || !option.value) return;

            var data = option.dataset;
            $('#nisn_siswa').val(data.nisn || '');
            $('#nama_siswa').val(data.nama || data.name || '');

            var ttl = splitTtl(data.ttl || '');
            $('#ttl_siswa').val(ttl.tempat || '');
            $('#date_siswa').val(ttl.tanggal || '');

            $('#jk_siswa').val(data.jk || '');
            $('#agama_siswa').val('Islam');
            $('#alamat_siswa').val(data.alamat || '');
            $('#no_telp_siswa').val(data.noTelp || '');
            $('#n_ayah_siswa').val(data.nAyah || '');
            $('#n_ibu_siswa').val(data.nIbu || '');
            $('#alamat_ortu_siswa').val(data.alamatOrtu || '');
            $('#no_telp_rumah_siswa').val(data.noTelpRumah || '');

            if (data.kelasId) {
                $('#kelas_id_siswa').val(data.kelasId);
            }
        }

        $('#user_id_siswa').on('change', function() {
            fillStudentFields(this.options[this.selectedIndex]);
        });

        @if (!$errors->any())
        if ($('#user_id_siswa').val()) {
            fillStudentFields($('#user_id_siswa')[0].options[$('#user_id_siswa')[0].selectedIndex]);
        }
        @endif

        @if ($errors->any())
        var modal = new bootstrap.Modal(document.getElementById('modalTempatkanSiswa'));
        modal.show();
        @endif

        @if ($errors->has('file'))
        var importModal = new bootstrap.Modal(document.getElementById('modalImportSiswa'));
        importModal.show();
        @endif

        var panel = document.getElementById('smcDetailPanel');
        var backdrop = document.getElementById('smcDetailBackdrop');
        function closePanel() {
            panel.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            panel.setAttribute('aria-hidden', 'true');
        }
        function openPanel(data) {
            $('#smcPanelAvatar').text((data.nama || 'S').trim().charAt(0).toUpperCase());
            $('#smcPanelName').text(data.nama || '-');
            $('#smcPanelNisn').text('NISN ' + (data.nisn || '-'));
            $('#smcPanelKelas').text(data.kelas || '-');
            $('#smcPanelJenjang').text(data.jenjang || '-');
            $('#smcPanelTahun').text(data.tahun || '-');
            $('#smcPanelSemester').text(data.semester || '-');
            $('#smcPanelPoin').text((data.poin || '0') + ' poin');
            panel.classList.add('is-open');
            backdrop.classList.add('is-open');
            panel.setAttribute('aria-hidden', 'false');
        }
        $(document).on('click', '.js-siswa-detail', function(e) {
            e.preventDefault();
            openPanel(this.dataset);
        });
        $('#smcPanelClose').on('click', closePanel);
        $('#smcDetailBackdrop').on('click', closePanel);

    });
</script>
@endpush