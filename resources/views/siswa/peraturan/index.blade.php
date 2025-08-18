@extends('layouts.main')
@section('title', 'Daftar Peraturan')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Daftar Peraturan</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover" id="datatable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Peraturan</th>
                        <th>Poin</th>
                        <th>Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peraturan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->poin }}</td>
                        <td>{{ $item->jenisPeraturan->nama ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<style>
    table.dataTable td {
    white-space: normal !important;
}
</style>