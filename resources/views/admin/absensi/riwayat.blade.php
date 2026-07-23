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
    --ms-radius: 14px; --ms-radius-sm: 10px;
}

.riwayat-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; max-width: 100%; }

/* ── Header ── */
.header-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow-md); overflow: hidden;
}
.header-card .card-body { padding: 20px 24px; }
.header-top { display: flex; flex-direction: column; flex-xl-row; justify-content: space-between; align-items: flex-start; align-items-xl-center; gap: 16px; }
.header-left { display: flex; align-items: center; gap: 14px; }
.header-icon {
    width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; font-size: 20px;
    box-shadow: 0 4px 12px rgba(22,163,74,.3); flex-shrink: 0;
}
.header-title { font-size: 18px; font-weight: 700; color: var(--ms-text); margin-bottom: 2px; }
.header-subtitle { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px; }
.header-badge {
    display: inline-flex; align-items: center; gap: 5px; padding: 3px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 600; background: #f0fdf4; color: #16a34a;
}
.header-right { display: flex; flex-wrap: wrap; gap: 8px; }

/* ── Buttons ── */
.btn-ms {
    padding: 8px 18px; border-radius: var(--ms-radius-sm); font-size: 12px; font-weight: 600;
    border: none; transition: all .2s ease; white-space: nowrap; display: inline-flex;
    align-items: center; gap: 6px; text-decoration: none; cursor: pointer; line-height: 1.4;
}
.btn-ms:hover { transform: translateY(-1px); }
.btn-ms:active { transform: translateY(0); }
.btn-green { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.28); }
.btn-green:hover { box-shadow: 0 4px 12px rgba(22,163,74,.38); color: #fff; }
.btn-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,.28); }
.btn-blue:hover { box-shadow: 0 4px 12px rgba(37,99,235,.38); color: #fff; }
.btn-indigo { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.28); }
.btn-indigo:hover { box-shadow: 0 4px 12px rgba(99,102,241,.38); color: #fff; }
.btn-outline { background: var(--ms-card); color: var(--ms-text-soft); border: 1.5px solid var(--ms-border); }
.btn-outline:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: #f0fdf4; }

/* ── Filter ── */
.filter-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow); padding: 20px 24px; margin-bottom: 20px;
}
.filter-label {
    font-weight: 600; font-size: 12px; color: var(--ms-text-soft); margin-bottom: 6px;
    display: flex; align-items: center; gap: 5px; text-transform: uppercase; letter-spacing: .3px;
}
.filter-label i { color: var(--ms-primary); font-size: 12px; }
.filter-input {
    width: 100%; height: 40px; border-radius: var(--ms-radius-sm); border: 1.5px solid var(--ms-border);
    font-size: 13px; padding: 0 14px; background: var(--ms-bg); color: var(--ms-text);
    transition: all .2s; outline: none;
}
.filter-input:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background: #fff; }
.btn-filter {
    height: 40px; padding: 0 22px; border-radius: var(--ms-radius-sm); font-size: 13px; font-weight: 600;
    border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff;
    display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all .2s;
    box-shadow: 0 2px 8px rgba(22,163,74,.25);
}
.btn-filter:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,163,74,.35); color: #fff; }
.btn-reset {
    height: 40px; padding: 0 18px; border-radius: var(--ms-radius-sm); font-size: 13px; font-weight: 600;
    border: 1.5px solid var(--ms-border); background: var(--ms-card); color: var(--ms-text-soft);
    display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all .2s;
    text-decoration: none;
}
.btn-reset:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: #f0fdf4; }

/* ── Report Header ── */
.report-header-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0f9ff 100%);
    border: 1px solid #d1fae5; border-radius: var(--ms-radius);
    padding: 18px 24px; margin-bottom: 16px; text-align: center;
}
.report-header-card h5 {
    font-size: 14px; font-weight: 700; color: #166534; margin-bottom: 4px;
    letter-spacing: .5px; text-transform: uppercase;
}
.report-header-card p {
    font-size: 12px; color: #15803d; margin: 0; font-weight: 500;
}

