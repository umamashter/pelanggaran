@extends('layouts.main')
@section('title', 'Edit Peserta Lomba')
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

    .lw-loading { display: none; align-items: center; gap: 8px; font-size: 12px; color: var(--lw-text-3); margin-top: 8px; font-weight: 600; }
    .lw-loading.on { display: flex; }
    .lw-loading .spinner-border { width: 15px; height: 15px; border-width: 2px; color: var(--lw-primary); }

    .lw-searchable2 { position: relative; }
    .lw-searchable2 .search { position: absolute; right: 10px; top: 8px; z-index: 3; width: 160px; }
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
    $isLocked = $pesertaLomba->is_haflah_selesai;
    $currentJenis = $pesertaLomba->lomba->jenis ?? 'Individu';
    $isTim = $currentJenis === 'Tim';
@endphp

<div class="lw-mod pl-mod">

<div class="lw-card lw-card-pad lw-form-card">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-pencil-square"></i></span>
                <div>
                    <h1 class="lw-hero-title">Edit Peserta Lomba</h1>
                    <p class="lw-hero-sub">Ubah lomba, status, atau peserta — periksa kembali sebelum menyimpan perubahan.</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    @if($isLocked)
        <div class="lw-lock-banner" style="margin-bottom:18px;"><i class="bi bi-lock-fill"></i> <div><b>Haflah telah selesai</b> — data pendaftaran terkunci dan tidak dapat diubah.</div></div>
    @endif

    @if ($errors->any())
        <div class="lw-alert lw-alert--err">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div style="flex:1;min-width:0;">
                <b>Periksa kembali form</b>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    <div class="lw-stepper" id="stepper">
        <div class="lw-step active" data-step="1">
            <div class="lw-step-dot">1</div>
            <div class="lw-step-txt"><b>Pilih Lomba</b><span>Kategori kompetisi</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="2">
            <div class="lw-step-dot">2</div>
            <div class="lw-step-txt"><b>Peserta &amp; Konfirmasi</b><span>Periksa &amp; simpan</span></div>
        </div>
    </div>

    <form action="{{ route('peserta-lomba.update', $pesertaLomba->id) }}" method="POST" id="formEdit" novalidate>
        @csrf @method('PUT')

        <input type="hidden" name="lomba_id" id="lomba_id" value="{{ old('lomba_id', $pesertaLomba->lomba_id) }}">

        {{-- STEP 1 : LOMBA --}}
        <section class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-trophy-fill"></i> Pilih Lomba</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Klik kartu lomba — data peserta akan menyesuaikan jenis lomba.</p>
            <div class="lw-pick-grid">
                @foreach($lombas as $l)
                    <button type="button" class="lw-pick-card" data-id="{{ $l->id }}" data-jenis="{{ $l->jenis ?? 'Individu' }}" data-nama="{{ $l->nama }}"
                        {{ (old('lomba_id', $pesertaLomba->lomba_id) == $l->id) ? 'data-cur' : '' }}>
                        <span class="ic"><i class="bi {{ ($l->jenis ?? '') === 'Tim' ? 'bi-people-fill' : 'bi-person-fill' }}"></i></span>
                        <span class="info"><h4>{{ $l->nama }}</h4><span class="lw-chip {{ ($l->jenis ?? '') === 'Tim' ? 'lw-chip--violet' : 'lw-chip--navy' }} lw-chip-mini">{{ ($l->jenis ?? '') === 'Tim' ? 'Tim' : 'Individu' }}</span></span>
                        <span class="check"><i class="bi bi-check-lg"></i></span>
                    </button>
                @endforeach
            </div>
            <div class="lw-wizard-nav">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="step1Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 2 : PESERTA & KONFIRMASI --}}
        <section class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-person-check-fill"></i> Data Peserta</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Pilih status dan perbarui peserta/tim.</p>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="lw-field">
                        <label class="lw-field-label" for="status_select"><i class="bi bi-flag-fill"></i> Status</label>
                        <select name="status" id="status_select" class="lw-select @error('status') is-invalid @enderror">
                            @foreach(['Terdaftar', 'Hadir', 'Tidak Hadir', 'Diskualifikasi'] as $st)
                                <option value="{{ $st }}" {{ old('status', $pesertaLomba->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div id="fieldIndividu" class="{{ $isTim ? 'd-none' : '' }}">
                <div class="lw-field" style="gap:6px;">
                    <label class="lw-field-label" for="student_id"><i class="bi bi-person-vcard-fill"></i> Siswa</label>
                    <div class="lw-searchable2">
                        <div class="search"><input type="text" id="siswaSearch" placeholder="Cari NISN / nama..." autocomplete="off"></div>
                        <select name="student_id" id="student_id" class="lw-select @error('student_id') is-invalid @enderror">
                            <option value="">Pilih Siswa</option>
                            @foreach($students as $st)
                                @php $sName = $st->user->name ?? $st->nama ?? '-'; @endphp
                                <option value="{{ $st->id }}" data-nisn="{{ $st->nisn }}" {{ old('student_id', $pesertaLomba->student_id) == $st->id ? 'selected' : '' }}>{{ $st->nisn }} - {{ $sName }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('student_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            <div id="fieldTim" class="{{ $isTim ? '' : 'd-none' }}">
                <div class="lw-field">
                    <label class="lw-field-label" for="kelompok_id"><i class="bi bi-people-fill"></i> Kelompok</label>
                    <select name="kelompok_lomba_id" id="kelompok_id" class="lw-select @error('kelompok_lomba_id') is-invalid @enderror">
                        <option value="">Pilih Kelompok</option>
                        @foreach($kelompokLombas as $kp)
                            <option value="{{ $kp->id }}" {{ old('kelompok_lomba_id', $pesertaLomba->kelompok_lomba_id) == $kp->id ? 'selected' : '' }}>{{ $kp->kode_kelompok }} - {{ $kp->nama_kelompok }}</option>
                        @endforeach
                    </select>
                    @error('kelompok_lomba_id')<div class="lw-inline-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>@enderror
                </div>
                <div class="lw-loading" id="loadingKelompok"><div class="spinner-border spinner-border-sm"></div><span>Memuat kelompok...</span></div>
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

            <div class="lw-confirm" style="margin-top:18px;">
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-trophy-fill"></i>Lomba</span><span class="v" id="cfLomba">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-diagram-3"></i>Jenis</span><span class="v" id="cfJenis">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-flag-fill"></i>Status</span><span class="v" id="cfStatus">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-person-vcard-fill"></i>Peserta</span><span class="v" id="cfPeserta">-</span></div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="step2Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button @if($isLocked) disabled @endif>
                    <span class="btn-label"><i class="bi bi-save"></i> {{ $isLocked ? 'Terkunci' : 'Simpan Perubahan' }}</span>
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
    function $id(id) { return document.getElementById(id); }
    var lombaId = $id('lomba_id');
    var state = { step: 1, id: parseInt(lombaId.value, 10), jenis: '{{ $currentJenis }}', nama: '' };
    var curId = state.id;

    function selectCard(card) {
        document.querySelectorAll('.lw-pick-card').forEach(function (c) { c.classList.remove('sel'); });
        card.classList.add('sel');
        state.id = parseInt(card.dataset.id, 10); state.jenis = card.dataset.jenis; state.nama = card.dataset.nama;
        lombaId.value = state.id;
        $id('step1Next').disabled = false;
    }

    var cur = document.querySelector('.lw-pick-card[data-cur]');
    if (cur) { selectCard(cur); state.nama = cur.dataset.nama; }

    document.querySelectorAll('.lw-pick-card').forEach(function (card) {
        card.addEventListener('click', function () {
            var isCur = card.dataset.id == curId;
            selectCard(card);
            if (!isCur) {
                var student = $id('student_id'), kelompok = $id('kelompok_id');
                student.value = ''; kelompok.value = '';
                var isTim = state.jenis === 'Tim';
                $id('fieldIndividu').classList.toggle('d-none', isTim);
                $id('fieldTim').classList.toggle('d-none', !isTim);
                if (isTim) {
                    kelompok.disabled = true; kelompok.innerHTML = '<option value="">Memuat...</option>';
                    $id('loadingKelompok').classList.add('on');
                    fetch('{{ url('get-kelompok') }}/' + state.id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            var o = '<option value="">Pilih Kelompok</option>';
                            data.forEach(function (v) { o += '<option value="' + v.id + '">' + v.text + '</option>'; });
                            kelompok.innerHTML = o; kelompok.disabled = false;
                            $id('loadingKelompok').classList.remove('on');
                        })
                        .catch(function () { $id('loadingKelompok').classList.remove('on'); });
                }
            }
            updatePreview();
        });
    });

    $id('step1Next').addEventListener('click', function () {
        $id('cfLomba').textContent = state.nama;
        $id('cfJenis').textContent = state.jenis === 'Tim' ? 'Tim' : 'Individu';
        $id('cfStatus').textContent = $id('status_select').value;
        updatePreview(true);
        goStep(2);
    });
    $id('step2Back').addEventListener('click', function () { goStep(1); });

    $id('siswaSearch').addEventListener('input', function () {
        var q = this.value.toLowerCase();
        [].slice.call($id('student_id').options).forEach(function (opt) {
            opt.style.display = (opt.text.toLowerCase().indexOf(q) !== -1) ? '' : 'none';
        });
    });

    function updatePreview(skipCheck) {
        var isTim = state.jenis === 'Tim';
        var has = isTim ? $id('kelompok_id').value : $id('student_id').value;
        if (!skipCheck && !has) { $id('previewCard').style.display = 'none'; return; }
        var ava = $id('pvAva');
        if (isTim) {
            var t = $id('kelompok_id').options[$id('kelompok_id').selectedIndex].text;
            ava.innerHTML = '<i class="bi bi-people-fill"></i>';
            $id('pvNama').textContent = t.replace(/^[^-]+ - /, '');
            $id('pvMeta').innerHTML = '<span><i class="bi bi-diagram-3"></i>Tim</span>';
            $id('cfPeserta').textContent = t;
        } else {
            var opt = $id('student_id').options[$id('student_id').selectedIndex];
            ava.innerHTML = '';
            ava.textContent = opt.text.trim().charAt(0).toUpperCase();
            var parts = opt.text.split(' - ');
            $id('pvNama').textContent = parts[1] || opt.text;
            $id('pvMeta').innerHTML = '<span><i class="bi bi-person-vcard-fill"></i>' + (parts[0] || '') + '</span>';
            $id('cfPeserta').textContent = parts[1] || opt.text;
        }
        $id('previewCard').style.display = '';
    }

    $id('student_id').addEventListener('change', function () {
        $id('cfStatus').textContent = $id('status_select').value;
        updatePreview(true);
    });
    $id('kelompok_id').addEventListener('change', function () {
        $id('cfStatus').textContent = $id('status_select').value;
        updatePreview(true);
    });
    $id('status_select').addEventListener('change', function () {
        $id('cfStatus').textContent = this.value;
    });

    updatePreview(true);

    var form = $id('formEdit');
    var submitBtn = document.querySelector('[data-submit-button]');
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
@endsection
