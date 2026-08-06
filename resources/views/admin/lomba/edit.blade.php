@extends('layouts.main')
@section('title', 'Edit Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-form-card { max-width: 860px; }
    .lw-breadcrumb { margin-bottom: 16px; }
</style>

@php $isLocked = $lomba->is_haflah_selesai; @endphp

<div class="lw-mod jd-page-lomba-edit">

<div class="lw-card lw-card-pad lw-form-card" style="margin:0 auto;">
    <div class="lw-breadcrumb">
        <a href="{{ route('lomba.index') }}">Lomba</a> <i class="bi bi-chevron-right"></i> <span>Edit Lomba</span>
    </div>

    <div class="lw-hero">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-pencil-square"></i></span>
                <div>
                    <h1 class="lw-hero-title">Edit: {{ $lomba->nama }}</h1>
                    <p class="lw-hero-sub">{{ $isLocked ? 'Lomba ini hanya dapat dilihat karena Haflah telah selesai.' : 'Ubah detail lomba. Validasi backend tetap berlaku.' }}</p>
                    <div class="lw-hero-badges">
                        <span class="lw-hero-badge"><i class="bi bi-trophy-fill"></i> {{ $lomba->nama }}</span>
                        @if($isLocked)
                            <span class="lw-hero-badge lw-hero-badge--lock"><i class="bi bi-lock-fill"></i> Haflah Selesai</span>
                        @else
                            <span class="lw-hero-badge lw-hero-badge--ok"><i class="bi bi-check2-circle"></i> Dapat Diedit</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="lw-alert lw-alert--err">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div style="flex:1;min-width:0;">
                <b>Terdapat kesalahan pada form</b>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    @if($isLocked)
        <div class="lw-lock-banner">
            <i class="bi bi-lock-fill"></i>
            <div>
                <b>Lomba Terkunci</b>
                <p style="margin:2px 0 0;">Haflatul Imtihan sudah <b>Selesai</b>. Seluruh field bersifat readonly — data tidak dapat disimpan.</p>
            </div>
        </div>
    @endif

    <form action="{{ route('lomba.update', $lomba->id) }}" method="POST" id="lombaEditForm" novalidate>
        @csrf @method('PUT')

        <div class="row g-4" style="margin-bottom:8px;">
            <div class="col-md-6">
                <div class="lw-field">
                    <label class="lw-field-label">Haflatul Imtihan</label>
                    <input type="text" class="lw-control" value="{{ $lomba->haflatulImtihan->nama_acara ?? '-' }}" readonly>
                    <div class="lw-help-text"><i class="bi bi-lock-fill"></i> Tidak dapat diubah.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="lw-field">
                    <label class="lw-field-label" for="nama">Nama Lomba</label>
                    <input type="text" id="nama" name="nama" class="lw-control @error('nama') is-invalid @enderror" value="{{ old('nama', $lomba->nama) }}" placeholder="Nama lomba" maxlength="255" {{ $isLocked ? 'readonly' : '' }}>
                    @error('nama')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="lw-field">
                    <label class="lw-field-label" for="jenis">Jenis</label>
                    <select id="jenis" name="jenis" class="lw-select @error('jenis') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                        <option value="Individu" {{ old('jenis', $lomba->jenis) == 'Individu' ? 'selected' : '' }}>Individu</option>
                        <option value="Tim" {{ old('jenis', $lomba->jenis) == 'Tim' ? 'selected' : '' }}>Tim</option>
                    </select>
                    @error('jenis')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="lw-field">
                    <label class="lw-field-label" for="lokasi">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" class="lw-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $lomba->lokasi) }}" placeholder="Lokasi" maxlength="255" {{ $isLocked ? 'readonly' : '' }}>
                    @error('lokasi')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="lw-field">
                    <label class="lw-field-label" for="sesi_lomba_id">Sesi Lomba</label>
                    <select id="sesi_lomba_id" name="sesi_lomba_id" class="lw-select @error('sesi_lomba_id') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">-- Pilih --</option>
                        @foreach($sesiLombas as $sl)
                            <option value="{{ $sl->id }}" {{ old('sesi_lomba_id', $lomba->sesi_lomba_id) == $sl->id ? 'selected' : '' }}>{{ $sl->nama }}</option>
                        @endforeach
                    </select>
                    @error('sesi_lomba_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="lw-field">
                    <label class="lw-field-label" for="status">Status</label>
                    <select id="status" name="status" class="lw-select @error('status') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                        <option value="Belum Mulai" {{ old('status', $lomba->status) == 'Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                        <option value="Berlangsung" {{ old('status', $lomba->status) == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="Selesai" {{ old('status', $lomba->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="lw-field">
                    <label class="lw-field-label" for="kelas_min">Dari Kelas</label>
                    <select id="kelas_min" name="kelas_min" class="lw-select @error('kelas_min') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">Semua Kelas</option>
                        @foreach($tingkatList as $t)
                            <option value="{{ $t }}" {{ old('kelas_min', $lomba->kelas_min) == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                        @endforeach
                    </select>
                    @error('kelas_min')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="lw-field">
                    <label class="lw-field-label" for="kelas_max">Sampai Kelas</label>
                    <select id="kelas_max" name="kelas_max" class="lw-select @error('kelas_max') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">Semua Kelas</option>
                        @foreach($tingkatList as $t)
                            <option value="{{ $t }}" {{ old('kelas_max', $lomba->kelas_max) == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                        @endforeach
                    </select>
                    @error('kelas_max')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-12">
                <div class="lw-field">
                    <label class="lw-field-label" for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="lw-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Deskripsi (opsional)" {{ $isLocked ? 'readonly' : '' }}>{{ old('deskripsi', $lomba->deskripsi) }}</textarea>
                    @error('deskripsi')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="lw-wizard-nav">
            <a href="{{ route('lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
            <span class="spacer"></span>
            <button type="submit" class="lw-btn lw-btn--solid" data-submit-button {{ $isLocked ? 'disabled' : '' }}>
                <span class="btn-label"><i class="bi bi-save"></i> {{ $isLocked ? 'Terkunci' : 'Simpan Perubahan' }}</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        </div>
    </form>
</div>

</div>

@push('scripts')
<script>
(function() {
    var form = document.getElementById('lombaEditForm');
    var submitBtn = document.querySelector('[data-submit-button]');
    if (form && submitBtn && !submitBtn.disabled) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            var label = submitBtn.querySelector('.btn-label');
            if (label) label.classList.add('d-none');
            var spinner = submitBtn.querySelector('.spinner-border');
            if (spinner) spinner.classList.remove('d-none');
        });
    }
})();
</script>
@endpush
@endsection
