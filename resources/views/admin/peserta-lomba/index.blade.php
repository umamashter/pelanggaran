@extends('layouts.main')
@section('title', 'Peserta Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .pl-mod .lw-kpi-grid { grid-template-columns: repeat(6, 1fr); }

    .lw-dash { position: relative; overflow: hidden; border-radius: 20px; padding: 24px 26px; margin-bottom: 18px;
        background: linear-gradient(135deg, var(--lw-card), var(--lw-bg)); border: 1px solid var(--lw-border); box-shadow: var(--lw-shadow); }
    .lw-dash::before { content: ""; position: absolute; inset: 0; pointer-events: none; opacity: .55;
        background-image: radial-gradient(rgba(43,60,120,.10) 1px, transparent 1px); background-size: 20px 20px; }
    .lw-dash-inner { position: relative; }
    .lw-dash-top { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .lw-dash-grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: 22px; align-items: stretch; }
    .lw-metric-big { font-size: clamp(38px, 4.5vw, 54px); font-weight: 800; letter-spacing: -1.5px; line-height: 1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .lw-metric-label { font-size: 12px; font-weight: 700; color: var(--lw-text-2); text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }
    .lw-metric-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 3px; }
    .lw-split { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 14px; max-width: 320px; }
    .lw-split-item { display: flex; align-items: center; gap: 9px; padding: 9px 12px; border-radius: 12px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .lw-split-item i { font-size: 15px; }
    .lw-split-item .n { font-size: 17px; font-weight: 800; line-height: 1; color: var(--lw-text); }
    .lw-split-item .l { font-size: 10px; color: var(--lw-text-3); font-weight: 600; margin-top: 2px; }
    .lw-dist { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; padding: 16px 18px; }
    .lw-stack { display: flex; height: 12px; border-radius: 999px; overflow: hidden; background: var(--lw-bg); }
    .lw-stack span { height: 100%; transition: width 1s cubic-bezier(.22,.61,.36,1); }
    .lw-stack-legend { display: flex; flex-wrap: wrap; gap: 8px 14px; margin-top: 12px; }
    .lw-legend { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: var(--lw-text-2); }
    .lw-legend i { font-size: 10px; }
    .lw-legend b { font-variant-numeric: tabular-nums; color: var(--lw-text); }

    .lw-health { margin-bottom: 16px; }
    .lw-health-item { display: grid; grid-template-columns: 200px 1fr 150px; gap: 12px; align-items: center; padding: 11px 14px; border-radius: 13px; background: var(--lw-card); border: 1px solid var(--lw-border); margin-bottom: 8px; transition: all .2s ease; }
    .lw-health-item:hover { border-color: var(--lw-primary-border); transform: translateX(3px); }
    .lw-health-name { font-size: 12.5px; font-weight: 700; color: var(--lw-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .lw-health-name small { display: block; font-size: 10px; color: var(--lw-text-3); font-weight: 600; }
    .lw-health-bar { height: 8px; border-radius: 999px; background: var(--lw-bg); overflow: hidden; position: relative; }
    .lw-health-bar i { display: block; height: 100%; border-radius: 999px; width: 0; transition: width 1s cubic-bezier(.22,.61,.36,1); }
    .lw-health-chip { display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px; font-size: 11px; font-weight: 700; }
    .lw-health-chip .pct { color: var(--lw-text); font-variant-numeric: tabular-nums; }

    .lw-name { display: flex; align-items: center; gap: 11px; min-width: 0; }
    .lw-name-info { min-width: 0; }
    .lw-name-info .nm { font-size: 13.5px; font-weight: 700; color: var(--lw-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .lw-name-info .id { font-size: 11px; color: var(--lw-text-3); font-variant-numeric: tabular-nums; }
    .lw-cell-sub { font-size: 10.5px; color: var(--lw-text-3); margin-top: 1px; }
    .lw-tag { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600; background: var(--lw-bg); color: var(--lw-text-2); white-space: nowrap; }
    .lw-tag i { font-size: 11px; color: var(--lw-primary); }

    .lw-act { display: inline-flex; align-items: center; gap: 4px; }
    .lw-act-btn { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); font-size: 13px; cursor: pointer; transition: all .2s ease; text-decoration: none; position: relative; overflow: hidden; }
    .lw-act-btn:hover { transform: translateY(-1px); box-shadow: var(--lw-shadow); border-color: var(--lw-primary-border); color: var(--lw-primary); }
    .lw-act-btn.edit:hover { color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .lw-act-btn.del:hover { color: var(--lw-red); border-color: var(--lw-red-border); }
    .lw-act-btn.is-off { opacity: .4; cursor: not-allowed; }
    .lw-act-btn.is-off:hover { transform: none; box-shadow: none; color: var(--lw-text-2); }

    .lw-team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .lw-team-card { position: relative; overflow: hidden; border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); box-shadow: var(--lw-shadow); padding: 16px; display: flex; flex-direction: column; gap: 11px; transition: all .22s ease; }
    .lw-team-card:hover { border-color: var(--lw-primary-border); transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); }
    .lw-team-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--lw-grad); opacity: 0; transition: opacity .2s; }
    .lw-team-card:hover::before { opacity: 1; }
    .lw-team-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .lw-team-top h3 { font-size: 14px; font-weight: 800; margin: 0; color: var(--lw-text); }
    .lw-team-code { font-size: 10px; font-weight: 700; color: var(--lw-text-3); background: var(--lw-bg); padding: 3px 8px; border-radius: 7px; letter-spacing: .4px; }
    .lw-team-meta { font-size: 11.5px; color: var(--lw-text-3); display: flex; align-items: center; gap: 6px; }
    .lw-team-meta i { font-size: 12px; color: var(--lw-primary); }
    .lw-team-members { display: flex; align-items: center; }
    .lw-team-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 7px; }
    .lw-team-stat { text-align: center; padding: 8px 6px; border-radius: 10px; background: var(--lw-bg); }
    .lw-team-stat .v { font-size: 15px; font-weight: 800; color: var(--lw-text); line-height: 1; font-variant-numeric: tabular-nums; }
    .lw-team-stat .l { font-size: 9px; color: var(--lw-text-3); margin-top: 3px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
    .lw-team-foot { display: flex; gap: 8px; border-top: 1px dashed var(--lw-border); padding-top: 10px; }
    .lw-team-foot .lw-act-btn { flex: 1; width: auto; }
    .lw-ava-sm { width: 30px; height: 30px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 10.5px; font-weight: 800; color: #fff; margin-left: -8px; border: 2px solid var(--lw-card); }
    .lw-ava-sm:first-child { margin-left: 0; }
    .lw-ava-more { width: 30px; height: 30px; border-radius: 10px; margin-left: -8px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; background: var(--lw-bg); color: var(--lw-text-2); border: 2px solid var(--lw-card); }

    @media (max-width: 1399.98px) { .pl-mod .lw-kpi-grid { grid-template-columns: repeat(3, 1fr); } .lw-team-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 991.98px) { .lw-dash-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) {
        .pl-mod .lw-kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .lw-team-grid { grid-template-columns: 1fr; }
        .lw-health-item { grid-template-columns: 1fr; gap: 8px; }
        .lw-health-chip { justify-content: flex-start; }
        .lw-dash { padding: 18px 16px; }
    }
</style>

<div class="lw-mod pl-mod jd-page-peserta">

@php
    $tab = request('tab', 'individu');
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $selectedHaflah = request()->filled('haflah_id') ? request('haflah_id') : session('haflah_id');
    $activeHaflah = $haflatuls->firstWhere('id', $selectedHaflah) ?? $haflatuls->firstWhere('id', session('haflah_id'));

    $statusMeta = [
        'Terdaftar'     => ['cls' => 'lw-chip--navy',  'ic' => 'bi-person-check-fill', 'tone' => 'var(--lw-primary)'],
        'Hadir'         => ['cls' => 'lw-chip--green', 'ic' => 'bi-check-circle-fill', 'tone' => 'var(--lw-green)'],
        'Tidak Hadir'   => ['cls' => 'lw-chip--red',   'ic' => 'bi-x-circle-fill',     'tone' => 'var(--lw-red)'],
        'Diskualifikasi'=> ['cls' => 'lw-chip--amber', 'ic' => 'bi-slash-circle-fill', 'tone' => 'var(--lw-amber)'],
    ];

    $qBase = \App\Models\PesertaLomba::withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)
        ->whereNotNull('student_id')
        ->whereHas('lomba', function ($q) {
            $q->withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)
              ->whereNull('jenis')->orWhere('jenis', '!=', 'Tim');
        });
    if ($selectedHaflah) { $qBase->where('haflah_id', $selectedHaflah); }

    $aggStatus = $qBase->clone()->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
    $hadir = (int)($aggStatus['Hadir'] ?? 0);
    $tdkHadir = (int)($aggStatus['Tidak Hadir'] ?? 0);
    $diskualifikasi = (int)($aggStatus['Diskualifikasi'] ?? 0);
    $terdaftar = (int)($aggStatus['Terdaftar'] ?? 0);
    $totalIndividu = array_sum($aggStatus->all());

    $kq = \App\Models\KelompokLomba::query();
    if ($selectedHaflah) { $kq->withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)->where('haflah_id', $selectedHaflah); }
    $totalKelompok = $kq->count();

    $totalPeserta = $totalIndividu + $totalKelompok;

    $perLomba = $qBase->clone()->selectRaw('lomba_id, count(*) as c')->groupBy('lomba_id')->pluck('c', 'lomba_id');
    $allLombas = \App\Models\Lomba::withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)
        ->where(function ($q) { $q->whereNull('jenis')->orWhere('jenis', '!=', 'Tim'); })
        ->when($selectedHaflah, fn($q) => $q->where('haflah_id', $selectedHaflah))
        ->orderBy('nama')->get();
    $kelasTingkat = \App\Models\Kelas::pluck('tingkat', 'id');
    $studentPerKelas = \App\Models\StudentKelas::where('aktif', true)
        ->selectRaw('kelas_id, count(*) as c')->groupBy('kelas_id')->pluck('c', 'kelas_id');
    $lombaEligible = $allLombas->mapWithKeys(function ($l) use ($kelasTingkat, $studentPerKelas) {
        $min = $l->kelas_min !== null && $l->kelas_min !== '' ? (int)$l->kelas_min : null;
        $max = $l->kelas_max !== null && $l->kelas_max !== '' ? (int)$l->kelas_max : null;
        $n = 0;
        foreach ($studentPerKelas as $kelasId => $cnt) {
            $t = $kelasTingkat[$kelasId] ?? null;
            if ($t === null) continue;
            $t = (int)$t;
            if (($min === null || $t >= $min) && ($max === null || $t <= $max)) { $n += (int)$cnt; }
        }
        return [$l->id => $n];
    });
    $lombaTerdaftar = $allLombas->filter(fn($l) => (int)($perLomba[$l->id] ?? 0) > 0)->count();
    $progressLomba = $allLombas->count() > 0 ? round($lombaTerdaftar / $allLombas->count() * 100) : 0;

    $health = $allLombas->map(function ($l) use ($perLomba, $lombaEligible) {
        $reg = (int)($perLomba[$l->id] ?? 0);
        $elig = (int)($lombaEligible[$l->id] ?? 0);
        $name = $l->nama;
        if ($elig === 0)      return ['name' => $name, 'cls' => 'lw-chip--slate', 'ic' => 'bi-question-circle-fill', 'label' => 'Belum ada siswa', 'pct' => 0, 'reg' => $reg, 'elig' => $elig, 'bar' => 'var(--lw-text-3)'];
        if ($reg === 0)       return ['name' => $name, 'cls' => 'lw-chip--red', 'ic' => 'bi-x-circle-fill',        'label' => 'Belum ada peserta', 'pct' => 0, 'reg' => $reg, 'elig' => $elig, 'bar' => 'var(--lw-red)'];
        if ($reg >= $elig)    return ['name' => $name, 'cls' => 'lw-chip--green', 'ic' => 'bi-check-circle-fill',  'label' => 'Lengkap', 'pct' => 100, 'reg' => $reg, 'elig' => $elig, 'bar' => 'var(--lw-green)'];
        return ['name' => $name, 'cls' => 'lw-chip--amber', 'ic' => 'bi-exclamation-triangle-fill', 'label' => 'Sebagian', 'pct' => round($reg / $elig * 100), 'reg' => $reg, 'elig' => $elig, 'bar' => 'var(--lw-amber)'];
    });
@endphp

<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-trophy-fill"></i></span>
            <div>
                <h1 class="lw-hero-title">Peserta Lomba</h1>
                <p class="lw-hero-sub">Participant Management — daftarkan peserta individu &amp; tim, pantau status kehadiran, dan kelola pendaftaran Haflatul Imtihan.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-calendar3"></i>{{ optional($activeHaflah)->nama_acara ?? 'Haflah belum dipilih' }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-clock"></i>{{ $today }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-people-fill"></i>{{ $totalPeserta }} peserta</span>
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('peserta-lomba.create') }}" class="lw-btn lw-btn--accent"><i class="bi bi-person-plus-fill"></i> Tambah Peserta</a>
            <a href="{{ route('peserta-lomba.massal') }}" class="lw-btn lw-btn--light"><i class="bi bi-layers-fill"></i> Tambah Massal</a>
            <a href="{{ route('peserta-lomba.cetak-pdf', request()->query()) }}" class="lw-btn lw-btn--light" title="Export PDF" target="_blank"><i class="bi bi-filetype-pdf"></i></a>
            <a href="{{ route('peserta-lomba.index') }}" class="lw-btn lw-btn--light" title="Refresh"><i class="bi bi-arrow-clockwise"></i></a>
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
    <div class="lw-alert lw-alert--accent"><i class="bi bi-info-circle-fill"></i> {{ session('info') }}</div>
@endif

<div class="lw-dash">
    <div class="lw-dash-inner">
        <div class="lw-dash-top">
            <div>
                <div class="lw-section-title"><i class="bi bi-graph-up-arrow"></i> Registration Progress Dashboard</div>
                <div class="lw-section-sub" style="margin-bottom:0;">Ringkasan pendaftaran {{ optional($activeHaflah)->nama_acara ?? 'Haflah Aktif' }}</div>
            </div>
            <span class="lw-chip lw-chip--glow"><i class="bi bi-kanban"></i> Progress pendaftaran <b style="margin-left:5px;" data-count="{{ $progressLomba }}">{{ $progressLomba }}</b>%</span>
        </div>
        <div class="lw-dash-grid">
            <div>
                <div>
                    <div class="lw-metric-big" data-count="{{ $totalPeserta }}">{{ $totalPeserta }}</div>
                    <div class="lw-metric-label">Total Peserta Terdaftar</div>
                    <div class="lw-metric-sub">{{ $totalIndividu }} individu &middot; {{ $totalKelompok }} kelompok</div>
                </div>
                <div class="lw-split">
                    <div class="lw-split-item"><i class="bi bi-person-fill" style="color:var(--lw-primary);"></i><div><div class="n" data-count="{{ $totalIndividu }}">{{ $totalIndividu }}</div><div class="l">Individu</div></div></div>
                    <div class="lw-split-item"><i class="bi bi-people-fill" style="color:var(--lw-violet);"></i><div><div class="n" data-count="{{ $totalKelompok }}">{{ $totalKelompok }}</div><div class="l">Kelompok</div></div></div>
                </div>
            </div>
            <div class="lw-dist">
                <div class="lw-section-title" style="font-size:12px;"><i class="bi bi-bar-chart-fill"></i> Distribusi Status Peserta</div>
                <div class="lw-stack" id="statusStack">
                    <span style="background:var(--lw-primary);width:0%;" data-w="{{ $totalIndividu ? $terdaftar / $totalIndividu * 100 : 0 }}"></span>
                    <span style="background:var(--lw-green);width:0%;" data-w="{{ $totalIndividu ? $hadir / $totalIndividu * 100 : 0 }}"></span>
                    <span style="background:var(--lw-red);width:0%;" data-w="{{ $totalIndividu ? $tdkHadir / $totalIndividu * 100 : 0 }}"></span>
                    <span style="background:var(--lw-amber);width:0%;" data-w="{{ $totalIndividu ? $diskualifikasi / $totalIndividu * 100 : 0 }}"></span>
                </div>
                <div class="lw-stack-legend">
                    <span class="lw-legend"><i class="bi bi-circle-fill" style="color:var(--lw-primary);"></i>Terdaftar <b data-count="{{ $terdaftar }}">{{ $terdaftar }}</b></span>
                    <span class="lw-legend"><i class="bi bi-circle-fill" style="color:var(--lw-green);"></i>Hadir <b data-count="{{ $hadir }}">{{ $hadir }}</b></span>
                    <span class="lw-legend"><i class="bi bi-circle-fill" style="color:var(--lw-red);"></i>Tdk Hadir <b data-count="{{ $tdkHadir }}">{{ $tdkHadir }}</b></span>
                    <span class="lw-legend"><i class="bi bi-circle-fill" style="color:var(--lw-amber);"></i>Diskualifikasi <b data-count="{{ $diskualifikasi }}">{{ $diskualifikasi }}</b></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lw-tabs" role="tablist" style="margin-bottom:14px;">
    <a href="{{ route('peserta-lomba.index', array_merge(request()->except('tab'), ['tab' => 'individu'])) }}" class="lw-tab {{ $tab !== 'kelompok' ? 'active' : '' }}" role="tab"><i class="bi bi-person-fill"></i> Individu <span class="lw-badge-count">{{ $pesertaLombas->total() }}</span></a>
    <a href="{{ route('peserta-lomba.index', array_merge(request()->except('tab'), ['tab' => 'kelompok'])) }}" class="lw-tab {{ $tab === 'kelompok' ? 'active' : '' }}" role="tab"><i class="bi bi-people-fill"></i> Kelompok <span class="lw-badge-count">{{ $kelompoks->total() }}</span></a>
