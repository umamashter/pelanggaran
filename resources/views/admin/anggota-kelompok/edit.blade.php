@extends('layouts.main')
@section('title', 'Edit Anggota Kelompok')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .lw-form-card { max-width: 880px; margin: 0 auto; }

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

    .lw-overview { margin-top: 14px; border: 1px dashed var(--lw-primary-border); border-radius: 16px; padding: 16px 18px; background: linear-gradient(135deg, var(--lw-card), var(--lw-bg)); }

    .lw-pick-panel { border: 1px solid var(--lw-border); border-radius: 16px; overflow: hidden; background: var(--lw-card); }
    .lw-pick-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--lw-border-soft); flex-wrap: wrap; }
    .lw-pick-head .t { font-size: 12.5px; font-weight: 800; color: var(--lw-text); display: flex; align-items: center; gap: 8px; }
    .lw-pick-head .t i { color: var(--lw-primary); }
    .lw-pick-search { position: relative; }
    .lw-pick-search i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--lw-text-3); font-size: 13px; }
    .lw-pick-search input { min-height: 32px; padding: 0 12px 0 32px; font-size: 12px; border-radius: 9px; border: 1px solid var(--lw-border); background: var(--lw-bg); color: var(--lw-text); }
    .lw-pick-search input:focus { border-color: var(--lw-primary); outline: none; }

    .lw-member-opt { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-bottom: 1px solid var(--lw-border-soft); cursor: pointer; transition: background .15s ease; }
    .lw-member-opt:last-child { border-bottom: 0; }
    .lw-member-opt:hover { background: var(--lw-bg); }
    .lw-member-opt.sel { background: var(--lw-primary-soft); }
    .lw-member-opt input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--lw-primary); flex-shrink: 0; cursor: pointer; }
    .lw-member-opt .ava { width: 34px; height: 34px; border-radius: 10px; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; }
    .lw-member-opt .info { flex: 1; min-width: 0; }
    .lw-member-opt .info .n { font-size: 13px; font-weight: 700; color: var(--lw-text); }
    .lw-member-opt .info .s { font-size: 11px; color: var(--lw-text-3); }

    .lw-pick-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-top: 1px solid var(--lw-border-soft); flex-wrap: wrap; }
    .lw-pick-foot .cnt { font-size: 12px; font-weight: 700; color: var(--lw-text-3); display: flex; align-items: center; gap: 8px; }

    .lw-confirm { border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); overflow: hidden; }
    .lw-confirm-row { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-bottom: 1px solid var(--lw-border-soft); font-size: 13px; }
    .lw-confirm-row:last-child { border-bottom: 0; }
    .lw-confirm-row .k { width: 150px; font-size: 10.5px; font-weight: 800; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; flex-shrink: 0; display: flex; align-items: center; gap: 8px; }
    .lw-confirm-row .k i { color: var(--lw-primary); }
    .lw-confirm-row .v { font-weight: 700; color: var(--lw-text); }

    .lw-confirm-chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .lw-confirm-chips .chip { display: inline-flex; align-items: center; gap: 6px; background: var(--lw-primary-soft); color: var(--lw-primary); border: 1px solid var(--lw-primary-border); border-radius: 999px; padding: 4px 12px; font-size: 11.5px; font-weight: 700; }

    @media (max-width: 767.98px) {
        .lw-pick-grid { grid-template-columns: 1fr; }
        .lw-confirm-row .k { width: 110px; }
        .lw-pick-search { width: 100%; }
    }
</style>

@php
    $currentKelompok = $kelompokLombas->firstWhere('id', $anggotaKelompok->kelompok_lomba_id);
    $hasOld = old('kelompok_lomba_id') !== null;
@endphp

<div class="lw-mod pl-mod">

