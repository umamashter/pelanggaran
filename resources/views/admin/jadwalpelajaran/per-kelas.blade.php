@extends('layouts.main')

@section('title', 'Jadwal Kelas ' . $kelas->nama_kelas)

@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-cell-empty { display: flex; align-items: center; justify-content: center; height: 100%; min-height: 76px;
        color: var(--jd-text-3); opacity: .45; font-size: 14px; font-weight: 700; letter-spacing: .3px; }

    .jd-day-stats { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 16px; }
    .jd-day-stat { flex: 1; min-width: 120px; text-align: center; padding: 14px 10px; border-radius: 14px;
        background: var(--jd-bg); border: 1px solid var(--jd-border); }
    .jd-day-stat b { display: block; font-size: 20px; color: var(--jd-text); font-variant-numeric: tabular-nums; }
    .jd-day-stat span { font-size: 10.5px; font-weight: 700; color: var(--jd-text-3); text-transform: uppercase; letter-spacing: .4px; }

    .jd-subtitle { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 700; color: var(--jd-text);
        margin: 0 0 12px; }
    .jd-subtitle i { color: var(--jd-primary); }
    .jd-subtitle .jd-count { margin-left: auto; font-size: 11.5px; color: var(--jd-text-3); font-weight: 600; }

    @media (max-width: 767.98px) {
        .jd-day-stats { gap: 8px; }
    }
</style>

@php
    $namaMapels = [];
    foreach ($jadwals as $jw) {
        $namaMapels[$jw->mapel->nama_mapel ?? '-'] = jd_mapel_color_idx($jw->mapel->nama_mapel ?? '-');
    }
    $tahunAktif = $jadwals->first()?->tahunAjaran?->tahun_ajaran;
@endphp

<div class="jd-mod jd-page-perkelas">

    <a href="{{ route('jadwal-pelajaran.daftar-kelas') }}" class="jd-back mb-3"><i class="fas fa-arrow-left"></i> Daftar Kelas</a>

    {{-- ===== HERO ===== --}}
    <div class="jd-detail-hero">
        <div class="jd-detail-hero-grid">
            <div class="jd-hero-left">
                <div class="jd-hero-icon"><i class="fas fa-calendar-week"></i></div>
                <div>
                    <h2 class="jd-hero-title">{{ $kelas->nama_kelas }}</h2>
                    <p class="jd-hero-sub">{{ $kelas->jenjang->nama_jenjang ?? 'Jenjang' }} &middot; Jadwal pelajaran mingguan kelas ini</p>
                    <div class="jd-hero-badges">
                        <span class="jd-hero-badge"><i class="fas fa-book"></i> {{ $jadwals->count() }} Jadwal</span>
                        <span class="jd-hero-badge"><i class="fas fa-calendar-day"></i> {{ $jadwals->pluck('hari')->unique()->count() }} Hari Aktif</span>
                        <span class="jd-hero-badge jd-hero-badge--ok"><i class="fas fa-user-graduate"></i> {{ $jadwals->pluck('guru_id')->unique()->count() }} Guru</span>
                        @if($tahunAktif)
                        <span class="jd-hero-badge"><i class="fas fa-calendar-alt"></i> TA {{ $tahunAktif }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="jd-hero-right">
                <a href="{{ route('jadwal-pelajaran.cetak', $kelas->id) }}" target="_blank" class="jd-btn jd-btn--light"><i class="fas fa-print"></i> Cetak Jadwal</a>
            </div>
        </div>
    </div>

    @if($jadwals->isEmpty())
    {{-- ===== EMPTY ===== --}}
    <div class="jd-card">
        <div class="jd-empty">
            <div class="jd-empty-illus">
                <div class="ring"></div>
                <div class="core"><i class="fas fa-calendar-times"></i></div>
            </div>
            <div class="jd-empty-title">Belum Ada Jadwal</div>
            <div class="jd-empty-sub">Kelas ini belum memiliki jadwal pelajaran untuk tahun ajaran aktif. Silakan tambahkan jadwal melalui menu Jadwal Pelajaran.</div>
            <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-btn jd-btn--soft"><i class="fas fa-plus"></i> Atur Jadwal</a>
        </div>
    </div>
    @else
    {{-- ===== STATISTIK ===== --}}
    <div class="jd-kpi-grid">
        <div class="jd-kpi">
            <div class="jd-kpi-icon blue"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="jd-kpi-num">{{ $jadwals->count() }}</div>
                <div class="jd-kpi-label">Total Jadwal</div>
                <div class="jd-kpi-sub">Seluruh slot terisi</div>
            </div>
            <div class="jd-kpi-watermark"><i class="fas fa-book-open"></i></div>
        </div>
        <div class="jd-kpi">
            <div class="jd-kpi-icon green"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="jd-kpi-num">{{ $jadwals->pluck('hari')->unique()->count() }}</div>
                <div class="jd-kpi-label">Hari Aktif</div>
                <div class="jd-kpi-sub">Dari {{ count($hariList) }} hari efektif</div>
            </div>
            <div class="jd-kpi-watermark"><i class="fas fa-calendar-day"></i></div>
        </div>
        <div class="jd-kpi">
            <div class="jd-kpi-icon amber"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="jd-kpi-num">{{ $jadwals->pluck('guru_id')->unique()->count() }}</div>
                <div class="jd-kpi-label">Guru Mengajar</div>
                <div class="jd-kpi-sub">Guru pengampu</div>
            </div>
            <div class="jd-kpi-watermark"><i class="fas fa-user-graduate"></i></div>
        </div>
        <div class="jd-kpi">
            <div class="jd-kpi-icon violet"><i class="fas fa-layer-group"></i></div>
            <div>
                <div class="jd-kpi-num">{{ $jadwals->pluck('mata_pelajaran_id')->unique()->count() }}</div>
                <div class="jd-kpi-label">Mata Pelajaran</div>
                <div class="jd-kpi-sub">Mapel terjadwal</div>
            </div>
            <div class="jd-kpi-watermark"><i class="fas fa-layer-group"></i></div>
        </div>
    </div>

    {{-- ===== JAM PER HARI ===== --}}
    <div class="jd-day-stats">
        @foreach($hariList as $day)
        <div class="jd-day-stat">
            <b>{{ $jadwals->where('hari', $day)->count() }}</b>
            <span>{{ $day }}</span>
        </div>
        @endforeach
    </div>

    {{-- ===== GRID JADWAL ===== --}}
    <div style="margin-top:20px;">
        <h3 class="jd-subtitle">
            <i class="fas fa-th-large"></i> Peta Jadwal
            <span class="jd-count">{{ $jadwals->count() }} slot terisi</span>
        </h3>
        <div class="jd-scheduler-wrap">
            <div class="jd-scheduler">
                <div class="jd-sched-row jd-sched-head">
                    <div class="jd-sched-hcell">Jam</div>
                    @foreach($hariList as $day)
                    <div class="jd-sched-hcell">{{ $day }}</div>
                    @endforeach
                </div>
                @foreach($jamList as $jamKe => $jamWaktu)
                    @if($loop->index === 2)
                    <div class="jd-sched-break"><i class="fas fa-mug-hot"></i> Istirahat</div>
                    @endif
                    <div class="jd-sched-row jd-sched-body-row">
                        <div class="jd-sched-time">
                            <b>Jam {{ $jamKe }}</b>
                            <span>{{ $jamWaktu['mulai'] }} - {{ $jamWaktu['selesai'] }}</span>
                        </div>
                        @foreach($hariList as $day)
                            @php
                                $jadwal = $jadwals->first(function ($j) use ($day, $jamKe) {
                                    return $j->hari === $day && (int) $j->jam_ke === (int) $jamKe;
                                });
                            @endphp
                            <div class="jd-sched-cell">
                                @if($jadwal)
                                    @php $mc = jd_mapel_color_idx($jadwal->mapel->nama_mapel ?? ''); @endphp
                                    <div class="jd-slot jd-mc-{{ $mc }}">
                                        <span class="jd-slot-top">
                                            <span class="jd-slot-name">{{ $jadwal->mapel->nama_mapel ?? '-' }}</span>
                                            <span class="jd-slot-dot"></span>
                                        </span>
                                        <span class="jd-slot-guru"><i class="fas fa-user-graduate"></i> {{ $jadwal->guru->nama ?? '-' }}</span>
                                        <span class="jd-slot-time"><i class="fas fa-clock"></i> {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                    </div>
                                @else
                                    <span class="jd-cell-empty">&mdash;</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== LEGEND ===== --}}
    @if(count($namaMapels))
    <div class="jd-legend" style="margin-top:16px;">
        @foreach($namaMapels as $nama => $mc)
        <span class="jd-legend-item"><span class="jd-mapel-dot jd-mc-{{ $mc }}" style="background:var(--mc);"></span> {{ $nama }}</span>
        @endforeach
    </div>
    @endif
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
