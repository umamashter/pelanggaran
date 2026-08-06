@extends('layouts.main')

@section('title', 'Jadwal Per Kelas')

@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-kelas-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    @media (max-width: 1200px) { .jd-kelas-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .jd-kelas-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } }
    @media (max-width: 480px) { .jd-kelas-grid { grid-template-columns: 1fr; } }

    .jd-kelas-card { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 24px 18px 20px; }
    .jd-kelas-icon { width: 60px; height: 60px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 24px; color: #fff; margin-bottom: 14px; transition: all .25s ease; }
    .jd-kelas-card:hover .jd-kelas-icon { border-radius: 50%; transform: rotate(-6deg) scale(1.05); }
    .jd-kelas-icon--mi { background: linear-gradient(135deg, #16a34a, #22c55e); box-shadow: 0 10px 20px -8px rgba(22,163,74,.55); }
    .jd-kelas-icon--mts { background: linear-gradient(135deg, #1d4ed8, #3b82f6); box-shadow: 0 10px 20px -8px rgba(37,99,235,.55); }
    .jd-kelas-icon--ma { background: linear-gradient(135deg, #b45309, #f59e0b); box-shadow: 0 10px 20px -8px rgba(217,119,6,.55); }
    .jd-kelas-icon--x { background: linear-gradient(135deg, #4f46e5, #8b5cf6); box-shadow: 0 10px 20px -8px rgba(124,58,237,.55); }

    .jd-kelas-name { font-size: 17px; font-weight: 700; color: var(--jd-text); margin: 0 0 3px; }
    .jd-kelas-meta { font-size: 12px; color: var(--jd-text-3); margin-bottom: 16px; display: inline-flex; align-items: center; gap: 6px; }
    .jd-kelas-meta i { color: var(--jd-primary); }

    .jd-subtitle { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 700; color: var(--jd-text);
        margin: 0 0 14px; }
    .jd-subtitle i { color: var(--jd-primary); }
</style>

@php
    $kodeMap = [
        'MI'  => ['md' => 'mi',  'icon' => 'fa-child'],
        'MTs' => ['md' => 'mts', 'icon' => 'fa-book-open'],
        'MA'  => ['md' => 'ma',  'icon' => 'fa-graduation-cap'],
    ];
    $kelasCount = $kelas->count();
@endphp

<div class="jd-mod jd-page-daftarkelas">

    <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-back mb-3"><i class="fas fa-arrow-left"></i> Dashboard Jadwal</a>

    {{-- ===== HERO ===== --}}
    <div class="jd-detail-hero">
        <div class="jd-detail-hero-grid">
            <div class="jd-hero-left">
                <div class="jd-hero-icon"><i class="fas fa-school"></i></div>
                <div>
                    <h2 class="jd-hero-title">Jadwal Per Kelas</h2>
                    <p class="jd-hero-sub">Pilih kelas untuk melihat peta jadwal pelajaran mingguan.</p>
                    <div class="jd-hero-badges">
                        <span class="jd-hero-badge"><i class="fas fa-school"></i> {{ $kelasCount }} Kelas</span>
                    </div>
                </div>
            </div>
            <div class="jd-hero-right">
                <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-btn jd-btn--light"><i class="fas fa-calendar-alt"></i> Kelola Jadwal</a>
            </div>
        </div>
    </div>

    @if($kelasCount === 0)
    {{-- ===== EMPTY ===== --}}
    <div class="jd-card">
        <div class="jd-empty">
            <div class="jd-empty-illus">
                <div class="ring"></div>
                <div class="core"><i class="fas fa-school"></i></div>
            </div>
            <div class="jd-empty-title">Belum Ada Kelas</div>
            <div class="jd-empty-sub">Tidak ada data kelas. Tambahkan kelas terlebih dahulu untuk menyusun jadwal pelajaran.</div>
        </div>
    </div>
    @else
    {{-- ===== GRID KELAS ===== --}}
    <h3 class="jd-subtitle"><i class="fas fa-layer-group"></i> Pilih Kelas</h3>
    <div class="jd-kelas-grid">
        @foreach($kelas as $item)
            @php
                $kodeJenjang = optional($item->jenjang)->kode ?? '';
                $md = $kodeMap[$kodeJenjang]['md'] ?? 'x';
                $icon = $kodeMap[$kodeJenjang]['icon'] ?? 'fa-school';
            @endphp
            <a href="{{ route('jadwal-pelajaran.per-kelas', $item->id) }}" class="jd-card jd-card--lift jd-kelas-card" style="text-decoration:none;">
                <div class="jd-kelas-icon jd-kelas-icon--{{ $md }}"><i class="fas {{ $icon }}"></i></div>
                <div class="jd-kelas-name">Kelas {{ $item->nama_kelas }}</div>
                <div class="jd-kelas-meta"><i class="fas fa-layer-group"></i> {{ optional($item->jenjang)->nama_jenjang ?? '-' }}</div>
                <span class="jd-btn jd-btn--soft"><i class="fas fa-eye"></i> Buka Jadwal</span>
            </a>
        @endforeach
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
$(function() {
    @if(session('success'))
    window.JD.toast('ok', 'Berhasil', @json(session('success')));
    @endif
    @if(session('error'))
    window.JD.toast('err', 'Gagal', @json(session('error')));
    @endif
});
</script>
@endpush
