@extends('layouts.main')
@section('title','Haflatul Imtihan')
@section('content')

@include('component.admin.lomba-workspace')

<style>
    .page-title-content { display: none !important; }

    .lw-fab { display: none; }
    @media (max-width: 991.98px) { .lw-fab { display: inline-flex; } }

    .lw-hero-progress { margin-top: 14px; max-width: 460px; }
    .lw-hero-progress .head { display: flex; justify-content: space-between; align-items: center; font-size: 10.5px; font-weight: 700;
        color: rgba(255,255,255,.85); letter-spacing: .4px; text-transform: uppercase; margin-bottom: 6px; }
    .lw-hero-progress .lw-progress { background: rgba(255,255,255,.16); border-color: rgba(255,255,255,.22); }

    @media (max-width: 767.98px) {
        .lw-active { flex-direction: column; align-items: flex-start; }
        .lw-active-right { width: 100%; }
        .lw-active-right .lw-btn { flex: 1; }
    }
</style>

<div class="lw-mod jd-page-haflah">

@php
    $hfAll = isset($semuaHaflah) ? $semuaHaflah : collect();
    $hiTotal = $haflatuls->total();
    $hiAktif = $hfAll->where('status', 'Aktif')->count();
    $hiPersiapan = $hfAll->where('status', 'Persiapan')->count();
    $hiSelesai = $hfAll->where('status', 'Selesai')->count();

    $hfLombaDist = $hfAll->map(function ($h) { return $h->lombas->count(); })->values()->toArray();
    $hfPesertaDist = $hfAll->map(function ($h) { return $h->pesertaLombas->count(); })->values()->toArray();
    $hfJuriDist = $hfAll->map(function ($h) { return $h->juriLombas->count(); })->values()->toArray();

    $pctAktif = $hiTotal > 0 ? (int) round(($hiAktif / $hiTotal) * 100) : 0;
    $pctLomba = 0;
    $pctPeserta = 0;
    $pctJuri = 0;
    if (isset($haflahAktif) && $haflahAktif) {
        $pctLomba = $totalLombas > 0 ? (int) round(($haflahAktif->lombas->count() / max(1, $totalLombas)) * 100) : 0;
        $pctPeserta = $totalPesertas > 0 ? (int) round(($haflahAktif->pesertaLombas->count() / max(1, $totalPesertas)) * 100) : 0;
        $pctJuri = $totalJuries > 0 ? (int) round(($haflahAktif->juriLombas->count() / max(1, $totalJuries)) * 100) : 0;
    }

    $hfHero = isset($haflahAktif) ? $haflahAktif : null;
    $hfProg = 0;
    $hfCdTarget = '';
    $hfCdLabel = '';
    $hfCdDone = '';
    if ($hfHero) {
        $hfMulai = $hfHero->tanggal_mulai;
        $hfSelesai = $hfHero->tanggal_selesai;
        if ($hfHero->status === 'Aktif' && $hfSelesai) {
            $hfCdTarget = $hfSelesai->format('Y-m-d\TH:i:s');
            $hfCdLabel = 'Berakhir dalam';
            $hfCdDone = 'Haflah selesai';
            $dur = max(1, $hfMulai->startOfDay()->diffInDays($hfSelesai->startOfDay()));
            $jalan = max(0, now()->startOfDay()->diffInDays($hfMulai->startOfDay()));
            $hfProg = $jalan >= $dur ? 100 : (int) round(($jalan / $dur) * 100);
        } elseif ($hfHero->status === 'Persiapan' && $hfMulai) {
            $hfCdTarget = $hfMulai->format('Y-m-d\TH:i:s');
            $hfCdLabel = 'Dimulai dalam';
            $hfCdDone = 'Segera berlangsung';
        } elseif ($hfHero->status === 'Selesai') {
            $hfProg = 100;
        }
    }

    $hfStatusIdx = -1;
    if ($hfHero) {
        $hfStatusIdx = match ($hfHero->status) {
            'Persiapan' => 0,
            'Aktif' => 1,
            'Selesai' => 2,
            default => -1
        };
    }
    $hfSteps = [
        ['icon' => 'bi-clock', 'name' => 'Persiapan', 'desc' => 'Penyusunan acara, lomba, dan peserta'],
        ['icon' => 'bi-play-circle-fill', 'name' => 'Aktif', 'desc' => 'Pelaksanaan haflatul imtihan berjalan'],
        ['icon' => 'bi-archive-fill', 'name' => 'Selesai', 'desc' => 'Acara berakhir, data diarsipkan'],
    ];
@endphp

