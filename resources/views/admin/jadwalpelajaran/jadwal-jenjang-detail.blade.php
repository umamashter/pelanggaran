@extends('layouts.main')

@section('title','Jadwal Jenjang')

@section('content')

<style>
    .table-warning td {
        background: #fff3cd !important;
        font-weight: 700;
    }

    .table td {
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fff5;
    }

    .bg-jadwal {
        background: rgb(86, 179, 67);
    }

    .table thead th {
        background: rgb(86, 179, 67);
        color: white;
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="card shadow">

    <div class="card-header bg-jadwal">

        <div class="d-flex justify-content-between">

            <h4 class="text-white mb-0">
                Jadwal Pelajaran Jenjang {{ $jenjang }}
            </h4>

            <a href="{{ route('jadwal-jenjang') }}"
                class="btn btn-light btn-sm">

                Kembali

            </a>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th width="10%">Hari</th>
                        <th width="5%">Jam</th>
                        <th width="15%">Waktu</th>

                        @foreach($kelas as $k)

                        <th>
                            {{ $k->nama_kelas }}
                        </th>

                        @endforeach

                    </tr>

                </thead>

                <tbody>

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

                    $jadwalHari = $jadwals
                    ->where('hari', $hari)
                    ->sortBy('jam_mulai');

                    $jamList = $jadwalHari
                    ->pluck('jam_mulai')
                    ->unique()
                    ->values();

                    $rowspan = $jamList->count();

                    if($jamList->count() >= 3){
                    $rowspan++;
                    }

                    @endphp

                    @foreach($jamList as $index => $jam)

                    <tr>

                        @if($index == 0)

                        <td rowspan="{{ $rowspan }}"
                            class="text-center fw-bold align-middle bg-light">

                            {{ $hari }}

                        </td>

                        @endif

                        <td class="text-center fw-bold">

                            {{ $loop->iteration }}

                        </td>

                        <td class="text-center">

                            @php

                            $jadwalPertama = $jadwals
                            ->where('hari', $hari)
                            ->where('jam_mulai', $jam)
                            ->first();

                            @endphp

                            @if($jadwalPertama)

                            <span class="fw-semibold">

                                {{ substr($jadwalPertama->jam_mulai,0,5) }}
                                -
                                {{ substr($jadwalPertama->jam_selesai,0,5) }}

                            </span>

                            @endif

                        </td>

                        @foreach($kelas as $k)

                        @php

                        $jadwal = $jadwals
                        ->where('hari', $hari)
                        ->where('kelas_id', $k->id)
                        ->where('jam_mulai', $jam)
                        ->first();

                        @endphp

                        <td>

                            @if($jadwal)

                            <div class="fw-bold text-success">

                                {{ $jadwal->mapel->nama_mapel ?? '-' }}

                            </div>

                            <small class="text-muted">

                                {{ $jadwal->guru->nama ?? '-' }}

                            </small>

                            @endif

                        </td>

                        @endforeach

                    </tr>

                    {{-- BARIS ISTIRAHAT --}}
                    @if($loop->iteration == 3)

                    <tr class="table-warning">

                        <td class="text-center fw-bold">
                            -
                        </td>

                        <td class="text-center fw-bold text-danger">
                            09:30 - 10:00
                        </td>

                        <td colspan="{{ $kelas->count() }}"
                            class="text-center fw-bold text-danger">

                            ☕ ISTIRAHAT

                        </td>

                    </tr>

                    @endif

                    @endforeach

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection