@extends('layouts.main')
@section('title', 'Edit Rubrik Penilaian')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Rubrik Builder — Edit ---------- */
    .ar-wizard { max-width: 1180px; margin: 0 auto; }

    .ar-builder-grid { display: grid; grid-template-columns: 1fr 320px; gap: 18px; align-items: start; }
    .ar-builder-main { display: flex; flex-direction: column; gap: 14px; }
    .ar-aspek-list { display: flex; flex-direction: column; gap: 10px; }
    .ar-aspek-row { display: flex; align-items: center; gap: 10px; padding: 11px 13px; background: var(--lw-card); border: 1.5px solid var(--lw-border); border-radius: 13px; transition: border-color .2s, box-shadow .2s, opacity .2s; }
    .ar-aspek-row:hover { border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow); }
    .ar-aspek-row.is-dragging { opacity: .5; border-style: dashed; }
    .ar-aspek-row.is-over { border-color: var(--lw-primary); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
    .ar-aspek-row.invalid { border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .ar-aspek-row.is-locked-row { opacity: .8; }
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
    .ar-add-row:disabled { opacity: .4; cursor: not-allowed; }
    .ar-add-row:disabled:hover { border-color: var(--lw-border); color: var(--lw-text-2); background: transparent; }

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
    $isLocked = $lomba->is_haflah_selesai ?? false;
    $existing = old('nama_aspek') ?: $aspekPenilaians->pluck('nama_aspek')->toArray();
    if (count($existing) < 4) { $existing = array_pad($existing, 4, ''); }
    $firstId = $aspekPenilaians->first()->id ?? null;
@endphp

<div class="lw-mod">

<div class="lw-card lw-card-pad ar-wizard">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-journal-richtext"></i></span>
                <div>
                    <h1 class="lw-hero-title">Edit Rubrik Penilaian</h1>
                    <p class="lw-hero-sub">Perbarui aspek penilaian <b>{{ $lomba->nama }}</b> — drag untuk mengatur urutan.</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <span class="lw-chip {{ $isLocked ? 'lw-chip--red' : 'lw-chip--green' }}"><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-unlock' }}"></i>{{ $isLocked ? 'Terkunci — Haflah Selesai' : 'Aktif — Dapat Diubah' }}</span>
                <a href="{{ route('aspek-penilaian.show', $lomba->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    @if($isLocked)
        <div class="lw-lock-banner"><i class="bi bi-lock-fill"></i><div>Haflah telah selesai. <b>Rubrik ini tidak dapat diubah atau dihapus.</b></div></div>
    @endif

    @if($errors->any())
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    <form id="arForm" method="POST" action="{{ route('aspek-penilaian.update', $firstId) }}" novalidate>
        @csrf @method('PUT')

        <div class="ar-builder-grid">
            <div class="ar-builder-main">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="lw-form-section" style="margin-bottom:0;"><i class="bi bi-trophy"></i> Lomba</div>
                        <p class="lw-help-text" style="margin:2px 0 0;">Rubrik dipasang pada lomba ini.</p>
                    </div>
                    <span class="lw-chip lw-chip--navy"><i class="bi bi-hash"></i><span id="arCountBadge">{{ count($existing) }}</span> aspek</span>
                </div>

                <div class="lw-card lw-card-pad" style="margin:14px 0 0;">
                    <div class="d-flex align-items-center gap-3">
                        <span class="lw-kpi-icon navy" style="width:44px;height:44px;border-radius:12px;font-size:18px;flex-shrink:0;"><i class="bi bi-trophy"></i></span>
                        <div style="min-width:0;">
                            <div class="fw-bold" style="font-size:13.5px;color:var(--lw-text);">{{ $lomba->nama }}</div>
                            <div style="display:flex;gap:10px;font-size:11px;color:var(--lw-text-3);margin-top:2px;">
                                <span><i class="bi {{ ($lomba->jenis ?? 'Individu') === 'Tim' ? 'bi-people' : 'bi-person' }}"></i> {{ $lomba->jenis ?? 'Individu' }}</span>
                                <span><i class="bi bi-flag"></i> {{ $lomba->status ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3" style="margin-top:14px;">
                    <label class="d-block" style="font-size:10.5px;font-weight:700;color:var(--lw-text-3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Ganti Lomba</label>
                    <select name="lomba_id" id="arLombaId" class="lw-select w-100" {{ $isLocked ? 'disabled' : '' }}>
                        @foreach($lombas as $l)
                        <option value="{{ $l->id }}" {{ (string)(old('lomba_id', $lomba->id)) === (string)$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    @if($isLocked)
                    <input type="hidden" name="lomba_id" value="{{ $lomba->id }}">
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2" style="margin-top:6px;">
                    <div>
                        <div class="lw-form-section" style="margin-bottom:0;"><i class="bi bi-list-check"></i> Daftar Aspek</div>
                        <p class="lw-help-text" style="margin:2px 0 0;">Tarik <i class="bi bi-grip-vertical"></i> untuk mengubah urutan — minimal 4 aspek.</p>
                    </div>
                </div>

                <div class="ar-aspek-list" id="arAspekList" style="margin-top:14px;">
                    @foreach($existing as $idx => $nama)
                    <div class="ar-aspek-row {{ $isLocked ? 'is-locked-row' : '' }}" draggable="{{ $isLocked ? 'false' : 'true' }}">
                        <span class="ar-grip" aria-hidden="true"><i class="bi {{ $isLocked ? 'bi-lock' : 'bi-grip-vertical' }}"></i></span>
                        <span class="ar-aspek-num">{{ $idx + 1 }}</span>
                        <input type="text" name="nama_aspek[]" value="{{ $nama }}" placeholder="Nama aspek penilaian" aria-label="Nama aspek {{ $idx + 1 }}" maxlength="255" {{ $isLocked ? 'readonly' : '' }}>
                        <button type="button" class="ar-row-remove" title="{{ $isLocked ? 'Terkunci' : 'Hapus aspek' }}" aria-label="Hapus aspek" {{ $isLocked ? 'disabled' : '' }}><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-x-lg' }}"></i></button>
                    </div>
                    @endforeach
                </div>

                <button type="button" id="arAddRow" class="ar-add-row" style="width:100%;margin-top:12px;" {{ $isLocked ? 'disabled' : '' }}><i class="bi bi-plus-lg"></i> Tambah Aspek</button>

                <div class="ar-aspek-err" id="arErr"><i class="bi bi-exclamation-triangle-fill"></i><span>Setiap aspek wajib diisi dan minimal 4 aspek.</span></div>
                <div class="ar-aspek-warn" id="arWarn"><i class="bi bi-info-circle-fill"></i><span>Rubrik dengan kurang dari 4 aspek dianggap "Rubrik Minimal".</span></div>
            </div>

            <aside class="ar-preview-panel">
                <div class="ar-preview-card">
                    <div class="ar-preview-head">
                        <b><i class="bi bi-eye"></i> Live Preview Juri</b>
                        <span class="ar-preview-count" id="arPreviewCount">{{ count($existing) }} aspek</span>
                    </div>
                    <div class="ar-preview-body" id="arPreviewBody">
                        <div class="ar-preview-empty"><i class="bi bi-clipboard2"></i>Belum ada aspek</div>
                    </div>
                </div>
                <div class="ar-panel-note"><i class="bi bi-lightbulb"></i><span>Panel ini menampilkan rubrik persis seperti yang akan dilihat juri saat menilai.</span></div>
            </aside>
        </div>

        <div class="lw-wizard-nav">
            <div class="d-flex align-items-center gap-2" style="font-size:12px;color:var(--lw-text-3);"><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-check-circle' }}"></i><span id="arFootInfo">Rubrik untuk {{ $lomba->nama }}</span></div>
            <span class="spacer"></span>
            <a href="{{ route('aspek-penilaian.show', $lomba->id) }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
            <button type="submit" id="arSubmit" class="lw-btn lw-btn--success" {{ $isLocked ? 'disabled' : '' }}><i class="bi bi-check2"></i> Simpan Rubrik</button>
        </div>
    </form>

</div>
</div>

@push('scripts')
<script>
(function () {
    var form = document.getElementById('arForm');
    if (!form) return;
    var list = document.getElementById('arAspekList');
    var isLocked = {{ $isLocked ? 'true' : 'false' }};
    if (isLocked) return;

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

    /* ---------- Submit validation + double-submit protection ---------- */
    var submitting = false;
    form.addEventListener('submit', function (e) {
        if (submitting) { e.preventDefault(); return; }
        var ok = true;
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
})();
</script>
@endpush
@endsection
