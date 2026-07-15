@extends('layouts.main')

@section('title','Hasil Nilai')

@section('content')

<div class="card shadow">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            Hasil Penilaian
        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Jumlah Siswa</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @foreach($penilaians as $penilaian)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $penilaian->jadwal->kelas->nama_kelas }}
                    </td>

                    <td>
                        {{ $penilaian->jadwal->mapel->nama_mapel }}
                    </td>

                    <td>
                        {{ $penilaian->jadwal->guru->nama }}
                    </td>

                    <td>
                        {{ $penilaian->details->count() }}
                    </td>

                    <td>

                        <a href="{{ route('penilaian.detail',$penilaian->id) }}"
                            class="btn btn-info btn-sm">

                            Lihat

                        </a>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection