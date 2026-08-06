@extends('layouts.main')
@section('title', 'Master User')
@include('component.admin.absensi-module')

@php
    $todayLabel = now()->translatedFormat('l, d F Y');
    $items       = $users->getCollection();
    $total       = $users->total();
    $totalLoaded = $items->count();
    $byRole      = $items->groupBy('role');
    $countAdmin  = $byRole->get(1, collect())->count();
    $countGuru   = $byRole->get(2, collect())->count();
    $countSiswa  = $byRole->get(3, collect())->count();
    $countBK     = $byRole->get(4, collect())->count();
    $countKepsek = $byRole->get(5, collect())->count();
    $countAktif  = $items->where('info', 1)->count();
    $countBelum  = $items->where('info', 0)->count();
    $lastUp      = $items->max('updated_at');
    $lastUpLabel = $lastUp ? $lastUp->translatedFormat('d M Y, H:i') : 'Belum ada pembaruan';
    $aktifPct    = $totalLoaded ? round($countAktif / $totalLoaded * 100) : 0;
    $usersJson   = $items->map(fn($u) => [
        'id'       => $u->id,
        'name'     => (string) $u->name,
        'username' => (string) $u->username,
        'email'    => (string) $u->email,
        'nisn'     => (string) $u->nisn,
        'role'     => (int) $u->role,
        'info'     => (int) $u->info,
        'created'  => $u->created_at ? $u->created_at->translatedFormat('d M Y, H:i') : '-',
        'updated'  => $u->updated_at ? $u->updated_at->translatedFormat('d M Y, H:i') : '-',
    ])->values()->toJson(JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
@endphp

<style>
    /* ============================================================
       MASTER USER — User Management Center
       Built on the shared ABSENSI design system (.abs-mod / .abm-*)
       ============================================================ */
    .us-mod { margin-top: 22px; }
    .us-mod .abm-hero-sub { max-width: 720px; }

    /* ---------- KPI accent colors ---------- */
    .us-kpi.total  { --ab-kpi-glow: rgba(37,99,235,.08);  --ab-kpi-wm: #2563eb; }
    .us-kpi.admin  { --ab-kpi-glow: rgba(217,119,6,.08);  --ab-kpi-wm: #d97706; }
    .us-kpi.guru   { --ab-kpi-glow: rgba(2,132,199,.08);  --ab-kpi-wm: #0284c7; }
    .us-kpi.siswa  { --ab-kpi-glow: rgba(22,163,74,.08);  --ab-kpi-wm: #16a34a; }
    .us-kpi.kepsek { --ab-kpi-glow: rgba(124,58,237,.08); --ab-kpi-wm: #7c3aed; }
    .us-kpi.aktif  { --ab-kpi-glow: rgba(13,148,136,.08); --ab-kpi-wm: #0d9488; }
    .us-kpi.belum  { --ab-kpi-glow: rgba(100,116,139,.08);--ab-kpi-wm: #64748b; }

    .us-kpi { position: relative; }
    .us-kpi-foot {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        margin-top: 10px; padding-top: 9px; border-top: 1px dashed var(--ab-border);
        font-size: 10.5px; color: var(--ab-text-3); font-weight: 600;
    }
    .us-kpi-foot i { font-size: 10px; }

    .us-ico-teal { background: linear-gradient(135deg, #0d9488, #2dd4bf); --ab-kpi-shadow: rgba(13,148,136,.4); }
    .us-ico-slate { background: linear-gradient(135deg, #64748b, #94a3b8); --ab-kpi-shadow: rgba(100,116,139,.4); }

    /* ---------- Sticky filter toolbar ---------- */
    .us-toolbar {
        position: sticky; top: 78px; z-index: 940;
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) repeat(2, minmax(150px, .42fr)) minmax(120px, .3fr) auto auto auto auto;
        gap: 12px; align-items: end;
        background: rgba(255,255,255,.92); border: 1px solid var(--ab-border);
        border-radius: 18px; padding: 14px 16px;
        box-shadow: 0 12px 28px -24px rgba(15,23,42,.18);
        backdrop-filter: blur(12px); margin-bottom: 18px;
    }
    html.dark-mode .us-toolbar { background: rgba(13,47,56,.92); }

    .us-field { display: flex; flex-direction: column; gap: 5px; }
    .us-field label { font-size: 10.5px; font-weight: 700; color: var(--ab-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .us-select-wrap { position: relative; }
    .us-select-wrap > i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ab-text-3); font-size: 12px; z-index: 2; pointer-events: none; }
    .us-select {
        width: 100%; min-height: 44px;
        border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; padding: 0 14px 0 34px;
        font-size: 12.5px; color: var(--ab-text); font-weight: 600;
        transition: border-color .2s, box-shadow .2s;
    }
    .us-select:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }

    .us-tool-btn {
        min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        border: 1.5px solid var(--ab-border); background: var(--ab-card); border-radius: 12px;
        padding: 0 14px; font-size: 12px; font-weight: 700; color: var(--ab-text-2); cursor: pointer;
        transition: all .2s cubic-bezier(.4,0,.2,1); white-space: nowrap;
    }
    .us-tool-btn:hover { border-color: var(--ab-primary-border); color: var(--ab-primary); background: var(--ab-primary-soft); }
    .us-tool-btn.is-on { background: var(--ab-primary-soft); color: var(--ab-primary); border-color: var(--ab-primary-border); }

    /* ---------- Data grid card ---------- */
    .us-card {
        background: var(--ab-card); border: 1px solid var(--ab-border);
        border-radius: 18px; box-shadow: var(--ab-shadow); overflow: hidden;
    }
    .us-card-head {
        display: flex; justify-content: space-between; align-items: center; gap: 14px;
        padding: 18px 20px 14px; flex-wrap: wrap;
    }
    .us-card-title { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 800; color: var(--ab-text); }
    .us-card-title i { color: var(--ab-primary); }
    .us-card-sub { margin-top: 4px; font-size: 12px; color: var(--ab-text-3); }

    /* ---------- Premium table ---------- */
    .us-table-scroll { overflow: auto; max-height: min(70vh, 1000px); border-radius: 0 0 18px 18px; }
    .us-table-wrap { padding: 0 18px 4px; }
    .us-table {
        width: 100%; border-collapse: separate; border-spacing: 0 10px;
        margin: 0 !important; background: transparent;
    }
    .us-table thead th {
        position: sticky; top: 0; z-index: 3;
        background: var(--ab-card);
        padding: 0 16px 8px;
        font-size: 11px; text-transform: uppercase; letter-spacing: .5px;
        color: var(--ab-text-3); font-weight: 800; text-align: left; white-space: nowrap;
        border-bottom: 1px solid var(--ab-border);
    }
    .us-table tbody td {
        background: var(--ab-card);
        border-top: 1px solid var(--ab-border); border-bottom: 1px solid var(--ab-border);
        padding: 13px 12px; font-size: 13px; color: var(--ab-text-2); vertical-align: middle;
        transition: background .22s, border-color .22s, transform .22s;
    }
    .us-table tbody tr:nth-child(even) td { background: var(--ab-bg); }
    .us-table tbody td:first-child {
        border-left: 1px solid var(--ab-border); border-radius: 16px 0 0 16px;
        width: 52px; text-align: center; color: var(--ab-text-3); font-weight: 700;
    }
    .us-table tbody td:last-child { border-right: 1px solid var(--ab-border); border-radius: 0 16px 16px 0; }
    .us-table tbody tr { transition: transform .22s ease; }
    .us-table tbody tr:hover td { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); }
    .us-table tbody tr:hover { transform: translateY(-2px); }

    .us-table .abm-chip { white-space: normal; }

    /* Compact density */
    .us-table.us-compact tbody td { padding: 8px 12px; font-size: 12.5px; }
    .us-table.us-compact tbody tr td:first-child { width: 44px; }
    .us-table.us-compact .us-avatar { width: 36px; height: 36px; font-size: 12px; border-radius: 11px; }

    .us-user { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .us-avatar {
        width: 44px; height: 44px; border-radius: 14px; flex-shrink: 0; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 800; letter-spacing: .5px;
        box-shadow: 0 4px 10px -2px rgba(15,23,42,.25);
    }
    .us-avatar.c0 { background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 4px 10px -2px rgba(37,99,235,.4); }
    .us-avatar.c1 { background: linear-gradient(135deg, #7c3aed, #a855f7); box-shadow: 0 4px 10px -2px rgba(124,58,237,.4); }
    .us-avatar.c2 { background: linear-gradient(135deg, #0ea5e9, #22d3ee); box-shadow: 0 4px 10px -2px rgba(14,165,233,.4); }
    .us-avatar.c3 { background: linear-gradient(135deg, #16a34a, #4ade80); box-shadow: 0 4px 10px -2px rgba(22,163,74,.4); }
    .us-avatar.c4 { background: linear-gradient(135deg, #ea580c, #fb923c); box-shadow: 0 4px 10px -2px rgba(234,88,12,.4); }
    .us-avatar.c5 { background: linear-gradient(135deg, #db2777, #f472b6); box-shadow: 0 4px 10px -2px rgba(219,39,119,.4); }
    .us-user-main { min-width: 0; }
    .us-user-name { font-size: 14px; font-weight: 800; color: var(--ab-text); line-height: 1.3; }
    .us-user-sub { margin-top: 3px; font-size: 11px; color: var(--ab-text-3); font-weight: 600; letter-spacing: .3px; }
    .us-email { font-size: 12.5px; color: var(--ab-text-2); font-weight: 500; }
    .us-nisn { font-size: 12px; color: var(--ab-text-3); font-weight: 600; font-variant-numeric: tabular-nums; }

    .us-stack { display: flex; flex-wrap: wrap; gap: 6px; }
    .us-actions { display: flex; justify-content: flex-end; gap: 7px; flex-wrap: wrap; }

    /* Role badges */
    .us-role { display: inline-flex; align-items: center; gap: 7px; padding: 4px 13px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; border: 1px solid transparent; line-height: 1.5; }
    .us-role i { font-size: 11px; }
    .us-role--1 { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .us-role--2 { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .us-role--3 { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
    .us-role--4 { background: #f5f3ff; color: #7c3aed; border-color: #ddd6fe; }
    .us-role--5 { background: #faf5ff; color: #9333ea; border-color: #e9d5ff; }
    html.dark-mode .us-role--1 { background: rgba(251,191,36,.12); color: #fbbf24; border-color: rgba(251,191,36,.35); }
    html.dark-mode .us-role--2 { background: rgba(61,169,252,.12); color: #60a5fa; border-color: rgba(61,169,252,.35); }
    html.dark-mode .us-role--3 { background: rgba(52,211,153,.12); color: #34d399; border-color: rgba(52,211,153,.35); }
    html.dark-mode .us-role--4 { background: rgba(167,139,250,.12); color: #a78bfa; border-color: rgba(167,139,250,.35); }
    html.dark-mode .us-role--5 { background: rgba(217,70,239,.12); color: #e879f9; border-color: rgba(217,70,239,.35); }

    .us-icon-btn {
        width: 40px; height: 40px; border-radius: 12px;
        border: 1px solid var(--ab-border); background: var(--ab-card);
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--ab-text-2); font-size: 14px; cursor: pointer;
        transition: all .22s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 4px 10px -6px rgba(15,23,42,.18);
    }
    .us-icon-btn:hover { transform: translateY(-2px); }
    .us-icon-btn--view { color: var(--ab-primary); }
    .us-icon-btn--view:hover { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); box-shadow: 0 10px 20px -10px rgba(37,99,235,.3); }
    .us-icon-btn--edit { color: #d97706; }
    .us-icon-btn--edit:hover { background: var(--ab-amber-soft); border-color: var(--ab-amber-border); box-shadow: 0 10px 20px -10px rgba(217,119,6,.28); }
    .us-icon-btn--pass { color: #7c3aed; }
    .us-icon-btn--pass:hover { background: var(--ab-violet-soft); border-color: var(--ab-violet-border); box-shadow: 0 10px 20px -10px rgba(124,58,237,.28); }
    .us-icon-btn--delete { color: var(--ab-red); }
    .us-icon-btn--delete:hover { background: var(--ab-red-soft); border-color: var(--ab-red-border); box-shadow: 0 10px 20px -10px rgba(220,38,38,.28); }

    /* ---------- Mobile cards ---------- */
    .us-mobile-grid { display: none; padding: 0 18px 18px; gap: 14px; }
    .us-mobile-card {
        background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 18px;
        box-shadow: var(--ab-shadow); padding: 16px; display: grid; gap: 14px;
    }
    .us-mobile-head { display: flex; align-items: center; gap: 12px; }
    .us-mobile-grid-inner { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .us-mobile-stat { background: var(--ab-border-soft); border-radius: 12px; padding: 10px 12px; min-width: 0; }
    .us-mobile-stat .k { font-size: 10px; color: var(--ab-text-3); text-transform: uppercase; letter-spacing: .3px; font-weight: 700; }
    .us-mobile-stat .v { margin-top: 5px; font-size: 12.5px; font-weight: 800; color: var(--ab-text); word-break: break-word; }
    .us-mobile-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .us-mobile-action {
        flex: 1; min-height: 42px; border-radius: 12px; border: 1px solid var(--ab-border);
        background: var(--ab-card); display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        font-size: 12px; font-weight: 700; color: var(--ab-text-2); text-decoration: none;
        transition: all .22s cubic-bezier(.4,0,.2,1); cursor: pointer;
    }
    .us-mobile-action--view { color: var(--ab-primary); }
    .us-mobile-action--view:hover { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); }
    .us-mobile-action--edit { color: #d97706; }
    .us-mobile-action--edit:hover { background: var(--ab-amber-soft); border-color: var(--ab-amber-border); }
    .us-mobile-action--pass { color: #7c3aed; }
    .us-mobile-action--pass:hover { background: var(--ab-violet-soft); border-color: var(--ab-violet-border); }
    .us-mobile-action--delete { color: var(--ab-red); }
    .us-mobile-action--delete:hover { background: var(--ab-red-soft); border-color: var(--ab-red-border); }

    /* ---------- Skeleton ---------- */
    .us-skeleton { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; padding: 4px 18px 18px; }
    .us-skeleton-card { min-height: 96px; border-radius: 18px; }
    .us-shimmer {
        background: linear-gradient(90deg, var(--ab-border-soft) 25%, rgba(148,163,184,.18) 50%, var(--ab-border-soft) 75%);
        background-size: 800px 100%; border-radius: 12px;
        animation: usShimmer 1.4s linear infinite;
    }
    @keyframes usShimmer { 0% { background-position: -800px 0; } 100% { background-position: 800px 0; } }

    /* ---------- Empty states ---------- */
    .us-empty { text-align: center; padding: 48px 20px 40px; }
    .us-empty-illu {
        position: relative; width: 96px; height: 96px; margin: 0 auto 18px;
        border-radius: 26px; background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border);
        display: flex; align-items: center; justify-content: center; color: var(--ab-primary); font-size: 38px;
    }
    .us-empty-illu::after {
        content: ''; position: absolute; inset: -12px; border-radius: 34px;
        border: 1.5px dashed var(--ab-primary-border); animation: usSpin 22s linear infinite;
    }
    @keyframes usSpin { to { transform: rotate(360deg); } }
    .us-empty-title { font-size: 16px; font-weight: 800; color: var(--ab-text); margin-bottom: 5px; }
    .us-empty-sub { font-size: 12.5px; color: var(--ab-text-3); margin-bottom: 20px; }
    .us-empty-result { text-align: center; padding: 40px 20px; }
    .us-empty-result i { font-size: 40px; opacity: .35; color: var(--ab-primary); margin-bottom: 10px; }

    /* ---------- Pagination ---------- */
    .us-pagination {
        display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
        padding: 4px 20px 20px; font-size: 13px; color: var(--ab-text-3);
    }
    .us-pager { display: flex; gap: 6px; flex-wrap: wrap; }
    .us-page-btn {
        min-width: 38px; height: 38px; padding: 0 10px; border-radius: 11px;
        border: 1px solid var(--ab-border); background: var(--ab-card);
        color: var(--ab-text-2); font-size: 12px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        transition: all .2s cubic-bezier(.4,0,.2,1);
    }
    .us-page-btn:hover { border-color: var(--ab-primary-border); color: var(--ab-primary); }
    .us-page-btn.is-active { background: var(--ab-grad); border-color: transparent; color: #fff; box-shadow: 0 12px 20px -14px rgba(37,99,235,.45); }
    .us-page-btn:disabled { opacity: .45; cursor: not-allowed; background: var(--ab-border-soft); }

    /* ---------- Toasts ---------- */
    .us-toast-wrap { position: fixed; top: 92px; right: 18px; z-index: 1200; display: grid; gap: 10px; width: min(360px, calc(100vw - 24px)); pointer-events: none; }
    .us-toast {
        display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 16px;
        background: rgba(255,255,255,.96); border: 1px solid var(--ab-border);
        box-shadow: 0 18px 34px -24px rgba(15,23,42,.24); backdrop-filter: blur(12px);
        opacity: 0; transform: translateY(-10px);
        transition: opacity .25s ease, transform .25s ease; pointer-events: auto;
    }
    html.dark-mode .us-toast { background: rgba(13,47,56,.94); }
    .us-toast.is-show { opacity: 1; transform: translateY(0); }
    .us-toast-icon { width: 40px; height: 40px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .us-toast.success .us-toast-icon { background: var(--ab-green-soft); color: var(--ab-green); }
    .us-toast.error .us-toast-icon { background: var(--ab-red-soft); color: var(--ab-red); }
    .us-toast-title { font-size: 13px; font-weight: 800; color: var(--ab-text); }
    .us-toast-text { margin-top: 2px; font-size: 12px; line-height: 1.6; color: var(--ab-text-2); }

    /* ---------- Modal shell (shared) ---------- */
    .us-modal .modal-dialog { max-width: 680px; }
    .us-modal--pass .modal-dialog { max-width: 520px; }
    .us-modal--import .modal-dialog { max-width: 760px; }
    .us-modal--confirm .modal-dialog { max-width: 560px; }
    .us-modal .modal-content { border: 1px solid var(--ab-border); border-radius: 20px; overflow: hidden; box-shadow: var(--ab-shadow-lg); background: var(--ab-card); }
    .us-modal-hero {
        position: relative; overflow: hidden; background: var(--ab-grad); color: #fff;
        padding: 20px 22px 18px;
    }
    .us-modal-hero::before {
        content: ''; position: absolute; inset: 0; opacity: .24; pointer-events: none;
        background-image: linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
        background-size: 28px 28px;
    }
    .us-modal-hero::after {
        content: ''; position: absolute; width: 180px; height: 180px; top: -70px; right: -30px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.18), transparent 72%); pointer-events: none;
    }
    .us-modal-hero--danger { background: linear-gradient(135deg, #dc2626, #f87171); box-shadow: 0 18px 40px -12px rgba(220,38,38,.4); }
    .us-modal-hero-top { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .us-modal-badge {
        width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 22px;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22);
        backdrop-filter: blur(8px); box-shadow: inset 0 1px 0 rgba(255,255,255,.28);
    }
    .us-modal-title { font-size: 18px; font-weight: 800; margin: 0; color: #fff; }
    .us-modal-subtitle { margin: 5px 0 0; color: rgba(255,255,255,.82); font-size: 12px; line-height: 1.6; }
    .us-modal-meta { position: relative; z-index: 1; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .us-modal-meta-item { padding: 10px 12px; border-radius: 14px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.18); backdrop-filter: blur(8px); }
    .us-modal-meta-item .k { font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: rgba(255,255,255,.78); font-weight: 700; }
    .us-modal-meta-item .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: #fff; }
    .us-modal .modal-body { padding: 20px; }
    .us-modal .modal-footer { padding: 14px 20px 20px; border-top: 1px solid var(--ab-border-soft); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .us-modal-footer-note { font-size: 11.5px; color: var(--ab-text-3); line-height: 1.6; }

    /* ---------- Form fields ---------- */
    .us-form-grid { display: grid; gap: 16px; margin-top: 16px; }
    .us-form-2col { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 18px; }
    .us-float { position: relative; }
    .us-float > label {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        font-size: 13px; color: var(--ab-text-3); font-weight: 500; pointer-events: none;
        transition: all .2s cubic-bezier(.4,0,.2,1); background: transparent; z-index: 1;
    }
    .us-float textarea ~ label { top: 18px; transform: none; }
    .us-float input, .us-float select, .us-float textarea {
        width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; padding: 22px 14px 8px; font-size: 13.5px; color: var(--ab-text);
        transition: border-color .2s, box-shadow .2s; line-height: 1.5;
    }
    .us-float select { padding: 8px 14px; height: 54px; }
    .us-float select ~ label { top: 8px; transform: none; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--ab-primary); }
    .us-float textarea { min-height: 96px; resize: vertical; }
    .us-float input::placeholder, .us-float textarea::placeholder { color: transparent; }
    .us-float input:focus, .us-float textarea:focus, .us-float select:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .us-float input:focus ~ label, .us-float input:not(:placeholder-shown) ~ label,
    .us-float textarea:focus ~ label, .us-float textarea:not(:placeholder-shown) ~ label {
        top: 8px; transform: translateY(0); font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px; color: var(--ab-primary);
    }
    .us-float.is-changed input, .us-float.is-changed select {
        border-color: var(--ab-amber); background: var(--ab-amber-soft);
        box-shadow: 0 0 0 3px var(--ab-amber-soft);
    }
    .us-float.is-error input { border-color: var(--ab-red); box-shadow: 0 0 0 3px var(--ab-red-soft); }
    .us-field-undo {
        position: absolute; right: 10px; bottom: 10px; width: 28px; height: 28px; border-radius: 9px;
        border: none; background: var(--ab-amber); color: #fff; font-size: 11px; cursor: pointer;
        display: none; align-items: center; justify-content: center; transition: all .2s; z-index: 2;
    }
    .us-float.is-changed .us-field-undo { display: inline-flex; animation: usPop .25s ease; }
    @keyframes usPop { from { transform: scale(.6); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .us-field-undo:hover { background: #b45309; }

    .us-feedback { display: none; margin-top: 6px; font-size: 12px; font-weight: 600; color: var(--ab-red); align-items: center; gap: 6px; }
    .us-feedback.is-on { display: flex; }
    .us-feedback--ok { color: var(--ab-green); }
    .us-shake { animation: usShake .4s ease; }
    @keyframes usShake { 0%,100% { transform: translateX(0); } 20% { transform: translateX(-6px); } 40% { transform: translateX(6px); } 60% { transform: translateX(-4px); } 80% { transform: translateX(4px); } }

    .us-hintbox {
        display: flex; align-items: flex-start; gap: 12px;
        background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border);
        border-radius: 12px; padding: 11px 14px; font-size: 12px; color: var(--ab-text-2); line-height: 1.6;
    }
    .us-hintbox i { color: var(--ab-primary); font-size: 15px; flex-shrink: 0; margin-top: 1px; }

    /* ---------- Identity card ---------- */
    .us-identity { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: 16px; border: 1.5px solid var(--ab-border); background: var(--ab-card); }
    .us-identity-name { font-size: 14px; font-weight: 800; color: var(--ab-text); }
    .us-identity-meta { font-size: 11.5px; color: var(--ab-text-3); margin-top: 3px; }
    .us-identity-lock { margin-top: 10px; font-size: 11px; color: var(--ab-text-3); display: inline-flex; align-items: center; gap: 6px; }

    /* ---------- Status radio ---------- */
    .us-status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .us-status-opt {
        border: 1.5px solid var(--ab-border); border-radius: 14px; background: var(--ab-card);
        padding: 12px 14px; cursor: pointer; text-align: left;
        display: flex; align-items: center; gap: 10px;
        transition: all .2s cubic-bezier(.4,0,.2,1);
    }
    .us-status-opt:hover { border-color: var(--ab-primary-border); transform: translateY(-2px); }
    .us-status-opt.is-selected { border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .us-status-opt .dot {
        width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
        border: 1.5px solid var(--ab-border); display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 11px; transition: all .2s;
    }
    .us-status-opt.is-selected .dot { border-color: var(--ab-primary); background: var(--ab-primary); }
    .us-status-opt .k { font-size: 13px; font-weight: 700; color: var(--ab-text); }
    .us-status-opt .d { font-size: 11px; color: var(--ab-text-3); margin-top: 1px; }

    /* ---------- Delete confirm ---------- */
    .us-delete-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .us-delete-box { padding: 12px; border-radius: 14px; background: var(--ab-red-soft); border: 1px solid var(--ab-red-border); }
    .us-delete-box .k { font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: var(--ab-red); font-weight: 700; }
    .us-delete-box .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: var(--ab-text); word-break: break-word; }

    /* ---------- Detail modal ---------- */
    .us-detail-grid { display: grid; gap: 10px; margin-top: 4px; }
    .us-detail-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 12px 14px; border-radius: 12px; background: var(--ab-border-soft); }
    .us-detail-row .k { font-size: 11px; text-transform: uppercase; letter-spacing: .3px; color: var(--ab-text-3); font-weight: 700; }
    .us-detail-row .v { margin-top: 4px; font-size: 13.5px; font-weight: 700; color: var(--ab-text); word-break: break-word; }

    /* ---------- Password strength ---------- */
    .us-pass-wrap { position: relative; }
    .us-pass-toggle {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        width: 32px; height: 32px; border-radius: 9px; border: none; background: transparent;
        color: var(--ab-text-3); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
        transition: color .2s; z-index: 2;
    }
    .us-pass-toggle:hover { color: var(--ab-primary); }
    .us-strength { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-top: 10px; }
    .us-strength i { height: 5px; border-radius: 5px; background: var(--ab-border); transition: background .3s; }
    .us-strength-label { margin-top: 6px; font-size: 11.5px; font-weight: 700; color: var(--ab-text-3); }
    .us-pass-check { display: grid; gap: 6px; margin-top: 12px; }
    .us-pass-check-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--ab-text-2); font-weight: 600; }
    .us-pass-check-item i { color: var(--ab-text-3); font-size: 12px; transition: color .2s; }
    .us-pass-check-item.is-ok { color: var(--ab-green); }
    .us-pass-check-item.is-ok i { color: var(--ab-green); }

    /* ---------- Import wizard ---------- */
    .us-wiz-steps { display: flex; gap: 6px; margin-bottom: 18px; }
    .us-wiz-step {
        flex: 1; display: flex; align-items: center; gap: 8px;
        padding: 9px 12px; border-radius: 11px; font-size: 11.5px; font-weight: 700; color: var(--ab-text-3);
        background: var(--ab-border-soft); transition: all .25s cubic-bezier(.4,0,.2,1);
    }
    .us-wiz-step .n {
        width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
        background: var(--ab-card); border: 1.5px solid var(--ab-border); color: var(--ab-text-3);
        display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;
    }
    .us-wiz-step.is-active { background: var(--ab-primary-soft); color: var(--ab-primary); border: 1px solid var(--ab-primary-border); }
    .us-wiz-step.is-active .n { background: var(--ab-primary); border-color: var(--ab-primary); color: #fff; }
    .us-wiz-step.is-done { background: var(--ab-green-soft); color: var(--ab-green); border: 1px solid var(--ab-green-border); }
    .us-wiz-step.is-done .n { background: var(--ab-green); border-color: var(--ab-green); color: #fff; }

    .us-dropzone {
        border: 2px dashed var(--ab-primary-border); border-radius: 18px;
        background: var(--ab-primary-soft); padding: 34px 20px; text-align: center; cursor: pointer;
        transition: all .25s cubic-bezier(.4,0,.2,1);
    }
    .us-dropzone:hover, .us-dropzone.is-over { border-color: var(--ab-primary); background: #dbeafe; transform: translateY(-2px); }
    html.dark-mode .us-dropzone:hover, html.dark-mode .us-dropzone.is-over { background: rgba(61,169,252,.2); }
    .us-dropzone-icon { width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 20px; background: var(--ab-card); border: 1px solid var(--ab-border); display: flex; align-items: center; justify-content: center; font-size: 26px; color: var(--ab-primary); box-shadow: var(--ab-shadow); }
    .us-dropzone h5 { font-size: 15px; font-weight: 800; color: var(--ab-text); margin-bottom: 4px; }
    .us-dropzone p { font-size: 12px; color: var(--ab-text-3); margin: 0; }
    .us-file-badge { display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 700; background: var(--ab-green-soft); color: var(--ab-green); border: 1px solid var(--ab-green-border); }

    .us-filecard { display: flex; align-items: center; gap: 12px; padding: 13px 14px; border-radius: 14px; border: 1.5px solid var(--ab-border); background: var(--ab-card); }
    .us-filecard-icon { width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #16a34a, #4ade80); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 5px 12px -3px rgba(22,163,74,.4); flex-shrink: 0; }
    .us-filecard-name { font-size: 13px; font-weight: 800; color: var(--ab-text); word-break: break-word; }
    .us-filecard-meta { font-size: 11px; color: var(--ab-text-3); margin-top: 2px; }

    .us-checklist { display: grid; gap: 10px; }
    .us-check-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px; background: var(--ab-border-soft); border: 1px solid transparent; transition: all .3s; }
    .us-check-item.is-active { border-color: var(--ab-primary-border); background: var(--ab-primary-soft); }
    .us-check-item.is-done { border-color: var(--ab-green-border); background: var(--ab-green-soft); }
    .us-check-item .ic { width: 28px; height: 28px; border-radius: 9px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; background: var(--ab-card); color: var(--ab-text-3); border: 1px solid var(--ab-border); transition: all .3s; }
    .us-check-item.is-active .ic { color: var(--ab-primary); border-color: var(--ab-primary-border); }
    .us-check-item.is-done .ic { background: var(--ab-green); color: #fff; border-color: var(--ab-green); }
    .us-check-item .k { font-size: 13px; font-weight: 700; color: var(--ab-text); }
    .us-check-item .d { font-size: 11.5px; color: var(--ab-text-3); margin-top: 1px; }

    .us-import-preview { max-height: 260px; overflow: auto; border: 1px solid var(--ab-border); border-radius: 14px; }
    .us-import-preview table { width: 100%; border-collapse: collapse; }
    .us-import-preview th { position: sticky; top: 0; background: var(--ab-bg); font-size: 10.5px; text-transform: uppercase; letter-spacing: .4px; color: var(--ab-text-3); font-weight: 800; text-align: left; padding: 9px 12px; border-bottom: 1px solid var(--ab-border); white-space: nowrap; }
    .us-import-preview td { padding: 9px 12px; font-size: 12px; color: var(--ab-text-2); border-bottom: 1px solid var(--ab-border-soft); white-space: nowrap; }
    .us-import-preview tr.is-err td { background: var(--ab-red-soft); }
    .us-import-preview .err-txt { color: var(--ab-red); font-size: 11px; font-weight: 700; }

    .us-summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .us-summary-box { padding: 12px; border-radius: 14px; background: var(--ab-border-soft); border: 1px solid var(--ab-border); text-align: center; }
    .us-summary-box .v { font-size: 22px; font-weight: 800; color: var(--ab-text); line-height: 1; font-variant-numeric: tabular-nums; }
    .us-summary-box .k { font-size: 10.5px; color: var(--ab-text-3); margin-top: 5px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
    .us-summary-box.ok .v { color: var(--ab-green); }
    .us-summary-box.bad .v { color: var(--ab-red); }
    .us-summary-box.primary .v { color: var(--ab-primary); }

    /* ---------- Loading button ---------- */
    .us-btn-loading { pointer-events: none; opacity: .8; }
    .us-btn-loading .us-spin { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: usSpin .6s linear infinite; }
    .us-btn-loading i { display: none; }

    /* ---------- Ripple ---------- */
    .us-ripple { position: relative; overflow: hidden; }
    .us-ripple-span { position: absolute; border-radius: 50%; background: rgba(255,255,255,.35); transform: scale(0); animation: usRipple .55s linear; pointer-events: none; }
    @keyframes usRipple { to { transform: scale(4); opacity: 0; } }

    /* ---------- FAB (mobile) ---------- */
    .us-fab {
        display: none; position: fixed; right: 18px; bottom: 20px; z-index: 960;
        width: 56px; height: 56px; border-radius: 18px; border: none; cursor: pointer; text-decoration: none;
        background: var(--ab-grad); color: #fff; font-size: 22px;
        box-shadow: 0 14px 30px -8px rgba(37,99,235,.55);
        align-items: center; justify-content: center; transition: transform .22s cubic-bezier(.4,0,.2,1);
    }
    .us-fab:hover { transform: translateY(-3px) scale(1.04); color: #fff; }
    .us-fab:active { transform: scale(.94); }

    /* ---------- Result count chip ---------- */
    .us-result-count { font-size: 12.5px; color: var(--ab-text-2); font-weight: 600; }
    .us-result-count b { color: var(--ab-primary); font-variant-numeric: tabular-nums; }

    /* ---------- Responsive ---------- */
    @media (max-width: 1399.98px) {
        .us-toolbar { grid-template-columns: minmax(0, 1fr) repeat(2, minmax(140px, .4fr)) repeat(5, auto); }
    }
    @media (max-width: 1299.98px) {
        .us-kpi-grid { grid-template-columns: repeat(3, 1fr); }
        .us-toolbar { grid-template-columns: minmax(0, 1fr) 1fr 1fr auto auto auto auto; }
    }
    @media (max-width: 1199.98px) {
        .us-toolbar { top: 70px; grid-template-columns: minmax(0, 1fr) 1fr 1fr; }
        .us-toolbar .us-tool-btn, .us-toolbar .abm-btn { min-height: 44px; }
    }
    @media (max-width: 991.98px) {
        .us-kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .us-form-2col { grid-template-columns: 1fr; }
    }
    @media (max-width: 767.98px) {
        .us-toolbar { grid-template-columns: 1fr; top: 64px; }
        .us-toolbar .us-field { order: 0; }
        .us-toolbar .us-tool-btn, .us-toolbar .abm-btn { width: 100%; }
        .us-table-scroll, .us-skeleton { display: none !important; }
        .us-mobile-grid { display: grid; }
        .abm-hero { padding: 20px; }
        .abm-hero-row { flex-direction: column; align-items: stretch; }
        .abm-hero-actions { justify-content: flex-start; }
        .abm-hero-actions .abm-btn { flex: 1; justify-content: center; }
        .us-form-2col { grid-template-columns: 1fr; }
        .us-modal-meta, .us-delete-grid { grid-template-columns: 1fr; }
        .us-summary-grid { grid-template-columns: repeat(2, 1fr); }
        .us-fab { display: inline-flex; }
        .us-mobile-grid-inner { grid-template-columns: 1fr 1fr; }
        .us-status-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 575.98px) {
        .us-kpi-grid { grid-template-columns: 1fr; }
        .us-mobile-grid-inner { grid-template-columns: 1fr; }
        .us-mobile-actions { flex-direction: column; }
        .us-mobile-action { width: 100%; }
        .us-status-grid, .us-summary-grid { grid-template-columns: 1fr; }
        .us-wiz-step { flex-direction: column; text-align: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .us-mod *, .us-mod *::before, .us-mod *::after, .us-modal *, .us-modal *::before, .us-modal *::after { animation: none !important; transition: none !important; }
        .us-mod .abm-kpi:hover, .us-mod .us-icon-btn:hover, .us-mod .abm-btn:hover { transform: none !important; }
    }
</style>

@section('content')
<div class="abs-mod us-mod master-user-page">

    {{-- ===== HERO ===== --}}
    <div class="abm-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-users-cog"></i></div>
                    <div>
                        <div class="abm-chip abm-chip--blue mb-2"><i class="fas fa-address-book"></i> User Management Center</div>
                        <h3>Master User</h3>
                        <p class="abm-hero-sub">Pusat pengelolaan akun seluruh pengguna sistem — cari, import, edit profil, dan atur ulang password dalam satu workspace yang cepat dan rapi.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-calendar-day"></i> {{ $todayLabel }}</span>
                    <span class="abm-hero-badge"><i class="fas fa-users"></i> {{ $total }} user terdaftar</span>
                    <span class="abm-hero-badge"><i class="fas fa-user-check"></i> {{ $countAktif }} aktif</span>
                    <span class="abm-hero-badge"><i class="fas fa-user-clock"></i> {{ $countBelum }} belum terdaftar</span>
                    <span class="abm-hero-badge"><i class="fas fa-clock"></i> Update terakhir {{ $lastUpLabel }}</span>
                </div>
            </div>
            <div class="abm-hero-right">
                <div class="abm-hero-clock">
                    <i class="fas fa-clock"></i>
                    <div>
                        <div class="abm-clock-time" id="usLiveClock">--:--:--</div>
                        <div class="abm-clock-date" id="usLiveClockDate">{{ $todayLabel }}</div>
                    </div>
                </div>
                <div class="abm-hero-actions">
                    <button type="button" class="abm-btn abm-btn--ghost us-ripple" data-bs-toggle="modal" data-bs-target="#usModalImport"><i class="fas fa-file-import"></i> Import User</button>
                    <a href="{{ url('/master-user/create') }}" class="abm-btn abm-btn--light us-ripple"><i class="fas fa-user-plus"></i> Tambah User</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ALERTS ===== --}}
    @if ($errors->any())
        <div class="abm-alert abm-alert--danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Terjadi kesalahan saat memproses data.</strong>
                <span style="opacity:.9;">@foreach ($errors->all() as $error){{ $loop->first ? '' : ' • ' }}{{ $error }}@endforeach</span>
            </div>
            <button type="button" class="ms-auto abm-btn abm-btn--danger abm-btn--xs" onclick="window.location.reload()"><i class="fas fa-rotate-right"></i> Coba Lagi</button>
        </div>
    @endif
    @if (session()->has('errors'))
        <div class="abm-alert abm-alert--danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div><strong>Import tidak dapat diproses.</strong> <span style="opacity:.9;">{{ session('errors') }}</span></div>
            <button type="button" class="ms-auto abm-btn abm-btn--danger abm-btn--xs" onclick="window.location.reload()"><i class="fas fa-rotate-right"></i> Coba Lagi</button>
        </div>
    @endif

    {{-- ===== KPI ===== --}}
    <div class="abm-kpi-grid us-kpi-grid">
        <div class="abm-kpi us-kpi total" title="Total seluruh user yang terdaftar di sistem">
            <i class="fas fa-users abm-kpi-watermark"></i>
            <div class="abm-kpi-icon blue"><i class="fas fa-users"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $totalLoaded }}">0</div>
                <div class="abm-kpi-label">Total User</div>
                <div class="abm-progress mt-2"><span style="width:100%"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>100%</span></div>
        </div>
        <div class="abm-kpi us-kpi admin" title="User dengan akses penuh pengelolaan madrasah">
            <i class="fas fa-user-shield abm-kpi-watermark"></i>
            <div class="abm-kpi-icon amber"><i class="fas fa-user-shield"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $countAdmin }}">0</div>
                <div class="abm-kpi-label">Admin</div>
                <div class="abm-progress mt-2"><span data-progress="{{ $totalLoaded ? round($countAdmin / $totalLoaded * 100) : 0 }}"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $totalLoaded ? round($countAdmin / $totalLoaded * 100) : 0 }}%</span></div>
        </div>
        <div class="abm-kpi us-kpi guru" title="User ber-role Guru (pengajar dan wali kelas)">
            <i class="fas fa-chalkboard-teacher abm-kpi-watermark"></i>
            <div class="abm-kpi-icon sky"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $countGuru }}">0</div>
                <div class="abm-kpi-label">Guru</div>
                <div class="abm-progress mt-2"><span data-progress="{{ $totalLoaded ? round($countGuru / $totalLoaded * 100) : 0 }}"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $totalLoaded ? round($countGuru / $totalLoaded * 100) : 0 }}%</span></div>
        </div>
        <div class="abm-kpi us-kpi siswa" title="User ber-role Siswa (peserta didik)">
            <i class="fas fa-user-graduate abm-kpi-watermark"></i>
            <div class="abm-kpi-icon green"><i class="fas fa-user-graduate"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $countSiswa }}">0</div>
                <div class="abm-kpi-label">Siswa</div>
                <div class="abm-progress mt-2"><span data-progress="{{ $totalLoaded ? round($countSiswa / $totalLoaded * 100) : 0 }}"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $totalLoaded ? round($countSiswa / $totalLoaded * 100) : 0 }}%</span></div>
        </div>
        <div class="abm-kpi us-kpi kepsek" title="User ber-role Kepala Sekolah (pimpinan madrasah)">
            <i class="fas fa-landmark abm-kpi-watermark"></i>
            <div class="abm-kpi-icon violet"><i class="fas fa-landmark"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $countKepsek }}">0</div>
                <div class="abm-kpi-label">Kepala Sekolah</div>
                <div class="abm-progress mt-2"><span data-progress="{{ $totalLoaded ? round($countKepsek / $totalLoaded * 100) : 0 }}"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $totalLoaded ? round($countKepsek / $totalLoaded * 100) : 0 }}%</span></div>
        </div>
        <div class="abm-kpi us-kpi aktif" title="User yang sudah terdaftar dan dapat login">
            <i class="fas fa-user-check abm-kpi-watermark"></i>
            <div class="abm-kpi-icon us-ico-teal"><i class="fas fa-user-check"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $countAktif }}">0</div>
                <div class="abm-kpi-label">User Aktif</div>
                <div class="abm-progress abm-progress--green mt-2"><span data-progress="{{ $aktifPct }}"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $aktifPct }}%</span></div>
        </div>
        <div class="abm-kpi us-kpi belum" title="User yang belum melengkapi pendaftaran akun">
            <i class="fas fa-user-clock abm-kpi-watermark"></i>
            <div class="abm-kpi-icon us-ico-slate"><i class="fas fa-user-clock"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $countBelum }}">0</div>
                <div class="abm-kpi-label">Belum Terdaftar</div>
                <div class="abm-progress mt-2"><span data-progress="{{ 100 - $aktifPct }}"></span></div>
            </div>
            <div class="us-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ 100 - $aktifPct }}%</span></div>
        </div>
    </div>

    {{-- ===== STICKY TOOLBAR ===== --}}
    <div class="us-toolbar" id="usToolbar">
        <div class="us-field">
            <label for="usSearch">Search</label>
            <div class="abm-search">
                <i class="fas fa-search"></i>
                <input type="search" id="usSearch" placeholder="Cari nama, username, email, atau NISN..." aria-label="Cari user">
            </div>
        </div>
        <div class="us-field">
            <label for="usFilterRole">Filter Role</label>
            <div class="us-select-wrap"><i class="fas fa-user-tag"></i>
                <select id="usFilterRole" class="us-select" aria-label="Filter role">
                    <option value="">Semua Role</option>
                    <option value="1">Admin</option>
                    <option value="2">Guru</option>
                    <option value="3">Siswa</option>
                    <option value="4">BK</option>
                    <option value="5">Kepala Sekolah</option>
                </select>
            </div>
        </div>
        <div class="us-field">
            <label for="usFilterStatus">Filter Status</label>
            <div class="us-select-wrap"><i class="fas fa-user-check"></i>
                <select id="usFilterStatus" class="us-select" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Belum Terdaftar</option>
                </select>
            </div>
        </div>
        <div class="us-field">
            <label for="usPerPage">Jumlah Data</label>
            <div class="us-select-wrap"><i class="fas fa-list-ol"></i>
                <select id="usPerPage" class="us-select" aria-label="Jumlah data per halaman">
                    <option value="10">10 data</option>
                    <option value="15">15 data</option>
                    <option value="25">25 data</option>
                    <option value="50">50 data</option>
                    <option value="100">100 data</option>
                </select>
            </div>
        </div>
        <div class="us-field">
            <label for="usDensity">&nbsp;</label>
            <button type="button" id="usDensity" class="us-tool-btn" title="Ubah kepadatan tampilan tabel"><i class="fas fa-compress"></i> Density</button>
        </div>
        <div class="us-field">
            <label>&nbsp;</label>
            <button type="button" id="usReset" class="us-tool-btn" title="Reset semua filter"><i class="fas fa-arrow-rotate-left"></i> Reset</button>
        </div>
        <div class="us-field">
            <label>&nbsp;</label>
            <button type="button" class="abm-btn abm-btn--outline us-ripple" data-bs-toggle="modal" data-bs-target="#usModalImport"><i class="fas fa-file-import"></i> Import</button>
        </div>
        <div class="us-field">
            <label>&nbsp;</label>
            <a href="{{ url('/master-user/create') }}" class="abm-btn abm-btn--solid us-ripple" style="min-height:44px;"><i class="fas fa-user-plus"></i> Tambah User</a>
        </div>
    </div>

    {{-- ===== DATA GRID ===== --}}
    <div class="us-card">
        <div class="us-card-head">
            <div>
                <div class="us-card-title"><i class="fas fa-users-cog"></i> Daftar User</div>
                <div class="us-card-sub">Semua akun ditampilkan dalam data grid modern agar cepat dipindai.</div>
            </div>
            <span class="abm-chip abm-chip--blue"><i class="fas fa-users"></i> <span class="us-result-count" id="usResultCount">0</span></span>
        </div>

        <div class="us-skeleton" id="usSkeleton" aria-hidden="true">
            <div class="us-skeleton-card us-shimmer"></div>
            <div class="us-skeleton-card us-shimmer"></div>
            <div class="us-skeleton-card us-shimmer"></div>
        </div>

        <div class="us-table-scroll" id="usTableScroll">
            <div class="us-table-wrap">
                <table class="us-table" id="usTable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usTbody"></tbody>
                </table>
            </div>
        </div>

        <div class="us-mobile-grid" id="usMobileGrid"></div>

        <div class="us-empty-result" id="usNoResult" style="display:none;">
            <i class="fas fa-filter-circle-xmark"></i>
            <div class="us-empty-title" style="font-size:15px;">Tidak ada user yang cocok</div>
            <div class="us-empty-sub">Coba ubah kata kunci pencarian atau hapus filter.</div>
        </div>

        <div class="us-pagination" id="usPagination">
            <div class="us-pager" id="usPager"></div>
            <span class="us-result-count" id="usPageInfo"></span>
        </div>
    </div>

    {{-- ===== EMPTY STATE (belum ada user sama sekali) ===== --}}
    <div class="us-card mt-3" id="usGlobalEmpty" style="{{ $totalLoaded > 0 ? 'display:none;' : '' }}">
        <div class="us-empty">
            <div class="us-empty-illu"><i class="fas fa-users-cog"></i></div>
            <div class="us-empty-title">Belum ada data User.</div>
            <div class="us-empty-sub">Tambahkan user pertama atau import data dari Excel untuk mulai mengelola akun pengguna.</div>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ url('/master-user/create') }}" class="abm-btn abm-btn--solid us-ripple"><i class="fas fa-user-plus"></i> Tambah User</a>
                <button type="button" class="abm-btn abm-btn--outline us-ripple" data-bs-toggle="modal" data-bs-target="#usModalImport"><i class="fas fa-file-import"></i> Import</button>
            </div>
        </div>
    </div>

    {{-- ===== MOBILE FAB ===== --}}
    <a href="{{ url('/master-user/create') }}" class="us-fab" title="Tambah User"><i class="fas fa-user-plus"></i></a>

    {{-- ===== TOAST STACK ===== --}}
    <div class="us-toast-wrap" id="usToastStack"></div>
</div>

{{-- ===== MODAL EDIT USER ===== --}}
@include('admin.page.user.edit_user')

{{-- ===== MODAL UBAH PASSWORD ===== --}}
<div class="modal fade us-modal us-modal--pass" id="usModalPass" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="us-modal-hero" style="background:linear-gradient(135deg,#7c3aed,#a855f7);box-shadow:0 18px 40px -12px rgba(124,58,237,.4);">
                <div class="us-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="us-modal-badge"><i class="fas fa-key"></i></span>
                        <div>
                            <h4 class="us-modal-title" id="usModalPassLabel">Ubah Password</h4>
                            <p class="us-modal-subtitle">Atur ulang password untuk akun <b id="usPassNama">-</b>.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
            </div>
            <form id="usFormPass" novalidate>
                @csrf
                <input type="hidden" id="usPassUserId">
                <div class="modal-body">
                    <div class="us-float mb-3">
                        <input type="password" id="usPassPassword" name="password" placeholder=" " autocomplete="new-password">
                        <label for="usPassPassword">Password Baru</label>
                        <button type="button" class="us-pass-toggle" data-target="usPassPassword" aria-label="Tampilkan password"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="us-strength" id="usStrength">
                        <i></i><i></i><i></i><i></i>
                    </div>
                    <div class="us-strength-label" id="usStrengthLabel">Ketik password untuk melihat kekuatannya</div>
                    <div class="us-pass-check">
                        <div class="us-pass-check-item" id="usChkLen"><i class="fas fa-circle-check"></i> Minimal 8 karakter</div>
                        <div class="us-pass-check-item" id="usChkMatch"><i class="fas fa-circle-check"></i> Password cocok</div>
                    </div>
                    <div class="us-float mt-3">
                        <input type="password" id="usPassConfirm" name="password_confirm" placeholder=" " autocomplete="new-password">
                        <label for="usPassConfirm">Konfirmasi Password</label>
                        <button type="button" class="us-pass-toggle" data-target="usPassConfirm" aria-label="Tampilkan password"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="us-feedback" id="usPassFeedback"><i class="fas fa-exclamation-circle"></i><span></span></div>
                </div>
                <div class="modal-footer">
                    <div class="us-modal-footer-note"><i class="fas fa-shield-alt me-1"></i>Gunakan password yang kuat dan mudah diingat.</div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--solid us-ripple" id="usBtnPass"><i class="fas fa-key"></i> Perbarui Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL IMPORT USER (wizard 4 langkah) ===== --}}
<div class="modal fade us-modal us-modal--import" id="usModalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="us-modal-hero" style="background:linear-gradient(135deg,#16a34a,#4ade80);box-shadow:0 18px 40px -12px rgba(22,163,74,.4);">
                <div class="us-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="us-modal-badge"><i class="fas fa-file-import"></i></span>
                        <div>
                            <h4 class="us-modal-title">Import User</h4>
                            <p class="us-modal-subtitle">Import banyak user sekaligus dari file Excel.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup" onclick="usResetImport()"></button>
                </div>
            </div>
            <form id="usImportForm" action="{{ route('user.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- wizard steps indicator --}}
                    <div class="us-wiz-steps">
                        <div class="us-wiz-step is-active" id="usWiz1"><span class="n">1</span> Upload</div>
                        <div class="us-wiz-step" id="usWiz2"><span class="n">2</span> Validasi</div>
                        <div class="us-wiz-step" id="usWiz3"><span class="n">3</span> Preview</div>
                        <div class="us-wiz-step" id="usWiz4"><span class="n">4</span> Konfirmasi</div>
                    </div>

                    {{-- STEP 1: Upload --}}
                    <div id="usStep1">
                        <input type="file" name="file" id="usImportFile" accept=".xlsx,.xls,.csv" style="display:none;">
                        <div class="us-dropzone" id="usDropzone">
                            <div class="us-dropzone-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                            <h5>Tarik file ke sini atau klik untuk memilih</h5>
                            <p>Format: <b>nisn | name | email | role</b> • mendukung .xlsx, .xls, dan .csv</p>
                        </div>
                        <div class="us-filecard mt-3" id="usFileCard" style="display:none;">
                            <div class="us-filecard-icon"><i class="fas fa-file-excel"></i></div>
                            <div style="min-width:0;flex:1;">
                                <div class="us-filecard-name" id="usFileName">-</div>
                                <div class="us-filecard-meta" id="usFileMeta">-</div>
                            </div>
                            <button type="button" class="abm-btn abm-btn--danger abm-btn--xs" id="usFileRemove"><i class="fas fa-trash"></i></button>
                        </div>
                        <div class="us-feedback" id="usImportFeedback"><i class="fas fa-exclamation-circle"></i><span></span></div>
                    </div>

                    {{-- STEP 2: Validasi --}}
                    <div id="usStep2" style="display:none;">
                        <div class="us-checklist">
                            <div class="us-check-item" id="usChkRead"><span class="ic"><i class="fas fa-spinner"></i></span><div><div class="k">Membaca File</div><div class="d">Memuat isi file ke pratinjau.</div></div></div>
                            <div class="us-check-item" id="usChkCols"><span class="ic"><i class="fas fa-columns"></i></span><div><div class="k">Memvalidasi Kolom</div><div class="d">Memastikan format kolom sesuai (nisn, name, email, role).</div></div></div>
                            <div class="us-check-item" id="usChkData"><span class="ic"><i class="fas fa-table"></i></span><div><div class="k">Memvalidasi Data</div><div class="d">Memeriksa kelengkapan nama, format email, dan role.</div></div></div>
                            <div class="us-check-item" id="usChkReady"><span class="ic"><i class="fas fa-rocket"></i></span><div><div class="k">Persiapan Import</div><div class="d">Menyiapkan baris data untuk diimport.</div></div></div>
                        </div>
                    </div>

                    {{-- STEP 3: Preview --}}
                    <div id="usStep3" style="display:none;">
                        <div class="us-hintbox mb-3" id="usPreviewInfo">
                            <i class="fas fa-info-circle"></i>
                            <div id="usPreviewInfoText">Pratinjau data yang akan diimport.</div>
                        </div>
                        <div class="us-import-preview">
                            <table>
                                <thead><tr><th>NISN</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th></tr></thead>
                                <tbody id="usPreviewBody"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- STEP 4: Konfirmasi --}}
                    <div id="usStep4" style="display:none;">
                        <div class="us-hintbox" id="usConfirmInfo">
                            <i class="fas fa-info-circle"></i>
                            <div>Ringkasan data sebelum diimport.</div>
                        </div>
                        <div class="us-summary-grid">
                            <div class="us-summary-box"><div class="v" id="usSumTotal">0</div><div class="k">Total Data</div></div>
                            <div class="us-summary-box ok"><div class="v" id="usSumValid">0</div><div class="k">Valid</div></div>
                            <div class="us-summary-box bad"><div class="v" id="usSumError">0</div><div class="k">Error</div></div>
                            <div class="us-summary-box primary"><div class="v" id="usSumImport">0</div><div class="k">Akan Diimport</div></div>
                        </div>
                        <div class="abm-alert abm-alert--warn mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>Baris dengan status <b>Error</b> otomatis dilewati saat import. User baru dibuat dengan status <b>Belum Terdaftar</b> dan password awal mengikuti NISN atau <b>password</b>.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="us-modal-footer-note" id="usImportNote"><i class="fas fa-file-excel me-1"></i>nisn | name | email | role</div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" id="usImportPrev" style="display:none;" onclick="usGoStep(usImportStep - 1)"><i class="fas fa-arrow-left"></i> Kembali</button>
                        <button type="button" class="abm-btn abm-btn--outline" id="usImportNext" onclick="usGoStep(usImportStep + 1)"><i class="fas fa-arrow-right"></i> Lanjut</button>
                        <button type="submit" class="abm-btn abm-btn--solid us-ripple" id="usImportSubmit" style="display:none;"><i class="fas fa-file-import"></i> Import</button>
                        <button type="button" class="abm-btn abm-btn--danger" id="usImportCancel" onclick="usResetImport()"><i class="fas fa-times"></i> Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL DETAIL USER ===== --}}
<div class="modal fade us-modal us-modal--confirm" id="usModalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="us-modal-hero" style="background:linear-gradient(135deg,#0284c7,#0ea5e9);box-shadow:0 18px 40px -12px rgba(2,132,199,.4);">
                <div class="us-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="us-modal-badge"><i class="fas fa-id-badge"></i></span>
                        <div>
                            <h4 class="us-modal-title">Detail User</h4>
                            <p class="us-modal-subtitle">Ringkasan lengkap akun user.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="us-modal-meta">
                    <div class="us-modal-meta-item"><div class="k">ID</div><div class="v" id="usDetailId">-</div></div>
                    <div class="us-modal-meta-item"><div class="k">Status</div><div class="v" id="usDetailStatus">-</div></div>
                    <div class="us-modal-meta-item"><div class="k">Role</div><div class="v" id="usDetailRole">-</div></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="us-identity mb-3">
                    <span class="us-avatar c0" id="usDetailAvatar">?</span>
                    <div>
                        <div class="us-identity-name" id="usDetailNama">-</div>
                        <div class="us-identity-meta"><i class="fas fa-at"></i> <span id="usDetailUsername">-</span></div>
                    </div>
                </div>
                <div class="us-detail-grid">
                    <div class="us-detail-row">
                        <div><div class="k"><i class="fas fa-envelope me-1"></i>Email</div><div class="v" id="usDetailEmail">-</div></div>
                    </div>
                    <div class="us-detail-row">
                        <div><div class="k"><i class="fas fa-id-card me-1"></i>NISN</div><div class="v" id="usDetailNisn">-</div></div>
                    </div>
                    <div class="us-detail-row">
                        <div><div class="k"><i class="fas fa-user-tag me-1"></i>Role</div><div class="v" id="usDetailRoleSub">-</div></div>
                    </div>
                    <div class="us-detail-row">
                        <div><div class="k"><i class="fas fa-user-check me-1"></i>Status Akun</div><div class="v" id="usDetailInfo">-</div></div>
                    </div>
                    <div class="us-detail-row">
                        <div><div class="k"><i class="fas fa-calendar-plus me-1"></i>Dibuat</div><div class="v" id="usDetailCreated">-</div></div>
                    </div>
                    <div class="us-detail-row">
                        <div><div class="k"><i class="fas fa-clock me-1"></i>Update Terakhir</div><div class="v" id="usDetailUpdated">-</div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="us-modal-footer-note"><i class="fas fa-shield-alt me-1"></i>Data akun sensitif dikelola admin.</div>
                <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL HAPUS USER ===== --}}
<div class="modal fade us-modal us-modal--confirm" id="usModalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="us-modal-hero us-modal-hero--danger">
                <div class="us-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="us-modal-badge"><i class="fas fa-trash"></i></span>
                        <div>
                            <h4 class="us-modal-title">Hapus User</h4>
                            <p class="us-modal-subtitle">Tindakan ini permanen dan tidak dapat dibatalkan. Periksa kembali data sebelum menghapus.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="us-identity">
                    <span class="us-avatar c0" id="usDeleteAvatar">?</span>
                    <div>
                        <div class="us-identity-name" id="usDeleteNama">-</div>
                        <div class="us-identity-meta"><i class="fas fa-at"></i> <span id="usDeleteUsername">-</span></div>
                    </div>
                </div>
                <div class="us-delete-grid">
                    <div class="us-delete-box"><div class="k">ID</div><div class="v" id="usDeleteIdBox">-</div></div>
                    <div class="us-delete-box"><div class="k">Role</div><div class="v" id="usDeleteRoleBox">-</div></div>
                    <div class="us-delete-box"><div class="k">Status</div><div class="v" id="usDeleteInfoBox">-</div></div>
                </div>
                <div class="abm-alert abm-alert--danger mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>Akun user yang dihapus tidak dapat dikembalikan. Seluruh akses login akun ini langsung dinonaktifkan.</div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="us-modal-footer-note"><i class="fas fa-exclamation-triangle me-1"></i>Hapus permanen.</div>
                <div class="d-flex gap-2 flex-wrap ms-auto">
                    <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="abm-btn abm-btn--danger us-ripple" id="usBtnDelete"><i class="fas fa-trash"></i> Hapus Permanen</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const USERS = {!! $usersJson !!};
    const ROLE_META = {
        1: { label: 'Admin',            icon: 'fa-user-shield',      cls: 'us-role--1', tip: 'Akses penuh pengelolaan madrasah' },
        2: { label: 'Guru',             icon: 'fa-chalkboard-teacher', cls: 'us-role--2', tip: 'Pengajar dan wali kelas' },
        3: { label: 'Siswa',            icon: 'fa-user-graduate',    cls: 'us-role--3', tip: 'Peserta didik madrasah' },
        4: { label: 'BK',               icon: 'fa-user-gear',        cls: 'us-role--4', tip: 'Bimbingan Konseling' },
        5: { label: 'Kepala Sekolah',   icon: 'fa-user-tie',         cls: 'us-role--5', tip: 'Pimpinan madrasah' }
    };

    const state = { q: '', role: '', status: '', perPage: 10, page: 1, compact: false };

    /* ================= helpers ================= */
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const initials = n => {
        const parts = (n || 'U').trim().split(/\s+/).filter(Boolean);
        return ((parts[0]?.[0] || 'U') + (parts[1]?.[0] || '')).toUpperCase();
    };
    const avatarClass = id => 'c' + (Number(id) % 6);
    const roleMeta = r => ROLE_META[r] || { label: 'Role ' + r, icon: 'fa-user', cls: 'us-role--4', tip: '' };
    const statusChip = u => u.info == 1
        ? '<span class="abm-chip abm-chip--ok" title="Akun aktif dan dapat login"><i class="fas fa-check-circle"></i> Aktif</span>'
        : '<span class="abm-chip abm-chip--muted" title="Akun belum melengkapi pendaftaran"><i class="fas fa-user-clock"></i> Belum Terdaftar</span>';
    const roleChip = u => {
        const r = roleMeta(u.role);
        return '<span class="us-role ' + r.cls + '" title="' + esc(r.tip) + '"><i class="fas ' + r.icon + '"></i> ' + r.label + '</span>';
    };

    /* ================= toast ================= */
    function showToast(title, text, type) {
        const stack = document.getElementById('usToastStack');
        if (!stack) return;
        const toast = document.createElement('div');
        toast.className = 'us-toast ' + type;
        toast.setAttribute('role', 'status');
        toast.innerHTML = '<span class="us-toast-icon"><i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle') + '"></i></span><div><div class="us-toast-title">' + esc(title) + '</div><div class="us-toast-text">' + esc(text) + '</div></div>';
        stack.appendChild(toast);
        requestAnimationFrame(function() { toast.classList.add('is-show'); });
        setTimeout(function() {
            toast.classList.remove('is-show');
            setTimeout(function() { toast.remove(); }, 260);
        }, 3400);
    }
    window.usToast = showToast;
    @if(session('success'))
        showToast('Berhasil', '{{ session('success') }}', 'success');
    @endif

    /* ================= live clock ================= */
    (function startClock() {
        const el = document.getElementById('usLiveClock');
        if (!el) return;
        const pad = n => String(n).padStart(2, '0');
        const tick = () => { const d = new Date(); el.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds()); };
        tick();
        setInterval(tick, 1000);
    })();

    /* ================= KPI counters + progress ================= */
    (function animateKpi() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        document.querySelectorAll('[data-count]').forEach(function(el) {
            const target = parseInt(el.getAttribute('data-count'), 10) || 0;
            if (reduced) { el.textContent = target; return; }
            let cur = 0;
            const step = Math.max(1, Math.ceil(target / 22));
            const t = setInterval(function() {
                cur += step;
                if (cur >= target) { cur = target; clearInterval(t); }
                el.textContent = cur;
            }, 36);
        });
        document.querySelectorAll('[data-progress]').forEach(function(el) {
            setTimeout(function() { el.style.width = el.getAttribute('data-progress') + '%'; }, 120);
        });
    })();

    /* ================= ripple ================= */
    function ripple(e) {
        const btn = e.currentTarget;
        const rect = btn.getBoundingClientRect();
        const span = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        span.className = 'us-ripple-span';
        span.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (e.clientX - rect.left - size / 2) + 'px;top:' + (e.clientY - rect.top - size / 2) + 'px;';
        btn.appendChild(span);
        setTimeout(function() { span.remove(); }, 600);
    }
    document.querySelectorAll('.us-ripple').forEach(function(b) { b.addEventListener('click', ripple); });

    /* ================= filtering + rendering ================= */
    const tbody = document.getElementById('usTbody');
    const mobileGrid = document.getElementById('usMobileGrid');
    const noResult = document.getElementById('usNoResult');
    const pager = document.getElementById('usPager');
    const pageInfo = document.getElementById('usPageInfo');
    const resultCount = document.getElementById('usResultCount');

    function filtered() {
        return USERS.filter(function(u) {
            const q = state.q.trim().toLowerCase();
            const hitQ = !q ||
                u.name.toLowerCase().includes(q) ||
                u.username.toLowerCase().includes(q) ||
                u.email.toLowerCase().includes(q) ||
                u.nisn.toLowerCase().includes(q);
            const hitRole = state.role === '' || String(u.role) === state.role;
            const hitStatus = state.status === '' || String(u.info) === state.status;
            return hitQ && hitRole && hitStatus;
        });
    }

    function render() {
        const list = filtered();
        const total = list.length;
        resultCount.textContent = total + ' user';
        const pages = Math.max(1, Math.ceil(total / state.perPage));
        if (state.page > pages) state.page = pages;
        const start = (state.page - 1) * state.perPage;
        const pageItems = list.slice(start, start + state.perPage);

        if (total === 0) {
            tbody.innerHTML = '';
        } else {
            tbody.innerHTML = pageItems.map(function(u, i) {
                return '<tr data-id="' + u.id + '">'
                    + '<td>' + (start + i + 1) + '</td>'
                    + '<td><div class="us-user"><span class="us-avatar ' + avatarClass(u.id) + '">' + esc(initials(u.name)) + '</span><div class="us-user-main"><div class="us-user-name">' + esc(u.name) + '</div><div class="us-user-sub"><i class="fas fa-at"></i> ' + esc(u.username) + '</div></div></div></td>'
                    + '<td><span class="us-email"><i class="fas fa-envelope me-1" style="color:var(--ab-text-3);"></i>' + esc(u.email) + '</span></td>'
                    + '<td>' + statusChip(u) + '</td>'
                    + '<td>' + roleChip(u) + '</td>'
                    + '<td><div class="us-actions">'
                    + '<button type="button" class="us-icon-btn us-icon-btn--view us-act-view" title="Detail" aria-label="Detail"><i class="fas fa-eye"></i></button>'
                    + '<button type="button" class="us-icon-btn us-icon-btn--edit us-act-edit" title="Edit" aria-label="Edit"><i class="fas fa-edit"></i></button>'
                    + '<button type="button" class="us-icon-btn us-icon-btn--pass us-act-pass" title="Ubah Password" aria-label="Ubah Password"><i class="fas fa-key"></i></button>'
                    + '<button type="button" class="us-icon-btn us-icon-btn--delete us-act-delete" title="Hapus" aria-label="Hapus"><i class="fas fa-trash"></i></button>'
                    + '</div></td></tr>';
            }).join('');
        }

        if (total === 0) {
            mobileGrid.innerHTML = '';
        } else {
            mobileGrid.innerHTML = pageItems.map(function(u, i) {
                return '<article class="us-mobile-card">'
                    + '<div class="us-mobile-head"><span class="us-avatar ' + avatarClass(u.id) + '">' + esc(initials(u.name)) + '</span>'
                    + '<div style="min-width:0;"><div class="us-user-name">' + esc(u.name) + '</div><div class="us-user-sub"><i class="fas fa-at"></i> ' + esc(u.username) + '</div></div></div>'
                    + '<div class="us-stack">' + statusChip(u) + roleChip(u) + '</div>'
                    + '<div class="us-mobile-grid-inner">'
                    + '<div class="us-mobile-stat"><div class="k">Email</div><div class="v">' + esc(u.email) + '</div></div>'
                    + '<div class="us-mobile-stat"><div class="k">NISN</div><div class="v">' + (u.nisn ? esc(u.nisn) : '—') + '</div></div>'
                    + '</div>'
                    + '<div class="us-mobile-actions">'
                    + '<button type="button" class="us-mobile-action us-mobile-action--view us-act-view" data-id="' + u.id + '"><i class="fas fa-eye"></i> Detail</button>'
                    + '<button type="button" class="us-mobile-action us-mobile-action--edit us-act-edit" data-id="' + u.id + '"><i class="fas fa-edit"></i> Edit</button>'
                    + '<button type="button" class="us-mobile-action us-mobile-action--pass us-act-pass" data-id="' + u.id + '"><i class="fas fa-key"></i> Password</button>'
                    + '<button type="button" class="us-mobile-action us-mobile-action--delete us-act-delete" data-id="' + u.id + '"><i class="fas fa-trash"></i> Hapus</button>'
                    + '</div></article>';
            }).join('');
        }

        const showNoResult = total === 0 && USERS.length > 0;
        document.getElementById('usGlobalEmpty').style.display = (USERS.length === 0) ? '' : 'none';
        noResult.style.display = showNoResult ? '' : 'none';
        document.querySelectorAll('.us-table-scroll').forEach(function(el) { el.style.display = (USERS.length === 0) ? 'none' : ''; });
        document.getElementById('usPagination').style.display = (USERS.length === 0) ? 'none' : '';

        renderPager(pages, total, start);
    }

    function renderPager(pages, total, start) {
        pageInfo.textContent = total === 0
            ? 'Menampilkan 0 data'
            : 'Menampilkan ' + (start + 1) + '–' + Math.min(start + state.perPage, total) + ' dari ' + total + ' data';

        let btns = '';
        btns += '<button type="button" class="us-page-btn" data-page="' + (state.page - 1) + '" ' + (state.page <= 1 ? 'disabled' : '') + ' aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>';
        const win = [];
        const from = Math.max(1, state.page - 2);
        const to = Math.min(pages, state.page + 2);
        for (let p = from; p <= to; p++) win.push(p);
        if (from > 1) { btns += '<button type="button" class="us-page-btn" data-page="1">1</button>'; if (from > 2) btns += '<span class="us-page-btn" style="background:none;border:none;">…</span>'; }
        win.forEach(function(p) {
            btns += '<button type="button" class="us-page-btn' + (p === state.page ? ' is-active' : '') + '" data-page="' + p + '">' + p + '</button>';
        });
        if (to < pages) { if (to < pages - 1) btns += '<span class="us-page-btn" style="background:none;border:none;">…</span>'; btns += '<button type="button" class="us-page-btn" data-page="' + pages + '">' + pages + '</button>'; }
        btns += '<button type="button" class="us-page-btn" data-page="' + (state.page + 1) + '" ' + (state.page >= pages ? 'disabled' : '') + ' aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>';
        pager.innerHTML = btns;
    }

    pager.addEventListener('click', function(e) {
        const btn = e.target.closest('.us-page-btn');
        if (!btn || btn.disabled) return;
        const p = parseInt(btn.getAttribute('data-page'), 10);
        if (p && p >= 1) { state.page = p; render(); }
    });

    const searchEl = document.getElementById('usSearch');
    let debounce;
    searchEl.addEventListener('input', function() {
        clearTimeout(debounce);
        debounce = setTimeout(function() { state.q = searchEl.value; state.page = 1; render(); }, 300);
    });
    document.getElementById('usFilterRole').addEventListener('change', function(e) { state.role = e.target.value; state.page = 1; render(); });
    document.getElementById('usFilterStatus').addEventListener('change', function(e) { state.status = e.target.value; state.page = 1; render(); });
    document.getElementById('usPerPage').addEventListener('change', function(e) { state.perPage = parseInt(e.target.value, 10) || 10; state.page = 1; render(); });
    document.getElementById('usReset').addEventListener('click', function() {
        state.q = ''; state.role = ''; state.status = ''; state.page = 1;
        searchEl.value = '';
        document.getElementById('usFilterRole').value = '';
        document.getElementById('usFilterStatus').value = '';
        render();
        showToast('Filter direset', 'Semua filter dikembalikan ke kondisi awal.', 'success');
    });
    document.getElementById('usDensity').addEventListener('click', function() {
        state.compact = !state.compact;
        this.classList.toggle('is-on', state.compact);
        document.querySelectorAll('#usTable').forEach(function(t) { t.classList.toggle('us-compact', state.compact); });
    });

    /* ================= row actions (delegated) ================= */
    function findUser(id) { return USERS.find(function(u) { return String(u.id) === String(id); }); }

    tbody.addEventListener('click', function(e) {
        const btn = e.target.closest('.us-icon-btn');
        if (!btn) return;
        const row = btn.closest('tr');
        const u = findUser(row.getAttribute('data-id'));
        if (!u) return;
        if (btn.classList.contains('us-act-view')) openDetail(u);
        else if (btn.classList.contains('us-act-edit')) openUsEditModal(u);
        else if (btn.classList.contains('us-act-pass')) openPass(u);
        else if (btn.classList.contains('us-act-delete')) openDelete(u);
    });
    mobileGrid.addEventListener('click', function(e) {
        const btn = e.target.closest('.us-mobile-action');
        if (!btn) return;
        const u = findUser(btn.getAttribute('data-id'));
        if (!u) return;
        if (btn.classList.contains('us-act-view')) openDetail(u);
        else if (btn.classList.contains('us-act-edit')) openUsEditModal(u);
        else if (btn.classList.contains('us-act-pass')) openPass(u);
        else if (btn.classList.contains('us-act-delete')) openDelete(u);
    });

    /* ================= detail modal ================= */
    function openDetail(u) {
        const r = roleMeta(u.role);
        document.getElementById('usDetailId').textContent = '#' + u.id;
        document.getElementById('usDetailNama').textContent = u.name;
        document.getElementById('usDetailUsername').textContent = u.username;
        document.getElementById('usDetailAvatar').textContent = initials(u.name);
        document.getElementById('usDetailAvatar').className = 'us-avatar ' + avatarClass(u.id);
        document.getElementById('usDetailEmail').textContent = u.email || '—';
        document.getElementById('usDetailNisn').textContent = u.nisn || '—';
        document.getElementById('usDetailRole').textContent = r.label;
        document.getElementById('usDetailRoleSub').innerHTML = roleChip(u);
        document.getElementById('usDetailInfo').innerHTML = statusChip(u);
        document.getElementById('usDetailStatus').innerHTML = u.info == 1 ? '<i class="fas fa-check-circle"></i> Aktif' : '<i class="fas fa-user-clock"></i> Belum Terdaftar';
        document.getElementById('usDetailCreated').textContent = u.created;
        document.getElementById('usDetailUpdated').textContent = u.updated;
        bootstrap.Modal.getOrCreateInstance(document.getElementById('usModalDetail')).show();
    }

    /* ================= password modal ================= */
    function openPass(u) {
        document.getElementById('usPassUserId').value = u.id;
        document.getElementById('usPassNama').textContent = u.name;
        document.getElementById('usPassPassword').value = '';
        document.getElementById('usPassConfirm').value = '';
        document.getElementById('usPassFeedback').classList.remove('is-on');
        updateStrength('');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('usModalPass')).show();
        setTimeout(function() { document.getElementById('usPassPassword').focus(); }, 120);
    }

    function strengthScore(pw) {
        let score = 0;
        if (!pw) return 0;
        if (pw.length >= 8) score++;
        if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) score++;
        if (/\d/.test(pw)) score++;
        if (/[^a-zA-Z0-9]/.test(pw)) score++;
        return score;
    }
    function updateStrength(pw) {
        const score = strengthScore(pw);
        const bars = document.querySelectorAll('#usStrength i');
        const label = document.getElementById('usStrengthLabel');
        const colors = ['var(--ab-border)', '#f87171', '#fb923c', '#facc15', '#4ade80'];
        const texts = ['Ketik password untuk melihat kekuatannya', 'Lemah', 'Cukup', 'Baik', 'Kuat'];
        bars.forEach(function(b, i) {
            b.style.background = i < score ? colors[score] : colors[0];
        });
        label.textContent = texts[score];
        label.style.color = score === 0 ? 'var(--ab-text-3)' : colors[score];
        const confirm = document.getElementById('usPassConfirm').value;
        document.getElementById('usChkLen').classList.toggle('is-ok', pw.length >= 8);
        document.getElementById('usChkMatch').classList.toggle('is-ok', pw.length >= 8 && pw === confirm && pw !== '');
    }
    document.getElementById('usPassPassword').addEventListener('input', function() { updateStrength(this.value); });
    document.getElementById('usPassConfirm').addEventListener('input', function() { updateStrength(document.getElementById('usPassPassword').value); });

    document.querySelectorAll('.us-pass-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const input = document.getElementById(btn.getAttribute('data-target'));
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            btn.innerHTML = '<i class="fas ' + (isPass ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
        });
    });

    document.getElementById('usFormPass').addEventListener('submit', function(e) {
        e.preventDefault();
        const pw = document.getElementById('usPassPassword').value;
        const cf = document.getElementById('usPassConfirm').value;
        const fb = document.getElementById('usPassFeedback');
        const fbText = fb.querySelector('span');
        if (pw.length < 8) {
            fbText.textContent = 'Password minimal 8 karakter.';
            fb.classList.add('is-on');
            document.getElementById('usPassPassword').closest('.us-float').classList.add('is-error', 'us-shake');
            setTimeout(function() { document.getElementById('usPassPassword').closest('.us-float').classList.remove('us-shake'); }, 450);
            return;
        }
        if (pw !== cf) {
            fbText.textContent = 'Konfirmasi password tidak cocok.';
            fb.classList.add('is-on');
            document.getElementById('usPassConfirm').closest('.us-float').classList.add('is-error', 'us-shake');
            setTimeout(function() { document.getElementById('usPassConfirm').closest('.us-float').classList.remove('us-shake'); }, 450);
            return;
        }
        const btn = document.getElementById('usBtnPass');
        btn.classList.add('us-btn-loading');
        btn.innerHTML = '<span class="us-spin"></span> Menyimpan...';
        $.ajax({
            url: '/change-pass/' + document.getElementById('usPassUserId').value,
            type: 'PUT',
            data: { _token: $('meta[name=csrf-token]').attr('content'), password: pw },
            success: function(res) {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('usModalPass')).hide();
                    showToast('Password diperbarui', 'Password akun berhasil diubah.', 'success');
                    setTimeout(function() { window.location.reload(); }, 1000);
                }
            },
            error: function(xhr) {
                btn.classList.remove('us-btn-loading');
                btn.innerHTML = '<i class="fas fa-key"></i> Perbarui Password';
                const j = xhr.responseJSON;
                fbText.textContent = (j && j.message) ? j.message : 'Terjadi kesalahan saat menyimpan password.';
                fb.classList.add('is-on');
            }
        });
    });

    /* ================= delete modal ================= */
    function openDelete(u) {
        const r = roleMeta(u.role);
        document.getElementById('usDeleteAvatar').textContent = initials(u.name);
        document.getElementById('usDeleteAvatar').className = 'us-avatar ' + avatarClass(u.id);
        document.getElementById('usDeleteNama').textContent = u.name;
        document.getElementById('usDeleteUsername').textContent = u.username;
        document.getElementById('usDeleteIdBox').textContent = '#' + u.id;
        document.getElementById('usDeleteRoleBox').textContent = r.label;
        document.getElementById('usDeleteInfoBox').textContent = u.info == 1 ? 'Aktif' : 'Belum Terdaftar';
        deleteTarget = u;
        bootstrap.Modal.getOrCreateInstance(document.getElementById('usModalDelete')).show();
    }
    let deleteTarget = null;
    document.getElementById('usBtnDelete').addEventListener('click', function() {
        if (!deleteTarget) return;
        const userId = deleteTarget;
        const btn = this;
        btn.classList.add('us-btn-loading');
        btn.innerHTML = '<span class="us-spin"></span> Menghapus...';
        $.ajax({
            url: '/master-user/' + userId.id,
            type: 'POST',
            data: { _token: $('meta[name=csrf-token]').attr('content'), userId: userId.id },
            success: function(res) {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('usModalDelete')).hide();
                    showToast('User dihapus', 'Akun user berhasil dihapus permanen.', 'success');
                    setTimeout(function() { window.location.reload(); }, 1000);
                }
            },
            error: function() {
                btn.classList.remove('us-btn-loading');
                btn.innerHTML = '<i class="fas fa-trash"></i> Hapus Permanen';
                showToast('Gagal menghapus', 'Terjadi kesalahan saat menghapus user.', 'error');
            }
        });
    });

    /* ================= edit modal ================= */
    let editOriginal = { name: '', email: '', nisn: '' };
    let editRole = null;
    let editInfo = 1;

    function openUsEditModal(u) {
        editOriginal = { name: u.name, email: u.email, nisn: u.nisn };
        editRole = u.role;
        editInfo = u.info;
        document.getElementById('usEditUserId').value = u.id;
        document.getElementById('usEditIdMeta').textContent = '#' + u.id;
        document.getElementById('usEditRoleMeta').textContent = roleMeta(u.role).label;
        document.getElementById('usEditStatusMeta').innerHTML = u.info == 1 ? '<i class="fas fa-check-circle"></i> Terdaftar' : '<i class="fas fa-user-clock"></i> Belum Terdaftar';
        document.getElementById('usEditAvatar').textContent = initials(u.name);
        document.getElementById('usEditAvatar').className = 'us-avatar ' + avatarClass(u.id);
        document.getElementById('usEditNameHeader').textContent = u.name;
        document.getElementById('usEditUsername').textContent = u.username;
        const set = (id, v) => { const el = document.getElementById(id); el.value = v || ''; el.closest('.us-float').classList.remove('is-changed', 'is-error'); };
        set('usEditName', u.name);
        set('usEditEmail', u.email);
        set('usEditNisn', u.nisn);
        document.getElementById('usEditNisnWrap').style.display = (u.role == 3) ? '' : 'none';
        setStatusOpt(u.info);
        document.getElementById('usEditFeedback').classList.remove('is-on');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('usModalEdit')).show();
    }
    window.openUsEditModal = openUsEditModal;

    function setStatusOpt(info) {
        document.getElementById('usInfoOpt1').classList.toggle('is-selected', info == 1);
        document.getElementById('usInfoOpt0').classList.toggle('is-selected', info == 0);
    }
    document.getElementById('usInfoOpt1').addEventListener('click', function() { editInfo = 1; setStatusOpt(1); });
    document.getElementById('usInfoOpt0').addEventListener('click', function() { editInfo = 0; setStatusOpt(0); });

    const editFieldMap = { 'usEditName': 'name', 'usEditEmail': 'email', 'usEditNisn': 'nisn' };
    Object.keys(editFieldMap).forEach(function(id) {
        const el = document.getElementById(id);
        el.addEventListener('input', function() {
            const key = editFieldMap[id];
            const changed = el.value !== (editOriginal[key] || '');
            el.closest('.us-float').classList.toggle('is-changed', changed);
            el.closest('.us-float').classList.remove('is-error');
        });
    });
    document.querySelectorAll('#usFormEdit .us-field-undo').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const key = btn.getAttribute('data-for');
            const el = document.getElementById(key === 'name' ? 'usEditName' : key === 'email' ? 'usEditEmail' : 'usEditNisn');
            el.value = editOriginal[key] || '';
            el.closest('.us-float').classList.remove('is-changed', 'is-error');
        });
    });
    document.getElementById('usEditReset').addEventListener('click', function() {
        const elName = document.getElementById('usEditName');
        const elEmail = document.getElementById('usEditEmail');
        const elNisn = document.getElementById('usEditNisn');
        elName.value = editOriginal.name || ''; elName.closest('.us-float').classList.remove('is-changed', 'is-error');
        elEmail.value = editOriginal.email || ''; elEmail.closest('.us-float').classList.remove('is-changed', 'is-error');
        elNisn.value = editOriginal.nisn || ''; elNisn.closest('.us-float').classList.remove('is-changed', 'is-error');
        setStatusOpt(editInfo);
        showToast('Perubahan dibatalkan', 'Field dikembalikan ke nilai semula.', 'success');
    });

    document.getElementById('usFormEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('usEditName').value.trim();
        const email = document.getElementById('usEditEmail').value.trim();
        const nisn = document.getElementById('usEditNisn').value.trim();
        const fb = document.getElementById('usEditFeedback');
        const fbText = fb.querySelector('span');
        fb.classList.remove('is-on');
        let firstErr = null;
        const mark = (id, msg) => {
            const wrap = document.getElementById(id).closest('.us-float');
            wrap.classList.add('is-error');
            if (!firstErr) { firstErr = wrap; fbText.textContent = msg; fb.classList.add('is-on'); }
        };
        if (!name) mark('usEditName', 'Nama harus diisi.');
        else if (name.length > 255) mark('usEditName', 'Nama maksimal 255 karakter.');
        else if (!/^[a-zA-Z\s\.,;\'\-]+$/.test(name)) mark('usEditName', 'Nama hanya boleh berisi huruf, spasi, dan tanda baca.');
        if (!email) mark('usEditEmail', 'Email harus diisi.');
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) mark('usEditEmail', 'Format email tidak valid.');
        if (editRole == 3) {
            if (!nisn) mark('usEditNisn', 'NISN wajib diisi untuk siswa.');
            else if (!/^\d{10}$/.test(nisn)) mark('usEditNisn', 'NISN harus tepat 10 digit.');
        }
        if (firstErr) { firstErr.classList.add('us-shake'); setTimeout(function() { firstErr.classList.remove('us-shake'); }, 450); return; }
        const btn = document.getElementById('usBtnEdit');
        btn.classList.add('us-btn-loading');
        btn.innerHTML = '<span class="us-spin"></span> Menyimpan...';
        $.ajax({
            url: '/master-user/' + document.getElementById('usEditUserId').value,
            type: 'PUT',
            data: { _token: $('meta[name=csrf-token]').attr('content'), nisn: editRole == 3 ? nisn : '', name: name, email: email, role: editRole, info: editInfo },
            success: function(res) {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('usModalEdit')).hide();
                    showToast('User diperbarui', 'Perubahan profil berhasil disimpan.', 'success');
                    setTimeout(function() { window.location.reload(); }, 1000);
                } else {
                    btn.classList.remove('us-btn-loading');
                    btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
                    fbText.textContent = res.message || 'Data tidak dapat disimpan.';
                    fb.classList.add('is-on');
                }
            },
            error: function(xhr) {
                btn.classList.remove('us-btn-loading');
                btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
                const j = xhr.responseJSON;
                let msg = (j && j.message) ? j.message : 'Terjadi kesalahan saat menyimpan.';
                if (j && j.errors) {
                    const firstKey = Object.keys(j.errors)[0];
                    if (firstKey) msg = j.errors[firstKey][0];
                }
                fbText.textContent = msg;
                fb.classList.add('is-on');
            }
        });
    });

    /* ================= import wizard ================= */
    let usImportStep = 1;
    let usFile = null;
    let usParsedRows = [];

    function usSetStep(n) {
        usImportStep = n;
        document.getElementById('usStep1').style.display = n === 1 ? '' : 'none';
        document.getElementById('usStep2').style.display = n === 2 ? '' : 'none';
        document.getElementById('usStep3').style.display = n === 3 ? '' : 'none';
        document.getElementById('usStep4').style.display = n === 4 ? '' : 'none';
        document.getElementById('usWiz1').className = 'us-wiz-step' + (n === 1 ? ' is-active' : (n > 1 ? ' is-done' : ''));
        document.getElementById('usWiz2').className = 'us-wiz-step' + (n === 2 ? ' is-active' : (n > 2 ? ' is-done' : ''));
        document.getElementById('usWiz3').className = 'us-wiz-step' + (n === 3 ? ' is-active' : (n > 3 ? ' is-done' : ''));
        document.getElementById('usWiz4').className = 'us-wiz-step' + (n === 4 ? ' is-active' : (n > 4 ? ' is-done' : ''));
        document.getElementById('usImportNext').style.display = (n === 4) ? 'none' : '';
        document.getElementById('usImportPrev').style.display = (n === 1) ? 'none' : '';
        document.getElementById('usImportSubmit').style.display = (n === 4) ? '' : 'none';
        document.getElementById('usImportCancel').textContent = (n === 4) ? 'Batal' : 'Batal';
    }
    window.usGoStep = function(n) {
        if (n === 2 && !usFile) {
            document.getElementById('usImportFeedback').querySelector('span').textContent = 'Pilih file Excel terlebih dahulu.';
            document.getElementById('usImportFeedback').classList.add('is-on');
            document.getElementById('usDropzone').classList.add('us-shake');
            setTimeout(function() { document.getElementById('usDropzone').classList.remove('us-shake'); }, 450);
            return;
        }
        if (n === 3 && usFile) usRunChecklist();
        if (n === 4 && usParsedRows.length > 0) usBuildPreview();
        usSetStep(n);
    };

    function usResetImport() {
        usFile = null;
        usParsedRows = [];
        usSetStep(1);
        document.getElementById('usImportFile').value = '';
        document.getElementById('usFileCard').style.display = 'none';
        document.getElementById('usImportFeedback').classList.remove('is-on');
    }
    window.usResetImport = usResetImport;

    const dropzone = document.getElementById('usDropzone');
    const fileInput = document.getElementById('usImportFile');
    dropzone.addEventListener('click', function() { fileInput.click(); });
    dropzone.addEventListener('dragover', function(e) { e.preventDefault(); dropzone.classList.add('is-over'); });
    dropzone.addEventListener('dragleave', function() { dropzone.classList.remove('is-over'); });
    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('is-over');
        if (e.dataTransfer.files && e.dataTransfer.files[0]) usSelectFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener('change', function() { if (this.files[0]) usSelectFile(this.files[0]); });

    function usSelectFile(f) {
        const fb = document.getElementById('usImportFeedback');
        const okType = /\.(xlsx|xls|csv)$/i.test(f.name);
        if (!okType) {
            fb.querySelector('span').textContent = 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv.';
            fb.classList.add('is-on');
            return;
        }
        usFile = f;
        fb.classList.remove('is-on');
        document.getElementById('usFileName').textContent = f.name;
        document.getElementById('usFileMeta').textContent = usFormatBytes(f.size) + ' • ' + (f.type || 'Excel / CSV');
        document.getElementById('usFileCard').style.display = 'flex';
        usParsedRows = [];
        document.getElementById('usImportNext').textContent = 'Lanjut';
        usSetStep(1);
    }
    document.getElementById('usFileRemove').addEventListener('click', function() {
        usFile = null;
        fileInput.value = '';
        document.getElementById('usFileCard').style.display = 'none';
    });

    function usFormatBytes(b) {
        if (!b) return '0 B';
        const k = 1024, units = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(b) / Math.log(k));
        return parseFloat((b / Math.pow(k, i)).toFixed(1)) + ' ' + units[i];
    }

    function usRunChecklist() {
        const items = [
            ['usChkRead', 350],
            ['usChkCols', 1000],
            ['usChkData', 1650],
            ['usChkReady', 2300]
        ];
        items.forEach(function(it) {
            setTimeout(function() {
                const el = document.getElementById(it[0]);
                el.classList.remove('is-active');
                el.classList.add('is-done');
                el.querySelector('.ic').innerHTML = '<i class="fas fa-check"></i>';
            }, it[1]);
        });
        setTimeout(function() {
            if (usFile && /\.csv$/i.test(usFile.name)) {
                const reader = new FileReader();
                reader.onload = function(ev) { usParsedRows = usParseCsv(String(ev.target.result)); usBuildPreview(); usSetStep(3); };
                reader.onerror = function() { usParsedRows = []; usSetStep(3); };
                reader.readAsText(usFile);
            } else {
                usParsedRows = [];
                usSetStep(3);
            }
        }, 2800);
    }

    function usParseCsv(text) {
        const rows = [];
        let row = [], cur = '', inQ = false;
        for (let i = 0; i < text.length; i++) {
            const c = text[i];
            if (inQ) {
                if (c === '"') { if (text[i + 1] === '"') { cur += '"'; i++; } else inQ = false; }
                else cur += c;
            } else if (c === '"') { inQ = true; }
            else if (c === ',') { row.push(cur); cur = ''; }
            else if (c === '\n') { row.push(cur); rows.push(row); row = []; cur = ''; }
            else if (c !== '\r') { cur += c; }
        }
        if (cur !== '' || row.length) { row.push(cur); rows.push(row); }
        return rows.filter(r => r.some(c => String(c).trim() !== ''));
    }

    const ROLE_ALIAS = { '1': 1, '2': 2, '3': 3, admin: 1, administrator: 1, guru: 2, siswa: 3, murid: 3 };
    function usParseRole(v) {
        const s = String(v || '').trim().toLowerCase();
        if (ROLE_ALIAS[s] !== undefined) return ROLE_ALIAS[s];
        const n = parseInt(s, 10);
        return [1, 2, 3].indexOf(n) !== -1 ? n : null;
    }

    function usBuildRows() {
        const parsed = usParsedRows;
        const out = { rows: [], valid: 0, error: 0 };
        if (!parsed.length) return out;
        let headerIdx = -1;
        const first = parsed[0].map(c => String(c).trim().toLowerCase());
        if (first.indexOf('name') !== -1 && first.indexOf('email') !== -1) headerIdx = 0;
        const body = headerIdx === -1 ? parsed : parsed.slice(1);
        const seenEmail = {};
        body.forEach(function(r) {
            const get = i => String(r[i] !== undefined ? r[i] : '').trim();
            const nisn = get(0);
            const name = get(1);
            const email = get(2);
            const roleRaw = get(3);
            const errs = [];
            if (!name) errs.push('Nama kosong');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errs.push('Email tidak valid');
            const role = usParseRole(roleRaw);
            if (role === null) errs.push('Role tidak valid (1/2/3)');
            if (nisn && !/^\d{10}$/.test(nisn)) errs.push('NISN harus 10 digit');
            if (email) {
                const key = email.toLowerCase();
                if (seenEmail[key]) errs.push('Email duplikat dalam file');
                else seenEmail[key] = true;
            }
            const ok = errs.length === 0;
            if (ok) out.valid++; else out.error++;
            out.rows.push({ nisn, name, email, role, roleLabel: role === null ? roleRaw : ROLE_META[role].label, ok, errs });
        });
        return out;
    }

    function usBuildPreview() {
        const data = usBuildRows();
        const body = document.getElementById('usPreviewBody');
        if (!data.rows.length) {
            body.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--ab-text-3);padding:16px;">Tidak ada baris data yang terbaca dari file ini.</td></tr>';
            document.getElementById('usPreviewInfoText').textContent = 'Tidak ada baris data terbaca. Pastikan kolom berisi nisn | name | email | role.';
            document.getElementById('usSumTotal').textContent = '0';
            document.getElementById('usSumValid').textContent = '0';
            document.getElementById('usSumError').textContent = '0';
            document.getElementById('usSumImport').textContent = '0';
            return;
        }
        body.innerHTML = data.rows.slice(0, 50).map(function(r) {
            return '<tr class="' + (r.ok ? '' : 'is-err') + '">'
                + '<td>' + esc(r.nisn || '—') + '</td>'
                + '<td>' + esc(r.name) + '</td>'
                + '<td>' + esc(r.email) + '</td>'
                + '<td>' + esc(r.roleLabel || '—') + '</td>'
                + '<td>' + (r.ok ? '<span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Valid</span>' : '<span class="err-txt"><i class="fas fa-exclamation-circle me-1"></i>' + esc(r.errs.join(', ')) + '</span>') + '</td>'
                + '</tr>';
        }).join('') + (data.rows.length > 50 ? '<tr><td colspan="5" style="text-align:center;color:var(--ab-text-3);padding:12px;">… dan ' + (data.rows.length - 50) + ' baris lainnya</td></tr>' : '');
        document.getElementById('usPreviewInfoText').innerHTML = /\.csv$/i.test(usFile.name)
            ? 'Ditemukan <b>' + data.rows.length + '</b> baris data. Baris dengan error akan dilewati saat import.'
            : 'Pratinjau otomatis tersedia untuk file CSV. Untuk .xlsx/.xls, validasi dilakukan di server saat import.';
        document.getElementById('usSumTotal').textContent = data.rows.length;
        document.getElementById('usSumValid').textContent = data.valid;
        document.getElementById('usSumError').textContent = data.error;
        document.getElementById('usSumImport').textContent = data.valid;
    }

    document.getElementById('usImportForm').addEventListener('submit', function(e) {
        if (!usFile) return;
        e.preventDefault();
        const btn = document.getElementById('usImportSubmit');
        btn.classList.add('us-btn-loading');
        btn.innerHTML = '<span class="us-spin"></span> Mengimport...';
        const fd = new FormData();
        fd.append('_token', $('meta[name=csrf-token]').attr('content'));
        fd.append('file', usFile, usFile.name);
        $.ajax({
            url: '{{ route('user.import') }}',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function() {
                bootstrap.Modal.getInstance(document.getElementById('usModalImport')).hide();
                showToast('Import berhasil', 'Data user berhasil diimport.', 'success');
                setTimeout(function() { window.location.reload(); }, 1000);
            },
            error: function(xhr) {
                btn.classList.remove('us-btn-loading');
                btn.innerHTML = '<i class="fas fa-file-import"></i> Import';
                const j = xhr.responseJSON;
                const msg = (j && j.message) ? j.message : 'Terjadi kesalahan saat import. Periksa kembali file.';
                showToast('Import gagal', msg, 'error');
            }
        });
    });

    /* ================= skeleton boot ================= */
    setTimeout(function() {
        document.getElementById('usSkeleton').style.display = 'none';
        render();
    }, 450);
});
</script>
@endpush
