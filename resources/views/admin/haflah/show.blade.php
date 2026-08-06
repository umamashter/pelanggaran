@extends('layouts.main')
@section('title','Detail Haflatul Imtihan')
@section('content')

@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-hero-progress { margin-top: 14px; max-width: 460px; }
    .lw-hero-progress .head { display: flex; justify-content: space-between; align-items: center; font-size: 10.5px; font-weight: 700;
        color: rgba(255,255,255,.85); letter-spacing: .4px; text-transform: uppercase; margin-bottom: 6px; }
    .lw-hero-progress .lw-progress { background: rgba(255,255,255,.16); border-color: rgba(255,255,255,.22); }

    /* Activity milestones */
    .lw-act-item { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px dashed var(--lw-border); }
    .lw-act-item:last-child { border-bottom: none; }
    .lw-act-ic { width: 36px; height: 36px; border-radius: 11px; background: var(--lw-bg); color: var(--lw-text-3);
        display: inline-flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; border: 1px solid var(--lw-border); }
    .lw-act-item.is-done .lw-act-ic { background: var(--lw-green-soft); color: var(--lw-green); border-color: var(--lw-green-border); }
    .lw-act-item.is-current .lw-act-ic { background: var(--lw-primary-soft); color: var(--lw-primary); border-color: var(--lw-primary-border); }
    .lw-act-txt b { display: block; font-size: 12.5px; color: var(--lw-text); }
    .lw-act-txt span { font-size: 11px; color: var(--lw-text-3); }
    .lw-act-time { margin-left: auto; flex-shrink: 0; font-size: 11px; font-weight: 600; color: var(--lw-text-3); }

    .lw-ready-pct { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--lw-primary); }
</style>

@php
    $isAktifSesi = session('haflah_id') == $haflatul->id;

    $cLomba = $haflatul->lombas->count();
    $cPeserta = $haflatul->pesertaLombas->count();
    $cJuri = $haflatul->juriLombas->count();
    $cKelompok = $haflatul->kelompokLombas->count();
    $cAspek = $haflatul->aspekPenilaians->count();
    $cHasil = $haflatul->hasilLombas->count();

    $hfProg = 0;
    $hfCdTarget = '';
    $hfCdLabel = '';
    $hfCdDone = '';
    if ($haflatul->status === 'Aktif' && $haflatul->tanggal_selesai) {
        $hfCdTarget = $haflatul->tanggal_selesai->format('Y-m-d\TH:i:s');
        $hfCdLabel = 'Berakhir dalam';
        $hfCdDone = 'Haflah selesai';
        $dur = max(1, $haflatul->tanggal_mulai->startOfDay()->diffInDays($haflatul->tanggal_selesai->startOfDay()));
        $jalan = max(0, now()->startOfDay()->diffInDays($haflatul->tanggal_mulai->startOfDay()));
        $hfProg = $jalan >= $dur ? 100 : (int) round(($jalan / $dur) * 100);
    } elseif ($haflatul->status === 'Persiapan' && $haflatul->tanggal_mulai) {
        $hfCdTarget = $haflatul->tanggal_mulai->format('Y-m-d\TH:i:s');
        $hfCdLabel = 'Dimulai dalam';
        $hfCdDone = 'Segera berlangsung';
    } elseif ($haflatul->status === 'Selesai') {
        $hfProg = 100;
    }

    $hfChecklist = [
        ['label' => 'Data Lomba', 'sub' => 'Lomba terdaftar pada haflah', 'ok' => $cLomba > 0, 'val' => $cLomba, 'icon' => 'bi-trophy-fill', 'tone' => 'accent'],
        ['label' => 'Data Peserta', 'sub' => 'Peserta lomba terdaftar', 'ok' => $cPeserta > 0, 'val' => $cPeserta, 'icon' => 'bi-people-fill', 'tone' => 'rose'],
        ['label' => 'Data Juri', 'sub' => 'Juri penilai terdaftar', 'ok' => $cJuri > 0, 'val' => $cJuri, 'icon' => 'bi-person-badge-fill', 'tone' => 'green'],
        ['label' => 'Aspek Penilaian', 'sub' => 'Kriteria penilaian lomba', 'ok' => $cAspek > 0, 'val' => $cAspek, 'icon' => 'bi-list-check', 'tone' => 'violet'],
        ['label' => 'Hasil Akhir', 'sub' => 'Rekap hasil lomba', 'ok' => $cHasil > 0, 'val' => $cHasil, 'icon' => 'bi-trophy', 'tone' => 'amber'],
    ];
    $hfReady = 0;
    foreach ($hfChecklist as $hfC) {
        if ($hfC['ok']) {
            $hfReady++;
        }
    }
    $hfReadyPct = (int) round(($hfReady / count($hfChecklist)) * 100);

    $hfStatusIdx = match ($haflatul->status) {
        'Persiapan' => 0,
        'Aktif' => 1,
        'Selesai' => 2,
        default => -1
    };
    $hfSteps = [
        ['icon' => 'bi-clock', 'name' => 'Persiapan', 'desc' => 'Penyusunan acara, lomba, dan peserta'],
        ['icon' => 'bi-play-circle-fill', 'name' => 'Aktif', 'desc' => 'Pelaksanaan haflatul imtihan berjalan'],
        ['icon' => 'bi-archive-fill', 'name' => 'Selesai', 'desc' => 'Acara berakhir, data diarsipkan'],
    ];
    $hfActs = [
        ['label' => 'Penyelenggaraan dibuat', 'time' => $haflatul->created_at ? $haflatul->created_at->translatedFormat('d M Y, H:i') : '-', 'icon' => 'bi-plus-circle-fill', 'done' => true],
        ['label' => 'Acara dimulai', 'time' => \Helper::tanggal_indonesia($haflatul->tanggal_mulai, false), 'icon' => 'bi-play-fill', 'done' => $haflatul->status !== 'Persiapan'],
        ['label' => 'Acara berakhir', 'time' => \Helper::tanggal_indonesia($haflatul->tanggal_selesai, false), 'icon' => 'bi-flag-fill', 'done' => $haflatul->status === 'Selesai'],
    ];

    $hfQuick = [
        ['name' => 'Lomba', 'sub' => 'Kelola lomba & sesi', 'icon' => 'bi-trophy-fill', 'tone' => 'lw-qn--violet', 'route' => 'lomba.index'],
        ['name' => 'Peserta', 'sub' => 'Daftar peserta lomba', 'icon' => 'bi-people-fill', 'tone' => 'lw-qn--accent', 'route' => 'peserta-lomba.index'],
        ['name' => 'Kelompok', 'sub' => 'Kelompok perlombaan', 'icon' => 'bi-people-fill', 'tone' => 'lw-qn--green', 'route' => 'kelompok-lomba.index'],
        ['name' => 'Juri', 'sub' => 'Juri penilai lomba', 'icon' => 'bi-person-badge-fill', 'tone' => 'lw-qn--amber', 'route' => 'juri-lomba.index'],
        ['name' => 'Penilaian', 'sub' => 'Input nilai peserta', 'icon' => 'bi-clipboard-check-fill', 'tone' => 'lw-qn--rose', 'route' => 'penilaian-lomba.index'],
        ['name' => 'Hasil', 'sub' => 'Rekap & pengumuman', 'icon' => 'bi-trophy', 'tone' => 'lw-qn--sky', 'route' => 'hasil-lomba.index'],
    ];
@endphp

<div class="lw-mod jd-page-haflah">

