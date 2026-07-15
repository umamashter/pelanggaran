@extends('layouts.main')

@section('title','Detail Nilai')

@section('content')

<div class="card shadow">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">

            Detail Nilai
            {{ $penilaian->jadwal->mapel->nama_mapel }}
            -
            {{ $penilaian->jadwal->kelas->nama_kelas }}

        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <a href="{{ route('penilaian.edit',$penilaian->id) }}"
                class="btn btn-warning">

                Edit Nilai

            </a>
            <a href="{{ route('penilaian.rekap',$penilaian->id) }}"
                class="btn btn-info">

                Rekap Nilai

            </a>

            <a href="{{ route('penilaian.pdf',$penilaian->id) }}"
                class="btn btn-danger">

                Export PDF

            </a>
            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Siswa</th>
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

                    <td>
                        {{ $detail->student->nama }}
                    </td>

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