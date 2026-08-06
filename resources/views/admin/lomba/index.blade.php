@extends('layouts.main')
@section('title', 'Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-lomba-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .lw-lomba-card {
        background: var(--lw-card); border: 1px solid var(--lw-border);
        border-radius: 18px; padding: 18px 18px 16px;
        display: flex; flex-direction: column; gap: 10px;
        box-shadow: var(--lw-shadow);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        position: relative; overflow: hidden;
    }
    .lw-lomba-card::before {
        content: ''; position: absolute; inset-inline: 0; top: 0; height: 3px;
        opacity: 0; transition: opacity .25s ease;
    }
    .lw-lomba-card:hover { transform: translateY(-4px); box-shadow: var(--lw-shadow-lg); border-color: var(--lw-primary-border); }
    .lw-lomba-card:hover::before { opacity: 1; }
    .lw-lomba-card.status-berlangsung::before { background: linear-gradient(90deg, #0e9f6e, #4ade80); opacity: 1; }
    .lw-lomba-card.status-selesai::before { background: linear-gradient(90deg, #7c3aed, #a78bfa); opacity: 1; }
    .lw-lomba-card.status-locked { opacity: .65; }
    .lw-lomba-card.status-locked:hover { opacity: .82; }

    .lw-lomba-top { display: flex; align-items: flex-start; gap: 12px; }
    .lw-lomba-avatar { width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 800; letter-spacing: .5px;
        box-shadow: 0 4px 12px -2px rgba(29, 43, 83, .35); }
    .lw-lomba-name { font-weight: 800; color: var(--lw-text); font-size: 14.5px; line-height: 1.3; word-break: break-word; }
    .lw-lomba-name a { color: inherit; text-decoration: none; }
    .lw-lomba-name a:hover { color: var(--lw-primary); }
    .lw-lomba-meta { font-size: 11px; color: var(--lw-text-3); margin-top: 3px; }
    .lw-lomba-meta i { margin-right: 3px; }

    .lw-lomba-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .lw-lomba-stat { background: var(--lw-bg); border-radius: 10px; padding: 9px 8px; text-align: center; }
    .lw-lomba-stat .v { font-size: 16px; font-weight: 800; color: var(--lw-text); line-height: 1; font-variant-numeric: tabular-nums; }
    .lw-lomba-stat .l { font-size: 9.5px; color: var(--lw-text-3); margin-top: 3px; font-weight: 600; text-transform: uppercase; letter-spacing: .2px; }

    .lw-lomba-readiness { display: flex; align-items: center; gap: 6px; }
    .lw-lomba-readiness .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .lw-lomba-readiness .dot.g { background: #22c55e; }
    .lw-lomba-readiness .dot.y { background: #f59e0b; }
    .lw-lomba-readiness .dot.o { background: #f97316; }
    .lw-lomba-readiness .dot.r { background: #ef4444; }
    .lw-lomba-readiness .bar { flex: 1; min-width: 40px; height: 5px; border-radius: 999px; background: var(--lw-bg); overflow: hidden; }
    .lw-lomba-readiness .fill { height: 100%; border-radius: 999px; transition: width .5s ease; }
    .lw-lomba-readiness .fill.g { background: linear-gradient(90deg, #22c55e, #0e9f6e); }
    .lw-lomba-readiness .fill.y { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .lw-lomba-readiness .fill.o { background: linear-gradient(90deg, #fb923c, #f97316); }
    .lw-lomba-readiness .fill.r { background: linear-gradient(90deg, #f87171, #ef4444); }
    .lw-lomba-readiness .lbl { font-size: 10px; font-weight: 600; color: var(--lw-text-3); white-space: nowrap; }

    .lw-lomba-actions { display: flex; gap: 8px; padding-top: 6px; border-top: 1px dashed var(--lw-border); }
    .lw-lomba-actions .lw-btn { flex: 1; }

    .lw-client-empty { display: none; padding: 32px 16px; text-align: center; color: var(--lw-text-3); }
    .lw-seg { display: inline-flex; align-items: center; gap: 6px; padding: 5px; border-radius: 999px; background: rgba(148, 163, 184, .12); border: 1px solid rgba(148, 163, 184, .2); }
    .lw-seg button {
        border: 0; background: transparent; color: var(--lw-text-3); border-radius: 999px;
        padding: 9px 14px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;
        transition: background .2s ease, color .2s ease, box-shadow .2s ease;
    }
    .lw-seg button.is-active { background: #fff; color: var(--lw-text); box-shadow: 0 8px 20px -14px rgba(15, 23, 42, .45); }
    .lw-listcard { display: none; }
    .lw-table-wrap { overflow: hidden; }
    .lw-table-scroll { overflow-x: auto; }
    .lw-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 920px; }
    .lw-table thead th {
        font-size: 11px; text-transform: uppercase; letter-spacing: .4px; font-weight: 800;
        color: var(--lw-text-3); background: var(--lw-bg); padding: 13px 14px; border-bottom: 1px solid var(--lw-border);
    }
    .lw-table tbody td {
        padding: 14px; border-bottom: 1px solid var(--lw-border); color: var(--lw-text); font-size: 13px; vertical-align: middle;
    }
    .lw-table tbody tr:last-child td { border-bottom: 0; }
    .lw-table .num { width: 58px; color: var(--lw-text-3); font-variant-numeric: tabular-nums; }

    @media (max-width: 1199.98px) { .lw-lomba-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767.98px) {
        .lw-lomba-grid { grid-template-columns: 1fr; }
        .lw-client-empty { display: block; }
    }
</style>

<div class="lw-mod jd-page-lomba">

@php
    $pageItems = $lombas->getCollection();
    $activeHaflah = $haflatuls->firstWhere('id', session('haflah_id'));
    $total = $lombas->total();
    $pageTotal = $pageItems->count();
    $currentCount = max(1, $pageTotal);

    $belumMulai = $pageItems->filter(fn($l) => $l->status === 'Belum Mulai')->count();
    $berlangsung = $pageItems->filter(fn($l) => $l->status === 'Berlangsung')->count();
    $selesai = $pageItems->filter(fn($l) => $l->status === 'Selesai')->count();
    $totalPeserta = $pageItems->sum('peserta_count');
    $totalJuri = $pageItems->sum('juri_count');
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
@endphp

<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-trophy-fill"></i></span>
            <div>
                <h1 class="lw-hero-title">Lomba</h1>
                <p class="lw-hero-sub">Competition Dashboard — pantau status, peserta, juri, dan progres setiap lomba dalam tampilan kartu.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-building-fill"></i>{{ optional($activeHaflah)->nama_acara ?? 'Haflah belum dipilih' }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-calendar-day"></i>{{ $today }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-hash"></i>{{ $total }} lomba</span>
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('lomba.create') }}" class="lw-btn lw-btn--light"><i class="bi bi-plus-lg"></i> Tambah</a>
            <a href="{{ route('lomba.index') }}" class="lw-btn lw-btn--light" style="border-color:rgba(255,255,255,.15);" title="Segarkan"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
@endif

<div class="lw-toolbar" id="lombaToolbar">
    <form id="lombaFilter" method="GET" style="display:contents;" autocomplete="off">
        <div class="lw-search" style="min-width:180px;">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" class="lw-control" id="lombaQuickSearch" placeholder="Cari nama lomba...">
        </div>
        <div class="lw-filter" style="min-width:150px;">
            <label>Haflah</label>
            <select name="haflah_id" class="lw-select">
                <option value="">Haflah Aktif</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }} ({{ $h->tahunAjaran->tahun_ajaran ?? '-' }})</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter" style="min-width:110px;">
            <label>Jenis</label>
            <select name="jenis" class="lw-select">
                <option value="">Semua Jenis</option>
                <option value="Individu" {{ request('jenis') == 'Individu' ? 'selected' : '' }}>Individu</option>
                <option value="Tim" {{ request('jenis') == 'Tim' ? 'selected' : '' }}>Tim</option>
            </select>
        </div>
        <div class="lw-filter" style="min-width:120px;">
            <label>Status</label>
            <select name="status" class="lw-select">
                <option value="">Semua Status</option>
                <option value="Belum Mulai" {{ request('status') == 'Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                <option value="Berlangsung" {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="lw-filter" style="min-width:80px;">
            <label>Entri</label>
            <select name="per_page" class="lw-select">
                @foreach([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int) $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="lw-form-section" style="margin-bottom:0;"><i class="bi bi-trophy-fill"></i> Daftar Lomba</div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div class="lw-seg" role="group" aria-label="Mode tampilan lomba">
                <button type="button" class="is-active" data-view="grid"><i class="bi bi-grid-3x3-gap-fill"></i> Grid</button>
                <button type="button" data-view="list"><i class="bi bi-list-ul"></i> List</button>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <span class="lw-chip lw-chip--amber"><i class="bi bi-hourglass-split"></i> Belum Mulai</span>
                <span class="lw-chip lw-chip--green"><i class="bi bi-play-fill"></i> Berlangsung</span>
                <span class="lw-chip lw-chip--violet"><i class="bi bi-check2-all"></i> Selesai</span>
            </div>
        </div>
    </div>

    @if($lombas->isEmpty())
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-trophy"></i></div></div>
            <div class="lw-empty-title">Belum Ada Lomba</div>
            <p class="lw-empty-sub">Mulai dengan menambahkan lomba pertama untuk Haflatul Imtihan yang sedang aktif.</p>
            <a href="{{ route('lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Lomba Pertama</a>
        </div>
    @else
        <div class="lw-client-empty" id="clientEmpty"><i class="bi bi-search mb-3" style="font-size:22px;display:inline-block;"></i>Tidak ada lomba yang cocok.</div>

        <div class="lw-lomba-grid" id="lombaCardGrid">
            @foreach($lombas as $l)
                @php
                    $isLocked = $l->is_haflah_selesai;
                    $hasChild = $l->peserta_count > 0 || $l->kelompok_count > 0 || $l->juri_count > 0 || $l->aspek_penilaians_count > 0 || $l->hasil_count > 0;

                    $statusClass = $l->status === 'Berlangsung' ? 'lw-chip--green' : ($l->status === 'Selesai' ? 'lw-chip--violet' : 'lw-chip--amber');
                    $cardClass = $isLocked ? 'status-locked' : ($l->status === 'Berlangsung' ? 'status-berlangsung' : ($l->status === 'Selesai' ? 'status-selesai' : ''));

                    $initials = lw_initial($l->nama);
                    $avaColor = lw_ava_color($l->nama);

                    $readinessPct = 0; $readinessColor = 'r'; $readinessLabel = 'Kosong';
                    if ($l->peserta_count > 0 && $l->juri_count > 0 && $l->aspek_penilaians_count > 0) {
                        if ($l->hasil_count > 0) { $readinessPct = 100; $readinessColor = 'g'; $readinessLabel = 'Siap'; }
                        else { $readinessPct = 85; $readinessColor = 'g'; $readinessLabel = 'Lengkap'; }
                    } elseif ($l->peserta_count > 0 && $l->juri_count > 0) {
                        $readinessPct = 60; $readinessColor = 'y'; $readinessLabel = 'Perlu Aspek';
                    } elseif ($l->peserta_count > 0 || $l->juri_count > 0) {
                        $readinessPct = 30; $readinessColor = 'o'; $readinessLabel = 'Dilengkapi';
                    }

                    $filterText = strtolower(trim($l->nama.' '.$l->jenis.' '.$l->status.' '.($l->haflatulImtihan->nama_acara ?? '')));

                    $kelasLabel = 'Semua';
                    if ($l->kelas_min && $l->kelas_max) $kelasLabel = 'K'. $l->kelas_min.'-'.$l->kelas_max;
                    elseif ($l->kelas_min) $kelasLabel = 'K'.$l->kelas_min.'+';
                    elseif ($l->kelas_max) $kelasLabel = 's/d K'.$l->kelas_max;
                @endphp
                <div class="lw-lomba-card {{ $cardClass }}" data-lomba-item data-filter="{{ $filterText }}">
                    <div class="lw-lomba-top">
                        <div class="lw-lomba-avatar" style="background:linear-gradient(135deg, {{ $avaColor }}, {{ $avaColor }}cc);">{{ $initials }}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="lw-lomba-name"><a href="{{ route('lomba.show', $l->id) }}">{{ $l->nama }}</a></div>
                                <span class="lw-chip {{ $statusClass }}"><i class="bi {{ lw_status_icon($l->status) }}"></i>{{ $l->status }}</span>
                            </div>
                            <div class="lw-lomba-meta">
                                <i class="bi bi-calendar3"></i>{{ $l->haflatulImtihan->nama_acara ?? '-' }}
                                &middot;
                                <span class="lw-chip {{ $l->jenis === 'Individu' ? 'lw-chip--navy' : 'lw-chip--violet' }}" style="display:inline-flex;font-size:10px;min-height:22px;padding:0 7px;">
                                    <i class="bi {{ $l->jenis === 'Individu' ? 'bi-person-fill' : 'bi-people-fill' }}"></i>{{ $l->jenis }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="lw-lomba-stats">
                        <div class="lw-lomba-stat"><div class="v">{{ $l->peserta_count }}</div><div class="l">Peserta</div></div>
                        <div class="lw-lomba-stat"><div class="v">{{ $l->juri_count }}</div><div class="l">Juri</div></div>
                        <div class="lw-lomba-stat"><div class="v">{{ $l->aspek_penilaians_count }}</div><div class="l">Aspek</div></div>
                        <div class="lw-lomba-stat"><div class="v">{{ $l->hasil_count }}</div><div class="l">Hasil</div></div>
                    </div>

                    <div class="lw-lomba-readiness">
                        <span class="dot {{ $readinessColor }}"></span>
                        <div class="bar"><div class="fill {{ $readinessColor }}" style="width:{{ $readinessPct }}%"></div></div>
                        <span class="lbl">{{ $readinessLabel }}</span>
                    </div>

                    <div style="font-size:11px;color:var(--lw-text-3);font-weight:600;">
                        <i class="bi bi-mortarboard-fill me-1"></i>Peserta: {{ $kelasLabel }}
                    </div>

                    <div class="lw-lomba-actions">
                        <a href="{{ route('lomba.show', $l->id) }}" class="lw-btn lw-btn--sm lw-btn--soft" title="Detail"><i class="bi bi-eye"></i> Detail</a>
                        <a href="{{ route('lomba.edit', $l->id) }}" class="lw-btn lw-btn--sm lw-btn--amber-soft {{ $isLocked ? 'lw-btn-lock' : '' }}" title="{{ $isLocked ? 'Haflah selesai' : 'Edit' }}" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil"></i> Edit</a>
                        <button type="button" class="lw-btn lw-btn--sm lw-btn--danger-soft {{ ($isLocked||$hasChild) ? 'lw-btn-lock' : '' }}" {{ ($isLocked||$hasChild) ? 'disabled' : '' }}
                            data-lomba-delete data-lomba-id="{{ $l->id }}" data-lomba-nama="{{ e($l->nama) }}" title="{{ $isLocked ? 'Haflah selesai' : ($hasChild ? 'Memiliki data terkait' : 'Hapus') }}"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="lw-listcard" id="lombaListCard">
            <div class="lw-card lw-table-wrap">
                <div class="lw-table-scroll">
                    <table class="lw-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Lomba</th>
                                <th>Haflah</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Peserta</th>
                                <th>Juri</th>
                                <th>Aspek</th>
                                <th>Hasil</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lombas as $l)
                                @php
                                    $isLocked = $l->is_haflah_selesai;
                                    $hasChild = $l->peserta_count > 0 || $l->kelompok_count > 0 || $l->juri_count > 0 || $l->aspek_penilaians_count > 0 || $l->hasil_count > 0;
                                    $statusClass = $l->status === 'Berlangsung' ? 'lw-chip--green' : ($l->status === 'Selesai' ? 'lw-chip--violet' : 'lw-chip--amber');
                                @endphp
                                <tr data-lomba-item data-filter="{{ strtolower(trim($l->nama.' '.$l->jenis.' '.$l->status.' '.($l->haflatulImtihan->nama_acara ?? ''))) }}">
                                    <td class="num">{{ ($lombas->firstItem() ?? 1) + $loop->index }}</td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;min-width:220px;">
                                            <span class="lw-lomba-avatar" style="width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg, {{ lw_ava_color($l->nama) }}, {{ lw_ava_color($l->nama) }}cc);font-size:12px;">{{ lw_initial($l->nama) }}</span>
                                            <div>
                                                <div style="font-weight:800;font-size:13px;"><a href="{{ route('lomba.show', $l->id) }}" style="color:inherit;text-decoration:none;">{{ $l->nama }}</a></div>
                                                <div style="font-size:11px;color:var(--lw-text-3);">{{ $l->kelas_min || $l->kelas_max ? 'Peserta '.$kelasLabel : 'Peserta Semua' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $l->haflatulImtihan->nama_acara ?? '-' }}</td>
                                    <td><span class="lw-chip {{ $l->jenis === 'Individu' ? 'lw-chip--navy' : 'lw-chip--violet' }}"><i class="bi {{ $l->jenis === 'Individu' ? 'bi-person-fill' : 'bi-people-fill' }}"></i>{{ $l->jenis }}</span></td>
                                    <td><span class="lw-chip {{ $statusClass }}"><i class="bi {{ lw_status_icon($l->status) }}"></i>{{ $l->status }}</span></td>
                                    <td>{{ $l->peserta_count }}</td>
                                    <td>{{ $l->juri_count }}</td>
                                    <td>{{ $l->aspek_penilaians_count }}</td>
                                    <td>{{ $l->hasil_count }}</td>
                                    <td>
                                        <div class="lw-lomba-actions" style="padding-top:0;border-top:0;min-width:190px;">
                                            <a href="{{ route('lomba.show', $l->id) }}" class="lw-btn lw-btn--sm lw-btn--soft" title="Detail"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('lomba.edit', $l->id) }}" class="lw-btn lw-btn--sm lw-btn--amber-soft {{ $isLocked ? 'lw-btn-lock' : '' }}" title="{{ $isLocked ? 'Haflah selesai' : 'Edit' }}" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil"></i></a>
                                            <button type="button" class="lw-btn lw-btn--sm lw-btn--danger-soft {{ ($isLocked||$hasChild) ? 'lw-btn-lock' : '' }}" {{ ($isLocked||$hasChild) ? 'disabled' : '' }} data-lomba-delete data-lomba-id="{{ $l->id }}" data-lomba-nama="{{ e($l->nama) }}" title="{{ $isLocked ? 'Haflah selesai' : ($hasChild ? 'Memiliki data terkait' : 'Hapus') }}"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <div style="font-size:12px;color:var(--lw-text-3);font-weight:500;">Menampilkan {{ $lombas->firstItem() ?? 0 }}-{{ $lombas->lastItem() ?? 0 }} dari {{ $total }} entri</div>
            <div>{{ $lombas->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>

</div>

<a href="{{ route('lomba.create') }}" class="lw-fab" aria-label="Tambah lomba"><i class="bi bi-plus-lg"></i></a>

<form id="lombaDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var toolbar = document.getElementById('lombaFilter');
    var searchInput = document.getElementById('lombaQuickSearch');
    var items = Array.from(document.querySelectorAll('[data-lomba-item]'));
    var emptyState = document.getElementById('clientEmpty');
    var deleteForm = document.getElementById('lombaDeleteForm');
    var grid = document.getElementById('lombaCardGrid');
    var list = document.getElementById('lombaListCard');
    var viewButtons = Array.from(document.querySelectorAll('.lw-seg button'));
    var view = 'grid';

    if (toolbar) {
        toolbar.querySelectorAll('select').forEach(function (el) {
            el.addEventListener('change', function () { toolbar.submit(); });
        });
    }

    function applySearch() {
        var q = searchInput ? searchInput.value.trim().toLowerCase() : '';
        var visible = 0;
        items.forEach(function (item) {
            var match = !q || (item.dataset.filter || '').indexOf(q) !== -1;
            item.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
    }

    if (searchInput) {
        var debounce;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(applySearch, 300);
        });
    }

    viewButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            viewButtons.forEach(function (node) { node.classList.remove('is-active'); });
            btn.classList.add('is-active');
            view = btn.dataset.view || 'grid';
            if (grid) grid.style.display = view === 'grid' ? 'grid' : 'none';
            if (list) list.style.display = view === 'list' ? 'block' : 'none';
            applySearch();
        });
    });

    (function staggerIn() {
        document.querySelectorAll('.lw-lomba-card').forEach(function (card, i) {
            card.style.opacity = '0'; card.style.transition = 'opacity .3s ease, transform .3s ease';
            setTimeout(function () { card.style.opacity = '1'; }, 40 + i * 50);
        });
    })();

    setTimeout(function() {
        document.querySelectorAll('.lw-lomba-readiness .fill').forEach(function(fill) {
            var w = fill.style.width; fill.style.width = '0';
            requestAnimationFrame(function() { fill.style.width = w; });
        });
    }, 200);

    applySearch();

    document.querySelectorAll('[data-lomba-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.lombaId, nama = btn.dataset.lombaNama;
            if (!id) return;
            LW.confirm('Hapus Lomba?', 'Lomba "' + nama + '" akan dihapus permanen.', 'bi-trash').then(function (ok) {
                if (ok) { deleteForm.action = '{{ url('lomba') }}/' + id; deleteForm.submit(); }
            });
        });
    });
})();
</script>
@endpush
@endsection
