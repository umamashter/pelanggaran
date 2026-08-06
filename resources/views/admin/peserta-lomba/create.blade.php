@extends('layouts.main')
@section('title', 'Tambah Peserta Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .lw-form-card { max-width: 900px; margin: 0 auto; }

    .lw-pick-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .lw-pick-card { position: relative; display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 15px;
        border: 1.5px solid var(--lw-border); background: var(--lw-card); text-align: left; cursor: pointer; transition: all .22s ease; width: 100%; }
    .lw-pick-card:hover { border-color: var(--lw-primary-border); transform: translateY(-2px); box-shadow: var(--lw-shadow); }
    .lw-pick-card.sel { border-color: var(--lw-primary); background: var(--lw-primary-soft); box-shadow: 0 0 0 4px var(--lw-primary-soft); }
    .lw-pick-card .ic { width: 42px; height: 42px; border-radius: 12px; background: var(--lw-grad-soft); color: var(--lw-primary); display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; transition: all .22s ease; }
    .lw-pick-card.sel .ic { background: var(--lw-grad); color: #fff; }
    .lw-pick-card .info { flex: 1; min-width: 0; }
    .lw-pick-card .info h4 { font-size: 13.5px; font-weight: 800; margin: 0 0 5px; color: var(--lw-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .lw-pick-card .check { flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--lw-border); display: inline-flex; align-items: center; justify-content: center; color: transparent; font-size: 13px; transition: all .2s ease; }
    .lw-pick-card.sel .check { background: var(--lw-primary); border-color: var(--lw-primary); color: #fff; }

    .lw-overview { margin-top: 14px; border: 1px dashed var(--lw-primary-border); border-radius: 16px; padding: 16px 18px; background: linear-gradient(135deg, var(--lw-card), var(--lw-bg)); }

    .lw-loading { display: none; align-items: center; gap: 8px; font-size: 12px; color: var(--lw-text-3); margin-top: 8px; font-weight: 600; }
    .lw-loading.on { display: flex; }
    .lw-loading .spinner-border { width: 15px; height: 15px; border-width: 2px; color: var(--lw-primary); }

    .lw-searchable2 { position: relative; }
    .lw-searchable2 .search { position: absolute; right: 10px; top: 8px; z-index: 3; width: 150px; }
    .lw-searchable2 .search input { min-height: 30px; padding: 0 10px; font-size: 11.5px; border-radius: 8px; border: 1px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text); }
    .lw-searchable2 .search input:focus { border-color: var(--lw-primary); outline: none; }

    .lw-confirm { border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); overflow: hidden; }
    .lw-confirm-row { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-bottom: 1px solid var(--lw-border-soft); font-size: 13px; }
    .lw-confirm-row:last-child { border-bottom: 0; }
    .lw-confirm-row .k { width: 150px; font-size: 10.5px; font-weight: 800; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; flex-shrink: 0; display: flex; align-items: center; gap: 8px; }
    .lw-confirm-row .k i { color: var(--lw-primary); }
    .lw-confirm-row .v { font-weight: 700; color: var(--lw-text); }

    @media (max-width: 767.98px) {
        .lw-pick-grid { grid-template-columns: 1fr; }
        .lw-confirm-row .k { width: 110px; }
        .lw-searchable2 .search { position: static; width: 100%; margin-bottom: 6px; }
    }
</style>

@php
    $pesertaPerLomba = \App\Models\PesertaLomba::withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)
        ->whereIn('lomba_id', $lombas->pluck('id'))
        ->selectRaw('lomba_id, count(*) c')->groupBy('lomba_id')->pluck('c', 'lomba_id');
    $sesiMap = \App\Models\SesiLomba::withoutGlobalScope(\App\Models\Scopes\HaflahScope::class)->get()->keyBy('id');
    $hasOld = old('lomba_id') !== null;
@endphp

<div class="lw-mod pl-mod">

<div class="lw-card lw-card-pad lw-form-card">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-person-plus-fill"></i></span>
                <div>
                    <h1 class="lw-hero-title">Tambah Peserta Lomba</h1>
                    <p class="lw-hero-sub">Wizard pendaftaran — pilih lomba, isi data peserta, lalu konfirmasi. Mendukung peserta individu maupun tim.</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="lw-stepper" id="stepper">
        <div class="lw-step active" data-step="1">
            <div class="lw-step-dot">1</div>
            <div class="lw-step-txt"><b>Pilih Lomba</b><span>Kategori kompetisi</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="2">
            <div class="lw-step-dot">2</div>
            <div class="lw-step-txt"><b>Data Peserta</b><span>Pilih peserta / tim</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="3">
            <div class="lw-step-dot">3</div>
            <div class="lw-step-txt"><b>Konfirmasi</b><span>Periksa &amp; simpan</span></div>
        </div>
    </div>

    @if ($errors->any())
        <div class="lw-alert lw-alert--err">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div style="flex:1;min-width:0;">
                <b>Periksa kembali form</b>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    <form action="{{ route('peserta-lomba.store') }}" method="POST" id="formPeserta" novalidate>
        @csrf

        {{-- STEP 1 : LOMBA --}}
        <section class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-trophy-fill"></i> Pilih Lomba</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Klik kartu lomba untuk memilih kategori pendaftaran.</p>

            @if($lombas->isEmpty())
                <div class="lw-empty">
                    <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-trophy"></i></div></div>
                    <div class="lw-empty-title">Belum Ada Lomba</div>
                    <p class="lw-empty-sub">Buat lomba terlebih dahulu melalui modul Lomba.</p>
                    <a href="{{ route('lomba.index') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Buat Lomba</a>
                </div>
            @else
            <div class="lw-pick-grid" id="lombaGrid">
                @foreach($lombas as $l)
                    <button type="button" class="lw-pick-card" data-id="{{ $l->id }}" data-jenis="{{ $l->jenis ?? 'Individu' }}"
                        data-kelas-min="{{ $l->kelas_min }}" data-kelas-max="{{ $l->kelas_max }}" data-nama="{{ $l->nama }}"
                        data-sesi="{{ $sesiMap->get($l->sesi_lomba_id)->nama ?? '-' }}" data-status="{{ $l->status }}"
                        data-peserta="{{ (int)($pesertaPerLomba[$l->id] ?? 0) }}" {{ old('lomba_id') == $l->id ? 'data-old' : '' }}>
                        <span class="ic"><i class="bi {{ ($l->jenis ?? '') === 'Tim' ? 'bi-people-fill' : 'bi-person-fill' }}"></i></span>
                        <span class="info">
                            <h4>{{ $l->nama }}</h4>
                            <span class="lw-chip {{ ($l->jenis ?? '') === 'Tim' ? 'lw-chip--violet' : 'lw-chip--navy' }} lw-chip-mini">{{ ($l->jenis ?? '') === 'Tim' ? 'Tim' : 'Individu' }}</span>
                            <span class="lw-chip {{ ($l->status ?? '') === 'Berlangsung' ? 'lw-chip--green' : 'lw-chip--amber' }} lw-chip-mini" style="margin-left:4px;">{{ $l->status ?? 'Belum Mulai' }}</span>
                        </span>
                        <span class="check"><i class="bi bi-check-lg"></i></span>
                    </button>
                @endforeach
            </div>

            <div class="lw-overview" id="overviewPanel" style="display:none;">
                <div class="lw-form-section" style="margin-bottom:12px;"><i class="bi bi-compass"></i> Competition Overview</div>
                <div class="lw-info-grid">
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-tag"></i>Lomba</div><div class="val" id="ovNama">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-diagram-3"></i>Jenis</div><div class="val" id="ovJenis">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-bar-chart-steps"></i>Rentang Kelas</div><div class="val" id="ovKelas">Semua</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-people"></i>Peserta Saat Ini</div><div class="val" id="ovPeserta">0</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-calendar-event"></i>Sesi</div><div class="val" id="ovSesi">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-flag"></i>Status Lomba</div><div class="val" id="ovStatus">-</div></div>
                </div>
            </div>
            @endif

            <div class="lw-wizard-nav">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="step1Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 2 : PESERTA --}}
        <section class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-person-check-fill"></i> Data Peserta</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Pilih status dan peserta yang akan didaftarkan.</p>

            <input type="hidden" name="lomba_id" id="lomba_id" value="{{ old('lomba_id') }}">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="status_select"><i class="bi bi-flag-fill"></i> Status</label>
                        <select name="status" id="status_select" class="lw-select @error('status') is-invalid @enderror">
                            @foreach(['Terdaftar', 'Hadir', 'Tidak Hadir', 'Diskualifikasi'] as $st)
                                <option value="{{ $st }}" {{ old('status', 'Terdaftar') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row g-3" id="fieldIndividu">
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="jenjang_id"><i class="bi bi-layers-fill"></i> Jenjang</label>
                        <select id="jenjang_id" class="lw-select">
                            <option value="">Pilih Jenjang</option>
                            @foreach($jenjangs as $j)
                                <option value="{{ $j->id }}" {{ old('jenjang_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenjang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="kelas_id"><i class="bi bi-door-open-fill"></i> Kelas</label>
                        <select id="kelas_id" class="lw-select" disabled>
                            <option value="">Pilih Kelas</option>
                        </select>
                    </div>
                    <div class="lw-loading" id="loadingKelas"><div class="spinner-border spinner-border-sm"></div><span>Memuat data kelas...</span></div>
                </div>
                <div class="col-12">
                    <label class="lw-field-label" for="student_id" style="margin-bottom:6px;"><i class="bi bi-person-vcard-fill"></i> Siswa</label>
                    <div class="lw-searchable2">
                        <div class="search"><input type="text" id="siswaSearch" placeholder="Cari NISN / nama..." autocomplete="off"></div>
                        <div class="lw-field">
                            <select name="student_id" id="student_id" class="lw-select @error('student_id') is-invalid @enderror" disabled>
                                <option value="">Pilih Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div class="lw-loading" id="loadingSiswa"><div class="spinner-border spinner-border-sm"></div><span>Memuat data siswa...</span></div>
                    @error('student_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    <div class="lw-help-text" style="margin-top:6px;" id="siswaInfo"></div>
                </div>
            </div>

            <div class="row g-3" id="fieldTim" style="display:none;">
                <div class="col-12">
                    <div class="lw-field">
                        <label class="lw-field-label" for="kelompok_id"><i class="bi bi-people-fill"></i> Kelompok</label>
                        <select name="kelompok_lomba_id" id="kelompok_id" class="lw-select @error('kelompok_lomba_id') is-invalid @enderror" disabled>
                            <option value="">Pilih Kelompok</option>
                        </select>
                    </div>
                    <div class="lw-loading" id="loadingKelompok"><div class="spinner-border spinner-border-sm"></div><span>Memuat kelompok...</span></div>
                    @error('kelompok_lomba_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            <div class="lw-preview" id="previewCard" style="display:none;">
                <div class="lw-preview-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <div style="flex:1;min-width:0;">
                    <div class="lw-preview-name" style="font-size:12px;letter-spacing:.4px;">LIVE PREVIEW — PESERTA YANG DIPILIH</div>
                    <div class="lw-preview-meta" style="margin-top:6px;">
                        <span><i class="bi bi-person-fill"></i><b id="pvNama">-</b></span>
                        <span id="pvMeta"></span>
                    </div>
                </div>
                <span class="lw-avatar" id="pvAva" style="background:var(--lw-grad);">?</span>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="step2Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="step2Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 3 : KONFIRMASI --}}
        <section class="lw-wizard-pane" data-pane="3">
            <div class="lw-form-section"><i class="bi bi-clipboard-check-fill"></i> Konfirmasi Pendaftaran</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Periksa kembali data sebelum disimpan.</p>

            <div class="lw-confirm">
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-trophy-fill"></i>Lomba</span><span class="v" id="cfLomba">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-diagram-3"></i>Jenis</span><span class="v" id="cfJenis">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-flag-fill"></i>Status</span><span class="v" id="cfStatus">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-person-vcard-fill"></i>Peserta</span><span class="v" id="cfPeserta">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-hash"></i>Nomor Urut</span><span class="v"><span class="lw-tag"><b style="color:var(--lw-text);">Otomatis</b></span></span></div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="step3Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button>
                    <span class="btn-label"><i class="bi bi-save"></i> Simpan Pendaftaran</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </section>

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
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

(function () {
    var state = { step: 1, id: null, jenis: null, min: null, max: null, nama: null, peserta: 0, sesi: null, status: null };

    function $id(id) { return document.getElementById(id); }
    var lombaId = $id('lomba_id');
    var step1Next = $id('step1Next'), step2Next = $id('step2Next');

    function showOverview() {
        $id('ovNama').textContent = state.nama;
        $id('ovJenis').textContent = state.jenis === 'Tim' ? 'Tim' : 'Individu';
        $id('ovKelas').textContent = fmtKelas(state.min, state.max);
        $id('ovPeserta').textContent = state.peserta;
        $id('ovSesi').textContent = state.sesi;
        $id('ovStatus').textContent = state.status;
        $id('overviewPanel').style.display = '';
    }

    function fmtKelas(min, max) {
        if (min && max) return 'Kelas ' + min + ' - ' + max;
        if (min) return 'Kelas ' + min + ' ke atas';
        if (max) return 'Sampai Kelas ' + max;
        return 'Semua';
    }

    function resetIndividu() {
        $id('jenjang_id').value = '';
        var kelas = $id('kelas_id'), siswa = $id('student_id');
        kelas.disabled = true; kelas.innerHTML = '<option value="">Pilih Kelas</option>';
        siswa.disabled = true; siswa.innerHTML = '<option value="">Pilih Siswa</option>';
        $id('siswaSearch').value = '';
        $id('loadingKelas').classList.remove('on');
        $id('loadingSiswa').classList.remove('on');
        $id('siswaInfo').textContent = '';
        hidePreview();
    }

    function hidePreview() { $id('previewCard').style.display = 'none'; }

    function updatePreview() {
        var isTim = state.jenis === 'Tim';
        var has = isTim ? !!$id('kelompok_id').value : !!$id('student_id').value;
        if (!has) { hidePreview(); return; }
        var ava = $id('pvAva');
        if (isTim) {
            var t = $id('kelompok_id').options[$id('kelompok_id').selectedIndex].text;
            ava.innerHTML = '<i class="bi bi-people-fill"></i>';
            $id('pvNama').textContent = t.replace(/^[^-]+ - /, '');
            $id('pvMeta').innerHTML = '<span><i class="bi bi-diagram-3"></i>Tim</span>';
        } else {
            var opt = $id('student_id').options[$id('student_id').selectedIndex];
            var label = opt.text;
            ava.innerHTML = '';
            ava.textContent = label.trim().charAt(0).toUpperCase();
            $id('pvNama').textContent = label.split(' - ')[1] || label;
            var nisn = label.split(' - ')[0] || '';
            var k = $id('kelas_id').options[$id('kelas_id').selectedIndex].text;
            var j = $id('jenjang_id').options[$id('jenjang_id').selectedIndex].text;
            $id('pvMeta').innerHTML = '<span><i class="bi bi-person-vcard-fill"></i>' + nisn + '</span><span><i class="bi bi-mortarboard-fill"></i>' + k + '</span><span><i class="bi bi-layers-fill"></i>' + j + '</span>';
        }
        $id('previewCard').style.display = '';
        step2Next.disabled = false;
    }

    function fetchJSON(url, cb) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(cb)
            .catch(function () { cb([]); });
    }

    /* ── Lomba cards ── */
    document.querySelectorAll('.lw-pick-card').forEach(function (card) {
        card.addEventListener('click', function () {
            document.querySelectorAll('.lw-pick-card').forEach(function (c) { c.classList.remove('sel'); });
            card.classList.add('sel');
            state.id = card.dataset.id; state.jenis = card.dataset.jenis;
            state.min = card.dataset.kelasMin || null; state.max = card.dataset.kelasMax || null;
            state.nama = card.dataset.nama; state.sesi = card.dataset.sesi;
            state.status = card.dataset.status; state.peserta = parseInt(card.dataset.peserta, 10) || 0;
            lombaId.value = state.id;
            showOverview();
            step1Next.disabled = false;
            resetIndividu();
            var kp = $id('kelompok_id'); kp.value = ''; kp.disabled = true;
        });
    });

    step1Next.addEventListener('click', function () {
        if (!state.id) return;
        goStep(2);
        if (state.jenis === 'Tim') { loadKelompok(); } else { toggleFields(); }
    });

    function toggleFields() {
        var isTim = state.jenis === 'Tim';
        $id('fieldIndividu').style.display = isTim ? 'none' : '';
        $id('fieldTim').style.display = isTim ? '' : 'none';
    }

    function loadKelompok() {
        var kp = $id('kelompok_id');
        kp.disabled = true; kp.innerHTML = '<option value="">Memuat...</option>';
        $id('loadingKelompok').classList.add('on');
        fetchJSON('{{ url('get-kelompok') }}/' + state.id, function (data) {
            var o = '<option value="">Pilih Kelompok</option>';
            data.forEach(function (v) { o += '<option value="' + v.id + '">' + v.text + '</option>'; });
            kp.innerHTML = o; kp.disabled = false;
            $id('loadingKelompok').classList.remove('on');
            step2Next.disabled = true;
        });
    }

    /* ── Jenjang → Kelas → Siswa ── */
    $id('jenjang_id').addEventListener('change', function () {
        var id = this.value;
        var kelas = $id('kelas_id'), siswa = $id('student_id');
        kelas.value = ''; kelas.disabled = true; kelas.innerHTML = '<option value="">Pilih Kelas</option>';
        siswa.value = ''; siswa.disabled = true; siswa.innerHTML = '<option value="">Pilih Siswa</option>';
        $id('siswaSearch').value = ''; hidePreview(); step2Next.disabled = true;
        if (!id) return;
        $id('loadingKelas').classList.add('on');
        fetchJSON('{{ url('get-kelas') }}/' + id, function (data) {
            var o = '<option value="">Pilih Kelas</option>';
            data.forEach(function (v) {
                var t = parseInt(v.tingkat, 10);
                var ok = !state.min || t >= parseInt(state.min, 10);
                var ok2 = !state.max || t <= parseInt(state.max, 10);
                if (ok && ok2) o += '<option value="' + v.id + '">' + v.nama_kelas + ' (Tingkat ' + v.tingkat + ')</option>';
            });
            kelas.innerHTML = o; kelas.disabled = false;
            $id('loadingKelas').classList.remove('on');
        });
    });

    $id('kelas_id').addEventListener('change', function () {
        var id = this.value;
        var siswa = $id('student_id');
        siswa.value = ''; siswa.disabled = true; siswa.innerHTML = '<option value="">Pilih Siswa</option>';
        $id('siswaSearch').value = ''; hidePreview(); step2Next.disabled = true;
        if (!id) return;
        $id('loadingSiswa').classList.add('on');
        fetchJSON('{{ url('get-siswa') }}/' + id + '?lomba_id=' + state.id, function (data) {
            var o = '<option value="">Pilih Siswa</option>';
            (data.siswa || []).forEach(function (v) { o += '<option value="' + v.id + '">' + v.nisn + ' - ' + v.nama + '</option>'; });
            siswa.innerHTML = o; siswa.disabled = false;
            $id('loadingSiswa').classList.remove('on');
            $id('siswaInfo').textContent = (data.sudah_terdaftar > 0)
                ? data.sudah_terdaftar + ' siswa sudah terdaftar (otomatis disembunyikan)'
                : 'Semua siswa di kelas ini siap didaftarkan';
        });
    });

    $id('siswaSearch').addEventListener('input', function () {
        var q = this.value.toLowerCase();
        [].slice.call($id('student_id').options).forEach(function (opt) {
            opt.style.display = (opt.text.toLowerCase().indexOf(q) !== -1) ? '' : 'none';
        });
    });

    $id('student_id').addEventListener('change', updatePreview);
    $id('kelompok_id').addEventListener('change', updatePreview);
    $id('status_select').addEventListener('change', updatePreview);

    /* ── Nav ── */
    $id('step2Back').addEventListener('click', function () { goStep(1); });
    $id('step2Next').addEventListener('click', function () {
        var isTim = state.jenis === 'Tim';
        var ok = isTim ? !!$id('kelompok_id').value : !!$id('student_id').value;
        if (!ok) return;
        $id('cfLomba').textContent = state.nama;
        $id('cfJenis').textContent = isTim ? 'Tim' : 'Individu';
        $id('cfStatus').textContent = $id('status_select').value;
        $id('cfPeserta').textContent = isTim
            ? $id('kelompok_id').options[$id('kelompok_id').selectedIndex].text
            : ($id('student_id').options[$id('student_id').selectedIndex].text.split(' - ')[1] || $id('student_id').options[$id('student_id').selectedIndex].text);
        goStep(3);
    });
    $id('step3Back').addEventListener('click', function () { goStep(2); });

    /* ── Prefill dari old() saat validasi gagal ── */
    var hasOld = {{ $hasOld ? 'true' : 'false' }};
    if (hasOld) {
        var oldCard = document.querySelector('#lombaGrid .lw-pick-card[data-old]');
        if (oldCard) {
            oldCard.click();
            toggleFields();
            if (state.jenis !== 'Tim' && {{ old('jenjang_id') ? 'true' : 'false' }}) {
                $id('jenjang_id').value = '{{ old('jenjang_id') }}';
                $id('jenjang_id').dispatchEvent(new Event('change'));
                if ({{ old('kelas_id') ? 'true' : 'false' }}) {
                    setTimeout(function () {
                        $id('kelas_id').value = '{{ old('kelas_id') }}';
                        $id('kelas_id').dispatchEvent(new Event('change'));
                        if ({{ old('student_id') ? 'true' : 'false' }}) {
                            setTimeout(function () {
                                $id('student_id').value = '{{ old('student_id') }}';
                                $id('student_id').disabled = false;
                                updatePreview();
                            }, 400);
                        }
                    }, 400);
                }
            } else if (state.jenis === 'Tim') {
                loadKelompok();
            }
        }
    }

    var form = $id('formPeserta');
    var submitBtn = document.querySelector('[data-submit-button]');
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
@endsection
