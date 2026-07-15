@extends('layouts.main')

@section('title','Riwayat Penilaian')

@section('content')

<div class="card shadow">

    <div class="card-header bg-dark text-white">

        <h4 class="mb-0">
            Riwayat Penilaian
            {{ $penilaian->jadwal->mapel->nama_mapel }}
            - {{ $penilaian->jadwal->kelas->nama_kelas }}
        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Tugas</th>
                    <th>UH</th>
                    <th>PTS</th>
                    <th>PAS</th>
                </tr>

            </thead>

            <tbody>

                @foreach($penilaian->details as $detail)

                <tr>

                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->student->nama }}</td>
                    <td>{{ $detail->tugas }}</td>
                    <td>{{ $detail->uh }}</td>
                    <td>{{ $detail->pts }}</td>
                    <td>{{ $detail->pas }}</td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection