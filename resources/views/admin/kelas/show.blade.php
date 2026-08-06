@extends('layouts.main')

@section('title', 'Detail Kelas')

@section('content')
@include('component.admin.kelas-module')

@php
    $kode = $kelas->jenjang->kode ?? '';
    $kodeClass = strtolower($kode) ?: 'default';
    $namaJenjang = $kelas->jenjang->nama_jenjang ?? ($kode ?: 'Jenjang');
    $namaKelas = 'Kelas ' . $kelas->nama_kelas;
    $tingkat = $kelas->tingkat;
    $count = $kelas->siswaAktif->count();
    $male = $kelas->siswaAktif->filter(fn($a) => ($a->student->jk ?? '') === 'Laki-laki')->count();
    $female = $kelas->siswaAktif->filter(fn($a) => ($a->student->jk ?? '') === 'Perempuan')->count();
    $kapasitas = 40;
    $pct = $kapasitas > 0 ? min(100, (int) round($count / $kapasitas * 100)) : 0;
    $sisa = max(0, $kapasitas - $count);
    $barClass = $count >= 30 ? 'red' : ($count >= 16 ? 'amber' : 'green');
    $waliGuru = $kelas->waliKelas->guru ?? null;
    $waliNama = $waliGuru->nama ?? 'Belum ditetapkan';
    $waliNip = $waliGuru->nip ?? '';
    $taAktif = $tahunAktifGlobal ?? null;
    $taNama = optional(optional($kelas->waliKelas)->tahunAjaran)->nama ?? optional($taAktif)->nama ?? 'Belum diatur';
    $semesterNama = optional(optional($taAktif)->semesterAktif)->nama ?? '-';
    $avatarClasses = ['blue', 'green', 'amber', 'violet', 'red', 'info'];
    $avatarCount = count($avatarClasses);
@endphp

