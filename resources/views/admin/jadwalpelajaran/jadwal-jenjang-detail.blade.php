@extends('layouts.main')

@section('title', 'Jadwal Jenjang ' . $jenjang)

@section('content')
@include('component.admin.jadwal-module')
<style>
    .page-title-content { display: none !important; }

    .jd-matrix-wrap { overflow-x: auto; border: 1px solid var(--jd-border); border-radius: var(--jd-radius); background: var(--jd-card); box-shadow: var(--jd-shadow); }
    .jd-matrix { width: 100%; min-width: 860px; border-collapse: separate; border-spacing: 0; font-size: 12.5px; }
    .jd-matrix thead th { position: sticky; top: 0; background: var(--jd-primary-soft); color: var(--jd-text);
        font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; padding: 12px 12px;
        text-align: center; border-bottom: 1px solid var(--jd-border); border-right: 1px solid var(--jd-border); }
    .jd-matrix thead th:last-child { border-right: none; }
    .jd-matrix thead th.jd-th-hari { text-align: left; color: var(--jd-text-3); }
    .jd-matrix tbody td { padding: 8px 9px; text-align: center; vertical-align: middle; border-bottom: 1px solid var(--jd-border); border-right: 1px solid var(--jd-border); }
    .jd-matrix tbody tr:last-child td { border-bottom: none; }
    .jd-matrix tbody td:last-child { border-right: none; }
    .jd-matrix .jd-m-day { background: var(--jd-bg); font-weight: 700; color: var(--jd-text); font-size: 12.5px; }
    .jd-matrix .jd-m-jam { background: var(--jd-bg); color: var(--jd-text-3); font-size: 10.5px; font-weight: 600; }
    .jd-matrix .jd-m-jam b { display: block; font-size: 12px; color: var(--jd-text); }
    .jd-matrix .jd-m-break { background: var(--jd-amber-soft) !important; color: var(--jd-amber); font-weight: 700; font-size: 11.5px; letter-spacing: .4px; }
    .jd-matrix tbody tr.jd-m-break-row td { background: var(--jd-amber-soft) !important; color: var(--jd-amber); }

    .jd-slot-mini { text-align: left; border-left: 3px solid var(--mc); background: var(--mc-soft); border-radius: 10px; padding: 8px 10px; min-height: 46px; }
    .jd-slot-mini b { display: block; font-size: 11.5px; font-weight: 700; color: var(--jd-text); line-height: 1.25; }
    .jd-slot-mini span { display: flex; align-items: center; gap: 4px; font-size: 10px; color: var(--jd-text-2); margin-top: 3px; }
    .jd-slot-mini span i { font-size: 10px; color: var(--jd-text-3); }
    .jd-slot-mini.jd-m-empty { background: transparent; border: 1px dashed var(--jd-border); min-height: 34px; }

    .jd-subtitle { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 700; color: var(--jd-text);
        margin: 0 0 14px; }
    .jd-subtitle i { color: var(--jd-primary); }
    .jd-subtitle .jd-count { margin-left: auto; font-size: 11.5px; color: var(--jd-text-3); font-weight: 600; }
</style>

@php
    $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Sabtu', 'Ahad'];
    $jenjangNama = optional(optional($kelas->first())->jenjang)->nama_jenjang ?? $jenjang;
    $mapelSet = [];
    foreach ($jadwals as $jw) {
        $mapelSet[$jw->mapel->nama_mapel ?? '-'] = jd_mapel_color_idx($jw->mapel->nama_mapel ?? '-');
    }
@endphp

