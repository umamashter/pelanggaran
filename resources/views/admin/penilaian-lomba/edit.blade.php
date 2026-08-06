@extends('layouts.main')
@section('title', 'Edit Penilaian Lomba')
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

    /* ---------- Read-only context cards ---------- */
    .pl-meta-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
    .pl-meta { display: flex; align-items: center; gap: 12px; padding: 15px 16px; background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 14px; box-shadow: var(--lw-shadow); min-width: 0; }
    .pl-meta-icon { flex-shrink: 0; width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
    .pl-meta-icon.blue { background: var(--lw-navy-soft); color: var(--lw-primary); }
    .pl-meta-icon.green { background: var(--lw-green-soft); color: var(--lw-green); }
    .pl-meta-icon.amber { background: var(--lw-amber-soft); color: var(--lw-amber); }
    .pl-meta-icon.violet { background: var(--lw-violet-soft); color: var(--lw-violet); }
    .pl-meta-icon.sky { background: var(--lw-sky-soft); color: var(--lw-sky); }
    .pl-meta .l { font-size: 10px; font-weight: 700; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .5px; }
    .pl-meta .v { font-size: 13.5px; font-weight: 800; color: var(--lw-text); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pl-meta .v a { color: var(--lw-primary); text-decoration: none; }
    .pl-meta .v a:hover { text-decoration: underline; }

    /* ---------- Aspek editor ---------- */
    .pl-section-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 16px; box-shadow: var(--lw-shadow); overflow: hidden; margin-bottom: 20px; }
    .pl-section-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 16px 20px; border-bottom: 1px solid var(--lw-border); flex-wrap: wrap; }
    .pl-section-head b { font-size: 14.5px; font-weight: 800; color: var(--lw-text); display: flex; align-items: center; gap: 9px; }
    .pl-section-head b i { color: var(--lw-primary); font-size: 16px; }
    .pl-section-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .pl-section-head .right { display: flex; align-items: center; gap: 8px; }

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

    /* Hide table wrap since we use grid */
    .pl-aspek-wrap { border: none !important; background: none !important; box-shadow: none !important; }

    /* ---------- Footer bar ---------- */
    .pl-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--lw-border); flex-wrap: wrap; }
    .pl-foot-info { font-size: 12px; color: var(--lw-text-3); display: flex; align-items: center; gap: 7px; }
    .pl-foot-info i { color: var(--lw-green); }
    .pl-foot-info b { color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .pl-foot .btns { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .pl-loading { display: inline-flex; align-items: center; gap: 8px; }
    .pl-spin { width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: plSpin .7s linear infinite; }
    @keyframes plSpin { to { transform: rotate(360deg); } }

    @media (max-width: 991.98px) { .pl-meta-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575.98px) {
        .pl-meta-grid { grid-template-columns: 1fr; }
        .pl-foot { flex-direction: column; align-items: stretch; }
        .pl-foot .btns { justify-content: flex-end; }
    }
</style>

@php
    $pl = $penilaianLomba->pesertaLomba;
    $lomba = $pl->lomba ?? null;
    $isTim = $lombaJenis === 'Tim';
    $kl = $isTim ? ($kelompokLombas->firstWhere('id', $currentKelompokId) ?? null) : null;
    $juriNama = $penilaianLomba->juriLomba->guru->nama ?? 'Juri #' . $penilaianLomba->juri_lomba_id;
    $pesertaNama = $isTim
        ? ($kl->nama_kelompok ?? '-')
        : ($pl->student->user->name ?? $pl->student->nama ?? '-');
    $sesiNama = $sesiLombas->firstWhere('id', $currentSesiId)->nama ?? '-';
@endphp

<div class="lw-mod pl-mod">
    <div class="pl-builder">

        <nav class="pl-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('penilaian-lomba.index') }}"><i class="bi bi-star"></i> Penilaian Lomba</a>
            <i class="bi bi-chevron-right"></i>
            <span>Edit Penilaian</span>
        </nav>

        <div class="pl-builder-head">
            <div class="pl-builder-head-left">
                <span class="pl-builder-icon"><i class="bi bi-pencil-square"></i></span>
                <div>
                    <h2 class="pl-builder-title">Edit Penilaian Lomba</h2>
                    <p class="pl-builder-sub">Periksa kembali konteks penilaian lalu ubah nilai aspek yang diperlukan.</p>
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

        <form action="{{ route('penilaian-lomba.update', $penilaianLomba->id) }}" method="POST" id="penilaianForm">
            @csrf @method('PUT')

            <input type="hidden" name="juri_lomba_id" value="{{ old('juri_lomba_id', $penilaianLomba->juri_lomba_id) }}">
            <input type="hidden" name="peserta_lomba_id" value="{{ old('peserta_lomba_id', $penilaianLomba->peserta_lomba_id) }}">
            @if($isTim)
            <input type="hidden" name="kelompok_lomba_id" value="{{ $currentKelompokId }}">
            @endif

            {{-- READ-ONLY CONTEXT --}}
            <div class="pl-meta-grid">
                <div class="pl-meta"><span class="pl-meta-icon green"><i class="bi bi-calendar-event"></i></span><div><div class="l">Sesi Lomba</div><div class="v">{{ $sesiNama }}</div></div></div>
                <div class="pl-meta"><span class="pl-meta-icon blue"><i class="bi bi-trophy"></i></span><div><div class="l">Lomba</div><div class="v">{{ $lomba->nama ?? '-' }}</div></div></div>
                <div class="pl-meta"><span class="pl-meta-icon violet"><i class="bi bi-gavel"></i></span><div><div class="l">Juri</div><div class="v">{{ $juriNama }}</div></div></div>
                <div class="pl-meta"><span class="pl-meta-icon {{ $isTim ? 'amber' : 'sky' }}"><i class="bi {{ $isTim ? 'bi-people' : 'bi-person-badge' }}"></i></span><div><div class="l">{{ $isTim ? 'Kelompok' : 'Peserta' }}</div>
                    <div class="v">
                        @if($isTim && $kl && $kl->id)
                        <a href="{{ route('kelompok-lomba.show', $kl->id) }}"><i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i> {{ $pesertaNama }}</a>
                        @else
                        {{ $pesertaNama }}
                        @endif
                    </div>
                </div></div>
            </div>

            {{-- ASPEK EDITOR --}}
            <div class="pl-section-card">
                <div class="pl-section-head">
                    <div>
                        <b><i class="bi bi-pencil-square"></i> Aspek Penilaian</b>
                        <div class="pl-section-sub">Ubah nilai untuk setiap aspek (0 – 100).</div>
                    </div>
                    <div class="right">
                        <span class="lw-chip"><i class="bi bi-hash"></i>{{ $aspekPenilaians->count() }} aspek</span>
                        <span class="lw-chip lw-chip--navy"><i class="bi bi-calculator"></i>Total <b class="ms-1" id="sumTotal">0</b></span>
                    </div>
                </div>

                <div class="pl-aspek-wrap">
                    <div id="aspek-table-body">
                        @forelse($aspekPenilaians as $idx => $ap)
                        <div class="pl-score-card">
                            <div class="pl-score-head">
                                <h4 class="pl-score-title">{{ $ap->nama_aspek }}</h4>
                                <span class="pl-score-num">{{ $idx + 1 }}</span>
                            </div>
                            <div class="pl-input-group">
                                <input type="number" step="0.01" name="nilai[]" class="pl-score-input aspek-nilai"
                                    placeholder="0" min="0" max="100" inputmode="decimal"
                                    aria-label="Nilai aspek {{ $idx + 1 }}"
                                    value="{{ old('nilai.' . $idx, isset($allPenilaian[$ap->id]) ? $allPenilaian[$ap->id]->nilai : '') }}"
                                    data-idx="{{ $idx }}">
                                <div class="pl-slider-wrap">
                                    <input type="range" class="pl-slider aspek-slider" min="0" max="100" step="1"
                                        value="{{ old('nilai.' . $idx, isset($allPenilaian[$ap->id]) ? $allPenilaian[$ap->id]->nilai : '') }}"
                                        data-idx="{{ $idx }}">
                                    <div class="pl-range-label">
                                        <span>0</span>
                                        <span>100</span>
                                    </div>
                                </div>
                                <input type="hidden" name="aspek_penilaian_id[]" value="{{ $ap->id }}">
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:40px; color:var(--lw-text-3);">
                            <i class="bi bi-info-circle mb-2" style="font-size:24px; display:block;"></i>
                            Tidak ada aspek penilaian untuk lomba ini
                        </div>
                        @endforelse
                    </div>
                </div>

                <div id="aspek-error" class="lw-alert lw-alert--err" style="display:none;margin:14px 16px 0;border-radius:11px;padding:10px 14px;font-size:12.5px;"><i class="bi bi-exclamation-circle"></i> <span></span></div>

                <div class="pl-foot" style="margin:18px 20px 20px;">
                    <div class="pl-foot-info"><i class="bi bi-check2-circle"></i> <span>Total nilai seluruh aspek: <b id="sumTotalFoot">0</b></span></div>
                    <div class="btns">
                        <a href="{{ route('penilaian-lomba.index') }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-left"></i> Batal</a>
                        <button type="submit" id="submitBtn" class="lw-btn lw-btn--success"><i class="bi bi-check2"></i> Simpan Perubahan</button>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('penilaianForm');
    if (!form) return;

    function updateTotal() {
        var sum = 0;
        document.querySelectorAll('.aspek-nilai').forEach(function (inp) {
            var v = parseFloat(inp.value);
            if (!isNaN(v) && v > 0) sum += v;
        });
        var s = sum.toLocaleString('id-ID');
        var el1 = document.getElementById('sumTotal');
        var el2 = document.getElementById('sumTotalFoot');
        if (el1) el1.textContent = s;
        if (el2) el2.textContent = s;
    }

    document.querySelectorAll('.aspek-nilai').forEach(function (inp) {
        inp.addEventListener('input', function () {
            document.getElementById('aspek-error').style.display = 'none';
            updateTotal();
        });
    });

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
            var errEl = document.getElementById('aspek-error');
            errEl.querySelector('span').textContent = 'Minimal satu aspek harus diisi nilai.';
            errEl.style.display = 'flex';
            errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        submitting = true;
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="pl-loading"><span class="pl-spin"></span> Menyimpan...</span>';
    });

    updateTotal();

    document.querySelectorAll('.lw-btn').forEach(function (b) {
        b.addEventListener('click', function (e) { if (window.LW && LW.ripple) LW.ripple(e); });
    });
})();
</script>
@endpush