{{-- ===== HERO ===== --}}
<div class="lw-hero">
    <div class="lw-hero-grid">
        <div class="lw-hero-left">
            <div class="lw-hero-icon"><i class="bi bi-award-fill"></i></div>
            <div style="flex:1;min-width:0;">
                <h1 class="lw-hero-title">Haflatul Imtihan</h1>
                <p class="lw-hero-sub">Kelola penyelenggaraan haflatul imtihan beserta tahun ajaran, tanggal berlangsung, dan status berjalannya.</p>
                <div class="lw-hero-badges">
                    <span class="lw-hero-badge"><i class="bi bi-calendar-event"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-mortarboard-fill"></i> {{ $tahunAktifGlobal?->tahun_ajaran ?? 'Belum ada TA aktif' }}</span>
                    <span class="lw-hero-badge"><i class="bi bi-flag-fill"></i> {{ $hiTotal }} Haflah</span>
                    @if($hiAktif > 0)
                    <span class="lw-hero-badge lw-hero-badge--ok"><i class="bi bi-play-circle-fill"></i> {{ $hiAktif }} Aktif</span>
                    @endif
                </div>

                @if($hfHero)
                <div class="lw-hero-progress">
                    <div class="head">
                        <span>{{ $hfHero->status === 'Aktif' ? 'Progres pelaksanaan' : 'Perjalanan acara' }}</span>
                        <span>{{ $hfHero->status === 'Selesai' ? '100%' : $hfProg . '%' }}</span>
                    </div>
                    <div class="lw-progress"><div class="lw-progress-fill" style="width:{{ $hfProg }}%"></div></div>
                </div>
                @endif
            </div>
        </div>

        @if($hfHero && $hfCdTarget)
        <div class="lw-countdown" data-target="{{ $hfCdTarget }}" data-label="{{ $hfCdLabel }}" data-done="{{ $hfCdDone }}" role="timer" aria-label="{{ $hfCdLabel }}">
            <span class="lw-cd-label"><i class="bi bi-hourglass-split"></i> <span class="lw-cd-label-txt">{{ $hfCdLabel }}</span></span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-d">00</span><span class="lw-cd-lbl">Hari</span></div>
            <span class="lw-cd-sep">:</span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-h">00</span><span class="lw-cd-lbl">Jam</span></div>
            <span class="lw-cd-sep">:</span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-m">00</span><span class="lw-cd-lbl">Mnt</span></div>
            <span class="lw-cd-sep">:</span>
            <div class="lw-cd-box"><span class="lw-cd-num lw-cd-s">00</span><span class="lw-cd-lbl">Dtk</span></div>
        </div>
        @endif

        <div class="lw-hero-right">
            @if($hfHero)
            <a href="{{ route('haflatul-imtihan.show', $hfHero->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-eye"></i> Detail</a>
            @if($hfHero->status !== 'Selesai')
            <a href="{{ route('haflatul-imtihan.edit', $hfHero->id) }}" class="lw-btn lw-btn--light"><i class="bi bi-pencil"></i> Edit</a>
            @endif
            @endif
            @if(!$sudahAda)
            <a href="{{ route('haflatul-imtihan.create') }}" class="lw-btn lw-btn--accent"><i class="bi bi-plus-lg"></i> Tambah Haflah</a>
            @else
            <span class="lw-btn lw-btn--light" style="opacity:.9;pointer-events:none;" title="Haflatul imtihan tahun ajaran aktif sudah ada"><i class="bi bi-check-circle-fill"></i> Sudah ada</span>
            @endif
        </div>
    </div>
</div>

{{-- ===== ALERTS ===== --}}
@if(session('success'))
<div class="lw-alert lw-alert--ok">
    <i class="bi bi-check-circle-fill"></i>
    <div><b>Berhasil</b> &middot; <span>{{ session('success') }}</span></div>
    <button type="button" class="lw-alert-close" onclick="this.closest('.lw-alert').remove()">&times;</button>
</div>
@endif
@if(session('error'))
<div class="lw-alert lw-alert--err">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div><b>Gagal</b> &middot; <span>{{ session('error') }}</span></div>
    <button type="button" class="lw-alert-close" onclick="this.closest('.lw-alert').remove()">&times;</button>
</div>
@endif