<div class="jd-mod jd-page-jenjangdetail">

    <a href="{{ route('jadwal-jenjang') }}" class="jd-back mb-3"><i class="fas fa-arrow-left"></i> Jadwal Per Jenjang</a>

    {{-- ===== HERO ===== --}}
    <div class="jd-detail-hero">
        <div class="jd-detail-hero-grid">
            <div class="jd-hero-left">
                <div class="jd-hero-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h2 class="jd-hero-title">{{ $jenjangNama }}</h2>
                    <p class="jd-hero-sub">Matriks jadwal pelajaran seluruh kelas jenjang ini.</p>
                    <div class="jd-hero-badges">
                        <span class="jd-hero-badge"><i class="fas fa-school"></i> {{ $kelas->count() }} Kelas</span>
                        <span class="jd-hero-badge jd-hero-badge--ok"><i class="fas fa-book"></i> {{ $jadwals->count() }} Jadwal</span>
                        <span class="jd-hero-badge"><i class="fas fa-calendar-day"></i> {{ $jadwals->pluck('hari')->unique()->count() }} Hari Aktif</span>
                    </div>
                </div>
            </div>
            <div class="jd-hero-right">
                <a href="{{ route('jadwal-jenjang.pdf', $jenjang) }}" target="_blank" class="jd-btn jd-btn--light"><i class="fas fa-file-pdf"></i> Cetak PDF</a>
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
            <div class="jd-empty-sub">Belum ada jadwal pelajaran untuk jenjang ini. Silakan atur jadwal melalui menu Jadwal Pelajaran.</div>
            <a href="{{ route('jadwal-pelajaran.index') }}" class="jd-btn jd-btn--soft"><i class="fas fa-plus"></i> Atur Jadwal</a>
        </div>
    </div>
    @else
    {{-- ===== MATRIX ===== --}}
    <h3 class="jd-subtitle">
        <i class="fas fa-table"></i> Matriks Jadwal
        <span class="jd-count">{{ $jadwals->count() }} slot terisi</span>
    </h3>
    <div class="jd-matrix-wrap">
        <table class="jd-matrix">
            <thead>
                <tr>
                    <th class="jd-th-hari">Hari</th>
                    <th>Jam</th>
                    <th>Waktu</th>
                    @foreach($kelas as $k)
                    <th>{{ $k->nama_kelas }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($hariList as $hari)
                    @php
                        $jadwalHari = $jadwals->where('hari', $hari)->sortBy('jam_mulai');
                        $jamList = $jadwalHari->pluck('jam_mulai')->unique()->values();
                        $rowspan = $jamList->count();
                        if ($jamList->count() >= 3) { $rowspan++; }
                    @endphp
                    @foreach($jamList as $index => $jam)
                        @php
                            $jadwalPertama = $jadwals->where('hari', $hari)->where('jam_mulai', $jam)->first();
                        @endphp
                        <tr>
                            @if($index == 0)
                            <td rowspan="{{ $rowspan }}" class="jd-m-day">{{ $hari }}</td>
                            @endif
                            <td class="jd-m-jam"><b>{{ $loop->iteration }}</b></td>
                            <td class="jd-m-jam">
                                @if($jadwalPertama)
                                {{ substr($jadwalPertama->jam_mulai, 0, 5) }} - {{ substr($jadwalPertama->jam_selesai, 0, 5) }}
                                @endif
                            </td>
                            @foreach($kelas as $k)
                                @php
                                    $jadwal = $jadwals->where('hari', $hari)->where('kelas_id', $k->id)->where('jam_mulai', $jam)->first();
                                @endphp
                                <td>
                                    @if($jadwal)
                                        @php $mc = jd_mapel_color_idx($jadwal->mapel->nama_mapel ?? ''); @endphp
                                        <div class="jd-slot-mini jd-mc-{{ $mc }}">
                                            <b>{{ $jadwal->mapel->nama_mapel ?? '-' }}</b>
                                            <span><i class="fas fa-user-graduate"></i> {{ $jadwal->guru->nama ?? '-' }}</span>
                                        </div>
                                    @else
                                        <div class="jd-slot-mini jd-m-empty"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @if($loop->iteration == 3)
                        <tr class="jd-m-break-row">
                            <td class="jd-m-break">-</td>
                            <td class="jd-m-break">09:30 - 10:00</td>
                            <td class="jd-m-break" colspan="{{ $kelas->count() }}"><i class="fas fa-mug-hot me-1"></i> ISTIRAHAT</td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== LEGEND ===== --}}
    @if(count($mapelSet))
    <div class="jd-legend" style="margin-top:16px;">
        @foreach($mapelSet as $nama => $mc)
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
