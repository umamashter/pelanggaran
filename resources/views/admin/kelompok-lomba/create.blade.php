@extends('layouts.main')
@section('title', 'Tambah Kelompok Lomba')
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
    .lw-field-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--lw-text-3); font-size: 14px; z-index: 2; pointer-events: none; }
    .lw-help-text { margin-top: 5px; font-size: 11px; color: var(--lw-text-3); }
    .lw-inline-error { margin-top: 5px; font-size: 12px; font-weight: 600; color: var(--lw-red); display: flex; align-items: center; gap: 6px; }
    .lw-error-banner { border: none; border-radius: 14px; background: var(--lw-red-soft); color: #991b1b; border-left: 4px solid var(--lw-red); padding: 14px 18px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
    .lw-error-banner ul { margin: 8px 0 0; padding-left: 18px; }
    .lw-form-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--lw-border); }
    .lw-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
    .lw-info-cell { background: var(--lw-bg); border: 1px solid var(--lw-border); border-radius: 12px; padding: 12px 14px; }
    .lw-info-cell .lbl { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; display: flex; align-items: center; gap: 5px; }
    .lw-info-cell .val { font-size: 14px; font-weight: 600; color: var(--lw-text); }
    @media (max-width: 767.98px) { .lw-form-actions { flex-direction: column-reverse; } .lw-form-actions .lw-btn { width: 100%; justify-content: center; } }
</style>

<div class="lw-mod lw-page-kl-create">
<div style="max-width:620px;margin:18px auto 0;padding:0 16px 32px;">

<div class="lw-card" style="overflow:hidden;">
    <div class="lw-card-pad" style="border-bottom:1px solid var(--lw-border);background:var(--lw-grad-soft);">
        <span class="lw-chip lw-chip--green" style="margin-bottom:8px;"><i class="bi bi-people-fill"></i> Team Builder</span>
        <h1 style="font-size:clamp(22px,2.6vw,28px);font-weight:800;letter-spacing:-.03em;color:var(--lw-text);margin:6px 0 4px;">Tambah Kelompok</h1>
        <p style="color:var(--lw-text-3);font-size:13px;margin:0;">Buat kelompok untuk lomba tim. Kode akan dibuat otomatis dan peserta didaftarkan ke sistem.</p>
    </div>

    <div class="lw-card-pad">
        @if ($errors->any())
            <div class="lw-error-banner">
                <strong class="d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>Terdapat kesalahan pada form</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('kelompok-lomba.store') }}" method="POST" id="klCreateForm" novalidate>
            @csrf

            <div class="mb-4">
                <label class="lw-field-label required" for="lomba_id">Pilih Lomba</label>
                <div class="lw-field-wrap">
                    <i class="bi bi-trophy-fill lw-field-icon"></i>
                    <select id="lomba_id" name="lomba_id" class="@error('lomba_id') is-invalid @enderror">
                        <option value="">-- Pilih Lomba Tim --</option>
                        @foreach($lombas as $lmb)
                            <option value="{{ $lmb->id }}" data-kelas-min="{{ $lmb->kelas_min }}" data-kelas-max="{{ $lmb->kelas_max }}" {{ old('lomba_id') == $lmb->id ? 'selected' : '' }}>{{ $lmb->nama }}</option>
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
                    <input type="text" id="nama_kelompok" name="nama_kelompok" class="@error('nama_kelompok') is-invalid @enderror" value="{{ old('nama_kelompok') }}" placeholder="Contoh: Tim Al-Fatih" maxlength="255">
                    @error('nama_kelompok')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>@enderror
                </div>
                <div class="lw-help-text">Nama unik yang mudah dikenali oleh panitia.</div>
            </div>

            <div class="mb-4">
                <label class="lw-field-label">Kode Kelompok</label>
                <div class="lw-field-wrap">
                    <i class="bi bi-hash lw-field-icon"></i>
                    <input type="text" class="@error('kode_kelompok') is-invalid @enderror" value="Otomatis" disabled>
                </div>
                <div class="lw-help-text"><i class="bi bi-info-circle-fill me-1" style="color:var(--lw-primary);"></i>Kode akan dibuat otomatis dengan format <strong>KLP-XXXX</strong> setelah disimpan.</div>
            </div>

            {{-- SUMMARY PREVIEW --}}
            <div class="lw-card lw-card-pad" style="margin-bottom:16px;">
                <div class="lw-section-title"><i class="bi bi-clipboard-check-fill"></i> Ringkasan</div>
                <div class="lw-info-grid mt-2" style="grid-template-columns:1fr 1fr;">
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-trophy-fill"></i>Lomba</div><div class="val" id="summaryLomba">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-tag-fill"></i>Nama</div><div class="val" id="summaryNama">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-mortarboard-fill"></i>Range Kelas</div><div class="val" id="summaryKelas">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-hash"></i>Kode</div><div class="val" style="color:var(--lw-text-3);">Otomatis (KLP-XXXX)</div></div>
                </div>
                <div style="font-size:11px;color:var(--lw-text-3);font-weight:600;margin-top:8px;padding:8px 10px;border-radius:8px;background:var(--lw-primary-soft);color:var(--lw-primary);">
                    <i class="bi bi-check-circle-fill me-1"></i>Peserta lomba otomatis didaftarkan ke sistem setelah kelompok disimpan.
                </div>
            </div>

            <div class="lw-form-actions">
                <a href="{{ route('kelompok-lomba.index') }}" class="lw-btn lw-btn--ghost" style="border:1px solid var(--lw-border);"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button>
                    <span class="btn-label"><i class="bi bi-save-fill"></i> Simpan Kelompok</span>
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
    var form = document.getElementById('klCreateForm');
    var submitBtn = document.querySelector('[data-submit-button]');
    var lombaSelect = document.getElementById('lomba_id');
    var kelasInfo = document.getElementById('kelasInfo');
    var summaryLomba = document.getElementById('summaryLomba');
    var summaryNama = document.getElementById('summaryNama');
    var summaryKelas = document.getElementById('summaryKelas');
    var namaInput = document.getElementById('nama_kelompok');

    function updateInfo() {
        var opt = lombaSelect.options[lombaSelect.selectedIndex];
        var min = opt.dataset.kelasMin || null;
        var max = opt.dataset.kelasMax || null;
        var lombaNama = opt.text || '-';

        if (!opt.value) { kelasInfo.style.display = 'none'; if (summaryKelas) summaryKelas.textContent = '-'; return; }

        if (min && max) {
            kelasInfo.innerHTML = '<i class="bi bi-funnel-fill"></i> Lomba ini untuk <strong>Kelas ' + min + ' - ' + max + '</strong>.';
            if (summaryKelas) summaryKelas.textContent = 'Kelas ' + min + ' - ' + max;
        } else if (min) {
            kelasInfo.innerHTML = '<i class="bi bi-funnel-fill"></i> Lomba ini untuk <strong>Kelas ' + min + ' ke atas</strong>.';
            if (summaryKelas) summaryKelas.textContent = 'Kelas ' + min + '+';
        } else if (max) {
            kelasInfo.innerHTML = '<i class="bi bi-funnel-fill"></i> Lomba ini untuk <strong>sampai Kelas ' + max + '</strong>.';
            if (summaryKelas) summaryKelas.textContent = 's/d Kelas ' + max;
        } else {
            kelasInfo.innerHTML = '<i class="bi bi-check-circle-fill" style="color:var(--lw-green);"></i> Lomba ini terbuka untuk <strong>semua kelas</strong>.';
            if (summaryKelas) summaryKelas.textContent = 'Semua Kelas';
        }
        kelasInfo.style.display = 'block';
        if (summaryLomba) summaryLomba.textContent = lombaNama;
    }

    function syncSummary() {
        if (summaryNama) summaryNama.textContent = namaInput?.value.trim() || '-';
        updateInfo();
    }

    if (lombaSelect) { lombaSelect.addEventListener('change', syncSummary); updateInfo(); }
    if (namaInput) { namaInput.addEventListener('input', syncSummary); }
    syncSummary();

    if (form && submitBtn) {
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
