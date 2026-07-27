@extends('layouts.main')
@section('title','Absensi Siswa')
@section('content')
@include('component.admin.ms-style')
<style>
    .page-title-content { display: none !important; }

    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 36px;
        padding: 0 8px;
        font-size: 10px;
        border-radius: 8px;
        gap: 3px;
    }
    .btn-header-ms.btn-simpan-ms.btn-compact i { font-size: 10px; }

    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }
    .stat-card {
        background: var(--ms-card, #fff); border: none; border-radius: 18px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
        padding: 22px 24px; display: flex; align-items: center; gap: 18px;
        transition: all .3s cubic-bezier(.4,0,.2,1); position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; opacity: 0;
        transition: opacity .3s;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.07), 0 4px 6px -2px rgba(0,0,0,.04); }
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
    .stat-number { font-size: 28px; font-weight: 800; color: var(--ms-text, #1e293b); line-height: 1; margin-bottom: 3px; }
    .stat-label { font-size: 12px; color: var(--ms-text-soft, #64748b); font-weight: 500; letter-spacing: .2px; }

    .kelas-name { font-weight: 700; color: var(--ms-text, #1e293b); font-size: 14px; }

    .siswa-count { display: inline-flex; align-items: baseline; gap: 3px; }
    .siswa-count .num { font-weight: 800; color: var(--ms-text, #1e293b); font-size: 16px; }
    .siswa-count .label { font-size: 11px; color: #94a3b8; font-weight: 500; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px;
        border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;
        transition: all .2s;
    }
    .status-pill:hover { transform: scale(1.05); }
    .status-pill.done { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #15803d; border: 1px solid #bbf7d0; }
    .status-pill.done i { font-size: 11px; }
    .status-pill.waiting { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; border: 1px solid #fde68a; }
    .status-pill.waiting i { font-size: 11px; animation: pulse 2s infinite; }
    .status-pill.libur { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-size: 11px; }
    .status-pill.libur i { font-size: 11px; }

    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

    .table-status-header {
        display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600;
        padding: 6px 14px; border-radius: 10px; white-space: nowrap;
    }
    .table-status-header.ok { color: #16a34a; background: #f0fdf4; }
    .table-status-header.warn { color: #d97706; background: #fffbeb; }

    .absen-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; justify-content: center; }
    .btn-absen-sm {
        padding: 5px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;
        border: none; transition: all .25s cubic-bezier(.4,0,.2,1); display: inline-flex; align-items: center;
        gap: 4px; text-decoration: none; cursor: pointer; line-height: 1.4; white-space: nowrap;
    }
    .btn-absen-sm:hover { transform: translateY(-2px); color: #fff; }
    .btn-absen-sm.btn-success { background: linear-gradient(135deg, #059669, #10b981); color: #fff; box-shadow: 0 2px 8px rgba(5,150,105,.3); }
    .btn-absen-sm.btn-success:hover { box-shadow: 0 4px 14px rgba(5,150,105,.45); }
    .btn-absen-sm.btn-warning { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; box-shadow: 0 2px 8px rgba(217,119,6,.3); }
    .btn-absen-sm.btn-warning:hover { box-shadow: 0 4px 14px rgba(217,119,6,.45); }
    .btn-absen-sm.btn-info { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.3); }
    .btn-absen-sm.btn-info:hover { box-shadow: 0 4px 14px rgba(79,70,229,.45); }

    @media (max-width: 575.98px) {
        .absen-action-group { gap: 3px !important; }
        .absen-action-group .btn-absen-sm {
            width: 28px !important; height: 28px !important; font-size: 0 !important;
            padding: 0 !important; justify-content: center; border-radius: 6px;
        }
        .absen-action-group .btn-absen-sm i { font-size: 12px; }
        .absen-action-group .btn-absen-sm span { display: none; }
    }
    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; gap: 12px; }
        .stat-card { padding: 16px 18px; }
        .stat-number { font-size: 22px; }
        .kelas-icon { display: none; }
    }
</style>

<div class="master-siswa-page">
    {{-- ===== HEADER ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Absensi Siswa
                        </h4>
                        <p class="mb-1" style="font-size:12px;color:#94a3b8;line-height:1.4;">
                            Kelola kehadiran siswa harian per kelas.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge" style="background:rgba(86,179,74,.12);color:rgb(86,179,67);border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-calendar-day me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="badge" style="background:rgba(37,99,235,.1);color:#2563eb;border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-graduation-cap me-1"></i> {{ $tahunAktif->tahun_ajaran }}
                            </span>
                            @if(isset($tahunAktif->semesterAktif))
                            <span class="badge" style="background:rgba(217,119,6,.1);color:#d97706;border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-bookmark me-1"></i> {{ $tahunAktif->semesterAktif->nama ?? '-' }}
                            </span>
                            @endif
                            @if($isJumat)
                            <span class="badge" style="background:rgba(100,116,139,.12);color:#64748b;border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-moon me-1"></i> Hari Libur
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('absensi.rekap') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background: linear-gradient(135deg, #0284c7, #0ea5e9); color: #fff; box-shadow: 0 2px 8px rgba(2,132,199,.25);">
                        <i class="fas fa-file-alt"></i> Rekap
                    </a>
                    <a href="{{ route('absensi.riwayat') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.25);">
                        <i class="fas fa-calendar-check"></i> Riwayat
                    </a>
                    <a href="{{ route('absensi.import') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,.25);">
                        <i class="fas fa-camera"></i> Import Foto
                    </a>
                    <a href="{{ route('absensi.create') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25);">
                        <i class="fas fa-plus me-1"></i> Input Absensi
                    </a>
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

    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
        <div class="card-body" style="padding: 16px 20px 20px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div style="font-size:16px;font-weight:700;color:var(--ms-text,#1e293b);display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-table" style="color:var(--ms-primary,#16a34a);font-size:18px;"></i> Daftar Kelas
                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#f0fdf4;color:#16a34a;">{{ $totalKelas }}</span>
                </div>
                <div>
                    @if($belumAbsen > 0)
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

            <table id="table_absensi" class="table-ms" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th style="text-align:center;">Jenjang</th>
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
                        <td>
                            <span class="kelas-name">{{ $kelas->nama_kelas }}</span>
                        </td>
                        <td style="text-align:center;">
                            <span style="display:inline-flex;align-items:center;padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;">{{ $kelas->jenjang->kode ?? '-' }}</span>
                        </td>
                        <td style="text-align:center;">
                            <div class="siswa-count">
                                <span class="num">{{ $siswaCount }}</span>
                                <span class="label">siswa</span>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            @if($isJumat)
                                <span class="status-pill libur"><i class="fas fa-moon"></i> Libur</span>
                            @elseif($sudahAbsen)
                                <span class="status-pill done"><i class="fas fa-check-circle"></i> Sudah Diabsen</span>
                            @else
                                <span class="status-pill waiting"><i class="fas fa-hourglass-half"></i> Belum</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div class="absen-action-group">
                                @if($sudahAbsen)
                                    <a href="{{ route('absensi.edit', $absensiId) }}" class="btn-absen-sm btn-warning" title="Edit Absensi">
                                        <i class="fas fa-edit"></i> <span>Edit</span>
                                    </a>
                                @elseif($isJumat)
                                    <span class="btn-absen-sm" style="opacity:.5;cursor:not-allowed;background:#f1f5f9;color:#94a3b8;border:1px solid #e2e8f0;" title="Hari Jumat — Libur Madrasah">
                                        <i class="fas fa-moon"></i> <span>Libur</span>
                                    </span>
                                @else
                                    <a href="{{ route('absensi.create', ['kelas_id' => $kelas->id, 'tanggal' => now()->toDateString()]) }}" class="btn-absen-sm btn-success" title="Input Absensi">
                                        <i class="fas fa-clipboard-list"></i> <span>Absen</span>
                                    </a>
                                @endif
                                <a href="{{ route('absensi.riwayat', ['kelas_id' => $kelas->id]) }}" class="btn-absen-sm btn-info" title="Riwayat">
                                    <i class="fas fa-history"></i> <span>Riwayat</span>
                                </a>
                            </div>
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
