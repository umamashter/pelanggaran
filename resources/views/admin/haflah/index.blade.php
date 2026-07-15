@extends('layouts.main')
@section('title', 'Haflatul Imtihan')
@section('content')

@include('component.admin.ms-style')

<style>
:root {
    --hi-primary: #7c3aed;
    --hi-primary-light: #ede9fe;
    --hi-primary-dark: #5b21b6;
    --hi-orange: #f59e0b;
    --hi-orange-light: #fffbeb;
    --hi-green: #10b981;
    --hi-green-light: #d1fae5;
    --hi-gray: #94a3b8;
    --hi-gray-light: #f1f5f9;
    --hi-blue: #3b82f6;
    --hi-blue-light: #eff6ff;
    --hi-red: #ef4444;
    --hi-red-light: #fef2f2;
}

/* ── DataTable gaya Peserta Lomba ── */
.dt-toolbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 14px; }
.dt-left, .dt-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

.dt-toolbar .dataTables_length label {
    display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#64748b; white-space:nowrap;
}
.dt-toolbar .dataTables_length select {
    height:34px; border-radius:18px; border:1.5px solid #e2e8f0;
    font-size:12px; padding:0 30px 0 14px; background-color:#f8fafc;
    color:#475569; min-width:60px; cursor:pointer; transition:all .25s;
    appearance:auto;
}
.dt-toolbar .dataTables_length select:focus {
    border-color:#7c3aed; box-shadow:0 0 0 3px rgba(124,58,237,.1); background-color:#fff;
}
.dt-toolbar .dataTables_filter label {
    display:inline-flex; align-items:center; position:relative;
}
.dt-toolbar .dataTables_filter input {
    height:34px; border:1.5px solid #e2e8f0; border-radius:18px;
    font-size:12px; padding:0 16px 0 34px; background-color:#f8fafc;
    color:#475569; min-width:240px; transition:all .25s; outline:none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:12px center; background-size:14px;
}
.dt-toolbar .dataTables_filter input:focus {
    border-color:#7c3aed; box-shadow:0 0 0 3px rgba(124,58,237,.1); background-color:#fff;
}
.dt-toolbar .dataTables_filter input::placeholder { color:#94a3b8; }

.dataTables_wrapper .dataTables_paginate { display:flex; justify-content:flex-end; padding-top:14px; }
.dataTables_wrapper .dataTables_paginate .pagination { margin:0; gap:4px; }
.dataTables_wrapper .dataTables_paginate .paginate_button.page-item .page-link {
    min-width:34px; height:34px; padding:0 10px; border-radius:8px;
    font-size:13px; font-weight:500; line-height:32px;
    color:#475569; background:#fff; border:1px solid var(--ms-border); box-shadow:none;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.page-item .page-link:hover {
    border-color:var(--ms-primary); color:var(--ms-primary); background:var(--ms-primary-light);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.page-item.active .page-link {
    background:var(--ms-primary); border-color:var(--ms-primary); color:#fff; box-shadow:0 2px 6px rgba(124,58,237,.25);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.page-item.disabled .page-link {
    opacity:.4; background:#f8fafc;
}

.btn-simpan-ms.btn-compact {
    height:34px; padding:0 14px; font-size:12px; border-radius:8px;
}

/* ══════════════════════════════════════════════════════
   DARK MODE — table.dataTable display stripe fix
   CDN 1.12.1 pakai box-shadow inset buat stripe,
   bukan background. Dark-mode.css cuma override
   background, tapi box-shadow-nya tetap jalan.
   Override total di sini biar bersih.
   ══════════════════════════════════════════════════════ */
html.dark-mode #table_haflah,
html.dark-mode #table_haflah.dataTable,
html.dark-mode #table_haflah.table-ms {
    background: transparent !important;
}
html.dark-mode #table_haflah tbody,
html.dark-mode #table_haflah tbody tr,
html.dark-mode #table_haflah tbody td {
    background: transparent !important;
    box-shadow: none !important;
}
html.dark-mode #table_haflah.display > tbody > tr.odd > *,
html.dark-mode #table_haflah.display > tbody > tr.even > *,
html.dark-mode #table_haflah.display > tbody > tr:hover > *,
html.dark-mode #table_haflah.display > tbody > tr > .sorting_1,
html.dark-mode #table_haflah.display > tbody > tr > .sorting_2,
html.dark-mode #table_haflah.display > tbody > tr > .sorting_3 {
    box-shadow: none !important;
    background: transparent !important;
}
/* Striping halus via pseudo — ambil dari card di belakang */
html.dark-mode #table_haflah tbody tr:nth-child(even) td {
    background: rgba(255,255,255,0.04) !important;
}
html.dark-mode #table_haflah tbody tr:hover td {
    background: rgba(0,229,255,0.10) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.page-item .page-link {
    min-width:34px; height:34px; padding:0 10px; border-radius:8px;
    font-size:13px; font-weight:500; line-height:32px;
    color:#475569; background:#fff; border:1px solid var(--ms-border); box-shadow:none;
}
html.dark-mode #table_haflah_wrapper .dataTables_paginate .paginate_button {
    background: transparent !important;
    box-shadow: none !important;
}
html.dark-mode #table_haflah_wrapper .dataTables_paginate .paginate_button.current {
    background: #7c3aed !important;
    border-color: #7c3aed !important;
    color: #fff !important;
}

