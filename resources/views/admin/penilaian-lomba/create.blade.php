@extends('layouts.main')
@section('title', 'Tambah Penilaian Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }
    .pl-mod { --pl-radius: 16px; }

    .pl-builder { max-width: 1000px; margin: 0 auto; }

    .pl-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--lw-text-3); margin-bottom: 16px; flex-wrap: wrap; }
    .pl-breadcrumb a { color: var(--lw-text-2); text-decoration: none; transition: color .2s; }
    .pl-breadcrumb a:hover { color: var(--lw-primary); }
    .pl-breadcrumb i { font-size: 11px; }

    .pl-builder-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 20px; }
    .pl-builder-head-left { display: flex; align-items: center; gap: 14px; }
    .pl-builder-icon { width: 50px; height: 50px; border-radius: 15px; background: var(--lw-navy-soft); color: var(--lw-primary); border: 1px solid var(--lw-navy-border); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; }
    .pl-builder-title { font-size: 19px; font-weight: 800; color: var(--lw-text); margin: 0; letter-spacing: -.3px; }
    .pl-builder-sub { font-size: 12.5px; color: var(--lw-text-3); margin-top: 2px; }

    /* ---------- Mode picker ---------- */
    .pl-pick-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .pl-pick-card { position: relative; display: block; overflow: hidden; border: 1.5px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); padding: 24px 22px; text-decoration: none !important; transition: all .22s ease; cursor: pointer; }
    .pl-pick-card:hover { border-color: var(--lw-primary); box-shadow: var(--lw-shadow-lg); transform: translateY(-3px); }
    .pl-pick-card::after { content: "\F659"; font-family: "bootstrap-icons"; position: absolute; top: 20px; right: 20px; font-size: 17px; color: var(--lw-text-3); opacity: .5; transition: all .2s; }
    .pl-pick-card:hover::after { color: var(--lw-primary); opacity: 1; transform: translateX(3px); }
    .pl-pick-icon { width: 56px; height: 56px; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 25px; margin-bottom: 15px; }
    .pl-pick-icon.p1 { background: var(--lw-navy-soft); color: var(--lw-primary); border: 1px solid var(--lw-navy-border); }
    .pl-pick-icon.p2 { background: var(--lw-amber-soft); color: var(--lw-amber); border: 1px solid var(--lw-amber-border); }
    .pl-pick-title { font-size: 16.5px; font-weight: 800; color: var(--lw-text); margin: 0 0 4px; }
    .pl-pick-sub { font-size: 12.5px; color: var(--lw-text-3); line-height: 1.55; margin: 0; }
    .pl-pick-note { display: inline-flex; align-items: center; gap: 6px; margin-top: 15px; font-size: 11.5px; font-weight: 600; color: var(--lw-text-2); background: var(--lw-bg); border: 1px solid var(--lw-border); padding: 6px 12px; border-radius: 999px; }
    .pl-pick-note i { font-size: 12px; }

    /* ---------- Fields ---------- */
    .pl-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .pl-field { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .pl-field > label { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 700; color: var(--lw-text); margin: 0; }
    .pl-field > label i { color: var(--lw-primary); font-size: 13px; }
    .pl-field > label .pl-req { color: var(--lw-red); }
    .pl-field .lw-select, .pl-field .lw-control { height: 44px; }
    .pl-hint { font-size: 11.5px; color: var(--lw-text-3); display: flex; align-items: center; gap: 6px; }
    .pl-hint i { color: var(--lw-primary); font-size: 12px; }
    .pl-err { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--lw-red); font-weight: 600; }
    .pl-err i { font-size: 12px; }
    .pl-note { display: flex; align-items: center; gap: 8px; border-radius: 11px; padding: 11px 13px; font-size: 12px; font-weight: 600; border: 1px solid var(--lw-amber-border); background: var(--lw-amber-soft); color: var(--lw-amber); }
    .pl-note i { font-size: 13px; }
    .pl-jenis { display: none; margin-top: 2px; }
    .pl-jenis.show { display: block; }

    /* ---------- Score Card Grid ---------- */
    .pl-score-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-top: 18px; }
    .pl-score-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; padding: 18px; box-shadow: var(--lw-shadow); transition: all .2s ease; position: relative; }
    .pl-score-card:hover { border-color: var(--lw-primary-border); transform: translateY(-2px); box-shadow: var(--lw-shadow-lg); }
    .pl-score-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 15px; }
    .pl-score-title { font-size: 14px; font-weight: 800; color: var(--lw-text); margin: 0; line-height: 1.4; }
    .pl-score-num { width: 28px; height: 28px; border-radius: 8px; background: var(--lw-navy-soft); color: var(--lw-primary); font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .pl-input-group { display: flex; align-items: center; gap: 12px; margin-top: 10px; }
    .pl-score-input { width: 70px; height: 44px; border: 1.5px solid var(--lw-border); border-radius: 12px; background: var(--lw-bg); text-align: center; font-size: 16px; font-weight: 800; color: var(--lw-primary); transition: all .2s; font-family: inherit; }
    .pl-score-input:focus { border-color: var(--lw-primary); outline: none; box-shadow: 0 0 0 3px var(--lw-primary-soft); background: var(--lw-card); }

    .pl-slider-wrap { flex: 1; display: flex; flex-direction: column; gap: 6px; }
    .pl-slider { -webkit-appearance: none; width: 100%; height: 6px; border-radius: 5px; background: var(--lw-border); outline: none; cursor: pointer; }
    .pl-slider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 18px; height: 18px; border-radius: 50%; background: var(--lw-primary); cursor: pointer; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: transform .1s; }
    .pl-slider::-webkit-slider-thumb:hover { transform: scale(1.1); }

    .pl-range-label { display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; }

    #aspek-table-wrapper { border: none !important; background: none !important; box-shadow: none !important; }

    /* ---------- Summary strip ---------- */
    .pl-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 18px; }
    .pl-sum-item { border: 1px solid var(--lw-border); border-radius: 13px; background: var(--lw-card); padding: 12px 15px; min-width: 0; }
    .pl-sum-item .l { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .pl-sum-item .v { font-size: 13px; font-weight: 700; color: var(--lw-text); margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pl-sum-item.hl { border-color: var(--lw-primary-border); background: var(--lw-primary-soft); }
    .pl-sum-item.hl .v { color: var(--lw-primary); font-size: 16px; }

    /* ---------- Footer bar ---------- */
    .pl-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--lw-border); flex-wrap: wrap; }
    .pl-foot-info { font-size: 12px; color: var(--lw-text-3); display: flex; align-items: center; gap: 7px; }
    .pl-foot-info i { color: var(--lw-green); }
    .pl-foot .btns { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .pl-loading { display: inline-flex; align-items: center; gap: 8px; }
    .pl-spin { width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: plSpin .7s linear infinite; }
    @keyframes plSpin { to { transform: rotate(360deg); } }

    @media (max-width: 991.98px) { .pl-summary { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767.98px) {
        .pl-pick-grid { grid-template-columns: 1fr; }
        .pl-grid2 { grid-template-columns: 1fr; gap: 14px; }
        .pl-foot { flex-direction: column; align-items: stretch; }
        .pl-foot .btns { justify-content: flex-end; }
    }
</style>

@php
    $modeTitle = $mode === 'tim' ? 'Kelompok' : 'Peserta';
@endphp

<div class="lw-mod pl-mod">
    <div class="pl-builder">

        <nav class="pl-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('penilaian-lomba.index') }}"><i class="bi bi-star"></i> Penilaian Lomba</a>
            <i class="bi bi-chevron-right"></i>
            <span>Tambah Penilaian</span>
        </nav>

        <div class="pl-builder-head">
            <div class="pl-builder-head-left">
                <span class="pl-builder-icon"><i class="bi bi-clipboard-plus"></i></span>
                <div>
                    <h2 class="pl-builder-title">Tambah Penilaian Lomba</h2>
                    <p class="pl-builder-sub">Catat nilai dari setiap juri untuk {{ $mode === 'tim' ? 'kelompok peserta' : 'peserta' }} — isi dalam 3 langkah cepat.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('toast_error'))
            <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('toast_error') }}</div>
        @endif
        @if ($errors->any())
            <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    Terdapat kesalahan:
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(empty($mode))

        {{-- ============ MODE PICKER ============ --}}
        <div class="pl-pick-grid">
            <a href="{{ route('penilaian-lomba.create', ['mode' => 'individu']) }}" class="pl-pick-card">
                <span class="pl-pick-icon p1"><i class="bi bi-person-badge"></i></span>
                <h3 class="pl-pick-title">Penilaian Individu</h3>
                <p class="pl-pick-sub">Untuk lomba per peserta — pilih juri, pilih peserta, lalu isi nilai setiap aspek.</p>
                <span class="pl-pick-note"><i class="bi bi-person"></i> Satu peserta dinilai</span>
            </a>
            <a href="{{ route('penilaian-lomba.create', ['mode' => 'tim']) }}" class="pl-pick-card">
                <span class="pl-pick-icon p2"><i class="bi bi-people"></i></span>
                <h3 class="pl-pick-title">Penilaian Tim</h3>
                <p class="pl-pick-sub">Untuk lomba kelompok — pilih juri, pilih kelompok, lalu isi nilai setiap aspek.</p>
                <span class="pl-pick-note"><i class="bi bi-people"></i> Satu kelompok dinilai</span>
            </a>
        </div>

        @else

        {{-- ============ SCORING WIZARD ============ --}}
        <form action="{{ route('penilaian-lomba.store') }}" method="POST" id="penilaianForm">
            @csrf

            {{-- STEPPER --}}
            <div class="lw-stepper" id="plStepper">
                <div class="lw-step active" data-step="1">
                    <span class="lw-step-dot">1</span>
                    <div class="lw-step-txt"><b>Sesi &amp; Lomba</b><span>Tentukan lomba</span></div>
                </div>
                <div class="lw-step-line"></div>
                <div class="lw-step" data-step="2">
                    <span class="lw-step-dot">2</span>
                    <div class="lw-step-txt"><b>Juri &amp; {{ $modeTitle }}</b><span>Pilih penilai</span></div>
                </div>
                <div class="lw-step-line"></div>
                <div class="lw-step" data-step="3">
                    <span class="lw-step-dot">3</span>
                    <div class="lw-step-txt"><b>Isi Nilai</b><span>Input skor aspek</span></div>
                </div>
            </div>

            {{-- STEP 1 : SESI & LOMBA --}}
            <div class="lw-wizard-pane is-show" data-pane="1">
                <div class="lw-card lw-card-pad">
                    <div class="lw-section-title" style="margin-bottom:4px;"><i class="bi bi-calendar3"></i> Pilih Sesi &amp; Lomba</div>
                    <div class="lw-section-sub">Tentukan sesi lalu pilih lomba {{ $mode === 'tim' ? 'tim' : 'individu' }} yang akan dinilai.</div>

                    @if($sesiLombas->isEmpty())
                        <div class="lw-empty">
                            <div class="lw-empty-illus"><div class="ring"></div><div class="core"><i class="bi bi-calendar-event"></i></div></div>
                            <div class="lw-empty-title">Belum ada sesi lomba</div>
                            <div class="lw-empty-sub">Tambahkan sesi lomba terlebih dahulu agar bisa menilai peserta.</div>
                            <a href="{{ route('sesi-lomba.index') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Kelola Sesi</a>
                        </div>
                    @else
                    <div class="pl-grid2">
                        <div class="pl-field">
                            <label for="sesi_lomba_id"><i class="bi bi-calendar-event"></i> Sesi Lomba <span class="pl-req">*</span></label>
                            <select id="sesi_lomba_id" class="lw-select" required aria-required="true">
                                <option value="">-- Pilih Sesi --</option>
                                @foreach($sesiLombas as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                            <span class="pl-hint"><i class="bi bi-info-circle"></i> Sesi = jadwal acara lomba.</span>
                        </div>
                        <div class="pl-field">
                            <label for="lomba_id"><i class="bi bi-trophy"></i> Lomba <span class="pl-req">*</span></label>
                            <select id="lomba_id" class="lw-select" disabled>
                                <option value="">-- Pilih Sesi Dulu --</option>
                            </select>
                            <input type="hidden" name="lomba_id" id="lomba_id_hidden" value="">
                            <input type="hidden" id="juri_lomba_id_tim_hidden" value="">
                            <div class="pl-jenis" id="jenis-info">
                                <span class="lw-chip lw-chip--navy" id="jenis-badge"><i class="bi bi-tag"></i> --</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="pl-foot">
                        <div class="pl-foot-info"><i class="bi bi-info-circle"></i> <span id="pane1Info">Pilih sesi untuk memuat daftar lomba.</span></div>
                        <div class="btns">
                            <a href="{{ route('penilaian-lomba.index') }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-left"></i> Batal</a>
                            <button type="button" id="plToPane2" class="lw-btn lw-btn--solid" disabled><i class="bi bi-arrow-right"></i> Lanjut: Juri &amp; {{ $modeTitle }}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2 : JURI & PESERTA / KELOMPOK --}}
            <div class="lw-wizard-pane" data-pane="2">
                <div class="lw-card lw-card-pad">
                    <div class="lw-section-title" style="margin-bottom:4px;"><i class="bi bi-people"></i> Pilih Juri &amp; {{ $modeTitle }}</div>
                    <div class="lw-section-sub">Pilih juri yang menilai, lalu pilih {{ $mode === 'tim' ? 'kelompok yang akan dinilai' : 'peserta yang belum dinilai juri tersebut' }}.</div>

                    @if($mode === 'individu')
                    <div class="pl-grid2">
                        <div class="pl-field">
                            <label for="juri_lomba_id"><i class="bi bi-gavel"></i> Juri <span class="pl-req">*</span></label>
                            <select name="juri_lomba_id" id="juri_lomba_id" class="lw-select @error('juri_lomba_id') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Lomba Dulu --</option>
                            </select>
                            @error('juri_lomba_id')<div class="pl-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="pl-field" id="section-individu" style="display:none;">
                            <label for="peserta_lomba_id"><i class="bi bi-person-badge"></i> Peserta <span class="pl-req">*</span></label>
                            <select name="peserta_lomba_id" id="peserta_lomba_id" class="lw-select @error('peserta_lomba_id') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Juri Dulu --</option>
                            </select>
                            <div id="peserta-empty-alert" style="display:none;"><div class="pl-note"><i class="bi bi-info-circle"></i> Semua peserta pada lomba ini sudah dinilai oleh juri terpilih.</div></div>
                            @error('peserta_lomba_id')<div class="pl-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    @else
                    <div class="pl-grid2">
                        <div class="pl-field">
                            <label for="juri_lomba_id_tim_display"><i class="bi bi-gavel"></i> Juri <span class="pl-req">*</span></label>
                            <select name="juri_lomba_id" id="juri_lomba_id_tim_display" class="lw-select @error('juri_lomba_id') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Lomba Dulu --</option>
                            </select>
                            @error('juri_lomba_id')<div class="pl-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="pl-field" id="section-tim" style="display:none;">
                            <label for="kelompok_lomba_id"><i class="bi bi-people"></i> Nama Kelompok <span class="pl-req">*</span></label>
                            <select name="kelompok_lomba_id" id="kelompok_lomba_id" class="lw-select @error('kelompok_lomba_id') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Juri Dulu --</option>
                            </select>
                            <div id="kelompok-empty-alert" style="display:none;"><div class="pl-note"><i class="bi bi-info-circle"></i> Semua kelompok pada lomba ini sudah dinilai oleh juri terpilih.</div></div>
                            @error('kelompok_lomba_id')<div class="pl-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                    @endif

                    <div class="pl-foot">
                        <div class="pl-foot-info"><i class="bi bi-info-circle"></i> <span id="pane2Info">Pilih juri untuk memuat daftar {{ $mode === 'tim' ? 'kelompok' : 'peserta' }}.</span></div>
                        <div class="btns">
                            <button type="button" id="plToPane1" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-left"></i> Kembali</button>
                            <button type="button" id="plToPane3" class="lw-btn lw-btn--solid" disabled><i class="bi bi-arrow-right"></i> Lanjut: Isi Nilai</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3 : ISI NILAI --}}
            <div class="lw-wizard-pane" data-pane="3">
                <div class="lw-card lw-card-pad">
                    <div class="lw-section-title" style="margin-bottom:4px;"><i class="bi bi-pencil-square"></i> Isi Nilai Aspek</div>
                    <div class="lw-section-sub">Masukkan nilai 0 – 100 untuk setiap aspek penilaian.</div>

                    <div class="pl-summary">
                        <div class="pl-sum-item"><div class="l">Lomba</div><div class="v" id="sumLomba">-</div></div>
                        <div class="pl-sum-item"><div class="l">Juri</div><div class="v" id="sumJuri">-</div></div>
                        <div class="pl-sum-item"><div class="l">{{ $modeTitle }}</div><div class="v" id="sumPeserta">-</div></div>
                        <div class="pl-sum-item hl"><div class="l">Total Nilai</div><div class="v" id="sumTotal">0</div></div>
                    </div>

                    <div id="aspek-table-wrapper" style="display:none;margin-top:18px;">
                        <div id="aspek-table-body">
                            <!-- Grid of Score Cards rendered via JS -->
                        </div>
                        <div id="aspek-error" class="pl-err" style="display:none;margin-top:10px;"><i class="bi bi-exclamation-circle"></i> <span></span></div>
                    </div>

                    <div class="pl-foot">
                        <div class="pl-foot-info"><i class="bi bi-check2-circle"></i> <span id="pane3Info">Pastikan data benar sebelum menyimpan.</span></div>
                        <div class="btns">
                            <button type="button" id="plToPane2b" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-left"></i> Kembali</button>
                            <button type="submit" id="submitBtn" class="lw-btn lw-btn--success" disabled><i class="bi bi-check2"></i> Simpan Penilaian</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('penilaianForm');
    if (!form) return;

    function stubNode() {
        return {
            style: { display: 'none' },
            disabled: true,
            value: '',
            innerHTML: '',
            addEventListener: function () {},
            dispatchEvent: function () {}
        };
    }

    var IS_TIM = !!document.getElementById('kelompok_lomba_id');

    var sesiSelect      = document.getElementById('sesi_lomba_id') || stubNode();
    var lombaSelect     = document.getElementById('lomba_id') || stubNode();
    var lombaHidden     = document.getElementById('lomba_id_hidden') || stubNode();
    var juriSelect      = document.getElementById('juri_lomba_id') || stubNode();
    var teamJuriSelect  = document.getElementById('juri_lomba_id_tim_display') || stubNode();
    var teamJuriHidden  = document.getElementById('juri_lomba_id_tim_hidden') || stubNode();
    var pesertaSelect   = document.getElementById('peserta_lomba_id') || stubNode();
    var kelompokSelect  = document.getElementById('kelompok_lomba_id') || stubNode();
    var aspekWrapper    = document.getElementById('aspek-table-wrapper') || stubNode();
    var aspekBody       = document.getElementById('aspek-table-body') || stubNode();
    var submitBtn       = document.getElementById('submitBtn') || stubNode();
    var emptyAlert      = document.getElementById('peserta-empty-alert') || stubNode();
    var kelompokAlert   = document.getElementById('kelompok-empty-alert') || stubNode();
    var jenisInfo       = document.getElementById('jenis-info') || stubNode();
    var jenisBadge      = document.getElementById('jenis-badge') || stubNode();
    var sectionIndividu = document.getElementById('section-individu') || stubNode();
    var sectionTim      = document.getElementById('section-tim') || stubNode();
    var aspekErrEl      = document.getElementById('aspek-error') || stubNode();
    var pane1Info       = document.getElementById('pane1Info') || stubNode();
    var pane2Info       = document.getElementById('pane2Info') || stubNode();
    var btnToPane2      = document.getElementById('plToPane2') || stubNode();
    var btnToPane3      = document.getElementById('plToPane3') || stubNode();
    var juriTarget      = IS_TIM ? teamJuriSelect : juriSelect;
    var objSelect       = IS_TIM ? kelompokSelect : pesertaSelect;
    var objEmptyAlert   = IS_TIM ? kelompokAlert : emptyAlert;

    var currentPane = 1;

    // ---------- Stepper / pane navigation ----------
    function goStep(n) {
        currentPane = n;
        document.querySelectorAll('#plStepper .lw-step').forEach(function (s) {
            s.classList.toggle('active', parseInt(s.dataset.step, 10) === n);
            s.classList.toggle('done', parseInt(s.dataset.step, 10) < n);
        });
        document.querySelectorAll('#plStepper .lw-step-line').forEach(function (l, i) {
            l.classList.toggle('done', i < n - 1);
        });
        document.querySelectorAll('[data-pane]').forEach(function (p) {
            p.classList.toggle('is-show', p.dataset.pane === String(n));
        });
        if (n === 3) {
            submitBtn.disabled = false;
            fillSummary();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ---------- Generic select loader ----------
    function resetSelect(el, placeholder) {
        el.innerHTML = '<option value="">' + placeholder + '</option>';
        el.disabled = true;
    }
    function loadSelect(url, selectEl, placeholder, onLoaded) {
        selectEl.innerHTML = '<option value="">Memuat...</option>';
        selectEl.disabled = true;
        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                selectEl.innerHTML = '<option value="">' + placeholder + '</option>';
                data.forEach(function (item) {
                    var opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.text;
                    selectEl.appendChild(opt);
                });
                selectEl.disabled = false;
                if (typeof onLoaded === 'function') onLoaded(data);
            })
            .catch(function () {
                selectEl.innerHTML = '<option value="">Gagal memuat data</option>';
                selectEl.disabled = false;
                if (typeof onLoaded === 'function') onLoaded([]);
            });
    }

    // ---------- Aspek Score Cards ----------
    function renderAspekTable(data) {
        if (!data || data.length === 0) {
            aspekBody.innerHTML = '<div style="grid-column: 1/-1; text-align:center; padding:40px; color:var(--lw-text-3);"><i class="bi bi-info-circle mb-2" style="font-size:24px; display:block;"></i> Belum ada aspek penilaian untuk lomba ini</div>';
            aspekWrapper.style.display = 'block';
            return;
        }
        var html = '<div class="pl-score-grid">';
        data.forEach(function (item, idx) {
            html += '<div class="pl-score-card">' +
                '<div class="pl-score-head">' +
                    '<h4 class="pl-score-title">' + item.nama_aspek + '</h4>' +
                    '<span class="pl-score-num">' + (idx + 1) + '</span>' +
                '</div>' +
                '<div class="pl-input-group">' +
                    '<input type="number" step="0.01" name="nilai[]" class="pl-score-input aspek-nilai" placeholder="0" min="0" max="100" data-idx="' + idx + '" inputmode="decimal" aria-label="Nilai aspek ' + (idx + 1) + '">' +
                    '<div class="pl-slider-wrap">' +
                        '<input type="range" class="pl-slider aspek-slider" min="0" max="100" step="1" value="0" data-idx="' + idx + '">' +
                        '<div class="pl-range-label"><span>0</span><span>100</span></div>' +
                    '</div>' +
                    '<input type="hidden" name="aspek_penilaian_id[]" value="' + item.id + '">' +
                '</div>' +
            '</div>';
        });
        html += '</div>';
        aspekBody.innerHTML = html;
        aspekWrapper.style.display = 'block';
        bindNilaiInputs();
    }
    function bindNilaiInputs() {
        document.querySelectorAll('.aspek-nilai').forEach(function (inp) {
            inp.addEventListener('input', function () {
                var idx = this.dataset.idx;
                var slider = document.querySelector('.aspek-slider[data-idx="' + idx + '"]');
                if (slider) slider.value = this.value || 0;
                aspekErrEl.style.display = 'none';
                updateTotal();
            });
        });
        document.querySelectorAll('.aspek-slider').forEach(function (sld) {
            sld.addEventListener('input', function () {
                var idx = this.dataset.idx;
                var input = document.querySelector('.aspek-nilai[data-idx="' + idx + '"]');
                if (input) input.value = this.value;
                aspekErrEl.style.display = 'none';
                updateTotal();
            });
        });
    }
    function loadAspek(lombaId) {
        aspekWrapper.style.display = 'none';
        aspekBody.innerHTML = '<div style="padding:40px; text-align:center;">Memuat aspek...</div>';
        fetch('{{ url("/penilaian-lomba/get-aspek") }}/' + lombaId)
            .then(function (r) { return r.json(); })
            .then(function (data) { renderAspekTable(data); })
            .catch(function () {
                aspekBody.innerHTML = '<div style="padding:40px; text-align:center; color:var(--lw-red);">Gagal memuat aspek penilaian</div>';
                aspekWrapper.style.display = 'block';
            });
    }
    function updateTotal() {
        var sum = 0;
        document.querySelectorAll('.aspek-nilai').forEach(function (inp) {
            var v = parseFloat(inp.value);
            if (!isNaN(v) && v > 0) sum += v;
        });
        var el = document.getElementById('sumTotal');
        if (el) el.textContent = sum.toLocaleString('id-ID');
    }
    function fillSummary() {
        var lo = lombaSelect.options[lombaSelect.selectedIndex];
        document.getElementById('sumLomba').textContent = (lo && lo.value) ? lo.textContent : '-';
        var jo = juriTarget.options[juriTarget.selectedIndex];
        document.getElementById('sumJuri').textContent = (jo && jo.value) ? jo.textContent : '-';
        var po = objSelect.options[objSelect.selectedIndex];
        document.getElementById('sumPeserta').textContent = (po && po.value) ? po.textContent : '-';
        updateTotal();
    }

    // ---------- STEP 1 : sesi → lomba ----------
    sesiSelect.addEventListener('change', function () {
        var sesiId = this.value;
        resetSelect(lombaSelect, '-- Pilih Sesi Dulu --');
        resetSelect(juriTarget, '-- Pilih Lomba Dulu --');
        resetSelect(objSelect, '-- Pilih Juri Dulu --');
        aspekWrapper.style.display = 'none';
        objEmptyAlert.style.display = 'none';
        jenisInfo.classList.remove('show');
        sectionIndividu.style.display = 'none';
        sectionTim.style.display = 'none';
        teamJuriHidden.value = '';
        teamJuriHidden.disabled = true;
        teamJuriSelect.innerHTML = '<option value="">-- Pilih Lomba Dulu --</option>';
        teamJuriSelect.disabled = true;
        juriSelect.disabled = true;
        submitBtn.disabled = true;
        btnToPane2.disabled = true;
        lombaHidden.value = '';
        pane1Info.textContent = 'Pilih sesi untuk memuat daftar lomba.';

        if (!sesiId) return;
        loadSelect('{{ url("/penilaian-lomba/get-lomba") }}/' + sesiId + '{{ "?jenis=" . ucfirst($mode) }}', lombaSelect, '-- Pilih Lomba --', function () {
            pane1Info.textContent = 'Pilih lomba dari daftar yang tersedia.';
        });
    });

    lombaSelect.addEventListener('change', function () {
        var lombaId = this.value;
        resetSelect(juriTarget, '-- Pilih Lomba Dulu --');
        resetSelect(objSelect, '-- Pilih Juri Dulu --');
        aspekWrapper.style.display = 'none';
        objEmptyAlert.style.display = 'none';
        sectionIndividu.style.display = 'none';
        sectionTim.style.display = 'none';
        teamJuriHidden.value = '';
        teamJuriHidden.disabled = true;
        teamJuriSelect.innerHTML = '<option value="">-- Pilih Lomba Dulu --</option>';
        teamJuriSelect.disabled = true;
        juriSelect.disabled = true;
        submitBtn.disabled = true;
        btnToPane2.disabled = true;
        btnToPane3.disabled = true;

        if (!lombaId) { jenisInfo.classList.remove('show'); lombaHidden.value = ''; return; }

        lombaHidden.value = lombaId;
        jenisBadge.innerHTML = '<i class="bi bi-tag"></i> ' + (IS_TIM ? 'Kelompok (Tim)' : 'Individu');
        jenisInfo.classList.add('show');
        pane1Info.innerHTML = '<i class="bi bi-check2"></i> Lomba terpilih — lanjut pilih juri.';

        if (IS_TIM) {
            loadSelect('{{ url("/penilaian-lomba/get-juri") }}/' + lombaId, teamJuriSelect, '-- Pilih Juri --', function () {
                teamJuriHidden.value = teamJuriSelect.value || '';
            });
        } else {
            loadSelect('{{ url("/penilaian-lomba/get-juri") }}/' + lombaId, juriSelect, '-- Pilih Juri --');
        }
        loadAspek(lombaId);
        btnToPane2.disabled = false;
    });

    // ---------- STEP 2 : juri → peserta/kelompok ----------
    juriTarget.addEventListener('change', function () {
        var juriId = this.value;
        resetSelect(objSelect, '-- Pilih Juri Dulu --');
        objEmptyAlert.style.display = 'none';
        btnToPane3.disabled = true;
        if (IS_TIM) teamJuriHidden.value = juriId || '';

        var lombaId = lombaSelect.value;
        if (!juriId || !lombaId) return;

        if (IS_TIM) {
            sectionTim.style.display = 'block';
            loadSelect('{{ url("/penilaian-lomba/get-kelompok") }}/' + lombaId + '?juri_lomba_id=' + juriId, kelompokSelect, '-- Pilih Kelompok --', function (data) {
                if (data.length === 0) {
                    kelompokAlert.style.display = 'block';
                } else {
                    objSelect.disabled = false;
                }
            });
        } else {
            sectionIndividu.style.display = 'block';
            objSelect.innerHTML = '<option value="">Memuat...</option>';
            objSelect.disabled = true;
            fetch('{{ url("/penilaian-lomba/get-peserta") }}/' + lombaId + '?juri_lomba_id=' + juriId)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    pesertaSelect.innerHTML = '<option value="">-- Pilih Peserta --</option>';
                    if (data.length === 0) {
                        emptyAlert.style.display = 'block';
                    } else {
                        data.forEach(function (item) {
                            var opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.text;
                            pesertaSelect.appendChild(opt);
                        });
                        pesertaSelect.disabled = false;
                    }
                })
                .catch(function () {
                    pesertaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        }
    });

    objSelect.addEventListener('change', function () {
        btnToPane3.disabled = !this.value;
    });

    // ---------- Pane navigation buttons ----------
    document.getElementById('plToPane1').addEventListener('click', function () { goStep(1); });
    document.getElementById('plToPane2').addEventListener('click', function () {
        if (!lombaHidden.value) {
            LW.toast('err', 'Pilih lomba', 'Pilih lomba terlebih dahulu untuk melanjutkan.');
            return;
        }
        goStep(2);
    });
    document.getElementById('plToPane3').addEventListener('click', function () {
        var ok = !!lombaHidden.value && !!juriTarget.value && !!objSelect.value;
        if (!ok) {
            LW.toast('err', 'Lengkapi dulu', 'Pilih juri dan ' + (IS_TIM ? 'kelompok' : 'peserta') + ' terlebih dahulu.');
            return;
        }
        goStep(3);
    });
    document.getElementById('plToPane2b').addEventListener('click', function () { goStep(2); });

    // ---------- Submit validation ----------
    var submitting = false;
    form.addEventListener('submit', function (e) {
        if (submitting) { e.preventDefault(); return; }
        var inputs = document.querySelectorAll('.aspek-nilai');
        var hasValue = false;
        for (var i = 0; i < inputs.length; i++) {
            var v = parseFloat(inputs[i].value);
            if (!isNaN(v) && v >= 0) { hasValue = true; break; }
        }
        if (!hasValue) {
            e.preventDefault();
            aspekErrEl.querySelector('span').textContent = 'Minimal satu aspek harus diisi nilai.';
            aspekErrEl.style.display = 'flex';
            aspekWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        submitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="pl-loading"><span class="pl-spin"></span> Menyimpan...</span>';
    });

    // Ripple
    document.querySelectorAll('.lw-btn, .pl-pick-card').forEach(function (b) {
        b.addEventListener('click', function (e) { if (window.LW && LW.ripple) LW.ripple(e); });
    });

    goStep(1);
})();
</script>
@endpush
