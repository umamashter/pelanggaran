@extends('layouts.main')
@section('title', 'Hasil Lomba')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .hl-mod { --hl-radius: 16px; }
    .hl-toolbar { top: 78px; }
    .hl-progress { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; padding: 22px 24px; box-shadow: var(--lw-shadow); margin-bottom: 20px; }
    .hl-progress-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; flex-wrap: wrap; }
    .hl-progress-top h2 { margin: 0; font-size: 16px; font-weight: 800; color: var(--lw-text); display: inline-flex; align-items: center; gap: 8px; }
    .hl-progress-top h2 i { color: var(--lw-primary); }
    .hl-progress-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-progress-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; background: var(--lw-primary-soft); color: var(--lw-primary); font-size: 11.5px; font-weight: 700; }
    .hl-progress-grid { display: grid; grid-template-columns: 1.35fr 1fr; gap: 20px; align-items: stretch; }
    .hl-metric-big { font-size: clamp(36px, 4vw, 52px); font-weight: 800; line-height: 1; letter-spacing: -1.5px; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .hl-metric-label { margin-top: 6px; font-size: 11px; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; font-weight: 700; }
    .hl-metric-sub { margin-top: 4px; font-size: 11.5px; color: var(--lw-text-2); }
    .hl-stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-top: 16px; }
    .hl-stat { padding: 11px 12px; border-radius: 14px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .hl-stat .n { font-size: 17px; font-weight: 800; color: var(--lw-text); }
    .hl-stat .l { font-size: 10px; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }
    .hl-status-board { background: linear-gradient(180deg, rgba(255,255,255,.82), rgba(255,255,255,.64)); border: 1px solid var(--lw-border); border-radius: 16px; padding: 18px; backdrop-filter: blur(10px); }
    html.dark-mode .hl-status-board { background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.03)); }
    .hl-status-title { font-size: 12px; font-weight: 700; color: var(--lw-text); display: inline-flex; align-items: center; gap: 7px; }
    .hl-status-title i { color: var(--lw-primary); }
    .hl-status-bar { margin-top: 14px; height: 12px; border-radius: 999px; background: var(--lw-bg); overflow: hidden; display: flex; }
    .hl-status-bar span { transition: width .8s cubic-bezier(.22,.61,.36,1); }
    .hl-status-legend { display: grid; gap: 10px; margin-top: 14px; }
    .hl-status-item { display: flex; align-items: center; justify-content: space-between; gap: 10px; font-size: 11.5px; color: var(--lw-text-2); }
    .hl-status-item strong { color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .hl-status-item .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 7px; }
    .hl-kpis { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 22px; }
    .hl-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .hl-card { position: relative; overflow: hidden; background: linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,255,255,.7)); border: 1px solid var(--lw-border); border-radius: 18px; box-shadow: var(--lw-shadow); padding: 18px; display: flex; flex-direction: column; gap: 14px; transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
    html.dark-mode .hl-card { background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .hl-card:hover { transform: translateY(-4px); box-shadow: var(--lw-shadow-lg); border-color: var(--lw-primary-border); }
    .hl-card::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 4px; background: var(--lw-grad); opacity: 0; transition: opacity .2s ease; }
    .hl-card:hover::before { opacity: 1; }
    .hl-card.locked { opacity: .76; }
    .hl-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .hl-avatar { width: 48px; height: 48px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .hl-avatar.c0 { background: linear-gradient(135deg,#2563eb,#60a5fa); }
    .hl-avatar.c1 { background: linear-gradient(135deg,#16a34a,#4ade80); }
    .hl-avatar.c2 { background: linear-gradient(135deg,#d97706,#fbbf24); }
    .hl-avatar.c3 { background: linear-gradient(135deg,#7c3aed,#a78bfa); }
    .hl-avatar.c4 { background: linear-gradient(135deg,#db2777,#f472b6); }
    .hl-name { font-size: 15px; font-weight: 800; color: var(--lw-text); margin: 0; line-height: 1.35; }
    .hl-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
    .hl-ranking { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .hl-podium { min-width: 92px; padding: 12px; border-radius: 16px; text-align: center; border: 1px solid var(--lw-border); background: var(--lw-bg); }
    .hl-podium .icon { font-size: 22px; line-height: 1; }
    .hl-podium .rank { margin-top: 6px; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-podium .val { margin-top: 3px; font-size: 18px; font-weight: 800; color: var(--lw-text); }
    .hl-podium.gold { background: linear-gradient(180deg, #fff8db, #fef3c7); border-color: rgba(217,119,6,.28); }
    .hl-podium.silver { background: linear-gradient(180deg, #f8fafc, #e2e8f0); border-color: rgba(148,163,184,.28); }
    .hl-podium.bronze { background: linear-gradient(180deg, #ffedd5, #fed7aa); border-color: rgba(194,120,59,.28); }
    .hl-rank-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border-radius: 14px; background: var(--lw-bg); border: 1px solid var(--lw-border); font-size: 14px; font-weight: 800; color: var(--lw-text); }
    .hl-score-panel { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .hl-score-box { padding: 12px; border-radius: 14px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .hl-score-box .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-score-box .v { margin-top: 4px; font-size: 17px; font-weight: 800; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .hl-actions { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-top: 12px; border-top: 1px solid var(--lw-border); }
    .hl-actions-left { display: flex; gap: 7px; }
    .hl-btn-icon { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); transition: all .2s ease; position: relative; overflow: hidden; }
    .hl-btn-icon:hover { transform: translateY(-1px); border-color: var(--lw-primary-border); color: var(--lw-primary); box-shadow: var(--lw-shadow); }
    .hl-btn-icon.warn:hover { color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .hl-btn-icon.danger:hover { color: var(--lw-red); border-color: var(--lw-red-border); }
    .hl-btn-icon.off { opacity: .45; cursor: not-allowed; }
    .hl-btn-icon.off:hover { transform: none; box-shadow: none; color: var(--lw-text-2); border-color: var(--lw-border); }
    .hl-pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 20px; }
    .hl-pager-info { font-size: 12px; color: var(--lw-text-3); }
    .hl-pager-info b { color: var(--lw-text); }
    .hl-pager-wrap .pagination { margin: 0; }
    @media (max-width: 1399.98px) { .hl-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 1199.98px) { .hl-kpis { grid-template-columns: repeat(3, 1fr); } .hl-progress-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) { .hl-grid, .hl-kpis, .hl-stats, .hl-score-panel { grid-template-columns: 1fr; } .hl-progress, .lw-hero { padding-left: 16px; padding-right: 16px; } .hl-ranking { flex-direction: column; align-items: stretch; } .hl-podium { width: 100%; } }
</style>
@endpush

@section('content')
@include('component.admin.lomba-workspace')

@php
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $activeHaflah = \App\Models\HaflatulImtihan::find(session('haflah_id'));
    $allResults = $hasilLombas->getCollection();

    $cards = $allResults->map(function ($hasil) {
        $pl = $hasil->pesertaLomba;
        $isIndividu = $pl->isIndividu();
        $nama = $isIndividu
            ? ($pl->student->user->name ?? $pl->student->nama ?? '-')
            : ($pl->kelompokLomba->nama_kelompok ?? '-');
        $peringkat = (int) ($hasil->peringkat ?? 0);
        $juara = trim((string) ($hasil->juara ?? ''));
        $locked = (bool) $hasil->is_haflah_selesai;
        $finalGap = (float) ($hasil->total_nilai ?? 0) - (float) ($hasil->total_dari_penilaian ?? 0);
        if ($locked) {
            $status = ['label' => 'Terkunci', 'cls' => 'red', 'ic' => 'bi-lock-fill'];
        } elseif ($finalGap === 0.0 && $hasil->total_nilai > 0) {
            $status = ['label' => 'Sudah Final', 'cls' => 'navy', 'ic' => 'bi-patch-check-fill'];
        } elseif (($hasil->total_nilai ?? 0) > 0) {
            $status = ['label' => 'Siap Diumumkan', 'cls' => 'green', 'ic' => 'bi-megaphone-fill'];
        } else {
            $status = ['label' => 'Belum Sinkron', 'cls' => 'slate', 'ic' => 'bi-hourglass-split'];
        }

        if (stripos($juara, 'Juara 1') !== false || $peringkat === 1) {
            $podium = ['cls' => 'gold', 'icon' => 'bi-trophy-fill', 'label' => 'Juara 1'];
        } elseif (stripos($juara, 'Juara 2') !== false || $peringkat === 2) {
            $podium = ['cls' => 'silver', 'icon' => 'bi-award-fill', 'label' => 'Juara 2'];
        } elseif (stripos($juara, 'Juara 3') !== false || $peringkat === 3) {
            $podium = ['cls' => 'bronze', 'icon' => 'bi-award', 'label' => 'Juara 3'];
        } else {
            $podium = null;
        }

        return [
            'id' => $hasil->id,
            'isIndividu' => $isIndividu,
            'nama' => $nama,
            'inisial' => mb_strtoupper(mb_substr(trim($nama), 0, 1)),
            'avaIdx' => (mb_ord(mb_substr(trim($nama), 0, 1)) ?? 0) % 5,
            'lomba' => $hasil->lomba->nama ?? '-',
            'nilaiPenilaian' => (float) ($hasil->total_dari_penilaian ?? 0),
            'totalNilai' => (float) ($hasil->total_nilai ?? 0),
            'peringkat' => $peringkat,
            'juara' => $juara ?: 'Finalis',
            'status' => $status,
            'podium' => $podium,
            'locked' => $locked,
            'query' => mb_strtolower($nama . ' ' . ($hasil->lomba->nama ?? '') . ' ' . $juara),
        ];
    });

    $totalHasil = $cards->count();
    $individuCount = $cards->where('isIndividu', true)->count();
    $kelompokCount = $cards->where('isIndividu', false)->count();
    $juara1Count = $cards->filter(fn($c) => $c['peringkat'] === 1)->count();
    $juara2Count = $cards->filter(fn($c) => $c['peringkat'] === 2)->count();
    $juara3Count = $cards->filter(fn($c) => $c['peringkat'] === 3)->count();
    $finalisCount = $cards->filter(fn($c) => $c['peringkat'] > 3)->count();
    $lockedCount = $cards->filter(fn($c) => $c['locked'])->count();
    $finalCount = $cards->filter(fn($c) => $c['status']['label'] === 'Sudah Final')->count();
    $readyCount = $cards->filter(fn($c) => $c['status']['label'] === 'Siap Diumumkan')->count();
    $syncCount = $cards->filter(fn($c) => $c['status']['label'] === 'Belum Sinkron')->count();
    $uniqueLomba = $cards->pluck('lomba')->filter()->unique()->count();
    $progress = $totalHasil > 0 ? round((($finalCount + $readyCount) / $totalHasil) * 100) : 0;
@endphp

<div class="lw-mod hl-mod">
    <div class="lw-hero">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-trophy-fill"></i></span>
                <div>
                    <h1 class="lw-hero-title">Hasil Lomba</h1>
                    <p class="lw-hero-sub">Competition Results Dashboard — verifikasi ranking, sinkronisasi hasil, dan tetapkan juara akhir Haflatul Imtihan dengan cepat.</p>
                    <div class="lw-hero-badges">
                        <span class="lw-hero-badge"><i class="bi bi-diagram-3"></i> Haflah {{ optional($activeHaflah)->nama_acara ?: 'belum dipilih' }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-calendar-event"></i>{{ $today }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-bar-chart"></i>{{ $uniqueLomba }} lomba final</span>
                    </div>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('hasil-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-stars"></i> Generate Hasil</a>
                <a href="{{ route('hasil-lomba.index', request()->query()) }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="hl-progress">
        <div class="hl-progress-top">
            <div>
                <h2><i class="bi bi-graph-up-arrow"></i> Progress Penentuan Juara</h2>
                <div class="hl-progress-sub">Pantau kesiapan hasil final sebelum diumumkan ke publik.</div>
            </div>
            <span class="hl-progress-chip"><i class="bi bi-clipboard-data"></i> Progress <b data-count="{{ $progress }}">{{ $progress }}</b>%</span>
        </div>
        <div class="hl-progress-grid">
            <div>
                <div class="hl-metric-big" data-count="{{ $totalHasil }}">{{ $totalHasil }}</div>
                <div class="hl-metric-label">Total Hasil Final</div>
                <div class="hl-metric-sub">{{ $uniqueLomba }} lomba • {{ $juara1Count }} juara utama • {{ $lockedCount }} hasil terkunci</div>
                <div class="hl-stats">
                    <div class="hl-stat"><div class="n" data-count="{{ $uniqueLomba }}">{{ $uniqueLomba }}</div><div class="l">Total Lomba</div></div>
                    <div class="hl-stat"><div class="n" data-count="{{ $juara1Count + $juara2Count + $juara3Count }}">{{ $juara1Count + $juara2Count + $juara3Count }}</div><div class="l">Total Juara</div></div>
                    <div class="hl-stat"><div class="n" data-count="{{ $individuCount }}">{{ $individuCount }}</div><div class="l">Individu</div></div>
                    <div class="hl-stat"><div class="n" data-count="{{ $kelompokCount }}">{{ $kelompokCount }}</div><div class="l">Kelompok</div></div>
                    <div class="hl-stat"><div class="n" data-count="{{ $finalCount + $readyCount }}">{{ $finalCount + $readyCount }}</div><div class="l">Siap Final</div></div>
                </div>
            </div>
            <div class="hl-status-board">
                <div class="hl-status-title"><i class="bi bi-broadcast-pin"></i> Status Hasil</div>
                <div class="hl-status-bar" id="hlStatusBar">
                    <span style="width:0;background:var(--lw-green);" data-w="{{ $totalHasil ? ($readyCount / $totalHasil) * 100 : 0 }}"></span>
                    <span style="width:0;background:var(--lw-primary);" data-w="{{ $totalHasil ? ($finalCount / $totalHasil) * 100 : 0 }}"></span>
                    <span style="width:0;background:var(--lw-violet);" data-w="{{ $totalHasil ? ($lockedCount / $totalHasil) * 100 : 0 }}"></span>
                    <span style="width:0;background:var(--lw-text-3);" data-w="{{ $totalHasil ? ($syncCount / $totalHasil) * 100 : 0 }}"></span>
                </div>
                <div class="hl-status-legend">
                    <div class="hl-status-item"><span><i class="dot" style="background:var(--lw-green);"></i>Siap Diumumkan</span><strong data-count="{{ $readyCount }}">{{ $readyCount }}</strong></div>
                    <div class="hl-status-item"><span><i class="dot" style="background:var(--lw-primary);"></i>Sudah Final</span><strong data-count="{{ $finalCount }}">{{ $finalCount }}</strong></div>
                    <div class="hl-status-item"><span><i class="dot" style="background:var(--lw-violet);"></i>Haflah Selesai</span><strong data-count="{{ $lockedCount }}">{{ $lockedCount }}</strong></div>
                    <div class="hl-status-item"><span><i class="dot" style="background:var(--lw-text-3);"></i>Belum Sinkron</span><strong data-count="{{ $syncCount }}">{{ $syncCount }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    @php
        $tabParams = request()->except('tab');
        $tabIndividuUrl = route('hasil-lomba.index', array_merge($tabParams, ['tab' => 'individu']));
        $tabKelompokUrl = route('hasil-lomba.index', array_merge($tabParams, ['tab' => 'kelompok']));
    @endphp
    <div class="lw-tabs lw-tab-pill" id="hlTabs">
        <a href="{{ $tabIndividuUrl }}" class="lw-tab {{ $tab === 'individu' ? 'active' : '' }}"><i class="bi bi-person"></i> Individu <span class="lw-badge-count">{{ $individuCount }}</span></a>
        <a href="{{ $tabKelompokUrl }}" class="lw-tab {{ $tab === 'kelompok' ? 'active' : '' }}"><i class="bi bi-people"></i> Kelompok <span class="lw-badge-count">{{ $kelompokCount }}</span></a>
    </div>

    <form id="hasilFilter" method="GET" autocomplete="off" class="lw-toolbar hl-toolbar">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="lw-filter lw-filter--perpage"><label>Per Page</label>
            <select name="per_page" class="lw-select">
                @foreach ([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Filter Lomba</label>
            <select name="lomba_id" class="lw-select">
                <option value="">Semua Lomba</option>
                @foreach($lombas as $l)
                <option value="{{ $l->id }}" {{ request('lomba_id')==$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Filter Juara</label>
            <select name="juara" class="lw-select">
                <option value="">Semua Juara</option>
                @foreach($juaraList as $j)
                <option value="{{ $j }}" {{ request('juara')==$j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-search"><i class="bi bi-search"></i>
            <input type="search" name="nama" value="{{ request('nama') }}" class="lw-control" placeholder="Cari lomba, peserta, kelompok..." aria-label="Cari hasil lomba">
        </div>
        <div class="lw-toolbar-actions">
            <a href="{{ route('hasil-lomba.index', ['tab' => $tab]) }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset Filter</a>
            <a href="{{ route('hasil-lomba.index', request()->query()) }}" class="lw-btn lw-btn--soft"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
        </div>
    </form>

    @if($totalHasil === 0)
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-trophy"></i></div></div>
            <div class="lw-empty-title">Belum ada hasil lomba</div>
            <div class="lw-empty-sub">Generate hasil pertama dari penilaian lomba agar operator dapat memverifikasi ranking, juara, dan status final dengan cepat.</div>
            <a href="{{ route('hasil-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-stars"></i> Generate Hasil Pertama</a>
        </div>
    @else
        <div class="hl-grid">
            @foreach($cards as $card)
            <article class="hl-card {{ $card['locked'] ? 'locked' : '' }}">
                <div class="hl-card-top">
                    <div class="d-flex gap-3 min-w-0">
                        <span class="hl-avatar c{{ $card['avaIdx'] }}">{{ $card['inisial'] ?: ($card['isIndividu'] ? 'P' : 'K') }}</span>
                        <div class="min-w-0">
                            <h3 class="hl-name" title="{{ $card['nama'] }}">{{ $card['nama'] }}</h3>
                            <div class="hl-meta">
                                <span class="lw-chip lw-chip--slate"><i class="bi bi-trophy"></i>{{ $card['lomba'] }}</span>
                                <span class="lw-chip lw-chip--{{ $card['status']['cls'] }}"><i class="bi {{ $card['status']['ic'] }}"></i>{{ $card['status']['label'] }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="lw-chip lw-chip--{{ $card['isIndividu'] ? 'navy' : 'violet' }}"><i class="bi {{ $card['isIndividu'] ? 'bi-person' : 'bi-people' }}"></i>{{ $card['isIndividu'] ? 'Individu' : 'Kelompok' }}</span>
                </div>

                <div class="hl-ranking">
                    @if($card['podium'])
                    <div class="hl-podium {{ $card['podium']['cls'] }}">
                        <div class="icon"><i class="bi {{ $card['podium']['icon'] }}"></i></div>
                        <div class="rank">{{ $card['podium']['label'] }}</div>
                        <div class="val">#{{ $card['peringkat'] }}</div>
                    </div>
                    @else
                    <span class="hl-rank-badge">#{{ $card['peringkat'] ?: '-' }}</span>
                    @endif
                    <div class="w-100">
                        <div class="lw-chip lw-chip--green" style="width:max-content;"><i class="bi bi-patch-check"></i>{{ $card['juara'] }}</div>
                    </div>
                </div>

                <div class="hl-score-panel">
                    <div class="hl-score-box"><div class="k">Total Nilai</div><div class="v">{{ number_format($card['totalNilai'], 1) }}</div></div>
                    <div class="hl-score-box"><div class="k">Nilai Penilaian</div><div class="v">{{ number_format($card['nilaiPenilaian'], 1) }}</div></div>
                </div>

                <div class="hl-actions">
                    <div class="hl-actions-left">
                        <a href="{{ route('hasil-lomba.show', $card['id']) }}" class="hl-btn-icon" title="Detail Hasil" aria-label="Detail"><i class="bi bi-eye"></i></a>
                        @if($card['locked'])
                            <span class="hl-btn-icon warn off" title="Haflah selesai — sinkronisasi terkunci" aria-label="Terkunci"><i class="bi bi-lock-fill"></i></span>
                            <span class="hl-btn-icon danger off" title="Haflah selesai — hapus dinonaktifkan" aria-label="Terkunci"><i class="bi bi-lock-fill"></i></span>
                        @else
                            <a href="{{ route('hasil-lomba.edit', $card['id']) }}" class="hl-btn-icon warn" title="Sinkronisasi Ulang" aria-label="Sinkronisasi"><i class="bi bi-arrow-repeat"></i></a>
                            <button type="button" class="hl-btn-icon danger" title="Hapus Hasil" aria-label="Hapus"
                                data-hl-delete
                                data-nama="{{ $card['nama'] }}"
                                data-url="{{ route('hasil-lomba.destroy', $card['id']) }}"><i class="bi bi-trash"></i></button>
                        @endif
                    </div>
                    <span class="lw-chip lw-chip--slate"><i class="bi bi-flag"></i>Rank {{ $card['peringkat'] ?: '-' }}</span>
                </div>
            </article>
            @endforeach
        </div>

        <div class="hl-pager">
            <div class="hl-pager-info">Menampilkan <b>{{ $hasilLombas->firstItem() }}</b>–<b>{{ $hasilLombas->lastItem() }}</b> dari <b>{{ $hasilLombas->total() }}</b> hasil</div>
            @if($hasilLombas->hasPages())
            <div class="hl-pager-wrap">{{ $hasilLombas->onEachSide(1)->links() }}</div>
            @endif
        </div>
    @endif

    <form id="hlDeleteForm" method="POST" action="" class="d-none">
        @csrf @method('DELETE')
    </form>

    <a href="{{ route('hasil-lomba.create') }}" class="lw-fab" aria-label="Generate hasil lomba"><i class="bi bi-plus-lg"></i></a>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.hl-mod [data-hl-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var form = document.getElementById('hlDeleteForm');
            if (!form || !window.LW) return;
            form.action = btn.getAttribute('data-url');
            LW.confirmForm(form, 'Hapus Hasil Lomba?', 'Data hasil "' + btn.getAttribute('data-nama') + '" akan dihapus permanen dan tidak dapat dikembalikan.', 'bi-trash3-fill');
        });
    });

    setTimeout(function () {
        document.querySelectorAll('#hlStatusBar span[data-w]').forEach(function (s) { s.style.width = s.dataset.w + '%'; });
    }, 120);

    document.querySelectorAll('.hl-mod [data-count]').forEach(function (el) {
        if (window.LW && LW.counter) LW.counter(el);
    });

    var form = document.getElementById('hasilFilter');
    if (form) {
        function applyFilter() {
            var params = new URLSearchParams();
            var data = new FormData(form);
            Array.from(data.entries()).forEach(function(pair) {
                if (pair[1]) params.append(pair[0], pair[1]);
            });
            window.location.search = params.toString();
        }
        form.querySelectorAll('select').forEach(function (el) { el.addEventListener('change', applyFilter); });
        var searchInput = form.querySelector('input[type="search"]');
        if (searchInput) {
            var debounce;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(applyFilter, 350);
            });
        }
    }

    document.querySelectorAll('.hl-mod .lw-btn, .hl-mod .hl-btn-icon, .hl-mod .lw-fab').forEach(function (btn) {
        if (btn.classList.contains('off')) return;
        btn.addEventListener('click', function (e) { if (window.LW) LW.ripple(e); });
    });
});
</script>
@endpush
