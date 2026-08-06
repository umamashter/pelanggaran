@extends('layouts.main')
@section('title', 'Detail Anggota Kelompok')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .lw-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
    .lw-info-cell { background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 12px; padding: 12px 14px; }
    .lw-info-cell .lbl { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; display: flex; align-items: center; gap: 5px; }
    .lw-info-cell .val { font-size: 14px; font-weight: 600; color: var(--lw-text); }
    .lw-form-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--lw-border); }
    @media (max-width: 767.98px) { .lw-form-actions { flex-direction: column; } .lw-form-actions .lw-btn { width: 100%; justify-content: center; } }
</style>

<div class="lw-mod lw-page-ak-show">
<div style="max-width:760px;margin:18px auto 0;padding:0 16px 32px;">

@php
    $student = $anggotaKelompok->student;
    $userName = $student->user->name ?? $student->nama ?? '-';
    $kelasAktif = $student->kelasAktif ?? null;
    $tingkat = $kelasAktif->kelas->tingkat ?? '-';
    $namaKelas = $kelasAktif->kelas->nama_kelas ?? '-';
    $jenjang = $kelasAktif->kelas->jenjang->nama_jenjang ?? '-';
    $kelompok = $anggotaKelompok->kelompokLomba;
    $isLocked = $kelompok->is_haflah_selesai ?? false;
@endphp

{{-- HERO --}}
<div class="lw-hero" style="margin-bottom:18px;">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon" style="background:{{ lw_ava_color($userName) }};"><i class="bi bi-person-fill"></i></span>
            <div>
                <h1 class="lw-hero-title">{{ $userName }}</h1>
                <p class="lw-hero-sub">
                    <span class="lw-chip lw-chip--green lw-chip-mini"><i class="bi bi-person-check-fill"></i>Anggota Tim</span>
                    &middot; {{ $kelompok->nama_kelompok ?? '-' }}
                </p>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('anggota-kelompok.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            <a href="{{ route('anggota-kelompok.edit', $anggotaKelompok->id) }}" class="lw-btn lw-btn--accent" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil-square"></i> Edit</a>
        </div>
    </div>
</div>

{{-- INFO --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="lw-section-title"><i class="bi bi-person-vcard-fill"></i> Detail Siswa</div>
    <div class="lw-info-grid mt-2">
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-people-fill"></i>Kelompok</div><div class="val">{{ $kelompok->nama_kelompok ?? '-' }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-trophy-fill"></i>Lomba</div><div class="val">{{ $kelompok->lomba->nama ?? '-' }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-hash"></i>NISN</div><div class="val">{{ $student->nisn ?? '-' }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-bar-chart-fill"></i>Tingkat</div><div class="val">{{ $tingkat }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-door-open-fill"></i>Kelas</div><div class="val">{{ $namaKelas }}</div></div>
        <div class="lw-info-cell"><div class="lbl"><i class="bi bi-buildings-fill"></i>Jenjang</div><div class="val">{{ $jenjang }}</div></div>
    </div>
</div>

<div class="lw-form-actions">
    <a href="{{ route('anggota-kelompok.index') }}" class="lw-btn lw-btn--ghost" style="border:1px solid var(--lw-border);"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
    <a href="{{ route('anggota-kelompok.edit', $anggotaKelompok->id) }}" class="lw-btn lw-btn--solid" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil-square"></i> Edit Anggota</a>
</div>

</div>
</div>
@endsection
