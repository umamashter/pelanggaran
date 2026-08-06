@extends('layouts.main')
@section('title', 'Juri Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Juri Lomba — Assignment Management Dashboard ---------- */
    .lj-status-chip { display: inline-flex; align-items: center; gap: 6px; border-radius: 999px; padding: 5px 11px;
        font-size: 11px; font-weight: 700; white-space: nowrap; letter-spacing: .1px; }
    .lj-status-chip i { font-size: 11px; }
    .lj-status-chip.empty   { background: var(--lw-bg); color: var(--lw-text-2); border: 1px solid var(--lw-border); }
    .lj-status-chip.ready   { background: var(--lw-green-soft); color: var(--lw-green); border: 1px solid var(--lw-green-border); }
    .lj-status-chip.ongoing { background: var(--lw-navy-soft); color: var(--lw-primary); border: 1px solid var(--lw-navy-border); }
    .lj-status-chip.ongoing i { animation: lwDot 1.6s ease-in-out infinite; }
    .lj-status-chip.completed { background: var(--lw-violet-soft); color: var(--lw-violet); border: 1px solid var(--lw-violet-border); }
    .lj-status-chip.locked  { background: var(--lw-red-soft); color: var(--lw-red); border: 1px solid var(--lw-red-border); }

    /* ---------- Competition coverage ---------- */
    .lj-coverage-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px; }
    .lj-coverage-item { display: flex; flex-direction: column; gap: 6px; padding: 16px 18px; }
    .lj-coverage-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .lj-coverage-value { font-size: 26px; font-weight: 800; letter-spacing: -.5px; color: var(--lw-text); font-variant-numeric: tabular-nums; line-height: 1; }
    .lj-coverage-label { font-size: 12px; font-weight: 600; color: var(--lw-text-3); }
    .lj-coverage-sub { font-size: 10.5px; color: var(--lw-text-3); }
    .lj-coverage-progress { height: 6px; border-radius: 999px; background: var(--lw-bg); border: 1px solid var(--lw-border); overflow: hidden; margin-top: 6px; }
    .lj-coverage-fill { height: 100%; border-radius: 999px; background: var(--lw-grad); transition: width .8s cubic-bezier(.22,1,.36,1); }
    .lj-coverage-item.hot .lj-coverage-value { color: var(--lw-amber); }
    @media (max-width: 991.98px) { .lj-coverage-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .lj-coverage-grid { grid-template-columns: 1fr 1fr; gap: 10px; } }

    /* ---------- Assignment grid ---------- */
    .lj-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 14px; }
    .lj-card {
        position: relative; overflow: hidden; display: flex; flex-direction: column;
        background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 18px;
        box-shadow: var(--lw-shadow);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, opacity .3s ease;
    }
    .lj-card::before { content: ''; position: absolute; inset-inline: 0; top: 0; height: 3px;
        background: linear-gradient(90deg, var(--lw-primary), #6b7fc4); opacity: 0; transition: opacity .25s ease; z-index: 2; }
    .lj-card:hover { transform: translateY(-4px); box-shadow: var(--lw-shadow-lg); border-color: var(--lw-primary-border); }
    .lj-card:hover::before { opacity: 1; }
    .lj-card.is-ready::before { background: linear-gradient(90deg, #0e9f6e, #4dd6a5); }
    .lj-card.is-ongoing::before { background: linear-gradient(90deg, #2b3c78, #6b7fc4); }
    .lj-card.is-completed::before { background: linear-gradient(90deg, #7c3aed, #a78bfa); }
    .lj-card.is-locked::before { background: linear-gradient(90deg, #dc4c4c, #f18b8b); }
    .lj-card.is-empty-card::before { background: linear-gradient(90deg, #94a3b8, #cbd5e1); }
    .lj-card.is-ready::before, .lj-card.is-ongoing::before, .lj-card.is-completed::before,
    .lj-card.is-locked::before, .lj-card.is-empty-card::before { opacity: 1; }

    .lj-card.is-locked { opacity: .68; }
    .lj-card.is-locked:hover { opacity: .85; }
    .lj-lock-veil { position: absolute; inset: 0; z-index: 3; display: none; align-items: center; justify-content: center;
        background: rgba(255,255,255,.35); backdrop-filter: blur(1px); cursor: not-allowed; }
    html.dark-mode .lj-lock-veil { background: rgba(15,23,42,.4); }
    .lj-card.is-locked .lj-lock-veil { display: flex; }
    .lj-lock-pill { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; border-radius: 999px;
        background: var(--lw-red); color: #fff; font-size: 11.5px; font-weight: 700; box-shadow: 0 10px 24px -6px rgba(220,76,76,.6); }

    .lj-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 18px 18px 0; }
    .lj-card-name { font-size: 15px; font-weight: 800; color: var(--lw-text); line-height: 1.3; word-break: break-word; }
    .lj-card-name a { color: inherit; text-decoration: none; }
    .lj-card-name a:hover { color: var(--lw-primary); }
    .lj-card-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 7px; }
    .lj-card-body { padding: 14px 18px 0; display: flex; flex-direction: column; gap: 12px; flex: 1; }

    .lj-avatars { display: flex; align-items: center; }
    .lj-avatar { width: 34px; height: 34px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; color: #fff; border: 2px solid var(--lw-card); flex-shrink: 0;
        box-shadow: 0 2px 6px -1px rgba(15,23,42,.25); }
    .lj-avatar + .lj-avatar { margin-left: -9px; }
    .lj-avatar-overflow { width: 34px; height: 34px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 10.5px; font-weight: 800; color: var(--lw-text-2); background: var(--lw-bg); border: 2px solid var(--lw-card);
        margin-left: -9px; }
    .lj-avatar-empty { font-size: 11.5px; color: var(--lw-text-3); font-weight: 600; }

    .lj-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .lj-stat { background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 11px; padding: 9px 8px; text-align: center; }
    .lj-stat .v { font-size: 17px; font-weight: 800; color: var(--lw-text); line-height: 1.1; font-variant-numeric: tabular-nums; }
    .lj-stat .l { font-size: 9.5px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .3px; margin-top: 3px; }

    .lj-health { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 10px; font-size: 11.5px; font-weight: 700; }
    .lj-health i { font-size: 12px; }
    .lj-health.ready { background: var(--lw-green-soft); color: var(--lw-green); }
    .lj-health.ongoing { background: var(--lw-navy-soft); color: var(--lw-primary); }
    .lj-health.completed { background: var(--lw-violet-soft); color: var(--lw-violet); }
    .lj-health.locked { background: var(--lw-red-soft); color: var(--lw-red); }
    .lj-health.empty { background: var(--lw-amber-soft); color: var(--lw-amber); }
    .lj-progress-line { flex: 1; height: 5px; border-radius: 999px; background: var(--lw-bg); overflow: hidden; }
    .lj-progress-fill { height: 100%; border-radius: 999px; background: currentColor; transition: width .7s ease; }

    .lj-card-actions { display: flex; gap: 8px; padding: 12px 18px 16px; margin-top: 6px; }
    .lj-icon-btn { width: 38px; height: 38px; border-radius: 11px; border: 1px solid var(--lw-border); background: var(--lw-card);
        color: var(--lw-text-2); display: inline-flex; align-items: center; justify-content: center; font-size: 14px;
        text-decoration: none; transition: all .18s ease; }
    .lj-icon-btn:hover { transform: translateY(-2px); box-shadow: var(--lw-shadow); }
    .lj-icon-btn.view:hover { color: var(--lw-primary); border-color: var(--lw-primary-border); background: var(--lw-primary-soft); }
    .lj-icon-btn.edit:hover { color: var(--lw-amber); border-color: var(--lw-amber-border); background: var(--lw-amber-soft); }
    .lj-icon-btn.del:hover { color: var(--lw-red); border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .lj-icon-btn:disabled, .lj-icon-btn.is-disabled { opacity: .38; cursor: not-allowed; pointer-events: auto; }
    .lj-icon-btn:disabled:hover, .lj-icon-btn.is-disabled:hover { transform: none; box-shadow: none; color: var(--lw-text-2); border-color: var(--lw-border); background: var(--lw-card); }

    /* ---------- Workload ---------- */
    .lj-workload-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; }
    .lj-workload-item + .lj-workload-item { border-top: 1px solid var(--lw-border-soft); }
    .lj-workload-avatar { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .lj-workload-name { flex: 1; min-width: 0; font-size: 13.5px; font-weight: 700; color: var(--lw-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .lj-workload-bar { flex: 1; max-width: 140px; height: 6px; border-radius: 999px; background: var(--lw-bg); overflow: hidden; }
    .lj-workload-fill { height: 100%; border-radius: 999px; background: var(--lw-grad); }
    .lj-workload-count { font-size: 12px; font-weight: 800; color: var(--lw-primary); background: var(--lw-primary-soft);
        border: 1px solid var(--lw-primary-border); padding: 3px 11px; border-radius: 999px; white-space: nowrap; }

    /* ---------- Client pager ---------- */
    .lj-client-pager { display: none; align-items: center; justify-content: center; gap: 8px; margin-top: 14px; }
    .lj-client-pager.is-visible { display: flex; }
    .lj-client-page { min-width: 36px; height: 36px; padding: 0 10px; border-radius: 10px; border: 1px solid var(--lw-border);
        background: var(--lw-card); color: var(--lw-text-2); font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all .18s ease; }
    .lj-client-page:hover:not(:disabled) { border-color: var(--lw-primary-border); color: var(--lw-primary); }
    .lj-client-page.is-active { background: var(--lw-grad); color: #fff; border-color: transparent; box-shadow: 0 6px 16px -6px rgba(43,60,120,.5); }
    .lj-client-page:disabled { opacity: .4; cursor: not-allowed; }

    /* ---------- Stagger entrance ---------- */
    .lj-card { opacity: 0; }
    .lj-card.lj-in { animation: ljCardIn .4s cubic-bezier(.22,1,.36,1) both; }
    @keyframes ljCardIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .lj-card.lj-hidden { display: none !important; }
</style>

@php
if (!function_exists('lj_status')) {
    function lj_status($item)
    {
        if ($item->jumlah_juri == 0) return 'empty';
        if ($item->is_haflah_selesai) return 'locked';
        if ($item->penilaian_count > 0) {
            return ($item->penilaian_count >= $item->jumlah_juri) ? 'completed' : 'ongoing';
        }
        return 'ready';
    }
}

$items = collect($juriLombas->items());
$totalLombaAll = $juriLombas->total();
$totalLomba = $items->count();

$sudahJuri = $items->where('jumlah_juri', '>', 0)->count();
$belumJuri = $totalLomba - $sudahJuri;
$sudahDinilai = $items->where('penilaian_count', '>', 0)->count();
$terkunci = $items->where('is_haflah_selesai', true)->count();
$juriDitugaskan = $items->sum('jumlah_juri');

$selesaiMenilai = 0; $sedangMenilai = 0; $siapMenilai = 0;
foreach ($items as $item) {
    $s = lj_status($item);
    if ($s === 'completed') $selesaiMenilai++;
    elseif ($s === 'ongoing') $sedangMenilai++;
    elseif ($s === 'ready') $siapMenilai++;
}

$workload = [];
foreach ($items as $item) {
    $names = $item->nama_juri ? array_filter(array_map('trim', explode(',', $item->nama_juri))) : [];
    foreach ($names as $n) $workload[$n] = ($workload[$n] ?? 0) + 1;
}
arsort($workload);
$totalGuruJuri = count($workload);
$topWorkload = array_slice($workload, 0, 8, true);
$penugasanPct = $totalLomba ? round($sudahJuri / $totalLomba * 100) : 0;

$today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
$haflahStatus = $haflahAktif->status ?? null;
$haflahStatusChip = $haflahStatus === 'Aktif' ? 'lw-chip--green' : ($haflahStatus === 'Selesai' ? 'lw-chip--violet' : 'lw-chip--amber');
$haflahStatusIcon = $haflahStatus === 'Aktif' ? 'bi-play-circle-fill' : ($haflahStatus === 'Selesai' ? 'bi-archive-fill' : 'bi-clock');
@endphp

<div class="lw-mod">

    {{-- HERO --}}
    <div class="lw-hero">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-gavel"></i></span>
                <div>
                    <h1 class="lw-hero-title">Juri Lomba</h1>
                    <p class="lw-hero-sub">Judge Management Dashboard — pantau distribusi juri, status penugasan, dan progres penilaian setiap lomba.</p>
                    <div class="lw-hero-badges">
                        <span class="lw-hero-badge"><i class="bi bi-award-fill"></i>{{ $haflahAktif->nama_acara ?? 'Haflah belum dipilih' }}</span>
                        <span class="lw-hero-badge {{ $haflahStatus === 'Selesai' ? 'lw-hero-badge--warn' : 'lw-hero-badge--ok' }}"><i class="bi {{ $haflahStatusIcon }}"></i>{{ $haflahStatus ?? '-' }}</span>
                        <span class="lw-hero-badge"><i class="bi bi-calendar3"></i>{{ $today }}</span>
                    </div>
                </div>
            </div>
            <div class="lw-hero-right">
                <button type="button" class="lw-btn lw-btn--light" id="ljRefresh" aria-label="Muat ulang data"><i class="bi bi-arrow-clockwise"></i></button>
                <a href="{{ route('juri-lomba.create') }}" class="lw-btn lw-btn--light"><i class="bi bi-plus-lg"></i> Tambah Juri</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    {{-- TOOLBAR --}}
    <div class="lw-toolbar" id="ljToolbar">
        <div class="lw-search" style="min-width:200px;">
            <i class="bi bi-search"></i>
            <input type="search" class="lw-control" id="ljSearch" placeholder="Cari lomba atau nama juri..." autocomplete="off">
        </div>
        <div class="lw-filter">
            <label>Status</label>
            <select class="lw-select" id="ljStatus">
                <option value="">Semua Status</option>
                <option value="empty">Belum Ada Juri</option>
                <option value="ready">Siap Menilai</option>
                <option value="ongoing">Sedang Menilai</option>
                <option value="completed">Penilaian Selesai</option>
                <option value="locked">Assignment Terkunci</option>
            </select>
        </div>
        <div class="lw-filter">
            <label>Per Halaman</label>
            <select class="lw-select" id="ljPerPage">
                <option value="10">10</option>
                <option value="6">6</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="lw-filter">
            <label>Haflah</label>
            <select class="lw-select" id="ljHaflah">
                @foreach($semuaHaflah as $h)
                    <option value="{{ $h->id }}" data-href="{{ route('haflah.aktifkan', $h->id) }}" @if((session('haflah_id') ?: $haflahAktif->id ?? null) == $h->id) selected @endif>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-toolbar-actions">
            <button type="button" class="lw-btn lw-btn--ghost" id="ljReset" title="Reset filter"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
        </div>
    </div>

    {{-- ASSIGNMENT GRID --}}
    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-people-fill"></i> Assignment Grid</div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <span class="lw-chip lw-chip--slate lw-chip-mini">Belum Ada Juri</span>
                <span class="lw-chip lw-chip--green lw-chip-mini">Siap Menilai</span>
                <span class="lw-chip lw-chip--navy lw-chip-mini">Sedang Menilai</span>
                <span class="lw-chip lw-chip--violet lw-chip-mini">Penilaian Selesai</span>
                <span class="lw-chip lw-chip--red lw-chip-mini">Terkunci</span>
            </div>
        </div>

        <div id="ljGridWrap">
            <div class="lj-grid" id="ljGrid">
                @forelse($items as $item)
                    @php
                        $s = lj_status($item);
                        $isLocked = $item->is_haflah_selesai;
                        $hasPenilaian = $item->penilaian_count > 0;
                        $namaArr = $item->nama_juri ? array_filter(array_map('trim', explode(',', $item->nama_juri))) : [];
                        $namaArr = array_values($namaArr);
                        $cardClass = $s === 'ready' ? 'is-ready' : ($s === 'ongoing' ? 'is-ongoing' : ($s === 'completed' ? 'is-completed' : ($s === 'locked' ? 'is-locked' : 'is-empty-card')));
                        $progress = $item->jumlah_juri > 0 ? min(100, (int) round($item->penilaian_count / $item->jumlah_juri * 100)) : 0;
                        $filterText = strtolower(trim(($item->lomba->nama ?? '') . ' ' . ($item->lomba->jenis ?? '') . ' ' . ($item->nama_juri ?? '')));
                        $lockReason = $isLocked ? 'Tidak dapat diubah karena Haflah telah selesai.' : ($hasPenilaian ? 'Assignment tidak dapat diubah karena sudah terdapat data penilaian.' : '');
                        $statusLabel = $s === 'empty' ? 'Belum Ada Juri' : ($s === 'ready' ? 'Siap Menilai' : ($s === 'ongoing' ? 'Sedang Menilai' : ($s === 'completed' ? 'Penilaian Selesai' : 'Assignment Terkunci')));
                        $healthLabel = $s === 'empty' ? 'Juri Belum Lengkap' : ($s === 'ready' ? 'Siap Dinilai' : ($s === 'ongoing' ? 'Penilaian Berlangsung' : ($s === 'completed' ? 'Penilaian Selesai' : 'Terkunci')));
                        $healthIcon = $s === 'empty' ? 'bi-exclamation-triangle-fill' : ($s === 'ready' ? 'bi-check-circle-fill' : ($s === 'ongoing' ? 'bi-arrow-clockwise' : ($s === 'completed' ? 'bi-flag-fill' : 'bi-lock-fill')));
                        $jenisIcon = ($item->lomba->jenis ?? '') === 'Tim' ? 'bi-people-fill' : 'bi-person-fill';
                    @endphp
                    <div class="lj-card {{ $cardClass }} lj-grid-item" data-status="{{ $s }}" data-filter="{{ $filterText }}">
                        @if($isLocked)
                            <div class="lj-lock-veil" title="Tidak dapat diubah karena Haflah telah selesai.">
                                <span class="lj-lock-pill"><i class="bi bi-lock-fill"></i> Haflah Selesai</span>
                            </div>
                        @endif

                        <div class="lj-card-top">
                            <div style="min-width:0;">
                                <div class="lj-card-name">
                                    @if($item->latest_id)
                                        <a href="{{ route('juri-lomba.show', $item->latest_id) }}">{{ $item->lomba->nama ?? '-' }}</a>
                                    @else
                                        {{ $item->lomba->nama ?? '-' }}
                                    @endif
                                </div>
                                <div class="lj-card-meta">
                                    <span class="lw-chip lw-chip-mini" style="display:inline-flex;font-size:10px;min-height:22px;padding:0 8px;">
                                        <i class="bi {{ $jenisIcon }}"></i>{{ $item->lomba->jenis ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            <span class="lj-status-chip {{ $s }}" title="{{ $lockReason ?: $statusLabel }}">
                                @if($s === 'locked')<i class="bi bi-lock-fill"></i>@elseif($s === 'ongoing')<i class="bi bi-circle-fill"></i>@else<i class="bi {{ $healthIcon }}"></i>@endif
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="lj-card-body">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
                                <div class="lj-avatars" title="{{ $item->nama_juri ?: 'Belum ada juri' }}">
                                    @forelse(array_slice($namaArr, 0, 4) as $i => $nama)
                                        <span class="lj-avatar" style="background:{{ lw_ava_color($nama) }};">{{ lw_initial($nama) }}</span>
                                    @empty
                                        <span class="lj-avatar-empty">Belum ada juri ditugaskan</span>
                                    @endforelse
                                    @php $remaining = count($namaArr) - 4; @endphp
                                    @if($remaining > 0)
                                        <span class="lj-avatar-overflow" title="{{ implode(', ', array_slice($namaArr, 4)) }}">+{{ $remaining }}</span>
                                    @endif
                                </div>
                                <span class="lw-chip lw-chip-mini"><i class="bi bi-gavel"></i>{{ $item->jumlah_juri }} juri</span>
                            </div>

                            <div class="lj-stats">
                                <div class="lj-stat"><div class="v">{{ $item->jumlah_juri }}</div><div class="l">Juri</div></div>
                                <div class="lj-stat"><div class="v">{{ $item->penilaian_count }}</div><div class="l">Penilaian</div></div>
                                <div class="lj-stat"><div class="v">{{ $progress }}%</div><div class="l">Progres</div></div>
                            </div>

                            <div class="lj-health {{ $s }}">
                                <i class="bi {{ $healthIcon }}"></i>{{ $healthLabel }}
                                <span class="lj-progress-line"><span class="lj-progress-fill" data-w="{{ $progress }}" style="width:0%"></span></span>
                            </div>
                        </div>

                        <div class="lj-card-actions">
                            <a href="{{ route('juri-lomba.show', $item->latest_id) }}" class="lj-icon-btn view" title="Lihat detail" aria-label="Detail {{ $item->lomba->nama }}"><i class="bi bi-eye"></i></a>
                            @if($isLocked || $hasPenilaian)
                                <button type="button" class="lj-icon-btn edit is-disabled" disabled title="{{ $lockReason }}" aria-label="Edit tidak tersedia"><i class="bi bi-pencil-square"></i></button>
                            @else
                                <a href="{{ route('juri-lomba.edit', $item->latest_id) }}" class="lj-icon-btn edit" title="Edit assignment" aria-label="Edit {{ $item->lomba->nama }}"><i class="bi bi-pencil-square"></i></a>
                            @endif
                            @if($isLocked || $hasPenilaian)
                                <button type="button" class="lj-icon-btn del is-disabled" disabled title="{{ $lockReason }}" aria-label="Hapus tidak tersedia"><i class="bi bi-trash"></i></button>
                            @else
                                <button type="button" class="lj-icon-btn del" title="Hapus assignment" aria-label="Hapus {{ $item->lomba->nama }}"
                                    data-lj-delete data-id="{{ $item->latest_id }}" data-nama="{{ e($item->lomba->nama ?? '') }}" data-jml="{{ $item->jumlah_juri }}"><i class="bi bi-trash"></i></button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="lw-empty" style="grid-column:1/-1;">
                        <div class="lw-empty-illus"><div class="ring"></div><div class="core"><i class="bi bi-gavel"></i></div></div>
                        <div class="lw-empty-title">Belum Ada Assignment</div>
                        <div class="lw-empty-sub">Belum ada penugasan juri untuk lomba apapun. Mulai dengan menambahkan juri pertama untuk memastikan tidak ada lomba yang terlewat.</div>
                        <a href="{{ route('juri-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Juri Pertama</a>
                    </div>
                @endforelse
            </div>
            <div class="lj-client-pager" id="ljClientPager"></div>
            <div class="lw-empty" id="ljClientEmpty" style="display:none;padding:32px 16px;">
                <i class="bi bi-search mb-3" style="font-size:22px;display:block;"></i>Tidak ada assignment yang cocok dengan filter.
            </div>
        </div>

        @if($juriLombas->hasPages())
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                <div style="font-size:12px;color:var(--lw-text-3);font-weight:500;">Menampilkan {{ $juriLombas->firstItem() ?? 0 }}-{{ $juriLombas->lastItem() ?? 0 }} dari {{ $totalLombaAll }} lomba</div>
                <div>{{ $juriLombas->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>

    {{-- WORKLOAD PANEL --}}
    @if(count($topWorkload) > 0)
    <div class="lw-card" style="margin-bottom:18px;">
        <div class="lw-card-header">
            <div>
                <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-bar-chart-fill"></i> Beban Kerja Juri</div>
                <div class="lw-section-sub" style="margin:2px 0 0;">Distribusi penugasan per guru — deteksi juri yang overload</div>
            </div>
        </div>
        <div class="lw-card-pad" style="padding-top:8px;">
            <div class="row g-0">
                @foreach($topWorkload as $nama => $count)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="lj-workload-item">
                            <span class="lj-workload-avatar" style="background:{{ lw_ava_color($nama) }};">{{ lw_initial($nama) }}</span>
                            <div class="lj-workload-name" title="{{ $nama }}">{{ $nama }}</div>
                            <span class="lj-workload-bar"><span class="lj-workload-fill" data-w="{{ $topWorkload ? round($count / max($topWorkload) * 100) : 0 }}" style="width:0%"></span></span>
                            <span class="lj-workload-count">{{ $count }} lomba</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

<a href="{{ route('juri-lomba.create') }}" class="lw-fab" aria-label="Tambah assignment juri"><i class="bi bi-plus-lg"></i></a>

<form id="ljDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var grid = document.getElementById('ljGrid');
    var cards = Array.prototype.slice.call(grid.querySelectorAll('.lj-grid-item'));
    var search = document.getElementById('ljSearch');
    var statusF = document.getElementById('ljStatus');
    var perPage = document.getElementById('ljPerPage');
    var haflahS = document.getElementById('ljHaflah');
    var pagerEl = document.getElementById('ljClientPager');
    var emptyEl = document.getElementById('ljClientEmpty');
    var clientPage = 1;

    /* ---------- ripple ---------- */
    document.querySelectorAll('#ljToolbar .lw-btn, #ljToolbar .lw-select, #ljToolbar .lw-control').forEach(function (el) {
        el.addEventListener('mousedown', function (e) { if (window.LW && LW.ripple) LW.ripple(e); });
    });

    /* ---------- filter + client pagination ---------- */
    function visibleCards() {
        var q = (search.value || '').toLowerCase().trim();
        var st = statusF.value;
        return cards.filter(function (c) {
            var hitT = !q || (c.dataset.filter || '').indexOf(q) !== -1;
            var hitS = !st || c.dataset.status === st;
            return hitT && hitS;
        });
    }
    function render() {
        var vis = visibleCards();
        var pp = parseInt(perPage.value, 10) || 10;
        var pages = Math.max(1, Math.ceil(vis.length / pp));
        if (clientPage > pages) clientPage = pages;
        cards.forEach(function (c) { c.classList.add('lj-hidden'); });
        var start = (clientPage - 1) * pp;
        vis.slice(start, start + pp).forEach(function (c) { c.classList.remove('lj-hidden'); });

        emptyEl.style.display = vis.length === 0 ? 'block' : 'none';

        if (pages > 1) {
            pagerEl.classList.add('is-visible');
            var h = '';
            h += '<button type="button" class="lj-client-page" data-pg="' + (clientPage - 1) + '" ' + (clientPage === 1 ? 'disabled' : '') + ' aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></button>';
            for (var i = 1; i <= pages; i++) {
                h += '<button type="button" class="lj-client-page' + (i === clientPage ? ' is-active' : '') + '" data-pg="' + i + '">' + i + '</button>';
            }
            h += '<button type="button" class="lj-client-page" data-pg="' + (clientPage + 1) + '" ' + (clientPage === pages ? 'disabled' : '') + ' aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></button>';
            pagerEl.innerHTML = h;
            pagerEl.querySelectorAll('.lj-client-page[data-pg]').forEach(function (b) {
                b.addEventListener('click', function () { clientPage = parseInt(b.dataset.pg, 10) || 1; render(); });
            });
        } else {
            pagerEl.classList.remove('is-visible');
        }
    }
    search.addEventListener('input', function () { clientPage = 1; render(); });
    statusF.addEventListener('change', function () { clientPage = 1; render(); });
    perPage.addEventListener('change', function () { clientPage = 1; render(); });
    document.getElementById('ljReset').addEventListener('click', function () {
        search.value = ''; statusF.value = ''; perPage.value = '10'; clientPage = 1; render();
    });

    /* ---------- haflah switcher ---------- */
    haflahS.addEventListener('change', function () {
        var opt = haflahS.options[haflahS.selectedIndex];
        if (opt && opt.dataset.href) { window.location.href = opt.dataset.href; }
    });

    /* ---------- stagger entrance ---------- */
    cards.forEach(function (c, i) {
        setTimeout(function () { c.classList.add('lj-in'); }, 50 + i * 45);
    });

    /* ---------- animated counters ---------- */
    document.querySelectorAll('.lw-kpi-num[data-count], .lj-coverage-value[data-count]').forEach(function (el) {
        if (window.LW && LW.counter) LW.counter(el);
    });

    /* ---------- bars ---------- */
    setTimeout(function () {
        document.querySelectorAll('.lj-coverage-fill, .lj-progress-fill, .lj-workload-fill').forEach(function (f) {
            f.style.width = f.dataset.w + '%';
        });
    }, 250);

    /* ---------- refresh with skeleton ---------- */
    document.getElementById('ljRefresh').addEventListener('click', function () {
        var i = this.querySelector('i');
        i.classList.add('spin');
        this.disabled = true;
        var sk = '';
        for (var r = 0; r < 6; r++) {
            sk += '<div class="lj-card" style="opacity:.6;"><div class="lw-skeleton" style="height:180px;border-radius:18px;"></div></div>';
        }
        grid.innerHTML = sk;
        setTimeout(function () { window.location.reload(); }, 550);
    });

    /* ---------- delete confirmation ---------- */
    var deleteForm = document.getElementById('ljDeleteForm');
    grid.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-lj-delete]');
        if (!btn) return;
        var id = btn.dataset.id, nama = btn.dataset.nama, jml = parseInt(btn.dataset.jml, 10) || 0;
        if (!id) return;
        LW.confirm('Hapus Assignment?', 'Lomba "' + nama + '" dengan ' + jml + ' juri akan dihapus. Data tidak dapat dikembalikan.', 'bi-trash').then(function (ok) {
            if (ok) { deleteForm.action = '{{ url('juri-lomba') }}/' + id; deleteForm.submit(); }
        });
    });

    render();
})();
</script>
<style>
    #ljRefresh i.spin { animation: lwSpin 1s linear infinite; }
</style>
@endpush
@endsection