{{-- ===== DETAIL HERO ===== --}}
<div class="lw-detail-hero">
    <div class="lw-detail-hero-grid">
        <div class="d-flex align-items-center gap-4" style="min-width:0;flex:1;">
            <div class="lw-detail-avatar"><i class="bi bi-award-fill"></i></div>
            <div style="min-width:0;">
                <h1 class="lw-detail-title">{{ $haflatul->nama_acara }}</h1>
                <div class="lw-detail-sub">Detail penyelenggaraan haflatul imtihan</div>
                <div class="lw-detail-meta">
                    <span class="lw-hero-badge"><i class="bi bi-mortarboard-fill"></i> {{ $haflatul->tahunAjaran?->tahun_ajaran ?? '-' }}</span>
                    <span class="lw-hero-badge {{ $haflatul->status == 'Aktif' ? 'lw-hero-badge--ok' : ($haflatul->status == 'Persiapan' ? 'lw-hero-badge--warn' : '') }}">
                        <i class="bi {{ lw_status_icon($haflatul->status) }}"></i> {{ $haflatul->status }}
                    </span>
                    @if($isAktifSesi)
                    <span class="lw-hero-badge lw-hero-badge--ok"><span class="lw-dot"></span> SEDANG AKTIF</span>
                    @endif
                </div>

                @if($haflatul->status !== 'Selesai')
                <div class="lw-hero-progress">
                    <div class="head">
                        <span>{{ $haflatul->status === 'Aktif' ? 'Progres pelaksanaan' : 'Perjalanan acara' }}</span>
                        <span>{{ $hfProg }}%</span>
                    </div>
                    <div class="lw-progress"><div class="lw-progress-fill" style="width:{{ $hfProg }}%"></div></div>
                </div>
                @endif
            </div>
        </div>

        @if($hfCdTarget)
        <div class="lw-countdown" data-target="{{ $hfCdTarget }}" data-label="{{ $hfCdLabel }}" data-done="{{ $hfCdDone }}" role="timer" aria-label="{{ $hfCdLabel }}">
            <span class="lw-cd-label"><i class="bi bi-hourglass-split"></i> <span class="lw-cd-label-txt">{{ $hfCdLabel }}</span></span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-d">00</span><span class="lw-cd-lbl">Hari</span></div>
            <span class="lw-cd-sep">:</span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-h">00</span><span class="lw-cd-lbl">Jam</span></div>
            <span class="lw-cd-sep">:</span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-m">00</span><span class="lw-cd-lbl">Mnt</span></div>
            <span class="lw-cd-sep">:</span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-s">00</span><span class="lw-cd-lbl">Dtk</span></div>
        </div>
        @endif

        <div class="lw-hero-right">
            <a href="{{ route('haflatul-imtihan.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Daftar</a>
            @if($haflatul->status !== 'Selesai')
            <a href="{{ route('haflatul-imtihan.edit', $haflatul->id) }}" class="lw-btn lw-btn--accent"><i class="bi bi-pencil"></i> Edit</a>
            @endif
        </div>
    </div>
</div>

{{-- ===== STATISTIK ===== --}}
<div class="lw-stat-grid">
    <div class="lw-card lw-stat">
        <div class="lw-stat-icon violet"><i class="bi bi-trophy-fill"></i></div>
        <div>
            <div class="lw-stat-num" data-count="{{ $cLomba }}">0</div>
            <div class="lw-stat-label">Lomba</div>
        </div>
    </div>
    <div class="lw-card lw-stat">
        <div class="lw-stat-icon accent"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="lw-stat-num" data-count="{{ $cPeserta }}">0</div>
            <div class="lw-stat-label">Peserta</div>
        </div>
    </div>
    <div class="lw-card lw-stat">
        <div class="lw-stat-icon green"><i class="bi bi-person-badge-fill"></i></div>
        <div>
            <div class="lw-stat-num" data-count="{{ $cJuri }}">0</div>
            <div class="lw-stat-label">Juri</div>
        </div>
    </div>
    <div class="lw-card lw-stat">
        <div class="lw-stat-icon amber"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="lw-stat-num" data-count="{{ $cKelompok }}">0</div>
            <div class="lw-stat-label">Kelompok</div>
        </div>
    </div>
</div>

