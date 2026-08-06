@extends('layouts.main')
@section('title', 'Daftar Sesi')
@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-alert { display: flex; align-items: center; gap: 12px; border-radius: 14px; padding: 13px 16px; font-size: 13px; font-weight: 600;
        margin-bottom: 18px; border: 1px solid var(--jd-border); background: var(--jd-card); box-shadow: var(--jd-shadow); }
    .jd-alert--ok { border-color: var(--jd-green-border); background: var(--jd-green-soft); color: var(--jd-green); }
    .jd-alert--err { border-color: var(--jd-red-border); background: var(--jd-red-soft); color: var(--jd-red); }

    .jd-sesi-table { margin: 0; }
    .jd-sesi-table thead th {
        font-size: 11px; text-transform: uppercase; letter-spacing: .5px; font-weight: 700;
        color: var(--jd-text-3); border-bottom: 1px solid var(--jd-border) !important;
        background: var(--jd-bg); padding: 13px 15px; white-space: nowrap;
    }
    .jd-sesi-table tbody td {
        padding: 15px 16px; vertical-align: middle;
        border-top: 1px solid var(--jd-border-soft); font-size: 13px;
    }
    .jd-sesi-table tbody tr { transition: background .15s ease; }
    .jd-sesi-table tbody tr:hover { background: var(--jd-bg); }
    .jd-sesi-table tbody tr.is-locked { opacity: .65; }

    .jd-sesi-name { font-size: 13.5px; font-weight: 700; color: var(--jd-text); }
    .jd-sesi-name-sub { font-size: 11px; color: var(--jd-text-3); margin-top: 1px; }

    .jd-sesi-badge-date { background: #fdf2f8; color: #be185d; }
    .jd-sesi-badge-time { background: var(--jd-primary-soft); color: var(--jd-primary); }
    .jd-sesi-badge-empty { background: var(--jd-bg); color: var(--jd-text-3); }

    .jd-sesi-usage { display: inline-flex; align-items: center; gap: 6px; min-height: 28px; padding: 0 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
    .jd-sesi-usage.used { background: var(--jd-amber-soft); color: var(--jd-amber); }
    .jd-sesi-usage.free { background: var(--jd-green-soft); color: var(--jd-green); }

    .jd-sesi-actions { display: inline-flex; align-items: center; gap: 5px; }
    .jd-sesi-act {
        width: 36px; height: 36px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--jd-border); background: var(--jd-card); color: var(--jd-text-2);
        font-size: 14px; cursor: pointer; transition: all .2s ease; text-decoration: none;
    }
    .jd-sesi-act:hover { transform: translateY(-1px); box-shadow: var(--jd-shadow); border-color: var(--jd-primary-border); color: var(--jd-primary); }
    .jd-sesi-act.edit:hover { color: var(--jd-amber); }
    .jd-sesi-act.del:hover { color: var(--jd-red); }
    .jd-sesi-act.is-disabled { opacity: .4; cursor: not-allowed; }

    .jd-mobile-card { border: 1px solid var(--jd-border); border-radius: var(--jd-radius); background: var(--jd-card); box-shadow: var(--jd-shadow); padding: 15px; }
    .jd-mobile-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
    .jd-mobile-card-title { font-size: 15px; font-weight: 700; color: var(--jd-text); margin: 0; }
    .jd-mobile-card-sub { font-size: 11px; color: var(--jd-text-3); margin-top: 2px; }
    .jd-mobile-card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 12px; }
    .jd-mobile-card-field { padding: 9px 11px; border-radius: 10px; background: var(--jd-bg); border: 1px solid var(--jd-border); }
    .jd-mobile-card-field .lbl { display: block; font-size: 9.5px; text-transform: uppercase; letter-spacing: .5px; font-weight: 700; color: var(--jd-text-3); margin-bottom: 3px; }
    .jd-mobile-card-field .val { font-size: 12.5px; font-weight: 600; color: var(--jd-text); }
    .jd-mobile-card-actions { display: flex; gap: 8px; margin-top: 12px; }
    .jd-mobile-card-actions .jd-sesi-act { flex: 1; width: auto; min-height: 42px; }
    .jd-mobile-card.locked { opacity: .7; }

    .jd-sesi-empty-client { display: none; padding: 32px 16px; text-align: center; color: var(--jd-text-3); }

    @media (max-width: 767.98px) {
        .jd-sesi-empty-client { display: block; }
    }
