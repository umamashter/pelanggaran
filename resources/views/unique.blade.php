@extends('layouts.auth')
@section('title', 'Dashboard')
@section('auths')
    @guest
    <div class="row mt-3">
        <div class="col-lg-6 offset-lg-3">
            <div class="card shadow-lg-3">
                <div class="card-header bg-primary text-light h5 p-3">
                    <i class="fas fa-tasks"></i>
                    Papan Peringkat
                </div>
                <div class="card-body py-4 text-dark">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr>
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>{{ $siswa->poin }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            overflow-x: hidden;
        }
    </style>
    @endguest
@endsection
