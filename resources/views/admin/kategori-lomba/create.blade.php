@extends('layouts.main')
@section('title', 'Tambah Kategori Lomba')
@push('css')
<style>
    .create-kategori-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 680px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu {
        margin-bottom: 20px;
    }
    .breadcrumb-cu .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .breadcrumb-cu .breadcrumb-item {
        font-size: 13px;
    }
    .breadcrumb-cu .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color .2s;
    }
    .breadcrumb-cu .breadcrumb-item a:hover {
        color: #16a34a;
    }
    .breadcrumb-cu .breadcrumb-item.active {
        color: #1e293b;
        font-weight: 500;
    }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before {
        color: #cbd5e1;
    }
    .create-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
    }
    .create-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .create-card-body {
        padding: 24px 28px 28px;
    }
    .form-label-cu {
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }
    .invalid-feedback-cu {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 13px;
        color: #dc2626;
        font-weight: 500;
    }
    .input-group-cu .form-control.is-invalid,
    .input-group-cu .form-select.is-invalid {
        border-color: #dc2626;
        background-image: none;
    }
    .input-group-cu .form-control.is-invalid:focus,
    .input-group-cu .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220,38,38,.1);
    }
    .input-group-cu {
        position: relative;
    }
    .input-group-cu .form-control,
    .input-group-cu .form-select {
        height: 46px;
        padding-left: 42px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 14px;
        transition: border .2s, box-shadow .2s;
    }
    .input-group-cu .form-control:focus,
    .input-group-cu .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    }
    .input-group-cu .form-control::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }
    .input-group-cu-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        z-index: 4;
        pointer-events: none;
    }
    .alert-cu {
        border: none;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 14px;
        margin-bottom: 20px;
    }
    .alert-cu.alert-success {
        background: #f0fdf4;
        color: #16a34a;
        border-left: 4px solid #16a34a;
    }
    .alert-cu.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }
    .alert-cu.alert-danger ul {
        padding-left: 20px;
        margin: 0;
    }
    .alert-cu.alert-danger ul li {
        list-style: disc;
    }
    .btn-cu {
        height: 44px;
        padding: 0 28px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border: none;
        gap: 8px;
    }
    .btn-cu-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }
    .btn-cu-secondary:hover {
        background: #e2e8f0;
        color: #334155;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,.08);
    }
    .btn-cu-primary {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        box-shadow: 0 2px 8px rgba(22,163,74,.25);
    }
    .btn-cu-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(22,163,74,.35);
        color: #fff;
    }
    .btn-cu:active {
        transform: translateY(0);
    }
    .form-actions-cu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }
    @media (max-width: 768px) {
        .create-kategori-page {
            margin-top: 16px;
            padding: 0 12px;
        }
        .create-card-header {
            padding: 18px 20px 16px;
        }
        .create-card-body {
            padding: 18px 20px 22px;
        }
        .form-actions-cu {
            flex-direction: column;
        }
        .form-actions-cu .btn-cu {
            width: 100%;
        }
    }
    @media (max-width: 480px) {
        .create-card-header {
            padding: 14px 16px 12px;
        }
        .create-card-body {
            padding: 14px 16px 18px;
        }
        .input-group-cu .form-control,
        .input-group-cu .form-select {
            height: 42px;
            font-size: 13px;
        }
        .btn-cu {
            height: 40px;
            padding: 0 20px;
            font-size: 13px;
        }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-kategori-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kategori-lomba.index') }}">Kategori Lomba</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Kategori Lomba</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Tambah Kategori Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Buat kategori baru untuk lomba.</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            @if(session('success'))
                <div class="alert alert-cu alert-success">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kategori-lomba.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label-cu">Nama Kategori</label>
                    <div class="input-group-cu">
                        <i class="fas fa-tag input-group-cu-icon"></i>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama kategori">
                        @error('nama')
                        <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Warna</label>
                            <div class="input-group-cu">
                                <i class="fas fa-palette input-group-cu-icon"></i>
                                <input type="text" name="warna" class="form-control @error('warna') is-invalid @enderror" value="{{ old('warna') }}" placeholder="#ffffff">
                                @error('warna')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Icon</label>
                            <div class="input-group-cu">
                                <i class="fas fa-icons input-group-cu-icon"></i>
                                <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="fa-icon-name">
                                @error('icon')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-cu">Urutan</label>
                    <div class="input-group-cu">
                        <i class="fas fa-sort-numeric-up-alt input-group-cu-icon"></i>
                        <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan') }}" placeholder="Urutan kategori" min="0">
                        @error('urutan')
                        <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('kategori-lomba.index') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