<div class="lw-card lw-card-pad lw-form-card">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-people-fill"></i></span>
                <div>
                    <h1 class="lw-hero-title">Edit Anggota Kelompok</h1>
                    <p class="lw-hero-sub">Kelola anggota tim — ganti kelompok atau perbarui susunan siswa (minimal 2 anggota).</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('anggota-kelompok.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="lw-stepper" id="stepper">
        <div class="lw-step active" data-step="1">
            <div class="lw-step-dot">1</div>
            <div class="lw-step-txt"><b>Pilih Kelompok</b><span>Tim peserta</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="2">
            <div class="lw-step-dot">2</div>
            <div class="lw-step-txt"><b>Pilih Siswa</b><span>Minimal 2 anggota</span></div>
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

    <form action="{{ route('anggota-kelompok.update', $anggotaKelompok->id) }}" method="POST" id="formAnggota" novalidate>
        @csrf @method('PUT')

        {{-- STEP 1 : KELOMPOK --}}
        <section class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-people-fill"></i> Pilih Kelompok</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Kelompok yang sedang aktif ditandai otomatis. Ganti bila diperlukan.</p>

            @if($kelompokLombas->isEmpty())
                <div class="lw-empty">
                    <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-people"></i></div></div>
                    <div class="lw-empty-title">Belum Ada Kelompok</div>
                    <p class="lw-empty-sub">Buat kelompok terlebih dahulu sebelum mengelola anggota.</p>
                    <a href="{{ route('kelompok-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Kelompok</a>
                </div>
            @else
            <div class="lw-pick-grid" id="kelompokGrid">
                @foreach($kelompokLombas as $kl)
                    @php
                        $l = $kl->lomba;
                        $kelas = 'Semua';
                        if ($l->kelas_min && $l->kelas_max) $kelas = 'Kelas '.$l->kelas_min.' - '.$l->kelas_max;
                        elseif ($l->kelas_min) $kelas = 'Kelas '.$l->kelas_min.'+';
                        elseif ($l->kelas_max) $kelas = 's/d Kelas '.$l->kelas_max;
                        $isCurrent = $kl->id == $anggotaKelompok->kelompok_lomba_id;
                    @endphp
                    <button type="button" class="lw-pick-card {{ $isCurrent ? 'sel' : '' }}" data-id="{{ $kl->id }}"
                        data-kelas-min="{{ $l->kelas_min ?? '' }}" data-kelas-max="{{ $l->kelas_max ?? '' }}"
                        data-nama="{{ $kl->nama_kelompok }}" data-lomba="{{ $l->nama ?? '-' }}"
                        data-jumlah="{{ $kl->anggota_count ?? 0 }}" {{ $isCurrent ? 'data-current' : '' }} {{ old('kelompok_lomba_id') == $kl->id ? 'data-old' : '' }}>
                        <span class="ic" style="background:{{ lw_ava_color($kl->nama_kelompok) }};color:#fff;"><i class="bi bi-people-fill"></i></span>
                        <span class="info">
                            <h4>{{ $kl->nama_kelompok }}</h4>
                            <span class="lw-chip lw-chip--navy lw-chip-mini"><i class="bi bi-trophy-fill"></i>{{ $l->nama ?? '-' }}</span>
                            <span class="lw-chip lw-chip--amber lw-chip-mini" style="margin-left:4px;">{{ $kelas }}</span>
                            <span class="lw-chip lw-chip--{{ $isCurrent ? 'green' : 'slate' }} lw-chip-mini" style="margin-left:4px;">{{ $kl->anggota_count ?? 0 }} anggota</span>
                        </span>
                        <span class="check"><i class="bi bi-check-lg"></i></span>
                    </button>
                @endforeach
            </div>

            <div class="lw-overview" id="overviewPanel" style="display:none;">
                <div class="lw-form-section" style="margin-bottom:12px;"><i class="bi bi-compass"></i> Informasi Tim</div>
                <div class="lw-info-grid">
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-tag"></i>Kelompok</div><div class="val" id="ovKelompok">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-trophy-fill"></i>Lomba</div><div class="val" id="ovLomba">-</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-mortarboard-fill"></i>Rentang Kelas</div><div class="val" id="ovKelas">Semua</div></div>
                    <div class="lw-info-cell"><div class="lbl"><i class="bi bi-person-check-fill"></i>Aturan Anggota</div><div class="val">Minimal 2 siswa</div></div>
                </div>
            </div>
            @endif

            <div class="lw-wizard-nav">
                <a href="{{ route('anggota-kelompok.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="step1Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 2 : SISWA --}}
        <section class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-person-check-fill"></i> Pilih Siswa</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Cari dan centang siswa yang menjadi anggota tim (minimal 2 siswa).</p>

            <input type="hidden" name="kelompok_lomba_id" id="kelompok_lomba_id" value="{{ old('kelompok_lomba_id', $anggotaKelompok->kelompok_lomba_id) }}">

            <div id="infoAturan" class="lw-alert lw-alert--warn" style="display:none;margin-bottom:14px;">
                <i class="bi bi-info-circle-fill"></i> Lomba ini hanya untuk <strong><span id="infoKelasRange"></span></strong>
            </div>

            <div class="lw-pick-panel" id="siswaPanel">
                <div class="lw-pick-head">
                    <div class="t"><i class="bi bi-person-vcard-fill"></i> Siswa Tersedia</div>
                    <div class="lw-pick-search"><i class="bi bi-search"></i><input type="text" id="siswaSearch" placeholder="Cari nama / NISN..." autocomplete="off"></div>
                </div>
                <div class="lw-loading" id="loadingSiswa" style="margin:14px 16px;"><div class="spinner-border spinner-border-sm"></div><span>Memuat data siswa...</span></div>
                <div id="siswaList">
                    <div class="lw-empty" style="padding:28px 16px;">
                        <div class="lw-empty-title" style="font-size:14px;">Memuat Anggota Saat Ini...</div>
                    </div>
                </div>
                <div class="lw-pick-foot">
                    <label class="lw-btn lw-btn--ghost lw-btn--sm" style="margin:0;cursor:pointer;"><input type="checkbox" id="selectAll" style="margin-right:7px;"> Pilih Semua</label>
                    <span class="cnt"><i class="bi bi-person-check-fill"></i> <span id="selectedCount">0</span> siswa dipilih</span>
                </div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn lw-btn--ghost" id="step2Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--soft" id="step2Next" disabled>Lanjut <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 3 : KONFIRMASI --}}
        <section class="lw-wizard-pane" data-pane="3">
            <div class="lw-form-section"><i class="bi bi-clipboard-check-fill"></i> Konfirmasi</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Periksa kembali data anggota sebelum disimpan.</p>

            <div class="lw-confirm" style="margin-bottom:14px;">
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-people-fill"></i>Kelompok</span><span class="v" id="cfKelompok">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-trophy-fill"></i>Lomba</span><span class="v" id="cfLomba">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-mortarboard-fill"></i>Rentang Kelas</span><span class="v" id="cfKelas">-</span></div>
                <div class="lw-confirm-row"><span class="k"><i class="bi bi-person-check-fill"></i>Jumlah Anggota</span><span class="v" id="cfJumlah">0 siswa</span></div>
            </div>

            <div class="lw-confirm" id="cfListWrap" style="display:none;">
                <div class="lw-confirm-row"><span class="k" style="width:auto;"><i class="bi bi-person-vcard-fill"></i>Daftar Siswa</span></div>
                <div class="lw-confirm-row"><span class="k" style="width:auto;"></span><span class="v"><span class="lw-confirm-chips" id="cfList"></span></span></div>
            </div>

            <div class="lw-wizard-nav">
                <button type="button" class="lw-btn lw-btn--ghost" id="step3Back"><i class="bi bi-arrow-left"></i> Kembali</button>
                <span class="spacer"></span>
                <button type="submit" class="lw-btn lw-btn--solid" data-submit-button>
                    <span class="btn-label"><i class="bi bi-save-fill"></i> Simpan Perubahan</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </section>

    </form>
