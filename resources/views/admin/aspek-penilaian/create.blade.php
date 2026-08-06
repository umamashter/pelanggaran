@extends('layouts.main')
@section('title', 'Buat Rubrik Penilaian')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Rubrik Builder — Aspek Penilaian Wizard ---------- */
    .ar-wizard { max-width: 1180px; margin: 0 auto; }

    .lw-pick-grid .lw-pick-card { text-align: left; font-family: inherit; position: relative; }
    .lw-pick-grid .lw-pick-title { flex: 1; min-width: 0; }
    .ar-rubrik-tag { position: absolute; bottom: 10px; right: 10px; display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 8px; border-radius: 8px; font-size: 10px; font-weight: 700; background: var(--lw-bg); color: var(--lw-text-2); white-space: nowrap; }
    .ar-rubrik-tag i { font-size: 10.5px; }
    .ar-rubrik-tag.has { color: var(--lw-green); background: var(--lw-green-soft); border: 1px solid var(--lw-green-border); }
    .ar-rubrik-tag.has i { color: var(--lw-green); }

    /* Builder layout */
    .ar-builder-grid { display: grid; grid-template-columns: 1fr 320px; gap: 18px; align-items: start; }
    .ar-builder-main { display: flex; flex-direction: column; gap: 14px; }
    .ar-aspek-list { display: flex; flex-direction: column; gap: 10px; }
    .ar-aspek-row { display: flex; align-items: center; gap: 10px; padding: 11px 13px; background: var(--lw-card); border: 1.5px solid var(--lw-border); border-radius: 13px; transition: border-color .2s, box-shadow .2s, opacity .2s; }
    .ar-aspek-row:hover { border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow); }
    .ar-aspek-row.is-dragging { opacity: .5; border-style: dashed; }
    .ar-aspek-row.is-over { border-color: var(--lw-primary); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
    .ar-aspek-row.invalid { border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .ar-grip { flex-shrink: 0; width: 30px; height: 34px; display: inline-flex; align-items: center; justify-content: center; color: var(--lw-text-3); cursor: grab; font-size: 15px; border-radius: 8px; }
    .ar-grip:hover { background: var(--lw-bg); color: var(--lw-text-2); }
    .ar-grip:active { cursor: grabbing; }
    .ar-aspek-num { flex-shrink: 0; width: 30px; height: 30px; border-radius: 9px; background: var(--lw-navy-soft); color: var(--lw-primary); font-size: 12.5px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; font-variant-numeric: tabular-nums; }
    .ar-aspek-row input[type="text"] { flex: 1; min-width: 0; height: 40px; border: 1.5px solid var(--lw-border); border-radius: 10px; background: var(--lw-card); color: var(--lw-text); font-size: 13px; font-family: inherit; padding: 0 13px; transition: border-color .2s, box-shadow .2s; }
    .ar-aspek-row input[type="text"]:focus { outline: none; border-color: var(--lw-primary); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
    .ar-aspek-row input[type="text"].is-invalid { border-color: var(--lw-red); box-shadow: 0 0 0 3px var(--lw-red-soft); }
    .ar-row-remove { flex-shrink: 0; width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid var(--lw-border); background: var(--lw-card); color: var(--lw-text-3); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; }
    .ar-row-remove:hover { color: var(--lw-red); border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .ar-row-remove:disabled { opacity: .35; cursor: not-allowed; }
    .ar-row-remove:disabled:hover { color: var(--lw-text-3); border-color: var(--lw-border); background: var(--lw-card); }

    .ar-aspek-err { display: none; margin-top: 12px; border-radius: 11px; padding: 10px 13px; font-size: 12px; font-weight: 600; color: var(--lw-red); background: var(--lw-red-soft); border: 1px solid var(--lw-red-border); align-items: center; gap: 8px; }
    .ar-aspek-err.show { display: flex; }
    .ar-aspek-warn { display: none; margin-top: 10px; border-radius: 11px; padding: 10px 13px; font-size: 12px; font-weight: 600; color: var(--lw-amber); background: var(--lw-amber-soft); border: 1px solid var(--lw-amber-border); align-items: center; gap: 8px; }
    .ar-aspek-warn.show { display: flex; }

    .ar-add-row { height: 46px; border: 1.5px dashed var(--lw-border); border-radius: 13px; background: transparent; color: var(--lw-text-2); font-size: 12.5px; font-weight: 700; font-family: inherit; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
    .ar-add-row:hover { border-color: var(--lw-primary); color: var(--lw-primary); background: var(--lw-primary-soft); }

    /* Live preview */
    .ar-preview-panel { position: sticky; top: 88px; display: flex; flex-direction: column; gap: 14px; }
    .ar-preview-card { border: 1px solid var(--lw-border); border-radius: 15px; background: var(--lw-card); box-shadow: var(--lw-shadow); overflow: hidden; }
    .ar-preview-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; border-bottom: 1px solid var(--lw-border); }
    .ar-preview-head b { font-size: 12.5px; font-weight: 800; color: var(--lw-text); display: flex; align-items: center; gap: 8px; }
    .ar-preview-head b i { color: var(--lw-primary); }
    .ar-preview-count { font-size: 10.5px; font-weight: 700; color: var(--lw-text-3); background: var(--lw-bg); padding: 3px 9px; border-radius: 999px; }
    .ar-preview-body { padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; min-height: 120px; }
    .ar-preview-item { display: flex; align-items: center; gap: 10px; padding: 9px 11px; border-radius: 11px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .ar-preview-item .no { width: 22px; height: 22px; border-radius: 7px; background: var(--lw-navy-soft); color: var(--lw-primary); font-size: 11px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ar-preview-item .tx { font-size: 12px; font-weight: 600; color: var(--lw-text); }
    .ar-preview-empty { text-align: center; padding: 22px 12px; color: var(--lw-text-3); font-size: 12px; }
    .ar-preview-empty i { display: block; font-size: 24px; margin-bottom: 8px; color: var(--lw-text-3); opacity: .6; }

    .ar-panel-note { border: 1px dashed var(--lw-border); border-radius: 13px; padding: 12px 14px; font-size: 11.5px; color: var(--lw-text-2); display: flex; gap: 9px; align-items: flex-start; line-height: 1.5; }
    .ar-panel-note i { color: var(--lw-primary); font-size: 14px; margin-top: 1px; }

    .ar-loading { display: inline-flex; align-items: center; gap: 8px; }
    .ar-spin { width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: lwSpin .7s linear infinite; }

    @media (max-width: 991.98px) { .ar-builder-grid { grid-template-columns: 1fr; } .ar-preview-panel { position: static; } }
</style>

@php
    $oldLomba = old('lomba_id');
    $oldAspek = old('nama_aspek');
    $rubrikMap = \App\Models\AspekPenilaian::select('lomba_id')->get()->groupBy('lomba_id');
    $lombaWithRubrik = $rubrikMap->map(fn ($g) => $g->count());
@endphp

<div class="lw-mod">

<div class="lw-card lw-card-pad ar-wizard">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-journal-plus"></i></span>
                <div>
                    <h1 class="lw-hero-title">Buat Rubrik Penilaian</h1>
                    <p class="lw-hero-sub">Pilih lomba lalu susun aspek yang dinilai juri — drag untuk mengatur urutan.</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('aspek-penilaian.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="lw-stepper" id="arStepper">
        <div class="lw-step active" data-step="1">
            <div class="lw-step-dot"><i class="bi bi-trophy-fill"></i></div>
            <div class="lw-step-txt"><b>Pilih Lomba</b><span>Tentukan cabang lomba</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="2">
            <div class="lw-step-dot"><i class="bi bi-list-check"></i></div>
            <div class="lw-step-txt"><b>Susun Aspek</b><span>Buat daftar penilaian</span></div>
        </div>
    </div>

    @if($errors->any())
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    <form id="arForm" method="POST" action="{{ route('aspek-penilaian.store') }}" novalidate>
        @csrf
        <input type="hidden" name="lomba_id" id="arLombaId" value="{{ $oldLomba }}">

        {{-- STEP 1 : PILIH LOMBA --}}
        <section class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-flag-fill"></i> Pilih Lomba</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Rubrik penilaian akan dipasang pada lomba berikut.</p>

            <div class="lw-search" style="margin-bottom:14px;">
                <i class="bi bi-search"></i>
                <input type="search" class="lw-control" id="arLombaSearch" placeholder="Cari nama lomba..." autocomplete="off">
            </div>

            @forelse($lombas as $l)
                <div class="lw-pick-grid" id="arLombaGrid" style="margin-bottom:14px;">
                    <button type="button" class="lw-pick-card" data-id="{{ $l->id }}" data-nama="{{ e($l->nama) }}" data-jenis="{{ $l->jenis ?? 'Individu' }}"
                        {{ (string)$oldLomba === (string)$l->id ? 'data-preselected' : '' }}>
                        <span class="lw-pick-icon"><i class="bi bi-trophy-fill"></i></span>
                        <span class="lw-pick-title">{{ $l->nama }}</span>
                        <span class="lw-chip lw-chip-mini" style="display:inline-flex;font-size:10px;min-height:22px;padding:0 8px;">
                            <i class="bi {{ ($l->jenis ?? 'Individu') === 'Tim' ? 'bi-people-fill' : 'bi-person-fill' }}"></i>{{ $l->jenis ?? 'Individu' }}
                        </span>
                        <span class="lw-pick-check"><i class="bi bi-check-lg"></i></span>
                        <span class="ar-rubrik-tag {{ ($lombaWithRubrik[$l->id] ?? 0) > 0 ? 'has' : '' }}"><i class="bi {{ ($lombaWithRubrik[$l->id] ?? 0) > 0 ? 'bi-check-circle-fill' : 'bi-folder2-open' }}"></i>{{ ($lombaWithRubrik[$l->id] ?? 0) > 0 ? ($lombaWithRubrik[$l->id] . ' aspek') : 'Kosong' }}</span>
                    </button>
                </div>
            @empty
                <div class="lw-empty">
                    <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-trophy"></i></div></div>
                    <div class="lw-empty-title">Belum Ada Lomba</div>
                    <p class="lw-empty-sub">Tidak ada lomba yang tersedia. Silakan tambahkan lomba terlebih dahulu.</p>
                    <a href="{{ route('lomba.index') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Kelola Lomba</a>
                </div>
            @endforelse

            <div class="ar-aspek-err show" id="arLombaErr" style="display:none;"><i class="bi bi-exclamation-circle-fill"></i> Pilih satu lomba terlebih dahulu untuk melanjutkan.</div>

            <div class="lw-wizard-nav">
                <a href="{{ route('aspek-penilaian.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--solid" id="arToStep2">Lanjut ke Aspek <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 2 : SUSUN ASPEK --}}
        <section class="lw-wizard-pane" data-pane="2">
            <div class="ar-builder-grid">
                <div class="ar-builder-main">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <div class="lw-form-section" style="margin-bottom:0;"><i class="bi bi-list-check"></i> Daftar Aspek</div>
                            <p class="lw-help-text" style="margin:2px 0 0;">Tarik <i class="bi bi-grip-vertical"></i> untuk mengubah urutan — minimal 4 aspek.</p>
                        </div>
                        <span class="lw-chip lw-chip--navy"><i class="bi bi-hash"></i><span id="arCountBadge">0</span> aspek</span>
                    </div>

                    <div class="ar-aspek-list" id="arAspekList" style="margin-top:14px;">
                        @php $rows = max(count($oldAspek ?? []), 4); @endphp
                        @for($i = 0; $i < $rows; $i++)
                        <div class="ar-aspek-row" draggable="true">
                            <span class="ar-grip" aria-hidden="true"><i class="bi bi-grip-vertical"></i></span>
                            <span class="ar-aspek-num">{{ $i + 1 }}</span>
                            <input type="text" name="nama_aspek[]" value="{{ old('nama_aspek.' . $i) ?? '' }}" placeholder="Nama aspek penilaian" aria-label="Nama aspek {{ $i + 1 }}" maxlength="255">
                            <button type="button" class="ar-row-remove" title="Hapus aspek" aria-label="Hapus aspek"><i class="bi bi-x-lg"></i></button>
                        </div>
                        @endfor
                    </div>

                    <button type="button" id="arAddRow" class="ar-add-row" style="width:100%;margin-top:12px;"><i class="bi bi-plus-lg"></i> Tambah Aspek</button>

                    <div class="ar-aspek-err" id="arErr"><i class="bi bi-exclamation-triangle-fill"></i><span>Setiap aspek wajib diisi dan minimal 4 aspek.</span></div>
                    <div class="ar-aspek-warn" id="arWarn"><i class="bi bi-info-circle-fill"></i><span>Rubrik dengan kurang dari 4 aspek dianggap "Rubrik Minimal".</span></div>
                </div>

                <aside class="ar-preview-panel">
                    <div class="ar-preview-card">
                        <div class="ar-preview-head">
                            <b><i class="bi bi-eye"></i> Live Preview Juri</b>
                            <span class="ar-preview-count" id="arPreviewCount">0 aspek</span>
                        </div>
                        <div class="ar-preview-body" id="arPreviewBody">
                            <div class="ar-preview-empty"><i class="bi bi-clipboard2"></i>Belum ada aspek</div>
                        </div>
                    </div>
                    <div class="ar-panel-note"><i class="bi bi-lightbulb"></i><span>Panel ini menampilkan rubrik persis seperti yang akan dilihat juri saat menilai. Pastikan urutan &amp; isi sudah sesuai.</span></div>
                </aside>
            </div>

            <div class="lw-wizard-nav">
                <div class="d-flex align-items-center gap-2" style="font-size:12px;color:var(--lw-text-3);"><i class="bi {{ $oldLomba ? 'bi-trophy' : 'bi-info-circle' }}"></i><span id="arFootInfo">{{ $oldLomba ? 'Rubrik untuk lomba terpilih' : 'Lomba belum dipilih' }}</span></div>
                <span class="spacer"></span>
                <button type="button" class="lw-btn" id="arToStep1"><i class="bi bi-arrow-left"></i> Kembali</button>
                <button type="submit" id="arSubmit" class="lw-btn lw-btn--success"><i class="bi bi-check2"></i> Simpan Rubrik</button>
            </div>
        </section>
    </form>

</div>
</div>

@push('scripts')
<script>
(function () {
    var form = document.getElementById('arForm');
    if (!form) return;
    var list = document.getElementById('arAspekList');
    var lombaId = document.getElementById('arLombaId');
    var currentStep = 1;

    /* ---------- Stepper ---------- */
    function goStep(n) {
        currentStep = n;
        document.querySelectorAll('#arStepper .lw-step').forEach(function (s) {
            s.classList.toggle('active', parseInt(s.dataset.step, 10) === n);
            s.classList.toggle('done', parseInt(s.dataset.step, 10) < n);
        });
        document.querySelectorAll('#arStepper .lw-step-line').forEach(function (l) {
            l.classList.toggle('done', n > 1);
        });
        document.querySelectorAll('#arForm [data-pane]').forEach(function (p) {
            p.classList.toggle('is-show', p.dataset.pane === String(n));
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ---------- Lomba selection ---------- */
    var selected = null;
    function selectLomba(card) {
        selected = card ? card.dataset.id : null;
        lombaId.value = selected || '';
        document.querySelectorAll('#arLombaGrid .lw-pick-card').forEach(function (c) {
            c.classList.toggle('is-picked', c === card);
        });
        document.getElementById('arLombaErr').style.display = 'none';
        if (selected) {
            document.getElementById('arFootInfo').innerHTML = '<i class="bi bi-trophy"></i> Rubrik untuk <b>' + card.dataset.nama + '</b>';
        } else {
            document.getElementById('arFootInfo').innerHTML = '<i class="bi bi-info-circle"></i> Lomba belum dipilih';
        }
    }
    document.querySelectorAll('#arLombaGrid .lw-pick-card').forEach(function (card) {
        card.addEventListener('click', function () { selectLomba(card); });
        if (card.hasAttribute('data-preselected')) { selectLomba(card); }
    });

    /* ---------- lomba search ---------- */
    document.getElementById('arLombaSearch').addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        document.querySelectorAll('#arLombaGrid .lw-pick-card').forEach(function (c) {
            c.style.display = c.dataset.nama.toLowerCase().indexOf(q) !== -1 ? '' : 'none';
        });
    });

    /* ---------- Aspect rows ---------- */
    function renumber() {
        Array.prototype.forEach.call(list.querySelectorAll('.ar-aspek-row'), function (row, i) {
            row.querySelector('.ar-aspek-num').textContent = i + 1;
            row.querySelector('input').setAttribute('aria-label', 'Nama aspek ' + (i + 1));
        });
        updatePreview();
    }
    function bindRemove(btn) {
        btn.addEventListener('click', function () {
            var rows = list.querySelectorAll('.ar-aspek-row');
            if (rows.length <= 1) return;
            btn.closest('.ar-aspek-row').remove();
            renumber();
        });
    }
    function addRow(val) {
        var row = document.createElement('div');
        row.className = 'ar-aspek-row';
        row.draggable = true;
        var num = list.querySelectorAll('.ar-aspek-row').length + 1;
        row.innerHTML = '<span class="ar-grip" aria-hidden="true"><i class="bi bi-grip-vertical"></i></span>' +
            '<span class="ar-aspek-num">' + num + '</span>' +
            '<input type="text" name="nama_aspek[]" value="' + (val || '') + '" placeholder="Nama aspek penilaian" aria-label="Nama aspek ' + num + '" maxlength="255">' +
            '<button type="button" class="ar-row-remove" title="Hapus aspek" aria-label="Hapus aspek"><i class="bi bi-x-lg"></i></button>';
        list.appendChild(row);
        bindRemove(row.querySelector('.ar-row-remove'));
        bindDrag(row);
        renumber();
        row.querySelector('input').focus();
    }
    document.getElementById('arAddRow').addEventListener('click', function () { addRow(); });

    /* ---------- Drag & drop reorder ---------- */
    var dragRow = null;
    function bindDrag(row) {
        row.addEventListener('dragstart', function (e) {
            dragRow = row;
            row.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
            try { e.dataTransfer.setData('text/plain', ''); } catch (err) {}
        });
        row.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (dragRow === row) return;
            var all = Array.prototype.slice.call(list.querySelectorAll('.ar-aspek-row'));
            var rect = row.getBoundingClientRect();
            var before = (e.clientY - rect.top) < (rect.height / 2);
            var target = before ? row : row.nextSibling;
            list.insertBefore(dragRow, target);
            row.classList.add('is-over');
        });
        row.addEventListener('dragleave', function () { row.classList.remove('is-over'); });
        row.addEventListener('drop', function (e) { e.preventDefault(); row.classList.remove('is-over'); });
        row.addEventListener('dragend', function () {
            row.classList.remove('is-dragging');
            document.querySelectorAll('.ar-aspek-row.is-over').forEach(function (r) { r.classList.remove('is-over'); });
            dragRow = null;
            renumber();
        });
    }
    document.querySelectorAll('.ar-aspek-row').forEach(function (row) {
        bindRemove(row.querySelector('.ar-row-remove'));
        bindDrag(row);
    });

    /* ---------- Live preview ---------- */
    function updatePreview() {
        var rows = list.querySelectorAll('.ar-aspek-row');
        var count = rows.length;
        document.getElementById('arCountBadge').textContent = count;
        document.getElementById('arPreviewCount').textContent = count + ' aspek';
        var body = document.getElementById('arPreviewBody');
        if (count === 0) {
            body.innerHTML = '<div class="ar-preview-empty"><i class="bi bi-clipboard2"></i>Belum ada aspek</div>';
        } else {
            var html = '';
            rows.forEach(function (row, i) {
                html += '<div class="ar-preview-item"><span class="no">' + (i + 1) + '</span><span class="tx"></span></div>';
            });
            body.innerHTML = html;
            rows.forEach(function (row, i) {
                var val = row.querySelector('input').value.trim() || 'Aspek ' + (i + 1);
                body.querySelectorAll('.ar-preview-item')[i].querySelector('.tx').textContent = val;
            });
        }
        document.getElementById('arWarn').classList.toggle('show', count > 0 && count < 4);
        document.getElementById('arErr').classList.remove('show');
    }
    list.addEventListener('input', function (e) {
        if (e.target && e.target.matches('input[type="text"]')) {
            var row = e.target.closest('.ar-aspek-row');
            row.classList.remove('invalid');
            e.target.classList.remove('is-invalid');
            updatePreview();
        }
    });

    /* ---------- Navigation ---------- */
    document.getElementById('arToStep2').addEventListener('click', function () {
        if (!lombaId.value) {
            document.getElementById('arLombaErr').style.display = 'flex';
            if (window.LW && LW.toast) LW.toast('err', 'Pilih lomba', 'Pilih lomba terlebih dahulu untuk melanjutkan.');
            document.querySelector('#arLombaGrid .lw-pick-card') && document.querySelector('#arLombaGrid .lw-pick-card').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        goStep(2);
    });
    document.getElementById('arToStep1').addEventListener('click', function () { goStep(1); });

    /* ---------- Submit validation + double-submit protection ---------- */
    var submitting = false;
    form.addEventListener('submit', function (e) {
        if (submitting) { e.preventDefault(); return; }
        var ok = true;
        if (!lombaId.value) ok = false;
        var rows = list.querySelectorAll('.ar-aspek-row');
        rows.forEach(function (row) {
            var input = row.querySelector('input');
            var val = input.value.trim();
            row.classList.toggle('invalid', val === '');
            input.classList.toggle('is-invalid', val === '');
            if (val === '') ok = false;
        });
        if (!ok) {
            e.preventDefault();
            document.getElementById('arErr').classList.add('show');
            var firstInvalid = list.querySelector('.ar-aspek-row.invalid input');
            if (firstInvalid) firstInvalid.focus();
            return;
        }
        submitting = true;
        var btn = document.getElementById('arSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="ar-loading"><span class="ar-spin"></span> Menyimpan...</span>';
    });

    updatePreview();
    goStep(1);
})();
</script>
@endpush
@endsection
