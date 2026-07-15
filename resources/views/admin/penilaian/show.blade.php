@extends('layouts.main')

@section('content')

<div class="card shadow">
    @if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

    @endif

    @if(session('error'))

    <div class="alert alert-danger">

        {{ session('error') }}

    </div>

    @endif
    <div class="card-header bg-success text-white">

        <h4>

            Nilai
            {{ $jadwal->mapel->nama_mapel }}

            -

            {{ $jadwal->kelas->nama_kelas }}

        </h4>

    </div>

    <div class="card-body">

        <form
            action="{{ route('penilaian.store') }}"
            method="POST">

            @csrf

            <input
                type="hidden"
                name="jadwal_pelajaran_id"
                value="{{ $jadwal->id }}">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>Nama</th>
                        <th>Tugas</th>
                        <th>UH</th>
                        <th>PTS</th>
                        <th>PAS</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($students as $siswa)

                    <tr>

                        <td>

                            {{ $siswa->nama }}

                        </td>

                        <td>

                            <input
                                type="number"
                                name="tugas[{{ $siswa->id }}]"
                                class="form-control">

                        </td>

                        <td>

                            <input
                                type="number"
                                name="uh[{{ $siswa->id }}]"
                                class="form-control">

                        </td>

                        <td>

                            <input
                                type="number"
                                name="pts[{{ $siswa->id }}]"
                                class="form-control">

                        </td>

                        <td>

                            <input
                                type="number"
                                name="pas[{{ $siswa->id }}]"
                                class="form-control">

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <button
                class="btn btn-success">

                Simpan Nilai

            </button>

        </form>

    </div>

</div>

@endsection