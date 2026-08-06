@extends('layouts.main')
@section('title', 'Tambah Sesi Lomba')
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

    .lw-haflah-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; }
</style>

@php
    $haflahAktif = \App\Models\HaflatulImtihan::with('tahunAjaran')->find(session('haflah_id'));
    $haflahNama = $haflahAktif->nama_acara ?? '-';
    $haflahStatus = $haflahAktif->status ?? '-';
    $haflahTglMulai = $haflahAktif->tanggal_mulai ?? null;
    $haflahTglSelesai = $haflahAktif->tanggal_selesai ?? null;
    $tahunAjaran = $haflahAktif->tahunAjaran->tahun_ajaran ?? '-';
@endphp

<div class="lw-mod jd-page-sesilomba">

<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-plus-lg"></i></span>
            <div>
                <h1 class="lw-hero-title">Tambah Sesi Lomba</h1>
                <p class="lw-hero-sub">Isi 3 langkah singkat untuk membuat sesi lomba baru yang siap dipakai.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-building-fill"></i> {{ $haflahNama }}</span>
                    <span class="lw-hero-badge lw-hero-badge--ok"><i class="bi bi-play-circle-fill"></i> Status: {{ $haflahStatus }}</span>
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
        <a href="{{ route('sesi-lomba.index') }}">Sesi Lomba</a> <i class="bi bi-chevron-right"></i> <span>Tambah Sesi Lomba</span>
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

    <form action="{{ route('sesi-lomba.store') }}" method="POST" id="slCreateForm" novalidate>
        @csrf
        <input type="hidden" name="haflah_id" value="{{ $haflahAktif->id ?? '' }}">

        {{-- STEP INDICATORS --}}
        <div class="lw-stepper">
            <div class="lw-step completed" data-step="1">
                <div class="lw-step-dot">1</div>
                <div class="lw-step-txt"><b>Informasi Haflah</b><span>Data haflah aktif</span></div>
            </div>
            <div class="lw-step-line done"></div>
            <div class="lw-step active" data-step="2">
                <div class="lw-step-dot">2</div>
                <div class="lw-step-txt"><b>Pilih Sesi</b><span>Template jadwal</span></div>
            </div>
            <div class="lw-step-line"></div>
            <div class="lw-step" data-step="3">
                <div class="lw-step-dot">3</div>
                <div class="lw-step-txt"><b>Keterangan &amp; Simpan</b><span>Konfirmasi</span></div>
            </div>
        </div>

        {{-- STEP 1: Haflah Info --}}
        <div class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-building-fill"></i> Informasi Haflah</div>
            <div class="lw-haflah-info">
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-building"></i> Nama Haflah</div>
                    <div class="val">{{ $haflahNama }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-mortarboard-fill"></i> Tahun Ajaran</div>
                    <div class="val">{{ $tahunAjaran }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-flag-fill"></i> Status</div>
                    <div class="val">
                        <span class="lw-chip {{ $haflahStatus === 'Selesai' ? 'lw-chip--navy' : 'lw-chip--green' }}">
                            <i class="bi {{ $haflahStatus === 'Selesai' ? 'bi-archive-fill' : 'bi-play-circle-fill' }}"></i>{{ $haflahStatus }}
                        </span>
                    </div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-calendar-range"></i> Rentang Tanggal</div>
                    <div class="val" style="font-size:12.5px;">
                        {{ $haflahTglMulai ? \Carbon\Carbon::parse($haflahTglMulai)->isoFormat('D MMM YYYY') : '-' }}
                        &mdash;
                        {{ $haflahTglSelesai ? \Carbon\Carbon::parse($haflahTglSelesai)->isoFormat('D MMM YYYY') : '-' }}
                    </div>
                </div>
            </div>
            <div class="lw-lock-note" style="margin-top:10px;"><i class="bi bi-lock-fill"></i> Menggunakan haflatul imtihan yang sedang aktif. Data ini tidak dapat diubah di halaman ini.</div>
            <div class="lw-wizard-nav">
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" data-sl-next="2">Lanjut ke Pilih Sesi <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- STEP 2: Pilih Sesi --}}
        <div class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-tag-fill"></i> Pilih Sesi</div>

            <div class="lw-field" style="margin-bottom:14px;">
                <label class="form-label" for="nama_sesi">Nama Sesi</label>
                <select name="nama" id="nama_sesi" class="lw-select @error('nama') is-invalid @enderror">
                    <option value="">-- Pilih Nama Sesi --</option>
                    @foreach($sesis as $s)
                        <option value="{{ $s->nama }}"
                            data-tanggal="{{ $s->tanggal }}"
                            data-jam-mulai="{{ $s->jam_mulai }}"
                            data-jam-selesai="{{ $s->jam_selesai }}"
                            {{ old('nama') == $s->nama ? 'selected' : '' }}>
                            {{ $s->nama }}@if($s->jam_mulai || $s->jam_selesai) ({{ $s->jam_mulai ? \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') : '??' }}-{{ $s->jam_selesai ? \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') : '??' }})@endif
                        </option>
                    @endforeach
                </select>
                @error('nama')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                <div class="lw-help-text">Pilih template sesi — tanggal dan jam akan terisi otomatis.</div>
            </div>

            <div id="detail-sesi" style="display:none;">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label" for="input_tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="input_tanggal" class="lw-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" readonly>
                        @error('tanggal')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="lw-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" readonly>
                        @error('jam_mulai')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="lw-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" readonly>
                        @error('jam_selesai')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="lw-warn-card d-none" id="dateRangeWarning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>Tanggal yang dipilih berada di luar rentang Haflah (<strong>{{ $haflahTglMulai ? \Carbon\Carbon::parse($haflahTglMulai)->isoFormat('D MMM YYYY') : '-' }} &mdash; {{ $haflahTglSelesai ? \Carbon\Carbon::parse($haflahTglSelesai)->isoFormat('D MMM YYYY') : '-' }}</strong>). Backend akan menolak data ini.</div>
                </div>

                <div class="lw-preview" id="slLivePreview" style="margin-top:16px;">
                    <div class="lw-preview-icon"><i class="bi bi-eye-fill"></i></div>
                    <div>
                        <div class="lw-preview-name">Live Preview</div>
                        <div class="lw-preview-meta">
                            <span><i class="bi bi-calendar-event"></i> <b data-preview="tanggal">Belum dipilih</b></span>
                            <span><i class="bi bi-play-fill"></i> <b data-preview="jam_mulai">-</b></span>
                            <span><i class="bi bi-stop-fill"></i> <b data-preview="jam_selesai">-</b></span>
                            <span><i class="bi bi-hourglass-split"></i> <b data-preview="durasi">-</b></span>
                        </div>
                    </div>
                </div>
                <div class="lw-tl-bar">
                    <span class="lw-chip lw-chip--green"><i class="bi bi-play-fill"></i> Mulai</span>
                    <div class="tl-line"></div>
                    <span class="lw-chip lw-chip--navy"><i class="bi bi-stop-fill"></i> Selesai</span>
                </div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" data-sl-prev="1"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" data-sl-next="3">Lanjut ke Keterangan <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- STEP 3: Keterangan & Submit --}}
        <div class="lw-wizard-pane" data-pane="3">
            <div class="lw-form-section"><i class="bi bi-align-left"></i> Keterangan &amp; Simpan</div>

            <div class="lw-field" style="margin-bottom:16px;">
                <label class="form-label" for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="lw-control @error('keterangan') is-invalid @enderror" rows="4" placeholder="Masukkan keterangan sesi (opsional)" maxlength="500">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                <div class="lw-char-counter" id="charCounter">0 / 500 karakter</div>
            </div>

            <div class="lw-preview" id="slSummary" style="border-color:var(--lw-green-border);border-left-color:var(--lw-green);">
                <div class="lw-preview-icon"><i class="bi bi-clipboard-check-fill"></i></div>
                <div>
                    <div class="lw-preview-name">Ringkasan Sesi</div>
                    <div class="lw-preview-meta">
                        <span><i class="bi bi-building-fill"></i> <b>{{ $haflahNama }}</b></span>
                        <span><i class="bi bi-tag-fill"></i> <b id="summaryNama">-</b></span>
                        <span><i class="bi bi-calendar-event"></i> <b id="summaryTanggal">-</b></span>
                        <span><i class="bi bi-clock"></i> <b id="summaryJam">-</b></span>
                    </div>
                </div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" data-sl-prev="2"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button>
                    <span class="btn-label"><i class="bi bi-save"></i> Simpan Sesi Lomba</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </form>
