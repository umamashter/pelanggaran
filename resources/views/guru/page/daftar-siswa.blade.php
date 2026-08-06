@extends('layouts.main')

@section('title', 'Daftar Siswa')

@section('content')
@include('component.admin.kelas-module')

@php
    $kelasName = optional($wali_kelas)->kelas?->nama_kelas ?? '';
    $kelasTingkat = optional($wali_kelas)->kelas?->tingkat ?? null;
    $jenjangKode = optional(optional($wali_kelas)->kelas?->jenjang)->kode ?? '';
    $jenjangNama = optional(optional($wali_kelas)->kelas?->jenjang)->nama_jenjang ?? '';
    $taNama = optional($tahunAktifGlobal)->nama ?? optional($tahunAktifGlobal)->tahun_ajaran ?? '';
    $semesterNama = optional(optional($tahunAktifGlobal)->semesterAktif)->nama ?? '-';

    $count = $siswas->count();
    $male = $siswas->filter(fn($s) => strtolower((string) $s->jk) === 'laki-laki')->count();
    $female = $siswas->filter(fn($s) => strtolower((string) $s->jk) === 'perempuan')->count();
    $totalPoin = $siswas->sum('poin');
    $avatarClasses = ['blue', 'green', 'amber', 'violet', 'red', 'info'];
    $avatarCount = count($avatarClasses);

    function guru_poin_class($poin) {
        $poin = (int) $poin;
        if ($poin >= 150) return ['danger', 'bi-shield-fill-exclamation', 'Bahaya'];
        if ($poin >= 56)  return ['amber', 'bi-exclamation-triangle-fill', 'Tinggi'];
        if ($poin > 0)    return ['warning', 'bi-cone-striped', 'Sedang'];
        return ['green', 'bi-shield-check', 'Aman'];
    }
@endphp

