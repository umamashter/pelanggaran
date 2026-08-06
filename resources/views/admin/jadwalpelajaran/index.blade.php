@extends('layouts.main')
@section('title', 'Jadwal Pelajaran')
@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-tabs > .jd-tab { z-index: 1; }
    .jd-tabs { max-width: 100%; }
    .jd-tabs-kelas { margin-top: 2px; }
    .jd-cell-masked { opacity: .3; }

    .jd-alert { display: flex; align-items: center; gap: 12px; border-radius: 14px; padding: 13px 16px; font-size: 13px; font-weight: 600;
        margin-bottom: 18px; border: 1px solid var(--jd-border); background: var(--jd-card); box-shadow: var(--jd-shadow); }
    .jd-alert i { font-size: 16px; flex-shrink: 0; }
    .jd-alert b { font-weight: 700; }
    .jd-alert span { font-weight: 500; opacity: .85; }
    .jd-alert--warn { border-color: var(--jd-amber-border); background: var(--jd-amber-soft); color: var(--jd-amber); }
    .jd-alert--err { border-color: var(--jd-red-border); background: var(--jd-red-soft); color: var(--jd-red); }
    .jd-alert--ok { border-color: var(--jd-green-border); background: var(--jd-green-soft); color: var(--jd-green); }
    .jd-alert--info { border-color: var(--jd-primary-border); background: var(--jd-primary-soft); color: var(--jd-primary); }

    .jd-chip-select { display: inline-flex; align-items: center; gap: 8px; border-radius: 11px; padding: 10px 16px; font-size: 12.5px; font-weight: 600;
        color: var(--jd-text-2); border: 1.5px solid var(--jd-border); background: var(--jd-card); cursor: pointer; transition: all .2s ease; font-family: inherit; }
    .jd-chip-select:hover { border-color: var(--jd-primary-border); color: var(--jd-text); transform: translateY(-1px); }
    .jd-chip-select.active { background: var(--jd-primary-soft); border-color: var(--jd-primary); color: var(--jd-primary); box-shadow: 0 4px 14px -6px rgba(37,99,235,.4); }
    .jd-chip-select i { font-size: 12px; }

    .jd-modal-card { border: none !important; border-radius: 20px !important; overflow: hidden; background: var(--jd-card); box-shadow: 0 25px 60px rgba(15,23,42,.18); }
    .jd-modal-head { position: relative; padding: 24px 24px 20px; color: #fff; background: var(--mc, #2563eb); }
    .jd-modal-head::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(255,255,255,.14), rgba(0,0,0,.16)); pointer-events: none; }
    .jd-modal-head > * { position: relative; z-index: 1; }
    .jd-modal-head .btn-close { z-index: 2 !important; }

    .jd-wizard-pane { min-height: 190px; }
    .jd-wizard-hint { font-size: 12px; color: var(--jd-text-3); margin-top: 6px; display: flex; align-items: center; gap: 6px; }
    .jd-wizard-hint i { color: var(--jd-primary); }

    @media (max-width: 767.98px) {
        .jd-step-txt { display: none; }
        .jd-step { justify-content: center; }
        .jd-stepper { gap: 4px; }
        .jd-step-line { margin: 0 6px; min-width: 12px; }
    }

    .jd-fab { display: none; }
    @media (max-width: 991.98px) {
        .jd-fab { display: inline-flex; }
    }
</style>

<div class="jd-mod jd-page-jadwal">

@php
    $jamSlot = [
        1 => ['label' => 'Jam 1', 'mulai' => '07:30', 'selesai' => '08:30'],
        2 => ['label' => 'Jam 2', 'mulai' => '08:30', 'selesai' => '09:30'],
        3 => ['label' => 'Jam 3', 'mulai' => '10:00', 'selesai' => '11:00'],
        4 => ['label' => 'Jam 4', 'mulai' => '11:00', 'selesai' => '12:00'],
    ];

    $totalJadwal = $jadwals->count();
    $kelasTerisi = $jadwals->pluck('kelas_id')->unique()->count();
    $totalKelasAll = 0;
    foreach ($kelasPerJenjang as $list) {
        $totalKelasAll += $list->count();
    }
    $guruAktif = $jadwals->pluck('guru_id')->unique()->count();
    $mapelTerjadwal = $jadwals->pluck('mata_pelajaran_id')->unique()->count();

    $guruSlot = [];
    $kelasSlot = [];
    foreach ($jadwals as $jw) {
        $gk = $jw->guru_id . '|' . $jw->hari . '|' . $jw->jam_ke;
        $kk = $jw->kelas_id . '|' . $jw->hari . '|' . $jw->jam_ke;
        $guruSlot[$gk] = ($guruSlot[$gk] ?? 0) + 1;
        $kelasSlot[$kk] = ($kelasSlot[$kk] ?? 0) + 1;
    }
    $jumlahKonflik = 0;
    $conflictIds = [];
    foreach ($jadwals as $jw) {
        $gk = $jw->guru_id . '|' . $jw->hari . '|' . $jw->jam_ke;
        $kk = $jw->kelas_id . '|' . $jw->hari . '|' . $jw->jam_ke;
        if (($guruSlot[$gk] ?? 0) > 1 || ($kelasSlot[$kk] ?? 0) > 1) {
            $conflictIds[$jw->id] = true;
            $jumlahKonflik++;
        }
    }

    $jdPayload = [];
    foreach ($jadwals as $jw) {
        $kid = $jw->kelas_id;
        $jid = $jw->jenjang_id;
        $namaMapel = $jw->mapel->nama_mapel ?? '-';
        $jdPayload[$jid][$kid][] = [
            'id' => $jw->id,
            'kelas_id' => $kid,
            'guru_id' => $jw->guru_id,
            'kelas' => $jw->kelas->nama_kelas ?? '-',
            'mapel' => $namaMapel,
            'guru' => $jw->guru->nama ?? '-',
            'hari' => $jw->hari,
            'jam_ke' => (int) $jw->jam_ke,
            'jam_mulai' => substr((string) $jw->jam_mulai, 0, 5),
            'jam_selesai' => substr((string) $jw->jam_selesai, 0, 5),
            'mc' => jd_mapel_color_idx($namaMapel),
            'conflict' => isset($conflictIds[$jw->id]),
        ];
    }

    $kelasPayload = [];
    foreach ($kelasPerJenjang as $jid => $list) {
        $kelasPayload[$jid] = $list->map(function ($k) use ($jdPayload, $jid) {
            return [
                'id' => $k->id,
                'nama' => $k->nama_kelas,
                'count' => count($jdPayload[$jid][$k->id] ?? []),
            ];
        })->values();
    }

    $semuaKelas = [];
    foreach ($kelasPerJenjang as $list) {
        foreach ($list as $k) {
            $semuaKelas[] = $k;
        }
    }
    usort($semuaKelas, function ($a, $b) {
        return strcmp($a->nama_kelas, $b->nama_kelas);
    });

    $filterAktif = request('kelas_id') || request('guru_id') || request('hari') || request('tahun_ajaran_id');

    $jdHariJson = $hariList;
    $jdJamJson = $jamSlot;
    $jdJenjangJson = $jenjangs->map(function ($j) {
        return ['id' => $j->id, 'nama' => $j->nama_jenjang];
    })->values();
    $jdKelasJson = $kelasPayload;
    $jdDataJson = $jdPayload;
    $jdKelasWizardJson = array_map(function ($k) {
        return ['id' => $k->id, 'nama' => $k->nama_kelas];
    }, $semuaKelas);
    $jdPengampuJson = $pengampuMapels->map(function ($p) {
        return [
            'guru_id' => $p->guru_id,
            'mata_pelajaran_id' => $p->mata_pelajaran_id,
            'kelas_id' => $p->kelas_id,
        ];
    })->values();
    $jdMapelJson = $mapels->map(function ($m) {
        return ['id' => $m->id, 'nama' => $m->nama_mapel];
    })->values();
    $jdGuruJson = $gurus->map(function ($g) {
        return ['id' => $g->id, 'nama' => $g->nama];
    })->values();
    $jdTahunJson = $tahunAjarans->map(function ($ta) {
        return ['id' => $ta->id, 'tahun_ajaran' => $ta->tahun_ajaran];
    })->values();
    $jdTahunAktifJson = $tahunAjaranAktif
        ? ['id' => $tahunAjaranAktif->id, 'tahun_ajaran' => $tahunAjaranAktif->tahun_ajaran]
        : null;
@endphp

{{-- ===== HERO ===== --}}
<div class="jd-hero">
    <div class="jd-hero-grid">
        <div class="jd-hero-left">
            <div class="jd-hero-icon"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <h1 class="jd-hero-title">Jadwal Pelajaran</h1>
                <p class="jd-hero-sub">Kelola jadwal mengajar per jenjang, kelas, dan hari — dengan deteksi bentrok otomatis dan migrasi antar tahun ajaran.</p>
                <div class="jd-hero-badges">
                    <span class="jd-hero-badge"><i class="fas fa-calendar-day"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="jd-hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAjaranAktif->tahun_ajaran }}</span>
                    @if($tahunAjaranAktif->semesterAktif)
                    <span class="jd-hero-badge"><i class="fas fa-bookmark"></i> {{ $tahunAjaranAktif->semesterAktif->nama ?? '-' }}</span>
                    @endif
                    <span class="jd-hero-badge jd-hero-badge--ok"><i class="fas fa-check-circle"></i> {{ $totalJadwal }} Jadwal</span>
                    @if($jumlahKonflik > 0)
                    <span class="jd-hero-badge jd-hero-badge--warn"><i class="fas fa-exclamation-triangle"></i> {{ $jumlahKonflik }} Konflik</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="jd-hero-right">
            @if($sudahDisalin)
            <span class="jd-btn jd-btn--light" style="opacity:.9;pointer-events:none;"><i class="fas fa-check"></i> Data sudah ada</span>
            @else
            <button type="button" class="jd-btn jd-btn--light" data-bs-toggle="modal" data-bs-target="#modalSalinJadwal"><i class="fas fa-copy"></i> Salin</button>
            @endif
            <button type="button" class="jd-btn jd-btn--light" data-bs-toggle="modal" data-bs-target="#modalExport"><i class="fas fa-file-export"></i> Export &amp; Cetak</button>
            <button type="button" class="jd-btn jd-btn--light" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fas fa-plus"></i> Tambah</button>
        </div>
    </div>
</div>

@if($jenjangs->isEmpty())

<div class="jd-card">
    <div class="jd-empty">
        <div class="jd-empty-illus">
            <div class="ring"></div>
            <div class="core"><i class="fas fa-school"></i></div>
        </div>
        <div class="jd-empty-title">Belum Ada Data Jenjang</div>
        <div class="jd-empty-sub">Tambahkan data jenjang terlebih dahulu sebelum membuat jadwal pelajaran.</div>
        <a href="{{ route('master-jenjang.index') }}" class="jd-btn jd-btn--solid"><i class="fas fa-plus"></i> Kelola Jenjang</a>
    </div>
</div>

@else

@if($jumlahKonflik > 0)
<div class="jd-alert jd-alert--err">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <b>{{ $jumlahKonflik }} jadwal terdeteksi bentrok</b>
        <span> — guru atau kelas memiliki lebih dari satu jadwal pada hari dan jam yang sama. Slot bermasalah ditandai merah di grid.</span>
    </div>
</div>
@endif

@if($filterAktif)
<div class="jd-alert jd-alert--info">
    <i class="fas fa-filter"></i>
    <div>
        <b>Filter aktif</b>
        <span>
            @if(request('kelas_id')) Kelas: <b>{{ $kelas->firstWhere('id', request('kelas_id'))->nama_kelas ?? request('kelas_id') }}</b> &middot; @endif
            @if(request('guru_id')) Guru: <b>{{ $gurus->firstWhere('id', request('guru_id'))->nama ?? request('guru_id') }}</b> &middot; @endif
            @if(request('hari')) Hari: <b>{{ request('hari') }}</b> &middot; @endif
            @if(request('tahun_ajaran_id')) TA: <b>{{ $tahunAjarans->firstWhere('id', request('tahun_ajaran_id'))->tahun_ajaran ?? request('tahun_ajaran_id') }}</b> @endif
        </span>
    </div>
    <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-btn jd-btn--ghost jd-btn--xs" style="margin-left:auto;"><i class="fas fa-rotate-left"></i> Reset</a>
</div>
@endif

{{-- ===== TOOLBAR (STICKY FILTER) ===== --}}
<form method="GET" action="{{ route('jadwal-pelajaran.index') }}" class="jd-toolbar" id="filterForm" autocomplete="off">
    <div class="jd-filter">
        <label><i class="fas fa-school me-1"></i> Kelas</label>
        <select name="kelas_id" class="jd-select" id="filterKelas">
            <option value="">Semua Kelas</option>
            @foreach($kelas as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} @if($k->jenjang)({{ $k->jenjang->nama_jenjang }})@endif</option>
            @endforeach
        </select>
    </div>
    <div class="jd-filter">
        <label><i class="fas fa-user-graduate me-1"></i> Guru</label>
        <select name="guru_id" class="jd-select" id="filterGuru">
            <option value="">Semua Guru</option>
            @foreach($gurus as $g)
            <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="jd-filter">
        <label><i class="fas fa-calendar-day me-1"></i> Hari</label>
        <select name="hari" class="jd-select" id="filterHari">
            <option value="">Semua Hari</option>
            @foreach($hariList as $day)
            <option value="{{ $day }}" {{ request('hari') == $day ? 'selected' : '' }}>{{ $day }}</option>
            @endforeach
        </select>
    </div>
    <div class="jd-filter">
        <label><i class="fas fa-calendar-alt me-1"></i> Tahun Ajaran</label>
        <select name="tahun_ajaran_id" class="jd-select" id="filterTA">
            <option value="">Tahun Aktif</option>
            @foreach($tahunAjarans as $ta)
            <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>{{ $ta->tahun_ajaran }}</option>
            @endforeach
        </select>
    </div>
    <div class="jd-search">
        <i class="fas fa-search"></i>
        <input type="text" class="jd-control" id="jdSearch" placeholder="Cari mata pelajaran atau guru..." aria-label="Cari jadwal">
    </div>
</form>

{{-- ===== TABS JENJANG ===== --}}
<div class="jd-tabs" id="jdJenjangTabs">
    <div class="jd-tab-pill" id="jdJenjangPill"></div>
</div>

{{-- ===== TABS KELAS ===== --}}
<div class="jd-tabs-kelas" id="jdKelasTabs" style="display:none;"></div>

<div class="jd-alert jd-alert--info" id="jdKelasEmptyNote" style="display:none;margin-bottom:14px;">
    <i class="fas fa-info-circle"></i>
    <div><b>Belum ada jadwal</b> <span> — gunakan tombol <b>+</b> pada grid di bawah untuk mengisi jadwal kelas ini.</span></div>
</div>

{{-- ===== LEGEND ===== --}}
<div class="jd-legend" id="jdLegend" style="margin-bottom:14px;"></div>

{{-- ===== SCHEDULER GRID ===== --}}
<div class="jd-scheduler-wrap">
    <div class="jd-scheduler" id="jdSched"></div>
</div>

<div class="jd-card" id="jdJenjangEmpty" style="display:none;">
    <div class="jd-empty">
        <div class="jd-empty-illus">
            <div class="ring"></div>
            <div class="core"><i class="fas fa-calendar-times"></i></div>
        </div>
        <div class="jd-empty-title">Belum Ada Kelas</div>
        <div class="jd-empty-sub">Tidak ada kelas yang terdaftar pada jenjang ini. Tambahkan kelas terlebih dahulu.</div>
    </div>
</div>

@endif

{{-- ===== FAB ===== --}}
<button type="button" class="jd-fab" data-bs-toggle="modal" data-bs-target="#modalTambah" aria-label="Tambah jadwal"><i class="fas fa-plus"></i></button>

{{-- ===== MODAL TAMBAH (WIZARD) ===== --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" id="formTambahJadwal">
            @csrf
            <div class="modal-content jd-modal-card">
                <div class="jd-modal-head" style="--mc:#2563eb;">
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    <div class="d-flex align-items-center gap-3">
                        <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-plus"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Tambah Jadwal Pelajaran</h5>
                            <div style="font-size:12px;opacity:.85;color:#fff;">Isi langkah demi langkah untuk menyusun jadwal baru</div>
                        </div>
                    </div>
                </div>
                <div class="modal-body p-4">
                    @if($tahunAjaranAktif)
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">

                    {{-- Stepper --}}
                    <div class="jd-stepper" id="wizSteps">
                        <div class="jd-step active" data-wstep="1"><div class="jd-step-dot">1</div><div class="jd-step-txt"><b>Kelas</b><span>Pilih kelas</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="2"><div class="jd-step-dot">2</div><div class="jd-step-txt"><b>Mapel</b><span>Mata pelajaran</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="3"><div class="jd-step-dot">3</div><div class="jd-step-txt"><b>Guru</b><span>Pengampu</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="4"><div class="jd-step-dot">4</div><div class="jd-step-txt"><b>Hari</b><span>Hari mengajar</span></div></div>
                        <div class="jd-step-line"></div>
                        <div class="jd-step" data-wstep="5"><div class="jd-step-dot">5</div><div class="jd-step-txt"><b>Jam</b><span>Slot waktu</span></div></div>
                    </div>

                    {{-- Pane 1: Kelas --}}
                    <div class="jd-wizard-pane is-show" data-pane="1">
                        <label class="jd-filter" style="min-width:0;">
                            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-school me-1" style="color:var(--jd-primary);"></i> Pilih Kelas</span>
                            <select name="kelas_id" class="jd-select" id="tambah_kelas" required>
                                <option value="">&mdash; Pilih Kelas &mdash;</option>
                                @foreach($semuaKelas as $item)
                                <option value="{{ $item->id }}" data-jenjang-id="{{ $item->jenjang_id }}" data-jenjang-nama="{{ $item->jenjang->nama_jenjang ?? '-' }}">
                                    {{ $item->nama_kelas }} &mdash; {{ $item->jenjang->nama_jenjang ?? '-' }}
                                </option>
                                @endforeach
                            </select>
                        </label>
                        <div class="jd-wizard-hint"><i class="fas fa-magic"></i> Jenjang terisi otomatis: <b id="tambah_jenjang" style="color:var(--jd-primary);margin-left:4px;">-</b></div>
                    </div>

                    {{-- Pane 2: Mapel --}}
                    <div class="jd-wizard-pane" data-pane="2">
                        <label class="jd-filter" style="min-width:0;">
                            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-book-open me-1" style="color:var(--jd-primary);"></i> Mata Pelajaran</span>
                            <select name="mata_pelajaran_id" class="jd-select" id="tambah_mapel" required>
                                <option value="">&mdash; Pilih Mata Pelajaran &mdash;</option>
                            </select>
                        </label>
                        <div class="jd-wizard-hint"><i class="fas fa-filter"></i> Ditampilkan sesuai pengampu mapel untuk kelas terpilih.</div>
                    </div>

                    {{-- Pane 3: Guru --}}
                    <div class="jd-wizard-pane" data-pane="3">
                        <label class="jd-filter" style="min-width:0;">
                            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-user-graduate me-1" style="color:var(--jd-primary);"></i> Guru Pengampu</span>
                            <select name="guru_id" class="jd-select" id="tambah_guru" required>
                                <option value="">&mdash; Pilih Guru &mdash;</option>
                            </select>
                        </label>
                        <div class="jd-wizard-hint"><i class="fas fa-filter"></i> Ditampilkan sesuai pengampu mata pelajaran terpilih.</div>
                    </div>

                    {{-- Pane 4: Hari --}}
                    <div class="jd-wizard-pane" data-pane="4">
                        <div style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:10px;"><i class="fas fa-calendar-day me-1" style="color:var(--jd-primary);"></i> Hari Mengajar</div>
                        <div class="d-flex flex-wrap gap-2" id="wizHariWrap">
                            @foreach($hariList as $day)
                            <button type="button" class="jd-chip-select" data-hari="{{ $day }}"><i class="fas fa-calendar-day"></i> {{ $day }}</button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pane 5: Jam + preview + conflict --}}
                    <div class="jd-wizard-pane" data-pane="5">
                        <div style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:10px;"><i class="fas fa-clock me-1" style="color:var(--jd-primary);"></i> Slot Jam Pelajaran</div>
                        <div class="d-flex flex-wrap gap-2" id="wizJamWrap">
                            @foreach($jamSlot as $jk => $jt)
                            <button type="button" class="jd-chip-select" data-jam="{{ $jk }}"><i class="fas fa-clock"></i> {{ $jt['label'] }} <span style="opacity:.7;">{{ $jt['mulai'] }}-{{ $jt['selesai'] }}</span></button>
                            @endforeach
                        </div>

                        <div id="wizPreviewWrap" style="display:none;margin-top:16px;"></div>

                        <div class="jd-conflict" id="wizConflict">
                            <div class="jd-conflict-title"><i class="fas fa-exclamation-triangle"></i> Bentrok Terdeteksi</div>
                            <div id="wizConflictItems"></div>
                        </div>
                        <div class="jd-conflict-ok" id="wizOk">
                            <i class="fas fa-check-circle"></i> Slot ini aman &mdash; tidak ada bentrok dengan jadwal lain.
                        </div>
                    </div>
                    @else
                    <div class="jd-alert jd-alert--err mb-0"><i class="fas fa-exclamation-triangle"></i> Tidak ada tahun ajaran aktif. Jadwal tidak dapat ditambahkan.</div>
                    @endif
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-between">
                    <button type="button" class="jd-btn" id="wizBack"><i class="fas fa-arrow-left"></i> Kembali</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="jd-btn jd-btn--ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="jd-btn jd-btn--solid" id="btnSimpanJadwal" disabled><i class="fas fa-save"></i> Simpan Jadwal</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL SALIN ===== --}}
<div class="modal fade" id="modalSalinJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content jd-modal-card">
            <div class="jd-modal-head" style="--mc:#2563eb;">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <div class="d-flex align-items-center gap-3">
                    <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-copy"></i></div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Salin Jadwal Pelajaran</h5>
                        <div style="font-size:12px;opacity:.85;color:#fff;">Migrasi jadwal antar tahun ajaran</div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="jd-mig">
                    <div class="jd-mig-step is-active" data-mig="1">
                        <div class="jd-mig-step-icon"><i class="fas fa-database"></i></div>
                        <div class="jd-mig-step-txt"><b>Membaca jadwal lama</b><span id="migFrom">Menyiapkan data tahun ajaran sebelumnya</span></div>
                    </div>
                    <div class="jd-mig-step" data-mig="2">
                        <div class="jd-mig-step-icon"><i class="fas fa-copy"></i></div>
                        <div class="jd-mig-step-txt"><b>Menyalin jadwal</b><span>Menyalin ke tahun ajaran aktif &middot; bentrok dilewati</span></div>
                    </div>
                    <div class="jd-mig-step" data-mig="3">
                        <div class="jd-mig-step-icon"><i class="fas fa-flag-checkered"></i></div>
                        <div class="jd-mig-step-txt"><b>Selesai</b><span>Jadwal siap digunakan</span></div>
                    </div>
                </div>

                <div class="jd-mig-bar mt-2 mb-1"><div class="jd-mig-bar-fill" id="migBar"></div></div>

                <div class="jd-mig-stats">
                    <div class="jd-mig-stat berhasil"><b id="migBerhasil">+0</b><span>Jadwal Disalin</span></div>
                    <div class="jd-mig-stat dilewati"><b id="migDilewati">0</b><span>Dilewati</span></div>
                </div>

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
<form action="{{ route('jadwal-pelajaran.salin') }}" method="POST" id="formSalin">@csrf</form>

