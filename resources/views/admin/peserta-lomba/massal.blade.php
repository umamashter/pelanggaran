@extends('layouts.main')
@section('title', 'Tambah Massal Peserta')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .lw-form-card { max-width: 980px; margin: 0 auto; }

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

    .lw-opt-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .lw-opt { display: flex; align-items: center; gap: 11px; padding: 16px; border-radius: 15px; border: 1.5px solid var(--lw-border); background: var(--lw-card); cursor: pointer; transition: all .22s ease; text-align: left; width: 100%; }
    .lw-opt:hover { border-color: var(--lw-primary-border); transform: translateY(-2px); box-shadow: var(--lw-shadow); }
    .lw-opt.sel { border-color: var(--lw-primary); background: var(--lw-primary-soft); box-shadow: 0 0 0 4px var(--lw-primary-soft); }
    .lw-opt .ic { width: 38px; height: 38px; border-radius: 11px; background: var(--lw-grad-soft); color: var(--lw-primary); display: inline-flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .lw-opt.sel .ic { background: var(--lw-grad); color: #fff; }
    .lw-opt b { font-size: 13px; font-weight: 800; color: var(--lw-text); }
    .lw-opt small { display: block; font-size: 10px; color: var(--lw-text-3); font-weight: 600; }

    .lw-loading { display: none; align-items: center; gap: 8px; font-size: 12px; color: var(--lw-text-3); margin-top: 10px; font-weight: 600; }
    .lw-loading.on { display: flex; }
    .lw-loading .spinner-border { width: 15px; height: 15px; border-width: 2px; color: var(--lw-primary); }

    .lw-kelas-wrap { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; }
    .lw-kelas { padding: 13px 12px; border-radius: 12px; border: 1.5px solid var(--lw-border); background: var(--lw-card); cursor: pointer; text-align: center; transition: all .22s ease; }
    .lw-kelas:hover { border-color: var(--lw-primary-border); transform: translateY(-2px); }
    .lw-kelas.sel { border-color: var(--lw-primary); background: var(--lw-primary-soft); box-shadow: 0 0 0 4px var(--lw-primary-soft); }
    .lw-kelas b { display: block; font-size: 13px; font-weight: 800; color: var(--lw-text); }
    .lw-kelas small { font-size: 10px; color: var(--lw-text-3); font-weight: 600; }

    .lw-sum-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .lw-sum-item { border-radius: 15px; border: 1px solid var(--lw-border); background: var(--lw-card); box-shadow: var(--lw-shadow); padding: 16px 14px; text-align: center; }
    .lw-sum-item .n { font-size: 30px; font-weight: 800; line-height: 1; letter-spacing: -.5px; font-variant-numeric: tabular-nums; }
    .lw-sum-item .l { font-size: 10.5px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .4px; margin-top: 7px; }
    .lw-sum-item .s { font-size: 10.5px; color: var(--lw-text-3); margin-top: 3px; }
    .lw-sum-item.blue .n { color: var(--lw-primary); }
    .lw-sum-item.red .n { color: var(--lw-red); }
    .lw-sum-item.amber .n { color: var(--lw-amber); }
    .lw-sum-item.green .n { color: var(--lw-green); }

    .lw-selbar { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; padding: 13px 16px; border-bottom: 1px solid var(--lw-border); background: var(--lw-bg); border-radius: 12px 12px 0 0; }
    .lw-selbar label { display: flex; align-items: center; gap: 9px; font-size: 12.5px; font-weight: 700; color: var(--lw-text); margin: 0; cursor: pointer; }
    .lw-selbar input[type=checkbox] { width: 17px; height: 17px; accent-color: var(--lw-primary); }
    .lw-selbar .cnt { font-size: 12.5px; font-weight: 800; color: var(--lw-primary); font-variant-numeric: tabular-nums; }
    .lw-sel-progress { height: 6px; border-radius: 999px; background: var(--lw-border); overflow: hidden; margin: 0 16px 10px; }
    .lw-sel-progress i { display: block; height: 100%; background: var(--lw-grad); width: 0; transition: width .3s ease; }

    .lw-table .siswa-checkbox { width: 18px; height: 18px; accent-color: var(--lw-primary); cursor: pointer; }
    .lw-status-new { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; background: var(--lw-green-soft); color: var(--lw-green); }

    .lw-confirm { border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); overflow: hidden; }
    .lw-confirm-row { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-bottom: 1px solid var(--lw-border-soft); font-size: 13px; }
    .lw-confirm-row:last-child { border-bottom: 0; }
    .lw-confirm-row .k { width: 160px; font-size: 10.5px; font-weight: 800; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; flex-shrink: 0; display: flex; align-items: center; gap: 8px; }
    .lw-confirm-row .k i { color: var(--lw-primary); }
    .lw-confirm-row .v { font-weight: 700; color: var(--lw-text); }
    .lw-confirm-row .v.danger { color: var(--lw-red); }
    .lw-confirm-row .v.good { color: var(--lw-green); }

    @media (max-width: 767.98px) {
        .lw-pick-grid, .lw-opt-grid { grid-template-columns: 1fr; }
        .lw-sum-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        .lw-confirm-row .k { width: 115px; }
    }
</style>

@php
    $hasOld = old('lomba_id') !== null;
@endphp

<div class="lw-mod pl-mod">

<div class="lw-card lw-card-pad lw-form-card">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-layers-fill"></i></span>
                <div>
                    <h1 class="lw-hero-title">Import Massal Peserta</h1>
                    <p class="lw-hero-sub">Daftarkan puluhan siswa sekaligus — pilih lomba, jenjang, dan kelas, lalu centang siswa yang ingin didaftarkan.</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
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

    <div class="lw-stepper" id="stepper">
        <div class="lw-step active" data-step="1">
            <div class="lw-step-dot">1</div>
            <div class="lw-step-txt"><b>Lomba</b><span>Kategori lomba</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="2">
            <div class="lw-step-dot">2</div>
            <div class="lw-step-txt"><b>Jenjang</b><span>Tingkat pendidikan</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="3">
            <div class="lw-step-dot">3</div>
            <div class="lw-step-txt"><b>Kelas</b><span>Rombongan belajar</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="4">
            <div class="lw-step-dot">4</div>
            <div class="lw-step-txt"><b>Ringkasan</b><span>Smart summary</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="5">
            <div class="lw-step-dot">5</div>
            <div class="lw-step-txt"><b>Pilih Siswa</b><span>Centang peserta</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="6">
            <div class="lw-step-dot">6</div>
            <div class="lw-step-txt"><b>Konfirmasi</b><span>Simpan pendaftaran</span></div>
        </div>
    </div>

    <form action="{{ route('peserta-lomba.massal') }}" method="POST" id="formMassal" novalidate>
        @csrf
        <input type="hidden" name="lomba_id" id="lomba_id" value="{{ old('lomba_id') }}">

        {{-- STEP 1 : LOMBA --}}
        <section class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-trophy-fill"></i> Pilih Lomba</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Hanya lomba individu yang mendukung pendaftaran massal.</p>
            @if($lombas->isEmpty())
                <div class="lw-empty">
                    <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-trophy"></i></div></div>
                    <div class="lw-empty-title">Belum Ada Lomba Individu</div>
                    <p class="lw-empty-sub">Buat lomba individu terlebih dahulu melalui modul Lomba.</p>
                    <a href="{{ route('lomba.index') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Buat Lomba</a>
                </div>
            @else
            <div class="lw-pick-grid">
                @foreach($lombas as $l)
                    <button type="button" class="lw-pick-card" data-id="{{ $l->id }}" data-nama="{{ $l->nama }}"
                        data-kelas-min="{{ $l->kelas_min }}" data-kelas-max="{{ $l->kelas_max }}" {{ old('lomba_id') == $l->id ? 'data-old' : '' }}>
                        <span class="ic"><i class="bi bi-person-fill"></i></span>
                        <span class="info"><h4>{{ $l->nama }}</h4><span class="lw-chip lw-chip--navy lw-chip-mini">Individu</span></span>
                        <span class="check"><i class="bi bi-check-lg"></i></span>
                    </button>
                @endforeach
            </div>
            @endif
            <div class="lw-wizard-nav">
                <a href="{{ route('peserta-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="s1Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 2 : JENJANG --}}
        <section class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-layers-fill"></i> Pilih Jenjang</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Tentukan jenjang kelas siswa yang akan didaftarkan.</p>
            <div class="lw-opt-grid" id="jenjangGrid">
                @foreach($jenjangs as $j)
                    <button type="button" class="lw-opt" data-id="{{ $j->id }}" data-nama="{{ $j->nama_jenjang }}">
                        <span class="ic"><i class="bi bi-mortarboard-fill"></i></span><span><b>{{ $j->nama_jenjang }}</b><small>Pilih jenjang kelas</small></span>
                    </button>
                @endforeach
            </div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="s2Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="s2Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 3 : KELAS --}}
        <section class="lw-wizard-pane" data-pane="3">
            <div class="lw-form-section"><i class="bi bi-door-open-fill"></i> Pilih Kelas</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;" id="kelasRangeHint">Memuat kelas...</p>
            <div id="kelasGrid" class="lw-kelas-wrap"></div>
            <div class="lw-loading" id="loadingKelas"><div class="spinner-border spinner-border-sm"></div><span>Memuat data kelas...</span></div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="s3Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="s3Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 4 : RINGKASAN --}}
        <section class="lw-wizard-pane" data-pane="4">
            <div class="lw-form-section"><i class="bi bi-clipboard-data-fill"></i> Smart Registration Summary</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;" id="sumTitle">Ringkasan pendaftaran kelas terpilih.</p>
            <div class="lw-sum-grid">
                <div class="lw-sum-item blue"><div class="n" id="sumTotal">0</div><div class="l">Total Siswa</div><div class="s">di kelas ini</div></div>
                <div class="lw-sum-item red"><div class="n" id="sumTerdaftar">0</div><div class="l">Sudah Terdaftar</div><div class="s">otomatis dilewati</div></div>
                <div class="lw-sum-item amber"><div class="n" id="sumBelum">0</div><div class="l">Belum Terdaftar</div><div class="s">kandidat peserta</div></div>
                <div class="lw-sum-item green"><div class="n" id="sumSiap">0</div><div class="l">Siap Ditambahkan</div><div class="s">dapat dicentang</div></div>
            </div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="s4Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="s4Next" disabled>Lihat Siswa <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 5 : PILIH SISWA --}}
        <section class="lw-wizard-pane" data-pane="5">
            <div class="lw-form-section"><i class="bi bi-person-check-fill"></i> Pilih Siswa</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Centang siswa yang akan didaftarkan ke <b id="selLomba" style="color:var(--lw-primary);">lomba</b>.</p>
            <div class="lw-card lw-table-card">
                <div class="lw-selbar">
                    <label><input type="checkbox" id="selectAll"> <span>Pilih Semua</span></label>
                    <span class="cnt" id="selectedCount">0 terpilih</span>
                </div>
                <div class="lw-sel-progress"><i id="selProgress"></i></div>
                <div class="lw-table-desktop"><div class="table-responsive">
                    <table class="table table-lw lw-table align-middle"><thead><tr><th style="width:56px;">Pilih</th><th>Nama Siswa</th><th>NISN</th><th>Kelas</th><th>Status</th></tr></thead><tbody id="siswaList"></tbody></table>
                </div></div>
            </div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="s5Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="s5Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 6 : KONFIRMASI --}}
        <section class="lw-wizard-pane" data-pane="6">
            <div class="lw-form-section"><i class="bi bi-clipboard-check-fill"></i> Konfirmasi Pendaftaran</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Periksa ringkasan sebelum menyimpan.</p>
            <div class="lw-confirm">
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-trophy-fill"></i>Lomba</span><span class="v" id="cfLomba">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-layers-fill"></i>Jenjang</span><span class="v" id="cfJenjang">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-door-open-fill"></i>Kelas</span><span class="v" id="cfKelas">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-check2-circle"></i>Akan Didaftarkan</span><span class="v good" id="cfCount">0 siswa</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-skip-forward"></i>Sudah Terdaftar</span><span class="v danger" id="cfSkip">0 dilewati</span></div>
            </div>
            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn" id="s6Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="submit" class="lw-btn lw-btn--solid" id="submitBtn" disabled>
                    <span class="btn-label"><i class="bi bi-save"></i> Daftarkan Peserta</span>
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
    var state = { step: 1, lomba: null, jenjang: null, kelas: null, data: null };

    function $id(id) { return document.getElementById(id); }
    var $s = {};
    ['s1Next','s2Back','s2Next','s3Back','s3Next','s4Back','s4Next','s5Back','s5Next','s6Back','submitBtn','selectAll']
        .forEach(function (id) { $s[id] = $id(id); });

    function fmtKelas(min, max) {
        if (min && max) return 'Kelas ' + min + ' - ' + max;
        if (min) return 'Kelas ' + min + ' ke atas';
        if (max) return 'Sampai Kelas ' + max;
        return 'Semua';
    }

    function fetchJSON(url, cb) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(cb)
            .catch(function () { cb([]); });
    }

    /* STEP 1 */
    document.querySelectorAll('.lw-pick-card').forEach(function (card) {
        card.addEventListener('click', function () {
            document.querySelectorAll('.lw-pick-card').forEach(function (c) { c.classList.remove('sel'); });
            card.classList.add('sel');
            state.lomba = { id: card.dataset.id, nama: card.dataset.nama, min: card.dataset.kelasMin || null, max: card.dataset.kelasMax || null };
            $id('lomba_id').value = state.lomba.id;
            $s.s1Next.disabled = false;
            $id('selLomba').textContent = state.lomba.nama;
        });
    });
    $s.s1Next.addEventListener('click', function () { goStep(2); });

    /* STEP 2 */
    document.querySelectorAll('#jenjangGrid .lw-opt').forEach(function (opt) {
        opt.addEventListener('click', function () {
            document.querySelectorAll('#jenjangGrid .lw-opt').forEach(function (o) { o.classList.remove('sel'); });
            opt.classList.add('sel');
            state.jenjang = { id: opt.dataset.id, nama: opt.dataset.nama };
            $s.s2Next.disabled = false;
        });
    });
    $s.s2Back.addEventListener('click', function () { goStep(1); });
    $s.s2Next.addEventListener('click', function () {
        $id('kelasRangeHint').textContent = fmtKelas(state.lomba.min, state.lomba.max) + ' · memuat kelas jenjang ' + state.jenjang.nama + '...';
        goStep(3); loadKelas();
    });

    /* STEP 3 */
    function loadKelas() {
        $id('loadingKelas').classList.add('on');
        $id('kelasGrid').innerHTML = '';
        $s.s3Next.disabled = true;
        fetchJSON('{{ url('get-kelas') }}/' + state.jenjang.id, function (data) {
            var min = state.lomba.min, max = state.lomba.max;
            var html = '';
            var shown = 0;
            data.forEach(function (v) {
                var t = parseInt(v.tingkat, 10);
                var ok = !min || t >= parseInt(min, 10);
                var ok2 = !max || t <= parseInt(max, 10);
                if (ok && ok2) {
                    html += '<button type="button" class="lw-kelas" data-id="' + v.id + '" data-nama="' + v.nama_kelas + '"><b>' + v.nama_kelas + '</b><small>Tingkat ' + v.tingkat + '</small></button>';
                    shown++;
                }
            });
            $id('kelasGrid').innerHTML = html || '<div style="grid-column:1/-1;text-align:center;padding:22px;color:var(--lw-text-3);font-size:12px;">Tidak ada kelas pada rentang yang diizinkan.</div>';
            $id('kelasRangeHint').textContent = fmtKelas(min, max) + ' · ' + shown + ' kelas tersedia di jenjang ' + state.jenjang.nama;
            $id('loadingKelas').classList.remove('on');
            document.querySelectorAll('#kelasGrid .lw-kelas').forEach(function (k) {
                k.addEventListener('click', function () {
                    document.querySelectorAll('#kelasGrid .lw-kelas').forEach(function (kk) { kk.classList.remove('sel'); });
                    k.classList.add('sel');
                    state.kelas = { id: k.dataset.id, nama: k.dataset.nama };
                    $s.s3Next.disabled = false;
                    loadSiswa();
                });
            });
        });
    }
    $s.s3Back.addEventListener('click', function () { goStep(2); });
    $s.s3Next.addEventListener('click', function () { goStep(4); });

    /* STEP 4 + 5 : getSiswa */
    function loadSiswa() {
        $id('sumSiap').textContent = '...';
        fetchJSON('{{ url('get-siswa') }}/' + state.kelas.id + '?lomba_id=' + state.lomba.id, function (data) {
            state.data = data;
            $id('sumTotal').textContent = data.total_siswa;
            $id('sumTerdaftar').textContent = data.sudah_terdaftar;
            $id('sumBelum').textContent = data.eligible;
            $id('sumSiap').textContent = data.eligible;
            $id('sumTitle').textContent = 'Ringkasan pendaftaran ' + state.kelas.nama + ' ke lomba ' + state.lomba.nama + '.';
            $s.s3Next.disabled = false;
            var rows = '';
            (data.siswa || []).forEach(function (v, i) {
                var ini = (v.nama || '?').trim().charAt(0).toUpperCase();
                rows += '<tr><td><input type="checkbox" name="student_ids[]" value="' + v.id + '" class="siswa-checkbox" checked></td>' +
                    '<td><div class="lw-name"><span class="lw-avatar lw-avatar--sm" style="background:' + (['var(--lw-grad)','var(--lw-violet)','var(--lw-sky)','var(--lw-green)','var(--lw-amber)'])[i % 5] + ';">' + ini + '</span><div class="lw-name-info"><div class="nm">' + v.nama + '</div></div></div></td>' +
                    '<td><span style="font-size:11px;color:var(--lw-text-3);font-variant-numeric:tabular-nums;">' + v.nisn + '</span></td>' +
                    '<td><span class="lw-tag"><i class="bi bi-door-open-fill"></i>' + state.kelas.nama + '</span></td>' +
                    '<td><span class="lw-status-new"><i class="bi bi-plus-circle"></i>Baru</span></td></tr>';
            });
            if (!rows) rows = '<tr><td colspan="5" style="text-align:center;padding:26px;color:var(--lw-text-3);font-weight:600;">Semua siswa di kelas ini sudah terdaftar pada lomba tersebut.</td></tr>';
            $id('siswaList').innerHTML = rows;
            $s.s4Next.disabled = false;
            updateCount();
        });
    }

    function updateCount() {
        var boxes = document.querySelectorAll('#siswaList .siswa-checkbox');
        var n = 0;
        boxes.forEach(function (b) { if (b.checked) n++; });
        $id('selectedCount').textContent = n + ' terpilih';
        $s.selectAll.checked = n > 0 && n === boxes.length;
        $id('selProgress').style.width = (boxes.length ? (n / boxes.length * 100) : 0) + '%';
        $s.s5Next.disabled = n === 0;
        $s.submitBtn.disabled = n === 0;
        var label = $s.submitBtn.querySelector('.btn-label');
        if (label) label.innerHTML = n ? '<i class="bi bi-save"></i> Daftarkan ' + n + ' Siswa' : '<i class="bi bi-save"></i> Daftarkan Peserta';
        $id('cfCount').textContent = n + ' siswa';
        $id('cfSkip').textContent = (state.data ? state.data.sudah_terdaftar : 0) + ' dilewati';
    }

    $s.s4Back.addEventListener('click', function () { goStep(3); });
    $s.s4Next.addEventListener('click', function () { goStep(5); });

    $s.s5Back.addEventListener('click', function () { goStep(4); });
    $s.s5Next.addEventListener('click', function () {
        $id('cfLomba').textContent = state.lomba.nama;
        $id('cfJenjang').textContent = state.jenjang.nama;
        $id('cfKelas').textContent = state.kelas.nama;
        goStep(6);
    });

    $s.s6Back.addEventListener('click', function () { goStep(5); });

    $s.selectAll.addEventListener('change', function () {
        document.querySelectorAll('#siswaList .siswa-checkbox').forEach(function (b) { b.checked = this.checked; }, this);
        updateCount();
    });
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('siswa-checkbox')) updateCount();
    });

    /* Prefill jika validasi gagal */
    if ({{ $hasOld ? 'true' : 'false' }}) {
        var oldCard = document.querySelector('.lw-pick-card[data-old]');
        if (oldCard) oldCard.click();
    }

    var form = $id('formMassal');
    var submitBtn = $s.submitBtn;
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
