@extends('layouts.main')

@section('content')
<div class="container">
    <h4>Edit Tindakan</h4>
    <form action="{{ route('tindak-lanjut.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Tindak Lanjut</label>
            <input type="text" name="tindak_lanjut" value="{{ $data->tindak_lanjut }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tingkatan</label>
            <select name="tingkatan" class="form-control" required>
                <option value="">-- Pilih Tingkatan --</option>
                <option value="Ringan" {{ $data->tingkatan == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                <option value="Sedang" {{ $data->tingkatan == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="Berat" {{ $data->tingkatan == 'Berat' ? 'selected' : '' }}>Berat</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
