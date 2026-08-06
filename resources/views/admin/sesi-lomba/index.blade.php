@extends('layouts.main')
@section('title', 'Sesi Lomba')
@section('content')
@include('component.admin.lomba-workspace')
<style>
    .page-title-content { display: none !important; }

    .lw-sesi-name { font-size: 13.5px; font-weight: 700; color: var(--lw-text); }
    .lw-sesi-name a { color: inherit; text-decoration: none; }
    .lw-sesi-name a:hover { color: var(--lw-primary); }
    .lw-sesi-ket { font-size: 11px; color: var(--lw-text-3); }

    .lw-count-chip { display: inline-flex; align-items: center; gap: 5px; min-height: 27px; padding: 0 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
    .lw-count-chip.has { background: var(--lw-navy-soft); color: var(--lw-primary); border: 1px solid var(--lw-navy-border); }
    .lw-count-chip.none { background: var(--lw-bg); color: var(--lw-text-3); border: 1px solid var(--lw-border); }

    .lw-table-desktop tbody tr.is-locked { opacity: .6; }
    .lw-table-desktop tbody tr.is-locked:hover { opacity: .75; }
    .lw-mobile-card.locked { opacity: .7; }
</style>

<div class="lw-mod jd-page-sesilomba">

@php
    $pageItems = $sesiLombas->getCollection();
    $activeHaflah = $haflatuls->firstWhere('id', session('haflah_id'));
    $selectedHaflah = request('haflah_id') ? $haflatuls->firstWhere('id', (int) request('haflah_id')) : $activeHaflah;
    $total = $sesiLombas->total();
    $pageTotal = $pageItems->count();
    $currentCount = max(1, $pageTotal);

    $activeUsed = $pageItems->filter(fn($s) => $s->lombas_count > 0 && !$s->is_haflah_selesai)->count();
    $freeCount = $pageItems->filter(fn($s) => $s->lombas_count == 0 && !$s->is_haflah_selesai)->count();
    $lockedCount = $pageItems->filter(fn($s) => $s->is_haflah_selesai)->count();
    $totalHaflah = $haflatuls->count();
    $activeHaflahStatus = optional($activeHaflah)->status ?? '-';
    $today = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
@endphp


<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <span class="lw-hero-icon"><i class="bi bi-clock"></i></span>
            <div>
                <h1 class="lw-hero-title">Sesi Lomba</h1>
                <p class="lw-hero-sub">Kelola jadwal sesi untuk setiap Haflatul Imtihan — status pemakaian langsung terlihat tanpa membuka detail.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-calendar-week"></i>{{ optional($selectedHaflah)->nama_acara ?? optional($activeHaflah)->nama_acara ?? 'Haflah belum dipilih' }}</span>
                    <span class="lw-hero-badge {{ $activeHaflahStatus === 'Selesai' ? 'lw-hero-badge--warn' : 'lw-hero-badge--ok' }}"><i class="bi bi-flag-fill"></i>{{ $activeHaflahStatus }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-calendar-event"></i>{{ $today }}</span>
                </div>
            </div>
        </div>
        <div class="lw-hero-right">
            <a href="{{ route('sesi-lomba.create') }}" class="lw-btn lw-btn--light"><i class="bi bi-plus-lg"></i> Tambah</a>
            <a href="{{ route('sesi-lomba.index') }}" class="lw-btn lw-btn--light" style="border-color:rgba(255,255,255,.15);"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="lw-alert lw-alert--ok"><i class="bi bi-check-circle-fill"></i> <div><b>Berhasil</b> &middot; <span>{{ session('success') }}</span></div></div>
@endif
@if(session('error'))
    <div class="lw-alert lw-alert--err"><i class="bi bi-exclamation-triangle-fill"></i> <div><b>Gagal</b> &middot; <span>{{ session('error') }}</span></div></div>
@endif


<div class="lw-kpi-grid">
    <div class="lw-kpi">
        <span class="lw-kpi-icon navy"><i class="bi bi-layers-fill"></i></span>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $total }}">0</div>
            <div class="lw-kpi-label">Total Sesi Lomba</div>
            <div class="lw-kpi-sub">Seluruh sesi terdaftar</div>
        </div>
        <span class="lw-kpi-watermark"><i class="bi bi-layers"></i></span>
    </div>
    <div class="lw-kpi">
        <span class="lw-kpi-icon green"><i class="bi bi-check-circle-fill"></i></span>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $freeCount }}">0</div>
            <div class="lw-kpi-label">Belum Dipakai</div>
            <div class="lw-kpi-sub">Siap dijadwalkan lomba</div>
        </div>
        <span class="lw-kpi-watermark"><i class="bi bi-check-circle"></i></span>
    </div>
    <div class="lw-kpi">
        <span class="lw-kpi-icon amber"><i class="bi bi-diagram-3-fill"></i></span>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $activeUsed }}">0</div>
            <div class="lw-kpi-label">Dipakai Lomba</div>
            <div class="lw-kpi-sub">Sudah terpakai lomba</div>
        </div>
        <span class="lw-kpi-watermark"><i class="bi bi-diagram-3"></i></span>
    </div>
    <div class="lw-kpi">
        <span class="lw-kpi-icon violet"><i class="bi bi-building-fill"></i></span>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $totalHaflah }}">0</div>
            <div class="lw-kpi-label">Total Haflah</div>
            <div class="lw-kpi-sub">Haflah tersedia</div>
        </div>
        <span class="lw-kpi-watermark"><i class="bi bi-building"></i></span>
    </div>
</div>


<div class="lw-toolbar" id="slToolbar">
    <form id="slFilter" method="GET" style="display:contents;" autocomplete="off">
        <div class="lw-search" style="min-width:200px;">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" class="lw-control" id="slQuickSearch" placeholder="Cari nama, tanggal, haflah...">
        </div>
        <div class="lw-filter">
            <label>Haflah</label>
            <select name="haflah_id" class="lw-select">
                <option value="">Haflah Aktif</option>
                @foreach($haflatuls as $h)
                    <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }} ({{ $h->tahunAjaran->tahun_ajaran ?? '-' }})</option>
                @endforeach
            </select>
        </div>
        <div class="lw-filter" style="min-width:90px;">
            <label>Entri</label>
            <select name="per_page" class="lw-select">
                @foreach([10, 15, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int) $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="lw-toolbar-actions">
            <a href="{{ route('sesi-lomba.index') }}" class="lw-btn lw-btn--ghost"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>


<div class="lw-card lw-table-card">
    <div class="lw-card-header">
        <div>
            <div class="lw-section-title"><i class="bi bi-table"></i> Data Sesi Lomba</div>
            <div class="lw-section-sub" style="margin-bottom:0;">Status dan pemakaian langsung terlihat.</div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <span class="lw-chip lw-chip--green"><i class="bi bi-check-circle-fill"></i> Belum Dipakai</span>
            <span class="lw-chip lw-chip--navy"><i class="bi bi-diagram-3-fill"></i> Dipakai Lomba</span>
            <span class="lw-chip lw-chip--red"><i class="bi bi-archive-fill"></i> Haflah Selesai</span>
        </div>
        <span class="lw-chip lw-chip--navy"><i class="bi bi-funnel-fill"></i> {{ $pageTotal }} dari {{ $total }}</span>
    </div>

    @if($sesiLombas->isEmpty())
        <div class="lw-empty">
            <div class="lw-empty-illus"><div class="ring"></div><div class="ring-2"></div><div class="core"><i class="bi bi-calendar-week"></i></div></div>
            <div class="lw-empty-title">Belum Ada Sesi Lomba</div>
            <div class="lw-empty-sub">Mulai dengan menambahkan sesi lomba pertama untuk Haflatul Imtihan yang sedang aktif.</div>
            <a href="{{ route('sesi-lomba.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Sesi Pertama</a>
        </div>
    @else
        <div class="lw-empty d-none" id="slClientEmpty" style="display:none;padding:32px 16px;"><i class="bi bi-search mb-3" style="font-size:22px;color:var(--lw-text-3);"></i><div class="lw-empty-title">Tidak ada sesi yang cocok</div><div class="lw-empty-sub">Coba ubah kata kunci pencarian atau filter haflah.</div></div>

        <div class="lw-table-desktop">
            <div class="table-responsive">
                <table class="table table-lw align-middle">
                    <thead>
                        <tr>
                            <th>Haflah</th>
                            <th>Nama Sesi</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Lomba</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="slTableBody">
                        @foreach($sesiLombas as $sl)
                            @php
                                $hasLomba = $sl->lombas_count > 0;
                                $isLocked = $sl->is_haflah_selesai;
                                $isEditable = !$isLocked;
                                $isDeletable = !$isLocked && !$hasLomba;

                                if ($isLocked) {
                                    $statusLabel = 'Haflah Selesai'; $statusClass = 'lw-chip--red'; $statusIcon = 'bi-archive-fill';
                                } elseif ($hasLomba) {
                                    $statusLabel = 'Dipakai Lomba'; $statusClass = 'lw-chip--navy'; $statusIcon = 'bi-diagram-3-fill';
                                } else {
                                    $statusLabel = 'Belum Dipakai'; $statusClass = 'lw-chip--green'; $statusIcon = 'bi-check-circle-fill';
                                }

                                $haflahNama = $sl->haflatulImtihan->nama_acara ?? '-';
                                $tanggalLabel = \Carbon\Carbon::parse($sl->tanggal)->isoFormat('D MMM YYYY');
                                $jamLabel = \Carbon\Carbon::parse($sl->jam_mulai)->format('H:i').' - '.\Carbon\Carbon::parse($sl->jam_selesai)->format('H:i');
                                $filterText = strtolower(trim($haflahNama.' '.$sl->nama.' '.$tanggalLabel.' '.$jamLabel.' '.$statusLabel));
                            @endphp
                            <tr class="{{ $isLocked ? 'is-locked' : '' }}" data-sl-item data-filter="{{ $filterText }}">
                                <td><div class="lw-cell-icon"><i class="bi bi-mortarboard-fill"></i> {{ $haflahNama }}</div></td>
                                <td>
                                    <div class="lw-sesi-name">
                                        <a href="{{ route('sesi-lomba.show', $sl->id) }}">{{ $sl->nama }}</a>
                                    </div>
                                    @if($sl->keterangan)<div class="lw-sesi-ket">{{ Str::limit($sl->keterangan, 40) }}</div>@endif
                                </td>
                                <td><span class="lw-chip"><i class="bi bi-calendar-event"></i>{{ $tanggalLabel }}</span></td>
                                <td><span class="lw-chip lw-chip--navy"><i class="bi bi-clock"></i>{{ $jamLabel }}</span></td>
                                <td>
                                    @if($hasLomba)
                                        <span class="lw-count-chip has"><i class="bi bi-diagram-3-fill"></i>{{ $sl->lombas_count }} lomba</span>
                                    @else
                                        <span class="lw-count-chip none"><i class="bi bi-dash-circle"></i>Belum</span>
                                    @endif
                                </td>
                                <td><span class="lw-chip {{ $statusClass }}"><i class="bi {{ $statusIcon }}"></i> {{ $statusLabel }}</span></td>
                                <td class="text-end">
                                    <div class="lw-actions">
                                        <a href="{{ route('sesi-lomba.show', $sl->id) }}" class="lw-btn lw-btn--xs lw-btn--outline" data-bs-toggle="tooltip" title="Detail"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('sesi-lomba.edit', $sl->id) }}" class="lw-btn lw-btn--xs lw-btn--amber-soft {{ $isEditable ? '' : 'lw-btn-lock' }}" {{ $isEditable ? '' : 'tabindex=-1' }} data-bs-toggle="tooltip" title="{{ $isEditable ? 'Edit' : 'Terkunci' }}"><i class="bi bi-pencil"></i></a>
                                        <button type="button" class="lw-btn lw-btn--xs lw-btn--danger-soft {{ $isDeletable ? '' : 'lw-btn-lock' }}" {{ $isDeletable ? '' : 'disabled' }}
                                            data-sl-delete data-sl-id="{{ $sl->id }}" data-sl-nama="{{ e($sl->nama) }}" data-sl-haflah="{{ e($haflahNama) }}"
                                            data-bs-toggle="tooltip" title="{{ $isDeletable ? 'Hapus' : ($hasLomba ? 'Dipakai lomba' : 'Terkunci') }}"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lw-mobile-card-list" id="slMobileStack">
            @foreach($sesiLombas as $sl)
                @php
                    $hasLomba = $sl->lombas_count > 0;
                    $isLocked = $sl->is_haflah_selesai;
                    $statusLabel = $isLocked ? 'Haflah Selesai' : ($hasLomba ? 'Dipakai Lomba' : 'Belum Dipakai');
                    $statusClass = $isLocked ? 'lw-chip--red' : ($hasLomba ? 'lw-chip--navy' : 'lw-chip--green');
                    $statusIcon = $isLocked ? 'bi-archive-fill' : ($hasLomba ? 'bi-diagram-3-fill' : 'bi-check-circle-fill');
                    $haflahNama = $sl->haflatulImtihan->nama_acara ?? '-';
                    $tanggalLabel = \Carbon\Carbon::parse($sl->tanggal)->isoFormat('D MMM YYYY');
                    $jamLabel = \Carbon\Carbon::parse($sl->jam_mulai)->format('H:i').' - '.\Carbon\Carbon::parse($sl->jam_selesai)->format('H:i');
                    $filterText = strtolower(trim($haflahNama.' '.$sl->nama.' '.$tanggalLabel.' '.$jamLabel.' '.$statusLabel));
                @endphp
                <article class="lw-mobile-card {{ $isLocked ? 'locked' : '' }}" data-sl-item data-filter="{{ $filterText }}">
                    <div class="lw-mobile-card-head">
                        <div><h3 style="font-size:14px;font-weight:700;color:var(--lw-text);margin:0;">{{ $sl->nama }}</h3><div class="lw-sesi-ket">{{ $haflahNama }}</div></div>
                        <span class="lw-chip {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="lw-mobile-card-grid">
                        <div class="lw-mobile-card-field"><span class="k">Tanggal</span><span class="v">{{ $tanggalLabel }}</span></div>
                        <div class="lw-mobile-card-field"><span class="k">Jam</span><span class="v">{{ $jamLabel }}</span></div>
                        <div class="lw-mobile-card-field"><span class="k">Lomba</span><span class="v">{{ $hasLomba ? $sl->lombas_count.' lomba' : 'Belum' }}</span></div>
                        <div class="lw-mobile-card-field"><span class="k">Status</span><span class="v">{{ $statusLabel }}</span></div>
                    </div>
                    <div class="lw-mobile-card-actions">
                        <a href="{{ route('sesi-lomba.show', $sl->id) }}" class="lw-btn lw-btn--xs lw-btn--outline" style="flex:1;"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('sesi-lomba.edit', $sl->id) }}" class="lw-btn lw-btn--xs lw-btn--amber-soft {{ $isLocked ? 'lw-btn-lock' : '' }}" style="flex:1;" {{ $isLocked ? 'tabindex=-1' : '' }}><i class="bi bi-pencil"></i></a>
                        <button type="button" class="lw-btn lw-btn--xs lw-btn--danger-soft {{ ($isLocked||$hasLomba) ? 'lw-btn-lock' : '' }}" style="flex:1;" {{ ($isLocked||$hasLomba) ? 'disabled' : '' }}
                            data-sl-delete data-sl-id="{{ $sl->id }}" data-sl-nama="{{ e($sl->nama) }}" data-sl-haflah="{{ e($haflahNama) }}"><i class="bi bi-trash"></i></button>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="lw-pagi">
            <div class="lw-pagi-info">Menampilkan {{ $sesiLombas->firstItem() ?? 0 }}-{{ $sesiLombas->lastItem() ?? 0 }} dari {{ $total }} entri</div>
            <div>{{ $sesiLombas->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>

</div>

<a href="{{ route('sesi-lomba.create') }}" class="lw-fab" aria-label="Tambah sesi lomba"><i class="bi bi-plus-lg"></i></a>

<form id="slDeleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>

@push('scripts')
<script>
(function () {
    var toolbar = document.getElementById('slFilter');
    var searchInput = document.getElementById('slQuickSearch');
    var items = Array.from(document.querySelectorAll('[data-sl-item]'));
    var emptyState = document.getElementById('slClientEmpty');
    var deleteForm = document.getElementById('slDeleteForm');

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
        document.querySelectorAll('.lw-table-desktop tbody tr').forEach(function (row, i) {
            row.style.opacity = '0'; row.style.transition = 'opacity .3s ease';
            setTimeout(function () { row.style.opacity = '1'; }, 40 + i * 50);
        });
    })();

    document.querySelectorAll('[data-sl-delete]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.slId, nama = btn.dataset.slNama, haflah = btn.dataset.slHaflah;
            if (!id) return;
            LW.confirm('Hapus Sesi Lomba?', 'Sesi "' + nama + '" pada "' + haflah + '" akan dihapus permanen.', 'bi-trash').then(function (ok) {
                if (ok) { deleteForm.action = '{{ url('sesi-lomba') }}/' + id; deleteForm.submit(); }
            });
        });
    });
})();
</script>
@endpush
@endsection
