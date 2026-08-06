@extends('layouts.main')

@section('title', 'Grid Jadwal')

@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-cell-empty { display: flex; align-items: center; justify-content: center; height: 100%; min-height: 76px;
        color: var(--jd-text-3); opacity: .45; font-size: 14px; font-weight: 700; letter-spacing: .3px; }

    .jd-grid-filter { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 14px; margin-bottom: 20px; }
    .jd-grid-filter .jd-filter { min-width: 240px; margin: 0; }

    .jd-subtitle { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 700; color: var(--jd-text);
        margin: 0 0 12px; }
    .jd-subtitle i { color: var(--jd-primary); }
    .jd-subtitle .jd-count { margin-left: auto; font-size: 11.5px; color: var(--jd-text-3); font-weight: 600; }
</style>

<div class="jd-mod jd-page-grid">

    <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-back mb-3"><i class="fas fa-arrow-left"></i> Dashboard Jadwal</a>

    {{-- ===== HERO ===== --}}
    <div class="jd-detail-hero">
        <div class="jd-detail-hero-grid">
            <div class="jd-hero-left">
                <div class="jd-hero-icon"><i class="fas fa-th-large"></i></div>
                <div>
                    <h2 class="jd-hero-title">Grid Jadwal Pelajaran</h2>
                    <p class="jd-hero-sub">Matriks jadwal per kelas &mdash; pilih kelas untuk melihat peta jadwal mingguan.</p>
                    <div class="jd-hero-badges">
                        <span class="jd-hero-badge"><i class="fas fa-school"></i> {{ $kelas->count() }} Kelas</span>
                        <span class="jd-hero-badge jd-hero-badge--ok"><i class="fas fa-calendar-day"></i> {{ count($hariList) }} Hari Efektif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FILTER KELAS ===== --}}
    <form method="GET" action="{{ route('jadwal.grid') }}" class="jd-grid-filter" autocomplete="off">
        <label class="jd-filter">
            <span style="font-size:12px;font-weight:700;color:var(--jd-text-2);margin-bottom:6px;"><i class="fas fa-school me-1" style="color:var(--jd-primary);"></i> Pilih Kelas</span>
            <select name="kelas_id" class="jd-select" id="kelasSelect" onchange="this.form.submit()">
                <option value="">&mdash; Pilih Kelas &mdash;</option>
                @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit" class="jd-btn jd-btn--soft"><i class="fas fa-filter"></i> Tampilkan</button>
        @if($selectedKelas)
        <a href="{{ route('jadwal.grid') }}" class="jd-btn jd-btn--ghost"><i class="fas fa-rotate-left"></i> Reset</a>
        @endif
    </form>

    @if($selectedKelas)
        @php
            $kelasTerpilih = $kelas->first(function ($k) use ($selectedKelas) {
                return $k->id == $selectedKelas;
            });
            $namaMapels = [];
            foreach ($jadwals as $jw) {
                $namaMapels[$jw->mapel->nama_mapel ?? '-'] = jd_mapel_color_idx($jw->mapel->nama_mapel ?? '-');
            }
        @endphp

        @if($jadwals->isEmpty())
        {{-- ===== EMPTY ===== --}}
        <div class="jd-card">
            <div class="jd-empty">
                <div class="jd-empty-illus">
                    <div class="ring"></div>
                    <div class="core"><i class="fas fa-calendar-times"></i></div>
                </div>
                <div class="jd-empty-title">Belum Ada Jadwal</div>
                <div class="jd-empty-sub">Kelas <b>{{ $kelasTerpilih->nama_kelas ?? '-' }}</b> belum memiliki jadwal pelajaran.</div>
                <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-btn jd-btn--soft"><i class="fas fa-plus"></i> Atur Jadwal</a>
            </div>
        </div>
        @else
        {{-- ===== GRID ===== --}}
        <h3 class="jd-subtitle">
            <i class="fas fa-th-large"></i> {{ $kelasTerpilih->nama_kelas ?? 'Kelas' }}
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
                @foreach($jamList as $jam)
                    @if($loop->index === 2)
                    <div class="jd-sched-break"><i class="fas fa-mug-hot"></i> Istirahat</div>
                    @endif
                    <div class="jd-sched-row jd-sched-body-row">
                        <div class="jd-sched-time">
                            <b>Jam {{ $loop->iteration }}</b>
                            <span>{{ substr($jam->jam_mulai, 0, 5) }} - {{ substr($jam->jam_selesai, 0, 5) }}</span>
                        </div>
                        @foreach($hariList as $day)
                            @php
                                $data = $jadwals->first(function ($j) use ($day, $jam) {
                                    return $j->hari == $day && $j->jam_mulai == $jam->jam_mulai;
                                });
                            @endphp
                            <div class="jd-sched-cell">
                                @if($data)
                                    @php $mc = jd_mapel_color_idx($data->mapel->nama_mapel ?? ''); @endphp
                                    <div class="jd-slot jd-mc-{{ $mc }}">
                                        <span class="jd-slot-top">
                                            <span class="jd-slot-name">{{ $data->mapel->nama_mapel ?? '-' }}</span>
                                            <span class="jd-slot-dot"></span>
                                        </span>
                                        <span class="jd-slot-guru"><i class="fas fa-user-graduate"></i> {{ $data->guru->nama ?? '-' }}</span>
                                        <span class="jd-slot-time"><i class="fas fa-clock"></i> {{ substr($data->jam_mulai, 0, 5) }} - {{ substr($data->jam_selesai, 0, 5) }}</span>
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

        {{-- ===== LEGEND ===== --}}
        @if(count($namaMapels))
        <div class="jd-legend" style="margin-top:16px;">
            @foreach($namaMapels as $nama => $mc)
            <span class="jd-legend-item"><span class="jd-mapel-dot jd-mc-{{ $mc }}" style="background:var(--mc);"></span> {{ $nama }}</span>
            @endforeach
        </div>
        @endif
        @endif
    @else
    {{-- ===== PILIH KELAS ===== --}}
    <div class="jd-card">
        <div class="jd-empty">
            <div class="jd-empty-illus">
                <div class="ring"></div>
                <div class="core"><i class="fas fa-school"></i></div>
            </div>
            <div class="jd-empty-title">Pilih Kelas</div>
            <div class="jd-empty-sub">Pilih salah satu kelas pada menu di atas untuk menampilkan grid jadwal pelajaran.</div>
        </div>
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
