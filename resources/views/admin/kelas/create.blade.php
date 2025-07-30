@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Tambah Kelas</h3>

    <form action="{{ route('kelas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_kelas">Nama Kelas</label>
            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary mt-2">Kembali</a>
    </form>
</div>
@endsection
