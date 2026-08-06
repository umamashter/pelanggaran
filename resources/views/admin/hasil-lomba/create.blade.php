@extends('layouts.main')
@section('title', 'Tambah Hasil Lomba')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .hl-mod { --hl-radius: 16px; }
    .hl-wrap { max-width: 1240px; margin: 0 auto; }
    .hl-layout { display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(320px, .8fr); gap: 20px; align-items: start; }
    .hl-panel { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 18px; box-shadow: var(--lw-shadow); overflow: hidden; }
    .hl-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--lw-border); flex-wrap: wrap; }
    .hl-panel-head b { font-size: 14.5px; font-weight: 800; color: var(--lw-text); display: inline-flex; align-items: center; gap: 8px; }
    .hl-panel-head b i { color: var(--lw-primary); }
    .hl-panel-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-panel-body { padding: 20px; }
    .hl-stepper-note { margin-bottom: 16px; font-size: 12px; color: var(--lw-text-3); }
    .hl-select { height: 46px; }
    .hl-preview-card { display: none; margin-top: 16px; padding: 18px; border-radius: 16px; background: linear-gradient(135deg, rgba(43,60,120,.09), rgba(231,166,21,.03)); border: 1px solid var(--lw-primary-border); }
    .hl-preview-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 14px; }
    .hl-preview-stat { padding: 12px; border-radius: 14px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .hl-preview-stat .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-preview-stat .v { margin-top: 4px; font-size: 15px; font-weight: 800; color: var(--lw-text); }
    .hl-podium-wrap { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 18px; }
    .hl-podium-card { display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: center; padding: 18px 14px; border-radius: 18px; border: 1px solid var(--lw-border); background: var(--lw-bg); min-height: 168px; }
    .hl-podium-card .icon { font-size: 28px; line-height: 1; }
    .hl-podium-card .rank { margin-top: 10px; font-size: 11px; text-transform: uppercase; letter-spacing: .55px; color: var(--lw-text-3); font-weight: 700; }
    .hl-podium-card .name { margin-top: 6px; font-size: 14px; font-weight: 800; color: var(--lw-text); line-height: 1.35; }
    .hl-podium-card .score { margin-top: 4px; font-size: 16px; font-weight: 800; color: var(--lw-primary); }
    .hl-podium-card.gold { background: linear-gradient(180deg,#fff8db,#fef3c7); border-color: rgba(217,119,6,.28); }
    .hl-podium-card.silver { background: linear-gradient(180deg,#f8fafc,#e2e8f0); }
    .hl-podium-card.bronze { background: linear-gradient(180deg,#ffedd5,#fed7aa); }
    .hl-list { margin-top: 18px; display: grid; gap: 10px; }
    .hl-list-item { display: grid; grid-template-columns: 52px minmax(0,1fr) 110px 120px 54px; gap: 10px; align-items: center; padding: 12px 14px; border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); }
    .hl-rank { width: 42px; height: 42px; border-radius: 14px; background: var(--lw-bg); border: 1px solid var(--lw-border); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; color: var(--lw-text); }
    .hl-name { font-size: 13.5px; font-weight: 800; color: var(--lw-text); }
    .hl-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-score { font-size: 15px; font-weight: 800; color: var(--lw-primary); text-align: right; }
    .hl-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; font-size: 11px; font-weight: 700; border: 1px solid transparent; }
    .hl-badge.gold { background: #fff8db; color: #a16207; border-color: rgba(217,119,6,.28); }
    .hl-badge.silver { background: #f8fafc; color: #475569; border-color: rgba(148,163,184,.25); }
    .hl-badge.bronze { background: #ffedd5; color: #9a3412; border-color: rgba(194,120,59,.25); }
    .hl-badge.neutral { background: var(--lw-bg); color: var(--lw-text-2); border-color: var(--lw-border); }
    .hl-check { width: 22px; height: 22px; accent-color: #2b3c78; cursor: pointer; }
    .hl-empty { padding: 28px 18px; text-align: center; border: 1px dashed var(--lw-border); border-radius: 16px; color: var(--lw-text-3); }
    .hl-summary-card { position: sticky; top: 92px; padding: 20px; border-radius: 18px; background: linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,255,255,.74)); border: 1px solid var(--lw-border); box-shadow: var(--lw-shadow); backdrop-filter: blur(10px); }
    html.dark-mode .hl-summary-card { background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04)); }
    .hl-summary-title { font-size: 13px; font-weight: 800; color: var(--lw-text); display: inline-flex; align-items: center; gap: 7px; }
    .hl-summary-title i { color: var(--lw-primary); }
    .hl-summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 16px; }
    .hl-summary-item { padding: 12px; border-radius: 14px; background: var(--lw-card); border: 1px solid var(--lw-border); }
    .hl-summary-item .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-summary-item .v { margin-top: 4px; font-size: 16px; font-weight: 800; color: var(--lw-text); }
    .hl-selected { margin-top: 16px; padding: 14px; border-radius: 14px; background: var(--lw-primary-soft); border: 1px solid var(--lw-primary-border); color: var(--lw-primary); font-size: 12px; font-weight: 700; display: none; }
    .hl-actions { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--lw-border); }
    .hl-loading { display: inline-flex; align-items: center; gap: 8px; }
    .hl-spin { width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: hlSpin .7s linear infinite; }
    @keyframes hlSpin { to { transform: rotate(360deg); } }
    @media (max-width: 1199.98px) { .hl-layout { grid-template-columns: 1fr; } .hl-summary-card { position: static; } }
    @media (max-width: 767.98px) { .hl-preview-grid, .hl-podium-wrap, .hl-summary-grid { grid-template-columns: 1fr; } .hl-list-item { grid-template-columns: 46px minmax(0,1fr); } .hl-score, .hl-list-item .hl-badge, .hl-list-item .ck-wrap { grid-column: 2; text-align: left; } }
</style>
@endpush

@section('content')
@include('component.admin.lomba-workspace')

@php
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    $activeHaflah = \App\Models\HaflatulImtihan::find(session('haflah_id'));
@endphp

<div class="lw-mod hl-mod">
    <div class="hl-wrap">
        <div class="lw-hero">
            <div class="lw-hero-grid">
                <div class="lw-hero-left">
                    <span class="lw-hero-icon"><i class="bi bi-stars"></i></span>
                    <div>
                        <h1 class="lw-hero-title">Winner Generator Wizard</h1>
                        <p class="lw-hero-sub">Bangun hasil resmi dari nilai penilaian, preview ranking otomatis, lalu pilih pemenang yang akan disahkan.</p>
                        <div class="lw-hero-badges">
                            <span class="lw-hero-badge"><i class="bi bi-diagram-3"></i>{{ optional($activeHaflah)->nama_acara ?: 'Haflah belum dipilih' }}</span>
                            <span class="lw-hero-badge"><i class="bi bi-calendar-event"></i>{{ $today }}</span>
                            <span class="lw-hero-badge"><i class="bi bi-trophy"></i>{{ $lombas->count() }} lomba siap digenerate</span>
                        </div>
                    </div>
                </div>
                <div class="lw-hero-right">
                    <a href="{{ route('hasil-lomba.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    Terdapat kesalahan:
                    <ul class="mb-0 mt-1 ps-3" style="font-weight:500;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="hl-layout">
            <div class="hl-panel">
                <div class="hl-panel-head">
                    <div>
                        <b><i class="bi bi-magic"></i> Generate Hasil Lomba</b>
                        <div class="hl-panel-sub">4 langkah: pilih lomba, muat ranking otomatis, cek podium, lalu pilih hasil resmi.</div>
                    </div>
                </div>
                <div class="hl-panel-body">
                    <form action="{{ route('hasil-lomba.store') }}" method="POST" id="hasilForm">
                        @csrf

                        <div class="lw-stepper" id="hlStepper">
                            <div class="lw-step active" data-step="1"><span class="lw-step-dot">1</span><div class="lw-step-txt"><b>Pilih Lomba</b><span>Card preview</span></div></div>
                            <div class="lw-step-line"></div>
                            <div class="lw-step" data-step="2"><span class="lw-step-dot">2</span><div class="lw-step-txt"><b>Muat Peserta</b><span>Ranking otomatis</span></div></div>
                            <div class="lw-step-line"></div>
                            <div class="lw-step" data-step="3"><span class="lw-step-dot">3</span><div class="lw-step-txt"><b>Preview Podium</b><span>Top 3 visual</span></div></div>
                            <div class="lw-step-line"></div>
                            <div class="lw-step" data-step="4"><span class="lw-step-dot">4</span><div class="lw-step-txt"><b>Tetapkan Hasil</b><span>Multi-select</span></div></div>
                        </div>

                        <div class="lw-wizard-pane is-show" data-pane="1">
                            <div class="hl-stepper-note">Pilih lomba yang sudah memiliki nilai penilaian agar hasil bisa digenerate.</div>
                            <div class="lw-field">
                                <label for="lomba_id" class="lw-field-label"><i class="bi bi-trophy"></i> Lomba <span style="color:var(--lw-red)">*</span></label>
                                <select id="lomba_id" name="lomba_id" class="lw-select hl-select" required>
                                    <option value="">-- Pilih Lomba --</option>
                                    @foreach($lombas as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($lombas->isEmpty())
                                <div class="hl-empty mt-3"><i class="bi bi-info-circle me-1"></i> Tidak ada lomba yang memiliki data penilaian.</div>
                            @endif
                            <div class="hl-preview-card" id="lombaPreview">
                                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                    <div>
                                        <div class="hl-summary-title"><i class="bi bi-binoculars"></i> Card Preview</div>
                                        <div class="mt-2" style="font-size:18px;font-weight:800;color:var(--lw-text);" id="previewNamaLomba">-</div>
                                        <div style="font-size:12px;color:var(--lw-text-3);margin-top:4px;" id="previewSubtitle">Ranking akan diambil otomatis dari total nilai tertinggi.</div>
                                    </div>
                                    <span class="lw-chip lw-chip--navy" id="previewJenis">Belum dipilih</span>
                                </div>
                                <div class="hl-preview-grid">
                                    <div class="hl-preview-stat"><div class="k">Jumlah Peserta</div><div class="v" id="previewJumlahPeserta">0</div></div>
                                    <div class="hl-preview-stat"><div class="k">Jumlah Juri</div><div class="v" id="previewJumlahJuri">Auto</div></div>
                                    <div class="hl-preview-stat"><div class="k">Nilai Tertinggi</div><div class="v" id="previewTertinggi">0</div></div>
                                    <div class="hl-preview-stat"><div class="k">Nilai Rata-rata</div><div class="v" id="previewRata">0</div></div>
                                </div>
                            </div>
                            <div class="hl-actions">
                                <div class="text-muted small">Step 1 dari 4</div>
                                <button type="button" class="lw-btn lw-btn--solid" id="toPane2" disabled><i class="bi bi-arrow-right"></i> Lanjut Muat Peserta</button>
                            </div>
                        </div>

                        <div class="lw-wizard-pane" data-pane="2">
                            <div class="hl-stepper-note">Peserta dimuat via AJAX dan diurutkan otomatis berdasarkan total nilai tertinggi.</div>
                            <div id="peserta-wrapper">
                                <div id="peserta-body" class="hl-list">
                                    <div class="hl-empty">Pilih lomba terlebih dahulu</div>
                                </div>
                            </div>
                            <div class="hl-actions">
                                <button type="button" class="lw-btn lw-btn--ghost" id="backTo1"><i class="bi bi-arrow-left"></i> Kembali</button>
                                <button type="button" class="lw-btn lw-btn--solid" id="toPane3" disabled><i class="bi bi-arrow-right"></i> Preview Podium</button>
                            </div>
                        </div>

                        <div class="lw-wizard-pane" data-pane="3">
                            <div class="hl-stepper-note">Tinjau 3 skor teratas sebagai podium, lalu lihat ranking berikutnya sebagai daftar finalis.</div>
                            <div class="hl-podium-wrap" id="podiumWrap">
                                <div class="hl-podium-card silver"><div class="icon"><i class="bi bi-award-fill"></i></div><div class="rank">Juara 2</div><div class="name">-</div><div class="score">0</div></div>
                                <div class="hl-podium-card gold"><div class="icon"><i class="bi bi-trophy-fill"></i></div><div class="rank">Juara 1</div><div class="name">-</div><div class="score">0</div></div>
                                <div class="hl-podium-card bronze"><div class="icon"><i class="bi bi-award"></i></div><div class="rank">Juara 3</div><div class="name">-</div><div class="score">0</div></div>
                            </div>
                            <div class="hl-list" id="rankingList"></div>
                            <div class="hl-actions">
                                <button type="button" class="lw-btn lw-btn--ghost" id="backTo2"><i class="bi bi-arrow-left"></i> Kembali</button>
                                <button type="button" class="lw-btn lw-btn--solid" id="toPane4" disabled><i class="bi bi-arrow-right"></i> Tetapkan Hasil</button>
                            </div>
                        </div>

                        <div class="lw-wizard-pane" data-pane="4">
                            <div class="hl-stepper-note">Pilih peserta/kelompok yang akan dijadikan hasil resmi. Total, peringkat, dan juara tetap mengikuti backend.</div>
                            <div class="hl-list" id="selectList"></div>
                            <div class="hl-selected" id="selectedInfo"><i class="bi bi-check-circle-fill me-1"></i> <span id="selectedCount">0</span> hasil siap disimpan.</div>
                            <div class="hl-actions">
                                <button type="button" class="lw-btn lw-btn--ghost" id="backTo3"><i class="bi bi-arrow-left"></i> Kembali</button>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('hasil-lomba.index') }}" class="lw-btn lw-btn--ghost"><i class="bi bi-x-circle"></i> Batal</a>
                                    <button type="submit" class="lw-btn lw-btn--success" id="btnSimpan" disabled><i class="bi bi-check2-circle"></i> Simpan Hasil</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="hl-summary-card">
                <div class="hl-summary-title"><i class="bi bi-activity"></i> Live Summary</div>
                <div class="hl-summary-grid">
                    <div class="hl-summary-item"><div class="k">Jumlah Peserta</div><div class="v" id="sumPeserta">0</div></div>
                    <div class="hl-summary-item"><div class="k">Jumlah Juara</div><div class="v" id="sumJuara">0</div></div>
                    <div class="hl-summary-item"><div class="k">Nilai Tertinggi</div><div class="v" id="sumHigh">0</div></div>
                    <div class="hl-summary-item"><div class="k">Nilai Terendah</div><div class="v" id="sumLow">0</div></div>
                    <div class="hl-summary-item"><div class="k">Rata-rata</div><div class="v" id="sumAvg">0</div></div>
                    <div class="hl-summary-item"><div class="k">Lomba Aktif</div><div class="v" id="sumLomba">-</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('hasilForm');
    if (!form) return;

    var lombaSelect = document.getElementById('lomba_id');
    var allPeserta = [];
    var currentPane = 1;
    var selectedIds = new Set();

    function goStep(n) {
        currentPane = n;
        document.querySelectorAll('#hlStepper .lw-step').forEach(function (s) {
            var step = parseInt(s.dataset.step, 10);
            s.classList.toggle('active', step === n);
            s.classList.toggle('done', step < n);
        });
        document.querySelectorAll('#hlStepper .lw-step-line').forEach(function (l, i) {
            l.classList.toggle('done', i < n - 1);
        });
        document.querySelectorAll('[data-pane]').forEach(function (p) {
            p.classList.toggle('is-show', p.dataset.pane === String(n));
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function winnerBadge(rank) {
        if (rank === 1) return '<span class="hl-badge gold"><i class="bi bi-trophy-fill"></i> Gold</span>';
        if (rank === 2) return '<span class="hl-badge silver"><i class="bi bi-award-fill"></i> Silver</span>';
        if (rank === 3) return '<span class="hl-badge bronze"><i class="bi bi-award"></i> Bronze</span>';
        return '<span class="hl-badge neutral"><i class="bi bi-flag"></i> Finalis</span>';
    }

    function syncHiddenInputs() {
        form.querySelectorAll('input[name="peserta_lomba_id[]"]').forEach(function (el) { el.remove(); });
        selectedIds.forEach(function (id) {
            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'peserta_lomba_id[]';
            hidden.value = id;
            form.appendChild(hidden);
        });
    }

    function updateSummary() {
        var scores = allPeserta.map(function (item) { return Number(item.total_nilai || 0); });
        var total = scores.length;
        var max = total ? Math.max.apply(null, scores) : 0;
        var min = total ? Math.min.apply(null, scores) : 0;
        var avg = total ? (scores.reduce(function (acc, n) { return acc + n; }, 0) / total) : 0;
        document.getElementById('sumPeserta').textContent = total.toLocaleString('id-ID');
        document.getElementById('sumJuara').textContent = Math.min(3, total).toLocaleString('id-ID');
        document.getElementById('sumHigh').textContent = max.toLocaleString('id-ID');
        document.getElementById('sumLow').textContent = min.toLocaleString('id-ID');
        document.getElementById('sumAvg').textContent = avg.toFixed(1).replace('.', ',');
        document.getElementById('sumLomba').textContent = lombaSelect.options[lombaSelect.selectedIndex] ? lombaSelect.options[lombaSelect.selectedIndex].textContent : '-';

        document.getElementById('previewJumlahPeserta').textContent = total.toLocaleString('id-ID');
        document.getElementById('previewJumlahJuri').textContent = 'Auto';
        document.getElementById('previewTertinggi').textContent = max.toLocaleString('id-ID');
        document.getElementById('previewRata').textContent = avg.toFixed(1).replace('.', ',');
    }

    function renderPreview() {
        var selectedOption = lombaSelect.options[lombaSelect.selectedIndex];
        var preview = document.getElementById('lombaPreview');
        if (!lombaSelect.value || !selectedOption) {
            preview.style.display = 'none';
            return;
        }
        preview.style.display = 'block';
        document.getElementById('previewNamaLomba').textContent = selectedOption.textContent;
        var isTeam = /kelompok|tim/i.test(selectedOption.textContent);
        document.getElementById('previewJenis').innerHTML = '<i class="bi ' + (isTeam ? 'bi-people' : 'bi-person') + '"></i> ' + (isTeam ? 'Kelompok' : 'Individu');
        document.getElementById('previewSubtitle').textContent = 'Peserta akan diurutkan otomatis dari total nilai tertinggi.';
    }

    function renderList(targetId, withCheck) {
        var target = document.getElementById(targetId);
        if (!target) return;
        if (!allPeserta.length) {
            target.innerHTML = '<div class="hl-empty">Belum ada peserta dengan nilai.</div>';
            return;
        }
        var html = '';
        allPeserta.forEach(function (item, idx) {
            var rank = idx + 1;
            var checked = selectedIds.has(String(item.id)) ? 'checked' : '';
            html += '<div class="hl-list-item">' +
                '<span class="hl-rank">#' + rank + '</span>' +
                '<div><div class="hl-name">' + item.text + '</div><div class="hl-sub">Peringkat otomatis dari total nilai</div></div>' +
                '<div class="hl-score">' + Number(item.total_nilai || 0).toLocaleString('id-ID') + '</div>' +
                winnerBadge(rank) +
                (withCheck ? '<div class="ck-wrap text-end"><input type="checkbox" class="hl-check result-check" data-id="' + item.id + '" ' + checked + '></div>' : '<div></div>') +
            '</div>';
        });
        target.innerHTML = html;
        if (withCheck) {
            target.querySelectorAll('.result-check').forEach(function (ck) {
                ck.addEventListener('change', function () {
                    if (this.checked) selectedIds.add(String(this.dataset.id));
                    else selectedIds.delete(String(this.dataset.id));
                    updateSelection();
                });
            });
        }
    }

    function renderPodium() {
        var wrap = document.getElementById('podiumWrap');
        var top = [allPeserta[1], allPeserta[0], allPeserta[2]];
        var classes = ['silver', 'gold', 'bronze'];
        var labels = ['Juara 2', 'Juara 1', 'Juara 3'];
        var icons = ['bi-award-fill', 'bi-trophy-fill', 'bi-award'];
        wrap.innerHTML = top.map(function (item, index) {
            return '<div class="hl-podium-card ' + classes[index] + '">' +
                '<div class="icon"><i class="bi ' + icons[index] + '"></i></div>' +
                '<div class="rank">' + labels[index] + '</div>' +
                '<div class="name">' + (item ? item.text : '-') + '</div>' +
                '<div class="score">' + (item ? Number(item.total_nilai || 0).toLocaleString('id-ID') : '0') + '</div>' +
            '</div>';
        }).join('');
    }

    function updateSelection() {
        var count = selectedIds.size;
        document.getElementById('selectedCount').textContent = count.toLocaleString('id-ID');
        document.getElementById('selectedInfo').style.display = count ? 'block' : 'none';
        document.getElementById('btnSimpan').disabled = count === 0;
        syncHiddenInputs();
    }

    function fetchPeserta() {
        var lombaId = lombaSelect.value;
        if (!lombaId) return;
        document.getElementById('peserta-body').innerHTML = '<div class="hl-empty">Memuat peserta...</div>';
        fetch('{{ route("hasil-lomba.get-peserta", "") }}/' + lombaId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                allPeserta = (data || []).sort(function (a, b) { return Number(b.total_nilai || 0) - Number(a.total_nilai || 0); });
                selectedIds = new Set(allPeserta.map(function (item) { return String(item.id); }));
                renderList('peserta-body', false);
                renderPodium();
                renderList('rankingList', false);
                renderList('selectList', true);
                updateSummary();
                updateSelection();
                document.getElementById('toPane3').disabled = !allPeserta.length;
                document.getElementById('toPane4').disabled = !allPeserta.length;
            })
            .catch(function () {
                allPeserta = [];
                document.getElementById('peserta-body').innerHTML = '<div class="hl-empty">Gagal memuat data peserta.</div>';
            });
    }

    lombaSelect.addEventListener('change', function () {
        renderPreview();
        document.getElementById('toPane2').disabled = !this.value;
        allPeserta = [];
        selectedIds = new Set();
        updateSummary();
    });

    document.getElementById('toPane2').addEventListener('click', function () {
        if (!lombaSelect.value) return;
        goStep(2);
        fetchPeserta();
    });
    document.getElementById('backTo1').addEventListener('click', function () { goStep(1); });
    document.getElementById('toPane3').addEventListener('click', function () { goStep(3); });
    document.getElementById('backTo2').addEventListener('click', function () { goStep(2); });
    document.getElementById('toPane4').addEventListener('click', function () { goStep(4); renderList('selectList', true); });
    document.getElementById('backTo3').addEventListener('click', function () { goStep(3); });

    var submitting = false;
    form.addEventListener('submit', function (e) {
        if (submitting) { e.preventDefault(); return; }
        if (!selectedIds.size) { e.preventDefault(); return; }
        submitting = true;
        var btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<span class="hl-loading"><span class="hl-spin"></span> Menyimpan...</span>';
    });

    document.querySelectorAll('.lw-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) { if (window.LW) LW.ripple(e); });
    });

    renderPreview();
    updateSummary();
    goStep(1);
})();
</script>
@endpush
