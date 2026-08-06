@extends('layouts.main')
@section('title', 'Master Guru')
@section('content')
@include('component.admin.absensi-module')

@php
    $todayLabel = now()->translatedFormat('l, d F Y');
    $totalGuru   = $gurus->count();
    $withNip     = $gurus->filter(fn($g) => filled($g->nip))->count();
    $withoutNip  = $totalGuru - $withNip;
    $withHp      = $gurus->filter(fn($g) => filled($g->no_hp))->count();
    $withoutHp   = $totalGuru - $withHp;
    $unlinked    = $users->count();
    $nipPct      = $totalGuru ? round($withNip / $totalGuru * 100) : 0;
    $hpPct       = $totalGuru ? round($withHp / $totalGuru * 100) : 0;
    $lastUp      = $gurus->max('updated_at');
    $lastUpLabel = $lastUp ? $lastUp->translatedFormat('d M Y, H:i') : 'Belum ada pembaruan';
    $nextKode    = 'GR' . str_pad(($gurus->max('id') ?? 0) + 1, 3, '0', STR_PAD_LEFT);
    $gurusJson   = $gurus->map(fn($g) => [
        'id' => $g->id,
        'kode' => (string) $g->kode_guru,
        'nama' => (string) $g->nama,
        'nip' => (string) $g->nip,
        'no_hp' => (string) $g->no_hp,
        'alamat' => (string) $g->alamat,
        'updated' => $g->updated_at ? $g->updated_at->translatedFormat('d M Y, H:i') : '-',
    ])->values()->toJson(JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
@endphp

<style>
    /* ============================================================
       MASTER GURU — Guru Management Center
       Built on the shared ABSENSI design system (.abs-mod / .abm-*)
       ============================================================ */
    .gr-mod { margin-top: 22px; }
    .gr-mod .abm-hero-sub { max-width: 720px; }

    /* ---------- KPI accent colors ---------- */
    .gr-kpi.total   { --ab-kpi-glow: rgba(37,99,235,.08);  --ab-kpi-wm: #2563eb; }
    .gr-kpi.nip     { --ab-kpi-glow: rgba(22,163,74,.08);  --ab-kpi-wm: #16a34a; }
    .gr-kpi.nipp    { --ab-kpi-glow: rgba(217,119,6,.08);  --ab-kpi-wm: #d97706; }
    .gr-kpi.hp      { --ab-kpi-glow: rgba(2,132,199,.08);  --ab-kpi-wm: #0284c7; }
    .gr-kpi.hpp     { --ab-kpi-glow: rgba(220,38,38,.08);  --ab-kpi-wm: #dc2626; }

    .gr-kpi { position: relative; }
    .gr-kpi-foot {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        margin-top: 10px; padding-top: 9px; border-top: 1px dashed var(--ab-border);
        font-size: 10.5px; color: var(--ab-text-3); font-weight: 600;
    }
    .gr-kpi-foot i { font-size: 10px; }

    /* ---------- Sticky filter toolbar ---------- */
    .gr-toolbar {
        position: sticky; top: 78px; z-index: 940;
        display: grid; grid-template-columns: minmax(0, 1.3fr) repeat(2, minmax(150px, .45fr)) minmax(120px, .3fr) auto auto auto;
        gap: 12px; align-items: end;
        background: rgba(255,255,255,.92); border: 1px solid var(--ab-border);
        border-radius: 18px; padding: 14px 16px;
        box-shadow: 0 12px 28px -24px rgba(15,23,42,.18);
        backdrop-filter: blur(12px); margin-bottom: 18px;
    }
    html.dark-mode .gr-toolbar { background: rgba(13,47,56,.92); }

    .gr-field { display: flex; flex-direction: column; gap: 5px; }
    .gr-field label { font-size: 10.5px; font-weight: 700; color: var(--ab-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .gr-select-wrap { position: relative; }
    .gr-select-wrap > i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ab-text-3); font-size: 12px; z-index: 2; pointer-events: none; }
    .gr-select {
        width: 100%; min-height: 44px;
        border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; padding: 0 14px 0 34px;
        font-size: 12.5px; color: var(--ab-text); font-weight: 600;
        transition: border-color .2s, box-shadow .2s;
    }
    .gr-select:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }

    .gr-tool-btn {
        min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        border: 1.5px solid var(--ab-border); background: var(--ab-card); border-radius: 12px;
        padding: 0 14px; font-size: 12px; font-weight: 700; color: var(--ab-text-2); cursor: pointer;
        transition: all .2s cubic-bezier(.4,0,.2,1); white-space: nowrap;
    }
    .gr-tool-btn:hover { border-color: var(--ab-primary-border); color: var(--ab-primary); background: var(--ab-primary-soft); }
    .gr-tool-btn.is-on { background: var(--ab-primary-soft); color: var(--ab-primary); border-color: var(--ab-primary-border); }

    /* ---------- Data grid card ---------- */
    .gr-card {
        background: var(--ab-card); border: 1px solid var(--ab-border);
        border-radius: 18px; box-shadow: var(--ab-shadow); overflow: hidden;
    }
    .gr-card-head {
        display: flex; justify-content: space-between; align-items: center; gap: 14px;
        padding: 18px 20px 14px; flex-wrap: wrap;
    }
    .gr-card-title { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 800; color: var(--ab-text); }
    .gr-card-title i { color: var(--ab-primary); }
    .gr-card-sub { margin-top: 4px; font-size: 12px; color: var(--ab-text-3); }

    /* ---------- Premium table ---------- */
    .gr-table-scroll { overflow: auto; max-height: min(70vh, 1000px); border-radius: 0 0 18px 18px; }
    .gr-table-wrap { padding: 0 18px 4px; }
    .gr-table {
        width: 100%; border-collapse: separate; border-spacing: 0 10px;
        margin: 0 !important; background: transparent;
    }
    .gr-table thead th {
        position: sticky; top: 0; z-index: 3;
        background: var(--ab-card);
        padding: 0 16px 8px;
        font-size: 11px; text-transform: uppercase; letter-spacing: .5px;
        color: var(--ab-text-3); font-weight: 800; text-align: left; white-space: nowrap;
        border-bottom: 1px solid var(--ab-border);
    }
    .gr-table tbody td {
        background: var(--ab-card);
        border-top: 1px solid var(--ab-border); border-bottom: 1px solid var(--ab-border);
        padding: 14px 12px; font-size: 13px; color: var(--ab-text-2); vertical-align: middle;
        transition: background .22s, border-color .22s, transform .22s;
    }
    .gr-table tbody tr:nth-child(even) td { background: var(--ab-bg); }
    .gr-table .abm-chip { white-space: normal; }
    .gr-table tbody td:first-child {
        border-left: 1px solid var(--ab-border); border-radius: 16px 0 0 16px;
        width: 52px; text-align: center; color: var(--ab-text-3); font-weight: 700;
    }
    .gr-table tbody td:last-child { border-right: 1px solid var(--ab-border); border-radius: 0 16px 16px 0; }
    .gr-table tbody tr { transition: transform .22s ease; }
    .gr-table tbody tr:hover td { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); }
    .gr-table tbody tr:hover { transform: translateY(-2px); }

    /* Compact density */
    .gr-table.gr-compact tbody td { padding: 8px 12px; font-size: 12.5px; }
    .gr-table.gr-compact tbody tr td:first-child { width: 44px; }
    .gr-table.gr-compact .gr-guru-avatar { width: 36px; height: 36px; font-size: 12px; border-radius: 11px; }

    .gr-guru { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .gr-guru-avatar {
        width: 44px; height: 44px; border-radius: 14px; flex-shrink: 0; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 800; letter-spacing: .5px;
        box-shadow: 0 4px 10px -2px rgba(15,23,42,.25);
    }
    .gr-guru-avatar.c0 { background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 4px 10px -2px rgba(37,99,235,.4); }
    .gr-guru-avatar.c1 { background: linear-gradient(135deg, #7c3aed, #a855f7); box-shadow: 0 4px 10px -2px rgba(124,58,237,.4); }
    .gr-guru-avatar.c2 { background: linear-gradient(135deg, #0ea5e9, #22d3ee); box-shadow: 0 4px 10px -2px rgba(14,165,233,.4); }
    .gr-guru-avatar.c3 { background: linear-gradient(135deg, #16a34a, #4ade80); box-shadow: 0 4px 10px -2px rgba(22,163,74,.4); }
    .gr-guru-avatar.c4 { background: linear-gradient(135deg, #ea580c, #fb923c); box-shadow: 0 4px 10px -2px rgba(234,88,12,.4); }
    .gr-guru-avatar.c5 { background: linear-gradient(135deg, #db2777, #f472b6); box-shadow: 0 4px 10px -2px rgba(219,39,119,.4); }
    .gr-guru-main { min-width: 0; }
    .gr-guru-name { font-size: 14px; font-weight: 800; color: var(--ab-text); line-height: 1.3; }
    .gr-guru-code { margin-top: 3px; font-size: 11px; color: var(--ab-text-3); font-weight: 600; letter-spacing: .3px; }

    .gr-stack { display: flex; flex-wrap: wrap; gap: 6px; }
    .gr-actions { display: flex; justify-content: flex-end; gap: 8px; }
    .gr-icon-btn {
        width: 42px; height: 42px; border-radius: 12px;
        border: 1px solid var(--ab-border); background: var(--ab-card);
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--ab-text-2); font-size: 15px; cursor: pointer;
        transition: all .22s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 4px 10px -6px rgba(15,23,42,.18);
    }
    .gr-icon-btn:hover { transform: translateY(-2px); }
    .gr-icon-btn--view { color: var(--ab-primary); }
    .gr-icon-btn--view:hover { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); box-shadow: 0 10px 20px -10px rgba(37,99,235,.3); }
    .gr-icon-btn--edit { color: #d97706; }
    .gr-icon-btn--edit:hover { background: var(--ab-amber-soft); border-color: var(--ab-amber-border); box-shadow: 0 10px 20px -10px rgba(217,119,6,.28); }
    .gr-icon-btn--delete { color: var(--ab-red); }
    .gr-icon-btn--delete:hover { background: var(--ab-red-soft); border-color: var(--ab-red-border); box-shadow: 0 10px 20px -10px rgba(220,38,38,.28); }

    /* ---------- Mobile cards ---------- */
    .gr-mobile-grid { display: none; padding: 0 18px 18px; gap: 14px; }
    .gr-mobile-card {
        background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 18px;
        box-shadow: var(--ab-shadow); padding: 16px; display: grid; gap: 14px;
    }
    .gr-mobile-head { display: flex; align-items: center; gap: 12px; }
    .gr-mobile-grid-inner { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .gr-mobile-stat { background: var(--ab-border-soft); border-radius: 12px; padding: 10px 12px; }
    .gr-mobile-stat .k { font-size: 10px; color: var(--ab-text-3); text-transform: uppercase; letter-spacing: .3px; font-weight: 700; }
    .gr-mobile-stat .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: var(--ab-text); word-break: break-word; }
    .gr-mobile-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .gr-mobile-action {
        flex: 1; min-height: 42px; border-radius: 12px; border: 1px solid var(--ab-border);
        background: var(--ab-card); display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        font-size: 12px; font-weight: 700; color: var(--ab-text-2); text-decoration: none;
        transition: all .22s cubic-bezier(.4,0,.2,1); cursor: pointer;
    }
    .gr-mobile-action--view { color: var(--ab-primary); }
    .gr-mobile-action--view:hover { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); }
    .gr-mobile-action--edit { color: #d97706; }
    .gr-mobile-action--edit:hover { background: var(--ab-amber-soft); border-color: var(--ab-amber-border); }
    .gr-mobile-action--delete { color: var(--ab-red); }
    .gr-mobile-action--delete:hover { background: var(--ab-red-soft); border-color: var(--ab-red-border); }

    /* ---------- Skeleton ---------- */
    .gr-skeleton { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; padding: 4px 18px 18px; }
    .gr-skeleton-card { min-height: 96px; border-radius: 18px; }
    .gr-shimmer {
        background: linear-gradient(90deg, var(--ab-border-soft) 25%, rgba(148,163,184,.18) 50%, var(--ab-border-soft) 75%);
        background-size: 800px 100%; border-radius: 12px;
        animation: grShimmer 1.4s linear infinite;
    }
    @keyframes grShimmer { 0% { background-position: -800px 0; } 100% { background-position: 800px 0; } }

    /* ---------- Empty states ---------- */
    .gr-empty { text-align: center; padding: 48px 20px 40px; }
    .gr-empty-illu {
        position: relative; width: 96px; height: 96px; margin: 0 auto 18px;
        border-radius: 26px; background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border);
        display: flex; align-items: center; justify-content: center; color: var(--ab-primary); font-size: 38px;
    }
    .gr-empty-illu::after {
        content: ''; position: absolute; inset: -12px; border-radius: 34px;
        border: 1.5px dashed var(--ab-primary-border); animation: grSpin 22s linear infinite;
    }
    @keyframes grSpin { to { transform: rotate(360deg); } }
    .gr-empty-title { font-size: 16px; font-weight: 800; color: var(--ab-text); margin-bottom: 5px; }
    .gr-empty-sub { font-size: 12.5px; color: var(--ab-text-3); margin-bottom: 20px; }
    .gr-empty-result { text-align: center; padding: 40px 20px; }
    .gr-empty-result i { font-size: 40px; opacity: .35; color: var(--ab-primary); margin-bottom: 10px; }

    /* ---------- Pagination ---------- */
    .gr-pagination {
        display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
        padding: 4px 20px 20px; font-size: 13px; color: var(--ab-text-3);
    }
    .gr-pager { display: flex; gap: 6px; flex-wrap: wrap; }
    .gr-page-btn {
        min-width: 38px; height: 38px; padding: 0 10px; border-radius: 11px;
        border: 1px solid var(--ab-border); background: var(--ab-card);
        color: var(--ab-text-2); font-size: 12px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        transition: all .2s cubic-bezier(.4,0,.2,1);
    }
    .gr-page-btn:hover { border-color: var(--ab-primary-border); color: var(--ab-primary); }
    .gr-page-btn.is-active { background: var(--ab-grad); border-color: transparent; color: #fff; box-shadow: 0 12px 20px -14px rgba(37,99,235,.45); }
    .gr-page-btn:disabled { opacity: .45; cursor: not-allowed; background: var(--ab-border-soft); }

    /* ---------- Toasts ---------- */
    .gr-toast-wrap { position: fixed; top: 92px; right: 18px; z-index: 1200; display: grid; gap: 10px; width: min(360px, calc(100vw - 24px)); pointer-events: none; }
    .gr-toast {
        display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 16px;
        background: rgba(255,255,255,.96); border: 1px solid var(--ab-border);
        box-shadow: 0 18px 34px -24px rgba(15,23,42,.24); backdrop-filter: blur(12px);
        opacity: 0; transform: translateY(-10px);
        transition: opacity .25s ease, transform .25s ease; pointer-events: auto;
    }
    html.dark-mode .gr-toast { background: rgba(13,47,56,.94); }
    .gr-toast.is-show { opacity: 1; transform: translateY(0); }
    .gr-toast-icon { width: 40px; height: 40px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .gr-toast.success .gr-toast-icon { background: var(--ab-green-soft); color: var(--ab-green); }
    .gr-toast.error .gr-toast-icon { background: var(--ab-red-soft); color: var(--ab-red); }
    .gr-toast-title { font-size: 13px; font-weight: 800; color: var(--ab-text); }
    .gr-toast-text { margin-top: 2px; font-size: 12px; line-height: 1.6; color: var(--ab-text-2); }

    /* ---------- Modal shell (shared) ---------- */
    .gr-modal .modal-dialog { max-width: 680px; }
    .gr-modal .modal-content { border: 1px solid var(--ab-border); border-radius: 20px; overflow: hidden; box-shadow: var(--ab-shadow-lg); background: var(--ab-card); }
    .gr-modal-hero {
        position: relative; overflow: hidden; background: var(--ab-grad); color: #fff;
        padding: 20px 22px 18px;
    }
    .gr-modal-hero::before {
        content: ''; position: absolute; inset: 0; opacity: .24; pointer-events: none;
        background-image: linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
        background-size: 28px 28px;
    }
    .gr-modal-hero::after {
        content: ''; position: absolute; width: 180px; height: 180px; top: -70px; right: -30px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.18), transparent 72%); pointer-events: none;
    }
    .gr-modal-hero--danger { background: linear-gradient(135deg, #dc2626, #f87171); box-shadow: 0 18px 40px -12px rgba(220,38,38,.4); }
    .gr-modal-hero-top { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .gr-modal-badge {
        width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 22px;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22);
        backdrop-filter: blur(8px); box-shadow: inset 0 1px 0 rgba(255,255,255,.28);
    }
    .gr-modal-title { font-size: 18px; font-weight: 800; margin: 0; color: #fff; }
    .gr-modal-subtitle { margin: 5px 0 0; color: rgba(255,255,255,.82); font-size: 12px; line-height: 1.6; }
    .gr-modal-meta { position: relative; z-index: 1; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .gr-modal-meta-item { padding: 10px 12px; border-radius: 14px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.18); backdrop-filter: blur(8px); }
    .gr-modal-meta-item .k { font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: rgba(255,255,255,.78); font-weight: 700; }
    .gr-modal-meta-item .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: #fff; }
    .gr-modal .modal-body { padding: 20px; }
    .gr-modal-panel { background: var(--ab-bg); border: 1px solid var(--ab-border); border-radius: 18px; padding: 16px; box-shadow: inset 0 1px 0 rgba(255,255,255,.5); }
    .gr-modal-panel h4 { margin: 0 0 6px; font-size: 14px; font-weight: 800; color: var(--ab-text); }
    .gr-modal-panel p { margin: 0; color: var(--ab-text-3); font-size: 12px; line-height: 1.6; }
    .gr-modal .modal-footer { padding: 14px 20px 20px; border-top: 1px solid var(--ab-border-soft); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .gr-modal-footer-note { font-size: 11.5px; color: var(--ab-text-3); line-height: 1.6; }

    /* ---------- Form fields ---------- */
    .gr-form-grid { display: grid; gap: 16px; margin-top: 16px; }
    .gr-form-2col { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; }
    .gr-float { position: relative; }
    .gr-float > label {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        font-size: 13px; color: var(--ab-text-3); font-weight: 500; pointer-events: none;
        transition: all .2s cubic-bezier(.4,0,.2,1); background: transparent; z-index: 1;
    }
    .gr-float textarea ~ label { top: 18px; transform: none; }
    .gr-float input, .gr-float textarea {
        width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; padding: 22px 14px 8px; font-size: 13.5px; color: var(--ab-text);
        transition: border-color .2s, box-shadow .2s; line-height: 1.5;
    }
    .gr-float textarea { min-height: 96px; resize: vertical; }
    .gr-float input::placeholder, .gr-float textarea::placeholder { color: transparent; }
    .gr-float input:focus, .gr-float textarea:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .gr-float input:focus ~ label, .gr-float input:not(:placeholder-shown) ~ label,
    .gr-float textarea:focus ~ label, .gr-float textarea:not(:placeholder-shown) ~ label {
        top: 8px; transform: translateY(0); font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px; color: var(--ab-primary);
    }
    .gr-float.is-changed input, .gr-float.is-changed textarea {
        border-color: var(--ab-amber); background: var(--ab-amber-soft);
        box-shadow: 0 0 0 3px var(--ab-amber-soft);
    }
    .gr-field-undo {
        position: absolute; right: 10px; bottom: 10px; width: 28px; height: 28px; border-radius: 9px;
        border: none; background: var(--ab-amber); color: #fff; font-size: 11px; cursor: pointer;
        display: none; align-items: center; justify-content: center; transition: all .2s; z-index: 2;
    }
    .gr-float.is-changed .gr-field-undo { display: inline-flex; animation: grPop .25s ease; }
    @keyframes grPop { from { transform: scale(.6); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .gr-field-undo:hover { background: #b45309; }

    .gr-hintbox {
        display: flex; align-items: flex-start; gap: 12px;
        background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border);
        border-radius: 12px; padding: 11px 14px; font-size: 12px; color: var(--ab-text-2); line-height: 1.6;
    }
    .gr-hintbox i { color: var(--ab-primary); font-size: 15px; flex-shrink: 0; margin-top: 1px; }

    /* ---------- Searchable user picker ---------- */
    .gr-uspick { position: relative; }
    .gr-uspick-btn {
        width: 100%; min-height: 46px; display: flex; align-items: center; justify-content: space-between; gap: 10px;
        border: 1.5px solid var(--ab-border); background: var(--ab-card); border-radius: 12px;
        padding: 0 14px; font-size: 13px; color: var(--ab-text); cursor: pointer;
        transition: border-color .2s, box-shadow .2s;
    }
    .gr-uspick-btn:hover { border-color: var(--ab-primary-border); }
    .gr-uspick-btn.is-open { border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .gr-uspick-val { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .gr-uspick-val i { color: var(--ab-text-3); }
    .gr-uspick-val b { font-weight: 700; color: var(--ab-text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .gr-chev { color: var(--ab-text-3); font-size: 11px; transition: transform .2s; }
    .gr-uspick-btn.is-open .gr-chev { transform: rotate(180deg); }
    .gr-uspick-panel {
        display: none; position: absolute; top: calc(100% + 8px); left: 0; right: 0; z-index: 60;
        background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 16px;
        box-shadow: var(--ab-shadow-lg); padding: 10px; animation: grDrop .2s ease;
    }
    .gr-uspick-btn.is-open + .gr-uspick-panel, .gr-uspick-panel.is-open { display: block; }
    @keyframes grDrop { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    .gr-uspick-search { position: relative; margin-bottom: 8px; }
    .gr-uspick-search > i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ab-text-3); font-size: 13px; }
    .gr-uspick-search input {
        width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 11px; padding: 9px 12px 9px 34px; font-size: 12.5px; color: var(--ab-text);
        transition: border-color .2s, box-shadow .2s;
    }
    .gr-uspick-search input:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .gr-uspick-list { max-height: 240px; overflow: auto; display: grid; gap: 6px; }
    .gr-uspick-item {
        display: flex; align-items: center; gap: 11px; width: 100%; text-align: left;
        border: 1px solid transparent; border-radius: 12px; padding: 9px 10px; cursor: pointer;
        background: transparent; transition: background .18s, border-color .18s;
    }
    .gr-uspick-item:hover { background: var(--ab-bg); border-color: var(--ab-border); }
    .gr-uspick-item.is-selected { background: var(--ab-primary-soft); border-color: var(--ab-primary-border); }
    .gr-uspick-avatar {
        width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0; color: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800;
        background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 3px 8px -2px rgba(37,99,235,.35);
    }
    .gr-uspick-meta { min-width: 0; flex: 1; }
    .gr-uspick-meta b { display: block; font-size: 12.5px; font-weight: 800; color: var(--ab-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .gr-uspick-meta small { display: block; font-size: 11px; color: var(--ab-text-3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .gr-uspick-role { flex-shrink: 0; font-size: 10.5px; font-weight: 800; color: var(--ab-primary); background: var(--ab-primary-soft); border: 1px solid var(--ab-primary-border); padding: 3px 9px; border-radius: 20px; }
    .gr-uspick-empty { text-align: center; padding: 16px; font-size: 12px; color: var(--ab-text-3); }

    .gr-feedback { display: none; margin-top: 8px; font-size: 12px; font-weight: 600; color: var(--ab-red); align-items: center; gap: 6px; }
    .gr-feedback.is-on { display: flex; }
    .gr-feedback--ok { color: var(--ab-green); }
    .gr-shake { animation: grShake .4s ease; }
    @keyframes grShake { 0%,100% { transform: translateX(0); } 20% { transform: translateX(-6px); } 40% { transform: translateX(6px); } 60% { transform: translateX(-4px); } 80% { transform: translateX(4px); } }

    /* ---------- Preview card (add modal) ---------- */
    .gr-preview {
        display: none; border-radius: 16px; border: 1.5px solid var(--ab-primary-border);
        background: var(--ab-primary-soft); padding: 16px; animation: grPop .3s ease;
    }
    .gr-preview.on { display: block; }
    .gr-preview-head { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .gr-preview-name { font-size: 14px; font-weight: 800; color: var(--ab-text); line-height: 1.3; }
    .gr-preview-sub { font-size: 11px; color: var(--ab-text-3); margin-top: 2px; }
    .gr-preview-row { display: flex; justify-content: space-between; gap: 10px; padding: 8px 0; border-bottom: 1px dashed var(--ab-primary-border); font-size: 12.5px; }
    .gr-preview-row:last-child { border-bottom: none; }
    .gr-preview-row .k { color: var(--ab-text-2); font-weight: 600; }
    .gr-preview-row .v { color: var(--ab-text); font-weight: 800; text-align: right; }

    /* ---------- Readonly identity card (edit modal) ---------- */
    .gr-identity { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: 16px; border: 1.5px solid var(--ab-border); background: var(--ab-card); }
    .gr-identity-name { font-size: 14px; font-weight: 800; color: var(--ab-text); }
    .gr-identity-meta { font-size: 11.5px; color: var(--ab-text-3); margin-top: 3px; }
    .gr-identity-lock { margin-top: 10px; font-size: 11px; color: var(--ab-text-3); display: inline-flex; align-items: center; gap: 6px; }

    /* ---------- Delete confirm ---------- */
    .gr-delete-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .gr-delete-box { padding: 12px; border-radius: 14px; background: var(--ab-red-soft); border: 1px solid var(--ab-red-border); }
    .gr-delete-box .k { font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: var(--ab-red); font-weight: 700; }
    .gr-delete-box .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: var(--ab-text); word-break: break-word; }

    /* ---------- Detail modal ---------- */
    .gr-detail-grid { display: grid; gap: 10px; margin-top: 4px; }
    .gr-detail-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 12px 14px; border-radius: 12px; background: var(--ab-border-soft); }
    .gr-detail-row .k { font-size: 11px; text-transform: uppercase; letter-spacing: .3px; color: var(--ab-text-3); font-weight: 700; }
    .gr-detail-row .v { margin-top: 4px; font-size: 13.5px; font-weight: 700; color: var(--ab-text); word-break: break-word; }

    /* ---------- Loading button ---------- */
    .gr-btn-loading { pointer-events: none; opacity: .8; }
    .gr-btn-loading .gr-spin { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: grSpin .6s linear infinite; }
    .gr-btn-loading i { display: none; }

    /* ---------- Ripple ---------- */
    .gr-ripple { position: relative; overflow: hidden; }
    .gr-ripple-span { position: absolute; border-radius: 50%; background: rgba(255,255,255,.35); transform: scale(0); animation: grRipple .55s linear; pointer-events: none; }
    @keyframes grRipple { to { transform: scale(4); opacity: 0; } }

    /* ---------- FAB (mobile) ---------- */
    .gr-fab {
        display: none; position: fixed; right: 18px; bottom: 20px; z-index: 960;
        width: 56px; height: 56px; border-radius: 18px; border: none; cursor: pointer;
        background: var(--ab-grad); color: #fff; font-size: 22px;
        box-shadow: 0 14px 30px -8px rgba(37,99,235,.55);
        align-items: center; justify-content: center; transition: transform .22s cubic-bezier(.4,0,.2,1);
    }
    .gr-fab:hover { transform: translateY(-3px) scale(1.04); }
    .gr-fab:active { transform: scale(.94); }

    /* ---------- Result count chip ---------- */
    .gr-result-count { font-size: 12.5px; color: var(--ab-text-2); font-weight: 600; }
    .gr-result-count b { color: var(--ab-primary); font-variant-numeric: tabular-nums; }

    /* ---------- Responsive ---------- */
    @media (max-width: 1299.98px) {
        .gr-kpi-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 1199.98px) {
        .gr-toolbar { top: 70px; grid-template-columns: minmax(0, 1fr) 1fr 1fr; }
    }
    @media (max-width: 991.98px) {
        .gr-kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .gr-form-2col { grid-template-columns: 1fr; }
    }
    @media (max-width: 767.98px) {
        .gr-toolbar { grid-template-columns: 1fr; top: 64px; }
        .gr-toolbar .gr-field { order: 0; }
        .gr-toolbar .gr-tool-btn, .gr-toolbar .abm-btn { width: 100%; }
        .gr-table-scroll, .gr-skeleton { display: none !important; }
        .gr-mobile-grid { display: grid; }
        .abm-hero { padding: 20px; }
        .abm-hero-row { flex-direction: column; align-items: stretch; }
        .abm-hero-actions { justify-content: flex-start; }
        .abm-hero-actions .abm-btn { flex: 1; justify-content: center; }
        .gr-form-2col { grid-template-columns: 1fr; }
        .gr-modal-meta, .gr-delete-grid { grid-template-columns: 1fr; }
        .gr-fab { display: inline-flex; }
        .gr-mobile-grid-inner { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 575.98px) {
        .gr-kpi-grid { grid-template-columns: 1fr; }
        .gr-mobile-grid-inner { grid-template-columns: 1fr; }
        .gr-mobile-actions { flex-direction: column; }
        .gr-mobile-action { width: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .gr-mod *, .gr-mod *::before, .gr-mod *::after { animation: none !important; transition: none !important; }
        .gr-mod .abm-kpi:hover, .gr-mod .gr-icon-btn:hover, .gr-mod .abm-btn:hover { transform: none !important; }
    }
</style>

<div class="abs-mod gr-mod master-siswa-page">
    {{-- ===== HERO ===== --}}
    <div class="abm-hero">
        <div class="abm-hero-grid"></div>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <div class="abm-chip abm-chip--blue mb-2"><i class="fas fa-address-book"></i> Guru Management Center</div>
                        <h3>Master Guru</h3>
                        <p class="abm-hero-sub">Pusat pengelolaan seluruh data guru — akun, NIP, nomor HP, dan kontak — dalam satu workspace yang cepat, rapi, dan mudah dipakai sehari-hari.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-calendar-day"></i> {{ $todayLabel }}</span>
                    <span class="abm-hero-badge"><i class="fas fa-users"></i> {{ $totalGuru }} guru terdaftar</span>
                    @if($unlinked > 0)
                        <span class="abm-hero-badge"><i class="fas fa-user-plus"></i> {{ $unlinked }} user guru belum terhubung</span>
                    @else
                        <span class="abm-hero-badge"><i class="fas fa-check-circle"></i> Semua user guru sudah terhubung</span>
                    @endif
                    <span class="abm-hero-badge"><i class="fas fa-clock"></i> Update terakhir {{ $lastUpLabel }}</span>
                </div>
            </div>
            <div class="abm-hero-right">
                <div class="abm-hero-clock">
                    <i class="fas fa-clock"></i>
                    <div>
                        <div class="abm-clock-time" id="grLiveClock">--:--:--</div>
                        <div class="abm-clock-date" id="grLiveClockDate">{{ $todayLabel }}</div>
                    </div>
                </div>
                <div class="abm-hero-actions">
                    <button type="button" class="abm-btn abm-btn--light gr-ripple" data-bs-toggle="modal" data-bs-target="#grModalTambah"><i class="fas fa-user-plus"></i> Tambah Guru</button>
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

    {{-- ===== KPI ===== --}}
    <div class="abm-kpi-grid gr-kpi-grid">
        <div class="abm-kpi gr-kpi total" title="Total seluruh data guru yang terdaftar di madrasah">
            <i class="fas fa-users abm-kpi-watermark"></i>
            <div class="abm-kpi-icon blue"><i class="fas fa-users"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $totalGuru }}">0</div>
                <div class="abm-kpi-label">Total Guru</div>
                <div class="abm-progress mt-2"><span style="width:100%"></span></div>
            </div>
            <div class="gr-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>100%</span></div>
        </div>
        <div class="abm-kpi gr-kpi nip" title="Guru yang sudah mengisi NIP di profilnya">
            <i class="fas fa-id-card abm-kpi-watermark"></i>
            <div class="abm-kpi-icon green"><i class="fas fa-id-card"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $withNip }}">0</div>
                <div class="abm-kpi-label">Guru Memiliki NIP</div>
                <div class="abm-progress abm-progress--green mt-2"><span data-progress="{{ $nipPct }}"></span></div>
            </div>
            <div class="gr-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $nipPct }}%</span></div>
        </div>
        <div class="abm-kpi gr-kpi nipp" title="Guru yang belum mengisi NIP — ditandai untuk dilengkapi">
            <i class="fas fa-id-card-slash abm-kpi-watermark"></i>
            <div class="abm-kpi-icon amber"><i class="fas fa-exclamation-circle"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $withoutNip }}">0</div>
                <div class="abm-kpi-label">Guru Belum Memiliki NIP</div>
                <div class="abm-progress mt-2"><span data-progress="{{ 100 - $nipPct }}"></span></div>
            </div>
            <div class="gr-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ 100 - $nipPct }}%</span></div>
        </div>
        <div class="abm-kpi gr-kpi hp" title="Guru yang sudah mengisi nomor HP aktif">
            <i class="fas fa-phone abm-kpi-watermark"></i>
            <div class="abm-kpi-icon sky"><i class="fas fa-phone-alt"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $withHp }}">0</div>
                <div class="abm-kpi-label">Guru Memiliki Nomor HP</div>
                <div class="abm-progress mt-2"><span data-progress="{{ $hpPct }}"></span></div>
            </div>
            <div class="gr-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ $hpPct }}%</span></div>
        </div>
        <div class="abm-kpi gr-kpi hpp" title="Guru yang belum mengisi nomor HP — kontak belum lengkap">
            <i class="fas fa-phone-slash abm-kpi-watermark"></i>
            <div class="abm-kpi-icon rose"><i class="fas fa-phone-slash"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $withoutHp }}">0</div>
                <div class="abm-kpi-label">Guru Tanpa Nomor HP</div>
                <div class="abm-progress mt-2"><span data-progress="{{ 100 - $hpPct }}"></span></div>
            </div>
            <div class="gr-kpi-foot"><span><i class="fas fa-clock"></i> {{ $lastUpLabel }}</span><span>{{ 100 - $hpPct }}%</span></div>
        </div>
    </div>

    {{-- ===== STICKY TOOLBAR ===== --}}
    <div class="gr-toolbar" id="grToolbar">
        <div class="gr-field">
            <label for="grSearch">Search</label>
            <div class="abm-search">
                <i class="fas fa-search"></i>
                <input type="search" id="grSearch" placeholder="Cari nama, kode guru, NIP, atau nomor HP..." aria-label="Cari guru">
            </div>
        </div>
        <div class="gr-field">
            <label for="grFilterNip">Filter NIP</label>
            <div class="gr-select-wrap"><i class="fas fa-id-card"></i>
                <select id="grFilterNip" class="gr-select" aria-label="Filter status NIP">
                    <option value="">Semua NIP</option>
                    <option value="yes">Sudah Diisi</option>
                    <option value="no">Belum Diisi</option>
                </select>
            </div>
        </div>
        <div class="gr-field">
            <label for="grFilterHp">Filter Nomor HP</label>
            <div class="gr-select-wrap"><i class="fas fa-phone"></i>
                <select id="grFilterHp" class="gr-select" aria-label="Filter status nomor HP">
                    <option value="">Semua No. HP</option>
                    <option value="yes">Lengkap</option>
                    <option value="no">Belum Ada</option>
                </select>
            </div>
        </div>
        <div class="gr-field">
            <label for="grPerPage">Jumlah Data</label>
            <div class="gr-select-wrap"><i class="fas fa-list-ol"></i>
                <select id="grPerPage" class="gr-select" aria-label="Jumlah data per halaman">
                    <option value="10">10 data</option>
                    <option value="15">15 data</option>
                    <option value="25">25 data</option>
                    <option value="50">50 data</option>
                    <option value="100">100 data</option>
                </select>
            </div>
        </div>
        <div class="gr-field">
            <label for="grDensity">&nbsp;</label>
            <button type="button" id="grDensity" class="gr-tool-btn" title="Ubah kepadatan tampilan tabel"><i class="fas fa-compress"></i> Density</button>
        </div>
        <div class="gr-field">
            <label>&nbsp;</label>
            <button type="button" id="grReset" class="gr-tool-btn" title="Reset semua filter"><i class="fas fa-arrow-rotate-left"></i> Reset</button>
        </div>
        <div class="gr-field">
            <label>&nbsp;</label>
            <button type="button" class="abm-btn abm-btn--solid gr-ripple" data-bs-toggle="modal" data-bs-target="#grModalTambah"><i class="fas fa-user-plus"></i> Tambah Guru</button>
        </div>
    </div>

    {{-- ===== DATA GRID ===== --}}
    <div class="gr-card">
        <div class="gr-card-head">
            <div>
                <div class="gr-card-title"><i class="fas fa-user-tie"></i> Daftar Guru</div>
                <div class="gr-card-sub">Semua guru ditampilkan dalam data grid modern agar cepat dipindai.</div>
            </div>
            <span class="abm-chip abm-chip--blue"><i class="fas fa-users"></i> <span class="gr-result-count" id="grResultCount">0</span></span>
        </div>

        <div class="gr-skeleton" id="grSkeleton" aria-hidden="true">
            <div class="gr-skeleton-card gr-shimmer"></div>
            <div class="gr-skeleton-card gr-shimmer"></div>
            <div class="gr-skeleton-card gr-shimmer"></div>
        </div>

        <div class="gr-table-scroll" id="grTableScroll">
            <div class="gr-table-wrap">
                <table class="gr-table" id="grTable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>NIP</th>
                            <th>Nomor HP</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="grTbody"></tbody>
                </table>
            </div>
        </div>

        <div class="gr-mobile-grid" id="grMobileGrid"></div>

        <div class="gr-empty-result" id="grNoResult" style="display:none;">
            <i class="fas fa-filter-circle-xmark"></i>
            <div class="gr-empty-title" style="font-size:15px;">Tidak ada guru yang cocok</div>
            <div class="gr-empty-sub">Coba ubah kata kunci pencarian atau hapus filter.</div>
        </div>

        <div class="gr-pagination" id="grPagination">
            <div class="gr-pager" id="grPager"></div>
            <span class="gr-result-count" id="grPageInfo"></span>
        </div>
    </div>

    {{-- ===== EMPTY STATE (belum ada guru sama sekali) ===== --}}
    <div class="gr-card mt-3" id="grGlobalEmpty" style="{{ $totalGuru > 0 ? 'display:none;' : '' }}">
        <div class="gr-empty">
            <div class="gr-empty-illu"><i class="fas fa-user-tie"></i></div>
            <div class="gr-empty-title">Belum ada data Guru.</div>
            <div class="gr-empty-sub">Tambahkan guru pertama untuk mulai mengelola data pengajar madrasah.</div>
            <button type="button" class="abm-btn abm-btn--solid gr-ripple" data-bs-toggle="modal" data-bs-target="#grModalTambah"><i class="fas fa-user-plus"></i> Tambah Guru</button>
        </div>
    </div>

    {{-- ===== MOBILE FAB ===== --}}
    <button type="button" class="gr-fab" data-bs-toggle="modal" data-bs-target="#grModalTambah" title="Tambah Guru"><i class="fas fa-user-plus"></i></button>

    {{-- ===== TOAST STACK ===== --}}
    <div class="gr-toast-wrap" id="grToastStack"></div>
</div>

{{-- ===== MODAL TAMBAH GURU ===== --}}
<div class="modal fade gr-modal" id="grModalTambah" tabindex="-1" aria-labelledby="grModalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="gr-modal-hero">
                <div class="gr-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="gr-modal-badge"><i class="fas fa-user-plus"></i></span>
                        <div>
                            <h4 class="gr-modal-title" id="grModalTambahLabel">Tambah Guru</h4>
                            <p class="gr-modal-subtitle">Hubungkan akun user ke data guru. Nama dan kode dibuat otomatis.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="gr-modal-meta">
                    <div class="gr-modal-meta-item"><div class="k">Kode Guru</div><div class="v" id="grNextKode">GR001</div></div>
                    <div class="gr-modal-meta-item"><div class="k">Sumber Nama</div><div class="v">User</div></div>
                    <div class="gr-modal-meta-item"><div class="k">Status Relasi</div><div class="v">Baru</div></div>
                </div>
            </div>
            <form id="grFormTambah" action="{{ route('master-guru.store') }}" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="gr-form-2col">
                        <div>
                            <div class="gr-form-grid" style="margin-top:0;">
                                <div>
                                    <label class="abm-field-label"><i class="fas fa-user-tie"></i>User Guru <span class="text-danger">*</span></label>
                                    <div class="gr-uspick" id="grUserPick">
                                        <button type="button" class="gr-uspick-btn" id="grUserPickBtn">
                                            <span class="gr-uspick-val" id="grUserPickVal"><i class="fas fa-user-tie"></i> <b>Pilih User Guru</b></span>
                                            <i class="fas fa-chevron-down gr-chev"></i>
                                        </button>
                                        <input type="hidden" name="user_id" id="grUserId" value="{{ old('user_id') }}">
                                        <div class="gr-uspick-panel" id="grUserPickPanel">
                                            <div class="gr-uspick-search"><i class="fas fa-search"></i><input type="text" id="grUserSearch" placeholder="Cari nama atau email..."></div>
                                            <div class="gr-uspick-list" id="grUserList">
                                                @forelse($users as $u)
                                                    <button type="button" class="gr-uspick-item" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}">
                                                        <span class="gr-uspick-avatar">{{ strtoupper(substr(preg_replace('/\s+/', '', trim($u->name)), 0, 2)) ?: 'G' }}</span>
                                                        <span class="gr-uspick-meta"><b>{{ $u->name }}</b><small>{{ $u->email }}</small></span>
                                                        <span class="gr-uspick-role"><i class="fas fa-user-graduate"></i> Guru</span>
                                                    </button>
                                                @empty
                                                    <div class="gr-uspick-empty">Tidak ada User Guru yang tersedia untuk dihubungkan.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gr-feedback" id="grUserFeedback"><i class="fas fa-exclamation-circle"></i><span>Pilih User Guru terlebih dahulu.</span></div>
                                </div>
                                <div class="gr-float">
                                    <input type="text" id="grTambahNip" name="nip" value="{{ old('nip') }}" placeholder=" " autocomplete="off">
                                    <label for="grTambahNip">NIP</label>
                                </div>
                                <div class="gr-float">
                                    <input type="text" id="grTambahHp" name="no_hp" value="{{ old('no_hp') }}" placeholder=" " autocomplete="off">
                                    <label for="grTambahHp">Nomor HP</label>
                                </div>
                                <div class="gr-float">
                                    <textarea id="grTambahAlamat" name="alamat" placeholder=" " rows="3">{{ old('alamat') }}</textarea>
                                    <label for="grTambahAlamat">Alamat</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="gr-preview" id="grPreview">
                                <div class="gr-preview-head">
                                    <span class="gr-guru-avatar c0" id="grPreviewAvatar">G</span>
                                    <div>
                                        <div class="gr-preview-name" id="grPreviewNama">-</div>
                                        <div class="gr-preview-sub" id="grPreviewEmail">-</div>
                                    </div>
                                </div>
                                <div class="gr-preview-row"><span class="k">Role</span><span class="v"><i class="fas fa-user-graduate"></i> Guru</span></div>
                                <div class="gr-preview-row"><span class="k">Kode Guru</span><span class="v" id="grPreviewKode">-</span></div>
                                <div class="gr-preview-row"><span class="k">Status Relasi</span><span class="v abm-chip abm-chip--ok" style="padding:2px 10px;"><i class="fas fa-link"></i> Siap dihubungkan</span></div>
                            </div>
                            <div class="gr-hintbox mt-3">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    Nama Guru diambil otomatis dari User. Kode guru <b id="grPreviewKodeInline">GR001</b> dibuat otomatis. Hanya User ber-role <b>Guru</b> yang belum memiliki data guru yang dapat dipilih.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="gr-modal-footer-note"><i class="fas fa-info-circle me-1"></i>Kode guru dibuat otomatis sesuai urutan.</div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--solid gr-ripple" id="grBtnTambah"><i class="fas fa-user-plus"></i> Simpan Guru</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL EDIT GURU ===== --}}
<div class="modal fade gr-modal" id="grModalEdit" tabindex="-1" aria-labelledby="grModalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="gr-modal-hero" style="background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 18px 40px -12px rgba(217,119,6,.4);">
                <div class="gr-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="gr-modal-badge"><i class="fas fa-edit"></i></span>
                        <div>
                            <h4 class="gr-modal-title" id="grModalEditLabel">Edit Guru</h4>
                            <p class="gr-modal-subtitle">Perbarui data kontak guru. Nama dan kode tidak dapat diubah di sini.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
            </div>
            <form id="grFormEdit" action="" method="POST" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="gr-form-2col">
                        <div>
                            <div class="gr-identity">
                                <span class="gr-guru-avatar c0" id="grEditAvatar">G</span>
                                <div>
                                    <div class="gr-identity-name" id="grEditNama">-</div>
                                    <div class="gr-identity-meta" id="grEditKode">-</div>
                                    <div class="gr-identity-lock"><i class="fas fa-lock"></i> Nama diambil dari User dan tidak dapat diubah.</div>
                                </div>
                            </div>
                        </div>
                        <div class="gr-form-grid" style="margin-top:0;">
                            <div class="gr-float" id="grEditNipWrap">
                                <input type="text" id="grEditNip" name="nip" value="" placeholder=" " autocomplete="off">
                                <label for="grEditNip">NIP</label>
                                <button type="button" class="gr-field-undo" data-for="nip" title="Urungkan perubahan"><i class="fas fa-rotate-left"></i></button>
                            </div>
                            <div class="gr-float" id="grEditHpWrap">
                                <input type="text" id="grEditHp" name="no_hp" value="" placeholder=" " autocomplete="off">
                                <label for="grEditHp">Nomor HP</label>
                                <button type="button" class="gr-field-undo" data-for="no_hp" title="Urungkan perubahan"><i class="fas fa-rotate-left"></i></button>
                            </div>
                            <div class="gr-float" id="grEditAlamatWrap">
                                <textarea id="grEditAlamat" name="alamat" placeholder=" " rows="3"></textarea>
                                <label for="grEditAlamat">Alamat</label>
                                <button type="button" class="gr-field-undo" data-for="alamat" title="Urungkan perubahan"><i class="fas fa-rotate-left"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="gr-modal-footer-note"><i class="fas fa-sync-alt me-1"></i>Perubahan ditandai otomatis. <button type="button" id="grEditReset" class="gr-tool-btn" style="min-height:30px;padding:0 10px;font-size:11.5px;"><i class="fas fa-undo"></i> Reset</button></div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--solid gr-ripple" id="grBtnEdit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL DETAIL GURU ===== --}}
<div class="modal fade gr-modal" id="grModalDetail" tabindex="-1" aria-labelledby="grModalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="gr-modal-hero" style="background:linear-gradient(135deg,#0284c7,#0ea5e9);box-shadow:0 18px 40px -12px rgba(2,132,199,.4);">
                <div class="gr-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="gr-modal-badge"><i class="fas fa-id-badge"></i></span>
                        <div>
                            <h4 class="gr-modal-title" id="grModalDetailLabel">Detail Guru</h4>
                            <p class="gr-modal-subtitle">Ringkasan lengkap data guru.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="gr-modal-meta">
                    <div class="gr-modal-meta-item"><div class="k">Kode Guru</div><div class="v" id="grDetailKode">-</div></div>
                    <div class="gr-modal-meta-item"><div class="k">ID</div><div class="v" id="grDetailId">-</div></div>
                    <div class="gr-modal-meta-item"><div class="k">Status</div><div class="v"><i class="fas fa-check-circle"></i> Terdaftar</div></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="gr-identity mb-3">
                    <span class="gr-guru-avatar c0" id="grDetailAvatar">G</span>
                    <div>
                        <div class="gr-identity-name" id="grDetailNama">-</div>
                        <div class="gr-identity-meta" id="grDetailKodeSub">-</div>
                    </div>
                </div>
                <div class="gr-detail-grid">
                    <div class="gr-detail-row">
                        <div><div class="k"><i class="fas fa-id-card me-1"></i>NIP</div><div class="v" id="grDetailNip">-</div></div>
                        <span id="grDetailNipBadge"></span>
                    </div>
                    <div class="gr-detail-row">
                        <div><div class="k"><i class="fas fa-phone me-1"></i>Nomor HP</div><div class="v" id="grDetailHp">-</div></div>
                        <span id="grDetailHpBadge"></span>
                    </div>
                    <div class="gr-detail-row">
                        <div><div class="k"><i class="fas fa-map-marker-alt me-1"></i>Alamat</div><div class="v" id="grDetailAlamat">-</div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="gr-modal-footer-note"><i class="fas fa-clock me-1"></i>Update terakhir <span id="grDetailUpd">-</span></div>
                <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL HAPUS GURU ===== --}}
<div class="modal fade gr-modal" id="grModalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="gr-modal-hero gr-modal-hero--danger">
                <div class="gr-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="gr-modal-badge"><i class="fas fa-trash"></i></span>
                        <div>
                            <h4 class="gr-modal-title">Hapus Guru</h4>
                            <p class="gr-modal-subtitle">Tindakan ini permanen dan tidak dapat dibatalkan. Periksa kembali data sebelum menghapus.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
            </div>
            <form id="grFormHapus" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="gr-identity">
                        <span class="gr-guru-avatar c0" id="grHapusAvatar">G</span>
                        <div>
                            <div class="gr-identity-name" id="grHapusNama">-</div>
                            <div class="gr-identity-meta" id="grHapusKode">-</div>
                        </div>
                    </div>
                    <div class="gr-delete-grid">
                        <div class="gr-delete-box"><div class="k">Kode Guru</div><div class="v" id="grHapusKodeBox">-</div></div>
                        <div class="gr-delete-box"><div class="k">Nama</div><div class="v" id="grHapusNamaBox">-</div></div>
                        <div class="gr-delete-box"><div class="k">NIP</div><div class="v" id="grHapusNipBox">-</div></div>
                    </div>
                    <div class="abm-alert abm-alert--danger mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>Data guru yang dihapus tidak dapat dikembalikan. Relasi ke akun User tetap ada dan dapat dihubungkan kembali.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="gr-modal-footer-note"><i class="fas fa-exclamation-triangle me-1"></i>Hapus permanen.</div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--danger gr-ripple"><i class="fas fa-trash"></i> Hapus Permanen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const GURUS = {!! $gurusJson !!};
    const NEXT_KODE = '{{ $nextKode }}';
    const EDIT_URL = '{{ route('master-guru.update', ['master_guru' => '__ID__']) }}';
    const DELETE_URL = '{{ route('master-guru.destroy', ['master_guru' => '__ID__']) }}';

    const state = { q: '', nip: '', hp: '', perPage: 10, page: 1, compact: false };
    let editOriginal = { nip: '', no_hp: '', alamat: '' };

    /* ================= helpers ================= */
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const initials = n => {
        const parts = (n || 'G').trim().split(/\s+/).filter(Boolean);
        return (parts[0]?.[0] || 'G') + (parts[1]?.[0] || '');
    };
    const avatarClass = id => 'c' + (Number(id) % 6);
    const fmtPhone = p => { const d = String(p || '').replace(/\D/g, ''); return d ? d.replace(/(\d{4})(?=\d)/g, '$1 ') : '-'; };

    /* ================= toast ================= */
    function showToast(title, text, type) {
        const stack = document.getElementById('grToastStack');
        if (!stack) return;
        const toast = document.createElement('div');
        toast.className = 'gr-toast ' + type;
        toast.setAttribute('role', 'status');
        toast.innerHTML = '<span class="gr-toast-icon"><i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle') + '"></i></span><div><div class="gr-toast-title">' + esc(title) + '</div><div class="gr-toast-text">' + esc(text) + '</div></div>';
        stack.appendChild(toast);
        requestAnimationFrame(function() { toast.classList.add('is-show'); });
        setTimeout(function() {
            toast.classList.remove('is-show');
            setTimeout(function() { toast.remove(); }, 260);
        }, 3400);
    }
    @if(session('success'))
        showToast('Berhasil', '{{ session('success') }}', 'success');
    @endif

    /* ================= live clock ================= */
    (function startClock() {
        const el = document.getElementById('grLiveClock');
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
        span.className = 'gr-ripple-span';
        span.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (e.clientX - rect.left - size / 2) + 'px;top:' + (e.clientY - rect.top - size / 2) + 'px;';
        btn.appendChild(span);
        setTimeout(function() { span.remove(); }, 600);
    }
    document.querySelectorAll('.gr-ripple').forEach(function(b) { b.addEventListener('click', ripple); });

    /* ================= filtering + rendering ================= */
    const tbody = document.getElementById('grTbody');
    const mobileGrid = document.getElementById('grMobileGrid');
    const noResult = document.getElementById('grNoResult');
    const pager = document.getElementById('grPager');
    const pageInfo = document.getElementById('grPageInfo');
    const resultCount = document.getElementById('grResultCount');

    function filtered() {
        return GURUS.filter(function(g) {
            const q = state.q.trim().toLowerCase();
            const hitQ = !q ||
                g.nama.toLowerCase().includes(q) ||
                g.kode.toLowerCase().includes(q) ||
                g.nip.toLowerCase().includes(q) ||
                g.no_hp.replace(/\D/g, '').includes(q.replace(/\D/g, '')) ||
                g.no_hp.toLowerCase().includes(q);
            const hitNip = state.nip === '' || (state.nip === 'yes' ? !!g.nip : !g.nip);
            const hitHp = state.hp === '' || (state.hp === 'yes' ? !!g.no_hp : !g.no_hp);
            return hitQ && hitNip && hitHp;
        });
    }

    function badgeHtml(g) {
        const nipOk = !!g.nip;
        const hpOk = !!g.no_hp;
        return '<span class="abm-chip ' + (nipOk ? 'abm-chip--ok' : 'abm-chip--warn') + '"><i class="fas ' + (nipOk ? 'fa-check-circle' : 'fa-id-card-slash') + '"></i> ' + (nipOk ? 'Sudah Diisi' : 'Belum Diisi') + '</span>'
             + '<span class="abm-chip ' + (hpOk ? 'abm-chip--info' : 'abm-chip--muted') + '"><i class="fas ' + (hpOk ? 'fa-phone-alt' : 'fa-phone-slash') + '"></i> ' + (hpOk ? 'Lengkap' : 'Belum Ada') + '</span>';
    }

    function render() {
        const list = filtered();
        const total = list.length;
        resultCount.textContent = total + ' guru';
        const pages = Math.max(1, Math.ceil(total / state.perPage));
        if (state.page > pages) state.page = pages;
        const start = (state.page - 1) * state.perPage;
        const pageItems = list.slice(start, start + state.perPage);

        /* desktop rows */
        if (total === 0) {
            tbody.innerHTML = '';
        } else {
            tbody.innerHTML = pageItems.map(function(g, i) {
                return '<tr data-id="' + g.id + '">'
                    + '<td>' + (start + i + 1) + '</td>'
                    + '<td><div class="gr-guru"><span class="gr-guru-avatar ' + avatarClass(g.id) + '">' + esc(initials(g.nama)) + '</span><div class="gr-guru-main"><div class="gr-guru-name">' + esc(g.nama) + '</div><div class="gr-guru-code"><i class="fas fa-hashtag"></i> ' + esc(g.kode) + '</div></div></div></td>'
                    + '<td>' + (g.nip ? '<span class="abm-chip abm-chip--muted"><i class="fas fa-id-card"></i> ' + esc(g.nip) + '</span>' : '<span style="color:var(--ab-text-3);font-size:12px;">—</span>') + '</td>'
                    + '<td>' + (g.no_hp ? '<span class="abm-chip abm-chip--muted"><i class="fas fa-phone"></i> ' + esc(fmtPhone(g.no_hp)) + '</span>' : '<span style="color:var(--ab-text-3);font-size:12px;">—</span>') + '</td>'
                    + '<td><div class="gr-stack">' + badgeHtml(g) + '</div></td>'
                    + '<td><div class="gr-actions">'
                    + '<button type="button" class="gr-icon-btn gr-icon-btn--view gr-act-view" title="Detail" aria-label="Detail"><i class="fas fa-eye"></i></button>'
                    + '<button type="button" class="gr-icon-btn gr-icon-btn--edit gr-act-edit" title="Edit" aria-label="Edit"><i class="fas fa-edit"></i></button>'
                    + '<button type="button" class="gr-icon-btn gr-icon-btn--delete gr-act-delete" title="Hapus" aria-label="Hapus"><i class="fas fa-trash"></i></button>'
                    + '</div></td></tr>';
            }).join('');
        }

        /* mobile cards */
        if (total === 0) {
            mobileGrid.innerHTML = '';
        } else {
            mobileGrid.innerHTML = pageItems.map(function(g, i) {
                return '<article class="gr-mobile-card">'
                    + '<div class="gr-mobile-head"><span class="gr-guru-avatar ' + avatarClass(g.id) + '">' + esc(initials(g.nama)) + '</span>'
                    + '<div style="min-width:0;"><div class="gr-guru-name">' + esc(g.nama) + '</div><div class="gr-guru-code"><i class="fas fa-hashtag"></i> ' + esc(g.kode) + '</div></div></div>'
                    + '<div class="gr-stack">' + badgeHtml(g) + '</div>'
                    + '<div class="gr-mobile-grid-inner">'
                    + '<div class="gr-mobile-stat"><div class="k">NIP</div><div class="v">' + (g.nip ? esc(g.nip) : 'Belum diisi') + '</div></div>'
                    + '<div class="gr-mobile-stat"><div class="k">No. HP</div><div class="v">' + (g.no_hp ? esc(fmtPhone(g.no_hp)) : 'Belum ada') + '</div></div>'
                    + '</div>'
                    + '<div class="gr-mobile-actions">'
                    + '<button type="button" class="gr-mobile-action gr-mobile-action--view gr-act-view" data-id="' + g.id + '"><i class="fas fa-eye"></i> Detail</button>'
                    + '<button type="button" class="gr-mobile-action gr-mobile-action--edit gr-act-edit" data-id="' + g.id + '"><i class="fas fa-edit"></i> Edit</button>'
                    + '<button type="button" class="gr-mobile-action gr-mobile-action--delete gr-act-delete" data-id="' + g.id + '"><i class="fas fa-trash"></i> Hapus</button>'
                    + '</div></article>';
            }).join('');
        }

        const showGlobalEmpty = total === 0;
        const showNoResult = total === 0 && GURUS.length > 0;
        document.getElementById('grGlobalEmpty').style.display = (GURUS.length === 0) ? '' : 'none';
        noResult.style.display = showNoResult ? '' : 'none';
        document.querySelectorAll('.gr-table-scroll').forEach(function(el) { el.style.display = (GURUS.length === 0) ? 'none' : ''; });
        document.getElementById('grPagination').style.display = (GURUS.length === 0) ? 'none' : '';

        renderPager(pages, total, start);
    }

    function renderPager(pages, total, start) {
        pageInfo.textContent = total === 0
            ? 'Menampilkan 0 data'
            : 'Menampilkan ' + (start + 1) + '–' + Math.min(start + state.perPage, total) + ' dari ' + total + ' data';

        let btns = '';
        btns += '<button type="button" class="gr-page-btn" data-page="' + (state.page - 1) + '" ' + (state.page <= 1 ? 'disabled' : '') + ' aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>';
        const win = [];
        const from = Math.max(1, state.page - 2);
        const to = Math.min(pages, state.page + 2);
        for (let p = from; p <= to; p++) win.push(p);
        if (from > 1) { btns += '<button type="button" class="gr-page-btn" data-page="1">1</button>'; if (from > 2) btns += '<span class="gr-page-btn" style="background:none;border:none;">…</span>'; }
        win.forEach(function(p) {
            btns += '<button type="button" class="gr-page-btn' + (p === state.page ? ' is-active' : '') + '" data-page="' + p + '">' + p + '</button>';
        });
        if (to < pages) { if (to < pages - 1) btns += '<span class="gr-page-btn" style="background:none;border:none;">…</span>'; btns += '<button type="button" class="gr-page-btn" data-page="' + pages + '">' + pages + '</button>'; }
        btns += '<button type="button" class="gr-page-btn" data-page="' + (state.page + 1) + '" ' + (state.page >= pages ? 'disabled' : '') + ' aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>';
        pager.innerHTML = btns;
    }

    /* pager click (delegated) */
    pager.addEventListener('click', function(e) {
        const btn = e.target.closest('.gr-page-btn');
        if (!btn || btn.disabled) return;
        const p = parseInt(btn.getAttribute('data-page'), 10);
        if (p && p >= 1) { state.page = p; render(); }
    });

    /* toolbar events */
    const searchEl = document.getElementById('grSearch');
    let debounce;
    searchEl.addEventListener('input', function() {
        clearTimeout(debounce);
        debounce = setTimeout(function() { state.q = searchEl.value; state.page = 1; render(); }, 300);
    });
    document.getElementById('grFilterNip').addEventListener('change', function(e) { state.nip = e.target.value; state.page = 1; render(); });
    document.getElementById('grFilterHp').addEventListener('change', function(e) { state.hp = e.target.value; state.page = 1; render(); });
    document.getElementById('grPerPage').addEventListener('change', function(e) { state.perPage = parseInt(e.target.value, 10) || 10; state.page = 1; render(); });
    document.getElementById('grReset').addEventListener('click', function() {
        state.q = ''; state.nip = ''; state.hp = ''; state.page = 1;
        searchEl.value = '';
        document.getElementById('grFilterNip').value = '';
        document.getElementById('grFilterHp').value = '';
        render();
        showToast('Filter direset', 'Semua filter dikembalikan ke kondisi awal.', 'success');
    });
    document.getElementById('grDensity').addEventListener('click', function() {
        state.compact = !state.compact;
        this.classList.toggle('is-on', state.compact);
        document.querySelectorAll('#grTable').forEach(function(t) { t.classList.toggle('gr-compact', state.compact); });
    });

    /* ================= row actions (delegated) ================= */
    function findGuru(id) { return GURUS.find(function(g) { return String(g.id) === String(id); }); }

    tbody.addEventListener('click', function(e) {
        const btn = e.target.closest('.gr-icon-btn');
        if (!btn) return;
        const row = btn.closest('tr');
        const g = findGuru(row.getAttribute('data-id'));
        if (!g) return;
        if (btn.classList.contains('gr-act-view')) openDetail(g);
        else if (btn.classList.contains('gr-act-edit')) openEdit(g);
        else if (btn.classList.contains('gr-act-delete')) openDelete(g);
    });
    mobileGrid.addEventListener('click', function(e) {
        const btn = e.target.closest('.gr-mobile-action');
        if (!btn) return;
        const g = findGuru(btn.getAttribute('data-id'));
        if (!g) return;
        if (btn.classList.contains('gr-act-view')) openDetail(g);
        else if (btn.classList.contains('gr-act-edit')) openEdit(g);
        else if (btn.classList.contains('gr-act-delete')) openDelete(g);
    });

    /* ================= detail modal ================= */
    function openDetail(g) {
        document.getElementById('grDetailKode').textContent = g.kode;
        document.getElementById('grDetailId').textContent = '#' + g.id;
        document.getElementById('grDetailNama').textContent = g.nama;
        document.getElementById('grDetailKodeSub').textContent = g.kode;
        document.getElementById('grDetailAvatar').textContent = initials(g.nama);
        document.getElementById('grDetailAvatar').className = 'gr-guru-avatar ' + avatarClass(g.id);
        document.getElementById('grDetailNip').textContent = g.nip || 'Belum diisi';
        document.getElementById('grDetailHp').textContent = g.no_hp ? fmtPhone(g.no_hp) : 'Belum ada';
        document.getElementById('grDetailAlamat').textContent = g.alamat || '—';
        document.getElementById('grDetailUpd').textContent = g.updated;
        document.getElementById('grDetailNipBadge').innerHTML = g.nip
            ? '<span class="abm-chip abm-chip--ok"><i class="fas fa-check-circle"></i> Sudah Diisi</span>'
            : '<span class="abm-chip abm-chip--warn"><i class="fas fa-id-card-slash"></i> Belum Diisi</span>';
        document.getElementById('grDetailHpBadge').innerHTML = g.no_hp
            ? '<span class="abm-chip abm-chip--info"><i class="fas fa-phone-alt"></i> Lengkap</span>'
            : '<span class="abm-chip abm-chip--muted"><i class="fas fa-phone-slash"></i> Belum Ada</span>';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('grModalDetail')).show();
    }

    /* ================= edit modal ================= */
    function openEdit(g) {
        editOriginal = { nip: g.nip, no_hp: g.no_hp, alamat: g.alamat };
        document.getElementById('grEditAvatar').textContent = initials(g.nama);
        document.getElementById('grEditAvatar').className = 'gr-guru-avatar ' + avatarClass(g.id);
        document.getElementById('grEditNama').textContent = g.nama;
        document.getElementById('grEditKode').textContent = g.kode;
        document.getElementById('grFormEdit').action = EDIT_URL.replace('__ID__', g.id);
        const set = (id, v) => { const el = document.getElementById(id); el.value = v || ''; el.closest('.gr-float').classList.remove('is-changed'); };
        set('grEditNip', g.nip);
        set('grEditHp', g.no_hp);
        set('grEditAlamat', g.alamat);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('grModalEdit')).show();
    }

    ['grEditNip', 'grEditHp', 'grEditAlamat'].forEach(function(id) {
        const el = document.getElementById(id);
        el.addEventListener('input', function() {
            const key = id.replace('grEdit', '') === 'Nip' ? 'nip' : id.replace('grEdit', '') === 'Hp' ? 'no_hp' : 'alamat';
            const changed = el.value !== (editOriginal[key] || '');
            el.closest('.gr-float').classList.toggle('is-changed', changed);
        });
    });
    document.querySelectorAll('.gr-field-undo').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const key = btn.getAttribute('data-for');
            const el = document.getElementById(key === 'nip' ? 'grEditNip' : key === 'no_hp' ? 'grEditHp' : 'grEditAlamat');
            el.value = editOriginal[key] || '';
            el.closest('.gr-float').classList.remove('is-changed');
        });
    });
    document.getElementById('grEditReset').addEventListener('click', function() {
        const elNip = document.getElementById('grEditNip');
        const elHp = document.getElementById('grEditHp');
        const elAl = document.getElementById('grEditAlamat');
        elNip.value = editOriginal.nip || ''; elNip.closest('.gr-float').classList.remove('is-changed');
        elHp.value = editOriginal.no_hp || ''; elHp.closest('.gr-float').classList.remove('is-changed');
        elAl.value = editOriginal.alamat || ''; elAl.closest('.gr-float').classList.remove('is-changed');
        showToast('Perubahan dibatalkan', 'Field dikembalikan ke nilai semula.', 'success');
    });
    document.getElementById('grFormEdit').addEventListener('submit', function(e) {
        const btn = document.getElementById('grBtnEdit');
        btn.classList.add('gr-btn-loading');
        btn.innerHTML = '<span class="gr-spin"></span> Menyimpan...';
    });

    /* ================= delete modal ================= */
    function openDelete(g) {
        document.getElementById('grHapusAvatar').textContent = initials(g.nama);
        document.getElementById('grHapusAvatar').className = 'gr-guru-avatar ' + avatarClass(g.id);
        document.getElementById('grHapusNama').textContent = g.nama;
        document.getElementById('grHapusKode').textContent = g.kode;
        document.getElementById('grHapusKodeBox').textContent = g.kode;
        document.getElementById('grHapusNamaBox').textContent = g.nama;
        document.getElementById('grHapusNipBox').textContent = g.nip || 'Belum diisi';
        document.getElementById('grFormHapus').action = DELETE_URL.replace('__ID__', g.id);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('grModalHapus')).show();
    }

    /* ================= add modal: searchable user picker ================= */
    const pickBtn = document.getElementById('grUserPickBtn');
    const pickPanel = document.getElementById('grUserPickPanel');
    const userSearch = document.getElementById('grUserSearch');
    const userFeedback = document.getElementById('grUserFeedback');

    pickBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = pickBtn.classList.toggle('is-open');
        pickPanel.classList.toggle('is-open', isOpen);
        if (isOpen) { userSearch.value = ''; filterUsers(''); setTimeout(function() { userSearch.focus(); }, 60); }
    });
    document.addEventListener('click', function(e) {
        if (!document.getElementById('grUserPick').contains(e.target)) {
            pickBtn.classList.remove('is-open');
            pickPanel.classList.remove('is-open');
        }
    });
    userSearch.addEventListener('input', function() { filterUsers(this.value); });
    function filterUsers(q) {
        q = q.toLowerCase().trim();
        document.querySelectorAll('#grUserList .gr-uspick-item').forEach(function(item) {
            const hay = (item.getAttribute('data-name') + ' ' + item.getAttribute('data-email')).toLowerCase();
            item.style.display = hay.includes(q) ? '' : 'none';
        });
        const anyVisible = Array.prototype.some.call(document.querySelectorAll('#grUserList .gr-uspick-item'), function(i) { return i.style.display !== 'none'; });
        const emptyMsg = document.querySelector('#grUserList .gr-uspick-empty');
        if (emptyMsg) emptyMsg.style.display = anyVisible ? 'none' : '';
    }
    document.getElementById('grUserList').addEventListener('click', function(e) {
        const item = e.target.closest('.gr-uspick-item');
        if (!item) return;
        selectUser(item);
    });
    function selectUser(item) {
        document.getElementById('grUserId').value = item.getAttribute('data-id');
        document.getElementById('grUserPickVal').innerHTML = '<i class="fas fa-user-tie"></i> <b>' + esc(item.getAttribute('data-name')) + '</b>';
        document.getElementById('grPreviewNama').textContent = item.getAttribute('data-name');
        document.getElementById('grPreviewEmail').textContent = item.getAttribute('data-email');
        document.getElementById('grPreviewAvatar').textContent = initials(item.getAttribute('data-name'));
        document.getElementById('grPreviewKode').textContent = NEXT_KODE;
        document.getElementById('grPreviewKodeInline').textContent = NEXT_KODE;
        document.getElementById('grNextKode').textContent = NEXT_KODE;
        document.getElementById('grPreview').classList.add('on');
        userFeedback.classList.remove('is-on');
        document.getElementById('grUserPickVal').closest('.gr-uspick').classList.remove('gr-shake');
        pickBtn.classList.remove('is-open');
        pickPanel.classList.remove('is-open');
        document.querySelectorAll('#grUserList .gr-uspick-item').forEach(function(i) { i.classList.remove('is-selected'); });
        item.classList.add('is-selected');
    }

    /* preselect from old() after validation error */
    const oldUserId = document.getElementById('grUserId').value;
    if (oldUserId) {
        const match = document.querySelector('#grUserList .gr-uspick-item[data-id="' + oldUserId + '"]');
        if (match) selectUser(match);
    }

    /* add form validation + loading */
    document.getElementById('grFormTambah').addEventListener('submit', function(e) {
        const uid = document.getElementById('grUserId').value;
        if (!uid) {
            e.preventDefault();
            userFeedback.classList.add('is-on');
            document.getElementById('grUserPick').classList.add('gr-shake');
            setTimeout(function() { document.getElementById('grUserPick').classList.remove('gr-shake'); }, 450);
            pickPanel.classList.add('is-open');
            pickBtn.classList.add('is-open');
            return;
        }
        const btn = document.getElementById('grBtnTambah');
        btn.classList.add('gr-btn-loading');
        btn.innerHTML = '<span class="gr-spin"></span> Menyimpan...';
    });

    /* ================= skeleton boot ================= */
    setTimeout(function() {
        document.getElementById('grSkeleton').style.display = 'none';
        render();
    }, 450);

    /* auto-open add modal when validation errors exist */
    @if($errors->any())
        bootstrap.Modal.getOrCreateInstance(document.getElementById('grModalTambah')).show();
    @endif
});
</script>
@endpush
