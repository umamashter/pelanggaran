@extends('layouts.main')

@section('title','Grid Jadwal')

@section('content')

<div class="card shadow">

    <div class="card-header bg-primary text-white">
        <h4>Jadwal Pelajaran (Grid)</h4>
    </div>

    <div class="card-body">

        {{-- FILTER KELAS --}}
        <form method="GET" class="mb-3">

            <select name="kelas_id" class="form-control" onchange="this.form.submit()">

                <option value="">-- Pilih Kelas --</option>

                @foreach($kelas as $k)
                <option value="{{ $k->id }}"
                    {{ $selectedKelas == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
                @endforeach

            </select>

        </form>

        @if($selectedKelas)

        <div class="table-responsive">

            <table class="table table-bordered text-center">

                <thead class="table-dark">

                    <tr>
                        <th>Jam / Hari</th>

                        @foreach($hariList as $hari)
                        <th>{{ $hari }}</th>
                        @endforeach

                    </tr>

                </thead>

                <tbody>

                    @foreach($jamList as $jam)

                    <tr>

                        <td>
                            {{ substr($jam->jam_mulai,0,5) }} - {{ substr($jam->jam_selesai,0,5) }}
                        </td>

                        @foreach($hariList as $hari)

                        @php
                        $data = $jadwals->firstWhere(function($j) use ($hari, $jam) {
                        return $j->hari == $hari &&
                        $j->jam_mulai == $jam->jam_mulai;
                        });
                        @endphp

                        <td>

                            @if($data)
                            <strong>{{ $data->mapel->nama_mapel }}</strong><br>
                            <small>{{ $data->guru->nama }}</small>
                            @else
                            -
                            @endif

                        </td>

                        @endforeach

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @endif

    </div>

</div>

@endsection