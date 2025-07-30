@extends('layouts.main')
@section('title', 'Edit Peraturan')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Peraturan</h1>
    <a href="/peraturan" class="btn btn-secondary mb-3">← Kembali</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/peraturan/{{ $peraturan->id }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Peraturan</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $peraturan->nama }}" required>
        </div>
        <div class="mb-3">
            <label for="poin" class="form-label">Poin</label>
            <input type="number" name="poin" id="poin" class="form-control" value="{{ $peraturan->poin }}" required>
        </div>
        <div class="mb-3">
            <label for="jenis_peraturan_id" class="form-label">Jenis Peraturan</label>
            <select name="jenis_peraturan_id" id="jenis_peraturan_id" class="form-select" required>
                @foreach ($jenisPeraturan as $jenis)
                    <option value="{{ $jenis->id }}" {{ $peraturan->jenis_peraturan_id == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
