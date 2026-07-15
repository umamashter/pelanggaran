@extends('layouts.main')
@section('title', 'Tambah Haflatul Imtihan')
@section('content')

@include('component.admin.ms-style')

<style>
:root {
    --hi-primary: #7c3aed;
    --hi-primary-light: #ede9fe;
}

.form-section-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section-title i {
    color: var(--hi-primary);
}

.form-card-inner {
    background: #f8fafc;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 24px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    font-size: 14px;
    padding: 8px 14px;
    transition: all .2s;
    background: #fff;
    color: #1e293b;
    height: 44px;
}

.form-control:focus, .form-select:focus {
    border-color: var(--hi-primary) !important;
    box-shadow: 0 0 0 3px rgba(124,58,237,.12) !important;
    outline: none;
}

.form-control:disabled {
    background: #f1f5f9;
    color: #64748b;
    cursor: not-allowed;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 6px;
}

.btn-hi-primary {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 28px;
    font-size: 14px;
    font-weight: 600;
    transition: all .25s;
    box-shadow: 0 4px 14px rgba(124,58,237,.3);
}

.btn-hi-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(124,58,237,.4);
    color: #fff;
}

.btn-hi-secondary {
    background: #fff;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 28px;
    font-size: 14px;
    font-weight: 600;
    transition: all .25s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-hi-secondary:hover {
    border-color: var(--hi-primary);
    color: var(--hi-primary);
    background: var(--hi-primary-light);
}
</style>

<div class="master-siswa-page">

    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
        <div class="card-body p-4">

            {{-- HEADER --}}
            <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1.5px solid #e2e8f0;">
                <div class="header-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);box-shadow:0 4px 14px rgba(124,58,237,.3);">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:var(--ms-text);font-size:20px;">Tambah Haflatul Imtihan</h4>
                    <p class="mb-0" style="color:var(--ms-text-soft);font-size:13px;">Buat penyelenggaraan haflatul imtihan baru</p>
                </div>
            </div>

            {{-- ERRORS --}}
            @if($errors->any())
            <div class="alert alert-danger" style="border:none;border-radius:12px;padding:14px 20px;font-size:14px;background:#fef2f2;color:#991b1b;border-left:4px solid #dc2626;margin-bottom:22px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Periksa kembali data yang dimasukkan</strong>
                </div>
                <ul class="mb-0 ps-3" style="font-size:13px;">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- SUDAH ADA --}}
            @if($sudahAda)
            <div class="alert alert-warning" style="border:none;border-radius:12px;padding:18px 22px;font-size:14px;background:#fffbeb;color:#92400e;border-left:4px solid #f59e0b;margin-bottom:22px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i>
                    <strong>Haflatul Imtihan tahun ini sudah ada</strong>
                </div>
                <p class="mb-2" style="font-size:13px;">
                    Tahun ajaran <strong>{{ $tahunAktif->tahun_ajaran ?? '-' }}</strong> sudah memiliki data Haflatul Imtihan.
                    Tidak dapat membuat lebih dari satu.
                </p>
                <a href="{{ route('haflatul-imtihan.index') }}" class="btn-hi-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            @else
            {{-- FORM --}}
            <form action="{{ route('haflatul-imtihan.store') }}" method="POST" onsubmit="return !this.submit.disabled && (this.submit.disabled=true, sessionStorage.setItem('sia_navigating','true'), true)">
                @csrf

                <div class="form-card-inner">
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Informasi Haflah
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1" style="color:var(--hi-primary);font-size:12px;"></i>
                                Tahun Ajaran
                            </label>
                            <div class="input-group-cu">
                                <div class="input-group-cu-icon"><i class="fas fa-school"></i></div>
                                <input type="text" class="form-control" value="{{ $tahunAktif->tahun_ajaran ?? '-' }}" disabled>
                                <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">
                            </div>
                            @error('tahun_ajaran_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            <div style="font-size:12px;color:#94a3b8;margin-top:4px;">
                                <i class="fas fa-lock me-1"></i> Menggunakan tahun ajaran aktif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:10px;padding:10px 14px;background:#fffbeb;border-radius:8px;border:1px solid #fde68a;">
                                <i class="fas fa-clock me-1" style="color:#f59e0b;"></i>
                                Status otomatis: <strong>Persiapan</strong> → <strong>Aktif</strong> (tanggal mulai) → <strong>Selesai</strong> (tanggal selesai)
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-tag me-1" style="color:var(--hi-primary);font-size:12px;"></i>
                                Nama Acara
                            </label>
                            <div class="input-group-cu">
                                <div class="input-group-cu-icon"><i class="fas fa-award"></i></div>
                                <input type="text" name="nama_acara" class="form-control @error('nama_acara') is-invalid @enderror" value="{{ old('nama_acara', 'Haflatul Imtihan dan Akhirussanah') }}" placeholder="Contoh: Haflatul Imtihan dan Akhirussanah">
                            </div>
                            @error('nama_acara')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-day me-1" style="color:var(--hi-primary);font-size:12px;"></i>
                                Tanggal Mulai
                            </label>
                            <div class="input-group-cu">
                                <div class="input-group-cu-icon"><i class="fas fa-calendar"></i></div>
                                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}">
                            </div>
                            @error('tanggal_mulai')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-check me-1" style="color:var(--hi-primary);font-size:12px;"></i>
                                Tanggal Selesai
                            </label>
                            <div class="input-group-cu">
                                <div class="input-group-cu-icon"><i class="fas fa-calendar"></i></div>
                                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">
                            </div>
                            @error('tanggal_selesai')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex gap-3 mt-4 pt-3" style="border-top:1.5px solid #e2e8f0;">
                    <button type="submit" class="btn-hi-primary">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                    <a href="{{ route('haflatul-imtihan.index') }}" class="btn-hi-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

            </form>
            @endif

        </div>
    </div>

</div>
@endsection
