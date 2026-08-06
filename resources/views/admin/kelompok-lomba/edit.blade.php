@extends('layouts.main')
@section('title', 'Edit Kelompok Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .lw-field-label { display: block; margin-bottom: 6px; font-size: 12.5px; font-weight: 700; color: var(--lw-text); }
    .lw-field-label.required::after { content: ' *'; color: var(--lw-red); }
    .lw-field-wrap { position: relative; }
    .lw-field-wrap input, .lw-field-wrap select {
        min-height: 44px; border-radius: 12px; border: 1.5px solid var(--lw-border); background: var(--lw-card);
        color: var(--lw-text); padding-left: 40px; font-size: 13px; width: 100%; transition: all .2s ease; box-shadow: none;
    }
    .lw-field-wrap input:focus, .lw-field-wrap select:focus { border-color: var(--lw-primary); box-shadow: 0 0 0 4px var(--lw-primary-soft); outline: none; }
    .lw-field-wrap input.is-invalid, .lw-field-wrap select.is-invalid { border-color: var(--lw-red); }
    .lw-field-wrap input[readonly] { background: var(--lw-bg); color: var(--lw-text-3); cursor: not-allowed; border-style: dashed; }
    .lw-field-wrap select[disabled] { background: var(--lw-bg); color: var(--lw-text-3); cursor: not-allowed; }
    .lw-field-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--lw-text-3); font-size: 14px; z-index: 2; pointer-events: none; }
    .lw-help-text { margin-top: 5px; font-size: 11px; color: var(--lw-text-3); }
    .lw-inline-error { margin-top: 5px; font-size: 12px; font-weight: 600; color: var(--lw-red); display: flex; align-items: center; gap: 6px; }
    .lw-error-banner { border: none; border-radius: 14px; background: var(--lw-red-soft); color: #991b1b; border-left: 4px solid var(--lw-red); padding: 14px 18px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
    .lw-error-banner ul { margin: 8px 0 0; padding-left: 18px; }
    .lw-form-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--lw-border); }
    @media (max-width: 767.98px) { .lw-form-actions { flex-direction: column-reverse; } .lw-form-actions .lw-btn { width: 100%; justify-content: center; } }
</style>

<div class="lw-mod lw-page-kl-edit">
<div style="max-width:620px;margin:18px auto 0;padding:0 16px 32px;">

@php $isLocked = $kelompokLomba->is_haflah_selesai; @endphp

