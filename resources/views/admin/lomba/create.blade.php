@extends('layouts.main')
@section('title', 'Tambah Lomba')
@push('css')
<style>
    .create-lomba-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 780px;
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
        padding: 16px 24px 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .create-card-body {
        padding: 16px 24px 20px;
    }
    .form-label-cu {
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        margin-bottom: 4px;
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
        height: 38px;
        padding: 0 22px;
        border-radius: 10px;
        font-size: 13px;
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
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }
    @media (max-width: 768px) {
        .create-lomba-page { margin-top: 16px; padding: 0 12px; }
        .create-card-header { padding: 14px 18px 12px; }
        .create-card-body { padding: 14px 18px 16px; }
        .form-actions-cu { flex-direction: column; }
        .form-actions-cu .btn-cu { width: 100%; }
    }
    @media (max-width: 480px) {
        .create-card-header { padding: 12px 14px 10px; }
        .create-card-body { padding: 12px 14px 14px; }
        .input-group-cu .form-control, .input-group-cu .form-select { height: 40px; font-size: 13px; }
        .btn-cu { height: 36px; padding: 0 16px; font-size: 12px; }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-lomba-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('lomba.index') }}">Lomba</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Lomba</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Tambah Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Buat lomba baru untuk haflatul imtihan.</span>
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

            <form action="{{ route('lomba.store') }}" method="POST">
                @csrf

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Haflatul Imtihan</label>
                            <div class="input-group-cu">
                                <i class="fas fa-calendar-alt input-group-cu-icon"></i>
                                <input type="text" class="form-control" value="{{ $haflahAktif->nama_acara ?? '-' }}" readonly style="background:#f8fafc;color:#64748b;cursor:default;">
                                <input type="hidden" name="haflah_id" value="{{ session('haflah_id') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Lokasi</label>
                            <div class="input-group-cu">
                                <i class="fas fa-map-marker-alt input-group-cu-icon"></i>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}" placeholder="Lokasi lomba">
                                @error('lokasi')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Sesi Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-clock input-group-cu-icon"></i>
                                <select name="sesi_lomba_id" class="form-select @error('sesi_lomba_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach($sesiLombas as $sl)
                                    <option value="{{ $sl->id }}" {{ old('sesi_lomba_id')==$sl->id ? 'selected' : '' }}>{{ $sl->nama }}</option>
                                    @endforeach
                                </select>
                                @error('sesi_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Kategori Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-tag input-group-cu-icon"></i>
                                <select name="kategori_lomba_id" class="form-select @error('kategori_lomba_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach($kategoriLombas as $kl)
                                    <option value="{{ $kl->id }}" {{ old('kategori_lomba_id')==$kl->id ? 'selected' : '' }}>{{ $kl->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-cu">Nama Lomba</label>
                    <div class="input-group-cu">
                        <i class="fas fa-trophy input-group-cu-icon"></i>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama lomba">
                        @error('nama')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Jenis</label>
                            <div class="input-group-cu">
                                <i class="fas fa-users input-group-cu-icon"></i>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="Individu" {{ old('jenis')=='Individu' ? 'selected' : '' }}>Individu</option>
                                    <option value="Tim" {{ old('jenis')=='Tim' ? 'selected' : '' }}>Tim</option>
                                </select>
                                @error('jenis')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-cu">Status</label>
                            <div class="input-group-cu">
                                <i class="fas fa-flag input-group-cu-icon"></i>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="Belum Mulai" {{ old('status')=='Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                                    <option value="Berlangsung" {{ old('status')=='Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="Selesai" {{ old('status')=='Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-cu">Aturan Peserta</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label style="font-size:12px;color:#64748b;margin-bottom:4px;display:block;">Dari Kelas</label>
                            <select name="kelas_min" class="form-select @error('kelas_min') is-invalid @enderror" style="padding-left:14px;">
                                <option value="">Semua Kelas</option>
                                @foreach($tingkatList as $t)
                                <option value="{{ $t }}" {{ old('kelas_min')==$t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                                @endforeach
                            </select>
                            @error('kelas_min')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:12px;color:#64748b;margin-bottom:4px;display:block;">Sampai Kelas</label>
                            <select name="kelas_max" class="form-select @error('kelas_max') is-invalid @enderror" style="padding-left:14px;">
                                <option value="">Semua Kelas</option>
                                @foreach($tingkatList as $t)
                                <option value="{{ $t }}" {{ old('kelas_max')==$t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                                @endforeach
                            </select>
                            @error('kelas_max')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:4px;"><i class="fas fa-info-circle me-1"></i> Kosongkan jika semua peserta boleh ikut.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label-cu">Deskripsi</label>
                    <div class="input-group-cu">
                        <i class="fas fa-align-left input-group-cu-icon" style="top:16px;transform:none;"></i>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="2" placeholder="Masukkan deskripsi lomba" style="padding-left:42px;padding-top:10px;height:auto;">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('lomba.index') }}" class="btn btn-cu btn-cu-secondary">
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