<div class="kls-page">

    {{-- ===================== BREADCRUMB ===================== --}}
    <nav class="kls-crumb" aria-label="breadcrumb">
        <a href="#">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span>Daftar Siswa</span>
    </nav>

    @if($wali_kelas)
        {{-- ===================== HERO ===================== --}}
        <header class="kls-hero">
            <div class="kls-hero-main">
                <div class="kls-eyebrow"><i class="bi bi-person-workspace"></i> Student Workspace</div>
                <h1 class="kls-hero-title">Kelas {{ $kelasName }}</h1>
                <p class="kls-hero-desc">
                    Kelola daftar siswa binaan Anda. Pantau poin, akses riwayat pelanggaran, dan
                    lihat detail profil setiap siswa dalam satu tempat.
                </p>
                <div class="kls-hero-chips">
                    @if($jenjangKode)
                        <span class="kls-chip kls-chip--blue"><i class="bi bi-layers-fill"></i> {{ $jenjangKode }}</span>
                    @endif
                    @if($jenjangNama)
                        <span class="kls-chip kls-chip--violet"><i class="bi bi-mortarboard-fill"></i> {{ $jenjangNama }}</span>
                    @endif
                    @if($kelasTingkat)
                        <span class="kls-chip"><i class="bi bi-bar-chart-fill"></i> Tingkat {{ $kelasTingkat }}</span>
                    @endif
                    @if($taNama)
                        <span class="kls-chip kls-chip--green"><i class="bi bi-calendar3"></i> {{ $taNama }}</span>
                    @endif
                    <span class="kls-chip"><i class="bi bi-sun"></i> Semester {{ $semesterNama }}</span>
                </div>
                <div class="kls-hero-stats">
                    <div class="kls-hero-stat">
                        <div class="k">Siswa Binaan</div>
                        <div class="v"><span data-counter="{{ $count }}">0</span></div>
                        <div class="s">Total siswa aktif</div>
                    </div>
                    <div class="kls-hero-stat">
                        <div class="k">Laki-laki</div>
                        <div class="v"><span data-counter="{{ $male }}">0</span></div>
                        <div class="s">Siswa putra</div>
                    </div>
                    <div class="kls-hero-stat">
                        <div class="k">Perempuan</div>
                        <div class="v"><span data-counter="{{ $female }}">0</span></div>
                        <div class="s">Siswa putri</div>
                    </div>
                </div>
            </div>

            <aside class="kls-hero-aside">
                <div class="kls-hero-panel">
                    <h4>Ringkasan Poin</h4>
                    <p>Total poin yang terkumpul dari seluruh siswa binaan pada semester ini.</p>
                    <div class="kls-mini-grid">
                        <div class="kls-mini-stat">
                            <div class="k">Total Poin</div>
                            <div class="v">{{ $totalPoin }}</div>
                        </div>
                        <div class="kls-mini-stat">
                            <div class="k">Rata-rata</div>
                            <div class="v">{{ $count > 0 ? round($totalPoin / $count) : 0 }}</div>
                        </div>
                    </div>
                </div>
            </aside>
        </header>

        {{-- ===================== KPI ===================== --}}
        <section class="kls-kpi-grid">
            <div class="kls-kpi">
                <div class="kls-kpi-top">
                    <span class="kls-kpi-ico blue"><i class="bi bi-people-fill"></i></span>
                    <span class="kls-kpi-tag">Total</span>
                </div>
                <div class="kls-kpi-num"><span data-counter="{{ $count }}">0</span></div>
                <div class="kls-kpi-label">Siswa Binaan</div>
                <div class="kls-kpi-sub">Kelas {{ $kelasName }}</div>
            </div>
            <div class="kls-kpi">
                <div class="kls-kpi-top">
                    <span class="kls-kpi-ico info"><i class="bi bi-gender-male"></i></span>
                    <span class="kls-kpi-tag">Putra</span>
                </div>
                <div class="kls-kpi-num"><span data-counter="{{ $male }}">0</span></div>
                <div class="kls-kpi-label">Laki-laki</div>
                <div class="kls-kpi-sub">Siswa putra</div>
            </div>
            <div class="kls-kpi">
                <div class="kls-kpi-top">
                    <span class="kls-kpi-ico violet"><i class="bi bi-gender-female"></i></span>
                    <span class="kls-kpi-tag">Putri</span>
                </div>
                <div class="kls-kpi-num"><span data-counter="{{ $female }}">0</span></div>
                <div class="kls-kpi-label">Perempuan</div>
                <div class="kls-kpi-sub">Siswa putri</div>
            </div>
            <div class="kls-kpi">
                <div class="kls-kpi-top">
                    <span class="kls-kpi-ico amber"><i class="bi bi-lightning-charge-fill"></i></span>
                    <span class="kls-kpi-tag">Total</span>
                </div>
                <div class="kls-kpi-num"><span data-counter="{{ $totalPoin }}">0</span></div>
                <div class="kls-kpi-label">Total Poin</div>
                <div class="kls-kpi-sub">Seluruh siswa</div>
            </div>
            <div class="kls-kpi">
                <div class="kls-kpi-top">
                    <span class="kls-kpi-ico green"><i class="bi bi-pie-chart-fill"></i></span>
                    <span class="kls-kpi-tag">Rerata</span>
                </div>
                <div class="kls-kpi-num"><span data-counter="{{ $count > 0 ? round($totalPoin / $count) : 0 }}">0</span></div>
                <div class="kls-kpi-label">Poin per Siswa</div>
                <div class="kls-kpi-sub">Nilai tengah</div>
            </div>
        </section>

        {{-- ===================== TABLE SISWA ===================== --}}
        <section class="kls-card kls-panel">
            <div class="kls-panel-head">
                <div class="kls-panel-title">
                    <h3><i class="bi bi-people-fill text-primary"></i> Daftar Siswa Binaan</h3>
                    <p>Siswa aktif di Kelas {{ $kelasName }} — klik poin untuk melihat riwayat.</p>
                </div>
                <span class="kls-chip kls-chip--green"><i class="bi bi-people"></i> {{ $count }} siswa</span>
            </div>
            <div class="kls-panel-body">
                @if($count > 0)
                    <div class="kls-table-wrap">
                        <table id="klsSiswaTable" class="kls-table display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="52">No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th width="90">Jenis Kelamin</th>
                                    <th width="120">Poin</th>
                                    <th width="130" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $siswa)
                                    @php
                                        [$poinClass, $poinIcon, $poinLabel] = guru_poin_class($siswa->poin);
                                        $inits = '';
                                        $words = preg_split('/\s+/', trim((string) $siswa->nama));
                                        foreach (array_slice($words, 0, 2) as $w) { if ($w !== '') $inits .= mb_strtoupper(mb_substr($w, 0, 1)); }
                                        $inits = $inits ?: '?';
                                        $avClass = $avatarClasses[($loop->index) % $avatarCount];
                                    @endphp
                                    <tr>
                                        <td class="num">{{ $loop->iteration }}</td>
                                        <td><span class="student-nisn">{{ $siswa->nisn }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="kls-avatar {{ $avClass }}">{{ $inits }}</span>
                                                <div>
                                                    <div style="font-weight:700;color:var(--kls-text);font-size:13.5px;">{{ $siswa->nama }}</div>
                                                    <div style="font-size:11px;color:var(--kls-text-3);">{{ $kelasName }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="kls-chip {{ strtolower((string) $siswa->jk) === 'laki-laki' ? 'kls-chip--blue' : 'kls-chip--violet' }}">
                                                <i class="bi {{ strtolower((string) $siswa->jk) === 'laki-laki' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                                                {{ $siswa->jk ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/guru/histori/{{ $siswa->id }}" class="kls-chip kls-chip--{{ $poinClass }}" style="text-decoration:none;">
                                                <i class="bi {{ $poinIcon }}"></i> {{ $siswa->poin }} poin
                                            </a>
                                            <div style="font-size:10.5px;color:var(--kls-text-3);margin-top:3px;">{{ $poinLabel }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="kls-actions" style="justify-content:center;">
                                                <button type="button" class="kls-icon-btn kls-icon-btn--blue"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Detail siswa"
                                                        aria-label="Detail {{ $siswa->nama }}"
                                                        data-bs-toggle="modal" data-bs-target="#klsDetail{{ $siswa->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="/guru/histori/{{ $siswa->id }}"
                                                   class="kls-icon-btn kls-icon-btn--amber"
                                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Riwayat poin"
                                                   aria-label="Riwayat poin {{ $siswa->nama }}">
                                                    <i class="bi bi-clock-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="kls-empty">
                        <div class="kls-empty-illus"><i class="bi bi-person-x"></i></div>
                        <h4>Belum ada siswa</h4>
                        <p>Belum ada siswa yang terdaftar aktif di kelas binaan Anda.</p>
                    </div>
                @endif
            </div>
        </section>
    @else
        {{-- ===================== EMPTY: GURU TANPA WALI ===================== --}}
        <div class="kls-card">
            <div class="kls-empty">
                <div class="kls-empty-illus"><i class="bi bi-person-badge"></i></div>
                <h4>Anda belum menjadi wali kelas</h4>
                <p>Akun guru Anda belum ditugaskan sebagai wali kelas. Hubungi admin untuk menetapkan penugasan agar daftar siswa binaan dapat ditampilkan.</p>
            </div>
        </div>
    @endif

    {{-- ===================== MODAL DETAIL ===================== --}}
    @if($wali_kelas)
        @foreach($siswas as $siswa)
        <div class="modal fade kls-modal" id="klsDetail{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                <div class="modal-content">
                    <div class="kls-modal-head">
                        <div class="kls-modal-head-inner">
                            <span class="kls-modal-ico blue"><i class="bi bi-person-badge"></i></span>
                            <div>
                                <h3 class="kls-modal-title">Detail Siswa</h3>
                                <p class="kls-modal-sub">{{ $siswa->nama }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="kls-modal-body">
                        <div class="kls-info-list">
                            <div class="kls-info-row"><span class="k">NISN</span><span class="v">{{ $siswa->nisn }}</span></div>
                            <div class="kls-info-row"><span class="k">Nama</span><span class="v">{{ $siswa->nama }}</span></div>
                            <div class="kls-info-row"><span class="k">TTL</span><span class="v">{{ $siswa->ttl }}</span></div>
                            <div class="kls-info-row"><span class="k">Jenis Kelamin</span><span class="v">{{ $siswa->jk }}</span></div>
                            <div class="kls-info-row"><span class="k">Agama</span><span class="v">{{ $siswa->agama }}</span></div>
                            <div class="kls-info-row"><span class="k">Alamat</span><span class="v">{{ $siswa->alamat }}</span></div>
                            <div class="kls-info-row"><span class="k">No. Telp</span><span class="v">{{ $siswa->no_telp }}</span></div>
                            <div class="kls-info-row"><span class="k">No. Telp Rumah</span><span class="v">{{ $siswa->no_telp_rumah }}</span></div>
                            <div class="kls-info-row">
                                <span class="k">Poin</span>
                                <span class="v">
                                    @php [$pc, $pi, $pl] = guru_poin_class($siswa->poin); @endphp
                                    <span class="kls-chip kls-chip--{{ $pc }}"><i class="bi {{ $pi }}"></i> {{ $siswa->poin }} poin</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="kls-modal-foot">
                        <a href="/guru/histori/{{ $siswa->id }}" class="kls-btn kls-btn--soft">
                            <i class="bi bi-clock-history"></i> Riwayat Poin
                        </a>
                        <button type="button" class="kls-btn kls-btn--secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection

@push('css')
<style>
    .student-nisn {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 12px;
        color: var(--kls-text-2);
        background: var(--kls-surface-2);
        border: 1px solid var(--kls-border);
        padding: 3px 8px;
        border-radius: 8px;
        white-space: nowrap;
    }
    .kls-table-wrap { overflow-x: auto; }
    .kls-table tbody td { white-space: nowrap; }
    .kls-panel { margin-top: 18px; }
    /* DataTables chrome alignment */
    .kls-table-wrap .dataTables_wrapper .dataTables_filter,
    .kls-table-wrap .dataTables_wrapper .dataTables_length {
        margin-bottom: 12px;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_filter input {
        min-height: 36px;
        border: 1px solid var(--kls-border-strong);
        border-radius: 9px;
        padding: 0 12px;
        font-size: 12.5px;
        background: var(--kls-surface);
        color: var(--kls-text);
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--kls-primary);
        box-shadow: var(--kls-ring);
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_length select {
        min-height: 34px;
        border: 1px solid var(--kls-border-strong);
        border-radius: 9px;
        font-size: 12.5px;
        background: var(--kls-surface);
        color: var(--kls-text);
        padding: 0 8px;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_info {
        font-size: 12px;
        color: var(--kls-text-3);
        padding-top: 12px;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid var(--kls-border) !important;
        border-radius: 8px !important;
        background: var(--kls-surface) !important;
        color: var(--kls-text-2) !important;
        margin: 0 2px;
        font-size: 12px;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border-color: var(--kls-primary-border) !important;
        background: var(--kls-primary-soft) !important;
        color: var(--kls-primary-dark) !important;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--kls-primary) !important;
        border-color: var(--kls-primary) !important;
        color: #fff !important;
    }
    .kls-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .4; }
    @media (max-width: 575.98px) {
        .kls-table-wrap .dataTables_wrapper .dataTables_filter input { width: 100%; }
    }
</style>
@endpush

@push('scripts')
@if($wali_kelas && $count > 0)
<script>
$(document).ready(function() {
    var table = $('#klsSiswaTable').DataTable({
        pagingType: 'simple_numbers',
        responsive: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2"lf>rt<"d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 pt-3"ip>',
        language: {
            url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json',
            search: '',
            searchPlaceholder: 'Cari siswa...',
            zeroRecords: 'Siswa tidak ditemukan',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ siswa',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_)',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        columnDefs: [
            { orderable: false, targets: [5] },
            { searchable: false, targets: [0, 5] }
        ],
        order: [[0, 'asc']]
    });
    $('#klsSiswaTable_filter input').attr('placeholder', 'Cari siswa...');

    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    $('[data-counter]').each(function() {
        var target = parseInt($(this).attr('data-counter'), 10) || 0;
        if (prefersReduced || target === 0) { $(this).text(target); return; }
        var el = $(this), cur = 0;
        var timer = setInterval(function() {
            cur += Math.max(1, Math.ceil(target / 24));
            if (cur >= target) { cur = target; clearInterval(timer); }
            el.text(cur);
        }, 28);
    });

    $('[data-bs-toggle="tooltip"]').each(function() {
        if (bootstrap.Tooltip.getInstance(this)) return;
        new bootstrap.Tooltip(this);
    });
});
</script>
@endif
@endpush
