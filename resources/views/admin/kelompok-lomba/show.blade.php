@extends('layouts.main')
@section('title', 'Detail Kelompok Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
    .lw-info-cell { background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 12px; padding: 12px 14px; }
    .lw-info-cell .lbl { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; display: flex; align-items: center; gap: 5px; }
    .lw-info-cell .val { font-size: 14px; font-weight: 600; color: var(--lw-text); }

    .lw-form-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--lw-border); }

    .lw-member-list { display: grid; gap: 8px; }
    .lw-member { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px; background: var(--lw-card); border: 1px solid var(--lw-border); transition: all .2s ease; }
    .lw-member:hover { border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow); }
    .lw-member-ava { width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; }

    @media (max-width: 767.98px) { .lw-form-actions { flex-direction: column; } .lw-form-actions .lw-btn { width: 100%; justify-content: center; } }
    .lw-qn-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    @media (max-width: 767.98px) { .lw-qn-grid { grid-template-columns: 1fr; } }
</style>

<div class="lw-mod lw-page-kl-show">
<div style="max-width:960px;margin:18px auto 0;padding:0 16px 32px;">

@php
    $isLocked = $kelompokLomba->is_haflah_selesai;
    $anggotaCount = $kelompokLomba->anggota->count();
    $statusClass = $isLocked ? 'lw-chip--violet' : ($anggotaCount > 0 ? 'lw-chip--green' : 'lw-chip--amber');
    $statusLabel = $isLocked ? 'Terkunci' : ($anggotaCount > 0 ? 'Lengkap' : 'Tanpa Anggota');
    $lomba = $kelompokLomba->lomba;
    $kelasLabel = 'Semua Kelas';
    if ($lomba->kelas_min && $lomba->kelas_max) $kelasLabel = 'Kelas '.$lomba->kelas_min.' - '.$lomba->kelas_max;
    elseif ($lomba->kelas_min) $kelasLabel = 'Kelas '.$lomba->kelas_min.'+';
    elseif ($lomba->kelas_max) $kelasLabel = 's/d Kelas '.$lomba->kelas_max;
@endphp

{{-- HERO --}}
<div class="lw-hero" style="margin-bottom:18px;">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon" style="background:{{ lw_ava_color($kelompokLomba->nama_kelompok) }};"><i class="bi bi-people-fill"></i></span>
            <div>
                <h1 class="lw-hero-title">{{ $kelompokLomba->nama_kelompok }}</h1>
                <p class="lw-hero-sub">
                    <span class="lw-chip lw-chip--slate lw-chip-mini"><i class="bi bi-hash"></i>{{ $kelompokLomba->kode_kelompok ?? 'Otomatis' }}</span>
                    &middot; {{ $lomba->nama ?? '-' }}
                    &middot; <span class="lw-chip {{ $statusClass }} lw-chip-mini">{{ $statusLabel }}</span>
                </p>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('kelompok-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            <a href="{{ route('kelompok-lomba.edit', $kelompokLomba->id) }}" class="lw-btn lw-btn--accent" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil-square"></i> Edit</a>
        </div>
    </div>
</div>

{{-- RINGKASAN --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="lw-section-title"><i class="bi bi-info-circle-fill"></i> Informasi Kelompok</div>
    <div class="lw-info-grid mt-2">
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-trophy-fill"></i>Lomba</div><div class="val">{{ $lomba->nama ?? '-' }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-tag-fill"></i>Kode</div><div class="val">{{ $kelompokLomba->kode_kelompok ?? 'Otomatis' }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-mortarboard-fill"></i>Range Kelas</div><div class="val"><span class="lw-chip lw-chip--violet lw-chip-mini">{{ $kelasLabel }}</span></div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-geo-alt-fill"></i>Asal</div><div class="val">{{ $kelompokLomba->asal ?? '-' }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-people-fill"></i>Anggota</div><div class="val"><strong>{{ $anggotaCount }}</strong> siswa</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-flag-fill"></i>Status</div><div class="val"><span class="lw-chip {{ $statusClass }}">{{ $statusLabel }}</span></div></div>
    </div>
</div>

{{-- TEAM SUMMARY --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="lw-section-title"><i class="bi bi-bar-chart-fill"></i> Ringkasan Tim</div>
    <div class="lw-kpi-grid" style="grid-template-columns:repeat(auto-fit,minmax(170px,1fr));margin-bottom:0;">
        <div class="lw-card lw-kpi"><span class="lw-kpi-icon sky"><i class="bi bi-people-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num">{{ $anggotaCount }}</div><div class="lw-kpi-label">Anggota</div></div></div>
        <div class="lw-card lw-kpi"><span class="lw-kpi-icon green"><i class="bi bi-check-circle-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num">{{ $isLocked ? 0 : $anggotaCount }}</div><div class="lw-kpi-label">Anggota Aktif</div></div></div>
        <div class="lw-card lw-kpi"><span class="lw-kpi-icon amber"><i class="bi bi-sign-stop-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" style="font-size:18px;">--</div><div class="lw-kpi-label">Batas Anggota</div></div></div>
        <div class="lw-card lw-kpi"><span class="lw-kpi-icon {{ $isLocked ? 'violet' : ($anggotaCount > 0 ? 'green' : 'amber') }}"><i class="bi bi-{{ $isLocked ? 'lock-fill' : ($anggotaCount > 0 ? 'people-fill' : 'person-x-fill') }}"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" style="font-size:16px;">{{ $isLocked ? 'Terkunci' : ($anggotaCount > 0 ? 'Siap' : 'Kosong') }}</div><div class="lw-kpi-label">Status Tim</div></div></div>
    </div>
</div>

{{-- ANGGOTA LIST --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-list-check"></i> Daftar Anggota <span class="lw-chip lw-chip--navy lw-chip-mini" style="margin-left:8px;">{{ $anggotaCount }}</span></div>
        <a href="{{ route('anggota-kelompok.index', ['kelompok_lomba_id' => $kelompokLomba->id]) }}" class="lw-btn lw-btn--ghost lw-btn--sm"><i class="bi bi-person-plus-fill"></i> Kelola Anggota</a>
    </div>

    @if($kelompokLomba->anggota->isEmpty())
        <div class="text-center py-4" style="color:var(--lw-text-3);font-size:13px;font-weight:500;">
            <i class="bi bi-person-x-fill mb-2 d-block" style="font-size:28px;opacity:.4;"></i>Belum ada anggota.
        </div>
    @else
        <div class="lw-member-list">
            @foreach($kelompokLomba->anggota as $ang)
                @php
                    $student = $ang->student;
                    $userName = $student->user->name ?? $student->nama ?? '-';
                    $nisn = $student->nisn ?? '-';
                    $tingkat = $student->kelasAktif->kelas->tingkat ?? '-';
                    $jenjang = $student->kelasAktif->kelas->jenjang->nama_jenjang ?? '-';
                @endphp
                <div class="lw-member">
                    <div class="lw-member-ava" style="background:{{ lw_ava_color($userName) }};">{{ lw_initial($userName) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:13px;color:var(--lw-text);">{{ $userName }}</div>
                        <div style="font-size:11px;color:var(--lw-text-3);">{{ $nisn }}</div>
                    </div>
                    <div style="text-align:right;">
                        <span class="lw-chip lw-chip--violet lw-chip-mini"><i class="bi bi-mortarboard-fill"></i>{{ $tingkat }}</span>
                        <span class="lw-chip lw-chip--navy lw-chip-mini ms-1"><i class="bi bi-buildings-fill"></i>{{ $jenjang }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- QUICK NAVIGATION --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="lw-section-title"><i class="bi bi-compass-fill"></i> Navigasi Cepat</div>
    <div class="lw-qn-grid" style="margin-bottom:0;">
        <a href="{{ route('anggota-kelompok.index', ['kelompok_lomba_id' => $kelompokLomba->id]) }}" class="lw-qn-card lw-qn--navy" style="text-decoration:none;"><span class="lw-qn-ic"><i class="bi bi-person-plus-fill"></i></span><span class="lw-qn-body"><span class="lw-qn-name">{{ $anggotaCount }} Anggota</span><span class="lw-qn-sub">Kelola anggota tim</span></span><i class="bi bi-chevron-right lw-qn-arrow"></i></a>
        <a href="{{ route('peserta-lomba.index') }}" class="lw-qn-card lw-qn--green" style="text-decoration:none;"><span class="lw-qn-ic"><i class="bi bi-person-vcard-fill"></i></span><span class="lw-qn-body"><span class="lw-qn-name">Peserta</span><span class="lw-qn-sub">Kelola peserta</span></span><i class="bi bi-chevron-right lw-qn-arrow"></i></a>
        <a href="{{ route('penilaian-lomba.index') }}" class="lw-qn-card lw-qn--amber" style="text-decoration:none;"><span class="lw-qn-ic"><i class="bi bi-star-fill"></i></span><span class="lw-qn-body"><span class="lw-qn-name">Penilaian</span><span class="lw-qn-sub">Kelola penilaian</span></span><i class="bi bi-chevron-right lw-qn-arrow"></i></a>
        <a href="{{ route('hasil-lomba.index') }}" class="lw-qn-card lw-qn--violet" style="text-decoration:none;"><span class="lw-qn-ic"><i class="bi bi-medal-fill"></i></span><span class="lw-qn-body"><span class="lw-qn-name">Hasil</span><span class="lw-qn-sub">Lihat hasil</span></span><i class="bi bi-chevron-right lw-qn-arrow"></i></a>
    </div>
</div>

<div class="lw-form-actions">
    <a href="{{ route('kelompok-lomba.index') }}" class="lw-btn lw-btn--ghost" style="border:1px solid var(--lw-border);"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
    <a href="{{ route('kelompok-lomba.edit', $kelompokLomba->id) }}" class="lw-btn lw-btn--solid" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil-square"></i> Edit Kelompok</a>
</div>

</div>
</div>
@endsection