<div class="lw-two-col">
    {{-- ===== INFO ===== --}}
    <div class="lw-card lw-card-pad">
        <h5 class="lw-section-title"><i class="bi bi-info-circle-fill"></i> Informasi Haflah</h5>
        <p class="lw-section-sub">Ringkasan penyelenggaraan haflatul imtihan</p>
        <div class="lw-info-grid" style="grid-template-columns:repeat(auto-fill,minmax(140px,1fr));">
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-calendar-week"></i> Tahun Ajaran</div>
                <div class="val">{{ $haflatul->tahunAjaran?->tahun_ajaran ?? '-' }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-calendar-event"></i> Tanggal Mulai</div>
                <div class="val">{{ \Helper::tanggal_indonesia($haflatul->tanggal_mulai) }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-calendar-check"></i> Tanggal Selesai</div>
                <div class="val">{{ \Helper::tanggal_indonesia($haflatul->tanggal_selesai) }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-flag-fill"></i> Status</div>
                <div class="val">
                    <span class="lw-chip {{ lw_status_chip($haflatul->status) }}">
                        <i class="bi {{ lw_status_icon($haflatul->status) }}"></i> {{ $haflatul->status }}
                    </span>
                </div>
            </div>
        </div>

        <h5 class="lw-section-title" style="margin-top:22px;"><i class="bi bi-signpost-2"></i> Alur Penyelenggaraan</h5>
        <p class="lw-section-sub">Status berjalan otomatis sesuai tanggal</p>
        <div class="lw-timeline">
            @foreach($hfSteps as $hfI => $hfStep)
            <div class="lw-tl-item {{ $hfI < $hfStatusIdx ? 'is-done' : ($hfI == $hfStatusIdx ? 'is-current' : '') }}">
                <div class="lw-tl-dot"><i class="bi {{ $hfStep['icon'] }}"></i></div>
                <div class="lw-tl-card">
                    <div class="lw-tl-main">
                        <div class="lw-tl-name">{{ $hfStep['name'] }}</div>
                        <div class="lw-tl-desc">{{ $hfStep['desc'] }}</div>
                    </div>
                    <div class="lw-tl-tag">
                        @if($hfI < $hfStatusIdx)
                        <span class="lw-chip lw-chip--green"><i class="bi bi-check-lg"></i> Selesai</span>
                        @elseif($hfI == $hfStatusIdx)
                        <span class="lw-chip lw-chip--glow"><span class="lw-dot"></span> Berlangsung</span>
                        @else
                        <span class="lw-chip lw-chip--slate"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;">
        {{-- ===== CHECKLIST KESIAPAN ===== --}}
        <div class="lw-card lw-card-pad">
            <div class="d-flex align-items-center justify-content-between gap-2" style="margin-bottom:4px;">
                <h5 class="lw-section-title mb-0"><i class="bi bi-list-check"></i> Kesiapan Data</h5>
                <span class="lw-ready-pct"><i class="bi bi-check-circle-fill"></i> {{ $hfReadyPct }}%</span>
            </div>
            <p class="lw-section-sub">Kelengkapan data pendukung haflah</p>
            <div class="lw-progress" style="margin-bottom:14px;">
                <div class="lw-progress-fill" style="width:{{ $hfReadyPct }}%"></div>
            </div>
            <div class="lw-checklist">
                @foreach($hfChecklist as $hfC)
                <div class="lw-check-item {{ $hfC['ok'] ? 'is-ok' : '' }}">
                    <div class="lw-check-ic"><i class="bi {{ $hfC['ok'] ? 'bi-check-lg' : $hfC['icon'] }}"></i></div>
                    <div class="lw-check-txt">
                        <b>{{ $hfC['label'] }}</b>
                        <span>{{ $hfC['sub'] }}</span>
                    </div>
                    <div class="lw-check-val">
                        <span class="lw-chip {{ $hfC['ok'] ? 'lw-chip--green' : 'lw-chip--slate' }}">
                            <i class="bi {{ $hfC['ok'] ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                            {{ $hfC['ok'] ? 'Siap' : $hfC['val'] . ' data' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ===== AKTIVITAS ===== --}}
        <div class="lw-card lw-card-pad">
            <h5 class="lw-section-title"><i class="bi bi-clock-history"></i> Aktivitas</h5>
            <p class="lw-section-sub">Linimasa milestone penyelenggaraan</p>
            @foreach($hfActs as $hfA)
            <div class="lw-act-item {{ $hfA['done'] ? 'is-done' : 'is-current' }}">
                <div class="lw-act-ic"><i class="bi {{ $hfA['icon'] }}"></i></div>
                <div class="lw-act-txt">
                    <b>{{ $hfA['label'] }}</b>
                    <span>{{ $hfA['done'] ? 'Tercatat' : 'Menunggu' }}</span>
                </div>
                <div class="lw-act-time">{{ $hfA['time'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ===== QUICK MENU ===== --}}
<div class="lw-card lw-card-pad" style="margin-bottom:20px;">
    <h5 class="lw-section-title"><i class="bi bi-lightning-charge-fill"></i> Menu Cepat</h5>
    <p class="lw-section-sub mb-0">Akses cepat modul terkait haflatul imtihan ini</p>
    <div class="lw-quicknav-grid" style="margin-top:16px;">
        @foreach($hfQuick as $hfQ)
        <a href="{{ route($hfQ['route']) }}" class="lw-qn-card {{ $hfQ['tone'] }}">
            <div class="lw-qn-ic"><i class="bi {{ $hfQ['icon'] }}"></i></div>
            <div class="lw-qn-body">
                <span class="lw-qn-name">{{ $hfQ['name'] }}</span>
                <span class="lw-qn-sub">{{ $hfQ['sub'] }}</span>
            </div>
            <i class="bi bi-arrow-right lw-qn-arrow"></i>
        </a>
        @endforeach
    </div>
</div>

{{-- ===== ACTIONS ===== --}}
<div class="lw-form-divider" style="margin-top:0;">
    <a href="{{ route('haflatul-imtihan.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
</div>

</div>
@endsection