</style>

<div class="jd-mod jd-page-sesi">

@php
    $pageItems = $sesis->getCollection();
    $activeHaflah = $haflatuls->firstWhere('id', session('haflah_id'));
    $selectedHaflah = request('haflah_id') ? $haflatuls->firstWhere('id', (int) request('haflah_id')) : $activeHaflah;
    $total = $sesis->total();
    $pageTotal = $pageItems->count();
    $currentCount = max(1, $pageTotal);

    $usageCounts = \App\Models\SesiLomba::selectRaw('nama, count(*) as count')
        ->whereIn('nama', $usedNames)
        ->groupBy('nama')
        ->pluck('count', 'nama')
        ->toArray();

    $freeCount = $pageItems->filter(fn($s) => !$s->is_haflah_selesai && !in_array($s->nama, $usedNames))->count();
    $usedCount = $pageItems->filter(fn($s) => in_array($s->nama, $usedNames) && !$s->is_haflah_selesai)->count();
    $lockedCount = $pageItems->filter(fn($s) => $s->is_haflah_selesai)->count();

    $pagiCount = $pageItems->filter(fn($s) => $s->jam_mulai && \Carbon\Carbon::parse($s->jam_mulai)->format('H') >= 6 && \Carbon\Carbon::parse($s->jam_mulai)->format('H') < 12)->count();
    $siangCount = $pageItems->filter(fn($s) => $s->jam_mulai && \Carbon\Carbon::parse($s->jam_mulai)->format('H') >= 12 && \Carbon\Carbon::parse($s->jam_mulai)->format('H') < 18)->count();
    $soreCount = $pageItems->filter(fn($s) => $s->jam_mulai && \Carbon\Carbon::parse($s->jam_mulai)->format('H') >= 18)->count();
    $noTimeCount = $pageItems->filter(fn($s) => !$s->jam_mulai)->count();
    $timelineTotal = max(1, $pagiCount + $siangCount + $soreCount + $noTimeCount);
@endphp


<div class="jd-hero">
    <div class="jd-hero-grid">
        <div class="jd-hero-left">
            <span class="jd-hero-icon"><i class="fas fa-clock"></i></span>
            <div>
                <h1 class="jd-hero-title">Daftar Sesi</h1>
                <p class="jd-hero-sub">Kelola nama sesi, jam dasar, dan status pemakaian untuk Haflatul Imtihan.</p>
                <div class="jd-hero-badges">
                    <span class="jd-hero-badge"><i class="fas fa-calendar-alt"></i>{{ $tahunAktifGlobal->tahun_ajaran ?? '-' }}</span>
                    <span class="jd-hero-badge"><i class="fas fa-calendar-check"></i>{{ optional($selectedHaflah)->nama_acara ?? optional($activeHaflah)->nama_acara ?? 'Haflah belum dipilih' }}</span>
                    <span class="jd-hero-badge"><i class="fas fa-hashtag"></i>{{ $total }} sesi</span>
                </div>
            </div>
        </div>
        <div class="jd-hero-right">
            <a href="{{ route('sesi.create') }}" class="jd-btn jd-btn--light"><i class="fas fa-plus"></i> Tambah</a>
            <a href="{{ route('sesi.index') }}" class="jd-btn jd-btn--light" style="border-color:rgba(255,255,255,.15);"><i class="fas fa-sync-alt"></i></a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="jd-alert jd-alert--ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="jd-alert jd-alert--err"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
@endif


