@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Edit Kelas</h3>

    <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_kelas">Nama Kelas</label>
            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" value="{{ $kelas->nama_kelas }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary mt-2">Kembali</a>
    </form>
</div>
@endsection
