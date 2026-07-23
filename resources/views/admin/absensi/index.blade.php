@extends('layouts.main')
@section('title','Absensi Siswa')
@section('content')
<style>
.page-title-content { display: none !important; }
:root {
    --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7;
    --ms-bg: #f5f7fb; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b;
    --ms-card: #ffffff;
    --ms-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --ms-shadow-md: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
    --ms-shadow-lg: 0 10px 15px -3px rgba(0,0,0,.07), 0 4px 6px -2px rgba(0,0,0,.04);
    --ms-radius: 16px; --ms-radius-sm: 10px;
}

.absensi-main-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }

/* ── Hero Header ── */
.hero-header {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #0d6e30 100%);
    border-radius: var(--ms-radius); padding: 28px 32px; color: #fff;
    position: relative; overflow: hidden; margin-bottom: 24px;
    box-shadow: 0 8px 30px rgba(22,163,74,.3);
}
.hero-header::before {
    content: ''; position: absolute; top: -40px; right: -40px; width: 200px; height: 200px;
    background: rgba(255,255,255,.08); border-radius: 50%;
}
.hero-header::after {
    content: ''; position: absolute; bottom: -60px; left: 30%; width: 300px; height: 300px;
    background: rgba(255,255,255,.04); border-radius: 50%;
}
.hero-top { display: flex; flex-direction: column; flex-xl-row; justify-content: space-between; align-items: flex-start; align-items-xl-center; gap: 20px; position: relative; z-index: 1; }
.hero-left { display: flex; align-items: center; gap: 18px; }
.hero-icon {
    width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.2); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,.2);
    font-size: 24px; flex-shrink: 0;
}
.hero-title { font-size: 22px; font-weight: 700; margin-bottom: 6px; text-shadow: 0 1px 2px rgba(0,0,0,.1); }
.hero-badges { display: flex; flex-wrap: wrap; gap: 8px; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 5px; padding: 4px 14px;
    border-radius: 20px; font-size: 12px; font-weight: 600;
    background: rgba(255,255,255,.18); backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.15); color: #fff;
}
.hero-right { display: flex; flex-wrap: wrap; gap: 10px; }

/* ── Hero Buttons ── */
.btn-hero {
    padding: 10px 22px; border-radius: var(--ms-radius-sm); font-size: 13px; font-weight: 600;
    border: none; transition: all .25s; white-space: nowrap; display: inline-flex;
    align-items: center; gap: 7px; text-decoration: none; cursor: pointer; line-height: 1.4;
}
.btn-hero:hover { transform: translateY(-2px); color: #fff; }
.btn-hero:active { transform: translateY(0); }
.btn-hero-glass {
    background: rgba(255,255,255,.18); backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.25); color: #fff;
}
.btn-hero-glass:hover { background: rgba(255,255,255,.3); box-shadow: 0 4px 12px rgba(0,0,0,.15); color: #fff; }
.btn-hero-white {
    background: #fff; color: #16a34a; box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.btn-hero-white:hover { box-shadow: 0 6px 20px rgba(0,0,0,.18); color: #15803d; }

/* ── Stats Row ── */
.stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
.stat-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow-md); padding: 20px 22px;
    display: flex; align-items: center; gap: 16px; transition: all .25s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--ms-shadow-lg); }
.stat-icon {
    width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center;
    justify-content: center; font-size: 20px; flex-shrink: 0;
}
.stat-icon-total { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; }
.stat-icon-done { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #16a34a; }
.stat-icon-pending { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #d97706; }
.stat-info { flex: 1; }
.stat-number { font-size: 24px; font-weight: 800; color: var(--ms-text); line-height: 1; margin-bottom: 2px; }
.stat-label { font-size: 12px; color: var(--ms-text-soft); font-weight: 500; }

/* ── Table Card ── */
.table-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow-md); overflow: hidden;
}
.table-card-header {
    padding: 20px 24px 16px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
}
.table-card-title { font-size: 15px; font-weight: 700; color: var(--ms-text); display: flex; align-items: center; gap: 8px; }
.table-card-title i { color: var(--ms-primary); }
.table-card-body { padding: 0; }

