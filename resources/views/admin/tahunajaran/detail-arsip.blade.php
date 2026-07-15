@extends('layouts.main')

@section('title','Detail Arsip')

@section('content')

<div class="card shadow">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">
            Arsip Tahun Ajaran {{ $tahunAjaran->tahun_ajaran }}
        </h4>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <div class="alert alert-primary">
                    Jumlah Siswa : <strong>{{ $jumlahSiswa }}</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="alert alert-warning">
                    Histori Pelanggaran : <strong>{{ $jumlahHistori }}</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="alert alert-success">
                    Alumni : <strong>{{ $jumlahAlumni }}</strong>
                </div>
            </div>

        </div>

        <hr>

        <h5>Daftar Siswa Tahun Ajaran Ini</h5>

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($siswas as $i => $siswa)

                <tr>

                    <td>{{ $i + 1 }}</td>

                    <td>{{ $siswa->nisn }}</td>

                    <td>{{ $siswa->nama }}</td>

                    <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>

                    <td>
                        @if($siswa->status == 'alumni')
                        <span class="badge bg-success">Alumni</span>
                        @else
                        <span class="badge bg-primary">Aktif</span>
                        @endif
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection