@extends('layouts.main')


@section('content')
<div class="container">
    <h4>Tambah Tindakan Baru</h4>
    <form action="{{ route('tindak-lanjut.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tindak Lanjut</label>
            <input type="text" name="tindak_lanjut" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tingkatan</label>
            <select name="tingkatan" class="form-control" required>
                <option value="">-- Pilih Tingkatan --</option>
                <option value="Ringan">Ringan</option>
                <option value="Sedang">Sedang</option>
                <option value="Berat">Berat</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