</div>

</div>

@push('scripts')
<script>
(function () {
    function $id(x) { return document.getElementById(x); }
    function goStep(n) {
        document.querySelectorAll('.lw-wizard-pane').forEach(function (p) { p.classList.remove('is-show'); });
        document.querySelector('.lw-wizard-pane[data-pane="' + n + '"]').classList.add('is-show');
        document.querySelectorAll('.lw-step').forEach(function (s) {
            var i = parseInt(s.dataset.step, 10);
            s.classList.toggle('active', i === n);
            s.classList.toggle('done', i < n);
        });
        document.querySelectorAll('.lw-step-line').forEach(function (line, idx) {
            line.classList.toggle('done', (idx + 1) < n);
        });
    }

    var state = { id: null, min: null, max: null, nama: '', lomba: '' };
    var picked = {};
    var studentRows = [];
    var keepIds = @json(array_map('strval', $currentMemberIds));

    function fmtKelas(min, max) {
        if (min && max) return 'Kelas ' + min + ' - ' + max;
        if (min) return 'Kelas ' + min + '+';
        if (max) return 's/d Kelas ' + max;
        return 'Semua Kelas';
    }

    function avaColor(name) {
        var palette = ['#2b3c78', '#e7a615', '#0e9f6e', '#d97706', '#db2777', '#3b82f6', '#7c3aed', '#0891b2'];
        var h = 0, s = String(name);
        for (var i = 0; i < s.length; i++) { h = (h * 31 + s.charCodeAt(i)) & 0x7fffffff; }
        return palette[h % palette.length];
    }

    function fetchJSON(url, cb) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(cb)
            .catch(function () { cb({ students: [] }); });
    }

    /* ── Step 1 : kelompok pick cards ── */
    document.querySelectorAll('#kelompokGrid .lw-pick-card').forEach(function (card) {
        card.addEventListener('click', function () {
            document.querySelectorAll('#kelompokGrid .lw-pick-card').forEach(function (c) { c.classList.remove('sel'); });
            card.classList.add('sel');
            state.id = card.dataset.id;
            state.min = card.dataset.kelasMin || null;
            state.max = card.dataset.kelasMax || null;
            state.nama = card.dataset.nama;
            state.lomba = card.dataset.lomba;
            $id('kelompok_lomba_id').value = state.id;
            $id('ovKelompok').textContent = state.nama;
            $id('ovLomba').textContent = state.lomba;
            $id('ovKelas').textContent = fmtKelas(state.min, state.max);
            $id('overviewPanel').style.display = '';
            $id('step1Next').disabled = false;
        });
    });

    $id('step1Next').addEventListener('click', function () {
        if (!state.id) return;
        goStep(2);
        loadStudents(state.id);
    });

    /* ── Step 2 : siswa picker ── */
    function renderList() {
        var wrap = $id('siswaList');
        if (!studentRows.length) {
            wrap.innerHTML = '<div class="lw-empty" style="padding:28px 16px;"><div class="lw-empty-title" style="font-size:14px;">Tidak Ada Siswa Tersedia</div><p class="lw-empty-sub" style="font-size:12px;">Siswa sudah terdaftar di kelompok lain atau tidak sesuai rentang kelas lomba.</p></div>';
            return;
        }
        var q = $id('siswaSearch').value.trim().toLowerCase();
        var html = '';
        studentRows.forEach(function (s) {
            if (q && (s.text.toLowerCase().indexOf(q) === -1)) return;
            var sel = !!picked[s.id];
            html += '<label class="lw-member-opt' + (sel ? ' sel' : '') + '">'
                + '<input type="checkbox" data-sid="' + s.id + '" ' + (sel ? 'checked' : '') + '>'
                + '<span class="ava" style="background:' + s.color + ';">' + s.init + '</span>'
                + '<span class="info"><span class="n">' + s.name + '</span><span class="s">' + s.nisn + '</span></span>'
                + '</label>';
        });
        wrap.innerHTML = html || '<div class="lw-empty" style="padding:24px 16px;"><p class="lw-empty-sub" style="font-size:12px;margin:0;">Tidak ditemukan siswa dengan kata kunci tersebut.</p></div>';
        updateCount();
    }

    function updateCount() {
        var n = Object.keys(picked).length;
        $id('selectedCount').textContent = n;
        $id('step2Next').disabled = n < 2;
        $id('selectAll').checked = studentRows.length > 0 && n === studentRows.length;
        $id('selectAll').indeterminate = n > 0 && n < studentRows.length;
    }

    function loadStudents(kelompokId) {
        picked = {}; studentRows = [];
        $id('siswaSearch').value = '';
        $id('loadingSiswa').classList.add('on');
        $id('siswaList').innerHTML = '';
        var url = '{{ url('anggota-kelompok/get-siswa') }}/' + kelompokId + '?selected_ids[]=' + keepIds.join('&selected_ids[]=');
        fetchJSON(url, function (data) {
            $id('loadingSiswa').classList.remove('on');
            if (data.kelas_min && data.kelas_max) {
                $id('infoKelasRange').textContent = (data.kelas_min == data.kelas_max)
                    ? 'Kelas ' + data.kelas_min : 'Kelas ' + data.kelas_min + ' - ' + data.kelas_max;
                $id('infoAturan').style.display = '';
            } else {
                $id('infoAturan').style.display = 'none';
            }
            studentRows = (data.students || []).map(function (item) {
                var m = item.text.match(/^(.*?)\s*\(([^)]*)\)\s*$/);
                var name = m ? m[1] : item.text;
                var nisn = m ? m[2] : '';
                var id = String(item.id);
                if (keepIds.indexOf(id) !== -1) { picked[id] = item.text; }
                return { id: id, name: name, nisn: nisn, text: item.text,
                    init: (name.trim().charAt(0) || '?').toUpperCase(),
                    color: avaColor(name) };
            });
            renderList();
        });
    }

    $id('siswaList').addEventListener('change', function (e) {
        var box = e.target.closest('input[type="checkbox"][data-sid]');
        if (!box) return;
        var id = box.dataset.sid;
        if (box.checked) {
            var s = studentRows.find(function (x) { return x.id === id; });
            if (s) picked[id] = s.text;
        } else {
            delete picked[id];
        }
        box.closest('.lw-member-opt').classList.toggle('sel', box.checked);
        updateCount();
    });

    $id('siswaSearch').addEventListener('input', renderList);

    $id('selectAll').addEventListener('change', function () {
        if (this.checked) {
            studentRows.forEach(function (s) { picked[s.id] = s.text; });
        } else {
            picked = {};
        }
        renderList();
    });

    $id('step2Back').addEventListener('click', function () { goStep(1); });
    $id('step2Next').addEventListener('click', function () {
        if (Object.keys(picked).length < 2) return;
        $id('cfKelompok').textContent = state.nama;
        $id('cfLomba').textContent = state.lomba;
        $id('cfKelas').textContent = fmtKelas(state.min, state.max);
        $id('cfJumlah').textContent = Object.keys(picked).length + ' siswa';
        var chips = '';
        Object.keys(picked).forEach(function (id) {
            var s = studentRows.find(function (x) { return x.id === id; });
            chips += '<span class="chip"><i class="bi bi-person-fill"></i>' + (s ? s.text.replace(/&/g, '&amp;').replace(/</g, '&lt;') : id) + '</span>';
        });
        $id('cfList').innerHTML = chips;
        $id('cfListWrap').style.display = '';
        goStep(3);
    });
    $id('step3Back').addEventListener('click', function () { goStep(2); });

    /* ── Init : preselect current kelompok ── */
    var currentCard = document.querySelector('#kelompokGrid .lw-pick-card[data-current]');
    if (currentCard) {
        currentCard.click();
        $id('step1Next').disabled = false;
    }

    var form = $id('formAnggota');
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