{{-- ===== MODAL EXPORT & CETAK ===== --}}
<div class="modal fade" id="modalExport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content jd-modal-card">
            <div class="jd-modal-head" style="--mc:#2563eb;">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <div class="d-flex align-items-center gap-3">
                    <div class="jd-hero-icon" style="width:48px;height:48px;font-size:20px;"><i class="fas fa-file-export"></i></div>
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Export &amp; Cetak</h5>
                        <div style="font-size:12px;opacity:.85;color:#fff;">Unduh atau cetak jadwal pelajaran</div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="jd-export-preview mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="jd-chip jd-chip--red"><i class="fas fa-file-pdf"></i> Export PDF</span>
                        <span style="font-size:12px;color:var(--jd-text-3);">Folio &middot; portrait</span>
                    </div>
                    <form method="GET" action="{{ route('jadwal-pelajaran.export-pdf') }}" id="formExportPdf" target="_blank">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Jenjang</label>
                                <select name="jenjang_id" class="jd-select" id="exportJenjang">
                                    <option value="">Semua Jenjang</option>
                                    @foreach($jenjangs as $j)
                                    <option value="{{ $j->id }}">{{ $j->nama_jenjang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Kelas</label>
                                <select name="kelas_id" class="jd-select" id="exportKelas">
                                    <option value="">Semua Kelas</option>
                                    @foreach($semuaKelas as $k)
                                    <option value="{{ $k->id }}" data-jenjang="{{ $k->jenjang_id }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Guru</label>
                                <select name="guru_id" class="jd-select">
                                    <option value="">Semua Guru</option>
                                    @foreach($gurus as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:var(--jd-text-3);text-transform:uppercase;letter-spacing:.4px;">Tahun Ajaran</label>
                                <select name="tahun_ajaran_id" class="jd-select">
                                    <option value="">Tahun Aktif</option>
                                    @foreach($tahunAjarans as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->tahun_ajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="jd-btn jd-btn--solid"><i class="fas fa-file-pdf"></i> Download PDF</button>
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="jd-chip jd-chip--green"><i class="fas fa-print"></i> Cetak Jadwal Siswa</span>
                    <span style="font-size:12px;color:var(--jd-text-3);">Versi matriks per jenjang untuk dibagikan ke siswa</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('jadwal-pelajaran.cetak-siswa') }}" target="_blank" class="jd-btn jd-btn--soft"><i class="fas fa-print"></i> Semua Jenjang</a>
                    @foreach($jenjangs as $j)
                    <a href="{{ route('jadwal-pelajaran.cetak-siswa', $j->id) }}" target="_blank" class="jd-btn jd-btn--ghost"><i class="fas fa-print"></i> {{ $j->nama_jenjang }}</a>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="jd-btn jd-btn--ghost" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@if(!$jenjangs->isEmpty())
{{-- ===== MODAL DETAIL / EDIT / HAPUS (per jadwal) ===== --}}
@foreach($jadwals as $jadwal)
@php $mcIdx = jd_mapel_color_idx($jadwal->mapel->nama_mapel ?? ''); @endphp

<div class="modal fade" id="detail{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content jd-modal-card jd-mc-{{ $mcIdx }}">
            <div class="jd-modal-head">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <div class="d-flex align-items-center gap-3">
                    <div style="width:54px;height:54px;border-radius:16px;background:rgba(255,255,255,.2);backdrop-filter:blur(8px);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-book-open" style="font-size:22px;"></i>
                    </div>
                    <div style="min-width:0;">
                        <h5 class="fw-bold mb-1" style="font-size:18px;color:#fff;">{{ $jadwal->mapel->nama_mapel ?? '-' }}</h5>
                        <div style="font-size:12.5px;opacity:.9;color:#fff;"><i class="fas fa-user-graduate me-1"></i>{{ $jadwal->guru->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="jd-info-grid mb-3">
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-layer-group"></i> Jenjang</div><div class="val">{{ $jadwal->jenjang->nama_jenjang ?? $jadwal->jenjang->kode ?? '-' }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-school"></i> Kelas</div><div class="val">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-calendar-day"></i> Hari</div><div class="val">{{ $jadwal->hari }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-clock"></i> Jam Ke</div><div class="val">Jam {{ $jadwal->jam_ke }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-hourglass-half"></i> Waktu</div><div class="val">{{ substr((string) $jadwal->jam_mulai, 0, 5) }} - {{ substr((string) $jadwal->jam_selesai, 0, 5) }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</div><div class="val">{{ $jadwal->tahunAjaran->tahun_ajaran ?? '-' }}</div></div>
                </div>
                @if(isset($conflictIds[$jadwal->id]))
                <div class="jd-alert jd-alert--err" style="margin-bottom:14px;"><i class="fas fa-exclamation-triangle"></i> Terdeteksi bentrok &mdash; guru atau kelas sudah memiliki jadwal pada slot ini.</div>
                @endif
                <div class="d-flex gap-2">
                    <button type="button" class="jd-btn jd-btn--soft flex-fill" data-close-before-target="#edit{{ $jadwal->id }}"><i class="fas fa-pen"></i> Edit</button>
                    <button type="button" class="jd-btn jd-btn--danger flex-fill" data-close-before-target="#hapus{{ $jadwal->id }}"><i class="fas fa-trash"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('jadwal-pelajaran.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content jd-modal-card">
                <div class="jd-modal-head" style="--mc:#d97706;">
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.2);backdrop-filter:blur(8px);display:inline-flex;align-items:center;justify-content:center;">
                            <i class="fas fa-pen-to-square" style="font-size:17px;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0" style="font-size:17px;color:#fff;">Edit Jadwal</h5>
                            <div style="font-size:12px;opacity:.85;color:#fff;">{{ $jadwal->kelas->nama_kelas ?? '' }} &middot; {{ $jadwal->hari }} Jam {{ $jadwal->jam_ke }}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:11.5px;font-weight:700;color:var(--jd-text-2);">Kelas</label>
                            <select name="kelas_id" class="jd-select" required>
                                @foreach($semuaKelas as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->kelas_id == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }} ({{ $item->jenjang->nama_jenjang ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:11.5px;font-weight:700;color:var(--jd-text-2);">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="jd-select" required>
                                @foreach($mapels as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->mata_pelajaran_id == $item->id ? 'selected' : '' }}>{{ $item->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:11.5px;font-weight:700;color:var(--jd-text-2);">Guru</label>
                            <select name="guru_id" class="jd-select" required>
                                @foreach($gurus as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->guru_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:11.5px;font-weight:700;color:var(--jd-text-2);">Tahun Ajaran</label>
                            <input type="text" class="jd-control" readonly value="{{ $tahunAjaranAktif->tahun_ajaran }}" style="background:var(--jd-bg);">
                            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:11.5px;font-weight:700;color:var(--jd-text-2);">Hari</label>
                            <select name="hari" class="jd-select" required>
                                @foreach($hariList as $day)
                                <option value="{{ $day }}" {{ $jadwal->hari == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:11.5px;font-weight:700;color:var(--jd-text-2);">Jam Pelajaran</label>
                            <select name="jam_ke" class="jd-select" required>
                                @foreach($jamSlot as $jk => $jt)
                                <option value="{{ $jk }}" {{ $jadwal->jam_ke == $jk ? 'selected' : '' }}>Jam {{ $jk }} ({{ $jt['mulai'] }}-{{ $jt['selesai'] }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="jd-btn" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="jd-btn jd-btn--success"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="hapus{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content jd-modal-card">
            <div class="text-center px-4 pt-5 pb-4">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--jd-red-soft);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <i class="fas fa-triangle-exclamation" style="font-size:30px;color:var(--jd-red);"></i>
                </div>
                <h5 class="fw-bold mb-2" style="font-size:18px;color:var(--jd-text);">Hapus Jadwal?</h5>
                <p class="mb-4" style="font-size:13px;line-height:1.6;color:var(--jd-text-3);">Data yang dihapus tidak dapat dikembalikan. Pastikan Anda yakin sebelum melanjutkan.</p>
                <div class="jd-info-grid mb-4">
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-book-open"></i> Mapel</div><div class="val">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-school"></i> Kelas</div><div class="val">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div></div>
                    <div class="jd-info-cell"><div class="lbl"><i class="fas fa-calendar-day"></i> Slot</div><div class="val">{{ $jadwal->hari }} &middot; Jam {{ $jadwal->jam_ke }}</div></div>
                </div>
                <form action="{{ route('jadwal-pelajaran.destroy', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="button" class="jd-btn flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="jd-btn jd-btn--danger flex-fill"><i class="fas fa-trash"></i> Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach
@endif

@endsection

@push('scripts')
<script>
$(function() {

    function esc(s){ return $('<div>').text(s == null ? '' : String(s)).html(); }

    {{-- ===== DATA ===== --}}
    var JD = {
        hari: @json($jdHariJson),
        jam: @json($jdJamJson),
        jenjang: @json($jdJenjangJson),
        kelas: @json($jdKelasJson),
        data: @json($jdDataJson),
    };
    JD.all = [];
    Object.keys(JD.data).forEach(function (jid) {
        Object.keys(JD.data[jid]).forEach(function (kid) {
            JD.data[jid][kid].forEach(function (j) { JD.all.push(j); });
        });
    });

    var WIZ_DATA = {
        kelas: @json($jdKelasWizardJson),
        pengampu: @json($jdPengampuJson),
        mapels: @json($jdMapelJson),
        gurus: @json($jdGuruJson),
    };

    {{-- ===== FLASH TOAST ===== --}}
    @if(session('success'))
    window.JD.toast('ok', 'Berhasil', @json(session('success')));
    @endif
    @if(session('error'))
    window.JD.toast('err', 'Gagal', @json(session('error')));
    @endif
    @if($errors->any())
    window.JD.toast('err', 'Periksa Kembali', @json($errors->first()));
    @endif

    {{-- ===== KPI COUNT-UP ===== --}}
    function animateCount($el) {
        var target = parseFloat($el.data('count')) || 0;
        var dur = 700, start = null;
        function tick(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            $el.text(Math.round(target * (1 - Math.pow(1 - p, 3))));
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }
    if ($('.jd-kpi-num[data-count]').length && 'IntersectionObserver' in window) {
        var kpiIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { animateCount($(en.target)); kpiIO.unobserve(en.target); }
            });
        }, { threshold: .4 });
        $('.jd-kpi-num[data-count]').each(function () { kpiIO.observe(this); });
    } else {
        $('.jd-kpi-num[data-count]').each(function () { $(this).text($(this).data('count')); });
    }

    {{-- ===== STICKY TOOLBAR ===== --}}
    if ($('#filterForm').length) {
        $(window).on('scroll', function () {
            $('#filterForm').toggleClass('is-stuck', $(window).scrollTop() + 78 > $('#filterForm').offset().top);
        });
    }
    $('#filterForm select').on('change', function () { this.form.submit(); });

    {{-- ===== EXPORT: jenjang -> kelas ===== --}}
    $('#exportJenjang').on('change', function () {
        var jid = this.value;
        $('#exportKelas option').each(function () {
            var show = !jid || $(this).data('jenjang') == jid || $(this).val() === '';
            $(this).prop('disabled', !show).toggle(show);
        });
        $('#exportKelas').val('');
    });

    {{-- ===== MODAL CHAIN (detail -> edit/hapus) ===== --}}
    $(document).on('click', '[data-close-before-target]', function () {
        var target = $(this).data('close-before-target');
        var $m = $(this).closest('.modal');
        $m.one('hidden.bs.modal', function () { $(target).modal('show'); });
        $m.modal('hide');
    });

    {{-- ===== SCHEDULER ===== --}}
    var currentJenjang = null, currentKelas = null, searchTerm = '';

    function skeletonHtml() {
        var h = '<div class="jd-sched-row jd-sched-head"><div class="jd-sched-hcell">Jam</div>';
        JD.hari.forEach(function (d) { h += '<div class="jd-sched-hcell">' + esc(d) + '</div>'; });
        h += '</div>';
        for (var r = 0; r < 5; r++) {
            h += '<div class="jd-sched-row jd-sched-body-row"><div class="jd-sched-time"><span class="jd-skeleton" style="height:14px;width:46px;"></span></div>';
            for (var c = 0; c < 6; c++) h += '<div class="jd-sched-cell"><div class="jd-skeleton" style="height:70px;"></div></div>';
            h += '</div>';
        }
        return h;
    }

    function slotFor(jadwals, hari, jk) {
        for (var i = 0; i < jadwals.length; i++) {
            if (jadwals[i].hari === hari && String(jadwals[i].jam_ke) === String(jk)) return jadwals[i];
        }
        return null;
    }
    function matches(j, q) {
        if (!q) return true;
        q = q.toLowerCase();
        return (j.mapel || '').toLowerCase().indexOf(q) !== -1 || (j.guru || '').toLowerCase().indexOf(q) !== -1;
    }
    function buildGrid(jid, kid, q) {
        var list = (JD.data[jid] || {})[kid] || [];
        var found = 0;
        list.forEach(function (j) { if (matches(j, q)) found++; });
        if (list.length && !found) {
            return '<div class="jd-empty"><div class="jd-empty-illus"><div class="ring"></div><div class="core"><i class="fas fa-search"></i></div></div>'
                + '<div class="jd-empty-title">Tidak Ditemukan</div><div class="jd-empty-sub">Tidak ada mata pelajaran atau guru yang cocok dengan pencarian &ldquo;' + esc(q) + '&rdquo; pada kelas ini.</div></div>';
        }
        var html = '<div class="jd-sched-row jd-sched-head"><div class="jd-sched-hcell">Jam</div>';
        JD.hari.forEach(function (h) { html += '<div class="jd-sched-hcell">' + esc(h) + '</div>'; });
        html += '</div>';
        var rowIdx = 0;
        [1, 2, 3, 4].forEach(function (jk) {
            rowIdx++;
            if (rowIdx === 3) html += '<div class="jd-sched-break"><i class="fas fa-mug-hot"></i> Istirahat</div>';
            var s = JD.jam[jk];
            html += '<div class="jd-sched-row jd-sched-body-row"><div class="jd-sched-time"><b>Jam ' + jk + '</b><span>' + s.mulai + ' - ' + s.selesai + '</span></div>';
            JD.hari.forEach(function (h) {
                var j = slotFor(list, h, jk);
                if (j && matches(j, q)) {
                    html += '<div class="jd-sched-cell"><button type="button" class="jd-slot jd-mc-' + j.mc + (j.conflict ? ' is-conflict' : '') + '" data-jd-id="' + j.id + '" title="' + esc(j.mapel) + ' &mdash; ' + esc(j.guru) + '">'
                        + '<span class="jd-slot-top"><span class="jd-slot-name">' + esc(j.mapel) + '</span><span class="jd-slot-dot"></span></span>'
                        + '<span class="jd-slot-guru"><i class="fas fa-user-graduate"></i> ' + esc(j.guru) + '</span>'
                        + '<span class="jd-slot-time"><i class="fas fa-clock"></i> ' + esc(j.jam_mulai) + ' - ' + esc(j.jam_selesai) + '</span>'
                        + (j.conflict ? '<span class="jd-conflict-tag"><i class="fas fa-exclamation"></i> Konflik</span>' : '')
                        + '</button></div>';
                } else if (j) {
                    html += '<div class="jd-sched-cell jd-cell-masked"></div>';
                } else {
                    html += '<div class="jd-sched-cell"><button type="button" class="jd-add-cell" data-kelas="' + kid + '" data-hari="' + h + '" data-jam="' + jk + '" title="Tambah jadwal ' + h + ' jam ' + jk + '"><i class="fas fa-plus"></i></button></div>';
                }
            });
            html += '</div>';
        });
        return html;
    }

    function renderLegend(kid) {
        var map = {};
        ((JD.data[currentJenjang] || {})[kid] || []).forEach(function (j) { map[j.mapel] = j.mc; });
        var html = '';
        Object.keys(map).forEach(function (name) {
            html += '<span class="jd-legend-item"><span class="jd-mapel-dot jd-mc-' + map[name] + '" style="background:var(--mc);"></span> ' + esc(name) + '</span>';
        });
        $('#jdLegend').html(html || '<span class="jd-legend-item" style="color:var(--jd-text-3);">Belum ada mata pelajaran terjadwal pada kelas ini</span>');
    }

    function renderScheduler(kid, q) {
        var $body = $('#jdSched');
        $body.html(skeletonHtml());
        setTimeout(function () {
            $body.html(buildGrid(currentJenjang, kid, q));
            $body.find('.jd-slot').on('click', function () {
                $('#detail' + $(this).data('jd-id')).modal('show');
            });
            $body.find('.jd-add-cell').on('click', function () {
                openWizard($(this).data('kelas'), $(this).data('hari'), $(this).data('jam'));
            });
            renderLegend(kid);
        }, 240);
    }

    function renderJenjangTabs(activeId) {
        $('#jdJenjangTabs .jd-tab').remove();
        var html = '';
        JD.jenjang.forEach(function (j) {
            var total = 0;
            (JD.kelas[j.id] || []).forEach(function (k) { total += k.count; });
            html += '<button type="button" class="jd-tab' + (String(activeId) === String(j.id) ? ' active' : '') + '" data-jd-j="' + j.id + '"><i class="fas fa-layer-group"></i> ' + esc(j.nama) + '<span class="jd-count">' + total + '</span></button>';
        });
        $('#jdJenjangTabs').append(html);
        movePill();
    }
    function movePill() {
        var $act = $('#jdJenjangTabs .jd-tab.active');
        var $pill = $('#jdJenjangPill');
        if (!$act.length) { $pill.css('opacity', 0); return; }
        $pill.css({ left: $act.position().left, width: $act.outerWidth(), opacity: 1 });
    }
    function renderKelasTabs(jid, aid) {
        var list = JD.kelas[jid] || [];
        var html = '';
        list.forEach(function (k) {
            html += '<button type="button" class="jd-tab-kelas' + (String(aid) === String(k.id) ? ' active' : '') + (k.count === 0 ? ' is-empty' : '') + '" data-jd-k="' + k.id + '"><i class="fas fa-school"></i> ' + esc(k.nama) + '<span class="jd-count">' + k.count + '</span></button>';
        });
        $('#jdKelasTabs').html(html).toggle(!!list.length);
    }
    function setJenjang(jid) {
        currentJenjang = jid;
        renderJenjangTabs(jid);
        var list = JD.kelas[jid] || [];
        if (list.length) {
            $('#jdJenjangEmpty').hide();
            $('#jdSched').show();
            currentKelas = list[0].id;
            renderKelasTabs(jid, currentKelas);
            setKelas(currentKelas);
        } else {
            currentKelas = null;
            renderKelasTabs(jid, null);
            $('#jdKelasEmptyNote').hide();
            $('#jdLegend').html('');
            $('#jdSched').hide();
            $('#jdJenjangEmpty').show();
        }
    }
    function setKelas(kid) {
        currentKelas = kid;
        $('#jdKelasTabs .jd-tab-kelas').removeClass('active');
        $('#jdKelasTabs .jd-tab-kelas[data-jd-k="' + kid + '"]').addClass('active');
        var list = (JD.data[currentJenjang] || {})[kid] || [];
        $('#jdKelasEmptyNote').toggle(list.length === 0);
        renderScheduler(kid, searchTerm);
    }
    $('#jdJenjangTabs').on('click', '.jd-tab', function () { setJenjang($(this).data('jd-j')); });
    $('#jdKelasTabs').on('click', '.jd-tab-kelas', function () { setKelas($(this).data('jd-k')); });

    var searchTimer;
    $('#jdSearch').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            searchTerm = $('#jdSearch').val();
            if (currentKelas) renderScheduler(currentKelas, searchTerm);
        }, 250);
    });

    if (JD.jenjang.length) {
        var activeJenjang = @json(request('jenjang_id') ? (int) request('jenjang_id') : null);
        var useJenjang = JD.jenjang[0].id;
        JD.jenjang.forEach(function (j) {
            if (String(j.id) === String(activeJenjang)) useJenjang = j.id;
        });
        setJenjang(useJenjang);
    }

    {{-- ===== WIZARD TAMBAH ===== --}}
    var WIZ = { step: 1, kelas: '', mapel: '', guru: '', hari: '', jam: '', prefill: false };

    function wizReset() {
        WIZ = { step: 1, kelas: '', mapel: '', guru: '', hari: '', jam: '', prefill: false };
        $('#tambah_kelas').val('');
        $('#tambah_mapel').html('<option value="">&mdash; Pilih Mata Pelajaran &mdash;</option>');
        $('#tambah_guru').html('<option value="">&mdash; Pilih Guru &mdash;</option>');
        $('#tambah_jenjang').text('-');
        $('#wizHariWrap .jd-chip-select').removeClass('active');
        $('#wizJamWrap .jd-chip-select').removeClass('active');
        $('#wizPreviewWrap').hide().html('');
        $('#wizConflict').removeClass('is-show');
        $('#wizOk').removeClass('is-show');
        $('#btnSimpanJadwal').prop('disabled', true);
        wizGo(1);
    }
    function wizGo(step) {
        WIZ.step = step;
        $('#wizSteps .jd-step').each(function () {
            var s = parseInt($(this).data('wstep'), 10);
            $(this).toggleClass('active', s === step).toggleClass('done', s < step);
        });
        $('#wizSteps .jd-step-line').each(function (i) {
            $(this).toggleClass('done', i < step - 1);
        });
        $('.jd-wizard-pane').each(function () {
            $(this).toggleClass('is-show', String($(this).data('pane')) === String(step));
        });
        $('#wizBack').toggle(step > 1);
        wizRefreshSubmit();
    }
    function wizRefreshSubmit() {
        var ready = !!(WIZ.kelas && WIZ.mapel && WIZ.guru && WIZ.hari && WIZ.jam);
        $('#btnSimpanJadwal').prop('disabled', !ready || $('#wizConflict').hasClass('is-show'));
    }
    function wizSelName(kind, id) {
        var arr = kind === 'mapel' ? WIZ_DATA.mapels : WIZ_DATA.gurus;
        for (var i = 0; i < arr.length; i++) if (String(arr[i].id) === String(id)) return arr[i].nama;
        return '';
    }
    function wizSelKelasName(id) {
        for (var i = 0; i < WIZ_DATA.kelas.length; i++) if (String(WIZ_DATA.kelas[i].id) === String(id)) return WIZ_DATA.kelas[i].nama;
        return '';
    }
    function wizUpdatePreview() {
        if (!(WIZ.kelas && WIZ.mapel && WIZ.guru && WIZ.hari && WIZ.jam)) return;
        var mapelName = wizSelName('mapel', WIZ.mapel);
        var guruName = wizSelName('guru', WIZ.guru);
        var kelasName = wizSelKelasName(WIZ.kelas);
        var mc = window.JD.mapelColorIdx(mapelName);
        var jam = JD.jam[WIZ.jam];
        var html = '<div class="jd-preview jd-mc-' + mc + '"><div class="jd-preview-icon"><i class="fas fa-book-open"></i></div>'
            + '<div style="flex:1;min-width:0;"><div class="jd-preview-name">' + esc(mapelName) + '</div>'
            + '<div class="jd-preview-meta">'
            + '<span><i class="fas fa-user-graduate"></i> ' + esc(guruName) + '</span>'
            + '<span><i class="fas fa-school"></i> ' + esc(kelasName) + '</span>'
            + '<span><i class="fas fa-calendar-day"></i> ' + esc(WIZ.hari) + '</span>'
            + '<span><i class="fas fa-clock"></i> Jam ' + WIZ.jam + ' (' + jam.mulai + '-' + jam.selesai + ')</span>'
            + '</div></div></div>';
        $('#wizPreviewWrap').html(html).show();

        var items = [];
        JD.all.forEach(function (j) {
            if (String(j.kelas_id) === String(WIZ.kelas) && j.hari === WIZ.hari && String(j.jam_ke) === WIZ.jam) {
                items.push('<div class="jd-conflict-item"><i class="fas fa-school"></i> Kelas <b>' + esc(j.kelas) + '</b> sudah terisi <b>' + esc(j.mapel) + '</b> pada slot ini.</div>');
            }
            if (String(j.guru_id) === String(WIZ.guru) && j.hari === WIZ.hari && String(j.jam_ke) === WIZ.jam) {
                items.push('<div class="jd-conflict-item"><i class="fas fa-user-graduate"></i> Guru <b>' + esc(j.guru) + '</b> sudah mengajar <b>' + esc(j.mapel) + '</b> pada slot ini.</div>');
            }
        });
        var has = items.length > 0;
        $('#wizConflictItems').html(items.join(''));
        $('#wizConflict').toggleClass('is-show', has);
        $('#wizOk').toggleClass('is-show', !has);
        wizRefreshSubmit();
    }

    function openWizard(kelasId, hari, jam) {
        $('#modalTambah').modal('show');
        wizReset();
        if (kelasId) {
            $('#tambah_kelas').val(kelasId).trigger('change');
            WIZ.prefill = true;
            if (hari) {
                WIZ.hari = hari;
                $('#wizHariWrap .jd-chip-select[data-hari="' + hari + '"]').addClass('active');
            }
            if (jam) {
                WIZ.jam = String(jam);
                $('#wizJamWrap .jd-chip-select[data-jam="' + jam + '"]').addClass('active');
            }
        }
    }
    $('#modalTambah').on('show.bs.modal', function (e) {
        var $t = $(e.relatedTarget);
        var kelas = $t.data('prefill-kelas');
        var hari = $t.data('prefill-hari');
        var jam = $t.data('prefill-jam');
        if (kelas) openWizard(kelas, hari, jam);
        else wizReset();
    });

    $('#tambah_kelas').on('change', function () {
        var val = this.value;
        var $opt = $(this).find(':selected');
        WIZ.kelas = val;
        WIZ.mapel = ''; WIZ.guru = ''; WIZ.hari = ''; WIZ.jam = ''; WIZ.prefill = false;
        $('#tambah_jenjang').text($opt.data('jenjang-nama') || '-');
        $('#tambah_guru').html('<option value="">&mdash; Pilih Guru &mdash;</option>');
        $('#wizHariWrap .jd-chip-select').removeClass('active');
        $('#wizJamWrap .jd-chip-select').removeClass('active');
        $('#wizPreviewWrap').hide().html('');
        $('#wizConflict').removeClass('is-show');
        $('#wizOk').removeClass('is-show');
        $('#btnSimpanJadwal').prop('disabled', true);
        if (!val) { wizGo(1); return; }

        var mapelIds = [];
        WIZ_DATA.pengampu.forEach(function (p) {
            if (String(p.kelas_id) === String(val) && mapelIds.indexOf(String(p.mata_pelajaran_id)) === -1) {
                mapelIds.push(String(p.mata_pelajaran_id));
            }
        });
        var $mapel = $('#tambah_mapel').html('<option value="">&mdash; Pilih Mata Pelajaran &mdash;</option>');
        if (!mapelIds.length) {
            $mapel.append('<option value="" disabled>Tidak ada mata pelajaran untuk kelas ini</option>');
            wizGo(2);
            return;
        }
        WIZ_DATA.mapels.forEach(function (m) {
            if (mapelIds.indexOf(String(m.id)) !== -1) $mapel.append('<option value="' + m.id + '">' + esc(m.nama) + '</option>');
        });
        wizGo(2);
    });

    $('#tambah_mapel').on('change', function () {
        var val = this.value;
        WIZ.mapel = val;
        WIZ.guru = '';
        $('#tambah_guru').html('<option value="">&mdash; Pilih Guru &mdash;</option>');
        if (!WIZ.prefill) {
            WIZ.hari = ''; WIZ.jam = '';
            $('#wizHariWrap .jd-chip-select').removeClass('active');
            $('#wizJamWrap .jd-chip-select').removeClass('active');
            $('#wizPreviewWrap').hide().html('');
            $('#wizConflict').removeClass('is-show');
            $('#wizOk').removeClass('is-show');
            $('#btnSimpanJadwal').prop('disabled', true);
        }
        if (!val) { wizGo(2); return; }

        var guruIds = [];
        WIZ_DATA.pengampu.forEach(function (p) {
            if (String(p.kelas_id) === String(WIZ.kelas) && String(p.mata_pelajaran_id) === String(val) && guruIds.indexOf(String(p.guru_id)) === -1) {
                guruIds.push(String(p.guru_id));
            }
        });
        var $guru = $('#tambah_guru').html('<option value="">&mdash; Pilih Guru &mdash;</option>');
        if (!guruIds.length) {
            $guru.append('<option value="" disabled>Tidak ada guru untuk mapel ini</option>');
            wizGo(3);
            return;
        }
        WIZ_DATA.gurus.forEach(function (g) {
            if (guruIds.indexOf(String(g.id)) !== -1) $guru.append('<option value="' + g.id + '">' + esc(g.nama) + '</option>');
        });
        wizGo(3);
    });

    $('#tambah_guru').on('change', function () {
        WIZ.guru = this.value;
        if (!this.value) { wizGo(3); return; }
        if (WIZ.hari) { wizGo(5); wizUpdatePreview(); }
        else { wizGo(4); }
    });

    $('#wizHariWrap').on('click', '.jd-chip-select', function () {
        WIZ.prefill = false;
        $('#wizHariWrap .jd-chip-select').removeClass('active');
        $(this).addClass('active');
        WIZ.hari = $(this).data('hari');
        wizGo(5);
    });

    $('#wizJamWrap').on('click', '.jd-chip-select', function () {
        WIZ.prefill = false;
        $('#wizJamWrap .jd-chip-select').removeClass('active');
        $(this).addClass('active');
        WIZ.jam = String($(this).data('jam'));
        wizUpdatePreview();
    });

    $('#wizBack').on('click', function () {
        if (WIZ.step > 1) wizGo(WIZ.step - 1);
    });

    $('#formTambahJadwal').on('submit', function () {
        $('#btnSimpanJadwal').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
    });

    {{-- ===== SALIN ===== --}}
    var tahunAjarans = @json($jdTahunJson);
    var tahunAktif = @json($jdTahunAktifJson);

    $('#modalSalinJadwal').on('show.bs.modal', function () {
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

    {{-- ===== RESIZE: pill position ===== --}}
    $(window).on('resize', function () { movePill(); });
});
</script>
@endpush
