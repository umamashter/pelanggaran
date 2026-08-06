@extends('layouts.main')
@section('title', 'Rubrik Penilaian')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Rubrik — Readiness Dashboard ---------- */
    .ar-dash { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 18px; box-shadow: var(--lw-shadow); padding: 22px 24px; margin-bottom: 20px; }
    .ar-dash-top { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .ar-dash-top h2 { font-size: 16px; font-weight: 800; color: var(--lw-text); margin: 0; display: flex; align-items: center; gap: 9px; }
    .ar-dash-top h2 i { color: var(--lw-primary); font-size: 18px; }
    .ar-dash-top .sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .ar-progress-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px; border-radius: 999px; font-size: 11.5px; font-weight: 700; background: var(--lw-navy-soft); color: var(--lw-primary); }
    .ar-dash-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 22px; align-items: stretch; }
    .ar-metric { display: flex; align-items: center; gap: 16px; }
    .ar-metric-big { font-size: clamp(36px, 4vw, 50px); font-weight: 800; letter-spacing: -1.5px; line-height: 1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .ar-metric-label { font-size: 12px; font-weight: 700; color: var(--lw-text-2); text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }
    .ar-metric-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 3px; }
    .ar-split { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 16px; max-width: 420px; }
    .ar-split-item { display: flex; align-items: center; gap: 9px; padding: 9px 12px; border-radius: 12px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .ar-split-item i { font-size: 15px; }
    .ar-split-item .n { font-size: 17px; font-weight: 800; line-height: 1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .ar-split-item .l { font-size: 10px; color: var(--lw-text-3); font-weight: 600; margin-top: 2px; }
    .ar-readiness { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; padding: 16px 18px; }
    .ar-readiness-title { font-size: 12px; font-weight: 700; color: var(--lw-text); margin-bottom: 12px; display: flex; align-items: center; gap: 7px; }
    .ar-readiness-title i { color: var(--lw-primary); }
    .ar-readiness-bar { display: flex; height: 12px; border-radius: 999px; overflow: hidden; background: var(--lw-bg); }
    .ar-readiness-bar span { height: 100%; transition: width 1s cubic-bezier(.22,.61,.36,1); }
    .ar-readiness-legend { display: flex; flex-wrap: wrap; gap: 8px 14px; margin-top: 12px; }
    .ar-legend { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: var(--lw-text-2); }
    .ar-legend i { font-size: 10px; }
    .ar-legend b { font-variant-numeric: tabular-nums; color: var(--lw-text); }
    .ar-readiness .ar-hint { display: flex; align-items: center; gap: 8px; margin-top: 12px; padding: 10px 13px; border-radius: 11px; background: var(--lw-bg); font-size: 11.5px; color: var(--lw-text-2); }
    .ar-readiness .ar-hint i { color: var(--lw-primary); font-size: 13px; }

    /* ---------- Status chips ---------- */
    .ar-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; font-size: 11.5px; font-weight: 700; border: 1px solid transparent; white-space: nowrap; }
    .ar-status.blue { background: var(--lw-navy-soft); color: var(--lw-primary); border-color: var(--lw-navy-border); }
    .ar-status.green { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
    .ar-status.red { background: var(--lw-red-soft); color: var(--lw-red); border-color: var(--lw-red-border); }
    .ar-status.amber { background: var(--lw-amber-soft); color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .ar-status.violet { background: var(--lw-violet-soft); color: var(--lw-violet); border-color: var(--lw-violet-border); }
    .ar-status.gray { background: var(--lw-bg); color: var(--lw-text-3); border-color: var(--lw-border); }
    .ar-status i { font-size: 12px; }
    .ar-status.is-lock { padding: 4px 9px; font-size: 10.5px; }

    /* ---------- Card grid ---------- */
    .ar-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .ar-card { position: relative; overflow: hidden; border: 1px solid var(--lw-border); border-radius: 18px; background: var(--lw-card); box-shadow: var(--lw-shadow); padding: 18px; display: flex; flex-direction: column; gap: 13px; transition: all .22s ease; }
    .ar-card:hover { border-color: var(--lw-primary-border); transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); }
    .ar-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--lw-grad); opacity: 0; transition: opacity .2s; }
    .ar-card:hover::before { opacity: 1; }
    .ar-card.is-locked { opacity: .72; }
    .ar-card.is-locked:hover { opacity: .85; }
    .ar-card.is-empty { background: linear-gradient(180deg, var(--lw-card), color-mix(in srgb, var(--lw-bg) 45%, var(--lw-card))); border-style: dashed; }
    .ar-card.is-empty::before { background: var(--lw-border); }

    .ar-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
    .ar-card-name { min-width: 0; }
    .ar-card-name h3 { font-size: 14.5px; font-weight: 800; color: var(--lw-text); margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ar-card-meta { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .ar-tag { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 8px; font-size: 10.5px; font-weight: 600; background: var(--lw-bg); color: var(--lw-text-2); white-space: nowrap; }
    .ar-tag i { font-size: 11px; color: var(--lw-primary); }
    .ar-badge-count { flex-shrink: 0; min-width: 46px; height: 46px; border-radius: 13px; display: inline-flex; flex-direction: column; align-items: center; justify-content: center; background: var(--lw-navy-soft); color: var(--lw-primary); border: 1px solid var(--lw-navy-border); }
    .ar-badge-count b { font-size: 17px; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
    .ar-badge-count span { font-size: 8.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; margin-top: 2px; }

    /* Preview list */
    .ar-preview { border-top: 1px dashed var(--lw-border); padding-top: 12px; }
    .ar-preview-title { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; }
    .ar-preview-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--lw-text-2); padding: 4px 0; }
    .ar-preview-item i { color: var(--lw-green); font-size: 12px; flex-shrink: 0; }
    .ar-preview-item .nm { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ar-preview-more { font-size: 11px; font-weight: 600; color: var(--lw-primary); margin-top: 5px; display: inline-flex; align-items: center; gap: 5px; }
    .ar-preview-empty { display: flex; flex-direction: column; align-items: center; gap: 7px; padding: 14px 8px; text-align: center; border-radius: 12px; border: 1.5px dashed var(--lw-border); color: var(--lw-text-3); font-size: 11.5px; }
    .ar-preview-empty i { font-size: 20px; color: var(--lw-text-3); }

    /* Actions */
    .ar-card-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; border-top: 1px solid var(--lw-border); padding-top: 12px; }
    .ar-act { display: inline-flex; align-items: center; gap: 4px; }
    .ar-act-btn { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); font-size: 13px; cursor: pointer; transition: all .2s ease; text-decoration: none; position: relative; overflow: hidden; }
    .ar-act-btn:hover { transform: translateY(-1px); box-shadow: var(--lw-shadow); border-color: var(--lw-primary-border); color: var(--lw-primary); }
    .ar-act-btn.edit:hover { color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .ar-act-btn.del:hover { color: var(--lw-red); border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .ar-act-btn.is-off { opacity: .4; cursor: not-allowed; }
    .ar-act-btn.is-off:hover { transform: none; box-shadow: none; color: var(--lw-text-2); border-color: var(--lw-border); background: var(--lw-card); }

    /* ---------- Client pager ---------- */
    .ar-client-pager { display: none; align-items: center; justify-content: center; gap: 8px; margin-top: 14px; }
    .ar-client-pager.is-visible { display: flex; }
    .ar-page-btn { min-width: 36px; height: 36px; padding: 0 10px; border-radius: 10px; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all .18s ease; }
    .ar-page-btn:hover:not(:disabled) { border-color: var(--lw-primary-border); color: var(--lw-primary); }
    .ar-page-btn.is-active { background: var(--lw-grad); color: #fff; border-color: transparent; box-shadow: 0 6px 16px -6px rgba(43,60,120,.5); }
    .ar-page-btn:disabled { opacity: .4; cursor: not-allowed; }

    .ar-hero-btn { text-decoration: none !important; }

    .modal-header-custom { padding: 18px 24px; border-bottom: none; }
    .modal-body-custom { padding: 16px 24px 20px; }
    html:not(.dark-mode) .modal-content { border: none; border-radius: 20px; box-shadow: 0 24px 80px rgba(0,0,0,.15); overflow: hidden; }
    html.dark-mode .modal-content { background: #131d3a !important; border: 2px solid #2b3c78 !important; border-radius: 24px !important; box-shadow: 0 30px 70px -16px rgba(0,0,0,.7) !important; }
    .btn-cancel-modal { border: none !important; border-radius: 10px !important; padding: 9px 22px !important; font-weight: 600 !important; font-size: 13px !important; transition: all .25s !important; }
    html:not(.dark-mode) .btn-cancel-modal { background: #f1f5f9 !important; color: #475569 !important; }
    html:not(.dark-mode) .btn-cancel-modal:hover { background: #e2e8f0 !important; color: #1e293b !important; }
    html.dark-mode .btn-cancel-modal { background: rgba(255,255,255,.08) !important; color: var(--text-secondary) !important; }
    html.dark-mode .btn-cancel-modal:hover { background: rgba(255,255,255,.14) !important; color: var(--text-primary) !important; }
    .btn-cancel-modal:hover { transform: translateY(-1px); }

    @media (max-width: 1399.98px) { .ar-grid { grid-template-columns: repeat(2, 1fr); } .lw-mod .lw-kpi-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 991.98px) { .ar-dash-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) {
        .ar-grid { grid-template-columns: 1fr; }
        .lw-mod .lw-kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .ar-split { max-width: none; }
        .ar-dash { padding: 18px 16px; }
        .ar-hero-right { width: 100%; }
        .ar-hero-right .lw-btn { flex: 1; justify-content: center; }
    }
</style>

@php
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $activeHaflah = \App\Models\HaflatulImtihan::find(session('haflah_id'));
    $haflahStatus = $haflahAktif->status ?? null;
    $haflahStatusChip = $haflahStatus === 'Aktif' ? 'lw-chip--green' : ($haflahStatus === 'Selesai' ? 'lw-chip--violet' : 'lw-chip--amber');
    $haflahStatusIcon = $haflahStatus === 'Aktif' ? 'bi-play-circle-fill' : ($haflahStatus === 'Selesai' ? 'bi-archive-fill' : 'bi-clock');

    $aspekRows = \App\Models\AspekPenilaian::select('id', 'lomba_id', 'nama_aspek', 'haflah_id')
        ->orderBy('nama_aspek')->get();
    $grouped = $aspekRows->groupBy('lomba_id');
    $totalAspek = $aspekRows->count();
    $totalRubrik = $grouped->count();
    $totalLomba = $lombas->count();
    $selesaiHaflah = \App\Models\HaflatulImtihan::where('status', 'Selesai')->pluck('id');

    $rubrikMap = $grouped->mapWithKeys(function ($g) use ($selesaiHaflah) {
        return [$g->first()->lomba_id => [
            'count'     => $g->count(),
            'names'     => $g->pluck('nama_aspek')->values()->all(),
            'locked'    => $selesaiHaflah->contains($g->first()->haflah_id),
            'latest_id' => $g->max('id'),
        ]];
    });
    $lockedCount = $rubrikMap->filter(fn ($r) => $r['locked'])->count();
    $readyCount  = $totalRubrik - $lockedCount;
    $emptyCount  = $totalLomba - $totalRubrik;
    $progress    = $totalLomba > 0 ? round($totalRubrik / $totalLomba * 100) : 0;
    $avgAspek    = $totalRubrik > 0 ? round($totalAspek / $totalRubrik, 1) : 0;

    $lombaStatusMeta = [
        'Belum Mulai' => ['cls' => 'blue',  'ic' => 'bi-hourglass-split'],
        'Berlangsung' => ['cls' => 'amber', 'ic' => 'bi-play-circle'],
        'Selesai'     => ['cls' => 'green', 'ic' => 'bi-flag-fill'],
    ];

    $cards = $lombas->map(function ($l) use ($rubrikMap, $lombaStatusMeta) {
        $info = $rubrikMap->get($l->id);
        $count = $info['count'] ?? 0;
        $locked = $info['locked'] ?? false;
        $names = $info['names'] ?? [];
        if ($count === 0) {
            $status = ['label' => 'Belum Ada Rubrik', 'cls' => 'gray', 'ic' => 'bi-file-earmark-x'];
        } elseif ($locked) {
            $status = ['label' => 'Haflah Selesai', 'cls' => 'violet', 'ic' => 'bi-lock-fill'];
        } elseif ($count < 4) {
            $status = ['label' => 'Rubrik Minimal', 'cls' => 'amber', 'ic' => 'bi-exclamation-triangle'];
        } else {
            $status = ['label' => 'Siap Dinilai', 'cls' => 'green', 'ic' => 'bi-check-circle'];
        }
        $st = $lombaStatusMeta[$l->status] ?? ['cls' => 'gray', 'ic' => 'bi-circle'];
        return [
            'id'       => $l->id,
            'nama'     => $l->nama,
            'jenis'    => $l->jenis ?? 'Individu',
            'status'   => $l->status ?? '-',
            'statusCls'=> $st['cls'],
            'statusIc' => $st['ic'],
            'count'    => $count,
            'names'    => array_slice($names, 0, 3),
            'extra'    => max(0, $count - 3),
            'locked'   => $locked,
            'stLabel'  => $status['label'],
            'stCls'    => $status['cls'],
            'stIc'     => $status['ic'],
            'latest_id'=> $info['latest_id'] ?? null,
        ];
    });
@endphp

<div class="lw-mod">

    {{-- HERO --}}
    <div class="lw-hero">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-clipboard2-check"></i></span>
                <div>
                    <h1 class="lw-hero-title">Rubrik Penilaian</h1>
                    <p class="lw-hero-sub">Rubric Management Center — susun aspek penilaian setiap lomba agar juri siap menilai saat Haflatul Imtihan dimulai.</p>
                    <div class="lw-hero-badges">
                        <span class="lw-hero-badge"><i class="bi bi-calendar-event"></i>{{ optional($activeHaflah)->nama_acara ?: 'Haflah belum dipilih' }}</span>
                        <span class="lw-hero-badge {{ $haflahStatus === 'Selesai' ? 'lw-hero-badge--warn' : 'lw-hero-badge--ok' }}"><i class="bi {{ $haflahStatusIcon }}"></i>{{ $haflahStatus ?? '-' }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-trophy"></i>{{ $totalLomba }} lomba</span>
                    </div>
                </div>
            </div>
            <div class="lw-hero-right">
                <button type="button" class="lw-btn lw-btn--light" id="arRefresh" aria-label="Muat ulang data"><i class="bi bi-arrow-clockwise"></i></button>
                <button type="button" class="lw-btn lw-btn--light ar-hero-btn" data-bs-toggle="modal" data-bs-target="#exportModal" data-format="pdf"><i class="bi bi-filetype-pdf"></i> Cetak PDF</button>
                <button type="button" class="lw-btn lw-btn--light ar-hero-btn" data-bs-toggle="modal" data-bs-target="#exportModal" data-format="excel"><i class="bi bi-filetype-xlsx"></i> Export Excel</button>
                <a href="{{ route('aspek-penilaian.create') }}" class="lw-btn lw-btn--solid ar-hero-btn"><i class="bi bi-plus-lg"></i> Buat Rubrik</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="lw-alert lw-alert--warn"><i class="bi bi-info-circle-fill"></i> {{ session('info') }}</div>
    @endif

    {{-- RUBRIC READINESS DASHBOARD --}}
    <div class="ar-dash">
        <div class="ar-dash-top">
            <div>
                <h2><i class="bi bi-graph-up-arrow"></i> Rubric Readiness Dashboard</h2>
                <div class="sub">Kesiapan penilaian {{ optional($activeHaflah)->nama_acara ?: 'Haflah Aktif' }}</div>
            </div>
            <span class="ar-progress-chip"><i class="bi bi-clipboard2-check"></i> Kesiapan <b class="ms-1" data-count="{{ $progress }}">{{ $progress }}</b>%</span>
        </div>
        <div class="ar-dash-grid">
            <div>
                <div class="ar-metric">
                    <div>
                        <div class="ar-metric-big" data-count="{{ $totalAspek }}">{{ $totalAspek }}</div>
                        <div class="ar-metric-label">Total Aspek Penilaian</div>
                        <div class="ar-metric-sub">{{ $totalRubrik }} lomba sudah punya rubrik &middot; {{ $avgAspek }} aspek/rubrik</div>
                    </div>
                </div>
                <div class="ar-split">
                    <div class="ar-split-item"><i class="bi bi-trophy" style="color:var(--lw-primary);"></i><div><div class="n" data-count="{{ $totalLomba }}">{{ $totalLomba }}</div><div class="l">Total Lomba</div></div></div>
                    <div class="ar-split-item"><i class="bi bi-check-circle" style="color:var(--lw-green);"></i><div><div class="n" data-count="{{ $readyCount }}">{{ $readyCount }}</div><div class="l">Rubrik Siap</div></div></div>
                    <div class="ar-split-item"><i class="bi bi-lock" style="color:var(--lw-violet);"></i><div><div class="n" data-count="{{ $lockedCount }}">{{ $lockedCount }}</div><div class="l">Terkunci</div></div></div>
                </div>
            </div>
            <div class="ar-readiness">
                <div class="ar-readiness-title"><i class="bi bi-bar-chart"></i> Distribusi Kesiapan Rubrik</div>
                <div class="ar-readiness-bar" id="readyBar">
                    <span style="background:var(--lw-green);width:0;" data-w="{{ $totalLomba ? $readyCount / $totalLomba * 100 : 0 }}"></span>
                    <span style="background:var(--lw-amber);width:0;" data-w="{{ $totalLomba ? ($totalRubrik - $readyCount - $lockedCount) / $totalLomba * 100 : 0 }}"></span>
                    <span style="background:var(--lw-violet);width:0;" data-w="{{ $totalLomba ? $lockedCount / $totalLomba * 100 : 0 }}"></span>
                    <span style="background:var(--lw-text-3);width:0;" data-w="{{ $totalLomba ? $emptyCount / $totalLomba * 100 : 0 }}"></span>
                </div>
                <div class="ar-readiness-legend">
                    <span class="ar-legend"><i class="bi bi-circle-fill" style="color:var(--lw-green);"></i>Siap <b data-count="{{ $readyCount }}">{{ $readyCount }}</b></span>
                    <span class="ar-legend"><i class="bi bi-circle-fill" style="color:var(--lw-amber);"></i>Minimal <b data-count="{{ $totalRubrik - $readyCount - $lockedCount }}">{{ $totalRubrik - $readyCount - $lockedCount }}</b></span>
                    <span class="ar-legend"><i class="bi bi-circle-fill" style="color:var(--lw-violet);"></i>Haflah Selesai <b data-count="{{ $lockedCount }}">{{ $lockedCount }}</b></span>
                    <span class="ar-legend"><i class="bi bi-circle-fill" style="color:var(--lw-text-3);"></i>Belum <b data-count="{{ $emptyCount }}">{{ $emptyCount }}</b></span>
                </div>
                @if($emptyCount > 0)
                <div class="ar-hint"><i class="bi bi-info-circle"></i> {{ $emptyCount }} lomba belum memiliki rubrik — lengkapi agar seluruh lomba siap dinilai.</div>
                @else
                <div class="ar-hint" style="background:var(--lw-green-soft);color:var(--lw-green);"><i class="bi bi-check2-circle"></i> Semua lomba sudah memiliki rubrik penilaian. Mantap!</div>
                @endif
            </div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="lw-toolbar" style="top:78px;" id="arToolbar">
        <div class="lw-search" style="min-width:200px;">
            <i class="bi bi-search"></i>
            <input type="search" id="arSearch" class="lw-control" placeholder="Cari nama lomba..." autocomplete="off" aria-label="Cari nama lomba">
        </div>
        <div class="lw-filter" style="min-width:190px;">
            <label><i class="bi bi-funnel"></i> Pilih Lomba</label>
            <select id="arFilterLomba" class="lw-select">
                <option value="">Semua Lomba</option>
                @foreach($lombas as $l)
                    <option value="{{ $l->id }}">{{ $l->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter lw-filter--perpage">
            <label>Entri</label>
            <select id="arPerPage" class="lw-select">
                <option value="8">8</option>
                <option value="16">16</option>
                <option value="24">24</option>
                <option value="0">Semua</option>
            </select>
        </div>
        <div class="lw-toolbar-actions">
            <button type="button" id="arReset" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
        </div>
    </div>

    {{-- DATA GRID --}}
    @if($cards->isEmpty())
        <div class="lw-card">
            <div class="lw-empty">
                <div class="lw-empty-illus"><div class="ring"></div><div class="core"><i class="bi bi-clipboard2-check"></i></div></div>
                <div class="lw-empty-title">Belum ada lomba untuk disiapkan</div>
                <div class="lw-empty-sub">Tidak ada lomba pada haflah ini. Tambahkan lomba terlebih dahulu sebelum menyusun rubrik penilaian.</div>
                <a href="{{ route('lomba.index') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Kelola Lomba</a>
            </div>
        </div>
    @else
        <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-journal-richtext"></i> Daftar Rubrik</div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <span class="lw-chip lw-chip--slate lw-chip-mini">Belum Ada Rubrik</span>
                    <span class="lw-chip lw-chip--amber lw-chip-mini">Rubrik Minimal</span>
                    <span class="lw-chip lw-chip--green lw-chip-mini">Siap Dinilai</span>
                    <span class="lw-chip lw-chip--violet lw-chip-mini">Haflah Selesai</span>
                </div>
            </div>

            <div class="ar-grid" id="arGrid">
                @foreach($cards as $c)
                <article class="ar-card {{ $c['locked'] ? 'is-locked' : '' }} {{ $c['count'] === 0 ? 'is-empty' : '' }}"
                    data-nama="{{ mb_strtolower($c['nama']) }}" data-lomba="{{ $c['id'] }}">
                    <div class="ar-card-top">
                        <div class="ar-card-name">
                            <h3 title="{{ $c['nama'] }}">{{ $c['nama'] }}</h3>
                            <div class="ar-card-meta">
                                <span class="ar-tag"><i class="bi {{ $c['jenis'] === 'Tim' ? 'bi-people' : 'bi-person' }}"></i>{{ $c['jenis'] }}</span>
                                <span class="ar-tag"><i class="bi {{ $c['statusIc'] }}" style="color:var(--lw-text-2);"></i>{{ $c['status'] }}</span>
                                <span class="ar-status {{ $c['stCls'] }}"><i class="bi {{ $c['stIc'] }}"></i>{{ $c['stLabel'] }}</span>
                                @if($c['locked'])
                                <span class="ar-status red is-lock"><i class="bi bi-lock-fill"></i> Terkunci</span>
                                @endif
                            </div>
                        </div>
                        <div class="ar-badge-count"><b>{{ $c['count'] }}</b><span>aspek</span></div>
                    </div>

                    <div class="ar-preview">
                        <div class="ar-preview-title">Pratinjau Rubrik</div>
                        @if($c['count'] > 0)
                            @foreach($c['names'] as $nm)
                            <div class="ar-preview-item"><i class="bi bi-check-circle-fill"></i><span class="nm">{{ $nm }}</span></div>
                            @endforeach
                            @if($c['extra'] > 0)
                            <span class="ar-preview-more"><i class="bi bi-plus-circle"></i>{{ $c['extra'] }} aspek lainnya</span>
                            @endif
                        @else
                            <div class="ar-preview-empty"><i class="bi bi-folder2-open"></i> Belum ada aspek. Buat rubrik untuk lomba ini.</div>
                        @endif
                    </div>

                    <div class="ar-card-foot">
                        <div class="ar-act">
                            <a href="{{ route('aspek-penilaian.show', $c['id']) }}" class="ar-act-btn" title="Detail Rubrik" aria-label="Detail Rubrik"><i class="bi bi-eye"></i></a>
                            @if($c['locked'])
                                <span class="ar-act-btn edit is-off" tabindex="-1" title="Terkunci — haflah telah selesai" aria-label="Terkunci"><i class="bi bi-lock"></i></span>
                                <span class="ar-act-btn del is-off" tabindex="-1" title="Terkunci — haflah telah selesai" aria-label="Terkunci"><i class="bi bi-lock"></i></span>
                            @else
                                @if($c['count'] > 0)
                                <a href="{{ route('aspek-penilaian.edit', $c['latest_id']) }}" class="ar-act-btn edit" title="Edit Rubrik" aria-label="Edit Rubrik"><i class="bi bi-pencil"></i></a>
                                @else
                                <span class="ar-act-btn edit is-off" tabindex="-1" title="Belum ada rubrik" aria-label="Edit Rubrik"><i class="bi bi-pencil"></i></span>
                                @endif
                                <button type="button" class="ar-act-btn del" title="Hapus Semua" aria-label="Hapus Semua Aspek"
                                    data-ar-delete data-id="{{ $c['id'] }}" data-nama="{{ e($c['nama']) }}" data-jml="{{ $c['count'] }}"><i class="bi bi-trash"></i></button>
                            @endif
                        </div>
                        @if($c['count'] > 0)
                        <span class="ar-tag" style="cursor:default;"><i class="bi bi-shield-check"></i>Total {{ $c['count'] }}</span>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>

            <div class="ar-client-pager" id="arClientPager"></div>
            <div class="lw-empty" id="arClientEmpty" style="display:none;padding:32px 16px;">
                <i class="bi bi-search mb-3" style="font-size:22px;display:block;"></i>Tidak ada lomba yang cocok dengan filter.
            </div>
        </div>
    @endif

</div>

{{-- EXPORT MODAL --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header-custom" style="padding:20px 24px 0;border:none;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="exportModalLabel"><i class="bi bi-download"></i> Export Rubrik Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body-custom" style="padding:8px 24px 22px;">
                <p class="text-muted" id="exportDesc" style="margin-bottom:16px;font-size:13px;">Pilih lomba dan format ekspor form penilaian.</p>
                <div class="mb-3">
                    <label class="form-label" style="font-size:12px;font-weight:700;color:var(--lw-text-3);text-transform:uppercase;letter-spacing:.4px;">Lomba</label>
                    <select id="exportLomba" class="lw-select w-100">
                        <option value="">-- Pilih Lomba --</option>
                        @foreach($lombas as $l)
                        <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="border rounded-3 p-3" style="border-color:var(--lw-border) !important;">
                    <div class="d-flex justify-content-between align-items-center py-1" style="font-size:12.5px;"><span class="text-secondary"><i class="bi bi-trophy me-1"></i>Lomba</span><span class="fw-semibold" id="expLomba">-</span></div>
                    <div class="d-flex justify-content-between align-items-center py-1" style="font-size:12.5px;"><span class="text-secondary"><i class="bi bi-list-check me-1"></i>Jumlah Aspek</span><span class="fw-semibold" id="expJumlah">-</span></div>
                    <div class="d-flex justify-content-between align-items-center py-1" style="font-size:12.5px;"><span class="text-secondary"><i class="bi bi-file-earmark me-1"></i>Format Export</span><span class="fw-semibold" id="expFormat">-</span></div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnExportGo" class="lw-btn lw-btn--solid"><i class="bi bi-download me-1"></i> Export Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('aspek-penilaian.create') }}" class="lw-fab" aria-label="Buat rubrik baru"><i class="bi bi-plus-lg"></i></a>

<form id="arDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var grid = document.getElementById('arGrid');
    if (!grid) return;
    var cards = Array.prototype.slice.call(grid.querySelectorAll('.ar-card'));
    var search = document.getElementById('arSearch');
    var filterLomba = document.getElementById('arFilterLomba');
    var perPageSel = document.getElementById('arPerPage');
    var pagerEl = document.getElementById('arClientPager');
    var emptyEl = document.getElementById('arClientEmpty');
    var state = { q: '', lomba: '', per: 8, page: 1 };

    function filtered() {
        return cards.filter(function (c) {
            var ok = true;
            if (state.q && c.dataset.nama.indexOf(state.q) === -1) ok = false;
            if (state.lomba && c.dataset.lomba !== state.lomba) ok = false;
            return ok;
        });
    }

    function render() {
        var list = filtered();
        var per = parseInt(state.per, 10) || list.length;
        var pages = Math.max(1, Math.ceil(list.length / per));
        state.page = Math.min(state.page, pages);
        var start = (state.page - 1) * per;
        var slice = list.slice(start, start + per);
        var ids = {};
        slice.forEach(function (c) { ids[c.dataset.lomba] = 1; });
        cards.forEach(function (c) { c.style.display = ids[c.dataset.lomba] ? '' : 'none'; });
        emptyEl.style.display = list.length === 0 ? 'block' : 'none';

        if (pages > 1) {
            pagerEl.classList.add('is-visible');
            var h = '';
            h += '<button type="button" class="ar-page-btn" data-pg="' + (state.page - 1) + '" ' + (state.page === 1 ? 'disabled' : '') + ' aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></button>';
            var startP = Math.max(1, state.page - 2), endP = Math.min(pages, startP + 4);
            for (var p = startP; p <= endP; p++) {
                h += '<button type="button" class="ar-page-btn' + (p === state.page ? ' is-active' : '') + '" data-pg="' + p + '">' + p + '</button>';
            }
            h += '<button type="button" class="ar-page-btn" data-pg="' + (state.page + 1) + '" ' + (state.page === pages ? 'disabled' : '') + ' aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></button>';
            pagerEl.innerHTML = h;
            pagerEl.querySelectorAll('.ar-page-btn[data-pg]').forEach(function (b) {
                b.addEventListener('click', function () { state.page = parseInt(b.dataset.pg, 10) || 1; render(); });
            });
        } else {
            pagerEl.classList.remove('is-visible');
        }
    }

    search.addEventListener('input', function () { state.q = this.value.trim().toLowerCase(); state.page = 1; render(); });
    filterLomba.addEventListener('change', function () { state.lomba = this.value; state.page = 1; render(); });
    perPageSel.addEventListener('change', function () { state.per = parseInt(this.value, 10); state.page = 1; render(); });
    document.getElementById('arReset').addEventListener('click', function () {
        search.value = ''; filterLomba.value = ''; perPageSel.value = '8';
        state = { q: '', lomba: '', per: 8, page: 1 };
        render();
    });

    render();

    /* ---------- readiness bar + counters ---------- */
    setTimeout(function () {
        document.querySelectorAll('#readyBar span[data-w]').forEach(function (s) { s.style.width = s.dataset.w + '%'; });
    }, 120);
    document.querySelectorAll('.lw-kpi-num[data-count], .ar-metric-big[data-count], .ar-split .n[data-count], .ar-progress-chip b[data-count], .ar-legend b[data-count]').forEach(function (el) {
        if (window.LW && LW.counter) LW.counter(el);
    });

    /* ---------- export modal ---------- */
    var exportModal = document.getElementById('exportModal');
    var exportFormat = 'pdf';
    var aspekJumlah = @json($rubrikMap->map(fn ($r) => $r['count'])->toArray());
    exportModal.addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        exportFormat = btn ? (btn.dataset.format || 'pdf') : 'pdf';
        document.getElementById('expFormat').textContent = exportFormat === 'pdf' ? 'PDF (A4 Landscape)' : 'Excel (.xlsx)';
        document.getElementById('exportLomba').value = '';
        updateExportPreview();
    });
    document.getElementById('exportLomba').addEventListener('change', updateExportPreview);
    function updateExportPreview() {
        var id = document.getElementById('exportLomba').value;
        var nama = id ? document.getElementById('exportLomba').selectedOptions[0].text : '-';
        var jml = id ? (aspekJumlah[id] || 0) : '-';
        document.getElementById('expLomba').textContent = nama;
        document.getElementById('expJumlah').textContent = (id ? jml + ' aspek' : '-');
        document.getElementById('btnExportGo').disabled = !id;
    }
    document.getElementById('btnExportGo').addEventListener('click', function () {
        var id = document.getElementById('exportLomba').value;
        if (!id) { if (window.LW && LW.toast) LW.toast('err', 'Pilih lomba', 'Silakan pilih lomba terlebih dahulu.'); return; }
        var url = exportFormat === 'pdf'
            ? '/aspek-penilaian/cetak-pdf/' + id
            : '/aspek-penilaian/export-excel/' + id;
        window.open(url, '_blank');
        bootstrap.Modal.getInstance(exportModal).hide();
    });

    /* ---------- delete confirmation ---------- */
    var deleteForm = document.getElementById('arDeleteForm');
    grid.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-ar-delete]');
        if (!btn) return;
        var id = btn.dataset.id, nama = btn.dataset.nama, jml = parseInt(btn.dataset.jml, 10) || 0;
        if (!id) return;
        LW.confirm('Hapus Semua Aspek?', 'Seluruh aspek penilaian lomba "' + nama + '" (' + jml + ' aspek) akan dihapus. Data tidak dapat dikembalikan.', 'bi-trash').then(function (ok) {
            if (ok) { deleteForm.action = '{{ url('aspek-penilaian/hapus-semua') }}/' + id; deleteForm.submit(); }
        });
    });

    /* ---------- refresh with skeleton ---------- */
    document.getElementById('arRefresh').addEventListener('click', function () {
        var i = this.querySelector('i');
        i.classList.add('spin');
        this.disabled = true;
        setTimeout(function () { window.location.reload(); }, 550);
    });

    /* ---------- ripple ---------- */
    document.querySelectorAll('#arToolbar .lw-btn, #arToolbar .lw-select, #arToolbar .lw-control').forEach(function (el) {
        el.addEventListener('mousedown', function (e) { if (window.LW && LW.ripple) LW.ripple(e); });
    });
})();
</script>
<style>
    #arRefresh i.spin { animation: lwSpin 1s linear infinite; }
</style>
@endpush
@endsection
