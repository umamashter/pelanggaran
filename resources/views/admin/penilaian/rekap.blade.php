@extends('layouts.main')

@section('title','Rekap Nilai')

@section('content')

<div class="card shadow">

    <div class="card-header bg-success text-white">

        <h4>
            Rekap Nilai
            {{ $penilaian->jadwal->mapel->nama_mapel }}
            -
            {{ $penilaian->jadwal->kelas->nama_kelas }}
        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tugas</th>
                    <th>UH</th>
                    <th>PTS</th>
                    <th>PAS</th>
                    <th>Nilai Akhir</th>
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

                    <td>
                        {{ number_format($detail->nilai_akhir,2) }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection