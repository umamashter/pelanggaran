@extends('layouts.main')

@section('title','Guru Pengampu Mata Pelajaran')

@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    /* ---------- Alerts ---------- */
    .jd-alert { display: flex; align-items: center; gap: 12px; border-radius: 14px; padding: 13px 16px; font-size: 13px; font-weight: 600;
        margin-bottom: 18px; border: 1px solid var(--jd-border); background: var(--jd-card); box-shadow: var(--jd-shadow); }
    .jd-alert i { font-size: 16px; flex-shrink: 0; }
    .jd-alert b { font-weight: 700; }
    .jd-alert span { font-weight: 500; opacity: .85; }
    .jd-alert--warn { border-color: var(--jd-amber-border); background: var(--jd-amber-soft); color: var(--jd-amber); }
    .jd-alert--err { border-color: var(--jd-red-border); background: var(--jd-red-soft); color: var(--jd-red); }
    .jd-alert--ok { border-color: var(--jd-green-border); background: var(--jd-green-soft); color: var(--jd-green); }
    .jd-alert--info { border-color: var(--jd-primary-border); background: var(--jd-primary-soft); color: var(--jd-primary); }

    /* ---------- Modal ---------- */
    .jd-modal-card { border: none !important; border-radius: 20px !important; overflow: hidden; background: var(--jd-card); box-shadow: 0 25px 60px rgba(15,23,42,.18); }
    .jd-modal-head { position: relative; padding: 24px 24px 20px; color: #fff; background: var(--mc, #2563eb); }
    .jd-modal-head::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(255,255,255,.14), rgba(0,0,0,.16)); pointer-events: none; }
    .jd-modal-head > * { position: relative; z-index: 1; }
    .jd-modal-head .btn-close { z-index: 2 !important; }

    .jd-wizard-pane { min-height: 140px; }
    .jd-wizard-hint { font-size: 12px; color: var(--jd-text-3); margin-top: 10px; display: flex; align-items: center; gap: 6px; }
    .jd-wizard-hint i { color: var(--jd-primary); }
    .jd-mod .form-label { font-size: 12px; font-weight: 700; color: var(--jd-text-2); margin-bottom: 6px; }
    .jd-control[readonly] { background: var(--jd-bg); color: var(--jd-text-3); cursor: not-allowed; }

    /* ---------- Toolbar ---------- */
    .jd-toolbar-pengampu { align-items: center; }
    .jd-filter-group { display: flex; flex-wrap: wrap; gap: 10px; }
    .jd-search { flex: 1 1 200px; min-width: 180px; }
    .jd-min-w { min-width: 0; }
    .jd-seg { display:inline-flex; align-items:center; gap:6px; padding:5px; border-radius:999px; background:var(--jd-bg); border:1px solid var(--jd-border); }
    .jd-seg button { border:0; background:transparent; color:var(--jd-text-3); border-radius:999px; padding:8px 14px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:8px; transition:all .2s ease; }
    .jd-seg button.is-active { background:#fff; color:var(--jd-text); box-shadow:0 8px 20px -14px rgba(15,23,42,.45); }
    html.dark-mode .jd-seg button.is-active { background:rgba(255,255,255,.08); color:var(--jd-text); }
    .jd-grid-list { display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; align-items:start; }
    .jd-grid-card { background:var(--jd-card); border:1px solid var(--jd-border); border-radius:18px; overflow:hidden; box-shadow:var(--jd-shadow);
        display:flex; flex-direction:column; transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
    .jd-grid-card:hover { transform:translateY(-3px); box-shadow:var(--jd-shadow-lg); border-color:var(--jd-primary-border); }
    .jd-grid-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px 18px 0; }
    .jd-grid-head .jd-guru { min-width:0; }
    .jd-grid-subject { display:flex; align-items:center; justify-content:space-between; gap:10px; margin:14px 18px 0; padding:10px 13px; border-radius:11px;
        background:var(--mc-soft, var(--jd-primary-soft)); border:1px solid var(--mc-border, var(--jd-primary-border)); }
    .jd-grid-subject b { font-size:13px; color:var(--mc, var(--jd-primary)); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .jd-grid-subject b i { font-size:11.5px; }
    .jd-grid-meta { display:grid; grid-template-columns:1fr 1fr; gap:12px 16px; padding:16px 18px; }
    .jd-meta-item { display:flex; align-items:center; gap:9px; min-width:0; }
    .jd-meta-item i { color:var(--jd-primary); font-size:12px; flex-shrink:0; width:16px; text-align:center; }
    .jd-meta-item .lbl { font-size:9.5px; font-weight:700; color:var(--jd-text-3); text-transform:uppercase; letter-spacing:.5px; }
    .jd-meta-item .val { font-size:12.5px; font-weight:700; color:var(--jd-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .jd-grid-foot { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:12px 18px; border-top:1px solid var(--jd-border-soft); background:var(--jd-bg); }
    .jd-listcard { display:none; }
    @media (max-width: 1199.98px) { .jd-grid-list { grid-template-columns:repeat(2, 1fr); } }
    @media (max-width: 767.98px) { .jd-grid-list { grid-template-columns:1fr; } }

    /* ---------- Table ---------- */
    .jd-table-card { overflow: hidden; }
    .jd-mod .table-jd { margin: 0; --bs-table-bg: transparent; }
    .jd-mod .table-jd > thead th { font-size: 10.5px; text-transform: uppercase; letter-spacing: .5px; color: var(--jd-text-3);
        background: var(--jd-bg); border-bottom: 1px solid var(--jd-border); padding: 12px 14px; white-space: nowrap; }
    .jd-mod .table-jd > tbody td { padding: 12px 14px; font-size: 13px; color: var(--jd-text-2); border-color: var(--jd-border-soft); vertical-align: middle; }
    .jd-mod .table-jd > tbody tr { transition: background .15s ease; }
    .jd-mod .table-jd > tbody tr:hover td { background: var(--jd-bg); }
    .jd-num { color: var(--jd-text-3); font-variant-numeric: tabular-nums; }

    #pengampuTable_wrapper .dataTables_length,
    #pengampuTable_wrapper .dataTables_filter { display: none !important; }
    .jd-mod .dataTables_info { font-size: 11.5px; color: var(--jd-text-3); padding-top: 14px; }
    .jd-mod .dataTables_paginate { padding-top: 14px; }
    .jd-mod .pagination { margin: 0; }
    .jd-mod .pagination .page-link { color: var(--jd-text-2); border-color: var(--jd-border); background: var(--jd-card); font-size: 12.5px; min-width: 32px; text-align: center; }
    .jd-mod .pagination .page-link:hover { color: var(--jd-primary); border-color: var(--jd-primary-border); background: var(--jd-primary-soft); }
    .jd-mod .pagination .active .page-link { background: var(--jd-grad); border-color: transparent; color: #fff; box-shadow: 0 4px 12px -4px rgba(37,99,235,.6); }
    .jd-mod .pagination .disabled .page-link { opacity: .5; }

    /* ---------- Cells ---------- */
    .jd-guru { display: flex; align-items: center; gap: 11px; min-width: 190px; }
    .jd-guru-avatar { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; color: #fff; flex-shrink: 0; letter-spacing: .5px; }
    .jd-ava-0 { background: linear-gradient(135deg, #2563eb, #60a5fa); }
    .jd-ava-1 { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
    .jd-ava-2 { background: linear-gradient(135deg, #16a34a, #4ade80); }
    .jd-ava-3 { background: linear-gradient(135deg, #d97706, #fbbf24); }
    .jd-guru b { display: block; font-size: 13px; color: var(--jd-text); line-height: 1.3; }
    .jd-guru span { font-size: 11px; color: var(--jd-text-3); }

    .jd-mapel-chip { display: inline-flex; align-items: center; gap: 7px; background: var(--mc-soft, var(--jd-primary-soft));
        color: var(--mc, var(--jd-primary)); border: 1px solid var(--mc-border, var(--jd-primary-border));
        border-radius: 9px; padding: 5px 10px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .jd-mapel-chip i { font-size: 7px; }

    .jd-cell-icon { display: inline-flex; align-items: center; gap: 7px; color: var(--jd-text-2); font-size: 12.5px; white-space: nowrap; }
    .jd-cell-icon i { color: var(--jd-primary); font-size: 11.5px; }

    /* ---------- Actions ---------- */
    .jd-actions { display: inline-flex; gap: 6px; }
    .jd-btn--amber-soft { background: var(--jd-amber-soft); color: var(--jd-amber); border-color: var(--jd-amber-border); }
    .jd-btn--amber-soft:hover { background: rgba(217,119,6,.18); color: var(--jd-amber); }
    .jd-btn--danger-soft { background: var(--jd-red-soft); color: var(--jd-red); border-color: var(--jd-red-border); }
    .jd-btn--danger-soft:hover { background: rgba(220,38,38,.16); color: var(--jd-red); }
    .jd-btn-lock { pointer-events: none; opacity: .45; }

    /* ---------- Salin box ---------- */
    .jd-salin-box { display: flex; align-items: center; gap: 12px; border-radius: 12px; padding: 12px 14px;
        background: var(--jd-bg); border: 1px solid var(--jd-border); border-left: 4px solid var(--jd-primary); }
    .jd-salin-box--green { border-left-color: var(--jd-green); }
    .jd-salin-box .jd-sb-ico { width: 38px; height: 38px; border-radius: 11px; display: inline-flex; align-items: center; justify-content: center;
        background: var(--jd-primary-soft); color: var(--jd-primary); font-size: 15px; flex-shrink: 0; }
    .jd-salin-box--green .jd-sb-ico { background: var(--jd-green-soft); color: var(--jd-green); }
    .jd-salin-box .lbl { font-size: 10px; font-weight: 700; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .jd-salin-box .val { font-size: 14px; font-weight: 700; color: var(--jd-text); }

    /* ---------- Responsive ---------- */
    .jd-fab { display: none; }
    @media (max-width: 991.98px) { .jd-fab { display: inline-flex; } }
    @media (max-width: 767.98px) {
        .jd-step-txt { display: none; }
        .jd-step { justify-content: center; }
        .jd-stepper { gap: 4px; }
        .jd-step-line { margin: 0 6px; min-width: 12px; }
        .jd-toolbar { top: 66px; }
    }
</style>

<div class="jd-mod jd-page-pengampu">

@php
    $totalPengampu = $pengampus->count();

    $jdTahunJson = $tahunAjarans->map(function ($ta) {
        return ['id' => $ta->id, 'tahun_ajaran' => $ta->tahun_ajaran];
    })->values();
    $jdTahunAktifJson = $tahunAktif ? ['id' => $tahunAktif->id, 'tahun_ajaran' => $tahunAktif->tahun_ajaran] : null;
    $jdExistingJson = $existingPengampus->map(function ($p) {
        return ['guru_id' => $p->guru_id, 'mata_pelajaran_id' => $p->mata_pelajaran_id, 'kelas_id' => $p->kelas_id];
    })->values();
@endphp

{{-- ===== HERO ===== --}}
<div class="jd-hero">
    <div class="jd-hero-grid">
        <div class="jd-hero-left">
            <div class="jd-hero-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div>
                <h1 class="jd-hero-title">Pengampu Mapel</h1>
                <p class="jd-hero-sub">Tentukan guru yang mengajar mata pelajaran di setiap kelas. Pengampu yang sudah dipakai pada jadwal pelajaran terkunci dan tidak dapat diubah.</p>
                <div class="jd-hero-badges">
                    <span class="jd-hero-badge"><i class="fas fa-calendar-day"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="jd-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran ?? 'Belum ada TA aktif' }}</span>
                    <span class="jd-hero-badge jd-hero-badge--ok"><i class="fas fa-users"></i> {{ $totalPengampu }} Pengampu</span>
                    @if($guruTerlibat > 0)
                    <span class="jd-hero-badge"><i class="fas fa-user-graduate"></i> {{ $guruTerlibat }} Guru</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="jd-hero-right">
            @if($sudahDisalin)
            <span class="jd-btn jd-btn--light" style="opacity:.9;pointer-events:none;" title="Data tahun ajaran aktif sudah ada"><i class="fas fa-check-circle"></i> Data sudah ada</span>
            @else
            <button type="button" class="jd-btn jd-btn--light" data-bs-toggle="modal" data-bs-target="#modalSalinPengampu"><i class="fas fa-copy"></i> Salin</button>
            @endif
            <button type="button" class="jd-btn jd-btn--light" data-bs-toggle="modal" data-bs-target="#modalTambahPengampu"><i class="fas fa-plus"></i> Tambah</button>
        </div>
    </div>
</div>

{{-- ===== ALERTS ===== --}}
@if(session('success'))
<div class="jd-alert jd-alert--ok">
    <i class="fas fa-check-circle"></i>
    <div><b>Berhasil</b> &middot; <span>{{ session('success') }}</span></div>
    <button type="button" class="jd-toast-close" style="margin-left:auto;background:none;border:none;color:var(--jd-green);font-size:15px;cursor:pointer;" onclick="this.closest('.jd-alert').remove()">&times;</button>
</div>
@endif
@if(session('error'))
<div class="jd-alert jd-alert--err">
    <i class="fas fa-exclamation-triangle"></i>
    <div><b>Gagal</b> &middot; <span>{{ session('error') }}</span></div>
    <button type="button" class="jd-toast-close" style="margin-left:auto;background:none;border:none;color:var(--jd-red);font-size:15px;cursor:pointer;" onclick="this.closest('.jd-alert').remove()">&times;</button>
</div>
@endif

{{-- ===== TOOLBAR ===== --}}
<div class="jd-toolbar jd-toolbar-pengampu">
    <form id="pengampuFilter" method="GET" autocomplete="off" class="jd-filter-group">
        <div class="jd-filter">
            <label><i class="fas fa-calendar-alt"></i> Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="jd-select" onchange="this.form.submit()">
                @foreach($tahunAjarans as $ta)
                <option value="{{ $ta->id }}" {{ (request('tahun_ajaran_id') ?: ($tahunAktif->id ?? ''))==$ta->id ? 'selected' : '' }}>
                    {{ $ta->tahun_ajaran }}{{ $ta->status == 'Aktif' ? ' (Aktif)' : '' }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="jd-filter">
            <label><i class="fas fa-layer-group"></i> Jenjang</label>
            <select name="jenjang_id" class="jd-select" onchange="this.form.submit()">
                <option value="">Semua Jenjang</option>
                @foreach($jenjangs as $j)
                <option value="{{ $j->id }}" {{ request('jenjang_id')==$j->id ? 'selected' : '' }}>{{ $j->nama_jenjang }}</option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="jd-search">
        <i class="fas fa-search"></i>
        <input type="search" id="customSearch" class="jd-control" placeholder="Cari guru, mapel, kelas..." autocomplete="off">
    </div>
    <div class="jd-seg" role="group" aria-label="Mode tampilan pengampu mapel">
        <button type="button" class="is-active" data-view="grid"><i class="fas fa-th-large"></i> Grid</button>
        <button type="button" data-view="list"><i class="fas fa-list"></i> List</button>
    </div>
    <div class="jd-filter" style="min-width:96px;">
        <label><i class="fas fa-list-ul"></i> Tampilkan</label>
        <select id="customLength" class="jd-select">
            @foreach([10, 15, 25, 50, 100] as $opt)
            <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- ===== GRID ===== --}}
<div class="jd-grid-list" id="pengampuGrid">
    @foreach($pengampus as $item)
    @php
        $pengampuSearch = strtolower(
            ($item->guru?->nama ?? '') . ' ' . ($item->guru?->kode_guru ?? '') . ' ' .
            ($item->mapel?->nama_mapel ?? '') . ' ' . ($item->mapel?->jenjang->nama_jenjang ?? '') . ' ' .
            ($item->kelas?->nama_kelas ?? '') . ' ' . ($item->tahunAjaran?->tahun_ajaran ?? '')
        );
    @endphp
    <div class="jd-grid-card" data-search="{{ $pengampuSearch }}">
        <div class="jd-grid-head">
            <div class="jd-guru">
                <div class="jd-guru-avatar jd-ava-{{ $loop->index % 4 }}">{{ mb_strtoupper(mb_substr((string) ($item->guru?->nama ?? 'G'), 0, 1)) }}</div>
                <div class="jd-min-w">
                    <b>{{ $item->guru?->nama ?? '-' }}</b>
                    <span>{{ $item->guru?->kode_guru ?? '' }}</span>
                </div>
            </div>
            @if($item->is_locked)
            <span class="jd-chip jd-chip--green"><i class="fas fa-check-circle"></i> Aktif</span>
            @else
            <span class="jd-chip jd-chip--red"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
            @endif
        </div>

        <div class="jd-grid-subject jd-mc-{{ jd_mapel_color_idx($item->mapel?->nama_mapel ?? '') }}">
            <b><i class="fas fa-book-open me-2"></i>{{ $item->mapel?->nama_mapel ?? '-' }}</b>
            <i class="fas fa-circle" style="font-size:7px;color:var(--mc, var(--jd-primary));"></i>
        </div>

        <div class="jd-grid-meta">
            <div class="jd-meta-item">
                <i class="fas fa-layer-group"></i>
                <div class="jd-min-w">
                    <div class="lbl">Jenjang</div>
                    <div class="val">{{ $item->mapel?->jenjang->nama_jenjang ?? '-' }}</div>
                </div>
            </div>
            <div class="jd-meta-item">
                <i class="fas fa-school"></i>
                <div class="jd-min-w">
                    <div class="lbl">Kelas</div>
                    <div class="val">{{ $item->kelas?->nama_kelas ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="jd-grid-foot">
            <span class="jd-chip jd-chip--blue"><i class="fas fa-calendar-alt"></i> {{ $item->tahunAjaran?->tahun_ajaran ?? '-' }}</span>
            <div class="jd-actions">
                @if($item->is_locked)
                <button type="button" class="jd-btn jd-btn--xs jd-btn--ghost jd-btn-lock" disabled title="Sudah digunakan di jadwal pelajaran"><i class="fas fa-lock"></i></button>
                <button type="button" class="jd-btn jd-btn--xs jd-btn--ghost jd-btn-lock" disabled title="Sudah digunakan di jadwal pelajaran"><i class="fas fa-lock"></i></button>
                @else
                <button type="button" class="jd-btn jd-btn--xs jd-btn--amber-soft" data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}" title="Edit pengampu"><i class="fas fa-pen"></i></button>
                <button type="button" class="jd-btn jd-btn--xs jd-btn--danger-soft" data-bs-toggle="modal" data-bs-target="#hapus{{ $item->id }}" title="Hapus pengampu"><i class="fas fa-trash"></i></button>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
            <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('pengampu-mapel.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content jd-modal-card">
                            <div class="jd-modal-head" style="--mc:#d97706;">
                                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-pen"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Edit Pengampu</h5>
                                        <div style="font-size:12px;opacity:.85;color:#fff;">Perbarui guru pengampu mata pelajaran</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><i class="fas fa-user me-1" style="color:var(--jd-primary);"></i> Guru</label>
                                    <select name="guru_id" class="jd-select" required>
                                        <option value="">&mdash; Pilih Guru &mdash;</option>
                                        @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ $item->guru_id == $guru->id ? 'selected' : '' }}>{{ $guru->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold"><i class="fas fa-layer-group me-1" style="color:var(--jd-primary);"></i> Jenjang</label>
                                        <select name="jenjang_id" class="jd-select editJenjang" required>
                                            <option value="">&mdash; Pilih Jenjang &mdash;</option>
                                            @foreach($jenjangs as $j)
                                            <option value="{{ $j->id }}" {{ ($item->mapel?->jenjang_id ?? '') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenjang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold"><i class="fas fa-school me-1" style="color:var(--jd-primary);"></i> Kelas</label>
                                        <select name="kelas_id" class="jd-select editKelas" required>
                                            <option value="">&mdash; Pilih Kelas &mdash;</option>
                                            @foreach($kelas as $kelasItem)
                                            <option value="{{ $kelasItem->id }}" data-jenjang="{{ $kelasItem->jenjang_id ?? '' }}" {{ $item->kelas_id == $kelasItem->id ? 'selected' : '' }}>{{ $kelasItem->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><i class="fas fa-book-open me-1" style="color:var(--jd-primary);"></i> Mata Pelajaran</label>
                                    <select name="mata_pelajaran_id" class="jd-select editMapel" required>
                                        <option value="">&mdash; Pilih Mata Pelajaran &mdash;</option>
                                        @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" data-jenjang="{{ $mapel->jenjang_id ?? '' }}" {{ $item->mata_pelajaran_id == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-semibold"><i class="fas fa-calendar-alt me-1" style="color:var(--jd-primary);"></i> Tahun Ajaran</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="jd-control" value="{{ $item->tahunAjaran?->tahun_ajaran ?? '-' }}" readonly>
                                        <input type="hidden" name="tahun_ajaran_id" value="{{ $item->tahun_ajaran_id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                <button type="button" class="jd-btn" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="jd-btn jd-btn--solid" style="background:linear-gradient(135deg,#d97706,#f59e0b);"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ===== MODAL HAPUS ===== --}}
            <div class="modal fade" id="hapus{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content jd-modal-card">
                        <div class="jd-modal-head" style="--mc:#dc2626;">
                            <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            <div class="d-flex align-items-center gap-3">
                                <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-trash"></i></div>
                                <div>
                                    <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Hapus Pengampu</h5>
                                    <div style="font-size:12px;opacity:.85;color:#fff;">Tindakan ini tidak dapat dibatalkan</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body p-4 text-center">
                            <div class="jd-info-grid mb-4" style="grid-template-columns:1fr;">
                                <div class="jd-info-cell"><div class="lbl"><i class="fas fa-user"></i> Guru</div><div class="val">{{ $item->guru?->nama ?? '-' }}</div></div>
                                <div class="jd-info-cell"><div class="lbl"><i class="fas fa-book-open"></i> Mata Pelajaran</div><div class="val">{{ $item->mapel?->nama_mapel ?? '-' }}</div></div>
                                <div class="jd-info-cell"><div class="lbl"><i class="fas fa-school"></i> Kelas</div><div class="val">{{ $item->kelas?->nama_kelas ?? '-' }}</div></div>
                            </div>
                            <form action="{{ route('pengampu-mapel.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="jd-btn" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="jd-btn jd-btn--danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    @endforeach
</div>

{{-- ===== TABLE ===== --}}
<div class="jd-card jd-table-card jd-listcard" id="pengampuList">
    <div class="jd-card-header">
        <div>
            <h5 class="jd-section-title"><i class="fas fa-chalkboard-teacher"></i> Daftar Guru Pengampu</h5>
            <p class="jd-section-sub mb-0">Data pengampu mata pelajaran per kelas pada tahun ajaran terpilih</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="jd-chip jd-chip--blue"><i class="fas fa-calendar-alt"></i> {{ $tahunAktif->tahun_ajaran ?? '-' }}</span>
        </div>
    </div>

    <table id="pengampuTable" class="table table-jd display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Guru</th>
                <th>Mata Pelajaran</th>
                <th>Jenjang</th>
                <th>Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengampus as $item)
            <tr>
                <td class="jd-num">{{ $loop->iteration }}</td>
                <td>
                    <div class="jd-guru">
                        <div class="jd-guru-avatar jd-ava-{{ $loop->index % 4 }}">{{ mb_strtoupper(mb_substr((string) ($item->guru?->nama ?? 'G'), 0, 1)) }}</div>
                        <div class="jd-min-w">
                            <b>{{ $item->guru?->nama ?? '-' }}</b>
                            <span>{{ $item->guru?->kode_guru ?? '' }}</span>
                        </div>
                    </div>
                </td>
                <td><span class="jd-mapel-chip jd-mc-{{ jd_mapel_color_idx($item->mapel?->nama_mapel ?? '') }}"><i class="fas fa-circle"></i> {{ $item->mapel?->nama_mapel ?? '-' }}</span></td>
                <td><span class="jd-cell-icon"><i class="fas fa-layer-group"></i> {{ $item->mapel?->jenjang->nama_jenjang ?? '-' }}</span></td>
                <td><span class="jd-cell-icon"><i class="fas fa-school"></i> {{ $item->kelas?->nama_kelas ?? '-' }}</span></td>
                <td><span class="jd-chip jd-chip--blue"><i class="fas fa-calendar-alt"></i> {{ $item->tahunAjaran?->tahun_ajaran ?? '-' }}</span></td>
                <td>
                    @if($item->is_locked)
                    <span class="jd-chip jd-chip--green"><i class="fas fa-check-circle"></i> Aktif</span>
                    @else
                    <span class="jd-chip jd-chip--red"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    @if($item->is_locked)
                    <div class="jd-actions">
                        <button type="button" class="jd-btn jd-btn--xs jd-btn--ghost jd-btn-lock" disabled title="Sudah digunakan di jadwal pelajaran"><i class="fas fa-lock"></i></button>
                        <button type="button" class="jd-btn jd-btn--xs jd-btn--ghost jd-btn-lock" disabled title="Sudah digunakan di jadwal pelajaran"><i class="fas fa-lock"></i></button>
                    </div>
                    @else
                    <div class="jd-actions">
                        <button type="button" class="jd-btn jd-btn--xs jd-btn--amber-soft" data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}" title="Edit pengampu"><i class="fas fa-pen"></i></button>
                        <button type="button" class="jd-btn jd-btn--xs jd-btn--danger-soft" data-bs-toggle="modal" data-bs-target="#hapus{{ $item->id }}" title="Hapus pengampu"><i class="fas fa-trash"></i></button>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ===== FAB (mobile) ===== --}}
<button type="button" class="jd-fab" data-bs-toggle="modal" data-bs-target="#modalTambahPengampu" aria-label="Tambah pengampu"><i class="fas fa-plus"></i></button>

{{-- ===== MODAL TAMBAH ===== --}}
<div class="modal fade" id="modalTambahPengampu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('pengampu-mapel.store') }}" method="POST">
            @csrf
            <div class="modal-content jd-modal-card">
                <div class="jd-modal-head" style="--mc:#16a34a;">
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    <div class="d-flex align-items-center gap-3">
                        <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-user-plus"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Tambah Pengampu Mapel</h5>
                            <div style="font-size:12px;opacity:.85;color:#fff;">Isi langkah demi langkah untuk menetapkan pengampu</div>
                        </div>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="jd-stepper mb-4" id="wizSteps">
                        <div class="jd-step active" data-wstep="1"><div class="jd-step-dot">1</div><div class="jd-step-txt"><b>Guru</b><span>Pilih guru</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="2"><div class="jd-step-dot">2</div><div class="jd-step-txt"><b>Jenjang</b><span>Pilih jenjang</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="3"><div class="jd-step-dot">3</div><div class="jd-step-txt"><b>Kelas &amp; Mapel</b><span>Kombinasi kelas-mapel</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="4"><div class="jd-step-dot">4</div><div class="jd-step-txt"><b>Tahun Ajaran</b><span>{{ $tahunAktif->tahun_ajaran ?? '-' }}</span></div></div>
                    </div>

                    {{-- Pane 1: Guru --}}
                    <div class="jd-wizard-pane is-show" data-pane="1">
                        <label class="jd-filter" style="min-width:0;">
                            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-user-graduate me-1" style="color:var(--jd-primary);"></i> Guru Pengampu</span>
                            <select name="guru_id" class="jd-select" required>
                                <option value="">&mdash; Pilih Guru &mdash;</option>
                                @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </label>
                        <div class="jd-wizard-hint"><i class="fas fa-info-circle"></i> Pilih guru terlebih dahulu untuk melanjutkan.</div>
                    </div>

                    {{-- Pane 2: Jenjang --}}
                    <div class="jd-wizard-pane" data-pane="2">
                        <label class="jd-filter" style="min-width:0;">
                            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-layer-group me-1" style="color:var(--jd-primary);"></i> Jenjang</span>
                            <select name="jenjang_id" id="tambahJenjang" class="jd-select" required>
                                <option value="">&mdash; Pilih Jenjang &mdash;</option>
                                @foreach($jenjangs as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jenjang }}</option>
                                @endforeach
                            </select>
                        </label>
                        <div class="jd-wizard-hint"><i class="fas fa-filter"></i> Kelas &amp; mata pelajaran akan disesuaikan dengan jenjang terpilih.</div>
                    </div>

                    {{-- Pane 3: Kelas + Mapel --}}
                    <div class="jd-wizard-pane" data-pane="3">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold"><i class="fas fa-school me-1" style="color:var(--jd-primary);"></i> Kelas</label>
                                <select name="kelas_id" id="tambahKelas" class="jd-select" required>
                                    <option value="">&mdash; Pilih Kelas &mdash;</option>
                                    @foreach($kelas as $kelasItem)
                                    <option value="{{ $kelasItem->id }}" data-jenjang="{{ $kelasItem->jenjang_id ?? '' }}">{{ $kelasItem->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold"><i class="fas fa-book-open me-1" style="color:var(--jd-primary);"></i> Mata Pelajaran</label>
                                <select name="mata_pelajaran_id" id="tambahMapel" class="jd-select" required>
                                    <option value="">&mdash; Pilih Mata Pelajaran &mdash;</option>
                                    @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}" data-jenjang="{{ $mapel->jenjang_id ?? '' }}">{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="jd-wizard-hint"><i class="fas fa-magic"></i> Mapel/kelas yang sudah diampu guru di kelas &amp; jenjang ini otomatis disembunyikan.</div>
                    </div>

                    {{-- Pane 4: Tahun Ajaran --}}
                    <div class="jd-wizard-pane" data-pane="4">
                        <label class="jd-filter" style="min-width:0;">
                            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-calendar-alt me-1" style="color:var(--jd-primary);"></i> Tahun Ajaran</span>
                            <input type="text" class="jd-control" value="{{ $tahunAktif->tahun_ajaran ?? '-' }}" readonly>
                            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">
                        </label>
                        <div class="jd-wizard-hint"><i class="fas fa-check-circle"></i> Pengampu selalu tercatat pada tahun ajaran aktif.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="jd-btn jd-btn--ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="jd-btn jd-btn--solid" style="background:linear-gradient(135deg,#16a34a,#22c55e);" id="tambahSubmit" disabled><i class="fas fa-save"></i> Simpan Pengampu</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL SALIN ===== --}}
<div class="modal fade" id="modalSalinPengampu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content jd-modal-card">
            <div class="jd-modal-head" style="--mc:#2563eb;">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <div class="d-flex align-items-center gap-3">
                    <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-copy"></i></div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Salin Pengampu Mapel</h5>
                        <div style="font-size:12px;opacity:.85;color:#fff;">Migrasi data pengampu antar tahun ajaran</div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="jd-mig">
                    <div class="jd-mig-step is-active" data-mig="1">
                        <div class="jd-mig-step-icon"><i class="fas fa-database"></i></div>
                        <div class="jd-mig-step-txt"><b>Membaca data lama</b><span id="migFrom">Menyiapkan data tahun ajaran sebelumnya</span></div>
                    </div>
                    <div class="jd-mig-step" data-mig="2">
                        <div class="jd-mig-step-icon"><i class="fas fa-copy"></i></div>
                        <div class="jd-mig-step-txt"><b>Menyalin pengampu</b><span>Menyalin ke tahun ajaran aktif &middot; duplikat dilewati</span></div>
                    </div>
                    <div class="jd-mig-step" data-mig="3">
                        <div class="jd-mig-step-icon"><i class="fas fa-flag-checkered"></i></div>
                        <div class="jd-mig-step-txt"><b>Selesai</b><span>Data pengampu siap digunakan</span></div>
                    </div>
                </div>

                <div class="jd-mig-bar mt-2 mb-1"><div class="jd-mig-bar-fill" id="migBar"></div></div>

                <div class="d-flex align-items-center gap-3 mt-3" style="border:1px solid var(--jd-border);border-radius:12px;padding:12px 14px;background:var(--jd-bg);">
                    <i class="fas fa-arrow-right-arrow-left" style="color:var(--jd-primary);font-size:15px;"></i>
                    <div style="flex:1;">
                        <div style="font-size:10px;font-weight:700;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Dari</div>
                        <b id="salinFromTA" style="font-size:13px;">-</b>
                    </div>
                    <i class="fas fa-arrow-right" style="color:var(--jd-text-3);"></i>
                    <div style="flex:1;text-align:right;">
                        <div style="font-size:10px;font-weight:700;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Ke (Aktif)</div>
                        <b id="salinToTA" style="font-size:13px;">-</b>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="jd-btn" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="jd-btn jd-btn--solid" id="btnSalinGo"><i class="fas fa-copy"></i> Ya, Salin Sekarang</button>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('pengampu-mapel.salin') }}" method="POST" id="formSalin">@csrf</form>

</div>

@endsection

@push('scripts')
<script>
$(function() {
    var tahunAjarans = @json($jdTahunJson);
    var tahunAktif = @json($jdTahunAktifJson);

    {{-- ===== SALIN ===== --}}
    $('#modalSalinPengampu').on('show.bs.modal', function () {
        if (tahunAktif) $('#salinToTA').text(tahunAktif.tahun_ajaran + ' (Aktif)');
        var prev = tahunAjarans.filter(function (ta) { return ta.id != (tahunAktif ? tahunAktif.id : 0); })
            .sort(function (a, b) { return String(b.tahun_ajaran).localeCompare(String(a.tahun_ajaran)); })[0];
        $('#salinFromTA').text(prev ? prev.tahun_ajaran : 'Tidak ada data');
        $('#migFrom').text(prev ? 'Data tahun ' + prev.tahun_ajaran : 'Tidak ada data untuk disalin');
        $('#migBar').css('width', '0%');
        $('.jd-mig-step').removeClass('is-active is-done').first().addClass('is-active');
        $('#btnSalinGo').prop('disabled', false).html('<i class="fas fa-copy"></i> Ya, Salin Sekarang');
    });

    $('#btnSalinGo').on('click', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyalin...');
        $('.jd-mig-step[data-mig="1"]').addClass('is-done');
        $('.jd-mig-step[data-mig="2"]').addClass('is-active');
        $('#migBar').css('width', '45%');
        setTimeout(function () {
            $('.jd-mig-step[data-mig="2"]').addClass('is-done').removeClass('is-active');
            $('.jd-mig-step[data-mig="3"]').addClass('is-active');
            $('#migBar').css('width', '100%');
            setTimeout(function () { $('#formSalin').submit(); }, 450);
        }, 800);
    });

    {{-- ===== DATATABLE ===== --}}
    var table = $('#pengampuTable').DataTable({
        pagingType: 'simple_numbers',
        responsive: false,
        scrollX: true,
        processing: true,
        pageLength: 10,
        lengthMenu: [
            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100]
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _TOTAL_ data)",
            paginate: {
                first: '«',
                previous: '‹',
                next: '›',
                last: '»'
            },
            aria: {
                paginate: {
                    first: 'First',
                    previous: 'Previous',
                    next: 'Next',
                    last: 'Last'
                }
            }
        },
        columnDefs: [{
            orderable: false,
            targets: 7
        }]
    });

    $('#customLength').on('change', function () {
        table.page.len($(this).val()).draw();
    });

    var grid = document.getElementById('pengampuGrid');
    var list = document.getElementById('pengampuList');
    var currentView = 'grid';

    function applySearch(q) {
        q = (q || '').trim().toLowerCase();
        if (currentView === 'list') {
            table.search(q).draw();
            return;
        }
        var total = 0;
        grid.querySelectorAll('.jd-grid-card').forEach(function (card) {
            var match = !q || (card.getAttribute('data-search') || '').indexOf(q) !== -1;
            card.style.display = match ? '' : 'none';
            if (match) total++;
        });
    }

    $('#customSearch').on('input', function () {
        applySearch(this.value);
    });

    Array.from(document.querySelectorAll('.jd-seg button')).forEach(function (btn) {
        btn.addEventListener('click', function () {
            Array.from(document.querySelectorAll('.jd-seg button')).forEach(function (node) { node.classList.remove('is-active'); });
            btn.classList.add('is-active');
            currentView = btn.dataset.view || 'grid';
            if (grid) grid.style.display = currentView === 'grid' ? 'grid' : 'none';
            if (list) list.style.display = currentView === 'list' ? 'block' : 'none';
            if (currentView === 'list') table.columns.adjust().draw(false);
            applySearch($('#customSearch').val());
        });
    });

    {{-- ===== CASCADE JENJANG ===== --}}
    function filterByJenjang(jenjangVal, kelasSel, mapelSel) {
        var $kelas = $(kelasSel);
        var $mapel = $(mapelSel);
        var prevKelas = $kelas.val();
        var prevMapel = $mapel.val();

        $kelas.find('option[data-jenjang]').each(function () {
            var $opt = $(this);
            if (!jenjangVal || $opt.data('jenjang') == jenjangVal) {
                $opt.show();
            } else {
                $opt.hide();
                if ($opt.val() === prevKelas) prevKelas = '';
            }
        });
        $kelas.val(prevKelas || '');

        $mapel.find('option[data-jenjang]').each(function () {
            var $opt = $(this);
            if (!jenjangVal || $opt.data('jenjang') == jenjangVal) {
                $opt.show();
            } else {
                $opt.hide();
                if ($opt.val() === prevMapel) prevMapel = '';
            }
        });
        $mapel.val(prevMapel || '');
    }

    $(document).on('change', '.editJenjang', function () {
        var $modal = $(this).closest('.modal');
        filterByJenjang($(this).val(), $modal.find('.editKelas'), $modal.find('.editMapel'));
    });

    {{-- ===== TAMBAH (duplicate prevention) ===== --}}
    var existingPengampus = @json($jdExistingJson);

    function resetTambahOptions() {
        $('#tambahKelas option[data-jenjang]').show();
        $('#tambahMapel option[data-jenjang]').show();
    }

    function applyTambahFilters() {
        var guruId = $('#modalTambahPengampu select[name="guru_id"]').val();
        var kelasId = $('#tambahKelas').val();
        var mapelId = $('#tambahMapel').val();

        resetTambahOptions();
        filterByJenjang($('#tambahJenjang').val(), '#tambahKelas', '#tambahMapel');

        if (guruId && kelasId) {
            var usedMapelIds = existingPengampus
                .filter(function (p) { return p.guru_id == guruId && p.kelas_id == kelasId; })
                .map(function (p) { return String(p.mata_pelajaran_id); });

            $('#tambahMapel option[data-jenjang]').each(function () {
                var $opt = $(this);
                if ($opt.val() === '') return;
                if (usedMapelIds.indexOf($opt.val()) !== -1) {
                    $opt.hide();
                }
            });

            if (usedMapelIds.indexOf(mapelId) !== -1) {
                $('#tambahMapel').val('');
            }
        }

        if (guruId && $('#tambahMapel').val()) {
            var activeMapelId = $('#tambahMapel').val();
            var usedKelasIds = existingPengampus
                .filter(function (p) { return p.guru_id == guruId && p.mata_pelajaran_id == activeMapelId; })
                .map(function (p) { return String(p.kelas_id); });

            $('#tambahKelas option[data-jenjang]').each(function () {
                var $opt = $(this);
                if ($opt.val() === '') return;
                if (usedKelasIds.indexOf($opt.val()) !== -1) {
                    $opt.hide();
                }
            });

            if (usedKelasIds.indexOf(kelasId) !== -1) {
                $('#tambahKelas').val('');
            }
        }
    }

    function updateWizard() {
        var hasGuru = !!$('#modalTambahPengampu select[name="guru_id"]').val();
        var hasJenjang = !!$('#tambahJenjang').val();
        var hasKelasMapel = !!$('#tambahKelas').val() && !!$('#tambahMapel').val();
        var cur = hasGuru ? (hasJenjang ? (hasKelasMapel ? 4 : 3) : 2) : 1;

        $('[data-wstep]').each(function () {
            var n = parseInt($(this).attr('data-wstep'), 10);
            $(this).toggleClass('done', n < cur);
            $(this).toggleClass('active', n === cur);
        });

        $('#tambahSubmit').prop('disabled', !hasJenjang);
    }

    $('#modalTambahPengampu').on('show.bs.modal', function () {
        $('.jd-wizard-pane').removeClass('is-show');
        $('.jd-wizard-pane[data-pane="1"]').addClass('is-show');
        $('#modalTambahPengampu select[name="guru_id"]').val('');
        $('#tambahJenjang').val('');
        $('#tambahKelas').val('');
        $('#tambahMapel').val('');
        updateWizard();
    });

    $('#modalTambahPengampu').on('change', 'select[name="guru_id"]', function () {
        if ($(this).val()) {
            $('.jd-wizard-pane[data-pane="2"]').addClass('is-show');
        } else {
            $('.jd-wizard-pane').not('[data-pane="1"]').removeClass('is-show');
        }
        updateWizard();
    });

    $('#tambahJenjang').on('change', function () {
        resetTambahOptions();
        filterByJenjang($(this).val(), '#tambahKelas', '#tambahMapel');
        if ($(this).val()) {
            $('.jd-wizard-pane[data-pane="3"], .jd-wizard-pane[data-pane="4"]').addClass('is-show');
        } else {
            $('.jd-wizard-pane').not('[data-pane="1"], [data-pane="2"]').removeClass('is-show');
        }
        updateWizard();
    });

    $('#modalTambahPengampu').on('change', '#tambahKelas', function () {
        applyTambahFilters();
        updateWizard();
    });

    $('#modalTambahPengampu').on('change', '#tambahMapel', function () {
        applyTambahFilters();
        updateWizard();
    });
});
</script>
@endpush