<div class="kls-page" id="klsShowPage">

    {{-- ===================== BREADCRUMB ===================== --}}
    <nav class="kls-crumb" aria-label="breadcrumb">
        <a href="{{ route('kelas.index') }}">Kelas</a>
        <i class="bi bi-chevron-right"></i>
        <span>Detail Kelas</span>
    </nav>

    {{-- ===================== HERO ===================== --}}
    <header class="kls-hero">
        <div class="kls-hero-main">
            <div class="kls-eyebrow"><i class="bi bi-mortarboard-fill"></i> Detail Kelas</div>
            <h1 class="kls-hero-title">{{ $namaKelas }}</h1>
            <p class="kls-hero-desc">
                Kelas tingkat {{ $tingkat }} pada jenjang {{ $namaJenjang }}.
                Kelola wali kelas, jadwal pelajaran, dan daftar siswa aktif di kelas ini.
            </p>
            <div class="kls-hero-chips">
                <span class="kls-chip kls-chip--blue"><i class="bi bi-layers-fill"></i> {{ $kode ?: '-' }}</span>
                <span class="kls-chip kls-chip--violet"><i class="bi bi-person-badge-fill"></i> {{ $waliNama }}</span>
                <span class="kls-chip kls-chip--green"><i class="bi bi-calendar3"></i> {{ $taNama }}</span>
                <span class="kls-chip"><i class="bi bi-sun"></i> Semester {{ $semesterNama }}</span>
            </div>
            <div class="kls-hero-stats">
                <div class="kls-hero-stat">
                    <div class="k">Siswa Aktif</div>
                    <div class="v"><span data-counter="{{ $count }}">0</span></div>
                    <div class="s">Total terdaftar di kelas ini</div>
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
                <h4>Kapasitas Kelas</h4>
                <p>Jumlah siswa aktif terhadap kapasitas maksimal {{ $kapasitas }} per kelas.</p>
                <div class="kls-mini-grid">
                    <div class="kls-mini-stat">
                        <div class="k">Terisi</div>
                        <div class="v">{{ $count }}</div>
                    </div>
                    <div class="kls-mini-stat">
                        <div class="k">Sisa Kursi</div>
                        <div class="v">{{ $sisa }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="kls-capacity-top mb-1">
                        <span>Kapasitas</span>
                        <b>{{ $pct }}%</b>
                    </div>
                    <div class="kls-progress {{ $barClass }}">
                        <span data-width="{{ $pct }}"></span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('kelas.index') }}" class="kls-btn kls-btn--secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('kelas.index') }}" class="kls-btn kls-btn--soft">
                    <i class="bi bi-pencil-square"></i> Edit Kelas
                </a>
                <a href="{{ route('jadwal-pelajaran.cetak', $kelas->id) }}" class="kls-btn kls-btn--primary" target="_blank">
                    <i class="bi bi-printer"></i> Cetak
                </a>
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
            <div class="kls-kpi-label">Siswa Aktif</div>
            <div class="kls-kpi-sub">{{ $namaKelas }}</div>
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
                <span class="kls-kpi-ico amber"><i class="bi bi-speedometer2"></i></span>
                <span class="kls-kpi-tag">Terpakai</span>
            </div>
            <div class="kls-kpi-num">{{ $pct }}<small style="font-size:14px;font-weight:700;color:var(--kls-text-3)">%</small></div>
            <div class="kls-kpi-label">Kapasitas</div>
            <div class="kls-kpi-bar"><span data-width="{{ $pct }}"></span></div>
        </div>
        <div class="kls-kpi">
            <div class="kls-kpi-top">
                <span class="kls-kpi-ico green"><i class="bi bi-door-open-fill"></i></span>
                <span class="kls-kpi-tag">Tersedia</span>
            </div>
            <div class="kls-kpi-num"><span data-counter="{{ $sisa }}">0</span></div>
            <div class="kls-kpi-label">Sisa Kursi</div>
            <div class="kls-kpi-sub">dari {{ $kapasitas }} kursi</div>
        </div>
    </section>

    {{-- ===================== MAIN LAYOUT ===================== --}}
    <div class="row g-3 g-lg-4">

        {{-- ---------- KIRI : DAFTAR SISWA ---------- --}}
        <div class="col-12 col-lg-8">
            <section class="kls-card kls-panel" id="klsSiswa">
                <div class="kls-panel-head">
                    <div class="kls-panel-title">
                        <h3><i class="bi bi-people-fill text-primary"></i> Daftar Siswa Aktif</h3>
                        <p>Siswa yang sedang aktif mengikuti pembelajaran di {{ $namaKelas }}.</p>
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
                                        <th width="110">Jenis Kelamin</th>
                                        <th width="90">Status</th>
                                        <th width="110" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kelas->siswaAktif as $anggota)
                                        @php
                                            $st = $anggota->student;
                                            $inits = '';
                                            $words = preg_split('/\s+/', trim((string) $st->nama));
                                            foreach (array_slice($words, 0, 2) as $w) { if ($w !== '') $inits .= mb_strtoupper(mb_substr($w, 0, 1)); }
                                            $inits = $inits ?: '?';
                                            $avClass = $avatarClasses[($loop->index) % $avatarCount];
                                        @endphp
                                        <tr>
                                            <td class="num">{{ $loop->iteration }}</td>
                                            <td><span class="student-nisn">{{ $st->nisn }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="kls-avatar {{ $avClass }}">{{ $inits }}</span>
                                                    <div>
                                                        <div style="font-weight:700;color:var(--kls-text);font-size:13.5px;">{{ $st->nama }}</div>
                                                        <div style="font-size:11px;color:var(--kls-text-3);">Poin {{ $st->poin ?? 0 }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="kls-chip {{ ($st->jk ?? '') === 'Laki-laki' ? 'kls-chip--blue' : 'kls-chip--violet' }}">
                                                    <i class="bi {{ ($st->jk ?? '') === 'Laki-laki' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                                                    {{ $st->jk ?? '-' }}
                                                </span>
                                            </td>
                                            <td><span class="kls-chip kls-chip--green"><i class="bi bi-check-circle"></i> Aktif</span></td>
                                            <td class="text-center">
                                                <a href="{{ route('master-siswa.detail', $st->id) }}"
                                                   class="kls-icon-btn kls-icon-btn--blue"
                                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Detail siswa"
                                                   aria-label="Detail {{ $st->nama }}"><i class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="kls-empty">
                            <div class="kls-empty-illus"><i class="bi bi-person-x"></i></div>
                            <h4>Belum ada siswa di kelas ini</h4>
                            <p>Kelas belum memiliki siswa aktif. Pindahkan siswa melalui Master Siswa agar kelas ini dapat digunakan untuk absensi, jadwal, dan penilaian.</p>
                            <div class="mt-4">
                                <a href="{{ route('kelas.index') }}" class="kls-btn kls-btn--primary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        </div>

        {{-- ---------- KANAN : QUICK ACTION + INFO ---------- --}}
        <div class="col-12 col-lg-4">

            <section class="kls-card kls-panel">
                <div class="kls-panel-head">
                    <div class="kls-panel-title">
                        <h3><i class="bi bi-lightning-charge-fill text-warning"></i> Aksi Cepat</h3>
                        <p>Alur kerja untuk kelas ini.</p>
                    </div>
                </div>
                <div class="kls-panel-body">
                    <div class="kls-quick">
                        <a href="{{ route('wali-kelas.index') }}">
                            <i class="bi bi-person-badge"></i>
                            <span>Atur Wali Kelas
                                <small>Tetapkan / ganti guru wali</small>
                            </span>
                        </a>
                        <a href="{{ route('jadwal-pelajaran.per-kelas', $kelas->id) }}">
                            <i class="bi bi-calendar3"></i>
                            <span>Jadwal Pelajaran
                                <small>Lihat jadwal per hari</small>
                            </span>
                        </a>
                        <a href="{{ route('jadwal-pelajaran.cetak', $kelas->id) }}" target="_blank">
                            <i class="bi bi-printer"></i>
                            <span>Cetak Jadwal Kelas
                                <small>Dokumen PDF jadwal</small>
                            </span>
                        </a>
                        <a href="{{ url('/master-siswa') }}">
                            <i class="bi bi-person-plus"></i>
                            <span>Kelola Siswa
                                <small>Master data siswa aktif</small>
                            </span>
                        </a>
                        <button type="button" onclick="window.print()">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Cetak Halaman Ini
                                <small>Rekap detail kelas</small>
                            </span>
                        </button>
                    </div>
                </div>
            </section>

            <section class="kls-card kls-panel">
                <div class="kls-panel-head">
                    <div class="kls-panel-title">
                        <h3><i class="bi bi-info-circle-fill text-primary"></i> Informasi Kelas</h3>
                        <p>Detail referensi kelas.</p>
                    </div>
                </div>
                <div class="kls-panel-body">
                    <div class="kls-info-list">
                        <div class="kls-info-row">
                            <span class="k">Nama Kelas</span>
                            <span class="v">{{ $namaKelas }}</span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Jenjang</span>
                            <span class="v">{{ $kode ?: '-' }}</span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Nama Jenjang</span>
                            <span class="v">{{ $namaJenjang }}</span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Tingkat</span>
                            <span class="v">{{ $tingkat }}</span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Wali Kelas</span>
                            <span class="v">
                                {{ $waliNama }}
                                @if($waliNip)
                                    <small class="d-block" style="font-size:11px;font-weight:600;color:var(--kls-text-3);">{{ $waliNip }}</small>
                                @endif
                            </span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Tahun Ajaran</span>
                            <span class="v">{{ $taNama }}</span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Semester</span>
                            <span class="v">Semester {{ $semesterNama }}</span>
                        </div>
                        <div class="kls-info-row">
                            <span class="k">Terakhir Update</span>
                            <span class="v">{{ $kelas->updated_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    {{-- Toast stack --}}
    <div class="kls-toasts" id="klsToasts" aria-live="polite" aria-atomic="true"></div>
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
    .kls-panel .kls-panel-head h3 { font-size: 14px; }
    #klsSiswa { scroll-margin-top: 96px; }
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
    .kls-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: .4;
    }
    @media (max-width: 575.98px) {
        .kls-table-wrap .dataTables_wrapper .dataTables_filter input { width: 100%; }
    }
</style>
@endpush

@push('scripts')
@if($count > 0)
<script>
$(document).ready(function() {
    /* ---------- DataTable ---------- */
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

    /* ---------- Animate counters & bars ---------- */
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
    $('[data-width]').each(function() {
        var w = parseFloat($(this).attr('data-width')) || 0;
        var el = $(this);
        setTimeout(function() { el.css('width', w + '%'); }, 120);
    });

    /* ---------- Tooltips ---------- */
    $('[data-bs-toggle="tooltip"]').each(function() {
        if (bootstrap.Tooltip.getInstance(this)) return;
        new bootstrap.Tooltip(this);
    });
});
</script>
@endif
@endpush
