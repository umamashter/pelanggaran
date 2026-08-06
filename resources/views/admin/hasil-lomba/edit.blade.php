@extends('layouts.main')
@section('title', 'Edit Hasil Lomba')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .hl-mod { --hl-radius: 16px; }
    .hl-wrap { max-width: 1100px; margin: 0 auto; }
    .hl-grid { display: grid; grid-template-columns: minmax(0,1.2fr) minmax(320px,.8fr); gap: 20px; align-items: start; }
    .hl-card { background: var(--lw-card); border: 1px solid var(--lw-border); border-radius: 18px; box-shadow: var(--lw-shadow); overflow: hidden; }
    .hl-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--lw-border); flex-wrap: wrap; }
    .hl-card-head b { font-size: 14.5px; font-weight: 800; color: var(--lw-text); display: inline-flex; align-items: center; gap: 8px; }
    .hl-card-head b i { color: var(--lw-primary); }
    .hl-card-sub { font-size: 11.5px; color: var(--lw-text-3); margin-top: 2px; }
    .hl-card-body { padding: 20px; }
    .hl-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .hl-meta { padding: 14px; border-radius: 16px; background: var(--lw-bg); border: 1px solid var(--lw-border); }
    .hl-meta .k { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-meta .v { margin-top: 4px; font-size: 15px; font-weight: 800; color: var(--lw-text); }
    .hl-diff { display: grid; gap: 12px; }
    .hl-diff-item { padding: 16px; border-radius: 16px; border: 1px solid var(--lw-border); background: linear-gradient(180deg, rgba(255,255,255,.82), rgba(255,255,255,.64)); }
    html.dark-mode .hl-diff-item { background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.03)); }
    .hl-diff-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .hl-diff-label { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: var(--lw-text-3); font-weight: 700; }
    .hl-diff-vals { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
    .hl-diff-old, .hl-diff-new { padding: 10px 12px; border-radius: 14px; font-size: 15px; font-weight: 800; }
    .hl-diff-old { background: var(--lw-bg); color: var(--lw-text-2); border: 1px solid var(--lw-border); }
    .hl-diff-new { background: var(--lw-primary-soft); color: var(--lw-primary); border: 1px solid var(--lw-primary-border); }
    .hl-diff-arrow { color: var(--lw-text-3); font-size: 18px; }
    .hl-sync-note { padding: 16px; border-radius: 16px; background: var(--lw-violet-soft); border: 1px solid var(--lw-violet-border); color: var(--lw-violet); font-size: 12px; font-weight: 700; }
    .hl-actions { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--lw-border); }
    .hl-loading { display: inline-flex; align-items: center; gap: 8px; }
    .hl-spin { width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: hlSpin .7s linear infinite; }
    @keyframes hlSpin { to { transform: rotate(360deg); } }
    @media (max-width: 991.98px) { .hl-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) { .hl-meta-grid { grid-template-columns: 1fr; } .hl-diff-vals { flex-direction: column; align-items: stretch; } }
</style>
@endpush

@section('content')
@include('component.admin.lomba-workspace')

@php
    $pl = $hasilLomba->pesertaLomba;
    $isIndividu = $pl->isIndividu();
    $backUrl = route('hasil-lomba.index', ['tab' => $isIndividu ? 'individu' : 'kelompok']);
    $newTotal = \App\Models\PenilaianLomba::where('peserta_lomba_id', $hasilLomba->peserta_lomba_id)->sum('nilai');
    $allInLomba = \App\Models\HasilLomba::where('lomba_id', $hasilLomba->lomba_id)->orderByDesc('total_nilai')->get();
    $newRank = optional($allInLomba->sortByDesc(function ($row) use ($hasilLomba, $newTotal) {
        return $row->id == $hasilLomba->id ? $newTotal : $row->total_nilai;
    })->values()->firstWhere('id', $hasilLomba->id))->peringkat ?? $hasilLomba->peringkat;
@endphp

