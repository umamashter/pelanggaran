@extends('layouts.main')
@section('title', 'Detail Rubrik — ' . ($lomba->nama ?? 'Penilaian'))
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    /* ---------- Rubrik Detail ---------- */
    .ar-detail { max-width: 980px; margin: 0 auto; }

    .ar-stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 18px; }
    .ar-stat { display: flex; align-items: center; gap: 12px; padding: 15px 16px; }
    .ar-stat-icon { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
    .ar-stat-icon.blue { background: var(--lw-navy-soft); color: var(--lw-primary); }
    .ar-stat-icon.green { background: var(--lw-green-soft); color: var(--lw-green); }
    .ar-stat-icon.amber { background: var(--lw-amber-soft); color: var(--lw-amber); }
    .ar-stat-icon.violet { background: var(--lw-violet-soft); color: var(--lw-violet); }
    .ar-stat-num { font-size: 19px; font-weight: 700; line-height: 1.1; color: var(--lw-text); font-variant-numeric: tabular-nums; }
    .ar-stat-label { font-size: 10.5px; font-weight: 600; color: var(--lw-text-3); text-transform: uppercase; letter-spacing: .4px; }

    .ar-aspek-list { display: flex; flex-direction: column; gap: 10px; }
    .ar-aspek-card { display: flex; align-items: center; gap: 13px; padding: 13px 15px; background: var(--lw-card); border: 1px solid var(--lw-border);
        border-radius: 13px; box-shadow: var(--lw-shadow); transition: all .2s ease; }
    .ar-aspek-card:hover { border-color: var(--lw-primary-border); transform: translateX(3px); box-shadow: var(--lw-shadow-lg); }
    .ar-aspek-num { flex-shrink: 0; width: 34px; height: 34px; border-radius: 10px; background: var(--lw-navy-soft); color: var(--lw-primary);
        font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; font-variant-numeric: tabular-nums; }
    .ar-aspek-name { flex: 1; min-width: 0; font-size: 13.5px; font-weight: 600; color: var(--lw-text); }
    .ar-aspek-name small { display: block; font-size: 10.5px; color: var(--lw-text-3); font-weight: 600; margin-top: 1px; }
    .ar-aspek-acts { display: flex; gap: 7px; align-items: center; flex-shrink: 0; }
    .ar-act-btn { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: 1.5px solid var(--lw-border);
        background: var(--lw-card); color: var(--lw-text-2); font-size: 13px; cursor: pointer; transition: all .2s ease; text-decoration: none; }
    .ar-act-btn:hover { transform: translateY(-1px); box-shadow: var(--lw-shadow); }
    .ar-act-btn.edit:hover { color: var(--lw-amber); border-color: var(--lw-amber-border); }
    .ar-act-btn.del:hover { color: var(--lw-red); border-color: var(--lw-red-border); background: var(--lw-red-soft); }
    .ar-act-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }
    .ar-act-btn:disabled:hover { color: var(--lw-text-2); border-color: var(--lw-border); background: var(--lw-card); }

    .ar-builder-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
    .ar-builder-foot .btns { display: flex; gap: 8px; align-items: center; }

    @media (max-width: 767.98px) {
        .ar-builder-foot { flex-direction: column; align-items: stretch; }
        .ar-builder-foot .btns { justify-content: flex-end; }
        .ar-aspek-card { flex-wrap: wrap; }
        .ar-aspek-name { flex: 1 1 100%; order: -1; }
        .ar-hero-right { width: 100%; }
        .ar-hero-right .lw-btn { flex: 1; justify-content: center; }
    }
</style>

@php
    $isLocked = $lomba->is_haflah_selesai ?? false;
    $total = $aspekPenilaians->count();
    $pesertaCount = $lomba->peserta()->count();
    $lockedText = $isLocked ? 'Terkunci — Haflah Selesai' : 'Aktif — Dapat Diubah';
    $jenisIc = ($lomba->jenis ?? 'Individu') === 'Tim' ? 'bi-people' : 'bi-person';
