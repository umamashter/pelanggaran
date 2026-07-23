@extends('layouts.main')
@section('title','Rekap Absensi Bulanan')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.riwayat-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.riwayat-header { text-align: center; margin-bottom: 20px; }
.riwayat-header h5 { font-weight: 700; font-size: 16px; color: var(--ms-text); margin-bottom: 2px; }
.riwayat-header p { font-size: 13px; color: var(--ms-text-soft); margin: 0; }
.btn-header-ms { padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 600; transition: all .25s; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px; border: none; text-decoration: none; }
.btn-header-ms:hover { transform: translateY(-2px); color: #fff; }
.btn-header-ms.btn-green { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
.btn-header-ms.btn-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,.25); }
.btn-header-ms.btn-indigo { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.25); }
.btn-header-ms.btn-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid var(--ms-border); }
.btn-header-ms.btn-secondary:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
.filter-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.filter-card .card-body { padding: 16px 20px; }
.filter-card .form-label { font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 4px; }
.filter-card .form-select, .filter-card .form-control { border-radius: 10px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 40px; padding: 0 14px; background-color: #f8fafc; transition: all .2s; color: var(--ms-text); }
.filter-card .form-select:focus, .filter-card .form-control:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
.btn-filter-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(22,163,74,.25); height: 40px; }
.btn-filter-ms:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(22,163,74,.35); color: #fff; }
.btn-reset-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; height: 40px; text-decoration: none; }
.btn-reset-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }

