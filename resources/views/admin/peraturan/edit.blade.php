@extends('layouts.main')
@section('title', 'Edit Peraturan')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Peraturan</h1>
    <a href="/peraturan" class="btn btn-secondary mb-3">← Kembali</a>

    <form action="/peraturan/{{ $peraturan->id }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama Peraturan --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Peraturan</label>
            <input 
                type="text" 
                name="nama" 
                id="nama" 
                class="form-control @error('nama') is-invalid @enderror" 
                value="{{ old('nama', $peraturan->nama) }}" 
                required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Poin --}}
        <div class="mb-3">
            <label for="poin" class="form-label">Poin</label>
            <input 
                type="number" 
                name="poin" 
                id="poin" 
                class="form-control @error('poin') is-invalid @enderror" 
                value="{{ old('poin', $peraturan->poin) }}" 
                required>
            @error('poin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jenis Peraturan --}}
        <div class="mb-3">
            <label for="jenis_peraturan_id" class="form-label">Jenis Peraturan</label>
            <select 
                name="jenis_peraturan_id" 
                id="jenis_peraturan_id" 
                class="form-select @error('jenis_peraturan_id') is-invalid @enderror" 
                required>
                <option value="">-- Pilih Jenis --</option>
                @foreach ($jenisPeraturan as $jenis)
                    <option value="{{ $jenis->id }}" 
                        {{ old('jenis_peraturan_id', $peraturan->jenis_peraturan_id) == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama }}
                    </option>
                @endforeach
            </select>
            @error('jenis_peraturan_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
