@extends('layouts.main')

@section('title', 'Jadwal Per Jenjang')

@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-jenjang-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    @media (max-width: 992px) { .jd-jenjang-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .jd-jenjang-grid { grid-template-columns: 1fr; } }

    .jd-jenjang-card { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 28px 20px; }
    .jd-jenjang-icon { width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
        font-size: 34px; color: #fff; margin-bottom: 16px; transition: all .25s ease; }
    .jd-jenjang-card:hover .jd-jenjang-icon { transform: scale(1.08) rotate(-6deg); }
    .jd-jenjang-icon--mi { background: linear-gradient(135deg, #16a34a, #22c55e); box-shadow: 0 12px 24px -8px rgba(22,163,74,.55); }
    .jd-jenjang-icon--mts { background: linear-gradient(135deg, #1d4ed8, #3b82f6); box-shadow: 0 12px 24px -8px rgba(37,99,235,.55); }
    .jd-jenjang-icon--ma { background: linear-gradient(135deg, #b45309, #f59e0b); box-shadow: 0 12px 24px -8px rgba(217,119,6,.55); }
    .jd-jenjang-icon--x { background: linear-gradient(135deg, #4f46e5, #8b5cf6); box-shadow: 0 12px 24px -8px rgba(124,58,237,.55); }

    .jd-jenjang-name { font-size: 18px; font-weight: 700; color: var(--jd-text); margin: 0 0 4px; }
    .jd-jenjang-level { font-size: 11.5px; font-weight: 600; color: var(--jd-text-3); margin-bottom: 16px; }
    .jd-jenjang-level i { color: var(--jd-primary); }

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
    $kelasPerJenjang = $kelas->groupBy('jenjang_id');
@endphp

<div class="jd-mod jd-page-jenjang">

    <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-back mb-3"><i class="fas fa-arrow-left"></i> Dashboard Jadwal</a>

    {{-- ===== HERO ===== --}}
    <div class="jd-detail-hero">
        <div class="jd-detail-hero-grid">
            <div class="jd-hero-left">
                <div class="jd-hero-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h2 class="jd-hero-title">Jadwal Per Jenjang</h2>
                    <p class="jd-hero-sub">Ringkasan jadwal pelajaran untuk setiap jenjang pendidikan.</p>
                    <div class="jd-hero-badges">
                        <span class="jd-hero-badge"><i class="fas fa-layer-group"></i> {{ $jenjangs->count() }} Jenjang</span>
                        <span class="jd-hero-badge jd-hero-badge--ok"><i class="fas fa-school"></i> {{ $kelas->count() }} Kelas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($jenjangs->isEmpty())
    {{-- ===== EMPTY ===== --}}
    <div class="jd-card">
        <div class="jd-empty">
            <div class="jd-empty-illus">
                <div class="ring"></div>
                <div class="core"><i class="fas fa-layer-group"></i></div>
            </div>
            <div class="jd-empty-title">Belum Ada Jenjang</div>
            <div class="jd-empty-sub">Tidak ada data jenjang pendidikan yang terdaftar.</div>
        </div>
    </div>
    @else
    {{-- ===== GRID JENJANG ===== --}}
    <h3 class="jd-subtitle"><i class="fas fa-layer-group"></i> Pilih Jenjang</h3>
    <div class="jd-jenjang-grid">
        @foreach($jenjangs as $j)
            @php
                $md = $kodeMap[$j->kode]['md'] ?? 'x';
                $icon = $kodeMap[$j->kode]['icon'] ?? 'fa-school';
                $jumlahKelas = $kelasPerJenjang->get($j->id)?->count() ?? 0;
            @endphp
            <a href="{{ route('jadwal-jenjang.detail', $j->kode) }}" class="jd-card jd-card--lift jd-jenjang-card" style="text-decoration:none;">
                <div class="jd-jenjang-icon jd-jenjang-icon--{{ $md }}"><i class="fas {{ $icon }}"></i></div>
                <div class="jd-jenjang-name">{{ $j->nama_jenjang }}</div>
                <div class="jd-jenjang-level"><i class="fas fa-school me-1"></i> {{ $jumlahKelas }} Kelas &middot; Tingkat {{ $j->tingkat_awal }}-{{ $j->tingkat_akhir }}</div>
                <span class="jd-btn jd-btn--soft"><i class="fas fa-eye"></i> Lihat Jadwal</span>
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