<div class="jd-kpi-grid">
    <div class="jd-kpi">
        <span class="jd-kpi-icon blue"><i class="fas fa-layer-group"></i></span>
        <div>
            <div class="jd-kpi-num js-counter" data-count="{{ $total }}">0</div>
            <div class="jd-kpi-label">Total Sesi</div>
            <div class="jd-kpi-sub">Semua sesi dalam filter</div>
        </div>
        <span class="jd-kpi-watermark"><i class="fas fa-layer-group"></i></span>
    </div>
    <div class="jd-kpi">
        <span class="jd-kpi-icon green"><i class="fas fa-unlock-alt"></i></span>
        <div>
            <div class="jd-kpi-num js-counter" data-count="{{ $freeCount }}">0</div>
            <div class="jd-kpi-label">Belum Dipakai</div>
            <div class="jd-kpi-sub">Siap dipilih di Sesi Lomba</div>
        </div>
        <span class="jd-kpi-watermark"><i class="fas fa-unlock-alt"></i></span>
    </div>
    <div class="jd-kpi">
        <span class="jd-kpi-icon amber"><i class="fas fa-project-diagram"></i></span>
        <div>
            <div class="jd-kpi-num js-counter" data-count="{{ $usedCount }}">0</div>
            <div class="jd-kpi-label">Dipakai Lomba</div>
            <div class="jd-kpi-sub">Sudah digunakan Sesi Lomba</div>
        </div>
        <span class="jd-kpi-watermark"><i class="fas fa-project-diagram"></i></span>
    </div>
    <div class="jd-kpi">
        <span class="jd-kpi-icon violet"><i class="fas fa-lock"></i></span>
        <div>
            <div class="jd-kpi-num js-counter" data-count="{{ $lockedCount }}">0</div>
            <div class="jd-kpi-label">Terkunci</div>
            <div class="jd-kpi-sub">Haflah sudah selesai</div>
        </div>
        <span class="jd-kpi-watermark"><i class="fas fa-lock"></i></span>
    </div>
</div>


@if($pageTotal > 0 && $timelineTotal > 0)
<div class="jd-card jd-card-pad" style="margin-bottom:20px; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:6px;">
        <i class="fas fa-chart-bar" style="color:var(--jd-primary);font-size:13px;"></i>
        <span style="font-size:11.5px;font-weight:600;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Distribusi Waktu</span>
    </div>
    <div style="flex:1; min-width:200px;">
        <div style="display:flex;height:8px;border-radius:999px;overflow:hidden;background:var(--jd-bg);gap:2px;">
            <div style="width:{{ round(($pagiCount/$timelineTotal)*100) }}%;background:linear-gradient(90deg,#fbbf24,#f59e0b);border-radius:999px;" title="Pagi: {{ $pagiCount }}"></div>
            <div style="width:{{ round(($siangCount/$timelineTotal)*100) }}%;background:linear-gradient(90deg,#60a5fa,#3b82f6);border-radius:999px;" title="Siang: {{ $siangCount }}"></div>
            <div style="width:{{ round(($soreCount/$timelineTotal)*100) }}%;background:linear-gradient(90deg,#a78bfa,#8b5cf6);border-radius:999px;" title="Sore: {{ $soreCount }}"></div>
            <div style="width:{{ round(($noTimeCount/$timelineTotal)*100) }}%;background:{{ $noTimeCount ? '#cbd5e1' : 'transparent' }};border-radius:999px;" title="Fleksibel: {{ $noTimeCount }}"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:4px;font-size:10px;color:var(--jd-text-3);font-weight:600;">
            <span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
        </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <span class="jd-chip"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#f59e0b;"></span> Pagi {{ $pagiCount }}</span>
        <span class="jd-chip"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#3b82f6;"></span> Siang {{ $siangCount }}</span>
        <span class="jd-chip"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#8b5cf6;"></span> Sore {{ $soreCount }}</span>
        @if($noTimeCount)
        <span class="jd-chip"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#cbd5e1;"></span> Fleksibel {{ $noTimeCount }}</span>
        @endif
    </div>
</div>
@endif


