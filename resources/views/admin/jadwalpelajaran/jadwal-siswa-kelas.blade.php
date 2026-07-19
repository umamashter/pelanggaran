@extends('layouts.main')

@section('title', 'Jadwal Kelas ' . $kelas->nama_kelas)

@section('content')

<style>
    .hero-kelas {
        background: linear-gradient(135deg, #2e7d32 0%, #66bb6a 100%);
        border-radius: 16px;
        padding: 24px 30px;
        position: relative;
        overflow: hidden;
    }

    .hero-kelas::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }

    .stat-card-kelas {
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 14px 18px;
        position: relative;
        z-index: 1;
        min-width: 110px;
    }

    .stat-card-kelas .num {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        line-height: 1.1;
    }

    .stat-card-kelas .lbl {
        font-size: 11px;
        color: rgba(255,255,255,.7);
    }

    .hari-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        transition: .2s;
    }

    .hari-card .card-header {
        border-bottom: none;
        padding: 12px 20px;
    }

    .table thead th {
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 2px solid #2e7d32;
        color: #2e7d32;
        background: #f1f8e9;
    }

    .table tbody tr:hover {
        background: #f9fbe7;
    }

    .jam-badge {
        background: #e8f5e9;
        border-radius: 8px;
        padding: 5px 12px;
        font-weight: 600;
        font-size: 13px;
        display: inline-block;
        color: #2e7d32;
    }

    .mapel-name {
        font-weight: 600;
        color: #1b5e20;
    }

    .guru-name {
        color: #555;
        font-size: 13px;
    }

    .empty-day {
        padding: 30px 20px;
        color: #999;
    }

    .btn-back {
        background: rgba(255,255,255,.2);
        border: 1.5px solid rgba(255,255,255,.35);
        color: #fff;
        border-radius: 10px;
        padding: 7px 18px;
        font-weight: 600;
        font-size: 13px;
        transition: all .25s;
        position: relative;
        z-index: 1;
    }

    .btn-back:hover {
        background: rgba(255,255,255,.3);
        color: #fff;
    }
</style>

{{-- Hero Header --}}
<div class="hero-kelas mb-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div style="position:relative;z-index:1;">
            <h4 class="text-white fw-bold mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Jadwal Pelajaran
            </h4>
            <div class="text-white-50" style="font-size:14px;">
                Kelas {{ $kelas->nama_kelas }}
                @if($kelas->jenjang)
                &middot; {{ $kelas->jenjang->nama_jenjang }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap" style="position:relative;z-index:1;">
            <div class="stat-card-kelas text-center">
                <div class="num">{{ $jadwals->count() }}</div>
                <div class="lbl">Total Jadwal</div>
            </div>
            <a href="{{ route('jadwal-siswa') }}" class="btn-back d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </div>

</div>

{{-- Jadwal Per Hari --}}
@php
$hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Sabtu', 'Ahad'];
@endphp

@foreach($hariList as $hari)

@php
$jadwalHari = $jadwals->where('hari', $hari);
$warnaHari = ['#e8f5e9','#e3f2fd','#fff3e0','#f3e5f5','#fce4ec','#e0f7fa'];
$iconHari = ['fa-sun','fa-clock','fa-cloud','fa-star','fa-moon','fa-calendar'];
$idxHari = $loop->index % 6;
@endphp

<div class="card shadow-sm mb-4 hari-card">

    <div class="card-header" style="background:{{ $warnaHari[$idxHari] }};">
        <h6 class="mb-0 fw-bold" style="color:#333;">
            <i class="fas {{ $iconHari[$idxHari] }} me-2" style="color:#2e7d32;"></i>
            {{ $hari }}
            <span class="badge bg-success ms-2" style="font-size:11px;font-weight:500;">
                {{ $jadwalHari->count() }} Mapel
            </span>
        </h6>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="18%">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($jadwalHari as $jadwal)

                    <tr>
                        <td class="text-center fw-bold" style="color:#999;">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            <span class="jam-badge">
                                {{ substr($jadwal->jam_mulai,0,5) }}
                                -
                                {{ substr($jadwal->jam_selesai,0,5) }}
                            </span>
                        </td>
                        <td class="mapel-name">{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                        <td class="guru-name">{{ $jadwal->guru->nama ?? '-' }}</td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center empty-day">
                            <i class="fas fa-calendar-times fa-lg text-secondary mb-1 d-block"></i>
                            <span class="text-muted">Belum ada jadwal untuk hari {{ $hari }}</span>
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endforeach

@endsection