.matrix-wrapper { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); overflow: hidden; }
.matrix-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.matrix-table { border-collapse: collapse; width: 100%; min-width: max-content; font-size: 12px; }
.matrix-table thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: .3px; padding: 8px 6px; border-bottom: 2px solid var(--ms-border); text-align: center; white-space: nowrap; position: sticky; top: 0; z-index: 2; }
.matrix-table thead th.group-header { background: #e2e8f0; font-size: 11px; letter-spacing: .5px; border-bottom: 1px solid var(--ms-border); }
.matrix-table thead th.sticky-col { position: sticky; left: 0; z-index: 3; background: #f8fafc; }
.matrix-table thead th.sticky-col-2 { position: sticky; z-index: 3; background: #f8fafc; }
.matrix-table thead th.sticky-col-3 { position: sticky; z-index: 3; background: #f8fafc; }
.matrix-table tbody td { padding: 6px 4px; border-bottom: 1px solid #f1f5f9; text-align: center; vertical-align: middle; white-space: nowrap; }
.matrix-table tbody td.sticky-col { position: sticky; left: 0; z-index: 1; background: #fff; font-weight: 600; font-size: 12px; color: #334155; text-align: center; border-right: 2px solid var(--ms-border); }
.matrix-table tbody td.sticky-col-2 { position: sticky; z-index: 1; background: #fff; font-size: 12px; color: #334155; text-align: left; border-right: 2px solid var(--ms-border); }
.matrix-table tbody td.sticky-col-3 { position: sticky; z-index: 1; background: #fff; font-size: 12px; color: #334155; text-align: left; min-width: 120px; }
.matrix-table tbody tr:hover td { background: #f8fafc; }
.matrix-table tbody tr:hover td.sticky-col,
.matrix-table tbody tr:hover td.sticky-col-2,
.matrix-table tbody tr:hover td.sticky-col-3 { background: #f0fdf4; }
.matrix-table tbody tr:last-child td { border-bottom: none; }
.matrix-table tfoot td { background: #f8fafc; font-weight: 700; font-size: 11px; padding: 8px 4px; border-top: 2px solid var(--ms-border); text-align: center; }

.tgl-cell { width: 28px; min-width: 28px; max-width: 32px; font-size: 11px; font-weight: 600; }
.status-cell { width: 28px; min-width: 28px; max-width: 32px; }
.status-badge { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 6px; font-size: 10px; font-weight: 700; line-height: 1; }
.status-H { background: #dcfce7; color: #16a34a; }
.status-I { background: #fef3c7; color: #d97706; }
.status-S { background: #fee2e2; color: #dc2626; }
.status-A { background: #e2e8f0; color: #64748b; }
.status-null { background: transparent; color: #cbd5e1; }

.rekap-cell { width: 28px; min-width: 28px; font-size: 11px; font-weight: 700; }
.rekap-A { color: #64748b; }
.rekap-I { color: #d97706; }
.rekap-S { color: #dc2626; }

.pencatat-cell { font-size: 11px; color: #64748b; text-align: left; white-space: normal; max-width: 140px; }

.legend { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 12px; font-size: 12px; color: var(--ms-text-soft); }
.legend-item { display: inline-flex; align-items: center; gap: 4px; }
.legend-dot { width: 18px; height: 18px; border-radius: 5px; display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; }

.empty-state { text-align: center; padding: 40px 20px; color: var(--ms-text-soft); }
.empty-state i { font-size: 48px; margin-bottom: 12px; opacity: .3; }
.empty-state p { font-size: 14px; }

@media (max-width: 768px) {
    .filter-card .card-body { padding: 12px 14px; }
    .matrix-table { font-size: 11px; }
    .matrix-table thead th { font-size: 10px; padding: 6px 3px; }
    .matrix-table tbody td { padding: 4px 2px; font-size: 10px; }
    .status-badge { width: 20px; height: 20px; font-size: 9px; }
}
</style>

<div class="riwayat-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon" style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;box-shadow:0 4px 14px rgba(22,163,74,.3);flex-shrink:0;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color:var(--ms-text);font-size:20px;">Rekap Absensi Bulanan</h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta" style="display:inline-flex;align-items:center;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:500;background:#f0fdf4;color:#16a34a;">
                                <i class="fas fa-graduation-cap me-1"></i>{{ $tahunAktif->tahun_ajaran }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('absensi.index') }}" class="btn-header-ms btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($selectedKelasId && $kelas)
                    <a href="{{ route('absensi.riwayat.pdf', ['kelas_id' => $kelas->id, 'bulan' => $bulan]) }}" class="btn-header-ms btn-indigo" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <button onclick="window.print()" class="btn-header-ms btn-blue">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="fas fa-chalkboard me-1" style="color:var(--ms-primary);"></i>Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $item)
                        <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="fas fa-calendar me-1" style="color:var(--ms-primary);"></i>Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}">
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter-ms"><i class="fas fa-search"></i> Tampilkan</button>
                        <a href="{{ route('absensi.riwayat') }}" class="btn-reset-ms"><i class="fas fa-undo"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedKelasId && $kelas)
    @php
        $tanggalAwal = \Carbon\Carbon::parse($bulan . '-01');
        $hariDalamBulan = $tanggalAwal->daysInMonth;
        $bulanLabel = $tanggalAwal->translatedFormat('F Y');
    @endphp

    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body" style="padding:16px 20px;">
            <div class="riwayat-header">
                <h5>ABSENSI SISWA MI NURUL ULUM</h5>
                <p>KELAS {{ strtoupper($kelas->nama_kelas) }} &middot; BULAN {{ strtoupper($bulanLabel) }} &middot; TAHUN AJARAN {{ $tahunAktif->tahun_ajaran }}</p>
            </div>
        </div>
    </div>

    <div class="matrix-wrapper">
        <div class="matrix-scroll">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th class="sticky-col" rowspan="2" style="width:36px;min-width:36px;">No</th>
                        <th class="sticky-col-2" rowspan="2" style="width:70px;min-width:70px;position:sticky;left:36px;">NIS</th>
                        <th class="sticky-col-3" rowspan="2" style="width:130px;min-width:130px;position:sticky;left:106px;">Nama Siswa</th>
                        <th class="group-header" colspan="{{ $hariDalamBulan }}">Tanggal</th>
                        <th class="group-header" colspan="3">Tidak Masuk</th>
                        <th rowspan="2" style="min-width:110px;">Dicatat Oleh</th>
                    </tr>
                    <tr>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        <th class="tgl-cell">{{ $d }}</th>
                        @endfor
                        <th class="rekap-cell">A</th>
                        <th class="rekap-cell">I</th>
                        <th class="rekap-cell">S</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                    @php
                        $data = $matrixData[$siswa->id] ?? [];
                        $rekap = $data['_rekap'] ?? ['A' => 0, 'I' => 0, 'S' => 0];
                        $pencatat = $data['_pencatat'] ?? [];
                    @endphp
                    <tr>
                        <td class="sticky-col">{{ $loop->iteration }}</td>
                        <td class="sticky-col-2" style="position:sticky;left:36px;">{{ $siswa->nisn }}</td>
                        <td class="sticky-col-3" style="position:sticky;left:106px;">{{ $siswa->nama }}</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        @php
                            $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                            $status = $data[$tgl] ?? null;
                        @endphp
                        <td class="status-cell">
                            @if($status)
                                <span class="status-badge status-{{ $status }}">{{ $status }}</span>
                            @else
                                <span class="status-badge status-null">-</span>
                            @endif
                        </td>
                        @endfor
                        <td class="rekap-cell rekap-A">{{ $rekap['A'] }}</td>
                        <td class="rekap-cell rekap-I">{{ $rekap['I'] }}</td>
                        <td class="rekap-cell rekap-S">{{ $rekap['S'] }}</td>
                        <td class="pencatat-cell">{{ $pencatat ? implode(', ', $pencatat) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $hariDalamBulan + 3 + 1 }}" class="empty-state">
                            <div class="empty-state">
                                <i class="fas fa-user-slash d-block"></i>
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
                        <td class="sticky-col" colspan="3" style="text-align:right;font-weight:700;">Total</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        <td></td>
                        @endfor
                        <td class="rekap-cell rekap-A">{{ $totalA }}</td>
                        <td class="rekap-cell rekap-I">{{ $totalI }}</td>
                        <td class="rekap-cell rekap-S">{{ $totalS }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="legend">
        <span class="legend-item"><span class="legend-dot status-H">H</span> Hadir</span>
        <span class="legend-item"><span class="legend-dot status-I">I</span> Izin</span>
        <span class="legend-item"><span class="legend-dot status-S">S</span> Sakit</span>
        <span class="legend-item"><span class="legend-dot status-A">A</span> Alpha</span>
        <span class="legend-item"><span class="legend-dot" style="background:#f1f5f9;color:#94a3b8;">-</span> Tidak ada data</span>
    </div>

    @else
    <div class="card border-0 shadow-sm" style="border-radius:18px;">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-calendar-times d-block"></i>
                <p>Pilih kelas dan bulan untuk menampilkan rekap absensi bulanan.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var wrapper = document.querySelector('.matrix-scroll');
    if (wrapper) {
        var stickyCols = wrapper.querySelectorAll('.sticky-col, .sticky-col-2, .sticky-col-3');
        wrapper.addEventListener('scroll', function() {
            stickyCols.forEach(function(col) { col.style.transform = 'translateX(0)'; });
        });
    }
});
</script>
@endpush

<style>
@media print {
    .page-title-content, .l-sidebar, .header-icon, .filter-card, .btn-header-ms, .legend { display: none !important; }
    .riwayat-page { margin-top: 0 !important; }
    .matrix-wrapper { box-shadow: none !important; border-radius: 0 !important; }
    .matrix-scroll { overflow: visible !important; }
    .matrix-table { font-size: 9px !important; min-width: unset !important; }
    .matrix-table thead th { position: static !important; background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .matrix-table tbody td.sticky-col,
    .matrix-table tbody td.sticky-col-2,
    .matrix-table tbody td.sticky-col-3 { position: static !important; background: #fff !important; }
    .status-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    body { margin: 0; padding: 10px; }
}
</style>
