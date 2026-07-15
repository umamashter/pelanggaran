@extends('layouts.main')

@section('title','Jadwal Kelas')

@section('content')

<style>
    .bg-jadwal {
        background: #32C718;
        border-bottom: 3px solid #32C718;
    }

    .card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .hari-card {
        border-left: 5px solid #32C718;
    }

    .table thead th {
        background: #32C718;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #fffef0;
    }

    .btn {
        border-radius: 10px;
        transition: .3s;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .jam-badge {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 600;
        display: inline-block;
    }

    .info-card {
        background: #fff;
        border-radius: 14px;
        text-align: center;
        padding: 20px;
    }

    .info-card h2 {
        font-weight: 700;
        margin-bottom: 0;
    }

    .info-card h6 {
        color: #6c757d;
        margin-bottom: 10px;
    }
</style>

<div class="card shadow-sm">

    <div class="card-header bg-jadwal">

        <div class="d-flex justify-content-between align-items-center">

            <h4 class="mb-0 fw-bold text-white">
                <i class="fas fa-calendar-alt me-2"></i>
                Jadwal Pelajaran Kelas {{ $kelas->nama_kelas }}
            </h4>

            <a href="{{ route('jadwal-pelajaran.cetak', $kelas->id) }}"
                target="_blank"
                class="btn btn-success btn-sm">

                <i class="fas fa-print me-1"></i>
                Cetak Jadwal

            </a>

        </div>

    </div>

    <div class="card-body">

        {{-- Statistik --}}
        <div class="row mb-4">

            <div class="col-md-4 mb-3">

                <div class="info-card shadow-sm">

                    <h6>Total Jadwal</h6>

                    <h2>
                        {{ $jadwals->count() }}
                    </h2>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="info-card shadow-sm">

                    <h6>Kelas</h6>

                    <h5 class="fw-bold">
                        {{ $kelas->nama_kelas }}
                    </h5>

                </div>

            </div>

        </div>

        @php
        $hariList = [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Sabtu',
        'Minggu'
        ];
        @endphp

        @foreach($hariList as $hari)

        @php
        $jadwalHari = $jadwals->where('hari', $hari);
        @endphp

        <div class="card shadow-sm mb-4 hari-card">

            <div class="card-header bg-light">

                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-calendar-day text-warning me-2"></i>
                    {{ $hari }}
                </h6>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($jadwalHari as $jadwal)

                            <tr>

                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="text-center">

                                    <span class="jam-badge">

                                        {{ substr($jadwal->jam_mulai,0,5) }}
                                        -
                                        {{ substr($jadwal->jam_selesai,0,5) }}

                                    </span>

                                </td>

                                <td>
                                    {{ $jadwal->mapel->nama_mapel ?? '-' }}
                                </td>

                                <td>
                                    {{ $jadwal->guru->nama ?? '-' }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="4" class="text-center py-4">

                                    <i class="fas fa-calendar-times fa-2x text-secondary mb-2"></i>

                                    <div class="text-muted">
                                        Belum ada jadwal untuk hari {{ $hari }}
                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection