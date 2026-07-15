@extends('layouts.main')
@section('title', 'Edit Peserta Lomba')
@push('css')
<style>
    .create-peserta-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 640px;
        margin: 14px auto 0;
        padding: 0 22px;
    }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 14px 16px 12px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 12px 16px 14px; }
    .form-label-cu { font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 5px; display: block; }
    .invalid-feedback-cu { display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 13px; color: #dc2626; font-weight: 500; }
    .input-group-cu { position: relative; }
    .input-group-cu .form-control, .input-group-cu .form-select { height: 38px; padding-left: 36px; border-radius: 8px; border: 1.5px solid #e2e8f0; font-size: 13px; transition: border .2s, box-shadow .2s; }
    .input-group-cu .form-control:focus, .input-group-cu .form-select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
    .input-group-cu-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; z-index: 4; pointer-events: none; }
    .btn-cu { height: 36px; padding: 0 20px; border-radius: 8px; font-size: 13px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 6px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 20px; padding-top: 14px; border-top: 1px solid #f1f5f9; }
    .btn-header-ms.btn-simpan-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }
    .compact-grid { row-gap: 6px; }
    @media (max-width: 768px) {
        .create-peserta-page { margin-top: 12px; padding: 0 14px; }
        .create-card-header { padding: 12px 14px 10px; }
        .create-card-body { padding: 12px 14px 16px; }
        .form-actions-cu { flex-direction: column; }
        .form-actions-cu .btn-cu { width: 100%; }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-peserta-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta-lomba.index') }}">Peserta Lomba</a></li>
            <li class="breadcrumb-item active">Edit Peserta Lomba</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:34px;height:34px;font-size:16px;"><i class="fas fa-edit"></i></div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 15px;">Edit Peserta Lomba</h4>
                    <span style="font-size: 12px; color: #64748b;">Ubah data peserta lomba.</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            @if ($errors->any())
                <div class="alert alert-cu alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('peserta-lomba.update', $pesertaLomba->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-2 compact-grid">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label-cu">Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-trophy input-group-cu-icon"></i>
                                <select name="lomba_id" class="form-select @error('lomba_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach($lombas as $l)
                                    <option value="{{ $l->id }}" {{ old('lomba_id', $pesertaLomba->lomba_id)==$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                    @endforeach
                                </select>
                                @error('lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label-cu">Status</label>
                            <div class="input-group-cu">
                                <i class="fas fa-flag input-group-cu-icon"></i>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="Terdaftar" {{ old('status', $pesertaLomba->status)=='Terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                    <option value="Hadir" {{ old('status', $pesertaLomba->status)=='Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Tidak Hadir" {{ old('status', $pesertaLomba->status)=='Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="Diskualifikasi" {{ old('status', $pesertaLomba->status)=='Diskualifikasi' ? 'selected' : '' }}>Diskualifikasi</option>
                                </select>
                                @error('status')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    @if($pesertaLomba->lomba && $pesertaLomba->lomba->jenis === 'Tim')
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label class="form-label-cu">Kelompok</label>
                            <div class="input-group-cu">
                                <i class="fas fa-users input-group-cu-icon"></i>
                                <select name="kelompok_lomba_id" class="form-select @error('kelompok_lomba_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach($kelompokLombas as $kl)
                                    <option value="{{ $kl->id }}" {{ old('kelompok_lomba_id', $pesertaLomba->kelompok_lomba_id)==$kl->id ? 'selected' : '' }}>{{ $kl->kode_kelompok ? $kl->kode_kelompok.' - ' : '' }}{{ $kl->nama_kelompok }}</option>
                                    @endforeach
                                </select>
                                @error('kelompok_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label class="form-label-cu">Siswa</label>
                            <div class="input-group-cu">
                                <i class="fas fa-user-graduate input-group-cu-icon"></i>
                                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach($students as $s)
                                    <option value="{{ $s->id }}" {{ old('student_id', $pesertaLomba->student_id)==$s->id ? 'selected' : '' }}>{{ $s->user->name ?? $s->nama ?? '-' }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('peserta-lomba.index') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-header-ms btn-simpan-ms btn-compact"><i class="fas fa-save"></i> Simpan</button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection