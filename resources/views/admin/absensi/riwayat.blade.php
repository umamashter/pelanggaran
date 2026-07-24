@extends('layouts.main')
@section('title','Rekap Absensi Bulanan')
@section('content')
<style>
.page-title-content { display: none !important; }
:root {
    --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7;
    --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b;
    --ms-bg: #f8fafc; --ms-card: #ffffff; --ms-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --ms-shadow-md: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
    --ms-shadow-lg: 0 10px 15px -3px rgba(0,0,0,.07), 0 4px 6px -2px rgba(0,0,0,.04);
    --ms-radius: 18px; --ms-radius-sm: 12px;
}
.riwayat-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; max-width: 100%; }

/* ── Hero Header ── */
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
}
.hero-right { display: flex; flex-wrap: wrap; gap: 10px; }
.btn-hero {
    padding: 11px 24px; border-radius: 12px; font-size: 13px; font-weight: 600;
    border: none; transition: all .3s cubic-bezier(.4,0,.2,1); white-space: nowrap;
    display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
    cursor: pointer; line-height: 1.4;
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

/* ── Filter Card ── */
.filter-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow-md); padding: 24px 28px; margin-bottom: 24px;
}
.filter-label {
    font-weight: 700; font-size: 11px; color: var(--ms-text-soft); margin-bottom: 8px;
    display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: .4px;
}
.filter-label i { color: var(--ms-primary); font-size: 12px; }
.filter-input {
    width: 100%; height: 44px; border-radius: var(--ms-radius-sm); border: 1.5px solid var(--ms-border);
    font-size: 13px; padding: 0 16px; background: var(--ms-bg); color: var(--ms-text);
    transition: all .25s; outline: none;
}
.filter-input:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.12); background: #fff; }
.btn-filter {
    height: 44px; padding: 0 24px; border-radius: var(--ms-radius-sm); font-size: 13px; font-weight: 600;
    border: none; background: linear-gradient(135deg, #059669, #10b981); color: #fff;
    display: inline-flex; align-items: center; gap: 7px; cursor: pointer; transition: all .25s;
    box-shadow: 0 3px 12px rgba(5,150,105,.3);
}
.btn-filter:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(5,150,105,.45); color: #fff; }
.btn-reset {
    height: 44px; padding: 0 20px; border-radius: var(--ms-radius-sm); font-size: 13px; font-weight: 600;
    border: 1.5px solid var(--ms-border); background: var(--ms-card); color: var(--ms-text-soft);
    display: inline-flex; align-items: center; gap: 7px; cursor: pointer; transition: all .25s;
    text-decoration: none;
}
.btn-reset:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: #f0fdf4; transform: translateY(-1px); }

/* ── Report Header ── */
.report-header-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0f9ff 100%);
    border: 1px solid #bbf7d0; border-radius: var(--ms-radius);
    padding: 20px 28px; margin-bottom: 20px; text-align: center;
    box-shadow: 0 2px 8px rgba(22,163,74,.08);
}
.report-header-card h5 {
    font-size: 15px; font-weight: 800; color: #166534; margin-bottom: 4px;
    letter-spacing: .5px; text-transform: uppercase;
}
.report-header-card p {
    font-size: 13px; color: #15803d; margin: 0; font-weight: 500;
}

