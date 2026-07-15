@extends('layouts.main')

@section('title','Arsip Tahun Ajaran')

@section('content')

<div class="card shadow">

    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Arsip Tahun Ajaran</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($arsips as $i => $arsip)

                <tr>

                    <td>{{ $i+1 }}</td>

                    <td>{{ $arsip->tahun_ajaran }}</td>

                    <td>
                        <span class="badge bg-secondary">Arsip</span>
                    </td>

                    <td>
                        <a href="{{ route('arsip-tahun-ajaran.detail',$arsip->id) }}"
                            class="btn btn-info btn-sm">Detail</a>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection