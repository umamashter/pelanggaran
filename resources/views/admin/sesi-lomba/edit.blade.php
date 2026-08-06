@extends('layouts.main')
@section('title', 'Edit Sesi Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-form-card { max-width: 900px; }
    .lw-breadcrumb { margin-bottom: 16px; }

    .lw-char-counter { font-size: 11px; color: var(--lw-text-3); text-align: right; margin-top: 4px; font-weight: 600; }
    .lw-char-counter.warn { color: var(--lw-amber); }
    .lw-char-counter.danger { color: var(--lw-red); }

    .lw-tl-bar { display: flex; align-items: center; gap: 10px; margin-top: 12px; }
    .lw-tl-bar .tl-line { flex: 1; height: 6px; border-radius: 999px; background: var(--lw-grad); position: relative; overflow: hidden; }

    .lw-preview-rows { border-top: 1px dashed var(--lw-border); margin-top: 12px; padding-top: 10px; }
</style>

@php
    $isLocked = $sesiLomba->is_haflah_selesai;
@endphp

<div class="lw-mod jd-page-sesilomba">

<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-pencil-square"></i></span>
            <div>
                <h1 class="lw-hero-title">Edit: {{ $sesiLomba->nama }}</h1>
                <p class="lw-hero-sub">{{ $isLocked ? 'Sesi ini hanya dapat dilihat karena Haflah telah selesai.' : 'Ubah jadwal sesi lomba. Validasi backend tetap berlaku.' }}</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-tag-fill"></i> {{ $sesiLomba->nama }}</span>
                    @if($isLocked)
                        <span class="lw-hero-badge lw-hero-badge--lock"><i class="bi bi-lock-fill"></i> Haflah Selesai</span>
                    @else
                        <span class="lw-hero-badge lw-hero-badge--ok"><i class="bi bi-check2-circle"></i> Dapat Diedit</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('sesi-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<div class="lw-card lw-card-pad lw-form-card">
    <div class="lw-breadcrumb">
        <a href="{{ route('sesi-lomba.index') }}">Sesi Lomba</a> <i class="bi bi-chevron-right"></i> <span>Edit Sesi Lomba</span>
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
                <b>Sesi Terkunci</b>
                <p style="margin:2px 0 0;">Haflatul Imtihan sudah <b>Selesai</b>. Seluruh field bersifat readonly dan tidak dapat disimpan. Gunakan halaman ini hanya untuk melihat arsip.</p>
            </div>
        </div>
    @endif

    <form action="{{ route('sesi-lomba.update', $sesiLomba->id) }}" method="POST" id="slEditForm" novalidate>
        @csrf @method('PUT')

        <div class="lw-form-section"><i class="bi bi-building-fill"></i> Haflatul Imtihan</div>

        <div class="lw-field" style="margin-bottom:16px;">
            <label class="lw-field-label" for="haflah_id">Haflatul Imtihan</label>
            <select name="haflah_id" id="haflah_id" class="lw-select @error('haflah_id') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                <option value="">-- Pilih Haflatul Imtihan --</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ old('haflah_id', $sesiLomba->haflah_id) == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
            @error('haflah_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
            @if($isLocked)<div class="lw-help-text"><i class="bi bi-lock-fill"></i> Haflah tidak dapat diubah karena sudah selesai.</div>@endif
        </div>

        <div class="lw-form-section"><i class="bi bi-tag-fill"></i> Nama Sesi</div>

        <div class="lw-field" style="margin-bottom:16px;">
            <label class="lw-field-label" for="nama_sesi">Nama Sesi</label>
            <select name="nama" id="nama_sesi" class="lw-select @error('nama') is-invalid @enderror" {{ $isLocked ? 'disabled' : '' }}>
                <option value="">-- Pilih Nama Sesi --</option>
                @foreach($sesis as $s)
                    <option value="{{ $s->nama }}"
                        data-tanggal="{{ $s->tanggal }}"
                        data-jam-mulai="{{ $s->jam_mulai }}"
                        data-jam-selesai="{{ $s->jam_selesai }}"
                        {{ old('nama', $sesiLomba->nama) == $s->nama ? 'selected' : '' }}>
                        {{ $s->nama }}@if($s->jam_mulai || $s->jam_selesai) ({{ $s->jam_mulai ? \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') : '??' }}-{{ $s->jam_selesai ? \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') : '??' }})@endif
                    </option>
                @endforeach
            </select>
            @error('nama')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
            <div class="lw-help-text">Pilih template sesi — tanggal dan jam akan terisi otomatis.</div>
        </div>

        <div id="detail-sesi" class="row g-4" style="display:none; margin-bottom:16px;">
            <div class="col-md-6">
                <div class="lw-field">
                    <label class="lw-field-label" for="input_tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="input_tanggal" class="lw-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $sesiLomba->tanggal) }}" readonly>
                    @error('tanggal')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="lw-field">
                    <label class="lw-field-label" for="jam_mulai">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="lw-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', $sesiLomba->jam_mulai) }}" readonly>
                    @error('jam_mulai')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="lw-field">
                    <label class="lw-field-label" for="jam_selesai">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="lw-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', $sesiLomba->jam_selesai) }}" readonly>
                    @error('jam_selesai')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="lw-form-section"><i class="bi bi-align-left"></i> Keterangan</div>

        <div class="lw-field" style="margin-bottom:20px;">
            <label class="lw-field-label" for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="lw-control @error('keterangan') is-invalid @enderror" rows="4" placeholder="Masukkan keterangan (opsional)" maxlength="500" {{ $isLocked ? 'readonly' : '' }}>{{ old('keterangan', $sesiLomba->keterangan) }}</textarea>
            @error('keterangan')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
            <div class="lw-char-counter" id="charCounter">0 / 500 karakter</div>
        </div>

        <div class="lw-preview {{ $isLocked ? '' : 'has-date' }}" style="border-color:var(--lw-border);">
            <div class="lw-preview-icon"><i class="bi bi-eye-fill"></i></div>
            <div style="flex:1;min-width:0;">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="lw-preview-name">Live Preview</div>
                    <span class="lw-chip {{ $isLocked ? 'lw-chip--navy' : 'lw-chip--green' }}">
                        <i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-check2-circle' }}"></i>{{ $isLocked ? 'Terkunci' : 'Dapat Diedit' }}
                    </span>
                </div>
                <div class="lw-preview-rows">
                    <div class="lw-breakdown-item" style="background:transparent;border:none;padding:5px 0;">
                        <span class="lw-breakdown-name"><i class="bi bi-calendar-event"></i> Tanggal</span>
                        <span class="lw-breakdown-val" data-preview="tanggal">{{ \Carbon\Carbon::parse(old('tanggal', $sesiLomba->tanggal))->isoFormat('D MMM YYYY') }}</span>
                    </div>
                    <div class="lw-breakdown-item" style="background:transparent;border:none;padding:5px 0;">
                        <span class="lw-breakdown-name"><i class="bi bi-play-fill"></i> Jam Mulai</span>
                        <span class="lw-breakdown-val" data-preview="jam_mulai">{{ old('jam_mulai', $sesiLomba->jam_mulai) ? \Carbon\Carbon::parse(old('jam_mulai', $sesiLomba->jam_mulai))->format('H:i') : '-' }}</span>
                    </div>
                    <div class="lw-breakdown-item" style="background:transparent;border:none;padding:5px 0;">
                        <span class="lw-breakdown-name"><i class="bi bi-stop-fill"></i> Jam Selesai</span>
                        <span class="lw-breakdown-val" data-preview="jam_selesai">{{ old('jam_selesai', $sesiLomba->jam_selesai) ? \Carbon\Carbon::parse(old('jam_selesai', $sesiLomba->jam_selesai))->format('H:i') : '-' }}</span>
                    </div>
                </div>
                <div class="lw-tl-bar">
                    <span class="lw-chip lw-chip--green"><i class="bi bi-play-fill"></i> Mulai</span>
                    <div class="tl-line"></div>
                    <span class="lw-chip lw-chip--navy"><i class="bi bi-stop-fill"></i> Selesai</span>
                </div>
            </div>
        </div>

        <div class="lw-wizard-nav">
            <a href="{{ route('sesi-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
            <span class="spacer"></span>
            <button type="submit" class="lw-btn lw-btn--solid" data-submit-button {{ $isLocked ? 'disabled' : '' }}>
                <span class="btn-label"><i class="bi bi-save"></i> {{ $isLocked ? 'Terkunci' : 'Simpan Perubahan' }}</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        </div>
    </form>
</div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('slEditForm');
    var submitBtn = document.querySelector('[data-submit-button]');
    var namaSesi = document.getElementById('nama_sesi');
    var detailSesi = document.getElementById('detail-sesi');
    var inputTanggal = document.getElementById('input_tanggal');
    var jamMulai = document.getElementById('jam_mulai');
    var jamSelesai = document.getElementById('jam_selesai');
    var ketEl = document.getElementById('keterangan');
    var counter = document.getElementById('charCounter');

    function fmtTanggalIndo(v) {
        if (!v) return 'Belum dipilih';
        try { return new Intl.DateTimeFormat('id-ID', {day:'numeric',month:'short',year:'numeric'}).format(new Date(v+'T00:00:00')); }
        catch(e) { return v; }
    }

    function updatePreview() {
        var tglVal = inputTanggal ? inputTanggal.value : '';
        var startVal = jamMulai ? jamMulai.value : '';
        var endVal = jamSelesai ? jamSelesai.value : '';
        var tglOut = document.querySelector('[data-preview="tanggal"]');
        var startOut = document.querySelector('[data-preview="jam_mulai"]');
        var endOut = document.querySelector('[data-preview="jam_selesai"]');
        if (tglOut) tglOut.textContent = fmtTanggalIndo(tglVal);
        if (startOut) startOut.textContent = startVal || '-';
        if (endOut) endOut.textContent = endVal || '-';
    }

    if (namaSesi) {
        namaSesi.addEventListener('change', function () {
            var opt = this.options[this.selectedIndex];
            if (!opt.value) {
                if (detailSesi) detailSesi.style.display = 'none';
                [inputTanggal, jamMulai, jamSelesai].forEach(function (el) { if (el) { el.removeAttribute('readonly'); el.value = ''; } });
                updatePreview();
                return;
            }
            if (inputTanggal) inputTanggal.value = opt.dataset.tanggal || '';
            if (jamMulai) jamMulai.value = opt.dataset.jamMulai || '';
            if (jamSelesai) jamSelesai.value = opt.dataset.jamSelesai || '';
            [inputTanggal, jamMulai, jamSelesai].forEach(function (el) { if (el) el.setAttribute('readonly', 'readonly'); });
            if (detailSesi) detailSesi.style.display = 'block';
            updatePreview();
        });
        if (namaSesi.selectedIndex > 0) namaSesi.dispatchEvent(new Event('change'));
    }

    if (ketEl && counter) {
        ketEl.addEventListener('input', function () {
            var len = (ketEl.value || '').length;
            counter.textContent = len + ' / 500 karakter';
            counter.className = 'lw-char-counter' + (len > 450 ? ' danger' : (len > 350 ? ' warn' : ''));
        });
        ketEl.dispatchEvent(new Event('input'));
    }

    if (form && submitBtn && !submitBtn.disabled) {
        form.addEventListener('submit', function () {
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
