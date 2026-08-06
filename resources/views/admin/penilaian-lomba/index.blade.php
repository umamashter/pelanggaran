@extends('layouts.main')
@section('title', 'Penilaian Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .pl-mod { --pl-radius: 18px; }

    /* ---------- Readiness Dashboard ---------- */
    .pl-dash { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: var(--pl-radius); box-shadow: var(--lw-shadow); padding: 22px 24px; margin-bottom: 20px; }
    .pl-dash-top { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .pl-dash-top h2 { font-size: 16px; font-weight: 800; color: var(--lw-text); margin: 0; display: flex; align-items: center; gap: 9px; }
    .pl-dash-top h2 i { color: var(--lw-primary); font-size: 18px; }
    .pl-dash-top .sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .pl-progress-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px; border-radius: 999px; font-size: 11.5px; font-weight: 700; background: var(--lw-navy-soft); color: var(--lw-primary); }
    .pl-dash-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 22px; align-items: stretch; }
    .pl-metric-big { font-size: clamp(36px, 4vw, 50px); font-weight: 800; letter-spacing: -1.5px; line-height: 1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .pl-metric-label { font-size: 12px; font-weight: 700; color: var(--lw-text-2); text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }
    .pl-metric-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 3px; }
    .pl-split { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 16px; max-width: 520px; }
    .pl-split-item { display: flex; align-items: center; gap: 9px; padding: 9px 12px; border-radius: 12px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .pl-split-item i { font-size: 15px; }
    .pl-split-item .n { font-size: 17px; font-weight: 800; line-height: 1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .pl-split-item .l { font-size: 10px; color: var(--lw-text-3); font-weight: 600; margin-top: 2px; }
    .pl-readiness { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; padding: 16px 18px; }
    .pl-readiness-title { font-size: 12px; font-weight: 700; color: var(--lw-text); margin-bottom: 12px; display: flex; align-items: center; gap: 7px; }
    .pl-readiness-title i { color: var(--lw-primary); }
    .pl-readiness-bar { display: flex; height: 12px; border-radius: 999px; overflow: hidden; background: var(--lw-bg); }
    .pl-readiness-bar span { height: 100%; transition: width 1s cubic-bezier(.22,.61,.36,1); }
    .pl-readiness-legend { display: flex; flex-wrap: wrap; gap: 8px 14px; margin-top: 12px; }
    .pl-legend { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: var(--lw-text-2); }
    .pl-legend i { font-size: 10px; }
    .pl-legend b { font-variant-numeric: tabular-nums; color: var(--lw-text); }
    .pl-readiness .pl-hint { display: flex; align-items: center; gap: 8px; margin-top: 12px; padding: 10px 13px; border-radius: 11px; background: var(--lw-bg); font-size: 11.5px; color: var(--lw-text-2); }
    .pl-readiness .pl-hint i { color: var(--lw-primary); font-size: 13px; }

    /* ---------- Status chips ---------- */
    .pl-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; font-size: 11.5px; font-weight: 700; border: 1px solid transparent; white-space: nowrap; }
    .pl-status.green { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
    .pl-status.red { background: var(--lw-red-soft); color: var(--lw-red); border-color: var(--lw-red-border); }
    .pl-status.amber { background: var(--lw-amber-soft); color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .pl-status.violet { background: var(--lw-violet-soft); color: var(--lw-violet); border-color: var(--lw-violet-border); }
    .pl-status.gray { background: var(--lw-bg); color: var(--lw-text-3); border-color: var(--lw-border); }
    .pl-status i { font-size: 12px; }

    /* ---------- Card grid ---------- */
    .pl-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .pl-card { position: relative; overflow: hidden; border: 1px solid var(--lw-border); border-radius: var(--pl-radius); background: var(--lw-card); box-shadow: var(--lw-shadow); padding: 18px; display: flex; flex-direction: column; gap: 13px; transition: all .22s ease; }
    .pl-card:hover { border-color: var(--lw-primary-border); transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); }
    .pl-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--lw-grad); opacity: 0; transition: opacity .2s; }
    .pl-card:hover::before { opacity: 1; }
    .pl-card.is-locked { opacity: .72; }
    .pl-card.is-locked:hover { opacity: .85; }

    .pl-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
    .pl-card-name { min-width: 0; }
    .pl-card-name h3 { font-size: 14.5px; font-weight: 800; color: var(--lw-text); margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pl-card-meta { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .pl-tag { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 8px; font-size: 10.5px; font-weight: 600; background: var(--lw-bg); color: var(--lw-text-2); white-space: nowrap; }
    .pl-tag i { font-size: 11px; color: var(--lw-primary); }

    .pl-ava { flex-shrink: 0; width: 46px; height: 46px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; font-weight: 800; color: #fff; }
    .pl-ava.c0 { background: linear-gradient(135deg,#2b3c78,#6b7fc4); }
    .pl-ava.c1 { background: linear-gradient(135deg,#0e9f6e,#4dd6a5); }
    .pl-ava.c2 { background: linear-gradient(135deg,#d97706,#f2bc2e); }
    .pl-ava.c3 { background: linear-gradient(135deg,#7c3aed,#a78bfa); }
    .pl-ava.c4 { background: linear-gradient(135deg,#db2777,#f472b6); }

    /* Judge progress row + ring */
    .pl-judge { display: flex; align-items: center; justify-content: space-between; gap: 12px; border-top: 1px dashed var(--lw-border); padding-top: 12px; }
    .pl-judge-txt { min-width: 0; }
    .pl-judge-txt b { font-size: 13px; font-weight: 700; color: var(--lw-text); display: block; }
    .pl-judge-txt span { font-size: 11px; color: var(--lw-text-3); }
    .pl-ring { width: 46px; height: 46px; flex-shrink: 0; transform: rotate(-90deg); }
    .pl-ring .bg { fill: none; stroke: var(--lw-border); stroke-width: 5; }
    .pl-ring .fg { fill: none; stroke-width: 5; stroke-linecap: round; stroke-dasharray: 113.1; stroke-dashoffset: 113.1; transition: stroke-dashoffset 1s cubic-bezier(.22,.61,.36,1); }

    /* Actions */
    .pl-card-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; border-top: 1px solid var(--lw-border); padding-top: 12px; }
    .pl-act { display: inline-flex; gap: 6px; }
    .pl-act-btn { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); font-size: 13px; cursor: pointer; transition: all .2s ease; text-decoration: none; position: relative; overflow: hidden; }
    .pl-act-btn:hover { transform: translateY(-1px); box-shadow: var(--lw-shadow); border-color: var(--lw-primary-border); color: var(--lw-primary); }
    .pl-act-btn.edit:hover { color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .pl-act-btn.del:hover { color: var(--lw-red); border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .pl-act-btn.is-off { opacity: .4; cursor: not-allowed; }
    .pl-act-btn.is-off:hover { transform: none; box-shadow: none; color: var(--lw-text-2); border-color: var(--lw-border); background: var(--lw-card); }

    /* ---------- Pagination ---------- */
    .pl-pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 18px; }
    .pl-pager-info { font-size: 12px; color: var(--lw-text-3); }
    .pl-pager-info b { color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .pl-pager-btns { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
    .pl-page-btn { min-width: 34px; height: 34px; padding: 0 10px; border-radius: 9px; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-2); font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; font-family: inherit; }
    .pl-page-btn:hover { border-color: var(--lw-primary-border); color: var(--lw-primary); }
    .pl-page-btn.active { background: var(--lw-grad); border-color: transparent; color: #fff; box-shadow: 0 4px 12px -4px rgba(43,60,120,.55); }
    .pl-page-btn:disabled { opacity: .4; cursor: not-allowed; }
    .pl-page-btn.ghost { border-color: transparent; background: transparent; }
    .pl-page-btn.ghost:hover { background: var(--lw-bg); border-color: transparent; }

    .pl-hero-btn { text-decoration: none !important; }

    /* ---------- Metric Cards ---------- */
    .pl-mod .lw-kpi-grid { grid-template-columns: repeat(6, 1fr); }

    @media (max-width: 1199.98px) { .pl-mod .lw-kpi-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 575.98px) { .pl-mod .lw-kpi-grid { grid-template-columns: repeat(2, 1fr); } }

    @media (max-width: 1399.98px) { .pl-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 991.98px) { .pl-dash-grid { grid-template-columns: 1fr; } .pl-split { grid-template-columns: repeat(2, 1fr); max-width: none; } }
    @media (max-width: 767.98px) {
        .pl-grid { grid-template-columns: 1fr; }
        .pl-dash { padding: 18px 16px; }
    }
</style>

@php
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $activeHaflah = \App\Models\HaflatulImtihan::find(session('haflah_id'));

    $rows = $individu->concat($tim);
    $pesertaIds = $rows->pluck('peserta_lomba_id')->filter();
    $lombaIds = $rows->map(fn ($r) => $r->pesertaLomba->lomba_id ?? null)->filter()->unique()->values();

    $juriPerLomba = \App\Models\JuriLomba::whereIn('lomba_id', $lombaIds)
        ->selectRaw('lomba_id, COUNT(*) as total')
        ->groupBy('lomba_id')
        ->pluck('total', 'lomba_id');

    $juriAktif = \App\Models\PenilaianLomba::whereIn('peserta_lomba_id', $pesertaIds)
        ->distinct()->count('juri_lomba_id');

    $cards = $rows->map(function ($row) use ($juriPerLomba) {
        $lomba = $row->pesertaLomba->lomba ?? null;
        $totalJuri = (int) ($juriPerLomba[$lomba->id ?? 0] ?? 0);
        $scored = (int) $row->jumlah_juri;
        $hasil = (bool) $row->pesertaLomba->hasil;
        $locked = (bool) $row->is_haflah_selesai;

        if ($hasil) {
            $status = ['label' => 'Sudah Diproses Hasil', 'cls' => 'violet', 'ic' => 'bi-box-arrow-up-right'];
        } elseif ($locked) {
            $status = ['label' => 'Terkunci', 'cls' => 'red', 'ic' => 'bi-lock-fill'];
        } elseif ($totalJuri > 0 && $scored >= $totalJuri) {
            $status = ['label' => 'Selesai Dinilai', 'cls' => 'green', 'ic' => 'bi-check-circle-fill'];
        } elseif ($scored > 0) {
            $status = ['label' => 'Sebagian Dinilai', 'cls' => 'amber', 'ic' => 'bi-hourglass-split'];
        } else {
            $status = ['label' => 'Belum Dinilai', 'cls' => 'gray', 'ic' => 'bi-circle'];
        }

        $nama = $row->lombaJenis === 'Tim'
            ? ($row->kelompok->nama_kelompok ?? '-')
            : ($row->pesertaLomba->student->user->name ?? $row->pesertaLomba->student->nama ?? '-');

        return [
            'id'           => $row->id,
            'latest_id'    => $row->latest_id,
            'pesertaId'    => $row->peserta_lomba_id,
            'lomba'        => $lomba->nama ?? '-',
            'jenis'        => $row->lombaJenis ?? 'Individu',
            'nama'         => $nama,
            'inisial'      => mb_strtoupper(mb_substr(trim($nama), 0, 1)),
            'avaIdx'       => (mb_ord(mb_substr(trim($nama), 0, 1)) ?? 0) % 5,
            'juriScored'   => $scored,
            'juriTotal'    => $totalJuri,
            'totalNilai'   => (int) $row->total_nilai,
            'progress'     => $totalJuri > 0 ? round($scored / $totalJuri * 100) : 0,
            'status'       => $status,
            'ringColor'    => ['violet' => 'var(--lw-violet)', 'red' => 'var(--lw-red)', 'green' => 'var(--lw-green)', 'amber' => 'var(--lw-amber)', 'gray' => 'var(--lw-text-3)'][$status['cls']],
            'hasil'        => $hasil,
            'locked'       => $locked,
            'kelompokId'   => $row->kelompok->id ?? null,
            'isTim'        => $row->lombaJenis === 'Tim',
        ];
    });

    $total = $cards->count();
    $individuCount = $cards->where('isTim', false)->count();
    $timCount = $cards->where('isTim', true)->count();
    $selesaiCount = $cards->filter(fn ($c) => $c['status']['label'] === 'Selesai Dinilai')->count();
    $sebagianCount = $cards->filter(fn ($c) => $c['status']['label'] === 'Sebagian Dinilai')->count();
    $belumCount = $cards->filter(fn ($c) => $c['status']['label'] === 'Belum Dinilai')->count();
    $prosesCount = $cards->filter(fn ($c) => $c['status']['label'] === 'Sudah Diproses Hasil')->count();
    $terkunciCount = $cards->filter(fn ($c) => $c['status']['label'] === 'Terkunci')->count();
    $dinilaiCount = $selesaiCount + $prosesCount;
    $progress = $total > 0 ? round($dinilaiCount / $total * 100) : 0;
    $lombaDinilai = $cards->pluck('lomba')->filter()->unique()->count();
@endphp

<div class="lw-mod pl-mod">

    {{-- HERO --}}
    <div class="lw-hero">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-star-fill"></i></span>
                <div>
                    <h1 class="lw-hero-title">Penilaian Lomba</h1>
                    <p class="lw-hero-sub">Competition Scoring Dashboard — pantau progres penilaian juri di setiap lomba secara real-time.</p>
                    <div class="lw-hero-badges">
                        <span class="lw-hero-badge"><i class="bi bi-calendar-event"></i>{{ optional($activeHaflah)->nama_acara ?: 'Haflah belum dipilih' }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-clock"></i>{{ $today }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-trophy"></i>{{ $lombaDinilai }} lomba dinilai</span>
                    </div>
                </div>
            </div>
            <div class="lw-hero-right">
                <button type="button" class="lw-btn lw-btn--light" id="plRefresh" aria-label="Muat ulang data"><i class="bi bi-arrow-clockwise"></i></button>
                <a href="{{ route('penilaian-lomba.create') }}" class="lw-btn lw-btn--solid pl-hero-btn"><i class="bi bi-plus-lg"></i> Tambah Penilaian</a>
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
    @if(session('toast_error'))
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('toast_error') }}</div>
    @endif
    @if ($errors->any())
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- SCORING PROGRESS DASHBOARD --}}
    <div class="pl-dash">
        <div class="pl-dash-top">
            <div>
                <h2><i class="bi bi-graph-up-arrow"></i> Scoring Progress</h2>
                <div class="sub">Ringkasan proses penilaian {{ optional($activeHaflah)->nama_acara ?: 'Haflah Aktif' }}</div>
            </div>
            <span class="pl-progress-chip"><i class="bi bi-clipboard-check"></i> Progress <b class="ms-1" data-count="{{ $progress }}">{{ $progress }}</b>%</span>
        </div>
        <div class="pl-dash-grid">
            <div>
                <div class="pl-metric-big" data-count="{{ $total }}">{{ $total }}</div>
                <div class="pl-metric-label">Total Peserta Dinilai</div>
                <div class="pl-metric-sub">{{ $lombaDinilai }} lomba &middot; {{ $juriAktif }} juri aktif</div>
                <div class="pl-split">
                    <div class="pl-split-item"><i class="bi bi-person" style="color:var(--lw-primary);"></i><div><div class="n" data-count="{{ $individuCount }}">{{ $individuCount }}</div><div class="l">Individu</div></div></div>
                    <div class="pl-split-item"><i class="bi bi-people" style="color:var(--lw-amber);"></i><div><div class="n" data-count="{{ $timCount }}">{{ $timCount }}</div><div class="l">Kelompok</div></div></div>
                    <div class="pl-split-item"><i class="bi bi-gavel" style="color:var(--lw-violet);"></i><div><div class="n" data-count="{{ $juriAktif }}">{{ $juriAktif }}</div><div class="l">Juri</div></div></div>
                    <div class="pl-split-item"><i class="bi bi-check-circle" style="color:var(--lw-green);"></i><div><div class="n" data-count="{{ $dinilaiCount }}">{{ $dinilaiCount }}</div><div class="l">Selesai</div></div></div>
                </div>
            </div>
            <div class="pl-readiness">
                <div class="pl-readiness-title"><i class="bi bi-bar-chart"></i> Distribusi Status Penilaian</div>
                <div class="pl-readiness-bar" id="plReadyBar">
                    <span style="background:var(--lw-green);width:0;" data-w="{{ $total ? $dinilaiCount / $total * 100 : 0 }}"></span>
                    <span style="background:var(--lw-amber);width:0;" data-w="{{ $total ? $sebagianCount / $total * 100 : 0 }}"></span>
                    <span style="background:var(--lw-text-3);width:0;" data-w="{{ $total ? $belumCount / $total * 100 : 0 }}"></span>
                    <span style="background:var(--lw-violet);width:0;" data-w="{{ $total ? $prosesCount / $total * 100 : 0 }}"></span>
                    <span style="background:var(--lw-red);width:0;" data-w="{{ $total ? $terkunciCount / $total * 100 : 0 }}"></span>
                </div>
                <div class="pl-readiness-legend">
                    <span class="pl-legend"><i class="bi bi-circle-fill" style="color:var(--lw-green);"></i>Selesai <b data-count="{{ $dinilaiCount }}">{{ $dinilaiCount }}</b></span>
                    <span class="pl-legend"><i class="bi bi-circle-fill" style="color:var(--lw-amber);"></i>Sebagian <b data-count="{{ $sebagianCount }}">{{ $sebagianCount }}</b></span>
                    <span class="pl-legend"><i class="bi bi-circle-fill" style="color:var(--lw-text-3);"></i>Belum <b data-count="{{ $belumCount }}">{{ $belumCount }}</b></span>
                    <span class="pl-legend"><i class="bi bi-circle-fill" style="color:var(--lw-violet);"></i>Diproses <b data-count="{{ $prosesCount }}">{{ $prosesCount }}</b></span>
                    <span class="pl-legend"><i class="bi bi-circle-fill" style="color:var(--lw-red);"></i>Terkunci <b data-count="{{ $terkunciCount }}">{{ $terkunciCount }}</b></span>
                </div>
                @if($total === 0)
                <div class="pl-hint"><i class="bi bi-info-circle"></i> Belum ada penilaian. Klik <b>&ldquo;Tambah Penilaian&rdquo;</b> untuk memulai.</div>
                @elseif($belumCount + $sebagianCount > 0)
                <div class="pl-hint"><i class="bi bi-info-circle"></i> {{ $belumCount + $sebagianCount }} peserta masih menunggu penilaian juri.</div>
                @else
                <div class="pl-hint" style="background:var(--lw-green-soft);color:var(--lw-green);"><i class="bi bi-check2-circle"></i> Seluruh penilaian selesai. Mantap!</div>
                @endif
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="lw-tabs" id="plTabs" role="tablist">
        <button type="button" class="lw-tab active" data-tab="individu" role="tab" aria-selected="true"><i class="bi bi-person"></i> Individu <span class="lw-badge-count">{{ $individuCount }}</span></button>
        <button type="button" class="lw-tab" data-tab="tim" role="tab" aria-selected="false"><i class="bi bi-people"></i> Kelompok <span class="lw-badge-count">{{ $timCount }}</span></button>
    </div>

    {{-- STICKY TOOLBAR --}}
    <div class="lw-toolbar" style="top:78px;" id="plToolbar">
        <div class="lw-search" style="flex:1 1 220px;"><i class="bi bi-search"></i>
            <input type="search" id="plSearch" class="lw-control" placeholder="Cari lomba atau peserta / kelompok..." aria-label="Cari lomba atau peserta" autocomplete="off">
        </div>
        <div class="lw-filter lw-filter--perpage"><label>Entri</label>
            <select id="plPerPage" class="lw-select">
                <option value="6">6</option>
                <option value="12">12</option>
                <option value="24">24</option>
                <option value="0">Semua</option>
            </select>
        </div>
        <div class="lw-toolbar-actions">
            <button type="button" id="plReset" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
        </div>
    </div>

    {{-- DATA GRID --}}
    @if($total === 0)
        <div class="lw-card">
            <div class="lw-empty">
                <div class="lw-empty-illus"><div class="ring"></div><div class="core"><i class="bi bi-star"></i></div></div>
                <div class="lw-empty-title">Belum ada penilaian lomba</div>
                <div class="lw-empty-sub">Mulai catat nilai dari setiap juri untuk peserta atau kelompok pada lomba Haflatul Imtihan.</div>
                <a href="{{ route('penilaian-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Penilaian Pertama</a>
            </div>
        </div>
    @else
        <div class="pl-grid" id="plGrid">
            @foreach($cards as $c)
            <article class="pl-card {{ $c['locked'] ? 'is-locked' : '' }}" data-tab="{{ $c['isTim'] ? 'tim' : 'individu' }}" data-q="{{ mb_strtolower($c['nama'] . ' ' . $c['lomba']) }}">
                <div class="pl-card-top">
                    <span class="pl-ava c{{ $c['avaIdx'] }}">{{ $c['inisial'] ?: ($c['isTim'] ? 'K' : 'S') }}</span>
                    <div class="pl-card-name">
                        <h3 title="{{ $c['nama'] }}">{{ $c['nama'] }}</h3>
                        <div class="pl-card-meta">
                            <span class="pl-tag"><i class="bi bi-trophy"></i>{{ $c['lomba'] }}</span>
                            <span class="pl-tag"><i class="bi {{ $c['isTim'] ? 'bi-people' : 'bi-person' }}"></i>{{ $c['jenis'] }}</span>
                            <span class="pl-status {{ $c['status']['cls'] }}"><i class="bi {{ $c['status']['ic'] }}"></i>{{ $c['status']['label'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="pl-judge">
                    <div class="pl-judge-txt">
                        <b>{{ $c['juriScored'] }}/{{ $c['juriTotal'] }} juri</b>
                        <span>Total nilai: <b style="color:var(--lw-text);">{{ $c['totalNilai'] }}</b></span>
                    </div>
                    <svg class="pl-ring" viewBox="0 0 46 46" role="img" aria-label="Progres penilaian {{ $c['progress'] }} persen">
                        <circle class="bg" cx="23" cy="23" r="18"></circle>
                        <circle class="fg" cx="23" cy="23" r="18" data-progress="{{ $c['progress'] }}" style="stroke:{{ $c['ringColor'] }};"></circle>
                    </svg>
                </div>

                <div class="pl-card-foot">
                    <div class="pl-act">
                        <a href="{{ route('penilaian-lomba.show', $c['id']) }}" class="pl-act-btn" title="Detail Penilaian" aria-label="Detail"><i class="bi bi-eye"></i></a>
                        @if($c['locked'] || $c['hasil'])
                            <span class="pl-act-btn edit is-off" tabindex="-1" title="{{ $c['hasil'] ? 'Sudah diproses hasil — terkunci' : 'Terkunci — haflah selesai' }}" aria-label="Terkunci"><i class="bi {{ $c['hasil'] ? 'bi-ban' : 'bi-lock-fill' }}"></i></span>
                            <span class="pl-act-btn del is-off" tabindex="-1" title="{{ $c['hasil'] ? 'Sudah diproses hasil' : 'Terkunci — haflah selesai' }}" aria-label="Terkunci"><i class="bi bi-lock-fill"></i></span>
                        @else
                            <a href="{{ route('penilaian-lomba.edit', $c['latest_id']) }}" class="pl-act-btn edit" title="Edit Nilai" aria-label="Edit"><i class="bi bi-pencil"></i></a>
                            <button type="button" class="pl-act-btn del" title="Hapus Penilaian" aria-label="Hapus Penilaian"
                                data-pl-delete data-id="{{ $c['pesertaId'] }}"
                                data-nama="{{ e($c['nama']) }}" data-lomba="{{ e($c['lomba']) }}" data-juri="{{ $c['juriScored'] }}"><i class="bi bi-trash"></i></button>
                        @endif
                    </div>
                    @if($c['isTim'] && $c['kelompokId'])
                    <a href="{{ route('kelompok-lomba.show', $c['kelompokId']) }}" class="pl-tag" style="text-decoration:none;cursor:pointer;"><i class="bi bi-box-arrow-up-right"></i>Detail Kelompok</a>
                    @endif
                </div>
            </article>
            @endforeach
        </div>

        <div class="pl-pager">
            <div class="pl-pager-info" id="plPagerInfo"></div>
            <div class="pl-pager-btns" id="plPagerBtns"></div>
        </div>
    @endif

</div>

<a href="{{ route('penilaian-lomba.create') }}" class="lw-fab" aria-label="Tambah penilaian baru"><i class="bi bi-plus-lg"></i></a>

<form id="plDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var grid = document.getElementById('plGrid');
    if (!grid) return;

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.pl-card'));
    var state = { tab: 'individu', q: '', per: 6, page: 1 };

    // ---------- Tabs ----------
    var tabs = document.querySelectorAll('#plTabs .lw-tab');
    tabs.forEach(function (t) {
        t.addEventListener('click', function () {
            tabs.forEach(function (x) { x.classList.remove('active'); x.setAttribute('aria-selected', 'false'); });
            t.classList.add('active');
            t.setAttribute('aria-selected', 'true');
            state.tab = t.dataset.tab;
            state.page = 1;
            render();
        });
    });

    // ---------- Filter / paginate ----------
    function filtered() {
        return cards.filter(function (c) {
            if (c.dataset.tab !== state.tab) return false;
            if (state.q && c.dataset.q.indexOf(state.q) === -1) return false;
            return true;
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
        slice.forEach(function (c) { ids[c.dataset.tab + c.dataset.q] = 1; });
        cards.forEach(function (c) {
            c.style.display = ids[c.dataset.tab + c.dataset.q] ? '' : 'none';
        });

        var info = document.getElementById('plPagerInfo');
        info.textContent = list.length === 0
            ? 'Tidak ada hasil'
            : 'Menampilkan ' + (start + 1) + '–' + (start + slice.length) + ' dari ' + list.length + ' data';

        var btns = document.getElementById('plPagerBtns');
        btns.innerHTML = '';
        if (pages > 1) {
            var mk = function (label, page, opts) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'pl-page-btn' + (opts || '');
                b.textContent = label;
                if (page === state.page) b.classList.add('active');
                if (page < 1 || page > pages) { b.disabled = true; return b; }
                b.addEventListener('click', function () { state.page = page; render(); });
                return b;
            };
            btns.appendChild(mk('\u2039', state.page - 1, ' ghost'));
            var startP = Math.max(1, state.page - 2), endP = Math.min(pages, startP + 4);
            for (var p = startP; p <= endP; p++) btns.appendChild(mk(String(p), p));
            btns.appendChild(mk('\u203A', state.page + 1, ' ghost'));
        }
    }

    document.getElementById('plSearch').addEventListener('input', function () {
        state.q = this.value.trim().toLowerCase();
        state.page = 1; render();
    });
    document.getElementById('plPerPage').addEventListener('change', function () {
        state.per = parseInt(this.value, 10); state.page = 1; render();
    });
    document.getElementById('plReset').addEventListener('click', function () {
        document.getElementById('plSearch').value = '';
        document.getElementById('plPerPage').value = '6';
        state = { tab: state.tab, q: '', per: 6, page: 1 };
        render();
    });

    render();

    // ---------- Progress ring + readiness bar + counters ----------
    setTimeout(function () {
        document.querySelectorAll('#plReadyBar span[data-w]').forEach(function (s) { s.style.width = s.dataset.w + '%'; });
        document.querySelectorAll('.pl-ring .fg').forEach(function (c) {
            var pct = parseFloat(c.dataset.progress) || 0;
            var off = 113.1 - (113.1 * pct / 100);
            c.style.strokeDashoffset = off;
        });
    }, 120);

    document.querySelectorAll('.lw-kpi-num[data-count], .pl-metric-big[data-count], .pl-split .n[data-count], .pl-progress-chip b[data-count], .pl-legend b[data-count]').forEach(function (el) {
        if (window.LW && LW.counter) LW.counter(el);
    });

    // ---------- Delete confirmation ----------
    var deleteForm = document.getElementById('plDeleteForm');
    grid.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-pl-delete]');
        if (!btn) return;
        var id = btn.dataset.id;
        if (!id) return;
        var nama = btn.dataset.nama, lomba = btn.dataset.lomba, juri = btn.dataset.juri;
        LW.confirm('Hapus Penilaian?', 'Seluruh nilai "' + nama + '" pada lomba "' + lomba + '" (' + juri + ' juri menilai) akan dihapus permanen dan tidak dapat dikembalikan.', 'bi-trash').then(function (ok) {
            if (ok) { deleteForm.action = '{{ url('penilaian-lomba/hapus-semua') }}/' + id; deleteForm.submit(); }
        });
    });

    // ---------- Refresh with spinner ----------
    document.getElementById('plRefresh').addEventListener('click', function () {
        var i = this.querySelector('i');
        i.classList.add('spin');
        this.disabled = true;
        setTimeout(function () { window.location.reload(); }, 550);
    });

    // ---------- ripple ----------
    document.querySelectorAll('#plToolbar .lw-btn, #plToolbar .lw-select, #plToolbar .lw-control, .lw-hero .lw-btn').forEach(function (el) {
        el.addEventListener('mousedown', function (e) { if (window.LW && LW.ripple) LW.ripple(e); });
    });
})();
</script>
<style>
    #plRefresh i.spin { animation: lwSpin 1s linear infinite; }
</style>
@endpush
@endsection