</div>

@if($tab !== 'kelompok')

@if($health->isNotEmpty())
<div class="lw-health">
    <div class="lw-section-title" style="margin-bottom:10px;"><i class="bi bi-activity"></i> Registration Health</div>
    @foreach($health->take(8) as $h)
        <div class="lw-health-item">
            <div class="lw-health-name">{{ $h['name'] }}<small><i class="bi bi-people-fill"></i> {{ $h['elig'] }} siswa eligible</small></div>
            <div class="lw-health-bar"><i data-w="{{ $h['pct'] }}" style="background:{{ $h['bar'] }};"></i></div>
            <div class="lw-health-chip"><span class="lw-chip {{ $h['cls'] }} lw-chip-mini"><i class="bi {{ $h['ic'] }}"></i>{{ $h['label'] }}</span><span class="pct">{{ $h['reg'] }}/{{ $h['elig'] }}</span></div>
        </div>
    @endforeach
    @if($health->count() > 8)
        <div class="text-end" style="margin-top:6px;"><button type="button" class="lw-btn lw-btn--sm lw-btn--ghost" id="healthExpand"><i class="bi bi-chevron-down"></i> Tampilkan semua ({{ $health->count() }} lomba)</button></div>
    @endif
</div>
@endif

<div class="lw-toolbar" id="pesertaToolbar">
    <form id="pesertaFilter" method="GET" style="display:contents;" autocomplete="off">
        <input type="hidden" name="tab" value="individu">
        <div class="lw-filter"><label>Haflah</label>
            <select name="haflah_id" class="lw-select">
                <option value="">Haflah Aktif</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Sesi</label>
            <select name="sesi_id" class="lw-select">
                <option value="">Semua Sesi</option>
                @foreach($sesiLombas as $sl)
                    <option value="{{ $sl->id }}" {{ request('sesi_id') == $sl->id ? 'selected' : '' }}>{{ $sl->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Lomba</label>
            <select name="lomba_id" class="lw-select">
                <option value="">Semua Lomba</option>
                @foreach($lombas as $lmb)
                    <option value="{{ $lmb->id }}" {{ request('lomba_id') == $lmb->id ? 'selected' : '' }}>{{ $lmb->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Status</label>
            <select name="status" class="lw-select">
                <option value="">Semua</option>
                @foreach(['Terdaftar', 'Hadir', 'Tidak Hadir', 'Diskualifikasi'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Entri</label>
            <select name="per_page" class="lw-select" style="min-width:70px;">
                @foreach([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int) $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-search"><i class="bi bi-search"></i>
            <input type="search" name="nama" value="{{ request('nama') }}" class="lw-control" placeholder="Cari nama peserta..." aria-label="Cari nama">
        </div>
        <div class="lw-toolbar-actions">
            <a href="{{ route('peserta-lomba.index', ['tab' => 'individu']) }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

<div class="lw-card lw-table-card">
    <div class="lw-card-header">
        <div>
            <div class="lw-section-title" style="margin-bottom:2px;"><i class="bi bi-person-fill"></i> Daftar Peserta Individu</div>
            <div class="lw-section-sub" style="margin-bottom:0;font-size:11.5px;">{{ $pesertaLombas->firstItem() ?? 0 }}-{{ $pesertaLombas->lastItem() ?? 0 }} dari {{ $pesertaLombas->total() }} peserta</div>
        </div>
    </div>

    @if($pesertaLombas->isEmpty())
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-person-dash"></i></div></div>
            <div class="lw-empty-title">Belum Ada Peserta Individu</div>
            <p class="lw-empty-sub">Daftarkan peserta pertama untuk memulai pendaftaran lomba.</p>
            <a href="{{ route('peserta-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-person-plus-fill"></i> Tambah Peserta Pertama</a>
        </div>
    @else
        <div class="lw-table-desktop"><div class="table-responsive">
            <table class="table table-lw align-middle">
                <thead><tr>
                    <th>Peserta</th><th>Kelas</th><th>Lomba</th><th>Sesi</th><th>Tanggal</th><th class="text-center">No Urut</th><th>Status</th><th class="text-end">Aksi</th>
                </tr></thead>
                <tbody id="pesertaTbody">
                    @foreach($pesertaLombas as $p)
                        @php
                            $student = $p->student;
                            $userName = $student->user->name ?? $student->nama ?? '-';
                            $nisn = $student->nisn ?? '-';
                            $ini = strtoupper(mb_substr($userName, 0, 1));
                            $kelasNama = $student->kelasAktif->kelas->nama_kelas ?? '-';
                            $jenjangNama = $student->kelasAktif->kelas->jenjang->nama_jenjang ?? '-';
                            $isLocked = $p->is_haflah_selesai;
                            $hasScore = ($p->penilaian_count ?? 0) + ($p->hasil_count ?? 0) > 0;
                            $sm = $statusMeta[$p->status] ?? ['cls' => 'lw-chip--slate', 'ic' => 'bi-circle-fill'];
                            $tgl = optional($p->lomba->sesiLomba)->tanggal ? \Carbon\Carbon::parse($p->lomba->sesiLomba->tanggal)->translatedFormat('d M Y') : '-';
                        @endphp
                        <tr class="{{ $isLocked ? 'is-locked' : '' }}">
                            <td><div class="lw-name"><span class="lw-avatar" style="background:{{ lw_ava_color($userName) }};">{{ $ini }}</span><div class="lw-name-info"><div class="nm">{{ $userName }}</div><div class="id">NISN {{ $nisn }}</div></div></div></td>
                            <td><span class="lw-tag"><i class="bi bi-mortarboard-fill"></i>{{ $kelasNama }}</span><div class="lw-cell-sub">{{ $jenjangNama }}</div></td>
                            <td><div style="font-size:13px;font-weight:600;color:var(--lw-text);">{{ $p->lomba->nama ?? '-' }}</div></td>
                            <td><div class="lw-cell-sub" style="color:var(--lw-text-2);">{{ $p->lomba->sesiLomba->nama ?? '-' }}</div></td>
                            <td><div class="lw-cell-sub" style="color:var(--lw-text-2);">{{ $tgl }}</div></td>
                            <td class="text-center"><span class="lw-tag" style="justify-content:center;min-width:34px;"><b style="color:var(--lw-text);">{{ $p->nomor_urut }}</b></span></td>
                            <td><span class="lw-chip {{ $sm['cls'] }}"><i class="bi {{ $sm['ic'] }}"></i>{{ $p->status }}</span></td>
                            <td class="text-end"><div class="lw-act">
                                <a href="{{ route('peserta-lomba.show', $p->id) }}" class="lw-act-btn" title="Detail"><i class="bi bi-eye"></i></a>
                                @if($isLocked)
                                    <span class="lw-act-btn is-off" title="Haflah telah selesai — data dikunci"><i class="bi bi-lock-fill"></i></span>
                                @elseif($hasScore)
                                    <a href="{{ route('peserta-lomba.edit', $p->id) }}" class="lw-act-btn edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <span class="lw-act-btn is-off" title="Memiliki data penilaian — tidak bisa dihapus"><i class="bi bi-trash"></i></span>
                                @else
                                    <a href="{{ route('peserta-lomba.edit', $p->id) }}" class="lw-act-btn edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <button type="button" class="lw-act-btn del" data-pl-delete data-pl-id="{{ $p->id }}" data-pl-nama="{{ e($userName) }}" title="Hapus"><i class="bi bi-trash"></i></button>
                                @endif
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>

        <div class="lw-mobile-card-list">
            @foreach($pesertaLombas as $p)
                @php
                    $student = $p->student; $userName = $student->user->name ?? $student->nama ?? '-'; $nisn = $student->nisn ?? '-';
                    $ini = strtoupper(mb_substr($userName, 0, 1));
                    $kelasNama = $student->kelasAktif->kelas->nama_kelas ?? '-';
                    $isLocked = $p->is_haflah_selesai;
                    $sm = $statusMeta[$p->status] ?? ['cls' => 'lw-chip--slate', 'ic' => 'bi-circle-fill'];
                @endphp
                <div class="lw-mobile-card {{ $isLocked ? 'locked' : '' }}">
                    <div class="lw-mobile-card-head">
                        <div class="lw-name"><span class="lw-avatar lw-avatar--sm" style="background:{{ lw_ava_color($userName) }};">{{ $ini }}</span><div class="lw-name-info"><div class="nm">{{ $userName }}</div><div class="id">NISN {{ $nisn }}</div></div></div>
                        <span class="lw-chip {{ $sm['cls'] }}"><i class="bi {{ $sm['ic'] }}"></i>{{ $p->status }}</span>
                    </div>
                    <div class="lw-mobile-card-grid">
                        <div class="lw-mobile-card-field"><span class="k">Lomba</span><span class="v">{{ $p->lomba->nama ?? '-' }}</span></div>
                        <div class="lw-mobile-card-field"><span class="k">Kelas</span><span class="v">{{ $kelasNama }}</span></div>
                        <div class="lw-mobile-card-field"><span class="k">Sesi</span><span class="v">{{ $p->lomba->sesiLomba->nama ?? '-' }}</span></div>
                        <div class="lw-mobile-card-field"><span class="k">No Urut</span><span class="v">{{ $p->nomor_urut }}</span></div>
                    </div>
                    <div class="lw-mobile-card-actions">
                        <a href="{{ route('peserta-lomba.show', $p->id) }}" class="lw-btn lw-btn--sm lw-btn--soft"><i class="bi bi-eye"></i> Detail</a>
                        <a href="{{ route('peserta-lomba.edit', $p->id) }}" class="lw-btn lw-btn--sm lw-btn--amber-soft {{ $isLocked ? 'lw-btn-lock' : '' }}"><i class="bi bi-pencil-square"></i> Edit</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="lw-pagi">
            <div class="lw-pagi-info">Menampilkan {{ $pesertaLombas->firstItem() ?? 0 }}-{{ $pesertaLombas->lastItem() ?? 0 }} dari {{ $pesertaLombas->total() }} entri</div>
            <div>{{ $pesertaLombas->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>

@else

<div class="lw-toolbar" id="kelompokToolbar">
    <form id="kelompokFilter" method="GET" style="display:contents;" autocomplete="off">
        <input type="hidden" name="tab" value="kelompok">
        <div class="lw-filter"><label>Haflah</label>
            <select name="haflah_id" class="lw-select">
                <option value="">Haflah Aktif</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select></div>
        <div class="lw-filter"><label>Lomba</label>
            <select name="lomba_id" class="lw-select">
                <option value="">Semua Lomba</option>
                @foreach($lombasKelompok as $lmb)
                    <option value="{{ $lmb->id }}" {{ request('lomba_id') == $lmb->id ? 'selected' : '' }}>{{ $lmb->nama }}</option>
                @endforeach
            </select></div>
        <div class="lw-filter"><label>Entri</label>
            <select name="per_page" class="lw-select" style="min-width:70px;">
                @foreach([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int) $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select></div>
        <div class="lw-toolbar-actions">
            <a href="{{ route('peserta-lomba.index', ['tab' => 'kelompok']) }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

<div class="lw-card lw-card-pad">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-people-fill"></i> Peserta Kelompok</div>
        <a href="{{ route('anggota-kelompok.create') }}" class="lw-btn lw-btn--solid lw-btn--sm"><i class="bi bi-person-plus-fill"></i> Tambah Anggota</a>
    </div>
    @if($kelompoks->isEmpty())
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-people-fill"></i></div></div>
            <div class="lw-empty-title">Belum Ada Peserta Kelompok</div>
            <p class="lw-empty-sub">Buat kelompok melalui modul Kelompok Lomba, lalu daftarkan sebagai peserta tim.</p>
            <a href="{{ route('anggota-kelompok.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-people-fill"></i> Buat Kelompok</a>
        </div>
    @else
        <div class="lw-team-grid">
            @foreach($kelompoks as $kp)
                @php
                    $isLocked = $kp->is_haflah_selesai;
                    $ac = ($kp->anggota_count ?? 0) + ($kp->penilaian_lombas_count ?? 0);
                    $members = $kp->anggota->take(4);
                    $more = max(0, ($kp->anggota_count ?? 0) - 4);
                @endphp
                <div class="lw-team-card">
                    <div class="lw-team-top">
                        <div><h3>{{ $kp->nama_kelompok }}</h3><div class="lw-team-meta"><i class="bi bi-trophy-fill"></i>{{ $kp->lomba->nama ?? '-' }} &middot; {{ $kp->lomba->sesiLomba->nama ?? '-' }}</div></div>
                        @if($kp->kode_kelompok)<span class="lw-team-code">{{ $kp->kode_kelompok }}</span>@endif
                    </div>
                    <div class="lw-team-members">
                        @forelse($members as $idx => $ang)
                            @php $mName = $ang->student->user->name ?? $ang->student->nama ?? '?'; @endphp
                            <span class="lw-ava-sm" style="background:{{ lw_ava_color($mName) }};" title="{{ $mName }}">{{ strtoupper(mb_substr($mName, 0, 1)) }}</span>
                        @empty
                            <span style="font-size:11px;color:var(--lw-text-3);">Belum ada anggota</span>
                        @endforelse
                        @if($more > 0)<span class="lw-ava-more" title="+{{ $more }} anggota lagi">+{{ $more }}</span>@endif
                    </div>
                    <div class="lw-team-stats">
                        <div class="lw-team-stat"><div class="v">{{ $kp->anggota_count }}</div><div class="l">Anggota</div></div>
                        <div class="lw-team-stat"><div class="v">{{ $kp->penilaian_lombas_count }}</div><div class="l">Penilaian</div></div>
                        <div class="lw-team-stat"><div class="v" style="font-size:12px;">{{ $kp->kode_kelompok ?? '-' }}</div><div class="l">Kode</div></div>
                    </div>
                    <div class="lw-team-foot">
                        <a href="{{ route('kelompok-lomba.show', $kp->id) }}" class="lw-act-btn"><i class="bi bi-eye"></i> Detail</a>
                        @if($isLocked || $ac > 0)
                            <span class="lw-act-btn is-off" title="{{ $isLocked ? 'Haflah telah selesai — data dikunci' : 'Memiliki data penilaian' }}"><i class="bi bi-ban"></i></span>
                        @else
                            <a href="{{ route('anggota-kelompok.edit', $kp->id) }}" class="lw-act-btn edit"><i class="bi bi-pencil-square"></i> Edit</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="lw-pagi">
            <div class="lw-pagi-info">Menampilkan {{ $kelompoks->firstItem() ?? 0 }}-{{ $kelompoks->lastItem() ?? 0 }} dari {{ $kelompoks->total() }} entri</div>
            <div>{{ $kelompoks->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>
@endif
</div>

<a href="{{ route('peserta-lomba.create') }}" class="lw-fab" aria-label="Tambah peserta"><i class="bi bi-plus-lg"></i></a>
<form id="plDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var sel = document.querySelectorAll('#pesertaFilter select, #pesertaFilter input[type=search], #kelompokFilter select');
    sel.forEach(function (el) { el.addEventListener('change', function () { this.form.submit(); }); });
    document.querySelectorAll('#pesertaFilter input[name=nama]').forEach(function (el) {
        var t; el.addEventListener('input', function () { clearTimeout(t); t = setTimeout(function () { el.form.submit(); }, 380); });
    });

    document.querySelectorAll('[data-count]').forEach(function (el) {
        if (typeof LW !== 'undefined' && LW.counter) { LW.counter(el); }
    });

    document.querySelectorAll('#statusStack span, .lw-health-bar i').forEach(function (el) {
        var w = parseFloat(el.dataset.w); if (isNaN(w)) w = 0;
        setTimeout(function () { el.style.width = Math.max(w, 0.001) + '%'; }, 200);
    });

    document.querySelectorAll('.lw-act-btn').forEach(function (b) { b.addEventListener('click', function (e) { if (b.classList.contains('is-off')) e.preventDefault(); }); });

    (function staggerIn() {
        document.querySelectorAll('.lw-table-lw tbody tr, .lw-team-card').forEach(function (el, i) {
            el.style.opacity = '0'; el.style.transform = 'translateY(6px)'; el.style.transition = 'opacity .3s ease, transform .3s ease';
            setTimeout(function () { el.style.opacity = '1'; el.style.transform = 'none'; }, 40 + i * 28);
        });
    })();

    var hExpand = document.getElementById('healthExpand');
    if (hExpand) {
        var extra = document.querySelectorAll('.lw-health-item:nth-child(n+4)');
        extra.forEach(function (el, i) { if (i >= 2) el.style.display = 'none'; });
        hExpand.addEventListener('click', function () {
            extra.forEach(function (el, i) { if (i >= 2) el.style.display = ''; });
            hExpand.style.display = 'none';
        });
    }

    document.querySelectorAll('[data-pl-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.plId, nama = btn.dataset.plNama; if (!id) return;
            LW.confirm('Hapus Peserta?', 'Peserta "' + nama + '" akan dihapus permanen dari pendaftaran.', 'bi-trash').then(function (ok) {
                if (ok) { var f = document.getElementById('plDeleteForm'); f.action = '{{ url('peserta-lomba') }}/' + id; f.submit(); }
            });
        });
    });
})();
</script>
@endpush
@endsection