<div class="jd-toolbar" id="sesiToolbar">
    <form id="sesiFilter" method="GET" style="display:contents;" autocomplete="off">
        <div class="jd-search" style="min-width:200px;">
            <i class="fas fa-search"></i>
            <input type="search" class="jd-control" id="sesiQuickSearch" placeholder="Cari nama, tanggal, status...">
        </div>
        <div class="jd-filter">
            <label>Haflah</label>
            <select name="haflah_id" class="jd-select">
                <option value="">Semua Haflah</option>
                @foreach($haflatuls as $h)
                        <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
        </div>
        <div class="jd-filter" style="min-width:90px;">
            <label>Entri</label>
            <select name="per_page" class="jd-select">
                @foreach([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int) $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="jd-toolbar-actions">
            <a href="{{ route('sesi.index') }}" class="jd-btn jd-btn--ghost"><i class="fas fa-undo-alt"></i> Reset</a>
        </div>
    </form>
</div>


<div class="jd-card" style="overflow:hidden;">
    <div class="jd-card-header">
        <div>
            <div class="jd-section-title"><i class="fas fa-table"></i> Data Sesi</div>
            <div class="jd-section-sub" style="margin-bottom:0;">Status dan pemakaian langsung terlihat tanpa membuka detail.</div>
        </div>
        <span class="jd-chip jd-chip--blue"><i class="fas fa-filter"></i> {{ $pageTotal }} dari {{ $total }}</span>
    </div>

    @if($sesis->isEmpty())
        <div class="jd-empty">
            <div class="jd-empty-illus"><div class="ring"></div><div class="core"><i class="fas fa-calendar-week"></i></div></div>
            <div class="jd-empty-title">Belum Ada Sesi</div>
            <div class="jd-empty-sub">Mulai dengan menambahkan sesi pertama untuk dipakai di modul Sesi Lomba pada Haflatul Imtihan.</div>
            <a href="{{ route('sesi.create') }}" class="jd-btn jd-btn--solid"><i class="fas fa-plus"></i> Tambah Sesi Pertama</a>
        </div>
    @else
        <div class="jd-sesi-empty-client" id="clientEmpty"><i class="fas fa-search mb-3" style="font-size:22px;"></i>Tidak ada sesi yang cocok.</div>

        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table jd-sesi-table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Sesi</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Pemakaian</th>
                            <th>Update</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="sesiTableBody">
                        @foreach($sesis as $s)
                            @php
                                $isUsed = in_array($s->nama, $usedNames);
                                $isLocked = $s->is_haflah_selesai || $isUsed;
                                $lombaCount = $usageCounts[$s->nama] ?? 0;
                                if ($s->is_haflah_selesai) {
                                    $statusLabel = 'Haflah Selesai'; $statusClass = 'jd-chip--red';
                                } elseif ($isUsed) {
                                    $statusLabel = 'Dipakai Lomba'; $statusClass = 'jd-chip--amber';
                                } else {
                                    $statusLabel = 'Dapat Diedit'; $statusClass = 'jd-chip--green';
                                }
                                $tanggalLabel = $s->tanggal ? \Carbon\Carbon::parse($s->tanggal)->isoFormat('D MMM YYYY') : 'Belum Ditentukan';
                                $jamLabel = ($s->jam_mulai || $s->jam_selesai)
                                    ? (($s->jam_mulai ? \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') : '??').' - '.($s->jam_selesai ? \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') : '??'))
                                    : 'Fleksibel';
                                $updatedLabel = optional($s->updated_at)->translatedFormat('d M Y H:i') ?? '-';
                                $filterText = strtolower(trim($s->nama.' '.$tanggalLabel.' '.$jamLabel.' '.$statusLabel.' '.$updatedLabel));
                            @endphp
                            <tr class="{{ $isLocked ? 'is-locked' : '' }}" data-sesi-item data-filter="{{ $filterText }}">
                                <td>
                                    <div class="jd-sesi-name">{{ $s->nama }}</div>
                                    @if($isLocked)<div class="jd-sesi-name-sub"><i class="fas fa-lock me-1" style="font-size:10px;"></i>{{ $s->is_haflah_selesai ? 'Haflah selesai' : 'Dipakai lomba' }}</div>@endif
                                </td>
                                <td>
                                    @if($s->tanggal)
                                        <span class="jd-chip jd-sesi-badge-date"><i class="fas fa-calendar-day"></i>{{ $tanggalLabel }}</span>
                                    @else
                                        <span class="jd-chip jd-sesi-badge-empty"><i class="fas fa-calendar-times"></i>Belum Ditentukan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->jam_mulai || $s->jam_selesai)
                                        <span class="jd-chip jd-sesi-badge-time"><i class="fas fa-clock"></i>{{ $jamLabel }}</span>
                                    @else
                                        <span class="jd-chip jd-sesi-badge-empty"><i class="fas fa-calendar-alt"></i>Fleksibel</span>
                                    @endif
                                </td>
                                <td><span class="jd-chip {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td>
                                    <span class="jd-sesi-usage {{ $isUsed ? 'used' : 'free' }}">
                                        <i class="fas {{ $isUsed ? 'fa-project-diagram' : 'fa-check' }}"></i>
                                        {{ $isUsed ? 'Dipakai '.$lombaCount.' lomba' : 'Belum dipakai' }}
                                    </span>
                                </td>
                                <td><span style="font-size:12px;color:var(--jd-text-3);font-weight:500;"><i class="far fa-clock me-1"></i>{{ $updatedLabel }}</span></td>
                                <td class="text-end">
                                    <div class="jd-sesi-actions">
                                        <a href="{{ route('sesi.edit', $s->id) }}" class="jd-sesi-act edit {{ $isLocked ? 'is-disabled' : '' }}" {{ $isLocked ? 'tabindex=-1' : '' }} title="{{ $isLocked ? ($s->is_haflah_selesai ? 'Haflah selesai' : 'Dipakai lomba') : 'Edit' }}"><i class="fas fa-edit"></i></a>
                                        <button type="button" class="jd-sesi-act del {{ $isLocked ? 'is-disabled' : '' }}" {{ $isLocked ? 'disabled' : '' }} data-sesi-delete data-sesi-id="{{ $s->id }}" data-sesi-nama="{{ e($s->nama) }}" title="{{ $isLocked ? ($s->is_haflah_selesai ? 'Haflah selesai' : 'Dipakai lomba') : 'Hapus' }}"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-md-none" id="sesiMobileStack" style="padding:16px; display:grid; gap:10px;">
            @foreach($sesis as $s)
                @php
                    $isUsed = in_array($s->nama, $usedNames);
                    $isLocked = $s->is_haflah_selesai || $isUsed;
                    $lombaCount = $usageCounts[$s->nama] ?? 0;
                    $statusLabel = $s->is_haflah_selesai ? 'Haflah Selesai' : ($isUsed ? 'Dipakai Lomba' : 'Dapat Diedit');
                    $statusClass = $s->is_haflah_selesai ? 'jd-chip--red' : ($isUsed ? 'jd-chip--amber' : 'jd-chip--green');
                    $tanggalLabel = $s->tanggal ? \Carbon\Carbon::parse($s->tanggal)->isoFormat('D MMM YYYY') : 'Belum Ditentukan';
                    $jamLabel = ($s->jam_mulai || $s->jam_selesai)
                        ? (($s->jam_mulai ? \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') : '??').' - '.($s->jam_selesai ? \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') : '??'))
                        : 'Fleksibel';
                    $updatedLabel = optional($s->updated_at)->translatedFormat('d M Y H:i') ?? '-';
                    $filterText = strtolower(trim($s->nama.' '.$tanggalLabel.' '.$jamLabel.' '.$statusLabel.' '.$updatedLabel));
                @endphp
                <article class="jd-mobile-card {{ $isLocked ? 'locked' : '' }}" data-sesi-item data-filter="{{ $filterText }}">
                    <div class="jd-mobile-card-head">
                        <div><h3 class="jd-mobile-card-title">{{ $s->nama }}</h3>@if($isLocked)<div class="jd-mobile-card-sub"><i class="fas fa-lock me-1" style="font-size:9px;"></i>{{ $s->is_haflah_selesai ? 'Haflah selesai' : 'Dipakai lomba' }}</div>@endif</div>
                        <span class="jd-chip {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="jd-mobile-card-grid">
                        <div class="jd-mobile-card-field"><span class="lbl">Tanggal</span><span class="val">{{ $tanggalLabel }}</span></div>
                        <div class="jd-mobile-card-field"><span class="lbl">Jam</span><span class="val">{{ $jamLabel }}</span></div>
                        <div class="jd-mobile-card-field"><span class="lbl">Pemakaian</span><span class="val">{{ $isUsed ? $lombaCount.' lomba' : 'Belum' }}</span></div>
                        <div class="jd-mobile-card-field"><span class="lbl">Update</span><span class="val">{{ $updatedLabel }}</span></div>
                    </div>
                    <div class="jd-mobile-card-actions">
                        <a href="{{ route('sesi.edit', $s->id) }}" class="jd-sesi-act edit {{ $isLocked ? 'is-disabled' : '' }}" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="fas fa-edit"></i></a>
                        <button type="button" class="jd-sesi-act del {{ $isLocked ? 'is-disabled' : '' }}" {{ $isLocked ? 'disabled' : '' }} data-sesi-delete data-sesi-id="{{ $s->id }}" data-sesi-nama="{{ e($s->nama) }}"><i class="fas fa-trash"></i></button>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 p-3">
            <div style="font-size:12px;color:var(--jd-text-3);font-weight:500;">Menampilkan {{ $sesis->firstItem() ?? 0 }}-{{ $sesis->lastItem() ?? 0 }} dari {{ $total }} entri</div>
            <div>{{ $sesis->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>

</div>

<a href="{{ route('sesi.create') }}" class="jd-fab" aria-label="Tambah sesi"><i class="fas fa-plus"></i></a>

<form id="sesiDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var toolbar = document.getElementById('sesiFilter');
    var searchInput = document.getElementById('sesiQuickSearch');
    var items = Array.from(document.querySelectorAll('[data-sesi-item]'));
    var emptyState = document.getElementById('clientEmpty');
    var deleteForm = document.getElementById('sesiDeleteForm');

    if (toolbar) {
        toolbar.querySelectorAll('select').forEach(function (el) {
            el.addEventListener('change', function () { toolbar.submit(); });
        });
    }

    if (searchInput) {
        var debounce;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(function () {
                var q = searchInput.value.trim().toLowerCase();
                var visible = 0;
                items.forEach(function (item) {
                    var match = !q || (item.dataset.filter || '').indexOf(q) !== -1;
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
            }, 300);
        });
    }

    document.querySelectorAll('.js-counter').forEach(function (el) {
        var target = parseInt(el.dataset.count || '0', 10);
        var current = 0;
        var step = Math.max(1, Math.ceil(target / 26));
        function tick() { current = Math.min(target, current + step); el.textContent = current; if (current < target) requestAnimationFrame(tick); }
        tick();
    });

    (function staggerIn() {
        document.querySelectorAll('.jd-sesi-table tbody tr').forEach(function (row, i) {
            row.style.opacity = '0'; row.style.transition = 'opacity .3s ease';
            setTimeout(function () { row.style.opacity = '1'; }, 40 + i * 50);
        });
    })();

    document.querySelectorAll('[data-sesi-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.sesiId, nama = btn.dataset.sesiNama;
            if (!id) return;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Sesi?',
                    html: 'Sesi <strong>"' + nama + '"</strong> akan dihapus permanen.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i>Ya, Hapus',
                    cancelButtonText: 'Batal', reverseButtons: true,
                    customClass: { popup: 'rounded-4 border-0 shadow-lg' }
                }).then(function (r) { if (r.isConfirmed) { deleteForm.action = '{{ url('sesi') }}/' + id; deleteForm.submit(); } });
            } else {
                if (confirm('Hapus sesi "' + nama + '"?')) { deleteForm.action = '{{ url('sesi') }}/' + id; deleteForm.submit(); }
            }
        });
    });
})();
</script>
@endpush
@endsection