@media (max-width:768px) {
    .dt-toolbar { justify-content:flex-start; }
    .dt-toolbar .dataTables_filter input { min-width:160px; flex:1 1 100%; }
}

/* ── Glass Summary Cards ── */
.summary-haflah {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.summary-card {
    position: relative;
    border-radius: 20px;
    padding: 22px 24px;
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
    gap: 18px;
    overflow: hidden;
}

.summary-card::before {
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

.summary-card::after {
    content: '';
    position: absolute;
    top: -60%;
    right: -20%;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,58,237,0.06) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 1px 2px rgba(0,0,0,0.03),
        0 8px 24px rgba(0,0,0,0.06),
        0 20px 48px -12px rgba(0,0,0,0.10),
        inset 0 1px 0 rgba(255,255,255,0.90);
    border-color: rgba(255,255,255,0.85);
}

/* ── Card left section: icon + chart ── */
.sc-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    z-index: 1;
}

/* ── 3D SVG Icon ── */
.sc-icon-3d {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    background: linear-gradient(145deg, rgba(255,255,255,0.90), rgba(255,255,255,0.40));
    box-shadow:
        0 2px 8px rgba(0,0,0,0.06),
        0 8px 24px -4px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.95),
        inset 0 -1px 0 rgba(0,0,0,0.04);
}

.sc-icon-3d svg {
    width: 26px;
    height: 26px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));
}

/* ── Mini chart bars ── */
.sc-chart {
    display: flex;
    align-items: flex-end;
    gap: 3px;
    height: 24px;
    z-index: 1;
}

.sc-chart .bar {
    width: 5px;
    border-radius: 3px 3px 1px 1px;
    transition: height .6s cubic-bezier(.2,.8,.2,1);
    position: relative;
}

.sc-chart .bar::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: inherit;
    filter: blur(4px);
    opacity: 0.6;
    transform: scaleY(1.4) scaleX(1.6);
    z-index: -1;
}

/* ── Card body ── */
.sc-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    z-index: 1;
}

.sc-body .sc-number {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -.5px;
}

.sc-body .sc-label {
    font-size: 12.5px;
    color: #64748b;
    font-weight: 500;
    margin-top: 4px;
    letter-spacing: .2px;
}

