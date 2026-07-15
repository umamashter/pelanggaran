@extends('layouts.main')

@section('title', 'Detail Kelas')

@section('content')

<div class="container-fluid mt-4">

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                Detail Kelas
            </h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">Kelas</th>
                    <td>
                        {{ $kelas->tingkat }}{{ $kelas->nama_kelas }}
                    </td>
                </tr>

                <tr>
                    <th>Jenjang</th>
                    <td>
                        {{ $kelas->jenjang->kode }}
                    </td>
                </tr>

                <tr>
                    <th>Wali Kelas</th>
                    <td>
                        {{ $kelas->waliKelas->guru->nama ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Jumlah Siswa</th>
                    <td>
                        {{ $kelas->siswaAktif->count() }}
                    </td>
                </tr>

            </table>

        </div>

    </div>

    <div class="card shadow mt-4">

        <div class="card-header">
            <h5 class="mb-0">
                Daftar Siswa
            </h5>
        </div>

        <div class="card-body">

            <table class="table table-striped">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($kelas->siswaAktif as $anggota)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $anggota->student->nisn }}
                        </td>

                        <td>
                            {{ $anggota->student->nama }}
                        </td>

                        <td>
                            <span class="badge bg-success">
                                Aktif
                            </span>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada siswa
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection