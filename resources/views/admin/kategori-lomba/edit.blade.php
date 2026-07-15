@extends('layouts.main')
@section('title', 'Edit Kategori Lomba')
@section('content')

@include('component.admin.ms-style')

<div class="master-siswa-page">

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Edit Kategori Lomba</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <form action="{{ route('kategori-lomba.update', $kategoriLomba->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategoriLomba->nama) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Warna</label>
                    <input type="text" name="warna" class="form-control" value="{{ old('warna', $kategoriLomba->warna) }}" placeholder="#ffffff">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Icon</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $kategoriLomba->icon) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $kategoriLomba->urutan) }}">
                </div>
                <button type="submit" class="btn btn-header-ms btn-simpan-ms">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