{{-- ===== KPI ===== --}}
<div class="lw-kpi-grid">
    <div class="lw-kpi">
        <div class="lw-kpi-icon violet"><i class="bi bi-award-fill"></i></div>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $hiTotal }}">0</div>
            <div class="lw-kpi-label">Total Haflah</div>
            <div class="lw-kpi-foot">
                {!! lw_dist_segs([$hiAktif, $hiPersiapan, $hiSelesai]) !!}
                <span class="lw-kpi-pct"><i class="bi bi-arrow-up-right"></i> {{ $pctAktif }}% aktif</span>
            </div>
        </div>
        <i class="bi bi-award lw-kpi-watermark"></i>
    </div>
    <div class="lw-kpi">
        <div class="lw-kpi-icon accent"><i class="bi bi-trophy-fill"></i></div>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $totalLombas }}">0</div>
            <div class="lw-kpi-label">Total Lomba</div>
            <div class="lw-kpi-foot">
                {!! lw_dist_segs($hfLombaDist) !!}
                <span class="lw-kpi-pct"><i class="bi bi-trophy"></i> {{ $pctLomba }}% haflah aktif</span>
            </div>
        </div>
        <i class="bi bi-trophy lw-kpi-watermark"></i>
    </div>
    <div class="lw-kpi">
        <div class="lw-kpi-icon rose"><i class="bi bi-people-fill"></i></div>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $totalPesertas }}">0</div>
            <div class="lw-kpi-label">Total Peserta</div>
            <div class="lw-kpi-foot">
                {!! lw_dist_segs($hfPesertaDist) !!}
                <span class="lw-kpi-pct"><i class="bi bi-people"></i> {{ $pctPeserta }}% haflah aktif</span>
            </div>
        </div>
        <i class="bi bi-people lw-kpi-watermark"></i>
    </div>
    <div class="lw-kpi">
        <div class="lw-kpi-icon green"><i class="bi bi-person-badge-fill"></i></div>
        <div class="lw-kpi-main">
            <div class="lw-kpi-num" data-count="{{ $totalJuries }}">0</div>
            <div class="lw-kpi-label">Total Juri</div>
            <div class="lw-kpi-foot">
                {!! lw_dist_segs($hfJuriDist) !!}
                <span class="lw-kpi-pct"><i class="bi bi-person-badge"></i> {{ $pctJuri }}% haflah aktif</span>
            </div>
        </div>
        <i class="bi bi-person-badge lw-kpi-watermark"></i>
    </div>
</div>

{{-- ===== ACTIVE HAFLAH BANNER ===== --}}
@if($hfHero)
<div class="lw-active">
    <div class="lw-active-left">
        <div class="lw-active-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        <div>
            <span class="lbl"><span class="lw-dot"></span> Sesi Haflah Aktif</span>
            <div class="name">{{ $hfHero->nama_acara }}</div>
            <div class="dates">
                <i class="bi bi-mortarboard-fill"></i>{{ $hfHero->tahunAjaran?->tahun_ajaran ?? '-' }}
                &middot; <i class="bi bi-calendar-event"></i>{{ \Helper::tanggal_indonesia($hfHero->tanggal_mulai) }} — {{ \Helper::tanggal_indonesia($hfHero->tanggal_selesai) }}
            </div>
        </div>
    </div>
    <div class="lw-active-right">
        <a href="{{ route('haflatul-imtihan.show', $hfHero->id) }}" class="lw-btn lw-btn--success"><i class="bi bi-eye"></i> Detail</a>
        @if($hfHero->status !== 'Selesai')
        <a href="{{ route('haflatul-imtihan.edit', $hfHero->id) }}" class="lw-btn lw-btn--accent-soft"><i class="bi bi-pencil"></i> Edit</a>
        @endif
    </div>
</div>
@endif

{{-- ===== TIMELINE ===== --}}
@if($hfHero)
<div class="lw-card lw-card-pad" style="margin-bottom:20px;">
    <h5 class="lw-section-title"><i class="bi bi-signpost-2"></i> Alur Penyelenggaraan</h5>
    <p class="lw-section-sub mb-0">Status berjalan otomatis mengikuti tanggal mulai dan selesai.</p>
    <div class="lw-timeline" style="margin-top:16px;">
        @foreach($hfSteps as $hfI => $hfStep)
        <div class="lw-tl-item {{ $hfI < $hfStatusIdx ? 'is-done' : ($hfI == $hfStatusIdx ? 'is-current' : '') }}">
            <div class="lw-tl-dot"><i class="bi {{ $hfStep['icon'] }}"></i></div>
            <div class="lw-tl-card">
                <div class="lw-tl-main">
                    <div class="lw-tl-name">{{ $hfStep['name'] }}</div>
                    <div class="lw-tl-desc">{{ $hfStep['desc'] }}</div>
                </div>
                <div class="lw-tl-tag">
                    @if($hfI < $hfStatusIdx)
                    <span class="lw-chip lw-chip--green"><i class="bi bi-check-lg"></i> Selesai</span>
                    @elseif($hfI == $hfStatusIdx)
                    <span class="lw-chip lw-chip--glow"><span class="lw-dot"></span> Berlangsung</span>
                    @else
                    <span class="lw-chip lw-chip--slate"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== TOOLBAR ===== --}}
<div class="lw-toolbar">
    <span class="lw-chip lw-chip--navy"><i class="bi bi-calendar-week"></i> TA Aktif: {{ $tahunAktifGlobal?->tahun_ajaran ?? '-' }}</span>
    @if($hiPersiapan > 0)
    <span class="lw-chip lw-chip--amber"><i class="bi bi-clock"></i> {{ $hiPersiapan }} Persiapan</span>
    @endif
    @if($hiSelesai > 0)
    <span class="lw-chip lw-chip--slate"><i class="bi bi-archive-fill"></i> {{ $hiSelesai }} Selesai</span>
    @endif
    <div class="lw-search">
        <i class="bi bi-search"></i>
        <input type="search" id="hiSearch" class="lw-control" placeholder="Cari nama acara, tahun ajaran, status..." autocomplete="off">
    </div>
</div>

{{-- ===== TABLE ===== --}}
<div class="lw-card lw-table-card">
    <div class="lw-card-header">
        <div>
            <h5 class="lw-section-title"><i class="bi bi-award-fill"></i> Daftar Haflatul Imtihan</h5>
            <p class="lw-section-sub mb-0">Penyelenggaraan haflatul imtihan setiap tahun ajaran</p>
        </div>
        <div class="lw-tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="lw-tab active" data-status=""><i class="bi bi-grid"></i> Semua <span class="lw-badge-count">{{ $hiTotal }}</span></button>
            <button type="button" class="lw-tab" data-status="Aktif"><i class="bi bi-play-circle-fill"></i> Aktif <span class="lw-badge-count">{{ $hiAktif }}</span></button>
            <button type="button" class="lw-tab" data-status="Persiapan"><i class="bi bi-clock"></i> Persiapan <span class="lw-badge-count">{{ $hiPersiapan }}</span></button>
            <button type="button" class="lw-tab" data-status="Selesai"><i class="bi bi-archive-fill"></i> Selesai <span class="lw-badge-count">{{ $hiSelesai }}</span></button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="table table-lw display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Nama Acara</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="hfTbody">
                @forelse($haflatuls as $haflatul)
                @php
                    $isAktifSesi = session('haflah_id') == $haflatul->id;
                @endphp
                <tr class="{{ $isAktifSesi ? 'lw-row-aktif' : '' }}" data-status="{{ $haflatul->status }}" data-search="{{ strtolower($haflatul->nama_acara . ' ' . ($haflatul->tahunAjaran?->tahun_ajaran ?? '') . ' ' . $haflatul->status) }}">
                    <td class="lw-num">{{ $loop->iteration }}</td>
                    <td>
                        <span class="lw-chip lw-chip--navy"><i class="bi bi-calendar-week"></i> {{ $haflatul->tahunAjaran?->tahun_ajaran ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="lw-haf-name">
                            <b>{{ $haflatul->nama_acara }}</b>
                            @if($isAktifSesi)
                            <span class="lw-chip lw-chip--glow lw-chip-mini"><span class="lw-dot"></span> SEDANG AKTIF</span>
                            @endif
                        </div>
                    </td>
                    <td><span class="lw-cell-icon"><i class="bi bi-calendar-event"></i> {{ \Helper::tanggal_indonesia($haflatul->tanggal_mulai) }}</span></td>
                    <td><span class="lw-cell-icon"><i class="bi bi-calendar-check"></i> {{ \Helper::tanggal_indonesia($haflatul->tanggal_selesai) }}</span></td>
                    <td>
                        <span class="lw-chip {{ lw_status_chip($haflatul->status) }}"><i class="bi {{ lw_status_icon($haflatul->status) }}"></i> {{ $haflatul->status }}</span>
                    </td>
                    <td>
                        @if($haflatul->status == 'Selesai')
                        <div class="lw-actions">
                            <span class="lw-btn lw-btn--xs lw-btn--ghost lw-btn-lock" title="Arsip — haflah sudah selesai"><i class="bi bi-archive-fill"></i></span>
                            <a href="{{ route('haflatul-imtihan.show', $haflatul->id) }}" class="lw-btn lw-btn--xs lw-btn--outline" data-bs-toggle="tooltip" title="Lihat detail"><i class="bi bi-eye"></i></a>
                        </div>
                        @else
                        <div class="lw-actions">
                            @if(!$isAktifSesi)
                            <a href="{{ route('haflah.aktifkan', $haflatul->id) }}" class="lw-btn lw-btn--xs lw-btn--success-soft lw-pulse" data-bs-toggle="tooltip" title="Aktifkan"
                                onclick="event.preventDefault(); LW.confirm('Aktifkan {{ $haflatul->nama_acara }}?', 'Haflah ini akan menjadi sesi aktif yang dipakai seluruh modul terkait. Status aktif lain akan diarsipkan otomatis.', 'bi-play-circle-fill').then(function(ok){ if(ok){ window.location.href='{{ route('haflah.aktifkan', $haflatul->id) }}'; } });">
                                <i class="bi bi-play-fill"></i>
                            </a>
                            @endif
                            <a href="{{ route('haflatul-imtihan.show', $haflatul->id) }}" class="lw-btn lw-btn--xs lw-btn--outline" data-bs-toggle="tooltip" title="Lihat detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('haflatul-imtihan.edit', $haflatul->id) }}" class="lw-btn lw-btn--xs lw-btn--amber-soft" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('haflatul-imtihan.destroy', $haflatul->id) }}" method="POST" class="d-inline"
                                onsubmit="event.preventDefault(); LW.confirmForm(this, 'Hapus {{ $haflatul->nama_acara }}?', 'Data haflatul imtihan akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.', 'bi-trash');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="lw-btn lw-btn--xs lw-btn--danger-soft" data-bs-toggle="tooltip" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="hf-table-empty-row">
                    <td colspan="7">
                        <div class="lw-empty">
                            <div class="lw-empty-illus">
                                <div class="ring"></div>
                                <div class="ring-2"></div>
                                <div class="core"><i class="bi bi-award"></i></div>
                            </div>
                            <div class="lw-empty-title">Belum ada data haflatul imtihan</div>
                            <div class="lw-empty-sub">Belum ada penyelenggaraan haflatul imtihan yang tercatat. Tambahkan haflah pertama untuk tahun ajaran aktif.</div>
                            @if(!$sudahAda)
                            <a href="{{ route('haflatul-imtihan.create') }}" class="lw-btn lw-btn--solid"><i class="bi bi-plus-lg"></i> Tambah Sekarang</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="lw-pagi">
        <span class="lw-pagi-info">Menampilkan {{ $haflatuls->firstItem() ?? 0 }} sampai {{ $haflatuls->lastItem() ?? 0 }} dari {{ $haflatuls->total() }} haflah</span>
        {{ $haflatuls->links() }}
    </div>
