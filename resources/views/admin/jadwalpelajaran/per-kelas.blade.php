@extends('layouts.main')

@section('title', 'Jadwal Kelas ' . $kelas->nama_kelas)

@section('content')

@push('css')
<style>
    .jadwal-header {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        border-radius: 16px;
    }
    .jadwal-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        font-size: 13px;
    }
    .jadwal-table thead th {
        background: #16a34a;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        padding: 12px 8px;
        font-weight: 600;
        border: none;
        font-size: 13px;
    }
    .jadwal-table thead th:first-child {
        border-radius: 12px 0 0 0;
    }
    .jadwal-table thead th:last-child {
        border-radius: 0 12px 0 0;
    }
    .jadwal-table tbody td {
        padding: 10px 8px;
        vertical-align: middle;
        border: 1px solid #e5e7eb;
        height: 70px;
    }
    .jadwal-table tbody tr:last-child td:first-child {
        border-radius: 0 0 0 12px;
    }
    .jadwal-table tbody tr:last-child td:last-child {
        border-radius: 0 0 12px 0;
    }
    .jam-cell {
        background: #f0fdf4;
        text-align: center;
        font-weight: 700;
        color: #166534;
        white-space: nowrap;
    }
    .jam-cell .jam-label {
        font-size: 14px;
        display: block;
    }
    .jam-cell .jam-waktu {
        font-size: 11px;
        color: #6b7280;
        font-weight: 400;
    }
    .mapel-cell {
        text-align: center;
        background: #fff;
        transition: all 0.2s;
    }
    .mapel-cell:hover {
        background: #f0fdf4;
    }
    .mapel-cell .nama-mapel {
        font-weight: 700;
        color: #166534;
        font-size: 13px;
        display: block;
        margin-bottom: 2px;
    }
    .mapel-cell .nama-guru {
        font-size: 11px;
        color: #6b7280;
    }
    .mapel-cell .jam-detail {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 2px;
    }
    .kosong {
        text-align: center;
        color: #d1d5db;
        font-size: 18px;
    }
    .info-badge {
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 500;
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .stat-card .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: #16a34a;
    }
    .stat-card .stat-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #6b7280;
        margin-right: 16px;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
</style>
@endpush

{{-- Header --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-4 jadwal-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;background:rgba(255,255,255,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-calendar-week" style="font-size:24px;"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="font-size:18px;">
                        Jadwal Pelajaran
                    </h4>
                    <span style="font-size:13px;opacity:0.85;">
                        {{ $kelas->nama_kelas }}{{ $kelas->jenjang ? ' — ' . $kelas->jenjang->nama_jenjang : '' }}
                    </span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="info-badge">
                    <i class="fas fa-book me-1"></i> {{ $jadwals->count() }} Jadwal
                </span>
                <a href="{{ route('jadwal-pelajaran.cetak', $kelas->id) }}" target="_blank"
                    class="btn btn-sm" style="background:rgba(255,255,255,0.9);color:#16a34a;border-radius:8px;font-weight:600;">
                    <i class="fas fa-print me-1"></i> Cetak
                </a>
                <a href="{{ route('jadwal-pelajaran.daftar-kelas') }}"
                    class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;border-radius:8px;border:1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Statistik --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $jadwals->count() }}</div>
            <div class="stat-label">Total Jadwal</div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $jadwals->pluck('hari')->unique()->count() }}</div>
            <div class="stat-label">Hari Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $jadwals->pluck('guru_id')->unique()->count() }}</div>
            <div class="stat-label">Guru Mengajar</div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $jadwals->pluck('mata_pelajaran_id')->unique()->count() }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
</div>

{{-- Tabel Jadwal --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="jadwal-table">
                <thead>
                    <tr>
                        <th style="width:100px;">Jam</th>
                        @foreach($hariList as $hari)
                        <th>{{ $hari }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($jamList as $jamKe => $jamWaktu)
                    <tr>
                        <td class="jam-cell">
                            <span class="jam-label">Jam {{ $jamKe }}</span>
                            <span class="jam-waktu">{{ $jamWaktu['mulai'] }} - {{ $jamWaktu['selesai'] }}</span>
                        </td>
                        @foreach($hariList as $hari)
                            @php
                                $jadwal = $jadwals->first(function($j) use ($hari, $jamKe) {
                                    return $j->hari === $hari && $j->jam_ke == $jamKe;
                                });
                            @endphp
                            <td class="mapel-cell">
                                @if($jadwal)
                                    <span class="nama-mapel">{{ $jadwal->mapel->nama_mapel ?? '-' }}</span>
                                    <span class="nama-guru">{{ $jadwal->guru->nama ?? '-' }}</span>
                                    <div class="jam-detail">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                @else
                                    <span class="kosong">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Keterangan --}}
<div class="mt-3" style="font-size:12px;">
    <span class="legend-item">
        <span class="legend-dot" style="background:#16a34a;"></span> Mata Pelajaran & Guru
    </span>
    <span class="legend-item">
        <span class="legend-dot" style="background:#d1d5db;"></span> Kosong / Istirahat
    </span>
</div>

@endsection
