@extends('layouts.main')
@section('title', 'Edit Kelompok Lomba')
@push('css')
<style>
    .edit-kelompok-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 720px;
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
    .edit-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
    }
    .edit-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .edit-card-body {
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
    .input-group-cu-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 4;
        pointer-events: none;
    }
    .input-group-cu .form-control.is-invalid,
    .input-group-cu .form-select.is-invalid {
        border-color: #dc2626;
        background-image: none;
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
    .form-actions-cu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }
    .kode-disabled {
        background: #f1f5f9 !important;
        color: #64748b !important;
        cursor: not-allowed;
    }
    @media (max-width: 768px) {
        .edit-kelompok-page {
            margin-top: 16px;
            padding: 0 12px;
        }
        .edit-card-header {
            padding: 18px 20px 16px;
        }
        .edit-card-body {
            padding: 18px 20px 22px;
        }
        .form-actions-cu {
            flex-direction: column;
        }
        .form-actions-cu .btn-cu {
            width: 100%;
        }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="edit-kelompok-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kelompok-lomba.index') }}">Kelompok Lomba</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Kelompok</li>
        </ol>
    </nav>

    <div class="card edit-card">
        <div class="edit-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Edit Kelompok Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Ubah data kelompok lomba</span>
                </div>
            </div>
        </div>

        <div class="edit-card-body">

            @if ($errors->any())
                <div class="alert alert-modern-ms alert-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada form:
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kelompok-lomba.update', $kelompokLomba->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Lomba</label>
                            <div class="input-group-cu">
                                <i class="fas fa-trophy input-group-cu-icon"></i>
                                <select name="lomba_id" id="lomba_id" class="form-select @error('lomba_id') is-invalid @enderror">
                                    <option value="">-- Pilih Lomba --</option>
                                    @foreach($lombas as $l)
                                    <option value="{{ $l->id }}" data-kelas-min="{{ $l->kelas_min }}" data-kelas-max="{{ $l->kelas_max }}" {{ old('lomba_id', $kelompokLomba->lomba_id) == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                    @endforeach
                                </select>
                                @error('lomba_id')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div id="infoAturan" style="display:none;margin-top:8px;font-size:13px;color:#c2410c;"><i class="fas fa-info-circle me-1"></i> Lomba ini hanya untuk <strong><span id="infoKelasRange"></span></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Kode Kelompok</label>
                            <div class="input-group-cu">
                                <i class="fas fa-barcode input-group-cu-icon"></i>
                                <input type="text" class="form-control kode-disabled" value="{{ $kelompokLomba->kode_kelompok ?? 'Otomatis' }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Nama Kelompok</label>
                            <div class="input-group-cu">
                                <i class="fas fa-tag input-group-cu-icon"></i>
                                <input type="text" name="nama_kelompok" class="form-control @error('nama_kelompok') is-invalid @enderror" value="{{ old('nama_kelompok', $kelompokLomba->nama_kelompok) }}" placeholder="Masukkan nama kelompok">
                                @error('nama_kelompok')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-cu">Asal</label>
                            <div class="input-group-cu">
                                <i class="fas fa-map-marker-alt input-group-cu-icon"></i>
                                <input type="text" name="asal" class="form-control @error('asal') is-invalid @enderror" value="{{ old('asal', $kelompokLomba->asal) }}" placeholder="Misal: Kelas 4A">
                                @error('asal')
                                <div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('kelompok-lomba.index') }}" class="btn btn-cu btn-cu-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-cu btn-cu-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    var $lomba = $('#lomba_id');
    var $infoAturan = $('#infoAturan');
    var $infoRange = $('#infoKelasRange');

    $lomba.on('change', function () {
        var opt = $(this).find(':selected');
        var min = opt.data('kelas-min');
        var max = opt.data('kelas-max');
        if (min && max) {
            $infoRange.text(min === max ? 'Kelas ' + min : 'Kelas ' + min + ' - ' + max);
            $infoAturan.show();
        } else {
            $infoAturan.hide();
        }
    });

    $lomba.trigger('change');
});
</script>
@endpush
