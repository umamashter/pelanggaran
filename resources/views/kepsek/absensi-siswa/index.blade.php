@extends('layouts.main')
@section('title','Absensi Siswa (Read Only)')
@section('content')
@include('component.admin.ms-style')
<style>
    .page-title-content { display: none !important; }
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }
    .stat-card {
        background: var(--ms-card, #fff); border: none; border-radius: 18px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
        padding: 22px 24px; display: flex; align-items: center; gap: 18px;
        transition: all .3s cubic-bezier(.4,0,.2,1); position: relative; overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.07), 0 4px 6px -2px rgba(0,0,0,.04); }
    .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .stat-icon-total { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; }
    .stat-icon-done { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #16a34a; }
    .stat-icon-pending { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #d97706; }
    .stat-info { flex: 1; }
    .stat-number { font-size: 28px; font-weight: 800; color: var(--ms-text, #1e293b); line-height: 1; margin-bottom: 3px; }
    .stat-label { font-size: 12px; color: var(--ms-text-soft, #64748b); font-weight: 500; }
    .kelas-name { font-weight: 700; color: var(--ms-text, #1e293b); font-size: 14px; }
    .siswa-count .num { font-weight: 800; color: var(--ms-text, #1e293b); font-size: 16px; }
    .siswa-count .label { font-size: 11px; color: #94a3b8; font-weight: 500; }
    .status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .status-pill.done { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #15803d; border: 1px solid #bbf7d0; }
    .status-pill.waiting { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; border: 1px solid #fde68a; }
    .status-pill.libur { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
    .table-status-header { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 10px; white-space: nowrap; }
    .table-status-header.ok { color: #16a34a; background: #f0fdf4; }
    .table-status-header.warn { color: #d97706; background: #fffbeb; }
    @media (max-width: 768px) { .stats-row { grid-template-columns: 1fr; gap: 12px; } }
</style>

<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Absensi Siswa</h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge" style="background:rgba(86,179,74,.12);color:rgb(86,179,67);border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-calendar-day me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="badge" style="background:rgba(37,99,235,.1);color:#2563eb;border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-graduation-cap me-1"></i> {{ $tahunAktif->tahun_ajaran }}
                            </span>
                            @if(isset($jenjang))
                            <span class="badge" style="background:rgba(22,163,74,.1);color:#16a34a;border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                                <i class="fas fa-school me-1"></i> {{ $jenjang->kode }} - {{ $jenjang->nama_jenjang }}
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
                <div>
                    <span style="font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-eye"></i> Hanya Melihat</span>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalKelas = $kelasList->count();
        $sudahAbsen = count($absensiHariIni);
        $belumAbsen = $totalKelas - $sudahAbsen;
    @endphp
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon stat-icon-total"><i class="fas fa-layer-group"></i></div>
            <div class="stat-info"><div class="stat-number">{{ $totalKelas }}</div><div class="stat-label">Total Kelas</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-done"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info"><div class="stat-number">{{ $sudahAbsen }}</div><div class="stat-label">Sudah Diabsen</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-pending"><i class="fas fa-clock"></i></div>
            <div class="stat-info"><div class="stat-number">{{ $belumAbsen }}</div><div class="stat-label">Belum Diabsen</div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
        <div class="card-body" style="padding: 16px 20px 20px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div style="font-size:16px;font-weight:700;color:var(--ms-text,#1e293b);display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-table" style="color:var(--ms-primary,#16a34a);font-size:18px;"></i> Daftar Kelas
                </div>
                <div>
                    @if($belumAbsen > 0)
                    <div class="table-status-header warn"><i class="fas fa-exclamation-triangle"></i> {{ $belumAbsen }} kelas belum diabsen</div>
                    @else
                    <div class="table-status-header ok"><i class="fas fa-check-circle"></i> Semua sudah diabsen</div>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelasList as $kelas)
                    @php
                        $siswaCount = $kelas->siswaAktif()->where('tahun_ajaran_id', $tahunAktif->id)->count();
                        $sudahAbsen = in_array($kelas->id, $absensiHariIni);
                    @endphp
                    <tr>
                        <td><span class="kelas-name">{{ $kelas->nama_kelas }}</span></td>
                        <td style="text-align:center;">
                            <span style="display:inline-flex;align-items:center;padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;">{{ $kelas->jenjang->kode ?? '-' }}</span>
                        </td>
                        <td style="text-align:center;"><div class="siswa-count"><span class="num">{{ $siswaCount }}</span> <span class="label">siswa</span></div></td>
                        <td style="text-align:center;">
                            @if($isJumat)
                                <span class="status-pill libur"><i class="fas fa-moon"></i> Libur</span>
                            @elseif($sudahAbsen)
                                <span class="status-pill done"><i class="fas fa-check-circle"></i> Sudah Diabsen</span>
                            @else
                                <span class="status-pill waiting"><i class="fas fa-hourglass-half"></i> Belum</span>
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
        columnDefs: [{ orderable: false, targets: 3 }],
        pageLength: 10,
        order: []
    });
});
</script>
@endpush