@endphp

<div class="lw-mod">
    <div class="ar-detail">

        {{-- HERO --}}
        <div class="lw-hero">
            <div class="lw-hero-grid">
                <div class="lw-hero-left">
                    <span class="lw-hero-icon"><i class="bi bi-clipboard2-check"></i></span>
                    <div>
                        <h1 class="lw-hero-title">{{ $lomba->nama ?? 'Lomba' }}</h1>
                        <p class="lw-hero-sub">Detail rubrik penilaian yang digunakan juri saat menilai lomba ini.</p>
                        <div class="lw-hero-badges">
                            <span class="lw-hero-badge"><i class="bi {{ $jenisIc }}"></i>{{ $lomba->jenis ?? 'Individu' }}</span>
                            <span class="lw-hero-badge"><i class="bi bi-flag"></i>{{ $lomba->status ?? '-' }}</span>
                            <span class="lw-hero-badge"><i class="bi bi-list-check"></i>{{ $total }} aspek</span>
                            <span class="lw-hero-badge {{ $isLocked ? 'lw-hero-badge--warn' : 'lw-hero-badge--ok' }}"><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-unlock' }}"></i>{{ $lockedText }}</span>
                        </div>
                    </div>
                </div>
                <div class="lw-hero-right">
                    <a href="{{ route('aspek-penilaian.index') }}" class="lw-btn lw-btn--light"><i class="bi bi-arrow-left"></i> Kembali</a>
                    @if($total > 0)
                    <a href="{{ route('aspek-penilaian.cetak-pdf', $lomba->id) }}" target="_blank" class="lw-btn lw-btn--light"><i class="bi bi-filetype-pdf"></i> Cetak PDF</a>
                    <a href="{{ route('aspek-penilaian.export-excel', $lomba->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-filetype-xlsx"></i> Export Excel</a>
                    @endif
                    @if(!$isLocked && $total > 0)
                    <a href="{{ route('aspek-penilaian.edit', $aspekPenilaians->first()->id) }}" class="lw-btn lw-btn--solid"><i class="bi bi-pencil"></i> Edit</a>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
        @endif

        {{-- SUMMARY --}}
        <div class="ar-stat-grid">
            <div class="lw-card lw-card-pad ar-stat"><span class="ar-stat-icon blue"><i class="bi bi-list-check"></i></span><div><div class="ar-stat-num">{{ $total }}</div><div class="ar-stat-label">Total Aspek</div></div></div>
            <div class="lw-card lw-card-pad ar-stat"><span class="ar-stat-icon green"><i class="bi bi-check2-circle"></i></span><div><div class="ar-stat-num">{{ $total >= 4 ? 'Lengkap' : 'Minimal' }}</div><div class="ar-stat-label">Status Rubrik</div></div></div>
            <div class="lw-card lw-card-pad ar-stat"><span class="ar-stat-icon amber"><i class="bi bi-people"></i></span><div><div class="ar-stat-num">{{ $pesertaCount }}</div><div class="ar-stat-label">Peserta</div></div></div>
            <div class="lw-card lw-card-pad ar-stat"><span class="ar-stat-icon violet"><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-unlock' }}"></i></span><div><div class="ar-stat-num">{{ $isLocked ? 'Terkunci' : 'Aktif' }}</div><div class="ar-stat-label">Edit Akses</div></div></div>
        </div>

        {{-- ASPEK LIST --}}
        <div class="lw-card lw-card-pad" style="margin-bottom:18px;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                    <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-journal-check"></i> Daftar Aspek Penilaian</div>
                    <div class="lw-section-sub" style="margin:2px 0 0;">Urutan aspek sesuai dengan lembar penilaian juri.</div>
                </div>
                @if($total > 0)
                <span class="lw-chip {{ $isLocked ? 'lw-chip--red' : 'lw-chip--green' }}"><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-shield-check' }}"></i>{{ $lockedText }}</span>
                @endif
            </div>

            @if($aspekPenilaians->isEmpty())
                <div class="lw-empty">
                    <div class="lw-empty-illus"><div class="ring"></div><div class="core"><i class="bi bi-journal-x"></i></div></div>
                    <div class="lw-empty-title">Belum ada aspek penilaian</div>
                    <div class="lw-empty-sub">Lomba ini belum memiliki rubrik. Buat rubrik agar juri dapat menilai peserta.</div>
                    @if(!$isLocked)
                    <a href="{{ route('aspek-penilaian.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Buat Rubrik</a>
                    @endif
                </div>
            @else
                <div class="ar-aspek-list">
                    @foreach($aspekPenilaians as $idx => $a)
                    <div class="ar-aspek-card">
                        <span class="ar-aspek-num">{{ $idx + 1 }}</span>
                        <div class="ar-aspek-name">
                            {{ $a->nama_aspek }}
                            <small>Aspek #{{ $a->id }} &middot; Bobot {{ $a->bobot ?? '-' }}</small>
                        </div>
                        <div class="ar-aspek-acts">
                            @if($isLocked)
                            <span class="ar-act-btn" title="Terkunci — haflah selesai"><i class="bi bi-lock-fill"></i></span>
                            @else
                            <a href="{{ route('aspek-penilaian.edit', $a->id) }}" class="ar-act-btn edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>
                            <button type="button" class="ar-act-btn del" title="Hapus" aria-label="Hapus"
                                data-ar-del-one data-id="{{ $a->id }}" data-nama="{{ e($a->nama_aspek) }}"><i class="bi bi-trash"></i></button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($aspekPenilaians->isNotEmpty())
        <div class="ar-builder-foot">
            <div class="d-flex align-items-center gap-2" style="font-size:12px;color:var(--lw-text-3);"><i class="bi {{ $isLocked ? 'bi-lock-fill' : 'bi-shield-check' }}"></i> {{ $lockedText }} &middot; {{ $total }} aspek penilaian terpasang</div>
            <div class="btns">
                @if(!$isLocked)
                <button type="button" class="lw-btn lw-btn--soft lw-btn--sm" style="color:var(--lw-red);border-color:var(--lw-red-border);background:var(--lw-red-soft);" id="arHapusSemua" data-lomba="{{ $lomba->nama }}" data-id="{{ $lomba->id }}" data-jml="{{ $total }}"><i class="bi bi-trash3"></i> Hapus Semua</button>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

