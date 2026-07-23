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

.hero-header {
    background: linear-gradient(135deg, #059669 0%, #16a34a 40%, #22c55e 100%);
    border-radius: 20px; padding: 0; color: #fff; position: relative; overflow: hidden;
    margin-bottom: 28px; box-shadow: 0 20px 60px rgba(5,150,105,.35);
}
.hero-header::before {
    content: ''; position: absolute; top: -80px; right: -60px; width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%); border-radius: 50%;
}
.hero-header::after {
    content: ''; position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%); border-radius: 50%;
}
.hero-inner { padding: 32px 36px; position: relative; z-index: 1; }
.hero-top { display: flex; flex-direction: column; flex-xl-row; justify-content: space-between; align-items: flex-start; align-items-xl-center; gap: 20px; }
.hero-left { display: flex; align-items: center; gap: 20px; }
.hero-icon {
    width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.25);
    font-size: 28px; flex-shrink: 0; box-shadow: 0 8px 32px rgba(0,0,0,.1);
}
.hero-title { font-size: 26px; font-weight: 800; margin-bottom: 8px; letter-spacing: -.3px; }
.hero-badges { display: flex; flex-wrap: wrap; gap: 8px; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px;
    border-radius: 24px; font-size: 12px; font-weight: 600;
    background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.2); color: #fff;
    transition: all .2s;
}
.hero-badge:hover { background: rgba(255,255,255,.25); }
.hero-right { display: flex; flex-wrap: wrap; gap: 10px; }
.btn-hero {
    padding: 11px 24px; border-radius: 12px; font-size: 13px; font-weight: 600;
    border: none; transition: all .3s cubic-bezier(.4,0,.2,1); white-space: nowrap;
    display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
    cursor: pointer; line-height: 1.4; position: relative; overflow: hidden;
}
.btn-hero:hover { transform: translateY(-3px); color: #fff; }
.btn-hero:active { transform: translateY(-1px); }
.btn-hero-glass {
    background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.25); color: #fff;
}
.btn-hero-glass:hover { background: rgba(255,255,255,.28); box-shadow: 0 8px 25px rgba(0,0,0,.15); color: #fff; }
.btn-hero-white {
    background: #fff; color: #059669; box-shadow: 0 4px 15px rgba(0,0,0,.12);
}
.btn-hero-white:hover { box-shadow: 0 8px 30px rgba(0,0,0,.18); color: #047857; }
.btn-hero-white::after {
    content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, transparent 40%, rgba(22,163,74,.08));
}

.stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }
.stat-card {
    background: var(--ms-card); border: none; border-radius: 18px;
    box-shadow: var(--ms-shadow-md); padding: 22px 24px;
    display: flex; align-items: center; gap: 18px; transition: all .3s cubic-bezier(.4,0,.2,1);
    position: relative; overflow: hidden;
}
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; opacity: 0;
    transition: opacity .3s;
}
.stat-card:hover { transform: translateY(-4px); box-shadow: var(--ms-shadow-lg); }
.stat-card:hover::before { opacity: 1; }
.stat-card:nth-child(1)::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.stat-card:nth-child(2)::before { background: linear-gradient(90deg, #16a34a, #4ade80); }
.stat-card:nth-child(3)::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.stat-icon {
    width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center;
    justify-content: center; font-size: 22px; flex-shrink: 0; transition: transform .3s;
}
.stat-card:hover .stat-icon { transform: scale(1.1); }
.stat-icon-total { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; }
.stat-icon-done { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #16a34a; }
.stat-icon-pending { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #d97706; }
.stat-info { flex: 1; }
.stat-number { font-size: 28px; font-weight: 800; color: var(--ms-text); line-height: 1; margin-bottom: 3px; }
.stat-label { font-size: 12px; color: var(--ms-text-soft); font-weight: 500; letter-spacing: .2px; }

.table-card {
    background: var(--ms-card); border: none; border-radius: 18px;
    box-shadow: var(--ms-shadow-md); overflow: hidden;
}
.table-card-header {
    padding: 22px 28px 18px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
}
.table-card-title { font-size: 16px; font-weight: 700; color: var(--ms-text); display: flex; align-items: center; gap: 10px; }
.table-card-title i { color: var(--ms-primary); font-size: 18px; }
.table-card-title .badge-count {
    display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 12px;
    font-size: 11px; font-weight: 600; background: #f0fdf4; color: #16a34a;
}
.table-card-body { padding: 0; }

.dataTables_wrapper .dataTables_filter { float: none; text-align: right; margin-bottom: 0; }
.dataTables_wrapper .dataTables_filter label {
    position: relative; display: inline-flex; align-items: center; font-size: 0; line-height: 0; color: transparent;
}
.dataTables_wrapper .dataTables_filter label input {
    font-size: 13px; line-height: normal; color: var(--ms-text); height: 40px;
    border: 1.5px solid var(--ms-border); border-radius: 12px;
    padding: 0 16px 0 42px; width: 280px;
    background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") 14px center no-repeat;
    background-size: 16px; transition: all .25s; outline: none;
}
.dataTables_wrapper .dataTables_filter label input:focus {
    border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.12); background-color: #fff;
}
.dataTables_wrapper .dataTables_paginate {
    padding: 14px 24px !important; display: flex; align-items: center; justify-content: flex-end;
    gap: 6px; float: none; text-align: right;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1.5px solid var(--ms-border); border-radius: 10px; padding: 6px 14px;
    font-size: 13px; font-weight: 500; color: #475569; background: #fff;
    cursor: pointer; transition: all .2s; min-width: 38px; text-align: center;
    display: inline-block; line-height: 1.4;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--ms-primary); border-color: var(--ms-primary); color: #fff;
    box-shadow: 0 4px 12px rgba(22,163,74,.3);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.previous,
.dataTables_wrapper .dataTables_paginate .paginate_button.next { font-size: 15px; padding: 6px 12px; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: .35; cursor: default; pointer-events: none; background: #f8fafc;
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px; color: var(--ms-text-soft); padding: 12px 24px 16px !important; clear: both;
}

#table_absensi { border-collapse: separate; border-spacing: 0; width: 100% !important; margin: 0 !important; }
#table_absensi thead th {
    background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    color: #475569; font-weight: 700; font-size: 11px;
    text-transform: uppercase; letter-spacing: .5px; padding: 14px 18px;
    border-bottom: 2px solid #e2e8f0; white-space: nowrap; text-align: center;
    position: sticky; top: 0; z-index: 2;
}
#table_absensi tbody td {
    padding: 14px 18px; font-size: 13px; color: #334155;
    border-bottom: 1px solid #f1f5f9; vertical-align: middle; line-height: 1.5;
    transition: all .2s;
}
#table_absensi tbody tr { transition: all .2s; }
#table_absensi tbody tr:hover td { background: #f0fdf4 !important; }
#table_absensi tbody tr:last-child td { border-bottom: none; }

.kelas-name { font-weight: 700; color: var(--ms-text); font-size: 14px; }
.kelas-icon {
    width: 38px; height: 38px; border-radius: 10px; display: inline-flex;
    align-items: center; justify-content: center; font-size: 13px; font-weight: 800;
    margin-right: 12px; flex-shrink: 0; vertical-align: middle;
    transition: transform .2s; letter-spacing: .5px;
}
.kelas-icon.absented { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; box-shadow: 0 2px 8px rgba(22,163,74,.15); }
.kelas-icon.pending { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; box-shadow: 0 2px 8px rgba(245,158,11,.15); }

.siswa-count {
    display: inline-flex; align-items: baseline; gap: 3px;
}
.siswa-count .num { font-weight: 800; color: var(--ms-text); font-size: 16px; }
.siswa-count .label { font-size: 11px; color: #94a3b8; font-weight: 500; }

.status-pill {
    display: inline-flex; align-items: center; gap: 7px; padding: 6px 16px;
    border-radius: 24px; font-size: 12px; font-weight: 600; white-space: nowrap;
    transition: all .2s;
}
.status-pill:hover { transform: scale(1.05); }
.status-pill.done { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #15803d; border: 1px solid #bbf7d0; }
.status-pill.done i { font-size: 12px; }
.status-pill.waiting { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; border: 1px solid #fde68a; }
.status-pill.waiting i { font-size: 12px; animation: pulse 2s infinite; }
.status-pill.libur { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .4; }
}

.action-group-absensi { display: inline-flex; gap: 6px; flex-wrap: nowrap; justify-content: center; }
.btn-absen-ms {
    padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 600;
    border: none; transition: all .25s cubic-bezier(.4,0,.2,1); display: inline-flex; align-items: center;
    gap: 6px; text-decoration: none; cursor: pointer; line-height: 1.4; white-space: nowrap;
    position: relative; overflow: hidden;
}
.btn-absen-ms:hover { transform: translateY(-2px); color: #fff; }
.btn-absen-ms:active { transform: translateY(0); }
.btn-absen-ms.btn-success-ms {
    background: linear-gradient(135deg, #059669, #10b981); color: #fff;
    box-shadow: 0 3px 12px rgba(5,150,105,.3);
}
.btn-absen-ms.btn-success-ms:hover { box-shadow: 0 6px 20px rgba(5,150,105,.45); }
.btn-absen-ms.btn-edit-ms {
    background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff;
    box-shadow: 0 3px 12px rgba(217,119,6,.3);
}
.btn-absen-ms.btn-edit-ms:hover { box-shadow: 0 6px 20px rgba(217,119,6,.45); }
.btn-absen-ms.btn-history-ms {
    background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff;
    box-shadow: 0 3px 12px rgba(79,70,229,.3);
}
.btn-absen-ms.btn-history-ms:hover { box-shadow: 0 6px 20px rgba(79,70,229,.45); }
.btn-absen-ms.btn-rekap-ms {
    background: linear-gradient(135deg, #0284c7, #0ea5e9); color: #fff;
    box-shadow: 0 3px 12px rgba(2,132,199,.3);
}
.btn-absen-ms.btn-rekap-ms:hover { box-shadow: 0 6px 20px rgba(2,132,199,.45); }

.table-status-header {
    display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600;
    padding: 6px 14px; border-radius: 10px; white-space: nowrap;
}
.table-status-header.ok { color: #16a34a; background: #f0fdf4; }
.table-status-header.warn { color: #d97706; background: #fffbeb; }
.table-status-header.off { color: #92400e; background: #fffbeb; }

@media (max-width: 575.98px) {
    .action-group-absensi { gap: 4px !important; }
    .action-group-absensi .btn-absen-ms {
        width: 32px !important; height: 32px !important; font-size: 0 !important;
        padding: 0 !important; justify-content: center; border-radius: 8px;
    }
    .action-group-absensi .btn-absen-ms i { font-size: 13px; }
    .action-group-absensi .btn-absen-ms span { display: none; }
}
@media (max-width: 768px) {
    .hero-inner { padding: 24px 20px; }
    .hero-title { font-size: 20px; }
    .stats-row { grid-template-columns: 1fr; gap: 12px; }
    .stat-card { padding: 16px 18px; }
    .stat-number { font-size: 22px; }
    .table-card-header { padding: 16px 18px 12px; }
    .dataTables_wrapper .dataTables_filter label input { width: 100%; }
    .dataTables_wrapper .dataTables_filter { float: none; text-align: left; }
    #table_absensi thead th { font-size: 10px; padding: 12px 10px; }
    #table_absensi tbody td { padding: 12px 10px; font-size: 12px; }
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
@media print {
    .page-title-content, .l-sidebar, .hero-header, .table-card-header .dataTables_filter { display: none !important; }
    .absensi-main-page { margin-top: 0 !important; }
    .stat-card { box-shadow: none !important; border: 1px solid #ccc; }
    .table-card { box-shadow: none !important; border: 1px solid #ccc; border-radius: 0 !important; }
    body { margin: 0; padding: 10px; }
}
</style>

<div class="absensi-main-page">
    <div class="hero-header" @if($isJumat) style="background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%); box-shadow: 0 20px 60px rgba(100,116,139,.35);" @endif>
        <div class="hero-inner">
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
    </div>

    @if($isJumat)
    <div class="stat-card" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a;margin-bottom:28px;">
        <div class="stat-icon stat-icon-pending" style="width:56px;height:56px;font-size:26px;"><i class="fas fa-mug-hot"></i></div>
        <div class="stat-info">
            <div style="font-size:20px;font-weight:800;color:#92400e;margin-bottom:4px;">Jumat — Hari Libur</div>
            <div style="font-size:13px;color:#b45309;">Hari ini adalah hari libur tetap madrasah. Tidak ada kegiatan belajar mengajar dan tidak ada absensi siswa.</div>
        </div>
    </div>
    @endif

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

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="fas fa-table"></i> Daftar Kelas
                <span class="badge-count">{{ $totalKelas }}</span>
            </div>
            <div class="d-flex gap-3 align-items-center">
                @if($isJumat)
                <div class="table-status-header off">
                    <i class="fas fa-mug-hot"></i> Hari Libur
                </div>
                @elseif($belumAbsen > 0)
                <div class="table-status-header warn">
                    <i class="fas fa-exclamation-triangle"></i> {{ $belumAbsen }} kelas belum diabsen
                </div>
                @else
                <div class="table-status-header ok">
                    <i class="fas fa-check-circle"></i> Semua sudah diabsen
                </div>
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
                        <td class="text-center" style="font-weight:700;color:#cbd5e1;font-size:13px;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="display:flex;align-items:center;">
                                <span class="kelas-icon {{ $sudahAbsen ? 'absented' : 'pending' }}">
                                    {{ strtoupper(substr($kelas->nama_kelas, 0, 2)) }}
                                </span>
                                <span class="kelas-name">{{ $kelas->nama_kelas }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="siswa-count">
                                <span class="num">{{ $siswaCount }}</span>
                                <span class="label">siswa</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($isJumat)
                                <span class="status-pill libur"><i class="fas fa-moon"></i> Libur</span>
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
        searching: false,
        lengthChange: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        columnDefs: [{ orderable: false, targets: 4 }],
        pageLength: 10,
        order: []
    });
});
</script>
@endpush
