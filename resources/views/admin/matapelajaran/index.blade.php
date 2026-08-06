@extends('layouts.main')
@section('title', 'Mata Pelajaran')
@section('content')
@include('component.admin.absensi-module')

@php
    $todayLabel = now()->translatedFormat('l, d F Y');
    $totalMapel = $mapel->total();
    $mapelCollection = $mapel->getCollection();
    $aktifCount = $mapelCollection->where('status', 'Aktif')->count();
    $nonaktifCount = $mapelCollection->where('status', 'Nonaktif')->count();
    $totalKurikulum = $kurikulums->count();

    $visibleCount = $mapelCollection->count();
    $persenAktif = $visibleCount ? (int) round($aktifCount / $visibleCount * 100) : 0;

    $jenjangDist = $mapelCollection
        ->countBy(function ($m) { return $m->jenjang->nama_jenjang ?? 'Tanpa Jenjang'; })
        ->sortDesc();
    $kurikulumDist = $mapelCollection
        ->countBy(function ($m) { return $m->kurikulum->nama_kurikulum ?? 'Tanpa Kurikulum'; })
        ->sortDesc();
    $kelompokDist = $mapelCollection
        ->countBy(function ($m) { return $m->kelompok ?? 'Tanpa Kelompok'; })
        ->sortDesc();

    $jenjangTotal = $jenjangDist->sum();
    $kurikulumTotal = $kurikulumDist->sum();
    $kelompokTotal = $kelompokDist->sum();

    $topKurikulum = $kurikulumDist->keys()->first();
    $topJenjang = $jenjangDist->keys()->first();
    $topKelompok = $kelompokDist->keys()->first();

    $hasActiveFilter = (bool) (request('search') || request('jenjang_id') || request('kurikulum_id') || request('status'));

    $chips = [];
    $qBase = request()->except(['page']);
    if (request('search')) {
        $chips[] = ['label' => 'Cari: ' . request('search'), 'url' => route('mata-pelajaran.index', array_merge($qBase, ['search' => null]))];
    }
    if (request('jenjang_id')) {
        $chips[] = [
            'label' => 'Jenjang: ' . (optional($jenjangs->firstWhere('id', request('jenjang_id')))->nama_jenjang ?? '?'),
            'url' => route('mata-pelajaran.index', array_merge($qBase, ['jenjang_id' => null])),
        ];
    }
    if (request('kurikulum_id')) {
        $chips[] = [
            'label' => 'Kurikulum: ' . (optional($kurikulums->firstWhere('id', request('kurikulum_id')))->nama_kurikulum ?? '?'),
            'url' => route('mata-pelajaran.index', array_merge($qBase, ['kurikulum_id' => null])),
        ];
    }
    if (request('status')) {
        $chips[] = ['label' => 'Status: ' . request('status'), 'url' => route('mata-pelajaran.index', array_merge($qBase, ['status' => null]))];
    }
@endphp