<form id="arDelForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var delForm = document.getElementById('arDelForm');

    /* ---------- delete one ---------- */
    document.querySelectorAll('[data-ar-del-one]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.id, nama = btn.dataset.nama;
            LW.confirm('Hapus Aspek?', 'Aspek "' + nama + '" akan dihapus dari rubrik dan tidak dapat dikembalikan.', 'bi-trash').then(function (ok) {
                if (ok) { delForm.action = '{{ url('aspek-penilaian') }}/' + id; delForm.submit(); }
            });
        });
    });

    /* ---------- delete all ---------- */
    var allBtn = document.getElementById('arHapusSemua');
    if (allBtn) {
        allBtn.addEventListener('click', function () {
            var id = allBtn.dataset.id, nama = allBtn.dataset.lomba, jml = parseInt(allBtn.dataset.jml, 10) || 0;
            LW.confirm('Hapus Semua Aspek?', 'Seluruh aspek penilaian lomba "' + nama + '" (' + jml + ' aspek) akan dihapus dan tidak dapat dikembalikan.', 'bi-trash').then(function (ok) {
                if (ok) { delForm.action = '{{ url('aspek-penilaian/hapus-semua') }}/' + id; delForm.submit(); }
            });
        });
    }
})();
</script>
@endpush
@endsection