<div class="lw-card" style="overflow:hidden;">
    <div class="lw-card-pad" style="border-bottom:1px solid var(--lw-border);background:var(--lw-grad-soft);">
        <span class="lw-chip lw-chip--amber" style="margin-bottom:8px;"><i class="bi bi-pencil-square"></i> Team Editor</span>
        <h1 style="font-size:clamp(22px,2.6vw,28px);font-weight:800;letter-spacing:-.03em;color:var(--lw-text);margin:6px 0 4px;">Edit: {{ $kelompokLomba->nama_kelompok }}</h1>
        <p style="color:var(--lw-text-3);font-size:13px;margin:0;">{{ $isLocked ? 'Kelompok ini hanya dapat dilihat karena Haflah telah selesai.' : 'Ubah lomba, nama, atau asal kelompok.' }}</p>
    </div>

    <div class="lw-card-pad">
        @if ($errors->any())
            <div class="lw-error-banner">
                <strong class="d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>Terdapat kesalahan pada form</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @if($isLocked)
            <div class="lw-lock-banner" style="margin-bottom:18px;"><i class="bi bi-lock-fill"></i> <div><b>Kelompok Terkunci</b> — Haflatul Imtihan sudah Selesai. Seluruh field readonly dan data tidak dapat disimpan.</div></div>
        @endif

        <form action="{{ route('kelompok-lomba.update', $kelompokLomba->id) }}" method="POST" id="klEditForm" novalidate>
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="lw-field-label required" for="lomba_id">Pilih Lomba</label>
                <div class="lw-field-wrap">
                    <i class="bi bi-trophy-fill lw-field-icon"></i>
                    <select id="lomba_id" name="lomba_id" class="@error('lomba_id') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                        @foreach($lombas as $lmb)
                            <option value="{{ $lmb->id }}" data-kelas-min="{{ $lmb->kelas_min }}" data-kelas-max="{{ $lmb->kelas_max }}" {{ old('lomba_id', $kelompokLomba->lomba_id) == $lmb->id ? 'selected' : '' }}>{{ $lmb->nama }}</option>
                        @endforeach
                    </select>
                    @error('lomba_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                </div>
                <div class="lw-help-text" id="kelasInfo" style="display:none;"></div>
            </div>

            <div class="mb-4">
                <label class="lw-field-label required" for="nama_kelompok">Nama Kelompok</label>
                <div class="lw-field-wrap">
                    <i class="bi bi-tag-fill lw-field-icon"></i>
                    <input type="text" id="nama_kelompok" name="nama_kelompok" class="@error('nama_kelompok') is-invalid @enderror" value="{{ old('nama_kelompok', $kelompokLomba->nama_kelompok) }}" maxlength="255" {{ $isLocked ? 'readonly' : '' }}>
                    @error('nama_kelompok')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="lw-field-label" for="asal">Asal</label>
                <div class="lw-field-wrap">
                    <i class="bi bi-geo-alt-fill lw-field-icon"></i>
                    <input type="text" id="asal" name="asal" class="@error('asal') is-invalid @enderror" value="{{ old('asal', $kelompokLomba->asal) }}" placeholder="Misal: Kelas 4A" maxlength="255" {{ $isLocked ? 'readonly' : '' }}>
                    @error('asal')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                </div>
                <div class="lw-help-text">Informasi tambahan tentang asal kelompok (opsional).</div>
            </div>

            <div class="mb-4">
                <label class="lw-field-label">Kode Kelompok</label>
                <div class="lw-field-wrap">
                    <i class="bi bi-hash lw-field-icon"></i>
                    <input type="text" value="{{ $kelompokLomba->kode_kelompok ?? '-' }}" disabled>
                </div>
                <div class="lw-help-text">Kode tidak dapat diubah — dibuat otomatis oleh sistem.</div>
            </div>

            <div class="lw-form-actions">
                <a href="{{ route('kelompok-lomba.index') }}" class="lw-btn lw-btn--ghost" style="border:1px solid var(--lw-border);"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button {{ $isLocked ? 'disabled' : '' }}>
                    <span class="btn-label"><i class="bi bi-save-fill"></i> {{ $isLocked ? 'Terkunci' : 'Simpan Perubahan' }}</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>

@push('scripts')
<script>
(function() {
    var form = document.getElementById('klEditForm');
    var submitBtn = document.querySelector('[data-submit-button]');
    var lombaSelect = document.getElementById('lomba_id');
    var kelasInfo = document.getElementById('kelasInfo');

    function updateInfo() {
        var opt = lombaSelect.options[lombaSelect.selectedIndex];
        var min = opt.dataset.kelasMin || null;
        var max = opt.dataset.kelasMax || null;
        if (!opt.value) { kelasInfo.style.display = 'none'; return; }
        if (min && max) { kelasInfo.innerHTML = '<i class="bi bi-funnel-fill"></i> Lomba untuk <strong>Kelas ' + min + ' - ' + max + '</strong>.'; }
        else if (min) { kelasInfo.innerHTML = '<i class="bi bi-funnel-fill"></i> Lomba untuk <strong>Kelas ' + min + ' ke atas</strong>.'; }
        else if (max) { kelasInfo.innerHTML = '<i class="bi bi-funnel-fill"></i> Lomba untuk <strong>sampai Kelas ' + max + '</strong>.'; }
        else { kelasInfo.innerHTML = '<i class="bi bi-check-circle-fill" style="color:var(--lw-green);"></i> Terbuka untuk <strong>semua kelas</strong>.'; }
        kelasInfo.style.display = 'block';
    }

    if (lombaSelect) { lombaSelect.addEventListener('change', updateInfo); updateInfo(); }

    if (form && submitBtn && !submitBtn.disabled) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-label')?.classList.add('d-none');
            var spinner = submitBtn.querySelector('.spinner-border');
            if (spinner) spinner.classList.remove('d-none');
        });
    }
})();
</script>
@endpush
@endsection