</div>

{{-- ===== FAB (mobile) ===== --}}
@if(!$sudahAda)
<a href="{{ route('haflatul-imtihan.create') }}" class="lw-fab" aria-label="Tambah haflatul imtihan"><i class="bi bi-plus-lg"></i></a>
@endif

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var $rows = $('#hfTbody tr[data-status]');
        var $search = $('#hiSearch');
        var $tabs = $('.lw-tab');
        var $emptyRow = null;

        function applyFilter() {
            var q = ($search.val() || '').trim().toLowerCase();
            var st = $tabs.filter('.active').data('status') || '';
            var visible = 0;
            $rows.each(function() {
                var ok = true;
                if (st && $(this).data('status') !== st) { ok = false; }
                if (ok && q) {
                    ok = ($(this).data('search') || '').indexOf(q) !== -1;
                }
                $(this).toggle(ok);
                if (ok) { visible++; }
            });
            if (visible === 0) {
                if (!$emptyRow || !$emptyRow.length || !$emptyRow.is(':visible')) {
                    if ($rows.length > 0) {
                        $emptyRow = $('<tr class="hf-table-empty-row"><td colspan="7"><div class="lw-empty" style="padding:34px 20px;">' +
                            '<div class="lw-empty-title">Tidak ada haflah yang cocok</div>' +
                            '<div class="lw-empty-sub">Coba ubah kata kunci pencarian atau pilih status lain.</div>' +
                            '</div></td></tr>');
                        $('#hfTbody').append($emptyRow);
                    }
                }
                $emptyRow.show();
            } else if ($emptyRow && $emptyRow.length) {
                $emptyRow.hide();
            }
        }

        $search.on('input', applyFilter);
        $tabs.on('click', function() {
            $tabs.removeClass('active');
            $(this).addClass('active');
            applyFilter();
        });
    });
</script>
@endpush