/* ── Per-card accent ── */
.summary-card:nth-child(1) .sc-icon-3d {
    background: linear-gradient(145deg, rgba(124,58,237,0.15), rgba(124,58,237,0.05));
    box-shadow:
        0 2px 8px rgba(124,58,237,0.10),
        0 8px 24px -4px rgba(124,58,237,0.08),
        inset 0 1px 0 rgba(255,255,255,0.95);
}
.summary-card:nth-child(1) .sc-icon-3d svg { color: #7c3aed; }
.summary-card:nth-child(1) .bar { background: linear-gradient(to top, #7c3aed, #a78bfa); }
.summary-card:nth-child(1)::after { background: radial-gradient(circle, rgba(124,58,237,0.08) 0%, transparent 70%); }

.summary-card:nth-child(2) .sc-icon-3d {
    background: linear-gradient(145deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05));
    box-shadow:
        0 2px 8px rgba(245,158,11,0.10),
        0 8px 24px -4px rgba(245,158,11,0.08),
        inset 0 1px 0 rgba(255,255,255,0.95);
}
.summary-card:nth-child(2) .sc-icon-3d svg { color: #f59e0b; }
.summary-card:nth-child(2) .bar { background: linear-gradient(to top, #f59e0b, #fcd34d); }
.summary-card:nth-child(2)::after { background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%); }

.summary-card:nth-child(3) .sc-icon-3d {
    background: linear-gradient(145deg, rgba(59,130,246,0.15), rgba(59,130,246,0.05));
    box-shadow:
        0 2px 8px rgba(59,130,246,0.10),
        0 8px 24px -4px rgba(59,130,246,0.08),
        inset 0 1px 0 rgba(255,255,255,0.95);
}
.summary-card:nth-child(3) .sc-icon-3d svg { color: #3b82f6; }
.summary-card:nth-child(3) .bar { background: linear-gradient(to top, #3b82f6, #93c5fd); }
.summary-card:nth-child(3)::after { background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%); }

.summary-card:nth-child(4) .sc-icon-3d {
    background: linear-gradient(145deg, rgba(16,185,129,0.15), rgba(16,185,129,0.05));
    box-shadow:
        0 2px 8px rgba(16,185,129,0.10),
        0 8px 24px -4px rgba(16,185,129,0.08),
        inset 0 1px 0 rgba(255,255,255,0.95);
}
.summary-card:nth-child(4) .sc-icon-3d svg { color: #10b981; }
.summary-card:nth-child(4) .bar { background: linear-gradient(to top, #10b981, #6ee7b7); }
.summary-card:nth-child(4)::after { background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%); }

/* ── Dark mode ── */
html.dark-mode .summary-card {
    background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 100%);
    border-color: rgba(255,255,255,0.10);
    box-shadow:
        0 1px 0 rgba(255,255,255,0.04) inset,
        0 4px 16px rgba(0,0,0,0.20),
        0 12px 32px -8px rgba(0,0,0,0.30),
        inset 0 1px 0 rgba(255,255,255,0.06);
}
html.dark-mode .summary-card::before {
    background: linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03) 40%, transparent 70%);
}
html.dark-mode .summary-card::after {
    opacity: 0.5;
}
html.dark-mode .summary-card:hover {
    border-color: rgba(255,255,255,0.18);
    box-shadow:
        0 1px 0 rgba(255,255,255,0.06) inset,
        0 8px 24px rgba(0,0,0,0.25),
        0 24px 56px -12px rgba(0,0,0,0.35),
        inset 0 1px 0 rgba(255,255,255,0.08);
}
html.dark-mode .sc-icon-3d {
    background: linear-gradient(145deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)) !important;
    box-shadow:
        0 2px 8px rgba(0,0,0,0.20),
        0 8px 24px -4px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.10) !important;
}
html.dark-mode .sc-icon-3d svg { filter: drop-shadow(0 2px 6px rgba(0,0,0,0.30)); }
html.dark-mode .sc-body .sc-number { color: #f1f5f9; }
html.dark-mode .sc-body .sc-label { color: #94a3b8; }

@media (max-width: 900px) {
    .summary-haflah { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .summary-haflah { grid-template-columns: 1fr; }
}

/* ── Active Haflah Banner (Glass) ── */
.active-banner {
    position: relative;
    border-radius: 20px;
    padding: 22px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 28px;
    flex-wrap: wrap;
    overflow: hidden;
    background: linear-gradient(145deg, rgba(124,58,237,0.12) 0%, rgba(124,58,237,0.04) 100%);
    border: 1px solid rgba(124,58,237,0.20);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    box-shadow:
        0 1px 2px rgba(0,0,0,0.03),
        0 4px 12px rgba(0,0,0,0.04),
        0 12px 32px -8px rgba(124,58,237,0.15),
        inset 0 1px 0 rgba(255,255,255,0.60);
    transition: all .4s cubic-bezier(.2,.8,.2,1);
}

.active-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 20px;
    padding: 1px;
    background: linear-gradient(160deg, rgba(255,255,255,0.90), rgba(124,58,237,0.15) 40%, transparent 70%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
}

.active-banner::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,58,237,0.10) 0%, transparent 70%);
    pointer-events: none;
}

.active-banner:hover {
    border-color: rgba(124,58,237,0.30);
    box-shadow:
        0 1px 2px rgba(0,0,0,0.03),
        0 8px 20px rgba(0,0,0,0.05),
        0 20px 48px -12px rgba(124,58,237,0.20),
        inset 0 1px 0 rgba(255,255,255,0.70);
    transform: translateY(-2px);
}

.active-banner .ab-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
}

.active-banner .ab-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: linear-gradient(145deg, rgba(124,58,237,0.20), rgba(124,58,237,0.08));
    box-shadow:
        0 2px 8px rgba(124,58,237,0.12),
        0 8px 24px -4px rgba(124,58,237,0.08),
        inset 0 1px 0 rgba(255,255,255,0.90);
}

.active-banner .ab-icon svg {
    width: 24px;
    height: 24px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
}

.active-banner .ab-text {
    display: flex;
    flex-direction: column;
}

.active-banner .ab-text .ab-title {
    font-size: 12.5px;
    font-weight: 600;
    color: #7c3aed;
    letter-spacing: .3px;
    text-transform: uppercase;
}

.active-banner .ab-text .ab-name {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-top: 2px;
}

.active-banner .ab-right {
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 1;
}

.active-banner .ab-right a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    color: #7c3aed;
    background: linear-gradient(145deg, rgba(124,58,237,0.12), rgba(124,58,237,0.04));
    border: 1px solid rgba(124,58,237,0.20);
    backdrop-filter: blur(8px) saturate(160%);
    -webkit-backdrop-filter: blur(8px) saturate(160%);
    box-shadow:
        0 1px 2px rgba(0,0,0,0.02),
        0 4px 12px rgba(124,58,237,0.08),
        inset 0 1px 0 rgba(255,255,255,0.70);
    transition: all .3s cubic-bezier(.2,.8,.2,1);
    position: relative;
    overflow: hidden;
}

