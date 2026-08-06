@extends('layouts.main')
@section('title', 'Kelompok Lomba')
@section('content')
@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .lw-team-card { position: relative; overflow: hidden; border: 1px solid var(--lw-border); border-radius: 16px; background: var(--lw-card); box-shadow: var(--lw-shadow); padding: 16px; display: flex; flex-direction: column; gap: 11px; transition: all .22s ease; }
    .lw-team-card:hover { border-color: var(--lw-primary-border); transform: translateY(-3px); box-shadow: var(--lw-shadow-lg); }
    .lw-team-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--lw-grad); opacity: 0; transition: opacity .2s; }
    .lw-team-card:hover::before { opacity: 1; }
    .lw-team-card.is-locked { opacity: .68; }
    .lw-team-card.is-locked:hover { opacity: .85; }
    .lw-team-top { display: flex; align-items: flex-start; gap: 12px; }
    .lw-team-ava { width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; letter-spacing: .5px; }
    .lw-team-name { font-weight: 800; color: var(--lw-text); font-size: 14px; line-height: 1.3; }
    .lw-team-name a { color: inherit; text-decoration: none; }
    .lw-team-name a:hover { color: var(--lw-primary); }
    .lw-team-meta { font-size: 11px; color: var(--lw-text-3); margin-top: 2px; }
    .lw-team-meta i { font-size: 11px; color: var(--lw-primary); }

    .lw-team-members { display: flex; align-items: center; margin-top: 4px; }
    .lw-ava-sm { width: 24px; height: 24px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; color: #fff; border: 2px solid var(--lw-card); margin-left: -6px; }
    .lw-ava-sm:first-child { margin-left: 0; }
    .lw-members-plus { font-size: 10px; color: var(--lw-text-3); font-weight: 700; margin-left: 4px; }

    .lw-team-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .lw-team-stat { background: var(--lw-bg); border-radius: 10px; padding: 9px 8px; text-align: center; }
    .lw-team-stat .v { font-size: 16px; font-weight: 800; color: var(--lw-text); line-height: 1; font-variant-numeric: tabular-nums; }
    .lw-team-stat .l { font-size: 9.5px; color: var(--lw-text-3); margin-top: 3px; font-weight: 600; text-transform: uppercase; letter-spacing: .2px; }

    .lw-completion { display: flex; align-items: center; gap: 6px; }
    .lw-completion .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .lw-completion .dot.g { background: var(--lw-green); }
    .lw-completion .dot.y { background: var(--lw-amber); }
    .lw-completion .dot.w { background: var(--lw-text-3); }
    .lw-completion .lbl { font-size: 11px; font-weight: 600; color: var(--lw-text-3); }

    .lw-team-actions { display: flex; gap: 8px; padding-top: 10px; border-top: 1px dashed var(--lw-border); }
    .lw-team-actions .lw-act-btn { flex: 1; width: auto; }
    .lw-act-btn.is-off { opacity: .4; cursor: not-allowed; }
    .lw-act-btn.is-off:hover { transform: none; box-shadow: none; color: var(--lw-text-2); }

    .lw-client-empty { display: none; padding: 32px 16px; text-align: center; color: var(--lw-text-3); }

    @media (max-width: 1199.98px) { .lw-team-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767.98px) { .lw-team-grid { grid-template-columns: 1fr; } }
</style>

<div class="lw-mod jd-page-kelompok">

@php
    $pageItems = $kelompokLombas->getCollection();
    $activeHaflah = $haflatuls->firstWhere('id', session('haflah_id'));
    $total = $kelompokLombas->total();
    $pageTotal = $pageItems->count();
    $currentCount = max(1, $pageTotal);
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');

    $totalAnggota = $pageItems->sum('anggota_count');
    $tanpaAnggota = $pageItems->filter(fn($k) => $k->anggota_count == 0)->count();
    $denganAnggota = $pageItems->filter(fn($k) => $k->anggota_count > 0)->count();
    $locked = $pageItems->filter(fn($k) => $k->is_haflah_selesai)->count();
@endphp

<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-people-fill"></i></span>
            <div>
                <h1 class="lw-hero-title">Kelompok Lomba</h1>
                <p class="lw-hero-sub">Team Management — kelola kelompok peserta lomba tim, pantau kelengkapan anggota, dan status kesiapan.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-calendar3"></i>{{ optional($activeHaflah)->nama_acara ?? 'Haflah belum dipilih' }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-clock"></i>{{ $today }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-hash"></i>{{ $total }} kelompok</span>
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('kelompok-lomba.create') }}" class="lw-btn lw-btn--accent"><i class="bi bi-plus-lg"></i> Tambah</a>
            <a href="{{ route('kelompok-lomba.cetak-pdf', request()->query()) }}" class="lw-btn lw-btn--light" target="_blank" title="Export PDF"><i class="bi bi-filetype-pdf"></i></a>
            <a href="{{ route('kelompok-lomba.index') }}" class="lw-btn lw-btn--light" title="Refresh"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
@endif

{{-- HEALTH PANEL --}}
<div class="lw-kpi-grid" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr));margin-bottom:16px;">
    <div class="lw-card lw-kpi"><span class="lw-kpi-icon navy"><i class="bi bi-people-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $total }}">{{ $total }}</div><div class="lw-kpi-label">Total Kelompok</div><div class="lw-kpi-sub">Semua kategori</div></div></div>
    <div class="lw-card lw-kpi"><span class="lw-kpi-icon green"><i class="bi bi-check-circle-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $denganAnggota }}">{{ $denganAnggota }}</div><div class="lw-kpi-label">Punya Anggota</div><div class="lw-kpi-sub">{{ $currentCount ? round(($denganAnggota/$currentCount)*100) : 0 }}% dari halaman</div></div></div>
    <div class="lw-card lw-kpi"><span class="lw-kpi-icon amber"><i class="bi bi-exclamation-circle-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $tanpaAnggota }}">{{ $tanpaAnggota }}</div><div class="lw-kpi-label">Tanpa Anggota</div><div class="lw-kpi-sub">{{ $currentCount ? round(($tanpaAnggota/$currentCount)*100) : 0 }}% dari halaman</div></div></div>
    <div class="lw-card lw-kpi"><span class="lw-kpi-icon violet"><i class="bi bi-lock-fill"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $locked }}">{{ $locked }}</div><div class="lw-kpi-label">Terkunci</div><div class="lw-kpi-sub">Haflah selesai</div></div></div>
    <div class="lw-card lw-kpi"><span class="lw-kpi-icon sky"><i class="bi bi-person-heart"></i></span><div class="lw-kpi-main"><div class="lw-kpi-num" data-count="{{ $totalAnggota }}">{{ $totalAnggota }}</div><div class="lw-kpi-label">Total Anggota</div><div class="lw-kpi-sub">Di halaman ini</div></div></div>
</div>

{{-- TOOLBAR --}}
<div class="lw-toolbar" id="klToolbar">
    <form id="klFilter" method="GET" style="display:contents;" autocomplete="off">
        <div class="lw-search" style="min-width:200px;">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" class="lw-control" id="klQuickSearch" placeholder="Cari nama kelompok...">
        </div>
        <div class="lw-filter"><label>Haflah</label>
            <select name="haflah_id" class="lw-select">
                <option value="">Haflah Aktif</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Kelas</label>
            <select name="kelas" class="lw-select">
                <option value="">Semua Kelas</option>
                @foreach(range(1,6) as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Lomba</label>
            <select name="lomba_id" class="lw-select">
                <option value="">Semua Lomba</option>
                @foreach($lombas as $lmb)
                    <option value="{{ $lmb->id }}" {{ request('lomba_id') == $lmb->id ? 'selected' : '' }}>{{ $lmb->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter"><label>Entri</label>
            <select name="per_page" class="lw-select" style="min-width:70px;">
                @foreach([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int) $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-toolbar-actions">
            <a href="{{ route('kelompok-lomba.index') }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

{{-- CARD GRID --}}
<div class="lw-card lw-card-pad" style="margin-bottom:18px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="lw-section-title" style="margin-bottom:0;"><i class="bi bi-people-fill"></i> Team Grid</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <span class="lw-chip lw-chip--green lw-chip-mini">Lengkap</span>
            <span class="lw-chip lw-chip--amber lw-chip-mini">Kosong</span>
            <span class="lw-chip lw-chip--violet lw-chip-mini">Terkunci</span>
        </div>
    </div>

    @if($kelompokLombas->isEmpty())
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-people-fill"></i></div></div>
            <div class="lw-empty-title">Belum Ada Kelompok</div>
            <p class="lw-empty-sub">Mulai dengan menambahkan kelompok pertama untuk lomba tim yang sedang aktif.</p>
            <a href="{{ route('kelompok-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Kelompok Pertama</a>
        </div>
    @else
        <div class="lw-client-empty" id="clientEmpty"><i class="bi bi-search mb-3" style="font-size:22px;display:block;"></i>Tidak ada kelompok yang cocok.</div>

        <div class="lw-team-grid" id="klCardGrid">
            @foreach($kelompokLombas as $kl)
                @php
                    $isLocked = $kl->is_haflah_selesai;
                    $hasAnggota = $kl->anggota_count > 0;

                    $statusLabel = $isLocked ? 'Terkunci' : ($hasAnggota ? 'Lengkap' : 'Tanpa Anggota');
                    $statusClass = $isLocked ? 'lw-chip--violet' : ($hasAnggota ? 'lw-chip--green' : 'lw-chip--amber');
                    $completionDot = $isLocked ? 'w' : ($hasAnggota ? 'g' : 'y');

                    $kelasLabel = 'Semua';
                    $klMin = $kl->lomba->kelas_min ?? null;
                    $klMax = $kl->lomba->kelas_max ?? null;
                    if ($klMin && $klMax) $kelasLabel = 'K'.$klMin.'-'.$klMax;
                    elseif ($klMin) $kelasLabel = 'K'.$klMin.'+';
                    elseif ($klMax) $kelasLabel = 's/d K'.$klMax;

                    $filterText = strtolower(trim($kl->nama_kelompok.' '.($kl->lomba->nama ?? '').' '.$statusLabel.' '.$kelasLabel));
                @endphp
                <div class="lw-team-card {{ $isLocked ? 'is-locked' : '' }}" data-kl-item data-filter="{{ $filterText }}">
                    <div class="lw-team-top">
                        <span class="lw-team-ava" style="background:{{ lw_ava_color($kl->nama_kelompok) }};">{{ lw_initial($kl->nama_kelompok) }}</span>
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="lw-team-name"><a href="{{ route('kelompok-lomba.show', $kl->id) }}">{{ $kl->nama_kelompok }}</a></div>
                                <span class="lw-chip {{ $statusClass }} lw-chip-mini">{{ $statusLabel }}</span>
                            </div>
                            <div class="lw-team-meta"><i class="bi bi-trophy-fill"></i>{{ $kl->lomba->nama ?? '-' }}</div>
                        </div>
                    </div>

                    @if($hasAnggota)
                    <div class="lw-team-members">
                        @foreach($kl->anggota->take(3) as $ang)
                            @php $mName = $ang->student->user->name ?? $ang->student->nama ?? '?'; @endphp
                            <span class="lw-ava-sm" style="background:{{ lw_ava_color($mName) }};" title="{{ $mName }}">{{ lw_initial($mName) }}</span>
                        @endforeach
                        @if($kl->anggota_count > 3)
                            <span class="lw-members-plus">+{{ $kl->anggota_count - 3 }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="lw-team-stats">
                        <div class="lw-team-stat"><div class="v">{{ $kl->anggota_count }}</div><div class="l">Anggota</div></div>
                        <div class="lw-team-stat"><div class="v" style="font-size:13px;">{{ $kelasLabel }}</div><div class="l">Kelas</div></div>
                        <div class="lw-team-stat"><div class="v" style="font-size:12px;">{{ $kl->kode_kelompok ?? '-' }}</div><div class="l">Kode</div></div>
                    </div>

                    <div class="lw-completion">
                        <span class="dot {{ $completionDot }}"></span>
                        <span class="lbl">
                            @if($isLocked) Kelompok terkunci — Haflah selesai
                            @elseif($hasAnggota) {{ $kl->anggota_count }} anggota terdaftar
                            @else Belum ada anggota
                            @endif
                        </span>
                    </div>

                    <div class="lw-team-actions">
                        <a href="{{ route('kelompok-lomba.show', $kl->id) }}" class="lw-act-btn" title="Detail"><i class="bi bi-eye"></i> Detail</a>
                        <a href="{{ route('kelompok-lomba.edit', $kl->id) }}" class="lw-act-btn edit {{ $isLocked ? 'is-off' : '' }}" {{ $isLocked ? 'tabindex=-1' : '' }} title="{{ $isLocked ? 'Haflah selesai' : 'Edit' }}"><i class="bi bi-pencil-square"></i> Edit</a>
                        <button type="button" class="lw-act-btn del {{ ($isLocked||$hasAnggota) ? 'is-off' : '' }}" {{ ($isLocked||$hasAnggota) ? 'disabled' : '' }}
                            data-kl-delete data-kl-id="{{ $kl->id }}" data-kl-nama="{{ e($kl->nama_kelompok) }}" title="{{ $isLocked ? 'Haflah selesai' : ($hasAnggota ? 'Memiliki anggota' : 'Hapus') }}"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="lw-pagi">
            <div class="lw-pagi-info">Menampilkan {{ $kelompokLombas->firstItem() ?? 0 }}-{{ $kelompokLombas->lastItem() ?? 0 }} dari {{ $total }} entri</div>
            <div>{{ $kelompokLombas->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>

</div>

<a href="{{ route('kelompok-lomba.create') }}" class="lw-fab" aria-label="Tambah kelompok"><i class="bi bi-plus-lg"></i></a>

<form id="klDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var toolbar = document.getElementById('klFilter');
    var searchInput = document.getElementById('klQuickSearch');
    var items = Array.from(document.querySelectorAll('[data-kl-item]'));
    var emptyState = document.getElementById('clientEmpty');
    var deleteForm = document.getElementById('klDeleteForm');

    document.querySelectorAll('[data-count]').forEach(function (el) {
        if (typeof LW !== 'undefined' && LW.counter) { LW.counter(el); }
    });

    if (toolbar) {
        toolbar.querySelectorAll('select').forEach(function (el) {
            el.addEventListener('change', function () { toolbar.submit(); });
        });
    }

    if (searchInput) {
        var debounce;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(function () {
                var q = searchInput.value.trim().toLowerCase();
                var visible = 0;
                items.forEach(function (item) {
                    var match = !q || (item.dataset.filter || '').indexOf(q) !== -1;
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
            }, 300);
        });
    }

    (function staggerIn() {
        document.querySelectorAll('.lw-team-card').forEach(function (card, i) {
            card.style.opacity = '0'; card.style.transition = 'opacity .3s ease';
            setTimeout(function () { card.style.opacity = '1'; }, 40 + i * 50);
        });
    })();

    document.querySelectorAll('.lw-act-btn').forEach(function (b) { b.addEventListener('click', function (e) { if (b.classList.contains('is-off')) e.preventDefault(); }); });

    document.querySelectorAll('[data-kl-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.klId, nama = btn.dataset.klNama;
            if (!id) return;
            LW.confirm('Hapus Kelompok?', 'Kelompok "' + nama + '" akan dihapus permanen.', 'bi-trash').then(function (ok) {
                if (ok) { deleteForm.action = '{{ url('kelompok-lomba') }}/' + id; deleteForm.submit(); }
            });
        });
    });
})();
</script>
@endpush
@endsection