<style>
    /* ============================================================
       MATA PELAJARAN — Academic Subject Management Center
       Built on the shared ABSENSI design system (.abs-mod / .abm-*)
       ============================================================ */
    .mpl-mod { margin-top: 22px; }
    .mpl-mod .page-title-content { display: none !important; }
    .mpl-mod .abm-hero-sub { max-width: 720px; }

    .mpl-kpi.total { --ab-kpi-glow: rgba(37,99,235,.08); --ab-kpi-wm: #2563eb; }
    .mpl-kpi.aktif { --ab-kpi-glow: rgba(22,163,74,.08); --ab-kpi-wm: #16a34a; }
    .mpl-kpi.nonaktif { --ab-kpi-glow: rgba(217,119,6,.08); --ab-kpi-wm: #d97706; }
    .mpl-kpi.kurikulum { --ab-kpi-glow: rgba(124,58,237,.08); --ab-kpi-wm: #7c3aed; }

    .mpl-kpi-sub {
        margin-top: 6px; font-size: 10.5px; color: var(--ab-text-3);
        display: flex; align-items: center; gap: 5px; font-weight: 600;
    }

    /* ---------- Breadcrumb ---------- */
    .mpl-crumb {
        display: flex; flex-wrap: wrap; align-items: center; gap: 8px;
        font-size: 11.5px; font-weight: 600; color: rgba(255,255,255,.72);
        margin-bottom: 14px; position: relative; z-index: 1;
    }
    .mpl-crumb a { color: rgba(255,255,255,.86); transition: color .2s; }
    .mpl-crumb a:hover { color: #fff; text-decoration: underline !important; }
    .mpl-crumb i { font-size: 8px; opacity: .6; }
    .mpl-crumb .current { color: #fff; font-weight: 800; }

    /* ---------- Insight / analytics panel ---------- */
    .mpl-insight { margin-bottom: 18px; }
    .mpl-insight-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
    .mpl-insight-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .mpl-insight-col { display: flex; flex-direction: column; gap: 14px; min-width: 0; }
    .mpl-insight-card {
        background: var(--ab-card); border: 1px solid var(--ab-border);
        border-radius: 16px; padding: 16px 18px; box-shadow: var(--ab-shadow);
        min-width: 0;
    }
    .mpl-insight-label {
        font-size: 11px; font-weight: 700; color: var(--ab-text-3);
        text-transform: uppercase; letter-spacing: .4px;
        display: flex; align-items: center; gap: 7px;
    }
    .mpl-insight-label i { color: var(--ab-primary); }
    .mpl-insight-big {
        font-size: 34px; font-weight: 800; color: var(--ab-text); line-height: 1;
        margin: 10px 0 12px; display: flex; align-items: baseline; gap: 4px;
    }
    .mpl-insight-big small { font-size: 15px; color: var(--ab-text-3); font-weight: 700; }
    .mpl-insight-stat {
        display: flex; justify-content: space-between; align-items: center; gap: 10px;
        margin-top: 12px; padding-top: 12px; border-top: 1px dashed var(--ab-border);
    }
    .mpl-insight-stat .k { font-size: 12px; color: var(--ab-text-2); font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .mpl-insight-stat .k i { color: var(--ab-primary); font-size: 12px; }
    .mpl-insight-stat .v { font-size: 13px; font-weight: 800; color: var(--ab-text); text-align: right; }
    .mpl-dist-title { font-size: 12.5px; font-weight: 800; color: var(--ab-text); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .mpl-dist-title i { color: var(--ab-primary); font-size: 13px; }
    .mpl-dist { display: flex; flex-direction: column; gap: 11px; }
    .mpl-dist-row { display: grid; grid-template-columns: 110px 1fr 34px; align-items: center; gap: 10px; }
    .mpl-dist-row .n { font-size: 11.5px; color: var(--ab-text-2); font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mpl-dist-row .abm-progress { height: 7px; }
    .mpl-dist-row .c { font-size: 11.5px; font-weight: 800; color: var(--ab-text-3); text-align: right; font-variant-numeric: tabular-nums; }
    .mpl-insight-empty { font-size: 12px; color: var(--ab-text-3); }

    /* ---------- Filter chips ---------- */
    .mpl-chips { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-bottom: 16px; }
    .mpl-chip {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 8px 6px 14px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
        background: var(--ab-primary-soft); color: var(--ab-primary-dark);
        border: 1px solid var(--ab-primary-border);
    }
    html.dark-mode .mpl-chip { color: #7dd3fc; }
    .mpl-chip-x {
        width: 22px; height: 22px; border-radius: 50%; display: inline-flex;
        align-items: center; justify-content: center; color: inherit;
        background: rgba(37,99,235,.1); transition: all .2s; font-size: 11px;
    }
    .mpl-chip-x:hover { background: var(--ab-primary); color: #fff; }
    .mpl-chip-clear {
        display: inline-flex; align-items: center; gap: 7px; padding: 6px 14px;
        border-radius: 20px; font-size: 11.5px; font-weight: 700; color: var(--ab-text-3);
        background: transparent; border: 1px dashed var(--ab-border); transition: all .2s;
    }
    .mpl-chip-clear:hover { color: var(--ab-red); border-color: var(--ab-red-border); background: var(--ab-red-soft); }

    /* ---------- Sticky filter toolbar ---------- */
    .mpl-toolbar {
        position: sticky; top: 78px; z-index: 940;
        display: grid; grid-template-columns: minmax(0, 1.3fr) repeat(4, minmax(150px, .5fr)) auto;
        gap: 12px; align-items: end;
        background: rgba(255,255,255,.92); border: 1px solid var(--ab-border);
        border-radius: 18px; padding: 14px 16px;
        box-shadow: 0 12px 28px -24px rgba(15,23,42,.18);
        backdrop-filter: blur(12px); margin-bottom: 18px;
    }
    html.dark-mode .mpl-toolbar { background: rgba(13,47,56,.92); }

    .mpl-field { display: flex; flex-direction: column; gap: 5px; }
    .mpl-field label { font-size: 10.5px; font-weight: 700; color: var(--ab-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .mpl-select-wrap { position: relative; }
    .mpl-select-wrap > i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ab-text-3); font-size: 12px; z-index: 2; pointer-events: none; }
    .mpl-select {
        width: 100%; min-height: 44px;
        border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; padding: 0 14px 0 34px;
        font-size: 12.5px; color: var(--ab-text); font-weight: 600;
        transition: border-color .2s, box-shadow .2s;
    }
    .mpl-select:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .mpl-toolbar .abm-btn { min-height: 44px; }

    /* ---------- Grid card ---------- */
    .mpl-card {
        background: var(--ab-card); border: 1px solid var(--ab-border);
        border-radius: 18px; box-shadow: var(--ab-shadow); overflow: hidden;
    }
    .mpl-card-head {
        display: flex; justify-content: space-between; align-items: center; gap: 14px;
        padding: 18px 20px 14px; flex-wrap: wrap;
    }
    .mpl-card-title { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 800; color: var(--ab-text); }
    .mpl-card-title i { color: var(--ab-primary); }
    .mpl-card-sub { margin-top: 4px; font-size: 12px; color: var(--ab-text-3); }

    /* ---------- Top loading bar ---------- */
    .mpl-loadbar { height: 3px; overflow: hidden; position: relative; }
    .mpl-loadbar span {
        display: block; height: 100%; width: 40%; border-radius: 4px;
        background: linear-gradient(90deg, var(--ab-primary), var(--ab-primary-3));
        animation: mplLoadbar 1.1s ease-in-out infinite;
    }
    @keyframes mplLoadbar { 0% { margin-left: -40%; } 100% { margin-left: 110%; } }
    .mpl-loadbar.is-done span { opacity: 0; }
    .mpl-loadbar.is-done { height: 0; transition: height .35s; }

    /* ---------- Row entrance ---------- */
    .mpl-fade-row { animation: mplRowIn .45s cubic-bezier(.22,1,.36,1) both; animation-delay: calc(var(--i, 0) * 30ms); }
    @keyframes mplRowIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { .mpl-fade-row { animation: none; } }

    /* ---------- Search highlight ---------- */
    .mpl-hl mark { background: var(--ab-amber); color: #451a03; padding: 0 2px; border-radius: 3px; font-weight: 700; }
    html.dark-mode .mpl-hl mark { color: #111; }

    /* ---------- Premium data grid ---------- */
    .mpl-table-wrap { padding: 0 18px 4px; }
    .mpl-table {
        width: 100%; border-collapse: separate; border-spacing: 0 12px;
        margin: 0 !important; background: transparent;
    }
    .mpl-table thead th {
        background: var(--ab-card);
        padding: 0 16px 8px;
        font-size: 11px; text-transform: uppercase; letter-spacing: .5px;
        color: var(--ab-text-3); font-weight: 800; text-align: left; white-space: nowrap;
        border-bottom: 1px solid var(--ab-border);
    }
    .mpl-table tbody td {
        background: var(--ab-card);
        border-top: 1px solid var(--ab-border); border-bottom: 1px solid var(--ab-border);
        padding: 14px 12px; font-size: 13px; color: var(--ab-text-2); vertical-align: middle;
        transition: background .22s, border-color .22s, transform .22s;
    }
    .mpl-table .abm-chip { white-space: normal; }
    .mpl-table tbody td:first-child {
        border-left: 1px solid var(--ab-border); border-radius: 18px 0 0 18px;
        width: 52px; text-align: center; color: var(--ab-text-3); font-weight: 700;
    }
    .mpl-table tbody td:last-child { border-right: 1px solid var(--ab-border); border-radius: 0 18px 18px 0; }
    .mpl-table tbody tr { transition: transform .22s ease; }
    .mpl-table tbody tr:hover td { background: var(--ab-bg); border-color: var(--ab-primary-border); }
    .mpl-table tbody tr:hover { transform: translateY(-2px); }
    .mpl-th-check { display: flex; align-items: center; justify-content: center; }

    .mpl-subject { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .mpl-subject > div { min-width: 0; }
    .mpl-subject-icon {
        width: 44px; height: 44px; border-radius: 14px; flex-shrink: 0; color: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 16px;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        box-shadow: 0 4px 10px -2px rgba(37,99,235,.35);
    }
    .mpl-subject-name { font-size: 14px; font-weight: 800; color: var(--ab-text); line-height: 1.3; }
    .mpl-subject-code { margin-top: 4px; font-size: 11px; color: var(--ab-text-3); }

    .mpl-chip-stack { display: flex; flex-wrap: wrap; gap: 8px; }

    .mpl-check { width: 19px; height: 19px; accent-color: var(--ab-primary); cursor: pointer; }

    /* ---------- Icon actions ---------- */
    .mpl-actions { display: flex; justify-content: center; gap: 8px; }
    .mpl-icon-btn {
        width: 44px; height: 44px; border-radius: 12px;
        border: 1px solid var(--ab-border); background: var(--ab-card);
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--ab-text-2); font-size: 15px; cursor: pointer;
        transition: all .22s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 4px 10px -6px rgba(15,23,42,.18);
    }
    .mpl-icon-btn:hover { transform: translateY(-2px); }
    .mpl-icon-btn--edit { color: #d97706; }
    .mpl-icon-btn--edit:hover { background: var(--ab-amber-soft); border-color: var(--ab-amber-border); box-shadow: 0 10px 20px -10px rgba(217,119,6,.28); }
    .mpl-icon-btn--delete { color: var(--ab-red); }
    .mpl-icon-btn--delete:hover { background: var(--ab-red-soft); border-color: var(--ab-red-border); box-shadow: 0 10px 20px -10px rgba(220,38,38,.28); }
    .mpl-icon-btn--view { color: var(--ab-text-3); opacity: .6; cursor: not-allowed; }
    .mpl-icon-btn--view:hover { background: var(--ab-border-soft); transform: none; }

    /* ---------- Mobile cards ---------- */
    .mpl-mobile-grid { display: none; padding: 0 18px 18px; gap: 14px; }
    .mpl-mobile-card {
        background: var(--ab-card); border: 1px solid var(--ab-border); border-radius: 18px;
        box-shadow: var(--ab-shadow); padding: 16px; display: grid; gap: 14px;
    }
    .mpl-mobile-head { display: flex; align-items: center; gap: 12px; }
    .mpl-mobile-check { margin-left: auto; flex-shrink: 0; }
    .mpl-mobile-grid-inner { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .mpl-mobile-stat { background: var(--ab-border-soft); border-radius: 12px; padding: 10px 12px; }
    .mpl-mobile-stat .k { font-size: 10px; color: var(--ab-text-3); text-transform: uppercase; letter-spacing: .3px; font-weight: 700; }
    .mpl-mobile-stat .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: var(--ab-text); }
    .mpl-mobile-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .mpl-mobile-action {
        flex: 1; min-height: 44px; border-radius: 12px; border: 1px solid var(--ab-border);
        background: var(--ab-card); display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        font-size: 12px; font-weight: 700; color: var(--ab-text-2); text-decoration: none;
        transition: all .22s cubic-bezier(.4,0,.2,1);
    }
    .mpl-mobile-action--edit { color: #d97706; }
    .mpl-mobile-action--edit:hover { background: var(--ab-amber-soft); border-color: var(--ab-amber-border); }
    .mpl-mobile-action--delete { color: var(--ab-red); }
    .mpl-mobile-action--delete:hover { background: var(--ab-red-soft); border-color: var(--ab-red-border); }

    /* ---------- Bulk action bar ---------- */
    .mpl-bulkbar { display: none; margin: 0 18px 16px; }
    .mpl-bulkbar.is-show { display: flex; }
    .mpl-bulkbar .abm-btn--danger:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }
    .mpl-coming-badge {
        position: absolute; top: -11px; right: -8px; z-index: 2;
        font-size: 9px; padding: 3px 8px;
        box-shadow: 0 6px 14px -6px rgba(217,119,6,.55);
    }

    /* ---------- Empty state ---------- */
    .mpl-empty { text-align: center; padding: 44px 20px 36px; }
    .mpl-empty > i { font-size: 44px; opacity: .4; color: var(--ab-primary); margin-bottom: 12px; }
    .mpl-empty-title { font-size: 15px; font-weight: 700; color: var(--ab-text-2); margin-bottom: 4px; }
    .mpl-empty-sub { font-size: 12.5px; color: var(--ab-text-3); margin-bottom: 18px; }

    /* ---------- Pagination ---------- */
    .mpl-pagination {
        display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
        padding: 4px 20px 20px; font-size: 13px; color: var(--ab-text-3);
    }
    .mpl-pagination .pagination { gap: 6px; margin: 0; }
    .mpl-pagination .page-item .page-link {
        border: 1px solid var(--ab-border); border-radius: 10px; min-width: 38px; text-align: center;
        color: var(--ab-text-2); background: var(--ab-card); font-size: 12px; font-weight: 700; box-shadow: none;
    }
    .mpl-pagination .page-item.active .page-link { background: var(--ab-grad); border-color: transparent; color: #fff; box-shadow: 0 12px 20px -14px rgba(37,99,235,.45); }
    .mpl-pagination .page-item.disabled .page-link { opacity: .45; background: var(--ab-border-soft); }

    /* ---------- Toasts ---------- */
    .mpl-toast-wrap { position: fixed; top: 92px; right: 18px; z-index: 1200; display: grid; gap: 10px; width: min(360px, calc(100vw - 24px)); pointer-events: none; }
    .mpl-toast {
        display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 16px;
        background: rgba(255,255,255,.96); border: 1px solid var(--ab-border);
        box-shadow: 0 18px 34px -24px rgba(15,23,42,.24); backdrop-filter: blur(12px);
        opacity: 0; transform: translateY(-10px);
        transition: opacity .25s ease, transform .25s ease; pointer-events: auto;
    }
    html.dark-mode .mpl-toast { background: rgba(13,47,56,.94); }
    .mpl-toast.is-show { opacity: 1; transform: translateY(0); }
    .mpl-toast-icon { width: 40px; height: 40px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .mpl-toast.success .mpl-toast-icon { background: var(--ab-green-soft); color: var(--ab-green); }
    .mpl-toast.error .mpl-toast-icon { background: var(--ab-red-soft); color: var(--ab-red); }
    .mpl-toast-title { font-size: 13px; font-weight: 800; color: var(--ab-text); }
    .mpl-toast-text { margin-top: 2px; font-size: 12px; line-height: 1.6; color: var(--ab-text-2); }

    /* ---------- Modal (shared) ---------- */
    .mpl-modal .modal-dialog { max-width: 640px; }
    .mpl-modal .modal-content { border: 1px solid var(--ab-border); border-radius: 20px; overflow: hidden; box-shadow: var(--ab-shadow-lg); background: var(--ab-card); }
    .mpl-modal-hero {
        position: relative; overflow: hidden; background: var(--ab-grad); color: #fff;
        padding: 20px 22px 18px;
    }
    .mpl-modal-hero::before {
        content: ''; position: absolute; inset: 0; opacity: .24; pointer-events: none;
        background-image: linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
        background-size: 28px 28px;
    }
    .mpl-modal-hero::after {
        content: ''; position: absolute; width: 180px; height: 180px; top: -70px; right: -30px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.18), transparent 72%); pointer-events: none;
    }
    .mpl-modal-hero--danger { background: linear-gradient(135deg, #dc2626, #f87171); box-shadow: 0 18px 40px -12px rgba(220,38,38,.4); }
    .mpl-modal-hero-top { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .mpl-modal-badge {
        width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 22px;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22);
        backdrop-filter: blur(8px); box-shadow: inset 0 1px 0 rgba(255,255,255,.28);
    }
    .mpl-modal-title { font-size: 18px; font-weight: 800; margin: 0; color: #fff; }
    .mpl-modal-subtitle { margin: 5px 0 0; color: rgba(255,255,255,.82); font-size: 12px; line-height: 1.6; }
    .mpl-modal-meta { position: relative; z-index: 1; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .mpl-modal-meta-item { padding: 10px 12px; border-radius: 14px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.18); backdrop-filter: blur(8px); }
    .mpl-modal-meta-item .k { font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: rgba(255,255,255,.78); font-weight: 700; }
    .mpl-modal-meta-item .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: #fff; }
    .mpl-modal .modal-body { padding: 20px; }
    .mpl-modal-panel { background: var(--ab-bg); border: 1px solid var(--ab-border); border-radius: 18px; padding: 16px; box-shadow: inset 0 1px 0 rgba(255,255,255,.5); }
    .mpl-modal-panel h4 { margin: 0 0 6px; font-size: 14px; font-weight: 800; color: var(--ab-text); }
    .mpl-modal-panel p { margin: 0; color: var(--ab-text-3); font-size: 12px; line-height: 1.6; }
    .mpl-form-grid { display: grid; gap: 16px; margin-top: 16px; }
    .mpl-modal .modal-footer { padding: 14px 20px 20px; border-top: 1px solid var(--ab-border-soft); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .mpl-modal-footer-note { font-size: 11.5px; color: var(--ab-text-3); line-height: 1.6; }

    .mpl-field-label { font-weight: 600; font-size: 13px; color: var(--ab-text-2); margin-bottom: 6px; }
    .mpl-field-label i { color: var(--ab-primary); margin-right: 6px; }
    .mpl-control {
        width: 100%; border: 1.5px solid var(--ab-border); background: var(--ab-card);
        border-radius: 12px; height: 46px; padding: 0 14px; font-size: 13.5px; color: var(--ab-text);
        transition: border-color .2s, box-shadow .2s;
    }
    .mpl-control:focus { outline: none; border-color: var(--ab-primary); box-shadow: 0 0 0 3px var(--ab-primary-soft); }
    .mpl-input-icon-wrap { position: relative; }
    .mpl-input-icon-wrap > i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ab-text-3); font-size: 14px; z-index: 2; pointer-events: none; }
    .mpl-input-icon-wrap .mpl-control, .mpl-input-icon-wrap .mpl-select { padding-left: 40px; }
    .mpl-live-note { margin-top: 8px; font-size: 11.5px; color: var(--ab-text-3); line-height: 1.6; min-height: 18px; }

    /* ---------- Edit field change highlight ---------- */
    .mpl-edit-label-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .mpl-edit-field .mpl-control, .mpl-edit-field .mpl-select { transition: border-color .2s, box-shadow .2s, background .2s; }
    .mpl-edit-field.is-changed .mpl-control, .mpl-edit-field.is-changed .mpl-select { border-color: var(--ab-amber); box-shadow: 0 0 0 3px var(--ab-amber-soft); background: var(--ab-amber-soft); }
    .mpl-edit-chip {
        display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px;
        border-radius: 20px; font-size: 9.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .3px;
        color: #92400e; background: var(--ab-amber-soft); border: 1px solid var(--ab-amber-border);
    }
    .mpl-edit-chip[hidden] { display: none; }
    .mpl-edit-preview { margin-top: 16px; border-radius: 16px; border: 1px solid var(--ab-border); background: var(--ab-bg); overflow: hidden; }
    .mpl-edit-preview-head {
        padding: 10px 16px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px;
        color: var(--ab-text-3); border-bottom: 1px solid var(--ab-border-soft);
        display: flex; align-items: center; gap: 8px;
    }
    .mpl-edit-preview-head i { color: var(--ab-primary); }
    .mpl-edit-preview-row { display: flex; justify-content: space-between; gap: 12px; padding: 9px 16px; border-bottom: 1px solid var(--ab-border-soft); font-size: 12px; }
    .mpl-edit-preview-row:last-child { border-bottom: none; }
    .mpl-edit-preview-row .k { color: var(--ab-text-3); font-weight: 600; }
    .mpl-edit-preview-row .v { color: var(--ab-text); font-weight: 700; text-align: right; }
    .mpl-edit-preview-row.is-changed { background: var(--ab-amber-soft); }
    .mpl-edit-preview-row.is-changed .v { color: #92400e; }

    /* ---------- Add wizard ---------- */
    .mpl-wizard-dialog { max-width: 900px !important; }
    .mpl-wizard-track { position: relative; margin: 2px 8px 22px; height: 4px; border-radius: 4px; background: var(--ab-border); }
    .mpl-wizard-track-fill { display: block; height: 100%; width: 0; border-radius: 4px; background: var(--ab-grad); transition: width .4s cubic-bezier(.22,1,.36,1); }
    .mpl-steps { position: relative; display: flex; margin-top: 18px; }
    .mpl-step {
        flex: 1; position: relative; display: flex; flex-direction: column; align-items: center; gap: 8px;
        text-align: center; z-index: 1; border: none; background: transparent; cursor: pointer;
        font-family: inherit; padding: 4px;
    }
    .mpl-step-dot {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800; color: var(--ab-text-3);
        background: var(--ab-card); border: 2px solid var(--ab-border);
        transition: all .3s cubic-bezier(.4,0,.2,1);
    }
    .mpl-step.active .mpl-step-dot { background: var(--ab-grad); border-color: transparent; color: #fff; box-shadow: 0 8px 18px -6px rgba(37,99,235,.5); transform: scale(1.08); }
    .mpl-step.done .mpl-step-dot { background: var(--ab-green-soft); border-color: var(--ab-green-border); color: var(--ab-green); }
    .mpl-step-t { font-size: 11.5px; font-weight: 800; color: var(--ab-text); }
    .mpl-step-d { font-size: 10px; color: var(--ab-text-3); margin-top: 2px; }

    .mpl-wizard-body { padding: 18px 22px 22px; }
    .mpl-pane { display: none; animation: mplFade .3s ease; }
    .mpl-pane.is-show { display: block; }
    @keyframes mplFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    .mpl-pane-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .mpl-pane-head .ic {
        width: 44px; height: 44px; border-radius: 14px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--ab-primary-soft); color: var(--ab-primary); font-size: 18px;
    }
    .mpl-pane-head h3 { margin: 0; font-size: 15px; font-weight: 800; color: var(--ab-text); }
    .mpl-pane-head p { margin: 3px 0 0; font-size: 12px; color: var(--ab-text-3); }

    .mpl-form-grid-2col { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .mpl-code-preview {
        padding: 14px 16px; border: 1.5px dashed var(--ab-primary-border); border-radius: 16px;
        background: var(--ab-primary-soft); display: grid; gap: 10px; align-content: center;
    }
    .mpl-code-preview-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .mpl-code-preview-value { font-size: 26px; font-weight: 800; color: var(--ab-primary); letter-spacing: .08em; font-variant-numeric: tabular-nums; }

    .mpl-live-card { display: none; margin-top: 16px; border-radius: 16px; border: 1.5px solid var(--ab-primary-border); background: var(--ab-primary-soft); padding: 14px 16px; }
    .mpl-live-card.on { display: block; animation: mplFade .3s ease; }
    .mpl-live-card .ttl { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: var(--ab-primary); margin-bottom: 10px; }
    .mpl-live-row { display: flex; justify-content: space-between; gap: 10px; padding: 7px 0; border-bottom: 1px dashed var(--ab-border); font-size: 12.5px; }
    .mpl-live-row:last-child { border-bottom: none; }
    .mpl-live-row .k { color: var(--ab-text-3); font-weight: 600; }
    .mpl-live-row .v { color: var(--ab-text); font-weight: 800; text-align: right; }

    .mpl-status-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .mpl-status-card {
        position: relative; padding: 16px; display: flex; align-items: center; gap: 12px; cursor: pointer;
        transition: all .22s ease; border: 1.5px solid var(--ab-border); border-radius: 16px; background: var(--ab-card);
    }
    .mpl-status-card:hover { border-color: var(--ab-primary-border); transform: translateY(-2px); box-shadow: var(--ab-shadow); }
    .mpl-status-card.is-selected { border-color: var(--ab-primary); background: var(--ab-primary-soft); box-shadow: 0 0 0 4px var(--ab-primary-soft); }
    .mpl-status-input { position: absolute; opacity: 0; pointer-events: none; }
    .mpl-status-icon { width: 42px; height: 42px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .mpl-status-icon.ok { background: var(--ab-green-soft); color: var(--ab-green); }
    .mpl-status-icon.off { background: var(--ab-border-soft); color: var(--ab-text-3); }
    .mpl-status-body { display: grid; gap: 3px; }
    .mpl-status-body strong { font-size: 13.5px; font-weight: 800; color: var(--ab-text); margin: 0; }
    .mpl-status-body small { font-size: 11px; color: var(--ab-text-3); line-height: 1.5; }

    .mpl-kelompok-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .mpl-kelompok-card {
        position: relative; padding: 16px 14px; display: flex; flex-direction: column; align-items: center; gap: 10px;
        text-align: center; cursor: pointer; border: 1.5px solid var(--ab-border); border-radius: 16px;
        background: var(--ab-card); transition: all .22s ease;
    }
    .mpl-kelompok-card:hover { border-color: var(--ab-primary-border); transform: translateY(-3px); box-shadow: var(--ab-shadow); }
    .mpl-kelompok-card.is-selected { border-color: var(--ab-primary); background: var(--ab-primary-soft); box-shadow: 0 0 0 4px var(--ab-primary-soft); }
    .mpl-kelompok-input { position: absolute; opacity: 0; pointer-events: none; }
    .mpl-kelompok-icon {
        width: 42px; height: 42px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 17px; background: var(--ab-border-soft); color: var(--ab-text-3); transition: all .22s ease;
    }
    .mpl-kelompok-card.is-selected .mpl-kelompok-icon { background: var(--ab-primary); color: #fff; box-shadow: 0 6px 14px -4px rgba(37,99,235,.5); }
    .mpl-kelompok-card strong { font-size: 12.5px; font-weight: 800; color: var(--ab-text); }
    .mpl-kelompok-card small { font-size: 10.5px; color: var(--ab-text-3); line-height: 1.5; }

    .mpl-summary { border-radius: 16px; border: 1px solid var(--ab-border); background: var(--ab-bg); overflow: hidden; margin-top: 16px; }
    .mpl-summary-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--ab-border-soft); font-size: 12.5px; }
    .mpl-summary-row:last-child { border-bottom: none; }
    .mpl-summary-row .k { color: var(--ab-text-3); font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
    .mpl-summary-row .k i { color: var(--ab-primary); font-size: 12px; }
    .mpl-summary-row .v { color: var(--ab-text); font-weight: 800; text-align: right; }
    .mpl-summary-warn { background: var(--ab-amber-soft); border-bottom: none; }
    .mpl-summary-warn .k, .mpl-summary-warn .v { color: #92400e; }

    .mpl-wizard-nav { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 18px; flex-wrap: wrap; }
    .mpl-wizard-nav--right { margin-left: auto; display: flex; gap: 8px; flex-wrap: wrap; }

    /* ---------- Import drag & drop ---------- */
    .mpl-import-steps { position: relative; z-index: 1; display: flex; gap: 6px; margin-top: 16px; }
    .mpl-import-step { flex: 1; text-align: center; padding: 8px 6px; border-radius: 12px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.14); }
    .mpl-import-step .n { font-size: 9.5px; text-transform: uppercase; letter-spacing: .4px; opacity: .75; font-weight: 700; }
    .mpl-import-step .t { font-size: 11.5px; font-weight: 800; margin-top: 3px; }
    .mpl-import-step.is-done { background: rgba(255,255,255,.22); border-color: rgba(255,255,255,.28); }
    .mpl-dropzone {
        position: relative; border: 2px dashed var(--ab-primary-border); border-radius: 16px;
        background: var(--ab-primary-soft); padding: 34px 20px; text-align: center; cursor: pointer;
        transition: all .25s ease;
    }
    .mpl-dropzone:hover { border-color: var(--ab-primary); background: rgba(37,99,235,.08); }
    .mpl-dropzone.drag { border-color: var(--ab-primary); box-shadow: inset 0 0 0 3px var(--ab-primary-border); transform: scale(1.01); }
    .mpl-dropzone.has-file { border-color: var(--ab-green); background: var(--ab-green-soft); border-style: solid; padding: 22px 20px; }
    .mpl-dropzone > i { font-size: 38px; color: var(--ab-primary); margin-bottom: 10px; opacity: .85; }
    .mpl-dropzone-title { font-size: 14px; font-weight: 800; color: var(--ab-text); }
    .mpl-dropzone-sub { font-size: 12px; color: var(--ab-text-3); margin-top: 4px; }
    .mpl-dropzone-hint {
        display: inline-block; margin-top: 12px; padding: 4px 12px; border-radius: 20px;
        font-size: 10.5px; font-weight: 700; color: var(--ab-text-3);
        background: var(--ab-card); border: 1px solid var(--ab-border);
    }
    .mpl-dropzone-input { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; }
    .mpl-dropzone:focus-within { outline: 2px solid var(--ab-primary-3); outline-offset: 2px; }
    .mpl-file-preview {
        display: none; align-items: center; gap: 12px; margin-top: 14px; padding: 12px 14px;
        border-radius: 14px; background: var(--ab-card); border: 1px solid var(--ab-green-border);
    }
    .mpl-file-preview.is-show { display: flex; }
    .mpl-file-preview > i { font-size: 22px; color: #16a34a; }
    .mpl-file-preview .meta { flex: 1; min-width: 0; }
    .mpl-file-preview .meta strong { display: block; font-size: 13px; color: var(--ab-text); }
    .mpl-file-preview .meta small { font-size: 11px; color: var(--ab-text-3); }

    /* ---------- Export dialog ---------- */
    .mpl-export-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .mpl-export-card {
        display: flex; align-items: center; gap: 13px; padding: 16px;
        border-radius: 16px; border: 1.5px solid var(--ab-border); background: var(--ab-card);
        transition: all .22s ease; text-decoration: none !important;
    }
    a.mpl-export-card:hover { transform: translateY(-3px); box-shadow: var(--ab-shadow-lg); border-color: var(--ab-primary-border); }
    .mpl-export-card.is-disabled { opacity: .6; cursor: not-allowed; }
    .mpl-export-card.is-disabled:hover { transform: none; box-shadow: none; }
    .mpl-export-icon {
        width: 46px; height: 46px; border-radius: 13px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center; font-size: 19px; color: #fff;
    }
    .mpl-export--excel .mpl-export-icon { background: linear-gradient(135deg, #16a34a, #4ade80); box-shadow: 0 6px 14px -4px rgba(22,163,74,.45); }
    .mpl-export--pdf .mpl-export-icon { background: linear-gradient(135deg, #dc2626, #f87171); box-shadow: 0 6px 14px -4px rgba(220,38,38,.4); }
    .mpl-export-card .info { flex: 1; min-width: 0; }
    .mpl-export-card .info strong { display: block; font-size: 13.5px; color: var(--ab-text); }
    .mpl-export-card .info small { font-size: 11.5px; color: var(--ab-text-3); }
    .mpl-export-go { color: var(--ab-text-3); font-size: 15px; flex-shrink: 0; }
    a.mpl-export-card:hover .mpl-export-go { color: var(--ab-green); }

    /* ---------- Delete confirm summary ---------- */
    .mpl-delete-target { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .mpl-delete-box { padding: 12px; border-radius: 14px; background: var(--ab-red-soft); border: 1px solid var(--ab-red-border); }
    .mpl-delete-box .k { font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: var(--ab-red); font-weight: 700; }
    .mpl-delete-box .v { margin-top: 5px; font-size: 13px; font-weight: 800; color: var(--ab-text); word-break: break-word; }

    /* ---------- Button spinner / ripple ---------- */
    .mpl-spinner {
        display: inline-block; width: 15px; height: 15px;
        border: 2px solid rgba(255,255,255,.35); border-top-color: #fff; border-radius: 50%;
        animation: mplSpin .7s linear infinite;
    }
    @keyframes mplSpin { to { transform: rotate(360deg); } }
    .mpl-ripple { position: relative; overflow: hidden; }
    .mpl-ripple-span { position: absolute; border-radius: 50%; background: rgba(255,255,255,.3); transform: scale(0); animation: mplRipple .55s linear; pointer-events: none; }
    @keyframes mplRipple { to { transform: scale(4); opacity: 0; } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1199.98px) {
        .mpl-toolbar { top: 70px; }
        .mpl-insight-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 991.98px) {
        .mpl-toolbar { grid-template-columns: 1fr 1fr; }
        .mpl-form-grid-2col, .mpl-status-grid, .mpl-modal-meta, .mpl-delete-target { grid-template-columns: 1fr 1fr; }
        .mpl-kelompok-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .mpl-export-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767.98px) {
        .mpl-toolbar { grid-template-columns: 1fr; top: 64px; }
        .mpl-table-wrap { display: none !important; }
        .mpl-mobile-grid { display: grid; }
        .mpl-insight-grid { grid-template-columns: 1fr; }
        .mpl-dist-row { grid-template-columns: 90px 1fr 34px; }
        .abm-hero { padding: 20px; }
        .abm-hero-row { flex-direction: column; align-items: stretch; }
        .abm-hero-actions { justify-content: flex-start; }
        .mpl-form-grid-2col, .mpl-status-grid, .mpl-modal-meta, .mpl-delete-target { grid-template-columns: 1fr; }
    }
    @media (max-width: 575.98px) {
        .mpl-mobile-grid-inner { grid-template-columns: 1fr; }
        .mpl-mobile-actions { flex-direction: column; }
        .mpl-kelompok-grid { grid-template-columns: 1fr; }
        .mpl-wizard-body { padding: 16px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .mpl-mod *, .mpl-mod *::before, .mpl-mod *::after { animation: none !important; transition: none !important; scroll-behavior: auto !important; }
    }
</style>

<div class="abs-mod mpl-mod">
    {{-- ===== HERO ===== --}}
    <div class="abm-hero">
        <div class="abm-hero-grid"></div>
        <nav class="mpl-crumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Beranda</a>
            <i class="fas fa-angle-right"></i>
            <span>Manajemen Akademik</span>
            <i class="fas fa-angle-right"></i>
            <span class="current" aria-current="page">Mata Pelajaran</span>
        </nav>
        <div class="abm-hero-row">
            <div class="abm-hero-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="abm-hero-icon"><i class="fas fa-book-open"></i></div>
                    <div>
                        <div class="abm-chip abm-chip--blue mb-2"><i class="fas fa-sitemap"></i> Academic Subject Management Center</div>
                        <h3>Mata Pelajaran</h3>
                        <p class="abm-hero-sub">Pusat pengelolaan seluruh mata pelajaran untuk mendukung jadwal pelajaran, guru, kurikulum, penilaian, dan beban mengajar dalam satu workspace yang cepat dan nyaman digunakan.</p>
                    </div>
                </div>
                <div class="abm-hero-badges">
                    <span class="abm-hero-badge"><i class="fas fa-calendar-day"></i> {{ $todayLabel }}</span>
                    <span class="abm-hero-badge"><i class="fas fa-book"></i> {{ $totalMapel }} mapel terkelola</span>
                    <span class="abm-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $totalKurikulum }} kurikulum</span>
                </div>
            </div>
            <div class="abm-hero-right">
                <div class="abm-hero-clock">
                    <i class="fas fa-clock"></i>
                    <div>
                        <div class="abm-clock-time" id="mplLiveClock">--:--:--</div>
                        <div class="abm-clock-date" id="mplLiveClockDate">{{ $todayLabel }}</div>
                    </div>
                </div>
                <div class="abm-hero-actions">
                    <button type="button" class="abm-btn abm-btn--ghost mpl-ripple" data-bs-toggle="modal" data-bs-target="#modalImport"><i class="fas fa-file-import"></i> Import Excel</button>
                    <button type="button" class="abm-btn abm-btn--ghost mpl-ripple" data-bs-toggle="modal" data-bs-target="#modalExport"><i class="fas fa-file-export"></i> Export</button>
                    <button type="button" class="abm-btn abm-btn--light mpl-ripple" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Mata Pelajaran</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ALERTS ===== --}}
    @if(session('success'))
        <div class="abm-alert abm-alert--info"><i class="fas fa-circle-check"></i><div><strong>Perubahan berhasil diproses</strong><span style="opacity:.9;">{{ session('success') }}</span></div></div>
    @endif
    @if ($errors->any())
        <div class="abm-alert abm-alert--danger"><i class="fas fa-exclamation-triangle"></i><div><strong>Form perlu diperiksa kembali</strong><span style="opacity:.9;">@foreach ($errors->all() as $error){{ $loop->first ? '' : ' • ' }}{{ $error }}@endforeach</span></div></div>
    @endif

    {{-- ===== KPI ===== --}}
    <div class="abm-kpi-grid">
        <div class="abm-kpi mpl-kpi total">
            <i class="fas fa-layer-group abm-kpi-watermark"></i>
            <div class="abm-kpi-icon blue"><i class="fas fa-layer-group"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $totalMapel }}">0</div>
                <div class="abm-kpi-label">Total Mata Pelajaran</div>
                <div class="mpl-kpi-sub"><i class="fas fa-database"></i> terdaftar di sistem</div>
            </div>
        </div>
        <div class="abm-kpi mpl-kpi aktif">
            <i class="fas fa-circle-check abm-kpi-watermark"></i>
            <div class="abm-kpi-icon green"><i class="fas fa-circle-check"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $aktifCount }}">0</div>
                <div class="abm-kpi-label">Mapel Aktif</div>
                <div class="mpl-kpi-sub"><i class="fas fa-bolt"></i> siap dijadwalkan</div>
            </div>
        </div>
        <div class="abm-kpi mpl-kpi nonaktif">
            <i class="fas fa-pause-circle abm-kpi-watermark"></i>
            <div class="abm-kpi-icon amber"><i class="fas fa-pause-circle"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $nonaktifCount }}">0</div>
                <div class="abm-kpi-label">Mapel Nonaktif</div>
                <div class="mpl-kpi-sub"><i class="fas fa-box-archive"></i> diarsipkan</div>
            </div>
        </div>
        <div class="abm-kpi mpl-kpi kurikulum">
            <i class="fas fa-book-open abm-kpi-watermark"></i>
            <div class="abm-kpi-icon violet"><i class="fas fa-book-open"></i></div>
            <div class="abm-kpi-info">
                <div class="abm-kpi-num" data-count="{{ $totalKurikulum }}">0</div>
                <div class="abm-kpi-label">Jumlah Kurikulum</div>
                <div class="mpl-kpi-sub"><i class="fas fa-graduation-cap"></i> terpakai di sekolah</div>
            </div>
        </div>
    </div>

    {{-- ===== INSIGHT ===== --}}
    <div class="mpl-insight">
        <div class="mpl-insight-head">
            <div class="abm-section-title"><i class="fas fa-chart-pie"></i> Insight &amp; Analitik</div>
            <span class="abm-chip abm-chip--muted"><i class="fas fa-database"></i> berdasarkan {{ $visibleCount }} data tampil</span>
        </div>
        <div class="mpl-insight-grid">
            <div class="mpl-insight-col">
                <div class="mpl-insight-card">
                    <div class="mpl-insight-label"><i class="fas fa-circle-check"></i> Mapel Aktif</div>
                    <div class="mpl-insight-big" id="mplPersenAktif">{{ $persenAktif }}<small>%</small></div>
                    <div class="abm-progress"><span data-bar="{{ $persenAktif }}"></span></div>
                    <div class="mpl-insight-stat"><span class="k"><i class="fas fa-book"></i> Kurikulum terbanyak</span><span class="v">{{ $topKurikulum ?? '-' }}</span></div>
                    <div class="mpl-insight-stat"><span class="k"><i class="fas fa-layer-group"></i> Jenjang terbanyak</span><span class="v">{{ $topJenjang ?? '-' }}</span></div>
                </div>
            </div>
            <div class="mpl-insight-col">
                <div class="mpl-insight-card">
                    <div class="mpl-dist-title"><i class="fas fa-layer-group"></i> Sebaran per Jenjang</div>
                    @if($jenjangDist->isEmpty())
                        <div class="mpl-insight-empty">Belum ada data untuk dianalisis.</div>
                    @else
                        <div class="mpl-dist">
                            @foreach($jenjangDist as $name => $count)
                                @php $pct = $jenjangTotal ? (int) round($count / $jenjangTotal * 100) : 0; @endphp
                                <div class="mpl-dist-row">
                                    <span class="n" title="{{ $name }}">{{ $name }}</span>
                                    <div class="abm-progress"><span data-bar="{{ $pct }}"></span></div>
                                    <span class="c">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="mpl-insight-col">
                <div class="mpl-insight-card">
                    <div class="mpl-dist-title"><i class="fas fa-tag"></i> Sebaran per Kelompok</div>
                    @if($kelompokDist->isEmpty())
                        <div class="mpl-insight-empty">Belum ada data untuk dianalisis.</div>
                    @else
                        <div class="mpl-dist">
                            @foreach($kelompokDist as $name => $count)
                                @php $pct = $kelompokTotal ? (int) round($count / $kelompokTotal * 100) : 0; @endphp
                                <div class="mpl-dist-row">
                                    <span class="n" title="{{ $name }}">{{ $name }}</span>
                                    <div class="abm-progress"><span data-bar="{{ $pct }}"></span></div>
                                    <span class="c">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STICKY FILTER TOOLBAR ===== --}}
    <form id="mplFilter" method="GET" autocomplete="off" class="mpl-toolbar" aria-label="Toolbar filter mata pelajaran">
        <div class="mpl-field">
            <label for="mplSearch">Search</label>
            <div class="abm-search">
                <i class="fas fa-search"></i>
                <input type="search" id="mplSearch" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama mata pelajaran..." aria-label="Cari kode atau nama mata pelajaran">
            </div>
        </div>
        <div class="mpl-field">
            <label for="mplJenjang">Filter Jenjang</label>
            <div class="mpl-select-wrap"><i class="fas fa-building"></i>
                <select id="mplJenjang" name="jenjang_id" class="mpl-select" aria-label="Filter jenjang">
                    <option value="">Semua Jenjang</option>
                    @foreach($jenjangs as $j)
                        <option value="{{ $j->id }}" {{ request('jenjang_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenjang }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mpl-field">
            <label for="mplKurikulum">Filter Kurikulum</label>
            <div class="mpl-select-wrap"><i class="fas fa-bookmark"></i>
                <select id="mplKurikulum" name="kurikulum_id" class="mpl-select" aria-label="Filter kurikulum">
                    <option value="">Semua Kurikulum</option>
                    @foreach($kurikulums as $k)
                        <option value="{{ $k->id }}" {{ request('kurikulum_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kurikulum }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mpl-field">
            <label for="mplStatus">Filter Status</label>
            <div class="mpl-select-wrap"><i class="fas fa-toggle-on"></i>
                <select id="mplStatus" name="status" class="mpl-select" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="mpl-field">
            <label for="mplPerPage">Jumlah Data</label>
            <div class="mpl-select-wrap"><i class="fas fa-list-ol"></i>
                <select id="mplPerPage" name="per_page" class="mpl-select" aria-label="Jumlah data per halaman">
                    @foreach ([10, 15, 25, 50, 100] as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }} data</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mpl-field">
            <label>&nbsp;</label>
            <button type="button" class="abm-btn abm-btn--outline" id="mplResetFilter"><i class="fas fa-arrow-rotate-left"></i> Reset Filter</button>
        </div>
    </form>

    {{-- ===== ACTIVE FILTER CHIPS ===== --}}
    @if(count($chips) > 0)
        <div class="mpl-chips" id="mplChips">
            @foreach($chips as $chip)
                <span class="mpl-chip"><i class="fas fa-filter"></i> {{ $chip['label'] }}<a href="{{ $chip['url'] }}" class="mpl-chip-x" aria-label="Hapus filter {{ $chip['label'] }}" title="Hapus filter"><i class="fas fa-xmark"></i></a></span>
            @endforeach
            <a href="{{ route('mata-pelajaran.index') }}" class="mpl-chip-clear"><i class="fas fa-arrow-rotate-left"></i> Hapus Semua</a>
        </div>
    @endif

    {{-- ===== PREMIUM DATA GRID ===== --}}
    <div class="mpl-card">
        <div class="mpl-loadbar" id="mplLoadbar" aria-hidden="true"><span></span></div>
        <div class="mpl-card-head">
            <div>
                <div class="mpl-card-title"><i class="fas fa-layer-group"></i> Daftar Mata Pelajaran</div>
                <div class="mpl-card-sub">Semua mapel ditampilkan dalam premium data grid agar lebih cepat dipindai.</div>
            </div>
            <span class="abm-chip abm-chip--ok"><i class="fas fa-circle-check"></i> {{ $aktifCount }} Aktif</span>
        </div>

        @if($mapel->count() > 0)
            <div class="mpl-table-wrap">
                <table class="mpl-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th><label class="mpl-th-check"><input type="checkbox" class="mpl-check mpl-check-all" aria-label="Pilih semua mapel di halaman ini" title="Pilih semua"></label></th>
                            <th>Nama Mapel</th>
                            <th>Kode</th>
                            <th>Jenjang</th>
                            <th>Kurikulum</th>
                            <th>Kelompok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mapel as $item)
                            @php
                                $jenjangName = $item->jenjang->nama_jenjang ?? '-';
                                $kurikulumName = $item->kurikulum->nama_kurikulum ?? '-';
                                $kelompok = $item->kelompok ?? '-';
                            @endphp
                            <tr class="mpl-fade-row" style="--i: {{ $loop->index }}">
                                <td>{{ ($mapel->currentPage() - 1) * $mapel->perPage() + $loop->iteration }}</td>
                                <td><input type="checkbox" class="mpl-check mpl-row-check" value="{{ $item->id }}" aria-label="Pilih {{ $item->nama_mapel }}"></td>
                                <td>
                                    <div class="mpl-subject">
                                        <span class="mpl-subject-icon"><i class="fas fa-book-open"></i></span>
                                        <div>
                                            <div class="mpl-subject-name mpl-hl">{{ $item->nama_mapel }}</div>
                                            <div class="mpl-subject-code">ID {{ $item->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="abm-chip abm-chip--muted mpl-hl"><i class="fas fa-hashtag"></i> {{ $item->kode_mapel }}</span></td>
                                <td><span class="abm-chip abm-chip--violet mpl-hl"><i class="fas fa-layer-group"></i> {{ $jenjangName }}</span></td>
                                <td><span class="abm-chip abm-chip--blue mpl-hl"><i class="fas fa-bookmark"></i> {{ $kurikulumName }}</span></td>
                                <td><span class="abm-chip abm-chip--muted mpl-hl"><i class="fas fa-tag"></i> {{ $kelompok }}</span></td>
                                <td class="mpl-hl">
                                    @if($item->status == 'Aktif')
                                        <span class="abm-chip abm-chip--ok"><i class="fas fa-circle-check"></i> Aktif</span>
                                    @else
                                        <span class="abm-chip abm-chip--danger"><i class="fas fa-pause-circle"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="mpl-actions">
                                        <button type="button" class="mpl-icon-btn mpl-icon-btn--view" data-bs-toggle="tooltip" title="Coming Soon" aria-label="Lihat detail (Coming Soon)" disabled><i class="fas fa-eye"></i></button>
                                        <button type="button" class="mpl-icon-btn mpl-icon-btn--edit" title="Edit mapel" data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}" aria-label="Edit {{ $item->nama_mapel }}"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="mpl-icon-btn mpl-icon-btn--delete btn-hapus-mpl"
                                            data-bs-toggle="tooltip" title="Hapus mapel"
                                            data-nama="{{ $item->nama_mapel }}"
                                            data-kode="{{ $item->kode_mapel }}"
                                            data-kurikulum="{{ $kurikulumName }}"
                                            data-url="{{ route('mata-pelajaran.destroy', $item->id) }}"
                                            aria-label="Hapus {{ $item->nama_mapel }}"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mpl-mobile-grid">
                @foreach($mapel as $item)
                    @php
                        $jenjangName = $item->jenjang->nama_jenjang ?? '-';
                        $kurikulumName = $item->kurikulum->nama_kurikulum ?? '-';
                        $kelompok = $item->kelompok ?? '-';
                    @endphp
                    <article class="mpl-mobile-card mpl-fade-row" style="--i: {{ $loop->index }}">
                        <div class="mpl-mobile-head">
                            <span class="mpl-subject-icon"><i class="fas fa-book-open"></i></span>
                            <div>
                                <div class="mpl-subject-name mpl-hl">{{ $item->nama_mapel }}</div>
                                <div class="mpl-subject-code mpl-hl">{{ $item->kode_mapel }}</div>
                            </div>
                            <label class="mpl-mobile-check"><input type="checkbox" class="mpl-check mpl-row-check" value="{{ $item->id }}" aria-label="Pilih {{ $item->nama_mapel }}"></label>
                        </div>
                        <div class="mpl-chip-stack">
                            <span class="abm-chip abm-chip--violet mpl-hl"><i class="fas fa-layer-group"></i> {{ $jenjangName }}</span>
                            <span class="abm-chip abm-chip--blue mpl-hl"><i class="fas fa-bookmark"></i> {{ $kurikulumName }}</span>
                            @if($item->status == 'Aktif')
                                <span class="abm-chip abm-chip--ok mpl-hl"><i class="fas fa-circle-check"></i> Aktif</span>
                            @else
                                <span class="abm-chip abm-chip--danger mpl-hl"><i class="fas fa-pause-circle"></i> Nonaktif</span>
                            @endif
                        </div>
                        <div class="mpl-mobile-grid-inner">
                            <div class="mpl-mobile-stat"><div class="k">Kelompok</div><div class="v mpl-hl">{{ $kelompok }}</div></div>
                            <div class="mpl-mobile-stat"><div class="k">ID</div><div class="v">#{{ $item->id }}</div></div>
                        </div>
                        <div class="mpl-mobile-actions">
                            <button type="button" class="mpl-mobile-action mpl-mobile-action--edit" data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}" aria-label="Edit {{ $item->nama_mapel }}"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="mpl-mobile-action mpl-mobile-action--delete btn-hapus-mpl"
                                data-nama="{{ $item->nama_mapel }}"
                                data-kode="{{ $item->kode_mapel }}"
                                data-kurikulum="{{ $kurikulumName }}"
                                data-url="{{ route('mata-pelajaran.destroy', $item->id) }}"
                                aria-label="Hapus {{ $item->nama_mapel }}"><i class="fas fa-trash"></i> Hapus</button>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- ===== BULK ACTION BAR ===== --}}
            <div class="abm-actionbar mpl-bulkbar" id="mplBulkBar">
                <div class="abm-actionbar-count"><i class="fas fa-check-square"></i> <b id="mplBulkCount">0</b> mapel terpilih</div>
                <div class="d-flex gap-2 flex-wrap ms-auto align-items-center">
                    <button type="button" class="abm-btn abm-btn--outline" id="mplBulkClear"><i class="fas fa-xmark"></i> Batal Pilihan</button>
                    <div class="position-relative">
                        <button type="button" class="abm-btn abm-btn--danger" id="mplBulkDelete" disabled><i class="fas fa-trash"></i> Hapus Terpilih</button>
                        <span class="abm-chip abm-chip--warn mpl-coming-badge"><i class="fas fa-hourglass-half"></i> Coming Soon</span>
                    </div>
                </div>
            </div>

            {{-- ===== EDIT MODALS ===== --}}
            @foreach($mapel as $item)
                @php
                    $jenjangName = $item->jenjang->nama_jenjang ?? '-';
                    $kurikulumName = $item->kurikulum->nama_kurikulum ?? '-';
                    $kelompok = $item->kelompok ?? '-';
                    $editNama = old('edit_id') == $item->id ? old('nama_mapel', $item->nama_mapel) : $item->nama_mapel;
                    $editJenjang = old('edit_id') == $item->id ? old('jenjang_id') : $item->jenjang_id;
                    $editKurikulum = old('edit_id') == $item->id ? old('kurikulum_id') : $item->kurikulum_id;
                    $editKelompok = old('edit_id') == $item->id ? old('kelompok') : $item->kelompok;
                    $editStatus = old('edit_id') == $item->id ? old('status') : $item->status;
                @endphp
                <div class="modal fade mpl-modal" id="edit{{ $item->id }}" tabindex="-1" aria-labelledby="editLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <form action="{{ route('mata-pelajaran.update', $item->id) }}" method="POST" class="mpl-edit-form" data-item="{{ $item->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="edit_id" value="{{ $item->id }}">
                                <div class="mpl-modal-hero" style="background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 18px 40px -12px rgba(217,119,6,.4);">
                                    <div class="mpl-modal-hero-top">
                                        <div class="d-flex gap-3">
                                            <span class="mpl-modal-badge"><i class="fas fa-edit"></i></span>
                                            <div>
                                                <h4 class="mpl-modal-title" id="editLabel{{ $item->id }}">Edit Mata Pelajaran</h4>
                                                <p class="mpl-modal-subtitle">Perbarui informasi mapel. Kode bersifat unik dan tidak dapat diubah.</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="mpl-modal-meta">
                                        <div class="mpl-modal-meta-item"><div class="k">Kode</div><div class="v">{{ $item->kode_mapel }}</div></div>
                                        <div class="mpl-modal-meta-item"><div class="k">Jenjang</div><div class="v">{{ $jenjangName }}</div></div>
                                        <div class="mpl-modal-meta-item"><div class="k">Status</div><div class="v">{{ $item->status }}</div></div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mpl-modal-panel">
                                        <h4>Informasi mata pelajaran</h4>
                                        <p>Semua field berikut diproses oleh validasi backend sebagai sumber kebenaran.</p>
                                        <div class="mpl-form-grid">
                                            <div class="mpl-edit-field">
                                                <div class="mpl-edit-label-row">
                                                    <label class="mpl-field-label" for="editNama{{ $item->id }}"><i class="fas fa-book"></i>Nama Mata Pelajaran</label>
                                                    <span class="mpl-edit-chip" hidden><i class="fas fa-pen"></i> Diubah</span>
                                                </div>
                                                <div class="mpl-input-icon-wrap"><i class="fas fa-book-open"></i>
                                                    <input type="text" class="mpl-control" id="editNama{{ $item->id }}" name="nama_mapel" value="{{ $editNama }}" data-orig="{{ $editNama }}" data-prev="epNama{{ $item->id }}" required>
                                                </div>
                                            </div>
                                            <div class="mpl-form-grid-2col">
                                                <div class="mpl-edit-field">
                                                    <div class="mpl-edit-label-row">
                                                        <label class="mpl-field-label" for="editJenjang{{ $item->id }}"><i class="fas fa-layer-group"></i>Jenjang</label>
                                                        <span class="mpl-edit-chip" hidden><i class="fas fa-pen"></i> Diubah</span>
                                                    </div>
                                                    <div class="mpl-input-icon-wrap"><i class="fas fa-building"></i>
                                                        <select class="mpl-select" id="editJenjang{{ $item->id }}" name="jenjang_id" data-orig="{{ $editJenjang ?? '' }}" data-prev="epJenjang{{ $item->id }}" required>
                                                            <option value="">-- Pilih Jenjang --</option>
                                                            @foreach($jenjangs as $jenjang)
                                                                <option value="{{ $jenjang->id }}" {{ $editJenjang == $jenjang->id ? 'selected' : '' }}>{{ $jenjang->nama_jenjang }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mpl-edit-field">
                                                    <div class="mpl-edit-label-row">
                                                        <label class="mpl-field-label" for="editKurikulum{{ $item->id }}"><i class="fas fa-bookmark"></i>Kurikulum</label>
                                                        <span class="mpl-edit-chip" hidden><i class="fas fa-pen"></i> Diubah</span>
                                                    </div>
                                                    <div class="mpl-input-icon-wrap"><i class="fas fa-list-ol"></i>
                                                        <select class="mpl-select" id="editKurikulum{{ $item->id }}" name="kurikulum_id" data-orig="{{ $editKurikulum ?? '' }}" data-prev="epKurikulum{{ $item->id }}">
                                                            @foreach($kurikulums as $kurikulum)
                                                                <option value="{{ $kurikulum->id }}" {{ $editKurikulum == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama_kurikulum }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mpl-edit-field">
                                                    <div class="mpl-edit-label-row">
                                                        <label class="mpl-field-label" for="editKelompok{{ $item->id }}"><i class="fas fa-tag"></i>Kelompok</label>
                                                        <span class="mpl-edit-chip" hidden><i class="fas fa-pen"></i> Diubah</span>
                                                    </div>
                                                    <div class="mpl-input-icon-wrap"><i class="fas fa-object-group"></i>
                                                        <select class="mpl-select" id="editKelompok{{ $item->id }}" name="kelompok" data-orig="{{ $editKelompok ?? '' }}" data-prev="epKelompok{{ $item->id }}" required>
                                                            @foreach (['PAI', 'Umum', 'Muatan Lokal', 'Ekstrakurikuler'] as $opt)
                                                                <option value="{{ $opt }}" {{ $editKelompok == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mpl-edit-field">
                                                    <div class="mpl-edit-label-row">
                                                        <label class="mpl-field-label" for="editStatus{{ $item->id }}"><i class="fas fa-toggle-on"></i>Status</label>
                                                        <span class="mpl-edit-chip" hidden><i class="fas fa-pen"></i> Diubah</span>
                                                    </div>
                                                    <div class="mpl-input-icon-wrap"><i class="fas fa-circle-check"></i>
                                                        <select class="mpl-select" id="editStatus{{ $item->id }}" name="status" data-orig="{{ $editStatus ?? '' }}" data-prev="epStatus{{ $item->id }}" required>
                                                            @foreach (['Aktif', 'Nonaktif'] as $opt)
                                                                <option value="{{ $opt }}" {{ $editStatus == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mpl-edit-preview">
                                        <div class="mpl-edit-preview-head"><i class="fas fa-eye"></i> Pratinjau Perubahan</div>
                                        <div class="mpl-edit-preview-row"><span class="k">Nama</span><span class="v" id="epNama{{ $item->id }}">{{ $item->nama_mapel }}</span></div>
                                        <div class="mpl-edit-preview-row"><span class="k">Jenjang</span><span class="v" id="epJenjang{{ $item->id }}">{{ $jenjangName }}</span></div>
                                        <div class="mpl-edit-preview-row"><span class="k">Kurikulum</span><span class="v" id="epKurikulum{{ $item->id }}">{{ $kurikulumName }}</span></div>
                                        <div class="mpl-edit-preview-row"><span class="k">Kelompok</span><span class="v" id="epKelompok{{ $item->id }}">{{ $kelompok }}</span></div>
                                        <div class="mpl-edit-preview-row"><span class="k">Status</span><span class="v" id="epStatus{{ $item->id }}">{{ $item->status }}</span></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="mpl-modal-footer-note"><i class="fas fa-lock me-1"></i>Kode mapel tidak dapat diubah setelah dibuat.</div>
                                    <div class="d-flex gap-2 flex-wrap ms-auto">
                                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="abm-btn abm-btn--solid"><i class="fas fa-save"></i> Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @if($hasActiveFilter)
                <div class="mpl-empty">
                    <i class="fas fa-magnifying-glass"></i>
                    <div class="mpl-empty-title">Tidak ada hasil yang cocok</div>
                    <div class="mpl-empty-sub">Tidak ditemukan mata pelajaran yang sesuai dengan filter aktif. Coba ubah kata kunci atau reset filter.</div>
                    <div class="mpl-chip-stack" style="justify-content:center;margin-bottom:18px;">
                        @foreach($chips as $chip)
                            <span class="mpl-chip">{{ $chip['label'] }}</span>
                        @endforeach
                    </div>
                    <button type="button" class="abm-btn abm-btn--solid mpl-ripple" onclick="window.location.href='{{ route('mata-pelajaran.index') }}'"><i class="fas fa-arrow-rotate-left"></i> Reset Semua Filter</button>
                </div>
            @else
                <div class="mpl-empty">
                    <i class="fas fa-book-open"></i>
                    <div class="mpl-empty-title">Belum ada mata pelajaran</div>
                    <div class="mpl-empty-sub">Tambahkan mapel baru atau import dari Excel untuk mengisi daftar.</div>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="button" class="abm-btn abm-btn--solid mpl-ripple" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Mata Pelajaran</button>
                        <button type="button" class="abm-btn abm-btn--outline mpl-ripple" data-bs-toggle="modal" data-bs-target="#modalImport"><i class="fas fa-file-import"></i> Import Excel</button>
                    </div>
                </div>
            @endif
        @endif

        @if($mapel->hasPages())
            <div class="mpl-pagination">
                <span><i class="fas fa-list me-1"></i> Menampilkan {{ $mapel->firstItem() }} sampai {{ $mapel->lastItem() }} dari {{ $mapel->total() }} data</span>
                <nav aria-label="Pagination mata pelajaran">{{ $mapel->links('pagination::bootstrap-4') }}</nav>
            </div>
        @endif
    </div>
</div>

{{-- ===== TOAST STACK ===== --}}
<div class="mpl-toast-wrap" id="mplToastStack" aria-live="polite" aria-atomic="true"></div>

{{-- ===== WIZARD: TAMBAH MATA PELAJARAN ===== --}}
<div class="modal fade mpl-modal" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable mpl-wizard-dialog">
        <div class="modal-content">
            <form id="mplWizardForm" action="{{ route('mata-pelajaran.store') }}" method="POST" novalidate>
                @csrf
                <div class="mpl-modal-hero">
                    <div class="mpl-modal-hero-top">
                        <div class="d-flex gap-3">
                            <span class="mpl-modal-badge"><i class="fas fa-book-open"></i></span>
                            <div>
                                <h4 class="mpl-modal-title" id="modalTambahLabel">Tambah Mata Pelajaran</h4>
                                <p class="mpl-modal-subtitle">Lengkapi data mapel melalui 4 langkah panduan.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="mpl-steps" id="mplWizardSteps">
                        <button type="button" class="mpl-step active" data-step="0" aria-current="step">
                            <span class="mpl-step-dot">1</span>
                            <div><div class="mpl-step-t">Identitas</div><div class="mpl-step-d">Nama &amp; jenjang</div></div>
                        </button>
                        <button type="button" class="mpl-step" data-step="1">
                            <span class="mpl-step-dot">2</span>
                            <div><div class="mpl-step-t">Klasifikasi</div><div class="mpl-step-d">Kurikulum &amp; kelompok</div></div>
                        </button>
                        <button type="button" class="mpl-step" data-step="2">
                            <span class="mpl-step-dot">3</span>
                            <div><div class="mpl-step-t">Status</div><div class="mpl-step-d">Aktif / Nonaktif</div></div>
                        </button>
                        <button type="button" class="mpl-step" data-step="3">
                            <span class="mpl-step-dot">4</span>
                            <div><div class="mpl-step-t">Konfirmasi</div><div class="mpl-step-d">Ringkasan data</div></div>
                        </button>
                    </div>
                    <div class="mpl-wizard-track"><span class="mpl-wizard-track-fill" id="mplWizardTrackFill"></span></div>
                </div>

                <div class="mpl-wizard-body">
                    <div class="mpl-pane is-show" data-pane="0">
                        <div class="mpl-pane-head">
                            <span class="ic"><i class="fas fa-book-open"></i></span>
                            <div><h3>Identitas Mata Pelajaran</h3><p>Informasi dasar yang wajib diisi terlebih dahulu.</p></div>
                        </div>
                        <div class="mpl-form-grid">
                            <div>
                                <label class="mpl-field-label" for="mplNama"><i class="fas fa-book"></i>Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                <div class="mpl-input-icon-wrap"><i class="fas fa-book-open"></i>
                                    <input type="text" class="mpl-control" id="mplNama" name="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Contoh: Matematika" required>
                                </div>
                                <div class="mpl-live-note" id="mplNamaErr" aria-live="polite"></div>
                            </div>
                            <div>
                                <label class="mpl-field-label" for="mplJenjang"><i class="fas fa-layer-group"></i>Jenjang <span class="text-danger">*</span></label>
                                <div class="mpl-input-icon-wrap"><i class="fas fa-building"></i>
                                    <select class="mpl-select" id="mplJenjang" name="jenjang_id" required>
                                        <option value="">-- Pilih Jenjang --</option>
                                        @foreach($jenjangs as $jenjang)
                                            <option value="{{ $jenjang->id }}" {{ old('jenjang_id') == $jenjang->id ? 'selected' : '' }}>{{ $jenjang->nama_jenjang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mpl-live-note" id="mplJenjangErr" aria-live="polite"></div>
                            </div>
                            <div class="mpl-code-preview">
                                <div class="mpl-code-preview-top">
                                    <div class="mpl-field-label" style="margin-bottom:0;color:var(--ab-primary-dark);"><i class="fas fa-hashtag"></i>Kode mapel (otomatis)</div>
                                    <div class="mpl-code-preview-value">MAP###</div>
                                </div>
                                <div class="mpl-live-note" style="color:var(--ab-text-3);">Kode dibuat otomatis oleh sistem saat data disimpan.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mpl-pane" data-pane="1">
                        <div class="mpl-pane-head">
                            <span class="ic"><i class="fas fa-tags"></i></span>
                            <div><h3>Klasifikasi Kurikulum</h3><p>Tentukan kurikulum dan kelompok mapel.</p></div>
                        </div>
                        <div class="mpl-form-grid">
                            <div>
                                <label class="mpl-field-label" for="mplKurikulum"><i class="fas fa-bookmark"></i>Kurikulum <span class="text-danger">*</span></label>
                                <div class="mpl-input-icon-wrap"><i class="fas fa-list-ol"></i>
                                    <select class="mpl-select" id="mplKurikulum" name="kurikulum_id" required>
                                        <option value="">-- Pilih Kurikulum --</option>
                                        @foreach($kurikulums as $kurikulum)
                                            <option value="{{ $kurikulum->id }}" {{ old('kurikulum_id') == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama_kurikulum }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mpl-live-note" id="mplKurikulumErr" aria-live="polite"></div>
                            </div>
                            <div>
                                <label class="mpl-field-label"><i class="fas fa-tag"></i>Kelompok <span class="text-danger">*</span></label>
                                <div class="mpl-kelompok-grid">
                                    <label class="mpl-kelompok-card {{ old('kelompok') == 'PAI' ? 'is-selected' : '' }}">
                                        <input type="radio" name="kelompok" value="PAI" class="mpl-kelompok-input" {{ old('kelompok') == 'PAI' ? 'checked' : '' }}>
                                        <span class="mpl-kelompok-icon"><i class="fas fa-star"></i></span>
                                        <strong>PAI</strong>
                                        <small>Pendidikan Agama Islam</small>
                                    </label>
                                    <label class="mpl-kelompok-card {{ old('kelompok') == 'Umum' ? 'is-selected' : '' }}">
                                        <input type="radio" name="kelompok" value="Umum" class="mpl-kelompok-input" {{ old('kelompok') == 'Umum' ? 'checked' : '' }}>
                                        <span class="mpl-kelompok-icon"><i class="fas fa-globe"></i></span>
                                        <strong>Umum</strong>
                                        <small>Mapel umum &amp; dasar</small>
                                    </label>
                                    <label class="mpl-kelompok-card {{ old('kelompok') == 'Muatan Lokal' ? 'is-selected' : '' }}">
                                        <input type="radio" name="kelompok" value="Muatan Lokal" class="mpl-kelompok-input" {{ old('kelompok') == 'Muatan Lokal' ? 'checked' : '' }}>
                                        <span class="mpl-kelompok-icon"><i class="fas fa-map"></i></span>
                                        <strong>Muatan Lokal</strong>
                                        <small>Kearifan lokal</small>
                                    </label>
                                    <label class="mpl-kelompok-card {{ old('kelompok') == 'Ekstrakurikuler' ? 'is-selected' : '' }}">
                                        <input type="radio" name="kelompok" value="Ekstrakurikuler" class="mpl-kelompok-input" {{ old('kelompok') == 'Ekstrakurikuler' ? 'checked' : '' }}>
                                        <span class="mpl-kelompok-icon"><i class="fas fa-flag"></i></span>
                                        <strong>Ekstrakurikuler</strong>
                                        <small>Kegiatan pilihan</small>
                                    </label>
                                </div>
                                <div class="mpl-live-note" id="mplKelompokErr" aria-live="polite"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mpl-pane" data-pane="2">
                        <div class="mpl-pane-head">
                            <span class="ic"><i class="fas fa-toggle-on"></i></span>
                            <div><h3>Status Ketersediaan</h3><p>Atur status mapel agar bisa dijadwalkan atau diarsipkan.</p></div>
                        </div>
                        <div class="mpl-status-grid">
                            <label class="mpl-status-card {{ old('status', 'Aktif') == 'Aktif' ? 'is-selected' : '' }}">
                                <input type="radio" name="status" value="Aktif" class="mpl-status-input" {{ old('status', 'Aktif') == 'Aktif' ? 'checked' : '' }}>
                                <span class="mpl-status-icon ok"><i class="fas fa-circle-check"></i></span>
                                <span class="mpl-status-body"><strong>Aktif</strong><small>Mapel tersedia untuk penjadwalan, guru, dan penilaian.</small></span>
                            </label>
                            <label class="mpl-status-card {{ old('status') == 'Nonaktif' ? 'is-selected' : '' }}">
                                <input type="radio" name="status" value="Nonaktif" class="mpl-status-input" {{ old('status') == 'Nonaktif' ? 'checked' : '' }}>
                                <span class="mpl-status-icon off"><i class="fas fa-pause-circle"></i></span>
                                <span class="mpl-status-body"><strong>Nonaktif</strong><small>Mapel diarsipkan dan tidak digunakan sementara.</small></span>
                            </label>
                        </div>
                        <div class="mpl-live-note" id="mplStatusErr" aria-live="polite"></div>
                    </div>

                    <div class="mpl-pane" data-pane="3">
                        <div class="mpl-pane-head">
                            <span class="ic"><i class="fas fa-clipboard-check"></i></span>
                            <div><h3>Konfirmasi Data</h3><p>Periksa kembali seluruh data sebelum disimpan.</p></div>
                        </div>
                        <div class="mpl-live-card" id="mplLiveCard">
                            <div class="ttl"><i class="fas fa-bolt"></i> Live Preview</div>
                            <div class="mpl-live-row"><span class="k">Nama Mapel</span><span class="v" id="mplLiveNama">-</span></div>
                            <div class="mpl-live-row"><span class="k">Jenjang</span><span class="v" id="mplLiveJenjang">-</span></div>
                            <div class="mpl-live-row"><span class="k">Kurikulum</span><span class="v" id="mplLiveKurikulum">-</span></div>
                        </div>
                        <div class="mpl-summary">
                            <div class="mpl-summary-row"><span class="k"><i class="fas fa-book"></i> Nama</span><span class="v" id="mplSumNama">-</span></div>
                            <div class="mpl-summary-row"><span class="k"><i class="fas fa-hashtag"></i> Kode</span><span class="v" id="mplSumKode">MAP### (otomatis)</span></div>
                            <div class="mpl-summary-row"><span class="k"><i class="fas fa-layer-group"></i> Jenjang</span><span class="v" id="mplSumJenjang">-</span></div>
                            <div class="mpl-summary-row"><span class="k"><i class="fas fa-bookmark"></i> Kurikulum</span><span class="v" id="mplSumKurikulum">-</span></div>
                            <div class="mpl-summary-row"><span class="k"><i class="fas fa-tag"></i> Kelompok</span><span class="v" id="mplSumKelompok">-</span></div>
                            <div class="mpl-summary-row mpl-summary-warn"><span class="k"><i class="fas fa-toggle-on"></i> Status</span><span class="v" id="mplSumStatus">-</span></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="mpl-modal-footer-note" id="mplWizardNote">Langkah 1 dari 4 — Lengkapi identitas mapel.</div>
                    <div class="mpl-wizard-nav">
                        <button type="button" class="abm-btn abm-btn--outline" id="mplWizardBack" style="display:none;"><i class="fas fa-arrow-left"></i> Kembali</button>
                        <div class="mpl-wizard-nav--right">
                            <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="abm-btn abm-btn--solid" id="mplWizardNext"><i class="fas fa-arrow-right"></i> Lanjut</button>
                            <button type="submit" class="abm-btn abm-btn--solid" id="mplWizardSubmit" style="display:none;"><i class="fas fa-check"></i> Simpan Data</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== IMPORT MODAL ===== --}}
<div class="modal fade mpl-modal" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('mata-pelajaran.import') }}" method="POST" enctype="multipart/form-data" id="mplImportForm">
                @csrf
                <div class="mpl-modal-hero">
                    <div class="mpl-modal-hero-top">
                        <div class="d-flex gap-3">
                            <span class="mpl-modal-badge"><i class="fas fa-file-import"></i></span>
                            <div>
                                <h4 class="mpl-modal-title" id="modalImportLabel">Import Mata Pelajaran</h4>
                                <p class="mpl-modal-subtitle">Upload file Excel untuk menambah banyak mapel sekaligus.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="mpl-import-steps">
                        <div class="mpl-import-step is-done"><div class="n">Langkah 1</div><div class="t">Upload File</div></div>
                        <div class="mpl-import-step"><div class="n">Langkah 2</div><div class="t">Validasi</div></div>
                        <div class="mpl-import-step"><div class="n">Langkah 3</div><div class="t">Selesai</div></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mpl-modal-panel">
                        <h4>Pilih file Excel</h4>
                        <p>File berformat .xlsx, .xls, atau .csv sesuai template yang disarankan.</p>
                        <div class="mpl-form-grid">
                            <div>
                                <label class="mpl-dropzone" id="mplDropzone" for="mplImportFile">
                                    <input type="file" class="mpl-dropzone-input" id="mplImportFile" name="file" accept=".xlsx,.xls,.csv" required>
                                    <i class="fas fa-upload"></i>
                                    <div class="mpl-dropzone-title">Tarik &amp; letakkan file di sini</div>
                                    <div class="mpl-dropzone-sub">atau klik untuk memilih file Excel</div>
                                    <span class="mpl-dropzone-hint"><i class="fas fa-file-excel me-1"></i>.xlsx &bull; .xls &bull; .csv</span>
                                </label>
                                <div class="mpl-file-preview" id="mplFilePreview">
                                    <i class="fas fa-file-excel"></i>
                                    <div class="meta">
                                        <strong id="mplFileName">-</strong>
                                        <small id="mplFileMeta">-</small>
                                    </div>
                                    <button type="button" class="abm-btn abm-btn--outline abm-btn--sm" id="mplFileClear"><i class="fas fa-rotate-left"></i> Ganti</button>
                                </div>
                                <div class="abm-alert abm-alert--danger" id="mplImportErr" style="margin-top:14px;display:none;"><i class="fas fa-exclamation-triangle"></i><div><strong>File tidak valid.</strong> Gunakan format .xlsx, .xls, atau .csv.</div></div>
                            </div>
                        </div>
                    </div>
                    <div class="mpl-modal-panel mt-3">
                        <h4><i class="fas fa-info-circle me-1" style="color:var(--ab-primary);"></i>Format kolom Excel</h4>
                        <p><strong>nama_mapel | kurikulum | kelompok | status</strong></p>
                        <p style="margin-top:6px;">Kode mapel dibuat otomatis oleh sistem, dan jenjang diisi manual setelah import.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="mpl-modal-footer-note">Import hanya memproses file Excel sesuai aturan backend.</div>
                    <div class="d-flex gap-2 flex-wrap ms-auto">
                        <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="abm-btn abm-btn--solid"><i class="fas fa-upload"></i> Import Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== EXPORT MODAL ===== --}}
<div class="modal fade mpl-modal" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="mpl-modal-hero" style="background:linear-gradient(135deg,#16a34a,#4ade80);box-shadow:0 18px 40px -12px rgba(22,163,74,.4);">
                <div class="mpl-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="mpl-modal-badge"><i class="fas fa-file-export"></i></span>
                        <div>
                            <h4 class="mpl-modal-title" id="modalExportLabel">Export Mata Pelajaran</h4>
                            <p class="mpl-modal-subtitle">Unduh data mapel dalam format yang Anda butuhkan.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="mpl-export-grid">
                    <a href="{{ route('mata-pelajaran.export') }}" class="mpl-export-card mpl-export--excel">
                        <span class="mpl-export-icon"><i class="fas fa-file-excel"></i></span>
                        <span class="info"><strong>Excel (.xlsx)</strong><small>Download seluruh data mapel.</small></span>
                        <i class="fas fa-download mpl-export-go"></i>
                    </a>
                    <div class="mpl-export-card mpl-export--pdf is-disabled" aria-disabled="true">
                        <span class="mpl-export-icon"><i class="fas fa-file-pdf"></i></span>
                        <span class="info"><strong>PDF</strong><small>Fitur ini segera hadir.</small></span>
                        <span class="abm-chip abm-chip--warn" style="font-size:9.5px;"><i class="fas fa-hourglass-half"></i> Coming Soon</span>
                    </div>
                </div>
                <div class="mpl-hintbox" style="display:flex;align-items:center;gap:12px;background:var(--ab-primary-soft);border:1px solid var(--ab-primary-border);border-radius:12px;padding:11px 14px;font-size:12.5px;color:var(--ab-text-2);margin-top:14px;line-height:1.5;">
                    <i class="fas fa-circle-info" style="color:var(--ab-primary);font-size:15px;flex-shrink:0;"></i>
                    <span>Export men-download <strong>seluruh data mapel</strong> terlepas dari filter yang sedang aktif.</span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="mpl-modal-footer-note"><i class="fas fa-shield me-1"></i>Data yang diunduh berasal dari sistem.</div>
                <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== DELETE CONFIRM MODAL ===== --}}
<div class="modal fade mpl-modal" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="mpl-modal-hero mpl-modal-hero--danger">
                <div class="mpl-modal-hero-top">
                    <div class="d-flex gap-3">
                        <span class="mpl-modal-badge"><i class="fas fa-trash"></i></span>
                        <div>
                            <h4 class="mpl-modal-title">Hapus Mata Pelajaran</h4>
                            <p class="mpl-modal-subtitle">Tindakan ini permanen dan tidak dapat dibatalkan. Periksa kembali data sebelum menghapus.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="mpl-modal-panel">
                    <h4>Data yang akan dihapus</h4>
                    <p>Pastikan mapel yang dipilih sudah benar.</p>
                    <div class="mpl-delete-target">
                        <div class="mpl-delete-box"><div class="k">Nama Mapel</div><div class="v" id="mplHapusNama">-</div></div>
                        <div class="mpl-delete-box"><div class="k">Kode</div><div class="v" id="mplHapusKode">-</div></div>
                        <div class="mpl-delete-box"><div class="k">Kurikulum</div><div class="v" id="mplHapusKurikulum">-</div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="mpl-modal-footer-note"><i class="fas fa-exclamation-triangle me-1"></i>Data yang dihapus tidak dapat dikembalikan.</div>
                <div class="d-flex gap-2 flex-wrap ms-auto">
                    <button type="button" class="abm-btn abm-btn--outline" data-bs-dismiss="modal">Batal</button>
                    <form id="mplFormHapus" method="POST" action="" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="abm-btn abm-btn--danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const hasValidationErrors = {{ $errors->any() ? 'true' : 'false' }};
    const mplSearch = @json(request('search')) || '';

    /* ---------- Toast ---------- */
    function showToast(type, title, text) {
        const stack = document.getElementById('mplToastStack');
        if (!stack) return;
        const toast = document.createElement('div');
        toast.className = 'mpl-toast ' + type;
        toast.innerHTML = '<span class="mpl-toast-icon"><i class="fas ' + (type === 'success' ? 'fa-circle-check' : 'fa-exclamation-triangle') + '"></i></span><div><div class="mpl-toast-title">' + title + '</div><div class="mpl-toast-text">' + text + '</div></div>';
        stack.appendChild(toast);
        requestAnimationFrame(function() { toast.classList.add('is-show'); });
        setTimeout(function() {
            toast.classList.remove('is-show');
            setTimeout(function() { toast.remove(); }, 260);
        }, 3200);
    }

    /* ---------- Tooltips ---------- */
    function initTooltips() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
            if (bootstrap.Tooltip.getInstance(el)) return;
            new bootstrap.Tooltip(el);
        });
    }

    /* ---------- Live clock ---------- */
    function startLiveClock() {
        const clockEl = document.getElementById('mplLiveClock');
        if (!clockEl) return;
        function pad(n) { return String(n).padStart(2, '0'); }
        function tick() {
            const now = new Date();
            clockEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        }
        tick();
        setInterval(tick, 1000);
    }

    /* ---------- KPI counters ---------- */
    function animateCounters() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        document.querySelectorAll('[data-count]').forEach(function(el) {
            const target = parseInt(el.getAttribute('data-count'), 10) || 0;
            if (prefersReduced) { el.textContent = target; return; }
            let current = 0;
            const timer = setInterval(function() {
                current += Math.max(1, Math.ceil(target / 20));
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = current;
            }, 40);
        });
    }

    /* ---------- Insight bars ---------- */
    function animateInsightBars() {
        document.querySelectorAll('[data-bar]').forEach(function(el) {
            const w = parseFloat(el.getAttribute('data-bar'));
            requestAnimationFrame(function() {
                requestAnimationFrame(function() { el.style.width = w + '%'; });
            });
        });
    }

    /* ---------- Search highlight ---------- */
    function highlightMatches(container, q) {
        if (!q) return;
        let query = q;
        try { query = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); } catch (e) { return; }
        const regex = new RegExp(query, 'gi');
        container.querySelectorAll('.mpl-hl').forEach(function(cell) {
            const walker = document.createTreeWalker(cell, NodeFilter.SHOW_TEXT, {
                acceptNode: function(n) { return n.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT; }
            });
            const nodes = [];
            while (walker.nextNode()) nodes.push(walker.currentNode);
            nodes.forEach(function(node) {
                let m;
                let last = 0;
                const frag = document.createDocumentFragment();
                regex.lastIndex = 0;
                while ((m = regex.exec(node.nodeValue)) !== null) {
                    if (m.index > last) frag.appendChild(document.createTextNode(node.nodeValue.slice(last, m.index)));
                    const mark = document.createElement('mark');
                    mark.textContent = m[0];
                    frag.appendChild(mark);
                    last = m.index + m[0].length;
                    if (m[0].length === 0) regex.lastIndex++;
                }
                if (last < node.nodeValue.length) frag.appendChild(document.createTextNode(node.nodeValue.slice(last)));
                node.parentNode.replaceChild(frag, node);
            });
        });
    }

    /* ---------- Bulk selection ---------- */
    function initBulk() {
        const bulkBar = document.getElementById('mplBulkBar');
        if (!bulkBar) return;
        const bulkCount = document.getElementById('mplBulkCount');
        const selectedIds = new Set();
        function renderBulk() {
            bulkCount.textContent = selectedIds.size;
            bulkBar.classList.toggle('is-show', selectedIds.size > 0);
        }
        document.querySelectorAll('.mpl-row-check').forEach(function(cb) {
            cb.addEventListener('change', function() {
                if (cb.checked) selectedIds.add(cb.value); else selectedIds.delete(cb.value);
                renderBulk();
            });
        });
        const allCheck = document.querySelector('.mpl-check-all');
        if (allCheck) {
            allCheck.addEventListener('change', function() {
                const checked = allCheck.checked;
                document.querySelectorAll('.mpl-row-check').forEach(function(cb) {
                    cb.checked = checked;
                    if (checked) selectedIds.add(cb.value); else selectedIds.delete(cb.value);
                });
                renderBulk();
            });
        }
        document.getElementById('mplBulkClear').addEventListener('click', function() {
            selectedIds.clear();
            document.querySelectorAll('.mpl-row-check, .mpl-check-all').forEach(function(cb) { cb.checked = false; });
            renderBulk();
        });
    }

    /* ---------- Filter toolbar (auto-submit + debounce) ---------- */
    const filterForm = document.getElementById('mplFilter');
    if (filterForm) {
        let debounce;
        filterForm.querySelectorAll('select').forEach(function(el) {
            el.addEventListener('change', function() { filterForm.submit(); });
        });
        filterForm.querySelectorAll('input[type="search"], input[type="text"]').forEach(function(el) {
            el.addEventListener('input', function() {
                clearTimeout(debounce);
                debounce = setTimeout(function() { filterForm.submit(); }, 350);
            });
        });
        const resetBtn = document.getElementById('mplResetFilter');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                window.location.href = '{{ route('mata-pelajaran.index') }}';
            });
        }
    }

    /* ---------- Submit spinner ---------- */
    function bindSubmitSpinner(formEl) {
        if (!formEl) return;
        formEl.addEventListener('submit', function() {
            const btn = formEl.querySelector('[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            btn.innerHTML = '<span class="mpl-spinner"></span> Menyimpan...';
        });
    }

    /* ---------- Delete confirmation modal ---------- */
    $(document).on('click', '.btn-hapus-mpl', function() {
        const $t = $(this);
        $('#mplHapusNama').text($t.data('nama'));
        $('#mplHapusKode').text($t.data('kode'));
        $('#mplHapusKurikulum').text($t.data('kurikulum'));
        $('#mplFormHapus').attr('action', $t.data('url'));
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });

    /* ---------- Ripple ---------- */
    $(document).on('click', '.mpl-ripple', function(e) {
        const btn = this;
        const rect = btn.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const span = document.createElement('span');
        span.className = 'mpl-ripple-span';
        span.style.width = span.style.height = size + 'px';
        span.style.left = (e.clientX - rect.left - size / 2) + 'px';
        span.style.top = (e.clientY - rect.top - size / 2) + 'px';
        btn.appendChild(span);
        setTimeout(function() { span.remove(); }, 600);
    });

    /* ---------- Add wizard ---------- */
    const wizardForm = document.getElementById('mplWizardForm');
    if (wizardForm) {
        let currentStep = 0;
        const totalSteps = 4;
        const panes = document.querySelectorAll('#modalTambah .mpl-pane');
        const steps = document.querySelectorAll('#modalTambah .mpl-step');
        const trackFill = document.getElementById('mplWizardTrackFill');
        const backBtn = document.getElementById('mplWizardBack');
        const nextBtn = document.getElementById('mplWizardNext');
        const submitBtn = document.getElementById('mplWizardSubmit');
        const note = document.getElementById('mplWizardNote');

        function clearErr(el) {
            el.classList.remove('is-invalid');
            const err = document.getElementById(el.id + 'Err');
            if (err) err.textContent = '';
        }

        function setErr(el, msg) {
            el.classList.add('is-invalid');
            const err = document.getElementById(el.id + 'Err');
            if (err) err.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + msg;
        }

        function validateStep(step) {
            let ok = true;
            if (step === 0) {
                const nama = document.getElementById('mplNama');
                const jenjang = document.getElementById('mplJenjang');
                if (!nama.value.trim()) { setErr(nama, 'Nama mata pelajaran wajib diisi.'); ok = false; } else clearErr(nama);
                if (!jenjang.value) { setErr(jenjang, 'Jenjang wajib dipilih.'); ok = false; } else clearErr(jenjang);
            } else if (step === 1) {
                const kurikulum = document.getElementById('mplKurikulum');
                const kelompokErr = document.getElementById('mplKelompokErr');
                if (!kurikulum.value) { setErr(kurikulum, 'Kurikulum wajib dipilih.'); ok = false; } else clearErr(kurikulum);
                if (!document.querySelector('#modalTambah input[name="kelompok"]:checked')) {
                    if (kelompokErr) kelompokErr.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Pilih kelompok mapel terlebih dahulu.';
                    ok = false;
                } else if (kelompokErr) {
                    kelompokErr.textContent = '';
                }
            } else if (step === 2) {
                const statusErr = document.getElementById('mplStatusErr');
                if (!document.querySelector('#modalTambah input[name="status"]:checked')) {
                    if (statusErr) statusErr.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Pilih status mapel terlebih dahulu.';
                    ok = false;
                } else if (statusErr) {
                    statusErr.textContent = '';
                }
            }
            return ok;
        }

        function optText(sel) {
            return sel.selectedOptions && sel.selectedOptions[0] ? sel.selectedOptions[0].text : '';
        }

        function syncLivePreview() {
            const nama = document.getElementById('mplNama').value.trim();
            const liveCard = document.getElementById('mplLiveCard');
            document.getElementById('mplLiveNama').textContent = nama || '-';
            document.getElementById('mplLiveJenjang').textContent = optText(document.getElementById('mplJenjang')) || '-';
            document.getElementById('mplLiveKurikulum').textContent = optText(document.getElementById('mplKurikulum')) || '-';
            if (liveCard) liveCard.classList.toggle('on', !!nama);
        }

        function syncCreateSummary() {
            document.getElementById('mplSumNama').textContent = document.getElementById('mplNama').value.trim() || '-';
            document.getElementById('mplSumKode').textContent = 'MAP### (otomatis)';
            document.getElementById('mplSumJenjang').textContent = optText(document.getElementById('mplJenjang')) || '-';
            document.getElementById('mplSumKurikulum').textContent = optText(document.getElementById('mplKurikulum')) || '-';
            const kelompok = document.querySelector('#modalTambah input[name="kelompok"]:checked');
            document.getElementById('mplSumKelompok').textContent = kelompok ? kelompok.value : '-';
            const st = document.querySelector('#modalTambah input[name="status"]:checked');
            document.getElementById('mplSumStatus').textContent = st ? st.value : '-';
        }

        function renderStep() {
            panes.forEach(function(p, i) { p.classList.toggle('is-show', i === currentStep); });
            steps.forEach(function(s, i) {
                s.classList.toggle('active', i === currentStep);
                s.classList.toggle('done', i < currentStep);
                if (i === currentStep) s.setAttribute('aria-current', 'step'); else s.removeAttribute('aria-current');
            });
            trackFill.style.width = (currentStep / (totalSteps - 1)) * 100 + '%';
            backBtn.style.display = currentStep === 0 ? 'none' : 'inline-flex';
            const isLast = currentStep === totalSteps - 1;
            nextBtn.style.display = isLast ? 'none' : 'inline-flex';
            submitBtn.style.display = isLast ? 'inline-flex' : 'none';
            const labels = [
                'Langkah 1 dari 4 — Lengkapi identitas mapel.',
                'Langkah 2 dari 4 — Tentukan kurikulum & kelompok.',
                'Langkah 3 dari 4 — Atur status ketersediaan.',
                'Langkah 4 dari 4 — Periksa ringkasan lalu simpan.'
            ];
            note.textContent = labels[currentStep];
            if (isLast) syncCreateSummary();
        }

        nextBtn.addEventListener('click', function() {
            if (!validateStep(currentStep)) return;
            currentStep = Math.min(currentStep + 1, totalSteps - 1);
            renderStep();
        });

        backBtn.addEventListener('click', function() {
            currentStep = Math.max(currentStep - 1, 0);
            renderStep();
        });

        steps.forEach(function(s, i) {
            s.addEventListener('click', function() {
                if (i < currentStep) { currentStep = i; renderStep(); }
                else if (i === currentStep + 1 && validateStep(currentStep)) { currentStep = i; renderStep(); }
            });
        });

        function bindRadioCards(scope) {
            const cards = scope.querySelectorAll('.mpl-status-card');
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    cards.forEach(function(c) { c.classList.remove('is-selected'); });
                    card.classList.add('is-selected');
                    card.querySelector('input').checked = true;
                    const statusErr = document.getElementById('mplStatusErr');
                    if (statusErr) statusErr.textContent = '';
                });
            });
            const kCards = scope.querySelectorAll('.mpl-kelompok-card');
            kCards.forEach(function(card) {
                card.addEventListener('click', function() {
                    kCards.forEach(function(c) { c.classList.remove('is-selected'); });
                    card.classList.add('is-selected');
                    card.querySelector('input').checked = true;
                    const kelompokErr = document.getElementById('mplKelompokErr');
                    if (kelompokErr) kelompokErr.textContent = '';
                });
            });
        }
        bindRadioCards(document.getElementById('modalTambah'));

        ['mplNama', 'mplJenjang', 'mplKurikulum'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', syncLivePreview);
                el.addEventListener('change', syncLivePreview);
            }
        });

        document.getElementById('modalTambah').addEventListener('shown.bs.modal', function() {
            if (!hasValidationErrors) {
                wizardForm.reset();
                document.querySelectorAll('#modalTambah .mpl-control, #modalTambah .mpl-select').forEach(function(el) { el.classList.remove('is-invalid'); });
                document.querySelectorAll('#modalTambah .mpl-status-card, #modalTambah .mpl-kelompok-card').forEach(function(c) { c.classList.remove('is-selected'); });
                const firstRadio = document.querySelector('input[name="status"][value="Aktif"]');
                if (firstRadio) { firstRadio.checked = true; firstRadio.closest('.mpl-status-card').classList.add('is-selected'); }
            }
            currentStep = 0;
            renderStep();
            syncLivePreview();
        });

        bindSubmitSpinner(wizardForm);
    }

    /* ---------- Edit modals: live preview + change highlight ---------- */
    function bindEditForms() {
        document.querySelectorAll('.mpl-edit-form').forEach(function(form) {
            function refresh() {
                form.querySelectorAll('[data-prev]').forEach(function(ctl) {
                    const row = form.querySelector('#' + ctl.getAttribute('data-prev'));
                    if (!row) return;
                    const isSel = ctl.tagName === 'SELECT';
                    const val = isSel ? (ctl.selectedOptions && ctl.selectedOptions[0] ? ctl.selectedOptions[0].text : '') : ctl.value;
                    row.textContent = val || '-';
                    const changed = ctl.value !== ctl.getAttribute('data-orig');
                    const field = ctl.closest('.mpl-edit-field');
                    if (field) {
                        field.classList.toggle('is-changed', changed);
                        const chip = field.querySelector('.mpl-edit-chip');
                        if (chip) chip.hidden = !changed;
                    }
                    row.parentElement.classList.toggle('is-changed', changed);
                });
            }
            form.querySelectorAll('[data-prev]').forEach(function(ctl) {
                ctl.addEventListener('change', refresh);
                if (ctl.tagName === 'INPUT') ctl.addEventListener('input', refresh);
            });
            form.addEventListener('shown.bs.modal', refresh);
            bindSubmitSpinner(form);
        });
    }
    bindEditForms();

    /* ---------- Import dropzone ---------- */
    function bindDropzone() {
        const dropzone = document.getElementById('mplDropzone');
        const fileInput = document.getElementById('mplImportFile');
        if (!dropzone || !fileInput) return;
        const preview = document.getElementById('mplFilePreview');
        const errBox = document.getElementById('mplImportErr');
        const allowed = ['xlsx', 'xls', 'csv'];

        function extOf(name) { return (name.split('.').pop() || '').toLowerCase(); }

        function showFile(file) {
            if (!file) return;
            if (allowed.indexOf(extOf(file.name)) === -1) {
                errBox.style.display = 'flex';
                fileInput.value = '';
                preview.classList.remove('is-show');
                dropzone.classList.remove('has-file');
                return;
            }
            errBox.style.display = 'none';
            document.getElementById('mplFileName').textContent = file.name;
            const sizeKb = (file.size / 1024).toFixed(1);
            const typeLabel = (file.type && file.type.indexOf('officedocument') !== -1) ? 'Excel' : (file.type || 'Excel');
            document.getElementById('mplFileMeta').textContent = sizeKb + ' KB' + (typeLabel ? ' • ' + typeLabel : '');
            preview.classList.add('is-show');
            dropzone.classList.add('has-file');
        }

        fileInput.addEventListener('change', function() {
            if (fileInput.files && fileInput.files.length) showFile(fileInput.files[0]);
        });

        ['dragenter', 'dragover'].forEach(function(ev) {
            dropzone.addEventListener(ev, function(e) { e.preventDefault(); e.stopPropagation(); dropzone.classList.add('drag'); });
        });
        ['dragleave', 'drop'].forEach(function(ev) {
            dropzone.addEventListener(ev, function(e) { e.preventDefault(); e.stopPropagation(); dropzone.classList.remove('drag'); });
        });
        dropzone.addEventListener('drop', function(e) {
            const files = e.dataTransfer && e.dataTransfer.files;
            if (files && files.length) {
                fileInput.files = files;
                showFile(files[0]);
            }
        });

        const clearBtn = document.getElementById('mplFileClear');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                fileInput.value = '';
                preview.classList.remove('is-show');
                dropzone.classList.remove('has-file');
                errBox.style.display = 'none';
            });
        }

        document.getElementById('mplImportForm').addEventListener('submit', function(e) {
            if (!fileInput.files || !fileInput.files.length) {
                e.preventDefault();
                errBox.style.display = 'flex';
                return;
            }
            if (allowed.indexOf(extOf(fileInput.files[0].name)) === -1) {
                e.preventDefault();
                errBox.style.display = 'flex';
                return;
            }
        });
        bindSubmitSpinner(document.getElementById('mplImportForm'));
    }
    bindDropzone();

    /* ---------- Delete form spinner ---------- */
    bindSubmitSpinner(document.getElementById('mplFormHapus'));

    /* ---------- Server flash + auto-open modal on validation error ---------- */
    @if(session('success'))
        showToast('success', 'Berhasil', @json(session('success')));
    @endif
    @if ($errors->any())
        showToast('error', 'Form perlu diperiksa', @json(implode(' • ', $errors->all())));
    @endif

    const editId = '{{ old('edit_id') }}';
    if (editId && document.getElementById('edit' + editId)) {
        new bootstrap.Modal(document.getElementById('edit' + editId)).show();
    } else if (hasValidationErrors) {
        new bootstrap.Modal(document.getElementById('modalTambah')).show();
    }

    /* ---------- Boot ---------- */
    initTooltips();
    startLiveClock();
    animateCounters();
    animateInsightBars();
    initBulk();
    highlightMatches(document, mplSearch);

    const loadbar = document.getElementById('mplLoadbar');
    if (loadbar) {
        setTimeout(function() { loadbar.classList.add('is-done'); }, 400);
    }
});
</script>
@endpush