.active-banner .ab-right a::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 12px;
    padding: 1px;
    background: linear-gradient(160deg, rgba(255,255,255,0.80), rgba(124,58,237,0.10) 50%, transparent 70%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
}

.active-banner .ab-right a:hover {
    transform: translateY(-2px);
    background: linear-gradient(145deg, rgba(124,58,237,0.18), rgba(124,58,237,0.08));
    border-color: rgba(124,58,237,0.35);
    box-shadow:
        0 2px 8px rgba(124,58,237,0.12),
        0 12px 28px -8px rgba(124,58,237,0.15),
        inset 0 1px 0 rgba(255,255,255,0.80);
}

.active-banner .ab-right a:active {
    transform: translateY(0);
}

html.dark-mode .active-banner .ab-right a {
    color: #a78bfa;
    background: linear-gradient(145deg, rgba(124,58,237,0.15), rgba(124,58,237,0.05));
    border-color: rgba(124,58,237,0.20);
    box-shadow:
        0 1px 0 rgba(255,255,255,0.04) inset,
        0 4px 12px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.06);
}
html.dark-mode .active-banner .ab-right a::before {
    background: linear-gradient(160deg, rgba(255,255,255,0.10), rgba(124,58,237,0.08) 50%, transparent 70%);
}
html.dark-mode .active-banner .ab-right a:hover {
    background: linear-gradient(145deg, rgba(124,58,237,0.22), rgba(124,58,237,0.10));
    border-color: rgba(124,58,237,0.35);
}