/* ── Matrix Table ── */
.matrix-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius);
    box-shadow: var(--ms-shadow-md); overflow: hidden;
}
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
    background: #f1f5f9; color: var(--ms-text-soft); font-weight: 600; font-size: 10px;
    text-transform: uppercase; letter-spacing: .4px; padding: 10px 6px;
    border-bottom: 2px solid var(--ms-border); text-align: center; white-space: nowrap;
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
.matrix-table thead th.sticky-col {
    position: sticky; left: 0; z-index: 4; background: #f1f5f9;
}
.matrix-table thead th.sticky-nis {
    position: sticky; z-index: 4; background: #f1f5f9;
}
.matrix-table thead th.sticky-nama {
    position: sticky; z-index: 4; background: #f1f5f9;
}
.matrix-table thead th.date-h { background: #f8fafc; font-size: 10px; font-weight: 700; color: #64748b; padding: 6px 2px; min-width: 26px; }
.matrix-table thead th.rekap-h { font-size: 10px; font-weight: 700; padding: 6px 4px; min-width: 26px; }
.matrix-table thead th.rekap-h-a { color: #64748b; }
.matrix-table thead th.rekap-h-i { color: #d97706; }
.matrix-table thead th.rekap-h-s { color: #dc2626; }
.matrix-table thead th.pencatat-h { font-size: 10px; font-weight: 700; color: #475569; min-width: 100px; text-align: left; padding-left: 12px; }

/* ── Body cells ── */
.matrix-table tbody td {
    padding: 7px 4px; border-bottom: 1px solid #f1f5f9; text-align: center;
    vertical-align: middle; white-space: nowrap; transition: background .15s;
}
.matrix-table tbody td.sticky-col {
    position: sticky; left: 0; z-index: 1; background: var(--ms-card);
    font-weight: 700; font-size: 11px; color: var(--ms-text-soft); text-align: center;
    border-right: 2px solid var(--ms-border); min-width: 36px;
}
.matrix-table tbody td.sticky-nis {
    position: sticky; z-index: 1; background: var(--ms-card);
    font-size: 11px; color: var(--ms-text); text-align: left; padding-left: 10px;
    border-right: 2px solid var(--ms-border); font-weight: 500; font-variant-numeric: tabular-nums;
}
.matrix-table tbody td.sticky-nama {
    position: sticky; z-index: 1; background: var(--ms-card);
    font-size: 12px; color: var(--ms-text); text-align: left; padding-left: 10px;
    font-weight: 600; min-width: 130px;
}
.matrix-table tbody tr:hover td { background: #f8fafc; }
.matrix-table tbody tr:hover td.sticky-col { background: #f0fdf4; color: var(--ms-primary-dark); }
.matrix-table tbody tr:hover td.sticky-nis { background: #f0fdf4; }
.matrix-table tbody tr:hover td.sticky-nama { background: #f0fdf4; }
.matrix-table tbody tr:last-child td { border-bottom: none; }

/* ── Status badges ── */
.tgl-cell { width: 26px; min-width: 26px; max-width: 30px; font-size: 10px; font-weight: 700; }
.status-cell { width: 28px; min-width: 28px; max-width: 32px; }
.status-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 24px; height: 24px; border-radius: 7px; font-size: 10px; font-weight: 700;
    line-height: 1; transition: transform .15s;
}
.status-badge:hover { transform: scale(1.15); }
.s-H { background: #dcfce7; color: #15803d; box-shadow: inset 0 -1px 0 rgba(22,163,74,.15); }
.s-I { background: #fef3c7; color: #b45309; box-shadow: inset 0 -1px 0 rgba(217,119,6,.15); }
.s-S { background: #fee2e2; color: #dc2626; box-shadow: inset 0 -1px 0 rgba(220,38,38,.15); }
.s-A { background: #e2e8f0; color: #475569; box-shadow: inset 0 -1px 0 rgba(100,116,139,.15); }
.s-null { background: transparent; color: #d1d5db; }

/* ── Rekap columns ── */
.rekap-cell { width: 28px; min-width: 28px; font-size: 11px; font-weight: 700; font-variant-numeric: tabular-nums; }
.rekap-A { color: #64748b; }
.rekap-I { color: #d97706; }
.rekap-S { color: #dc2626; }

.pencatat-cell { font-size: 11px; color: var(--ms-text-soft); text-align: left; white-space: normal; max-width: 140px; line-height: 1.4; padding-left: 8px !important; }

/* ── Footer ── */
.matrix-table tfoot td {
    background: linear-gradient(180deg, #f8fafc, #f1f5f9); font-weight: 700; font-size: 11px;
    padding: 10px 4px; border-top: 2px solid var(--ms-border); text-align: center;
    color: var(--ms-text);
}

/* ── Legend ── */
.legend-card {
    background: var(--ms-card); border: none; border-radius: var(--ms-radius-sm);
    box-shadow: var(--ms-shadow); padding: 14px 20px; margin-top: 16px;
    display: flex; flex-wrap: wrap; gap: 16px; align-items: center;
}
.legend-title { font-size: 11px; font-weight: 600; color: var(--ms-text-soft); text-transform: uppercase; letter-spacing: .4px; }
.legend-items { display: flex; flex-wrap: wrap; gap: 14px; }
.legend-item { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: var(--ms-text-soft); font-weight: 500; }
.legend-dot {
    width: 22px; height: 22px; border-radius: 6px; display: inline-flex;
    align-items: center; justify-content: center; font-size: 9px; font-weight: 700;
}

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 60px 24px; color: var(--ms-text-soft);
}
.empty-state .empty-icon {
    width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: inline-flex;
    align-items: center; justify-content: center; margin-bottom: 16px;
}
.empty-state .empty-icon i { font-size: 32px; color: #cbd5e1; }
.empty-state h6 { font-size: 15px; font-weight: 600; color: var(--ms-text); margin-bottom: 4px; }
.empty-state p { font-size: 13px; color: var(--ms-text-soft); margin: 0; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .header-card .card-body, .filter-card { padding: 14px 16px; }
    .header-title { font-size: 16px; }
    .matrix-table thead th { font-size: 9px; padding: 7px 3px; }
    .matrix-table tbody td { padding: 5px 2px; font-size: 10px; }
    .status-badge { width: 22px; height: 22px; font-size: 9px; }
    .legend-card { padding: 12px 14px; gap: 10px; }
}

/* ── Print ── */
@media print {
    .page-title-content, .l-sidebar, .header-card, .filter-card, .legend-card { display: none !important; }
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
    .status-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .s-H, .s-I, .s-S, .s-A { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    body { margin: 0; padding: 8px; }
}
</style>

<div class="riwayat-page">
    {{-- ── Header ── --}}
    <div class="header-card mb-4">
        <div class="card-body">
            <div class="header-top">
                <div class="header-left">
                    <div class="header-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="header-title">Rekap Absensi Bulanan</div>
                        <div class="header-subtitle">
                            <span class="header-badge"><i class="fas fa-graduation-cap"></i> {{ $tahunAktif->tahun_ajaran }}</span>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <a href="{{ route('absensi.index') }}" class="btn-ms btn-outline">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($selectedKelasId && $kelas)
                    <a href="{{ route('absensi.riwayat.pdf', ['kelas_id' => $kelas->id, 'bulan' => $bulan]) }}" class="btn-ms btn-indigo" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <button onclick="window.print()" class="btn-ms btn-blue">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filter ── --}}
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <div class="filter-label"><i class="fas fa-chalkboard"></i> Kelas</div>
                <select name="kelas_id" class="filter-input" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $item)
                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="filter-label"><i class="fas fa-calendar"></i> Bulan</div>
                <input type="month" name="bulan" class="filter-input" value="{{ $bulan }}">
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Tampilkan</button>
                    <a href="{{ route('absensi.riwayat') }}" class="btn-reset"><i class="fas fa-undo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>

    @if($selectedKelasId && $kelas)
    @php
        $tanggalAwal = \Carbon\Carbon::parse($bulan . '-01');
        $hariDalamBulan = $tanggalAwal->daysInMonth;
        $bulanLabel = $tanggalAwal->translatedFormat('F Y');
    @endphp

    {{-- ── Report Header ── --}}
    <div class="report-header-card">
        <h5>Absensi Siswa MI Nurul Ulum</h5>
        <p>Kelas {{ strtoupper($kelas->nama_kelas) }} &middot; Bulan {{ strtoupper($bulanLabel) }} &middot; Tahun Ajaran {{ $tahunAktif->tahun_ajaran }}</p>
    </div>

    {{-- ── Matrix Table ── --}}
    <div class="matrix-card">
        <div class="matrix-scroll">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th class="sticky-col" rowspan="2" style="width:36px;">No</th>
                        <th class="sticky-nis" rowspan="2" style="width:70px;position:sticky;left:36px;">NIS</th>
                        <th class="sticky-nama" rowspan="2" style="width:130px;position:sticky;left:106px;">Nama Siswa</th>
                        <th class="group-date" colspan="{{ $hariDalamBulan }}">Tanggal</th>
                        <th class="group-rekap" colspan="3">Tidak Masuk</th>
                        <th class="pencatat-h" rowspan="2">Dicatat Oleh</th>
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
                        $pencatat = $data['_pencatat'] ?? [];
                    @endphp
                    <tr>
                        <td class="sticky-col">{{ $loop->iteration }}</td>
                        <td class="sticky-nis" style="position:sticky;left:36px;">{{ $siswa->nisn }}</td>
                        <td class="sticky-nama" style="position:sticky;left:106px;">{{ $siswa->nama }}</td>
                        @for($d = 1; $d <= $hariDalamBulan; $d++)
                        @php
                            $tgl = $tanggalAwal->copy()->day($d)->format('Y-m-d');
                            $status = $data[$tgl] ?? null;
                        @endphp
                        <td class="status-cell">
                            @if($status)
                                <span class="status-badge s-{{ $status }}">{{ $status }}</span>
                            @else
                                <span class="status-badge s-null">-</span>
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
                        <td colspan="{{ 3 + $hariDalamBulan + 3 + 1 }}">
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
                        <td class="sticky-col" colspan="3" style="text-align:right;padding-right:12px;">TOTAL</td>
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

    {{-- ── Legend ── --}}
    <div class="legend-card">
        <span class="legend-title">Keterangan</span>
        <div class="legend-items">
            <span class="legend-item"><span class="legend-dot s-H">H</span> Hadir</span>
            <span class="legend-item"><span class="legend-dot s-I">I</span> Izin</span>
            <span class="legend-item"><span class="legend-dot s-S">S</span> Sakit</span>
            <span class="legend-item"><span class="legend-dot s-A">A</span> Alpha</span>
            <span class="legend-item"><span class="legend-dot s-null">-</span> Tidak ada data</span>
        </div>
    </div>

    @else
    {{-- ── Empty State ── --}}
    <div class="matrix-card">
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
            <h6>Pilih Kelas & Bulan</h6>
            <p>Silakan pilih kelas dan bulan untuk menampilkan rekap absensi bulanan.</p>
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
        var stickyEls = wrapper.querySelectorAll('.sticky-col, .sticky-nis, .sticky-nama');
        wrapper.addEventListener('scroll', function() {
            stickyEls.forEach(function(el) { el.style.transform = 'translateX(0)'; });
        });
    }
});
</script>
@endpush
