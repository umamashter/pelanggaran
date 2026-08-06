@extends('layouts.main')
@section('title', 'Tambah Juri Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Juri Lomba — Assignment Wizard ---------- */
    .ljw-wizard { max-width: 940px; margin: 0 auto; }

    .lw-pick-grid .lw-pick-card { text-align: left; font-family: inherit; }
    .lw-pick-grid .lw-pick-title { flex: 1; min-width: 0; }

    .ljw-guru-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 10px; }
    .ljw-guru-card { position: relative; display: flex; align-items: center; gap: 11px; padding: 12px; cursor: pointer;
        background: var(--lw-card); border: 1.5px solid var(--lw-border); border-radius: 13px; transition: all .18s ease;
        font-family: inherit; text-align: left; width: 100%; }
    .ljw-guru-card:hover { transform: translateY(-2px); border-color: var(--lw-primary-border); box-shadow: var(--lw-shadow); }
    .ljw-guru-card.is-picked { border-color: var(--lw-primary); background: var(--lw-primary-soft); box-shadow: 0 0 0 3px var(--lw-primary-soft); }
    .ljw-avatar { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0; box-shadow: 0 3px 8px -2px rgba(15,23,42,.3); }
    .ljw-guru-info { flex: 1; min-width: 0; }
    .ljw-guru-name { display: block; font-size: 12.5px; font-weight: 700; color: var(--lw-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ljw-guru-nip { display: block; font-size: 10.5px; color: var(--lw-text-3); margin-top: 2px; }
    .ljw-check { flex-shrink: 0; width: 22px; height: 22px; border-radius: 50%; border: 1.5px solid var(--lw-border);
        display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; transition: all .2s ease; }
    .ljw-guru-card.is-picked .ljw-check { background: var(--lw-primary); border-color: var(--lw-primary); }

    .ljw-selected { display: none; margin-top: 16px; border: 1.5px dashed var(--lw-primary-border); border-radius: 14px; padding: 14px; background: var(--lw-card); }
    .ljw-selected.is-show { display: block; animation: lwFadeUp .25s ease both; }
    .ljw-selected-title { font-size: 11px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .ljw-selected-title .ljw-count-badge { margin-left: auto; background: var(--lw-primary-soft); color: var(--lw-primary); border: 1px solid var(--lw-primary-border);
        min-width: 22px; height: 22px; padding: 0 7px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; }
    .ljw-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .ljw-chip { display: inline-flex; align-items: center; gap: 7px; background: var(--lw-primary-soft); color: var(--lw-primary);
        border: 1px solid var(--lw-primary-border); padding: 6px 8px 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; animation: lwFadeUp .2s ease both; }
    .ljw-chip .ljw-rm { width: 19px; height: 19px; border-radius: 50%; background: rgba(43,60,120,.15); display: inline-flex; align-items: center;
        justify-content: center; cursor: pointer; font-size: 13px; line-height: 1; transition: all .15s ease; }
    .ljw-chip .ljw-rm:hover { background: var(--lw-red); color: #fff; transform: scale(1.12); }

    .ljw-preview { display: none; margin-top: 16px; border-radius: 14px; padding: 16px 18px;
        background: var(--lw-grad-soft); border: 1px solid var(--lw-primary-border); border-left: 4px solid var(--lw-primary); }
    .ljw-preview.is-show { display: block; animation: lwFadeUp .25s ease both; }
    .ljw-preview-title { font-size: 11px; font-weight: 800; color: var(--lw-primary); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
    .ljw-preview-body { display: flex; flex-wrap: wrap; align-items: center; gap: 16px; }
    .ljw-preview-stats { display: flex; gap: 26px; }
    .ljw-preview-stat { text-align: center; }
    .ljw-preview-stat .val { font-size: 24px; font-weight: 800; color: var(--lw-text); line-height: 1.1; font-variant-numeric: tabular-nums; }
    .ljw-preview-stat .lbl { font-size: 9.5px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .4px; }
    .ljw-preview-avatars { display: flex; align-items: center; flex: 1; min-width: 120px; justify-content: flex-end; flex-wrap: wrap; gap: 6px; }
    .ljw-preview-avatar { width: 30px; height: 30px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 800; color: #fff; box-shadow: 0 3px 8px -2px rgba(15,23,42,.3); }

    .ljw-error { display: none; margin-top: 14px; border-radius: 12px; padding: 12px 14px; border: 1px solid var(--lw-red-border);
        background: var(--lw-red-soft); color: var(--lw-red); font-size: 12.5px; font-weight: 600; align-items: center; gap: 8px; }
    .ljw-error.is-show { display: flex; animation: lwFadeUp .25s ease both; }

    .ljw-badge-check { display: none; align-items: center; gap: 7px; font-size: 12px; font-weight: 700; color: var(--lw-green); }
    .ljw-badge-check.is-show { display: inline-flex; }

    @media (max-width: 575.98px) {
        .ljw-preview-body { flex-direction: column; align-items: flex-start; }
        .ljw-preview-avatars { justify-content: flex-start; }
    }
</style>

<div class="lw-mod">

<div class="lw-card lw-card-pad lw-form-card ljw-wizard">

    <div class="lw-hero" style="margin:-26px -26px 22px;border-radius:0;">
        <div class="lw-hero-grid">
            <div class="lw-hero-left">
                <span class="lw-hero-icon"><i class="bi bi-bank"></i></span>
                <div>
                    <h1 class="lw-hero-title">Assignment Juri Baru</h1>
                    <p class="lw-hero-sub">Pilih kompetisi, lalu tetapkan guru sebagai juri penilai — dua langkah sederhana.</p>
                </div>
            </div>
            <div class="lw-hero-right">
                <a href="{{ route('juri-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="lw-stepper" id="ljwStepper">
        <div class="lw-step active" data-step="1">
            <div class="lw-step-dot"><i class="bi bi-trophy-fill"></i></div>
            <div class="lw-step-txt"><b>Pilih Lomba</b><span>Kompetisi yang dinilai</span></div>
        </div>
        <div class="lw-step-line"></div>
        <div class="lw-step" data-step="2">
            <div class="lw-step-dot"><i class="bi bi-person-vcard-fill"></i></div>
            <div class="lw-step-txt"><b>Tentukan Juri</b><span>Pilih satu atau lebih guru</span></div>
        </div>
    </div>

    @if(session('error'))
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    <form id="ljwForm" action="{{ route('juri-lomba.store') }}" method="POST" novalidate>
        @csrf

        {{-- STEP 1 : PILIH LOMBA --}}
        <section class="lw-wizard-pane is-show" data-pane="1">
            <div class="lw-form-section"><i class="bi bi-flag-fill"></i> Pilih Kompetisi</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Klik kartu untuk memilih lomba yang akan ditugaskan juri.</p>

            <div class="lw-search" style="margin-bottom:14px;">
                <i class="bi bi-search"></i>
                <input type="search" class="lw-control" id="ljwLombaSearch" placeholder="Cari nama lomba..." autocomplete="off">
            </div>

            @forelse($lombas as $lomba)
                <div class="lw-pick-grid" id="ljwLombaGrid" style="margin-bottom:14px;">
                    <button type="button" class="lw-pick-card" data-id="{{ $lomba->id }}" data-nama="{{ e($lomba->nama) }}" data-jenis="{{ $lomba->jenis }}">
                        <span class="lw-pick-icon"><i class="bi bi-trophy-fill"></i></span>
                        <span class="lw-pick-title">{{ $lomba->nama }}</span>
                        <span class="lw-chip lw-chip-mini" style="display:inline-flex;font-size:10px;min-height:22px;padding:0 8px;">
                            <i class="bi {{ $lomba->jenis === 'Tim' ? 'bi-people-fill' : 'bi-person-fill' }}"></i>{{ $lomba->jenis }}
                        </span>
                        <span class="lw-pick-check"><i class="bi bi-check-lg"></i></span>
                    </button>
                </div>
            @empty
                <div class="lw-empty">
                    <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-trophy"></i></div></div>
                    <div class="lw-empty-title">Belum Ada Lomba</div>
                    <p class="lw-empty-sub">Tambahkan lomba terlebih dahulu sebelum menugaskan juri.</p>
                </div>
            @endforelse

            <div class="ljw-error" id="ljwLombaErr"><i class="bi bi-exclamation-circle-fill"></i> Pilih satu lomba terlebih dahulu untuk melanjutkan.</div>

            <div class="lw-wizard-nav">
                <a href="{{ route('juri-lomba.index') }}" class="lw-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--solid" id="ljwNextBtn">Lanjut ke Juri <i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        {{-- STEP 2 : TENTUKAN JURI --}}
        <section class="lw-wizard-pane" data-pane="2">
            <div class="lw-form-section"><i class="bi bi-person-vcard-fill"></i> Tentukan Juri</div>
            <p class="lw-help-text" style="margin:-10px 0 14px;">Klik kartu guru untuk menambah atau menghapus dari daftar juri.</p>

            <div class="lw-search" style="margin-bottom:14px;">
                <i class="bi bi-search"></i>
                <input type="search" class="lw-control" id="ljwGuruSearch" placeholder="Cari guru berdasarkan nama atau NIP..." autocomplete="off">
            </div>

            @forelse($gurus as $guru)
                <div class="ljw-guru-grid" id="ljwGuruGrid" style="margin-bottom:14px;">
                    <button type="button" class="ljw-guru-card" data-id="{{ $guru->id }}" data-nama="{{ e($guru->nama) }}" data-nip="{{ $guru->nip ?? '' }}">
                        <span class="ljw-avatar" style="background:{{ lw_ava_color($guru->nama) }};">{{ lw_initial($guru->nama) }}</span>
                        <span class="ljw-guru-info">
                            <span class="ljw-guru-name">{{ $guru->nama }}</span>
                            <span class="ljw-guru-nip">{{ $guru->nip ?? '-' }}</span>
                        </span>
                        <span class="ljw-check"><i class="bi bi-check-lg"></i></span>
                    </button>
                </div>
            @empty
                <div class="lw-empty" style="padding:32px 16px;">
                    <div class="lw-empty-illus" style="width:96px;height:96px;"><div class="ring"></div><div class="core"><i class="bi bi-person-slash"></i></div></div>
                    <div class="lw-empty-title" style="font-size:14px;">Belum Ada Guru</div>
                    <p class="lw-empty-sub" style="font-size:12px;">Tambahkan data guru terlebih dahulu sebelum menugaskan juri.</p>
                </div>
            @endforelse

            <div class="ljw-selected" id="ljwSelected">
                <div class="ljw-selected-title"><i class="bi bi-check-circle-fill"></i> Juri Dipilih <span class="ljw-count-badge" id="ljwSelCount">0</span></div>
                <div class="ljw-chips" id="ljwChipWrap"></div>
            </div>

            <div class="ljw-preview" id="ljwPreview">
                <div class="ljw-preview-title"><i class="bi bi-eye-fill"></i> Ringkasan Live</div>
                <div class="ljw-preview-body">
                    <div class="ljw-preview-stats">
                        <div class="ljw-preview-stat"><div class="val" id="ljwLiveTotal">0</div><div class="lbl">Total Juri</div></div>
                        <div class="ljw-preview-stat"><div class="val" id="ljwLiveNama">-</div><div class="lbl">Lomba</div></div>
                    </div>
                    <div class="ljw-preview-avatars" id="ljwLiveAvatars"></div>
                </div>
            </div>

            <div class="ljw-error" id="ljwGuruErr"><i class="bi bi-exclamation-circle-fill"></i> Pilih minimal satu guru sebagai juri.</div>

            <div class="lw-wizard-nav">
                <span class="ljw-badge-check" id="ljwReady"><i class="bi bi-check-circle-fill"></i> Siap disimpan</span>
                <span class="spacer"></span>
                <button type="button" class="lw-btn lw-btn--ghost" id="ljwBackBtn"><i class="bi bi-arrow-left"></i> Kembali</button>
                <button type="submit" class="lw-btn lw-btn--solid" id="ljwSubmitBtn" disabled>
                    <span class="ljw-btn-label"><i class="bi bi-save-fill"></i> Simpan Assignment</span>
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function avaColor(name) {
        var palette = ['#2b3c78', '#e7a615', '#0e9f6e', '#d97706', '#db2777', '#3b82f6', '#7c3aed', '#0891b2'];
        var h = 0, s = String(name);
        for (var i = 0; i < s.length; i++) { h = (h * 31 + s.charCodeAt(i)) & 0x7fffffff; }
        return palette[h % palette.length];
    }
    function initials(name) {
        var p = String(name || '').trim().split(/\s+/);
        if (!p[0]) { return '?'; }
        var r = p[0].charAt(0);
        if (p.length > 1) { r += p[p.length - 1].charAt(0); }
        return r.toUpperCase();
    }

    var form = $id('ljwForm');
    var selectedLomba = null, selectedGurus = {};

    /* ---------- ripple ---------- */
    [['ljwNextBtn', 1], ['ljwBackBtn', 1], ['ljwSubmitBtn', 1]].forEach(function (x) {
        var b = $id(x[0]);
        if (b) { b.addEventListener('mousedown', function (e) { if (window.LW && LW.ripple) { LW.ripple(e); } }); }
    });

    /* ── Step 1 : lomba pick cards ── */
    var lombaCards = Array.prototype.slice.call(document.querySelectorAll('#ljwLombaGrid .lw-pick-card'));
    $id('ljwLombaSearch').addEventListener('input', function () {
        var t = this.value.toLowerCase().trim();
        lombaCards.forEach(function (c) {
            c.style.display = (!t || (c.dataset.nama || '').toLowerCase().indexOf(t) !== -1) ? '' : 'none';
        });
    });
    lombaCards.forEach(function (c) {
        c.addEventListener('click', function () {
            lombaCards.forEach(function (o) { o.classList.remove('is-picked'); });
            c.classList.add('is-picked');
            selectedLomba = { id: c.dataset.id, nama: c.dataset.nama, jenis: c.dataset.jenis };
            syncLombaInput();
            updatePreviewNama();
            $id('ljwLombaErr').classList.remove('is-show');
        });
    });

    /* ── Step 2 : guru pick cards ── */
    var guruCards = Array.prototype.slice.call(document.querySelectorAll('#ljwGuruGrid .ljw-guru-card'));
    $id('ljwGuruSearch').addEventListener('input', function () {
        var t = this.value.toLowerCase().trim();
        guruCards.forEach(function (c) {
            var hit = !t || (c.dataset.nama || '').toLowerCase().indexOf(t) !== -1 || (c.dataset.nip || '').toLowerCase().indexOf(t) !== -1;
            c.style.display = hit ? '' : 'none';
        });
    });
    guruCards.forEach(function (c) {
        c.addEventListener('click', function () {
            var id = c.dataset.id;
            if (selectedGurus[id]) {
                delete selectedGurus[id];
                c.classList.remove('is-picked');
            } else {
                selectedGurus[id] = c.dataset.nama;
                c.classList.add('is-picked');
            }
            renderChips();
        });
    });

    /* ── hidden inputs ── */
    var lombaInput = document.createElement('input');
    lombaInput.type = 'hidden'; lombaInput.name = 'lomba_id';
    form.appendChild(lombaInput);
    function syncLombaInput() { lombaInput.value = selectedLomba ? selectedLomba.id : ''; }
    function syncGuruInputs() {
        document.querySelectorAll('input[name="guru_id[]"]').forEach(function (e) { e.remove(); });
        Object.keys(selectedGurus).forEach(function (id) {
            var i = document.createElement('input');
            i.type = 'hidden'; i.name = 'guru_id[]'; i.value = id;
            form.appendChild(i);
        });
    }

    /* ── render chips + preview ── */
    function renderChips() {
        var ids = Object.keys(selectedGurus), cnt = ids.length;
        $id('ljwSelCount').textContent = cnt;
        $id('ljwLiveTotal').textContent = cnt;
        $id('ljwSelected').classList.toggle('is-show', cnt > 0);
        $id('ljwPreview').classList.toggle('is-show', cnt > 0);
        $id('ljwReady').classList.toggle('is-show', cnt > 0 && !!selectedLomba);
        $id('ljwSubmitBtn').disabled = cnt < 1;
        $id('ljwGuruErr').classList.remove('is-show');

        var wrap = $id('ljwChipWrap');
        wrap.innerHTML = '';
        ids.forEach(function (id) {
            var chip = document.createElement('span');
            chip.className = 'ljw-chip';
            chip.innerHTML = '<i class="bi bi-bank"></i>' + selectedGurus[id] + '<span class="ljw-rm" data-rm="' + id + '" aria-label="Hapus">&times;</span>';
            wrap.appendChild(chip);
        });
        wrap.querySelectorAll('.ljw-rm').forEach(function (rm) {
            rm.addEventListener('click', function () {
                var id = rm.dataset.rm;
                delete selectedGurus[id];
                var card = document.querySelector('.ljw-guru-card[data-id="' + id + '"]');
                if (card) { card.classList.remove('is-picked'); }
                renderChips();
            });
        });
        renderAvatars(ids);
        syncGuruInputs();
    }

    function renderAvatars(ids) {
        var avWrap = $id('ljwLiveAvatars');
        avWrap.innerHTML = '';
        ids.slice(0, 8).forEach(function (id) {
            var card = document.querySelector('.ljw-guru-card[data-id="' + id + '"]');
            var name = card ? card.dataset.nama : selectedGurus[id];
            var a = document.createElement('span');
            a.className = 'ljw-preview-avatar';
            a.style.background = avaColor(name);
            a.textContent = initials(name);
            a.title = name;
            avWrap.appendChild(a);
        });
        if (ids.length > 8) {
            var more = document.createElement('span');
            more.className = 'lw-chip lw-chip-mini';
            more.textContent = '+' + (ids.length - 8);
            avWrap.appendChild(more);
        }
    }

    function updatePreviewNama() {
        $id('ljwLiveNama').textContent = selectedLomba ? selectedLomba.nama : '-';
    }

    /* ── navigation ── */
    $id('ljwNextBtn').addEventListener('click', function () {
        if (!selectedLomba) {
            var e = $id('ljwLombaErr');
            e.classList.remove('is-show'); void e.offsetWidth;
            e.classList.add('is-show');
            $id('ljwLombaSearch').focus();
            return;
        }
        $id('ljwLombaErr').classList.remove('is-show');
        goStep(2);
    });
    $id('ljwBackBtn').addEventListener('click', function () { goStep(1); });

    /* ── submit guard ── */
    var submitting = false;
    form.addEventListener('submit', function (e) {
        if (!selectedLomba) { e.preventDefault(); goStep(1); $id('ljwLombaErr').classList.add('is-show'); return false; }
        if (Object.keys(selectedGurus).length === 0) {
            e.preventDefault();
            var g = $id('ljwGuruErr');
            g.classList.remove('is-show'); void g.offsetWidth;
            g.classList.add('is-show');
            return false;
        }
        if (submitting) { e.preventDefault(); return false; }
        submitting = true;
        syncLombaInput();
        syncGuruInputs();
        var btn = $id('ljwSubmitBtn');
        btn.disabled = true;
        btn.querySelector('.ljw-btn-label').classList.add('d-none');
        btn.querySelector('.spinner-border').classList.remove('d-none');
        return true;
    });
})();
</script>
@endpush
@endsection
