@extends('layouts.main')
@section('title', 'Tambah Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-form-card { max-width: 860px; }
    .lw-breadcrumb { margin-bottom: 16px; }

    .lw-range-preview { font-size: 12px; color: var(--lw-text-3); font-weight: 600; margin-top: 8px; }
    .lw-range-preview i { margin-right: 4px; }
</style>

@php
    $haflahAktif = \App\Models\HaflatulImtihan::with('tahunAjaran')->find(session('haflah_id'));
    $haflahNama = $haflahAktif->nama_acara ?? '-';
    $haflahStatus = $haflahAktif->status ?? '-';
    $tahunAjaran = $haflahAktif->tahunAjaran->tahun_ajaran ?? '-';
@endphp

<div class="lw-mod jd-page-lomba-create">

<div class="lw-card lw-card-pad lw-form-card" style="margin:0 auto;">
    <div class="lw-breadcrumb">
        <a href="{{ route('lomba.index') }}">Lomba</a> <i class="bi bi-chevron-right"></i> <span>Tambah Lomba</span>
    </div>

    <div class="lw-hero">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-trophy-fill"></i></span>
                <div>
                    <h1 class="lw-hero-title">Tambah Lomba</h1>
                    <p class="lw-hero-sub">Isi 4 langkah untuk membuat lomba baru yang siap dikelola.</p>
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

    <form action="{{ route('lomba.store') }}" method="POST" id="lombaCreateForm" novalidate>
        @csrf
        <input type="hidden" name="haflah_id" value="{{ session('haflah_id') }}">

        {{-- STEPPER --}}
        <div class="lw-stepper">
            <div class="lw-step active" data-step="1">
                <div class="lw-step-dot">1</div>
                <div class="lw-step-txt"><b>Haflah</b><span>Informasi haflah</span></div>
            </div>
            <div class="lw-step-line"></div>
            <div class="lw-step" data-step="2">
                <div class="lw-step-dot">2</div>
                <div class="lw-step-txt"><b>Lomba</b><span>Detail lomba</span></div>
            </div>
            <div class="lw-step-line"></div>
            <div class="lw-step" data-step="3">
                <div class="lw-step-dot">3</div>
                <div class="lw-step-txt"><b>Peserta</b><span>Aturan kelas</span></div>
            </div>
            <div class="lw-step-line"></div>
            <div class="lw-step" data-step="4">
                <div class="lw-step-dot">4</div>
                <div class="lw-step-txt"><b>Simpan</b><span>Review &amp; simpan</span></div>
            </div>
        </div>

        {{-- STEP 1: HAFLATUL IMTHAN --}}
        <div class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-building-fill"></i> Informasi Haflah</div>
            <p class="lw-help-text" style="margin:-8px 0 14px;">Lomba akan otomatis terhubung ke Haflatul Imtihan yang sedang aktif.</p>
            <div class="lw-info-grid" style="margin-bottom:16px;">
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-building"></i> Nama Haflah</div>
                    <div class="val">{{ $haflahNama }}</div>
                </div>
                <div class="lw-info-cell">
                    <div class="lbl"><i class="bi bi-calendar3"></i> Tahun Ajaran</div>
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
            </div>
            <div class="lw-lock-note" style="margin-bottom:16px;"><i class="bi bi-lock-fill"></i> Data haflah tidak dapat diubah di halaman ini.</div>
            <div class="lw-wizard-nav">
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" data-lw-next="2">Lanjut ke Lomba <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- STEP 2: LOMBA INFO --}}
        <div class="lw-wizard-pane" data-pane="2">
            <div class="row g-4" style="margin-bottom:8px;">
                <div class="col-md-8">
                    <div class="lw-field">
                        <label class="lw-field-label" for="nama">Nama Lomba</label>
                        <input type="text" id="nama" name="nama" class="lw-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Musabaqah Hifzhil Qur'an" maxlength="255">
                        @error('nama')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lw-field">
                        <label class="lw-field-label" for="jenis">Jenis</label>
                        <select id="jenis" name="jenis" class="lw-select @error('jenis') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="Individu" {{ old('jenis') == 'Individu' ? 'selected' : '' }}>Individu</option>
                            <option value="Tim" {{ old('jenis') == 'Tim' ? 'selected' : '' }}>Tim</option>
                        </select>
                        @error('jenis')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="lw-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}" placeholder="Ruang kelas, lapangan, dsb." maxlength="255">
                        @error('lokasi')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="sesi_lomba_id">Sesi Lomba</label>
                        <select id="sesi_lomba_id" name="sesi_lomba_id" class="lw-select @error('sesi_lomba_id') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach($sesiLombas as $sl)
                                <option value="{{ $sl->id }}" {{ old('sesi_lomba_id') == $sl->id ? 'selected' : '' }}>{{ $sl->nama }}</option>
                            @endforeach
                        </select>
                        @error('sesi_lomba_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="status">Status</label>
                        <select id="status" name="status" class="lw-select @error('status') is-invalid @enderror">
                            <option value="Belum Mulai" {{ old('status') == 'Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                            <option value="Berlangsung" {{ old('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" data-lw-prev="1"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" data-lw-next="3">Lanjut ke Peserta <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- STEP 3: PESERTA RULES --}}
        <div class="lw-wizard-pane" data-pane="3">
            <div class="lw-form-section"><i class="bi bi-mortarboard-fill"></i> Range Kelas Peserta</div>
            <div class="row g-4" style="margin-bottom:8px;">
                <div class="col-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="kelas_min">Dari Kelas Minimum</label>
                        <select id="kelas_min" name="kelas_min" class="lw-select @error('kelas_min') is-invalid @enderror">
                            <option value="">Semua Kelas</option>
                            @foreach($tingkatList as $t)
                                <option value="{{ $t }}" {{ old('kelas_min') == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                            @endforeach
                        </select>
                        @error('kelas_min')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="kelas_max">Sampai Kelas Maksimum</label>
                        <select id="kelas_max" name="kelas_max" class="lw-select @error('kelas_max') is-invalid @enderror">
                            <option value="">Semua Kelas</option>
                            @foreach($tingkatList as $t)
                                <option value="{{ $t }}" {{ old('kelas_max') == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                            @endforeach
                        </select>
                        @error('kelas_max')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="lw-range-preview" id="rangePreview"><i class="bi bi-info-circle"></i> Kosongkan kedua pilihan jika semua kelas boleh ikut.</div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" data-lw-prev="2"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" data-lw-next="4">Lanjut ke Simpan <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- STEP 4: REVIEW & SAVE --}}
        <div class="lw-wizard-pane" data-pane="4">
            <div class="lw-form-section"><i class="bi bi-align-left"></i> Deskripsi</div>
            <div class="lw-field" style="margin-bottom:16px;">
                <label class="lw-field-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="lw-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Deskripsi lomba (opsional)">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
            </div>

            <div class="lw-preview has-date" style="margin-bottom:8px;">
                <div class="lw-preview-icon"><i class="bi bi-clipboard-check-fill"></i></div>
                <div style="flex:1;min-width:0;">
                    <div class="lw-preview-name">Ringkasan Lomba</div>
                    <div class="lw-preview-meta" style="margin-top:6px;">
                        <span><i class="bi bi-building-fill"></i> <b>{{ $haflahNama }}</b></span>
                        <span><i class="bi bi-trophy-fill"></i> <b id="summaryNama">-</b></span>
                        <span><i class="bi bi-layer-fill"></i> <b id="summaryJenis">-</b></span>
                        <span><i class="bi bi-flag-fill"></i> <b id="summaryStatus">-</b></span>
                        <span><i class="bi bi-mortarboard-fill"></i> <b id="summaryRange">Semua Kelas</b></span>
                    </div>
                </div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" data-lw-prev="3"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button>
                    <span class="btn-label"><i class="bi bi-save"></i> Simpan Lomba</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </form>
</div>

</div>

@push('scripts')
<script>
window.goStep = function(n) {
    document.querySelectorAll('.lw-wizard-pane').forEach(function(el) { el.classList.remove('is-show'); });
    var pane = document.querySelector('.lw-wizard-pane[data-pane="' + n + '"]');
    if (pane) pane.classList.add('is-show');
    document.querySelectorAll('.lw-step').forEach(function(el) {
        var s = parseInt(el.dataset.step, 10);
        el.classList.remove('active', 'done');
        if (s < n) el.classList.add('done');
        if (s === n) el.classList.add('active');
    });
    document.querySelectorAll('.lw-step-line').forEach(function(el, i) {
        el.classList.toggle('done', (i + 1) < n);
    });
};
document.querySelectorAll('[data-lw-next]').forEach(function(btn) {
    btn.addEventListener('click', function() { goStep(parseInt(btn.dataset.lwNext, 10)); });
});
document.querySelectorAll('[data-lw-prev]').forEach(function(btn) {
    btn.addEventListener('click', function() { goStep(parseInt(btn.dataset.lwPrev, 10)); });
});

(function() {
    var form = document.getElementById('lombaCreateForm');
    var submitBtn = document.querySelector('[data-submit-button]');

    var kelasMin = document.getElementById('kelas_min');
    var kelasMax = document.getElementById('kelas_max');
    var preview = document.getElementById('rangePreview');

    function updateRange() {
        var min = kelasMin.value, max = kelasMax.value;
        if (!min && !max) { preview.innerHTML = '<i class="bi bi-info-circle"></i> Semua kelas boleh mengikuti lomba ini.'; }
        else if (min && !max) { preview.innerHTML = '<i class="bi bi-filter"></i> Peserta minimal dari <strong>Kelas ' + min + '</strong> ke atas.'; }
        else if (!min && max) { preview.innerHTML = '<i class="bi bi-filter"></i> Peserta maksimal sampai <strong>Kelas ' + max + '</strong>.'; }
        else if (parseInt(min) > parseInt(max)) { preview.innerHTML = '<i class="bi bi-exclamation-triangle-fill" style="color:var(--lw-red);"></i> <span style="color:var(--lw-red);">Nilai maks harus &ge; nilai min.</span>'; }
        else { preview.innerHTML = '<i class="bi bi-check-circle-fill" style="color:var(--lw-green);"></i> Peserta dari <strong>Kelas ' + min + '</strong> sampai <strong>Kelas ' + max + '</strong>.'; }
    }
    if (kelasMin) kelasMin.addEventListener('change', updateRange);
    if (kelasMax) kelasMax.addEventListener('change', updateRange);
    updateRange();

    function syncSummary() {
        var nama = document.getElementById('nama') ? document.getElementById('nama').value : '-';
        var jenis = document.getElementById('jenis') ? document.getElementById('jenis').value : '-';
        var status = document.getElementById('status') ? document.getElementById('status').value : '-';
        var min = document.getElementById('kelas_min') ? document.getElementById('kelas_min').value : '';
        var max = document.getElementById('kelas_max') ? document.getElementById('kelas_max').value : '';

        var sn = document.getElementById('summaryNama');
        var sj = document.getElementById('summaryJenis');
        var ss = document.getElementById('summaryStatus');
        var sr = document.getElementById('summaryRange');

        if (sn) sn.textContent = nama;
        if (sj) sj.textContent = jenis;
        if (ss) ss.textContent = status;
        if (sr) {
            if (!min && !max) sr.textContent = 'Semua Kelas';
            else if (min && !max) sr.textContent = 'Dari Kelas ' + min;
            else if (!min && max) sr.textContent = 's/d Kelas ' + max;
            else sr.textContent = 'Kelas ' + min + ' - ' + max;
        }
    }
    ['nama','jenis','status','kelas_min','kelas_max'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.addEventListener('input', syncSummary); el.addEventListener('change', syncSummary); }
    });
    syncSummary();

    if (form && submitBtn) {
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