<div class="lw-mod hl-mod">
    <div class="hl-wrap">
        <div class="lw-hero">
            <div class="lw-hero-grid">
                <div class="lw-hero-left">
                    <span class="lw-hero-icon"><i class="bi bi-arrow-repeat"></i></span>
                    <div>
                        <h1 class="lw-hero-title">Review Dashboard</h1>
                        <p class="lw-hero-sub">Bandingkan nilai lama dengan hasil sinkronisasi terbaru sebelum menyimpan ulang ranking resmi.</p>
                        <div class="lw-hero-badges">
                            <span class="lw-hero-badge"><i class="bi bi-trophy"></i>{{ $hasilLomba->lomba->nama ?? '-' }}</span>
                            <span class="lw-hero-badge"><i class="bi {{ $isIndividu ? 'bi-person' : 'bi-people' }}"></i>{{ $isIndividu ? 'Individu' : 'Kelompok' }}</span>
                        </div>
                    </div>
                </div>
                <div class="lw-hero-right">
                    <a href="{{ $backUrl }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>

        <div class="hl-grid">
            <div class="hl-card">
                <div class="hl-card-head">
                    <div>
                        <b><i class="bi bi-grid-1x2"></i> Konteks Hasil</b>
                        <div class="hl-card-sub">Semua field readonly. Backend tetap menentukan total, ranking, dan juara.</div>
                    </div>
                </div>
                <div class="hl-card-body">
                    <form action="{{ route('hasil-lomba.update', $hasilLomba->id) }}" method="POST" id="syncForm">
                        @csrf @method('PUT')
                        <input type="hidden" name="lomba_id" value="{{ $hasilLomba->lomba_id }}">
                        <input type="hidden" name="peserta_lomba_id" value="{{ $hasilLomba->peserta_lomba_id }}">

                        <div class="hl-meta-grid">
                            <div class="hl-meta"><div class="k">Lomba</div><div class="v">{{ $hasilLomba->lomba->nama ?? '-' }}</div></div>
                            <div class="hl-meta"><div class="k">Jenis</div><div class="v">{{ $isIndividu ? 'Individu' : 'Kelompok' }}</div></div>
                            <div class="hl-meta"><div class="k">Peserta</div><div class="v">{{ $isIndividu ? ($pl->student->user->name ?? $pl->student->nama ?? '-') : ($pl->kelompokLomba->nama_kelompok ?? '-') }}</div></div>
                            <div class="hl-meta"><div class="k">Juara Saat Ini</div><div class="v">{{ $hasilLomba->juara ?? '-' }}</div></div>
                            <div class="hl-meta"><div class="k">Total Nilai Saat Ini</div><div class="v">{{ number_format($hasilLomba->total_nilai, 1) }}</div></div>
                            <div class="hl-meta"><div class="k">Peringkat Saat Ini</div><div class="v">#{{ $hasilLomba->peringkat }}</div></div>
                        </div>

                        <div class="hl-actions">
                            <a href="{{ $backUrl }}" class="lw-btn lw-btn--ghost"><i class="bi bi-x-circle"></i> Batal</a>
                            <button type="submit" class="lw-btn lw-btn--success" id="syncBtn"><i class="bi bi-arrow-repeat"></i> Sinkronisasi Ulang</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="hl-card">
                <div class="hl-card-head">
                    <div>
                        <b><i class="bi bi-arrow-left-right"></i> Sync Preview</b>
                        <div class="hl-card-sub">Preview perubahan sebelum submit.</div>
                    </div>
                </div>
                <div class="hl-card-body">
                    <div class="hl-diff">
                        <div class="hl-diff-item">
                            <div class="hl-diff-top"><span class="hl-diff-label">Nilai Lama → Nilai Baru</span></div>
                            <div class="hl-diff-vals">
                                <span class="hl-diff-old">{{ number_format($hasilLomba->total_nilai, 1) }}</span>
                                <span class="hl-diff-arrow"><i class="bi bi-arrow-right"></i></span>
                                <span class="hl-diff-new">{{ number_format($newTotal, 1) }}</span>
                            </div>
                        </div>
                        <div class="hl-diff-item">
                            <div class="hl-diff-top"><span class="hl-diff-label">Peringkat Saat Ini → Perkiraan Baru</span></div>
                            <div class="hl-diff-vals">
                                <span class="hl-diff-old">#{{ $hasilLomba->peringkat }}</span>
                                <span class="hl-diff-arrow"><i class="bi bi-arrow-right"></i></span>
                                <span class="hl-diff-new">#{{ $newRank }}</span>
                            </div>
                        </div>
                        <div class="hl-sync-note"><i class="bi bi-info-circle me-1"></i> Highlight perubahan ini hanya preview visual. Setelah submit, controller akan menghitung ulang ranking resmi seluruh lomba.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('syncForm');
    if (!form) return;
    var submitting = false;
    form.addEventListener('submit', function (e) {
        if (submitting) { e.preventDefault(); return; }
        submitting = true;
        var btn = document.getElementById('syncBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="hl-loading"><span class="hl-spin"></span> Menyinkronkan...</span>';
    });
    document.querySelectorAll('.lw-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) { if (window.LW) LW.ripple(e); });
    });
})();
</script>
@endpush
