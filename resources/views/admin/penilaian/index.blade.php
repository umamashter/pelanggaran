@extends('layouts.main')

@section('title','Penilaian Siswa')

@section('content')

<div class="card shadow">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            Penilaian Siswa
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
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @foreach($jadwals as $jadwal)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $jadwal->kelas->nama_kelas }}
                    </td>

                    <td>
                        {{ $jadwal->mapel->nama_mapel }}
                    </td>

                    <td>
                        {{ $jadwal->guru->nama }}
                    </td>

                    <td>

                        <a
                            href="{{ route('penilaian.show',$jadwal->id) }}"
                            class="btn btn-primary btn-sm">

                            Input Nilai

                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection