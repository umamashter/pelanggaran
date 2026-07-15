@extends('layouts.main')
@section('title', 'Tambah Nama Sesi')
@push('css')
<style>
    .create-sesi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; max-width: 520px; margin: 22px auto 0; padding: 0 16px; }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 24px 28px 28px; }
    .form-label-cu { font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 8px; display: block; }
    .invalid-feedback-cu { display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 13px; color: #dc2626; font-weight: 500; }
    .input-group-cu { position: relative; }
    .input-group-cu .form-control.is-invalid { border-color: #dc2626; background-image: none; }
    .input-group-cu .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.1); }
    .input-group-cu .form-control { height: 46px; padding-left: 42px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px; transition: border .2s, box-shadow .2s; width:100%; }
    .input-group-cu .form-control:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
    .input-group-cu .form-control::placeholder { color: #94a3b8; font-size: 13px; }
    .input-group-cu-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px; z-index: 4; pointer-events: none; }
    .alert-cu { border: none; border-radius: 12px; padding: 14px 20px; font-size: 14px; margin-bottom: 20px; }
    .alert-cu.alert-success { background: #f0fdf4; color: #16a34a; border-left: 4px solid #16a34a; }
    .alert-cu.alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
    .btn-cu { height: 44px; padding: 0 28px; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; gap: 8px; border: none; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    @media (max-width:768px) { .create-sesi-page { margin-top:16px; padding:0 12px; } .create-card-header { padding:18px 20px 16px; } .create-card-body { padding:18px 20px 22px; } .form-actions-cu { flex-direction:column; } .form-actions-cu .btn-cu { width:100%; } }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-sesi-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sesi.index') }}">Nama Sesi</a></li>
            <li class="breadcrumb-item active">Tambah Nama Sesi</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;"><i class="fas fa-plus"></i></div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1e293b;font-size:18px;">Tambah Nama Sesi</h4>
                    <span style="font-size:13px;color:#64748b;">Tambah opsi nama sesi untuk dropdown.</span>
                </div>
            </div>
        </div>
        <div class="create-card-body">

            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan:
                    <ul class="mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('sesi.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label-cu">Nama Sesi</label>
                    <div class="input-group-cu">
                        <i class="fas fa-tag input-group-cu-icon"></i>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Cth: Hari 1 Pagi">
                        @error('nama')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-cu">Tanggal <span style="color:#94a3b8;font-weight:400;font-size:12px;">(default untuk sesi lomba)</span></label>
                    <div class="input-group-cu">
                        <i class="fas fa-calendar-day input-group-cu-icon"></i>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}">
                        @error('tanggal')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label-cu">Jam Mulai</label>
                        <div class="input-group-cu">
                            <i class="fas fa-play input-group-cu-icon"></i>
                            <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}">
                            @error('jam_mulai')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-cu">Jam Selesai</label>
                        <div class="input-group-cu">
                            <i class="fas fa-stop input-group-cu-icon"></i>
                            <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}">
                            @error('jam_selesai')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="form-actions-cu">
                    <a href="{{ route('sesi.index') }}" class="btn btn-cu btn-cu-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-cu btn-cu-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