/* ── Matrix Card ── */
.matrix-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow-md); overflow: hidden;
}
.matrix-card-header {
    padding: 18px 24px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center;
}
.matrix-card-title {
    font-size: 15px; font-weight: 700; color: var(--ms-text); display: flex; align-items: center; gap: 10px;
}
.matrix-card-title i { color: var(--ms-primary); font-size: 18px; }
.matrix-scroll {
    overflow-x: auto; -webkit-overflow-scrolling: touch;
    scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
}
.matrix-scroll::-webkit-scrollbar { height: 6px; }
.matrix-scroll::-webkit-scrollbar-track { background: transparent; }
.matrix-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
.matrix-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.matrix-table { border-collapse: separate; border-spacing: 0; width: 100%; min-width: max-content; }
.matrix-table thead th {
    background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    color: #475569; font-weight: 700; font-size: 10px;
    text-transform: uppercase; letter-spacing: .4px; padding: 12px 6px;
    border-bottom: 2px solid #e2e8f0; text-align: center; white-space: nowrap;
    position: sticky; top: 0; z-index: 2;
}
.matrix-table thead th.group-date {
    background: linear-gradient(180deg, #e2e8f0, #f1f5f9); font-size: 10px;
    color: #475569; letter-spacing: .5px; padding: 8px 4px;
}
.matrix-table thead th.group-rekap {
    background: linear-gradient(180deg, #fef3c7, #fffbeb); font-size: 10px;
    color: #92400e; letter-spacing: .5px; padding: 8px 4px;
}
.matrix-table thead th.sticky-col { position: sticky; left: 0; z-index: 4; background: #f1f5f9; }
.matrix-table thead th.sticky-nis { position: sticky; z-index: 4; background: #f1f5f9; }
.matrix-table thead th.sticky-nama { position: sticky; z-index: 4; background: #f1f5f9; }
.matrix-table thead th.date-h { background: #f8fafc; font-size: 10px; font-weight: 700; color: #64748b; padding: 6px 2px; min-width: 26px; }
.matrix-table thead th.rekap-h { font-size: 10px; font-weight: 700; padding: 6px 4px; min-width: 26px; }
.matrix-table thead th.rekap-h-a { color: #64748b; }
.matrix-table thead th.rekap-h-i { color: #d97706; }
.matrix-table thead th.rekap-h-s { color: #dc2626; }

.matrix-table tbody td {
    padding: 8px 4px; border-bottom: 1px solid #f1f5f9; text-align: center;
    vertical-align: middle; white-space: nowrap; transition: all .15s;
}
.matrix-table tbody td.sticky-col {
    position: sticky; left: 0; z-index: 1; background: var(--ms-card);
    font-weight: 700; font-size: 11px; color: #cbd5e1; text-align: center;
    border-right: 2px solid var(--ms-border); min-width: 36px;
}
.matrix-table tbody td.sticky-nis {
    position: sticky; z-index: 1; background: var(--ms-card);
    font-size: 11px; color: var(--ms-text-soft); text-align: left; padding-left: 10px;
    border-right: 2px solid var(--ms-border); font-weight: 500; font-variant-numeric: tabular-nums;
}
.matrix-table tbody td.sticky-nama {
    position: sticky; z-index: 1; background: var(--ms-card);
    font-size: 12px; color: var(--ms-text); text-align: left; padding-left: 10px;
    font-weight: 700; min-width: 140px;
}
.matrix-table tbody tr { transition: all .15s; }
.matrix-table tbody tr:hover td { background: #f0fdf4 !important; }
.matrix-table tbody tr:nth-child(even) td { background: #fafbfc; }
.matrix-table tbody tr:nth-child(even):hover td { background: #f0fdf4 !important; }
.matrix-table tbody tr:last-child td { border-bottom: none; }

.status-cell { width: 28px; min-width: 28px; max-width: 32px; }
.status-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 8px; font-size: 10px; font-weight: 800;
    line-height: 1; transition: all .2s;
}
.status-badge.clickable { cursor: pointer; }
.status-badge.clickable:hover { transform: scale(1.25); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
.libur-cell {
    display: inline-flex; align-items: center; justify-content: center;
    width: auto; min-width: 56px; height: 26px; border-radius: 8px;
    font-size: 9px; font-weight: 800; letter-spacing: .5px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #94a3b8;
}
.s-H { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; box-shadow: 0 2px 6px rgba(22,163,74,.12); }
.s-I { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; box-shadow: 0 2px 6px rgba(217,119,6,.12); }
.s-S { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; box-shadow: 0 2px 6px rgba(220,38,38,.12); }
.s-A { background: linear-gradient(135deg, #e2e8f0, #cbd5e1); color: #475569; box-shadow: 0 2px 6px rgba(100,116,139,.12); }
.s-null { background: transparent; color: #d1d5db; }

.rekap-cell { width: 28px; min-width: 28px; font-size: 12px; font-weight: 800; font-variant-numeric: tabular-nums; }
.rekap-A { color: #64748b; }
.rekap-I { color: #d97706; }
.rekap-S { color: #dc2626; }

.matrix-table tfoot td {
    background: linear-gradient(180deg, #f8fafc, #f1f5f9); font-weight: 800; font-size: 12px;
    padding: 12px 4px; border-top: 2px solid var(--ms-border); text-align: center;
    color: var(--ms-text);
}

/* ── Legend ── */
.legend-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow); padding: 16px 24px; margin-top: 20px;
    display: flex; flex-wrap: wrap; gap: 16px; align-items: center;
}
.legend-title {
    font-size: 11px; font-weight: 700; color: var(--ms-text-soft);
    text-transform: uppercase; letter-spacing: .5px;
}
.legend-items { display: flex; flex-wrap: wrap; gap: 16px; }
.legend-item { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--ms-text-soft); font-weight: 500; }
.legend-dot {
    width: 24px; height: 24px; border-radius: 7px; display: inline-flex;
    align-items: center; justify-content: center; font-size: 10px; font-weight: 800;
}

/* ── Empty State ── */
.empty-state { text-align: center; padding: 60px 24px; color: var(--ms-text-soft); }
.empty-state .empty-icon {
    width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;
}
.empty-state .empty-icon i { font-size: 32px; color: #cbd5e1; }
.empty-state h6 { font-size: 16px; font-weight: 700; color: var(--ms-text); margin-bottom: 4px; }
.empty-state p { font-size: 13px; color: var(--ms-text-soft); margin: 0; }

/* ── Detail Modal ── */
.detail-modal .modal-content {
    border: none; border-radius: 18px; overflow: hidden;
    box-shadow: 0 25px 60px -12px rgba(0,0,0,.18);
}
.detail-modal .modal-header { border-bottom: none; padding: 22px 28px 14px; }
.detail-modal .modal-title {
    font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px;
}
.detail-modal .modal-body { padding: 0 28px 24px; }
.detail-modal .modal-footer {
    border-top: 1px solid #f1f5f9; padding: 16px 28px;
    display: flex; gap: 8px; justify-content: flex-end;
}
.detail-status-badge {
    display: inline-flex; align-items: center; gap: 7px; padding: 7px 16px;
    border-radius: 10px; font-size: 13px; font-weight: 700;
}
.detail-status-badge.status-hadir { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; }
.detail-status-badge.status-izin { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; }
.detail-status-badge.status-sakit { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
.detail-status-badge.status-alpha { background: linear-gradient(135deg, #e2e8f0, #cbd5e1); color: #475569; }
.detail-row { display: flex; padding: 11px 0; border-bottom: 1px solid #f1f5f9; }
.detail-row:last-child { border-bottom: none; }
.detail-label {
    flex: 0 0 140px; font-size: 12px; font-weight: 600; color: var(--ms-text-soft);
    display: flex; align-items: center; gap: 7px;
}
.detail-label i { font-size: 11px; color: #94a3b8; width: 14px; text-align: center; }
.detail-value { flex: 1; font-size: 13px; color: var(--ms-text); font-weight: 600; }
.detail-modal .btn-close-modal {
    padding: 9px 22px; border-radius: var(--ms-radius-sm); font-size: 12px; font-weight: 600;
    border: 1.5px solid var(--ms-border); background: #fff; color: var(--ms-text-soft);
    cursor: pointer; transition: all .2s;
}
.detail-modal .btn-close-modal:hover { border-color: var(--ms-text-soft); color: var(--ms-text); }
.detail-modal .btn-edit-modal {
    padding: 9px 22px; border-radius: var(--ms-radius-sm); font-size: 12px; font-weight: 600;
    border: none; background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff;
    cursor: pointer; transition: all .25s; display: inline-flex; align-items: center; gap: 7px;
    text-decoration: none;
}
.detail-modal .btn-edit-modal:hover { box-shadow: 0 6px 20px rgba(37,99,235,.35); color: #fff; transform: translateY(-2px); }

.detail-modal.modal-header-green .modal-header { background: linear-gradient(135deg, #f0fdf4, #dcfce7); }
.detail-modal.modal-header-green .modal-title { color: #166534; }
.detail-modal.modal-header-yellow .modal-header { background: linear-gradient(135deg, #fffbeb, #fef3c7); }
.detail-modal.modal-header-yellow .modal-title { color: #92400e; }
.detail-modal.modal-header-red .modal-header { background: linear-gradient(135deg, #fef2f2, #fee2e2); }
.detail-modal.modal-header-red .modal-title { color: #991b1b; }
.detail-modal.modal-header-gray .modal-header { background: linear-gradient(180deg, #f8fafc, #f1f5f9); }
.detail-modal.modal-header-gray .modal-title { color: #475569; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .hero-inner { padding: 24px 20px; }
    .hero-title { font-size: 20px; }
    .filter-card { padding: 16px 18px; }
    .matrix-table thead th { font-size: 9px; padding: 8px 3px; }
    .matrix-table tbody td { padding: 6px 2px; font-size: 10px; }
    .status-badge { width: 22px; height: 22px; font-size: 9px; }
    .legend-card { padding: 12px 16px; gap: 10px; }
    .matrix-card-header { padding: 14px 16px; }
}
@media print {
    .page-title-content, .l-sidebar, .hero-header, .filter-card, .legend-card, .matrix-card-header { display: none !important; }
    .riwayat-page { margin-top: 0 !important; }
    .report-header-card { border: 1px solid #999; background: #fff !important; }
    .report-header-card h5 { color: #000; }
    .report-header-card p { color: #333; }
    .matrix-card { box-shadow: none !important; border-radius: 0 !important; border: 1px solid #ccc; }
    .matrix-scroll { overflow: visible !important; }
    .matrix-table { font-size: 8px !important; min-width: unset !important; }
    .matrix-table thead th { position: static !important; background: #e5e7eb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .matrix-table tbody td.sticky-col,
    .matrix-table tbody td.sticky-nis,
    .matrix-table tbody td.sticky-nama { position: static !important; background: #fff !important; border-right: 1px solid #ccc !important; }
    .status-badge, .s-H, .s-I, .s-S, .s-A { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    body { margin: 0; padding: 8px; }
}
</style>

<div class="riwayat-page">
    <div class="hero-header">
        <div class="hero-inner">
            <div class="hero-top">
                <div class="hero-left">
                    <div class="hero-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="hero-title">Rekap Absensi Bulanan</div>
                        <div class="hero-badges">
                            <span class="hero-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                            @if($selectedKelasId && $kelas)
                            <span class="hero-badge"><i class="fas fa-chalkboard"></i> {{ $kelas->nama_kelas }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hero-right">
                    <a href="{{ route('absensi.index') }}" class="btn-hero btn-hero-glass">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($selectedKelasId && $kelas)
                    <a href="{{ route('absensi.riwayat.pdf', ['kelas_id' => $kelas->id, 'bulan' => $bulan]) }}" class="btn-hero btn-hero-glass" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="filter-card">
        <form method="GET" id="riwayatFilterForm" class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <div class="filter-label"><i class="fas fa-chalkboard"></i> Kelas</div>
                <select name="kelas_id" class="filter-input" id="filterKelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $item)
                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="filter-label"><i class="fas fa-calendar"></i> Bulan</div>
                <input type="month" name="bulan" class="filter-input" id="filterBulan" value="{{ $bulan }}">
            </div>
        </form>
    </div>

    @if($selectedKelasId && $kelas)
    @php
        $tanggalAwal = \Carbon\Carbon::parse($bulan . '-01');
        $hariDalamBulan = $tanggalAwal->daysInMonth;
        $bulanLabel = $tanggalAwal->translatedFormat('F Y');
    @endphp

    <div class="report-header-card">
        <h5>Absensi Siswa MI Nurul Ulum</h5>
        <p>Kelas {{ strtoupper($kelas->nama_kelas) }} &middot; Bulan {{ strtoupper($bulanLabel) }} &middot; Tahun Ajaran {{ $tahunAktif->tahun_ajaran }}</p>
    </div>

    <div class="matrix-card">
        <div class="matrix-card-header">
            <div class="matrix-card-title">
                <i class="fas fa-table"></i> Matriks Absensi
                <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#f0fdf4;color:#16a34a;">{{ $siswas->count() }} siswa</span>
            </div>
        </div>
        <div class="matrix-scroll">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th class="sticky-col" rowspan="2" style="width:36px;">No</th>
                        <th class="sticky-nis" rowspan="2" style="width:70px;position:sticky;left:36px;">NIS</th>
                        <th class="sticky-nama" rowspan="2" style="width:140px;position:sticky;left:106px;">Nama Siswa</th>
                        <th class="group-date" colspan="{{ $hariDalamBulan }}">Tanggal</th>
                        <th class="group-rekap" colspan="3">Tidak Masuk</th>
                    </tr>
                    <tr>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        <th class="date-h">{{ $d }}</th>
                        @endfor
                        <th class="rekap-h rekap-h-a">A</th>
                        <th class="rekap-h rekap-h-i">I</th>
                        <th class="rekap-h rekap-h-s">S</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                    @php
                        $data = $matrixData[$siswa->id] ?? [];
                        $rekap = $data['_rekap'] ?? ['A' => 0, 'I' => 0, 'S' => 0];
                        $meta = $detailMeta[$siswa->id] ?? [];
                    @endphp
                    <tr>
                        <td class="sticky-col">{{ $loop->iteration }}</td>
                        <td class="sticky-nis" style="position:sticky;left:36px;">{{ $siswa->nisn }}</td>
                        <td class="sticky-nama" style="position:sticky;left:106px;">{{ $siswa->nama }}</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        @php
                            $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                            $isFriday = isset($fridaySet[$tgl]);
                            $status = $isFriday ? null : ($data[$tgl] ?? null);
                            $m = $isFriday ? null : ($meta[$tgl] ?? null);
                        @endphp
                        <td class="status-cell">
                            @if($isFriday)
                                <span class="libur-cell" title="Hari Jumat — Libur Madrasah">LIBUR</span>
                            @elseif($status && $m)
                                <span class="status-badge s-{{ $status }} clickable"
                                    onclick="showDetail(this)"
                                    data-nama="{{ $siswa->nama }}"
                                    data-nisn="{{ $siswa->nisn }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}"
                                    data-status="{{ $status }}"
                                    data-status-text="{{ $status === 'H' ? 'Hadir' : ($status === 'I' ? 'Izin' : ($status === 'S' ? 'Sakit' : 'Alpha')) }}"
                                    data-user="{{ $m['user_name'] }}"
                                    data-waktu="{{ $m['created_at'] }}"
                                    data-keterangan="{{ $m['keterangan'] }}"
                                    data-absensi-id="{{ $m['absensi_id'] }}"
                                    data-student-id="{{ $siswa->id }}"
                                >{{ $status }}</span>
                            @else
                                <span class="status-badge s-null">-</span>
                            @endif
                        </td>
                        @endfor
                        <td class="rekap-cell rekap-A">{{ $rekap['A'] }}</td>
                        <td class="rekap-cell rekap-I">{{ $rekap['I'] }}</td>
                        <td class="rekap-cell rekap-S">{{ $rekap['S'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $hariDalamBulan + 3 }}">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
                                <h6>Tidak Ada Data</h6>
                                <p>Belum ada data siswa di kelas {{ $kelas->nama_kelas }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($siswas->count())
                @php
                    $totalA = 0; $totalI = 0; $totalS = 0;
                    foreach($siswas as $s) {
                        $r = $matrixData[$s->id]['_rekap'] ?? ['A'=>0,'I'=>0,'S'=>0];
                        $totalA += $r['A']; $totalI += $r['I']; $totalS += $r['S'];
                    }
                @endphp
                <tfoot>
                    <tr>
                        <td class="sticky-col" colspan="3" style="text-align:right;padding-right:12px;font-weight:800;color:#475569;">TOTAL</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        <td></td>
                        @endfor
                        <td class="rekap-cell rekap-A">{{ $totalA }}</td>
                        <td class="rekap-cell rekap-I">{{ $totalI }}</td>
                        <td class="rekap-cell rekap-S">{{ $totalS }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="legend-card">
        <span class="legend-title">Keterangan</span>
        <div class="legend-items">
            <span class="legend-item"><span class="legend-dot s-H">H</span> Hadir</span>
            <span class="legend-item"><span class="legend-dot s-I">I</span> Izin</span>
            <span class="legend-item"><span class="legend-dot s-S">S</span> Sakit</span>
            <span class="legend-item"><span class="legend-dot s-A">A</span> Alpha</span>
            <span class="legend-item"><span class="legend-dot libur-cell" style="min-width:24px;font-size:8px;">J</span> Jumat (Libur)</span>
            <span class="legend-item"><span class="legend-dot s-null">-</span> Belum diisi</span>
        </div>
    </div>

    @else
    <div class="matrix-card">
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
            <h6>Pilih Kelas & Bulan</h6>
            <p>Silakan pilih kelas dan bulan untuk menampilkan rekap absensi bulanan.</p>
        </div>
    </div>
    @endif
</div>

<div class="modal fade detail-modal" id="detailAbsensiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list"></i> Detail Absensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-user"></i> Nama Siswa</div>
                    <div class="detail-value" id="modalNama">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-id-card"></i> NIS</div>
                    <div class="detail-value" id="modalNisn">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-calendar"></i> Tanggal</div>
                    <div class="detail-value" id="modalTanggal">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-info-circle"></i> Status</div>
                    <div class="detail-value" id="modalStatus">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-user-check"></i> Dicatat Oleh</div>
                    <div class="detail-value" id="modalUser">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-clock"></i> Waktu Dicatat</div>
                    <div class="detail-value" id="modalWaktu">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-comment-dots"></i> Keterangan</div>
                    <div class="detail-value" id="modalKeterangan">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn-edit-modal" id="modalEditBtn">
                    <i class="fas fa-pen"></i> Edit Absensi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetail(el) {
    var status = el.getAttribute('data-status');
    var modal = $('#detailAbsensiModal');
    var modalHeader = modal.find('.modal-header');
    var headerClass = '';

    modal.find('#modalNama').text(el.getAttribute('data-nama'));
    modal.find('#modalNisn').text(el.getAttribute('data-nisn'));
    modal.find('#modalTanggal').text(el.getAttribute('data-tanggal'));
    modal.find('#modalUser').text(el.getAttribute('data-user'));
    modal.find('#modalWaktu').text(el.getAttribute('data-waktu'));
    modal.find('#modalKeterangan').text(el.getAttribute('data-keterangan') || '-');

    if (status === 'H') {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-hadir"><i class="fas fa-check-circle"></i> Hadir</span>');
        headerClass = 'modal-header-green';
    } else if (status === 'I') {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-izin"><i class="fas fa-clipboard-check"></i> Izin</span>');
        headerClass = 'modal-header-yellow';
    } else if (status === 'S') {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-sakit"><i class="fas fa-heartbeat"></i> Sakit</span>');
        headerClass = 'modal-header-red';
    } else {
        modal.find('#modalStatus').html('<span class="detail-status-badge status-alpha"><i class="fas fa-times-circle"></i> Alpha</span>');
        headerClass = 'modal-header-gray';
    }

    modalHeader.removeClass('modal-header-green modal-header-yellow modal-header-red modal-header-gray');
    modalHeader.addClass(headerClass);

    var absensiId = el.getAttribute('data-absensi-id');
    var studentId = el.getAttribute('data-student-id');
    modal.find('#modalEditBtn').attr('href', '{{ url("absensi") }}/' + absensiId + '/edit?siswa=' + studentId);

    var bsModal = new bootstrap.Modal(document.getElementById('detailAbsensiModal'));
    bsModal.show();
}

$(document).ready(function() {
    var wrapper = document.querySelector('.matrix-scroll');
    if (wrapper) {
        var stickyEls = wrapper.querySelectorAll('.sticky-col, .sticky-nis, .sticky-nama');
        wrapper.addEventListener('scroll', function() {
            stickyEls.forEach(function(el) { el.style.transform = 'translateX(0)'; });
        });
    }

    var form = document.getElementById('riwayatFilterForm');
    var filterKelas = document.getElementById('filterKelas');
    var filterBulan = document.getElementById('filterBulan');
    if (filterKelas) {
        filterKelas.addEventListener('change', function() {
            if (this.value) form.submit();
        });
    }
    if (filterBulan) {
        filterBulan.addEventListener('change', function() {
            if (filterKelas.value) form.submit();
        });
    }
});
</script>
@endpush