/* Dark mode */
html.dark-mode .active-banner {
    background: linear-gradient(145deg, rgba(124,58,237,0.12) 0%, rgba(124,58,237,0.04) 100%);
    border-color: rgba(124,58,237,0.15);
    box-shadow:
        0 1px 0 rgba(255,255,255,0.04) inset,
        0 4px 16px rgba(0,0,0,0.20),
        0 12px 32px -8px rgba(124,58,237,0.10);
}
html.dark-mode .active-banner::before {
    background: linear-gradient(160deg, rgba(255,255,255,0.10), rgba(124,58,237,0.08) 40%, transparent 70%);
}
html.dark-mode .active-banner .ab-text .ab-title {
    color: #a78bfa;
}
html.dark-mode .active-banner .ab-text .ab-name {
    color: #f1f5f9;
}
html.dark-mode .active-banner .ab-icon {
    background: linear-gradient(145deg, rgba(124,58,237,0.25), rgba(124,58,237,0.08));
    box-shadow:
        0 2px 8px rgba(0,0,0,0.20),
        0 8px 24px -4px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.10);
}

/* ── Status Badges ── */
.badge-haflah {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    letter-spacing: .3px;
}

.badge-haflah.bg-purple { background: #ede9fe; color: #7c3aed; }
.badge-haflah.bg-green { background: #d1fae5; color: #059669; }
.badge-haflah.bg-gray { background: #f1f5f9; color: #64748b; }

.badge-aktif {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #7c3aed;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: .3px;
    margin-left: 6px;
}

/* ── Table active row ── */
tr.table-highlight {
    background: #f5f3ff !important;
    border-left: 3px solid #7c3aed;
}

tr.table-highlight td {
    background: #f5f3ff !important;
}

/* ── Aktifkan button pulse ── */
.btn-pulse {
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,.4); }
    50% { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .summary-haflah { grid-template-columns: repeat(2, 1fr); }
    .active-banner { flex-direction: column; text-align: center; }
    .active-banner .ab-left { flex-direction: column; }
}
</style>

<div class="master-siswa-page">

    {{-- HEADER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);box-shadow:0 4px 14px rgba(124,58,237,.3);">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold" style="color:var(--ms-text);font-size:20px;">Haflatul Imtihan</h4>
                        <p class="mb-0" style="color:var(--ms-text-soft);font-size:13px;">Kelola penyelenggaraan haflatul imtihan</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if(!$sudahAda)
                    <a href="{{ route('haflatul-imtihan.create') }}" class="btn btn-header-ms btn-tambah-ms btn-compact">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS — GLASSMORPHISM --}}
    <div class="summary-haflah">

        {{-- Total Haflah --}}
        <div class="summary-card">
            <div class="sc-left">
                <div class="sc-icon-3d">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" fill="rgba(124,58,237,0.08)"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        <path d="M8 14h.01" stroke-width="2"/>
                        <path d="M12 14h.01" stroke-width="2"/>
                        <path d="M16 14h.01" stroke-width="2"/>
                        <path d="M8 18h.01" stroke-width="2"/>
                        <path d="M12 18h.01" stroke-width="2"/>
                        <path d="M16 18h.01" stroke-width="2"/>
                    </svg>
                </div>
                <div class="sc-chart">
                    <div class="bar" style="height:12px"></div>
                    <div class="bar" style="height:20px"></div>
                    <div class="bar" style="height:8px"></div>
                    <div class="bar" style="height:24px"></div>
                    <div class="bar" style="height:16px"></div>
                </div>
            </div>
            <div class="sc-body">
                <span class="sc-number">{{ $haflatuls->total() }}</span>
                <span class="sc-label">Total Haflah</span>
            </div>
        </div>

        {{-- Total Lomba --}}
        <div class="summary-card">
            <div class="sc-left">
                <div class="sc-icon-3d">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="5" fill="rgba(245,158,11,0.08)"/>
                        <path d="M12 2v1" stroke-width="2"/>
                        <path d="M12 15v6"/>
                        <path d="M8 21h8"/>
                        <path d="M5.3 5.3l.7.7"/>
                        <path d="M18.7 5.3l-.7.7"/>
                        <path d="M4 15l2-1"/>
                        <path d="M20 15l-2-1"/>
                    </svg>
                </div>
                <div class="sc-chart">
                    <div class="bar" style="height:18px"></div>
                    <div class="bar" style="height:10px"></div>
                    <div class="bar" style="height:22px"></div>
                    <div class="bar" style="height:14px"></div>
                    <div class="bar" style="height:20px"></div>
                </div>
            </div>
            <div class="sc-body">
                <span class="sc-number">{{ $totalLombas }}</span>
                <span class="sc-label">Total Lomba</span>
            </div>
        </div>

        {{-- Total Peserta --}}
        <div class="summary-card">
            <div class="sc-left">
                <div class="sc-icon-3d">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="7" r="3" fill="rgba(59,130,246,0.08)"/>
                        <circle cx="17" cy="9" r="2.5" fill="rgba(59,130,246,0.06)"/>
                        <path d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                        <path d="M13.5 8.5l3-1"/>
                        <path d="M17 12.5l3-1"/>
                    </svg>
                </div>
                <div class="sc-chart">
                    <div class="bar" style="height:7px"></div>
                    <div class="bar" style="height:15px"></div>
                    <div class="bar" style="height:22px"></div>
                    <div class="bar" style="height:18px"></div>
                    <div class="bar" style="height:11px"></div>
                </div>
            </div>
            <div class="sc-body">
                <span class="sc-number">{{ $totalPesertas }}</span>
                <span class="sc-label">Total Peserta</span>
            </div>
        </div>

        {{-- Total Juri --}}
        <div class="summary-card">
            <div class="sc-left">
                <div class="sc-icon-3d">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="2" width="16" height="20" rx="2" fill="rgba(16,185,129,0.08)"/>
                        <line x1="9" y1="6" x2="15" y2="6" stroke-width="2"/>
                        <line x1="12" y1="6" x2="12" y2="18"/>
                        <path d="M7 10l5 3 5-3"/>
                        <path d="M8 14l4 2 4-2"/>
                    </svg>
                </div>
                <div class="sc-chart">
                    <div class="bar" style="height:14px"></div>
                    <div class="bar" style="height:22px"></div>
                    <div class="bar" style="height:9px"></div>
                    <div class="bar" style="height:17px"></div>
                    <div class="bar" style="height:12px"></div>
                </div>
            </div>
            <div class="sc-body">
                <span class="sc-number">{{ $totalJuries }}</span>
                <span class="sc-label">Total Juri</span>
            </div>
        </div>

    </div>

    {{-- ACTIVE HAFLAH BANNER --}}
    @php $activeHaflah = $haflatuls->first(fn($h) => session('haflah_id') == $h->id); @endphp
    @if($activeHaflah)
    <div class="active-banner">
        <div class="ab-left">
            <div class="ab-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" fill="rgba(124,58,237,0.08)"/>
                </svg>
            </div>
            <div class="ab-text">
                <span class="ab-title">Haflah Sedang Aktif</span>
                <span class="ab-name">{{ $activeHaflah->nama_acara }} — {{ $activeHaflah->tahunAjaran?->tahun_ajaran ?? '-' }}</span>
            </div>
        </div>
        <div class="ab-right">
            <a href="{{ route('haflatul-imtihan.show', $activeHaflah->id) }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                Detail
            </a>
        </div>
    </div>
    @endif

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="card table-card">
        <div class="card-body" style="padding:20px 24px;">
            <div class="table-responsive">
                <table id="table_haflah" class="table table-ms display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Nama Acara</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th width="210">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($haflatuls as $haflatul)
                        <tr class="{{ session('haflah_id') == $haflatul->id ? 'table-highlight' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span style="font-weight:600;color:#475569;">{{ $haflatul->tahunAjaran?->tahun_ajaran ?? '-' }}</span>
                            </td>
                            <td>
                                <span style="font-weight:600;color:#1e293b;">{{ $haflatul->nama_acara }}</span>
                                @if(session('haflah_id') == $haflatul->id)
                                <span class="badge-aktif"><i class="fas fa-circle" style="font-size:6px;"></i> SEDANG AKTIF</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">{{ \Helper::tanggal_indonesia($haflatul->tanggal_mulai) }}</td>
                            <td style="white-space:nowrap;">{{ \Helper::tanggal_indonesia($haflatul->tanggal_selesai) }}</td>
                            <td>
                                @php
                                $badgeClass = match($haflatul->status) {
                                    'Aktif' => 'bg-green',
                                    'Persiapan' => 'bg-gray',
                                    'Selesai' => 'bg-purple',
                                    default => 'bg-gray'
                                };
                                $statusIcon = match($haflatul->status) {
                                    'Aktif' => 'fa-play-circle',
                                    'Persiapan' => 'fa-clock',
                                    'Selesai' => 'fa-box-archive',
                                    default => 'fa-circle'
                                };
                                @endphp
                                <span class="badge-haflah {{ $badgeClass }}">
                                    <i class="fas {{ $statusIcon }}" style="font-size:10px;"></i>
                                    {{ $haflatul->status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-group-ms">
                                    @if($haflatul->status == 'Selesai')
                                    <span class="btn btn-outline-secondary" title="Arsip" style="cursor:not-allowed;opacity:.5;">
                                        <i class="fas fa-box-archive"></i>
                                    </span>
                                    <a href="{{ route('haflatul-imtihan.show', $haflatul->id) }}"
                                        class="btn btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @else
                                    @if(session('haflah_id') != $haflatul->id)
                                    <a href="{{ route('haflah.aktifkan', $haflatul->id) }}"
                                        class="btn btn-outline-success btn-pulse" title="Aktifkan"
                                        onclick="return confirm('Aktifkan {{ $haflatul->nama_acara }}?')">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('haflatul-imtihan.show', $haflatul->id) }}"
                                        class="btn btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('haflatul-imtihan.edit', $haflatul->id) }}"
                                        class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('haflatul-imtihan.destroy', $haflatul->id) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus {{ $haflatul->nama_acara }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div style="text-align:center;padding:40px 20px;">
                                    <i class="fas fa-award" style="font-size:48px;color:#e2e8f0;margin-bottom:14px;"></i>
                                    <p style="color:#94a3b8;font-size:15px;margin:0;">Belum ada data haflatul imtihan</p>
                                    @if(!$sudahAda)
                                    <a href="{{ route('haflatul-imtihan.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Sekarang</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#table_haflah').DataTable({
            pagingType: 'simple_numbers',
            responsive: true,
            pageLength: 10,
            order: [[3, 'desc']],
            dom: '<"dt-toolbar"<"dt-left"l><"dt-right"f>>tip',
            language: {
                url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json',
                search: '',
                searchPlaceholder: 'Cari haflatul imtihan...',
                paginate: { first: '«', previous: '‹', next: '›', last: '»' },
                aria: { paginate: { first: 'First', previous: 'Previous', next: 'Next', last: 'Last' } }
            }
        });
    });
</script>
@endpush
