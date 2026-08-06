@extends('layouts.main')
@section('title', 'Detail Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-detail-wrap { max-width: 960px; }

    .lw-detail-meta .lw-hero-badge { color: #fff; }

    .lw-stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; }
    .lw-qn-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }

    @media (max-width: 767.98px) { .lw-qn-grid { grid-template-columns: 1fr; } }
</style>

<div class="lw-mod jd-page-lomba-show">

@php
    $isLocked = $lomba->is_haflah_selesai;
    $statusClass = $lomba->status === 'Berlangsung' ? 'lw-chip--green' : ($lomba->status === 'Selesai' ? 'lw-chip--violet' : 'lw-chip--amber');
    $kelasLabel = 'Semua Kelas';
    if ($lomba->kelas_min && $lomba->kelas_max) $kelasLabel = 'Kelas '.$lomba->kelas_min.' - '.$lomba->kelas_max;
    elseif ($lomba->kelas_min) $kelasLabel = 'Kelas '.$lomba->kelas_min.'+';
    elseif ($lomba->kelas_max) $kelasLabel = 's/d Kelas '.$lomba->kelas_max;
@endphp

<div class="lw-detail-wrap">
    <div class="lw-breadcrumb" style="margin-bottom:16px;">
        <a href="{{ route('lomba.index') }}">Lomba</a> <i class="bi bi-chevron-right"></i> <span>Detail</span>
    </div>

    <div class="lw-detail-hero">
        <div class="lw-detail-hero-grid">
            <div class="d-flex align-items-center gap-3" style="min-width:0;">
                <span class="lw-detail-avatar"><i class="bi bi-trophy-fill"></i></span>
                <div style="min-width:0;">
                    <h1 class="lw-detail-title">{{ $lomba->nama }}</h1>
                    <div class="lw-detail-sub">
                        {{ $lomba->haflatulImtihan->nama_acara ?? '-' }}
                        &middot;
                        <i class="bi {{ $lomba->jenis === 'Individu' ? 'bi-person-fill' : 'bi-people-fill' }}"></i>{{ $lomba->jenis }}
                    </div>
                </div>
            </div>
            <div class="lw-detail-meta">
                <span class="lw-hero-badge {{ $lomba->status === 'Berlangsung' ? 'lw-hero-badge--ok' : ($lomba->status === 'Selesai' ? 'lw-hero-badge--lock' : '') }}">
                    <i class="bi {{ lw_status_icon($lomba->status) }}"></i>{{ $lomba->status }}
                </span>
                <a href="{{ route('lomba.edit', $lomba->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>

    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-info-circle-fill"></i> Ringkasan Lomba</div>
        <div class="lw-info-grid">
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-building"></i> Haflatul Imtihan</div>
                <div class="val">{{ $lomba->haflatulImtihan->nama_acara ?? '-' }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-clock"></i> Sesi Lomba</div>
                <div class="val">{{ $lomba->sesiLomba->nama ?? '-' }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi {{ $lomba->jenis === 'Individu' ? 'bi-person-fill' : 'bi-people-fill' }}"></i> Jenis</div>
                <div class="val">
                    <span class="lw-chip {{ $lomba->jenis === 'Individu' ? 'lw-chip--navy' : 'lw-chip--violet' }}">
                        <i class="bi {{ $lomba->jenis === 'Individu' ? 'bi-person-fill' : 'bi-people-fill' }}"></i>{{ $lomba->jenis }}
                    </span>
                </div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-mortarboard-fill"></i> Range Peserta</div>
                <div class="val">{{ $kelasLabel }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-geo-alt-fill"></i> Lokasi</div>
                <div class="val">{{ $lomba->lokasi ?? '-' }}</div>
            </div>
            <div class="lw-info-cell">
                <div class="lbl"><i class="bi bi-flag-fill"></i> Status</div>
                <div class="val">
                    <span class="lw-chip {{ $statusClass }}"><i class="bi {{ lw_status_icon($lomba->status) }}"></i>{{ $lomba->status }}</span>
                </div>
            </div>
            @if($lomba->deskripsi)
            <div class="lw-info-cell" style="grid-column:1 / -1;">
                <div class="lbl"><i class="bi bi-align-left"></i> Deskripsi</div>
                <div class="val" style="font-weight:400;white-space:pre-line;">{{ $lomba->deskripsi }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-bar-chart-fill"></i> Statistik Peserta</div>
        <div class="lw-stat-grid">
            <div class="lw-card lw-stat">
                <span class="lw-stat-icon navy"><i class="bi bi-people-fill"></i></span>
                <div><div class="lw-stat-num">{{ $totalPesertaLomba }}</div><div class="lw-stat-label">Total Eligible</div></div>
            </div>
            <div class="lw-card lw-stat">
                <span class="lw-stat-icon green"><i class="bi bi-check-circle-fill"></i></span>
                <div><div class="lw-stat-num">{{ $pesertaTerdaftar }}</div><div class="lw-stat-label">Sudah Terdaftar</div></div>
            </div>
            <div class="lw-card lw-stat">
                <span class="lw-stat-icon amber"><i class="bi bi-dash-circle-fill"></i></span>
                <div><div class="lw-stat-num">{{ $pesertaBelumTerdaftar }}</div><div class="lw-stat-label">Belum Terdaftar</div></div>
            </div>
            <div class="lw-card lw-stat">
                <span class="lw-stat-icon violet"><i class="bi bi-person-video3"></i></span>
                <div><div class="lw-stat-num">{{ $totalJuri }}</div><div class="lw-stat-label">Total Juri</div></div>
            </div>
        </div>
    </div>

    <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
        <div class="lw-form-section"><i class="bi bi-compass-fill"></i> Navigasi Cepat</div>
        <div class="lw-qn-grid">
            <a href="{{ route('peserta-lomba.index') }}?haflah_id={{ $lomba->haflah_id }}" class="lw-qn-card lw-qn--green" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-person-vcard"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">{{ $pesertaTerdaftar }} Peserta</span><span class="lw-qn-sub">Kelola peserta</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('kelompok-lomba.index') }}" class="lw-qn-card lw-qn--navy" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-layers-fill"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">{{ $lomba->kelompok->count() }} Kelompok</span><span class="lw-qn-sub">Kelompok lomba</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('juri-lomba.index') }}" class="lw-qn-card lw-qn--violet" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-person-video3"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">{{ $totalJuri }} Juri</span><span class="lw-qn-sub">Panel juri</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('aspek-penilaian.index') }}" class="lw-qn-card lw-qn--amber" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-ui-checks-grid"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">{{ $lomba->aspekPenilaians->count() }} Aspek Penilaian</span><span class="lw-qn-sub">Kriteria penilaian</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('penilaian-lomba.index') }}" class="lw-qn-card lw-qn--sky" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-star-fill"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">Penilaian</span><span class="lw-qn-sub">Input nilai</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
            <a href="{{ route('hasil-lomba.index') }}" class="lw-qn-card lw-qn--rose" style="text-decoration:none;">
                <span class="lw-qn-ic"><i class="bi bi-trophy-fill"></i></span>
                <span class="lw-qn-body"><span class="lw-qn-name">{{ $lomba->hasil->count() }} Hasil</span><span class="lw-qn-sub">Rekap pemenang</span></span>
                <i class="bi bi-chevron-right lw-qn-arrow"></i>
            </a>
        </div>
    </div>

    <div class="lw-wizard-nav">
        <a href="{{ route('lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        <span class="spacer"></span>
        <a href="{{ route('lomba.edit', $lomba->id) }}" class="lw-btn lw-btn--solid"><i class="bi bi-pencil"></i> Edit Lomba</a>
    </div>
</div>

</div>
@endsection