</div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('slCreateForm');
    var submitBtn = document.querySelector('[data-submit-button]');

    function goToStep(n) {
        document.querySelectorAll('.lw-wizard-pane').forEach(function (el) { el.classList.remove('is-show'); });
        var pane = document.querySelector('.lw-wizard-pane[data-pane="' + n + '"]');
        if (pane) pane.classList.add('is-show');
        document.querySelectorAll('.lw-step').forEach(function (el) {
            var s = parseInt(el.dataset.step, 10);
            el.classList.remove('active', 'done');
            if (s < n) el.classList.add('done');
            if (s === n) el.classList.add('active');
        });
        document.querySelectorAll('.lw-step-line').forEach(function (el, i) {
            el.classList.toggle('done', (i + 1) < n);
        });
    }
    document.querySelectorAll('[data-sl-next]').forEach(function (btn) {
        btn.addEventListener('click', function () { goToStep(parseInt(btn.dataset.slNext, 10)); });
    });
    document.querySelectorAll('[data-sl-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () { goToStep(parseInt(btn.dataset.slPrev, 10)); });
    });

    var namaSesi = document.getElementById('nama_sesi');
    var detailSesi = document.getElementById('detail-sesi');
    var inputTanggal = document.getElementById('input_tanggal');
    var jamMulai = document.getElementById('jam_mulai');
    var jamSelesai = document.getElementById('jam_selesai');
    var dateWarning = document.getElementById('dateRangeWarning');

    var haflahTglMulai = '{{ $haflahTglMulai }}';
    var haflahTglSelesai = '{{ $haflahTglSelesai }}';

    function fmtTanggalIndo(v) {
        if (!v) return 'Belum dipilih';
        try { return new Intl.DateTimeFormat('id-ID', {day:'numeric',month:'short',year:'numeric'}).format(new Date(v+'T00:00:00')); }
        catch(e) { return v; }
    }

    function calcDurasi(start, end) {
        if (!start || !end) return '-';
        var s = new Date('1970-01-01T'+start+':00');
        var e = new Date('1970-01-01T'+end+':00');
        var m = (e - s) / 60000;
        if (m <= 0) return '-';
        return Math.floor(m/60) + ' jam ' + (m%60) + ' menit';
    }

    function updatePreview() {
        var tglVal = inputTanggal && inputTanggal.value || '';
        var startVal = jamMulai && jamMulai.value || '';
        var endVal = jamSelesai && jamSelesai.value || '';

        var tglOut = document.querySelector('[data-preview="tanggal"]');
        var startOut = document.querySelector('[data-preview="jam_mulai"]');
        var endOut = document.querySelector('[data-preview="jam_selesai"]');
        var durasiOut = document.querySelector('[data-preview="durasi"]');
        var sumNama = document.getElementById('summaryNama');
        var sumTanggal = document.getElementById('summaryTanggal');
        var sumJam = document.getElementById('summaryJam');

        if (tglOut) tglOut.textContent = fmtTanggalIndo(tglVal);
        if (startOut) startOut.textContent = startVal || '-';
        if (endOut) endOut.textContent = endVal || '-';
        if (durasiOut) durasiOut.textContent = calcDurasi(startVal, endVal);
        if (sumNama) sumNama.textContent = (namaSesi && namaSesi.value) || '-';
        if (sumTanggal) sumTanggal.textContent = fmtTanggalIndo(tglVal);
        if (sumJam) sumJam.textContent = (startVal || '??') + ' - ' + (endVal || '??');

        if (dateWarning && tglVal && haflahTglMulai && haflahTglSelesai) {
            dateWarning.classList.toggle('d-none', !(tglVal < haflahTglMulai || tglVal > haflahTglSelesai));
        }
    }

    if (namaSesi) {
        namaSesi.addEventListener('change', function () {
            var opt = this.options[this.selectedIndex];
            var val = opt.value;
            if (!val) {
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

        if (namaSesi.selectedIndex > 0) {
            namaSesi.dispatchEvent(new Event('change'));
        }
    }

    var ketEl = document.getElementById('keterangan');
    var counter = document.getElementById('charCounter');
    if (ketEl && counter) {
        ketEl.addEventListener('input', function () {
            var len = (ketEl.value || '').length;
            counter.textContent = len + ' / 500 karakter';
            counter.className = 'lw-char-counter' + (len > 450 ? ' danger' : (len > 350 ? ' warn' : ''));
        });
        ketEl.dispatchEvent(new Event('input'));
    }

    if (form && submitBtn) {
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