/* ── DataTable Overrides ── */
.dataTables_wrapper .dataTables_filter {
    float: none; text-align: right; margin-bottom: 0;
}
.dataTables_wrapper .dataTables_filter label {
    position: relative; display: inline-flex; align-items: center; font-size: 0; line-height: 0; color: transparent;
}
.dataTables_wrapper .dataTables_filter label input {
    font-size: 13px; line-height: normal; color: var(--ms-text); height: 38px;
    border: 1.5px solid var(--ms-border); border-radius: 10px;
    padding: 0 14px 0 38px; width: 260px;
    background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") 12px center no-repeat;
    background-size: 15px; transition: all .25s; outline: none;
}
.dataTables_wrapper .dataTables_filter label input:focus {
    border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff;
}
.dataTables_wrapper .dataTables_paginate {
    padding: 12px 20px !important; display: flex; align-items: center; justify-content: flex-end;
    gap: 4px; float: none; text-align: right;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid var(--ms-border); border-radius: 8px; padding: 5px 12px;
    font-size: 13px; font-weight: 500; color: #475569; background: #fff;
    cursor: pointer; transition: all .2s; min-width: 36px; text-align: center;
    display: inline-block; line-height: 1.4;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--ms-primary); border-color: var(--ms-primary); color: #fff;
    box-shadow: 0 2px 6px rgba(22,163,74,.25);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.previous,
.dataTables_wrapper .dataTables_paginate .paginate_button.next { font-size: 14px; padding: 5px 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: .4; cursor: default; pointer-events: none; background: #f8fafc;
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px; color: var(--ms-text-soft); padding: 10px 20px 14px !important; clear: both;
}

/* ── Table ── */
#table_absensi { border-collapse: collapse; width: 100% !important; border: none; margin: 0 !important; }
#table_absensi thead th {
    background: #f8fafc; color: #475569; font-weight: 600; font-size: 11px;
    text-transform: uppercase; letter-spacing: .4px; padding: 12px 16px;
    border-bottom: 2px solid #f1f5f9; white-space: nowrap; text-align: center;
}
#table_absensi tbody td {
    padding: 12px 16px; font-size: 13px; color: #334155;
    border-bottom: 1px solid #f8fafc; vertical-align: middle; line-height: 1.5;
}
#table_absensi tbody tr:last-child td { border-bottom: none; }
#table_absensi tbody tr { transition: background .15s; }
#table_absensi tbody tr:hover td { background: #f8fafc; }
#table_absensi tbody tr:nth-child(even) td { background: #fafbfc; }
#table_absensi tbody tr:nth-child(even):hover td { background: #f1f5f9; }

/* ── Kelas Name Cell ── */
.kelas-name { font-weight: 700; color: var(--ms-text); font-size: 14px; }
.kelas-icon {
    width: 34px; height: 34px; border-radius: 8px; display: inline-flex;
    align-items: center; justify-content: center; font-size: 13px; font-weight: 700;
    margin-right: 10px; flex-shrink: 0; vertical-align: middle;
}
.kelas-icon.absented { background: #f0fdf4; color: #16a34a; }
.kelas-icon.pending { background: #fffbeb; color: #d97706; }

/* ── Status Badge ── */
.status-pill {
    display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px;
    border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;
}
.status-pill.done { background: #f0fdf4; color: #16a34a; }
.status-pill.done i { font-size: 11px; }
.status-pill.waiting { background: #fffbeb; color: #d97706; }
.status-pill.waiting i { font-size: 11px; }

/* ── Action Buttons ── */
.action-group-absensi { display: inline-flex; gap: 6px; flex-wrap: nowrap; justify-content: center; }
.btn-absen-ms {
    padding: 7px 16px; border-radius: 8px; font-size: 12px; font-weight: 600;
    border: none; transition: all .25s; display: inline-flex; align-items: center;
    gap: 5px; text-decoration: none; cursor: pointer; line-height: 1.4; white-space: nowrap;
}
.btn-absen-ms:hover { transform: translateY(-1px); color: #fff; }
.btn-absen-ms.btn-success-ms {
    background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff;
    box-shadow: 0 2px 8px rgba(22,163,74,.25);
}
.btn-absen-ms.btn-success-ms:hover { box-shadow: 0 4px 14px rgba(22,163,74,.4); }
.btn-absen-ms.btn-edit-ms {
    background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff;
    box-shadow: 0 2px 8px rgba(245,158,11,.25);
}
.btn-absen-ms.btn-edit-ms:hover { box-shadow: 0 4px 14px rgba(245,158,11,.4); }
.btn-absen-ms.btn-history-ms {
    background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff;
    box-shadow: 0 2px 8px rgba(99,102,241,.25);
}
.btn-absen-ms.btn-history-ms:hover { box-shadow: 0 4px 14px rgba(99,102,241,.4); }
.btn-absen-ms.btn-rekap-ms {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8); color: #fff;
    box-shadow: 0 2px 8px rgba(14,165,233,.25);
}
.btn-absen-ms.btn-rekap-ms:hover { box-shadow: 0 4px 14px rgba(14,165,233,.4); }

/* ── Mobile Responsive ── */
@media (max-width: 575.98px) {
    .action-group-absensi { gap: 4px !important; }
    .action-group-absensi .btn-absen-ms {
        width: 30px !important; height: 30px !important; font-size: 0 !important;
        padding: 0 !important; justify-content: center; border-radius: 8px;
    }
    .action-group-absensi .btn-absen-ms i { font-size: 13px; }
    .action-group-absensi .btn-absen-ms span { display: none; }
}
@media (max-width: 768px) {
    .hero-header { padding: 20px; }
    .hero-title { font-size: 18px; }
    .stats-row { grid-template-columns: 1fr; gap: 10px; }
    .stat-card { padding: 14px 16px; }
    .stat-number { font-size: 20px; }
    .table-card-header { padding: 14px 16px 10px; }
    .dataTables_wrapper .dataTables_filter label input { width: 100%; }
    .dataTables_wrapper .dataTables_filter { float: none; text-align: left; }
    #table_absensi thead th { font-size: 10px; padding: 10px 10px; }
    #table_absensi tbody td { padding: 10px; font-size: 12px; }
    .kelas-icon { display: none; }
    .dataTables_wrapper .dataTables_paginate { justify-content: center; }
    .dataTables_wrapper .dataTables_info { text-align: center; }
}
@media (max-width: 480px) {
    .action-group-absensi { display: inline-flex !important; gap: 4px !important; flex-wrap: nowrap !important; }
    .action-group-absensi .btn-absen-ms {
        width: 28px !important; height: 28px !important; font-size: 11px !important;
        padding: 0 !important; justify-content: center;
    }
}

/* ── Print ── */
@media print {
    .page-title-content, .l-sidebar, .hero-header, .table-card-header .dataTables_filter { display: none !important; }
    .absensi-main-page { margin-top: 0 !important; }
    .stat-card { box-shadow: none !important; border: 1px solid #ccc; }
    .table-card { box-shadow: none !important; border: 1px solid #ccc; border-radius: 0 !important; }
    body { margin: 0; padding: 10px; }
}
</style>

<div class="absensi-main-page">
    {{-- ── Hero Header ── --}}
    <div class="hero-header" @if($isJumat) style="background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%); box-shadow: 0 8px 30px rgba(100,116,139,.3);" @endif>
        <div class="hero-top">
            <div class="hero-left">
                <div class="hero-icon"><i class="fas {{ $isJumat ? 'fa-mug-hot' : 'fa-clipboard-check' }}"></i></div>
                <div>
                    <div class="hero-title">Absensi Siswa</div>
                    <div class="hero-badges">
                        <span class="hero-badge"><i class="fas fa-calendar-day"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                        <span class="hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                        @if(isset($tahunAktif->semesterAktif))
                        <span class="hero-badge"><i class="fas fa-bookmark"></i> {{ $tahunAktif->semesterAktif->nama ?? '-' }}</span>
                        @endif
                        @if($isJumat)
                        <span class="hero-badge" style="background:rgba(255,255,255,.3);border-color:rgba(255,255,255,.3);"><i class="fas fa-moon"></i> Hari Libur</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="hero-right">
                @if(!$isJumat)
                <a href="{{ route('absensi.rekap') }}" class="btn-hero btn-hero-glass">
                    <i class="fas fa-file-alt"></i> Rekap
                </a>
                <a href="{{ route('absensi.riwayat') }}" class="btn-hero btn-hero-glass">
                    <i class="fas fa-calendar-check"></i> Riwayat Bulanan
                </a>
                <a href="{{ route('absensi.create') }}" class="btn-hero btn-hero-white">
                    <i class="fas fa-plus"></i> Input Absensi
                </a>
                @else
                <a href="{{ route('absensi.riwayat') }}" class="btn-hero btn-hero-glass">
                    <i class="fas fa-calendar-check"></i> Riwayat Bulanan
                </a>
                @endif
            </div>
        </div>
    </div>

    @if($isJumat)
    {{-- ── Jumat Libur Banner ── --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a;margin-bottom:24px;">
        <div class="stat-icon stat-icon-pending" style="width:56px;height:56px;font-size:26px;"><i class="fas fa-mug-hot"></i></div>
        <div class="stat-info">
            <div style="font-size:20px;font-weight:800;color:#92400e;margin-bottom:4px;">Jumat — Hari Libur</div>
            <div style="font-size:13px;color:#b45309;">Hari ini adalah hari libur tetap madrasah. Tidak ada kegiatan belajar mengajar dan tidak ada absensi siswa.</div>
        </div>
    </div>
    @endif

    {{-- ── Stats ── --}}
    @php
        $totalKelas = $kelasList->count();
        $sudahAbsen = count($absensiHariIni);
        $belumAbsen = $totalKelas - $sudahAbsen;
    @endphp
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon stat-icon-total"><i class="fas fa-layer-group"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $totalKelas }}</div>
                <div class="stat-label">Total Kelas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-done"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $sudahAbsen }}</div>
                <div class="stat-label">Sudah Diabsen</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-pending"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $belumAbsen }}</div>
                <div class="stat-label">Belum Diabsen</div>
            </div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="fas fa-list"></i> Daftar Kelas
                <span style="font-size:12px;font-weight:500;color:#94a3b8;">({{ $totalKelas }} kelas)</span>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if($isJumat)
                <span style="font-size:12px;color:#92400e;font-weight:600;display:flex;align-items:center;gap:4px;">
                    <i class="fas fa-mug-hot"></i> Hari Libur — Tidak ada absensi hari ini
                </span>
                @elseif($belumAbsen > 0)
                <span style="font-size:12px;color:#d97706;font-weight:600;display:flex;align-items:center;gap:4px;">
                    <i class="fas fa-exclamation-triangle"></i> {{ $belumAbsen }} kelas belum diabsen hari ini
                </span>
                @else
                <span style="font-size:12px;color:#16a34a;font-weight:600;display:flex;align-items:center;gap:4px;">
                    <i class="fas fa-check-circle"></i> Semua kelas sudah diabsen
                </span>
                @endif
            </div>
        </div>
        <div class="table-card-body">
            <table id="table_absensi" class="table display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Kelas</th>
                        <th style="text-align:center;">Jumlah Siswa</th>
                        <th style="text-align:center;">Status Hari Ini</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelasList as $kelas)
                    @php
                        $siswaCount = $kelas->siswaAktif()->where('tahun_ajaran_id', $tahunAktif->id)->count();
                        $sudahAbsen = in_array($kelas->id, $absensiHariIni);
                        $absensiId = $absensiMap[$kelas->id] ?? null;
                    @endphp
                    <tr>
                        <td class="text-center" style="font-weight:600;color:#94a3b8;font-size:12px;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="display:flex;align-items:center;">
                                <span class="kelas-icon {{ $sudahAbsen ? 'absented' : 'pending' }}">
                                    {{ strtoupper(substr($kelas->nama_kelas, 0, 2)) }}
                                </span>
                                <span class="kelas-name">{{ $kelas->nama_kelas }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span style="font-weight:700;color:var(--ms-text);">{{ $siswaCount }}</span>
                            <span style="font-size:11px;color:#94a3b8;margin-left:2px;">siswa</span>
                        </td>
                        <td class="text-center">
                            @if($isJumat)
                                <span class="status-pill" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-moon"></i> Libur</span>
                            @elseif($sudahAbsen)
                                <span class="status-pill done"><i class="fas fa-check-circle"></i> Sudah Diabsen</span>
                            @else
                                <span class="status-pill waiting"><i class="fas fa-hourglass-half"></i> Belum</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($isJumat)
                                <span style="font-size:12px;color:#94a3b8;font-style:italic;">Tidak tersedia</span>
                            @else
                            <div class="action-group-absensi">
                                @if($sudahAbsen)
                                    <a href="{{ route('absensi.edit', $absensiId) }}" class="btn-absen-ms btn-edit-ms" title="Edit Absensi">
                                        <i class="fas fa-edit"></i> <span>Edit</span>
                                    </a>
                                @else
                                    <a href="{{ route('absensi.create', ['kelas_id' => $kelas->id, 'tanggal' => now()->toDateString()]) }}" class="btn-absen-ms btn-success-ms" title="Input Absensi">
                                        <i class="fas fa-clipboard-list"></i> <span>Absen</span>
                                    </a>
                                @endif
                                <a href="{{ route('absensi.riwayat', ['kelas_id' => $kelas->id]) }}" class="btn-absen-ms btn-history-ms" title="Riwayat">
                                    <i class="fas fa-history"></i> <span>Riwayat</span>
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#table_absensi').DataTable({
        pagingType: 'simple_numbers',
        responsive: false,
        scrollX: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        columnDefs: [{ orderable: false, targets: 4 }],
        pageLength: 10
    });
    $('#table_absensi_filter input').attr('placeholder', 'Cari kelas...');
});
</script>
@endpush
