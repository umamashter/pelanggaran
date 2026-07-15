@extends('layouts.main')
@section('title','Rekap Absensi')
@section('content')
<style>
.page-title-content {
    display: none !important;
}
:root {
    --ms-primary: #16a34a;
    --ms-primary-dark: #15803d;
    --ms-primary-light: #dcfce7;
    --ms-bg: #f5f7fb;
    --ms-border: #e2e8f0;
    --ms-text: #1e293b;
    --ms-text-soft: #64748b;
}
.master-absensi-page {
    font-family: 'Inter', 'Poppins', system-ui, sans-serif;
    margin-top: 22px;
}
.header-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    box-shadow: 0 4px 14px rgba(22,163,74,.3);
    flex-shrink: 0;
}
.badge-modern {
    display: inline-flex;
    align-items: center;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}
.badge-ta {
    background: #f0fdf4;
    color: #16a34a;
}
.filter-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
}
.filter-card .card-body {
    padding: 16px 20px;
}
.filter-card .form-label {
    font-weight: 600;
    font-size: 13px;
    color: #475569;
    margin-bottom: 4px;
}
.filter-card .form-select,
.filter-card .form-control {
    border-radius: 10px;
    border: 1.5px solid var(--ms-border);
    font-size: 13px;
    height: 40px;
    padding: 0 14px;
    background-color: #f8fafc;
    transition: all .2s;
    color: var(--ms-text);
}
.filter-card .form-select:focus,
.filter-card .form-control:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    background-color: #fff;
}
.btn-filter-ms {
    padding: 8px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    transition: all .25s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(22,163,74,.25);
    height: 40px;
}
.btn-filter-ms:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(22,163,74,.35);
    color: #fff;
}
.btn-pdf-ms {
    padding: 8px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    transition: all .25s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(220,38,38,.25);
    text-decoration: none;
    height: 40px;
}
.btn-pdf-ms:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(220,38,38,.35);
    color: #fff;
}
.table-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
}
.table-card .card-body {
    padding: 16px 20px 20px;
}
.table-rekap {
    border-collapse: collapse;
    width: 100% !important;
    border: 1px solid var(--ms-border);
    border-radius: 12px;
    margin: 0 !important;
}
.table-rekap thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 11px 14px;
    border-bottom: 2px solid var(--ms-border);
    white-space: nowrap;
    text-align: center;
}
.table-rekap tbody td {
    padding: 10px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    line-height: 1.5;
}
.table-rekap tbody tr:last-child td {
    border-bottom: none;
}
.table-rekap tbody tr:hover td {
    background: #f8fafc;
}
.table-rekap tbody tr:nth-child(even) td {
    background: #fafbfc;
}
.table-rekap tbody tr:nth-child(even):hover td {
    background: #f1f5f9;
}
.badge-count {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    min-width: 28px;
    text-align: center;
}
.badge-count.hadir { background: #f0fdf4; color: #16a34a; }
.badge-count.izin { background: #fffbeb; color: #d97706; }
.badge-count.sakit { background: #fef2f2; color: #dc2626; }
.badge-count.alpha { background: #f1f5f9; color: #64748b; }
.btn-kembali-ms {
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    border: 1.5px solid var(--ms-border);
    background: #fff;
    color: #475569;
    transition: all .25s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}
.btn-kembali-ms:hover {
    border-color: var(--ms-primary);
    color: var(--ms-primary);
    background: var(--ms-primary-light);
}
@media (max-width: 768px) {
    .table-card .card-body { padding: 12px 14px 16px; }
    .table-rekap thead th { font-size: 11px; padding: 9px 8px; }
    .table-rekap tbody td { padding: 8px; font-size: 12px; }
    .header-icon { width: 44px; height: 44px; font-size: 20px; }
}
</style>

<div class="master-absensi-page">

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Rekap Absensi Siswa
                        </h4>
                        <span class="badge-modern badge-ta">
                            <i class="fas fa-clipboard-check me-1"></i>
                            {{ $siswas->count() }} Siswa
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-chalkboard me-1" style="color:var(--ms-primary);"></i>
                        Kelas
                    </label>
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $item)
                        <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt me-1" style="color:var(--ms-primary);"></i>
                        Bulan
                    </label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn-filter-ms">
                        <i class="fas fa-search"></i>
                        Filter
                    </button>
                    <a href="{{ route('absensi.rekap.pdf',['bulan'=>request('bulan')]) }}"
                        target="_blank"
                        class="btn-pdf-ms">
                        <i class="fas fa-file-pdf"></i>
                        Cetak PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-rekap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $siswa)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge-count hadir">
                                    {{ \App\Models\AbsensiDetail::where('student_id',$siswa->id)->where('status','H')->when($bulan, function ($query) use ($bulan) { $query->whereHas('absensi', function ($q) use ($bulan) { $q->whereYear('tanggal', date('Y', strtotime($bulan))); $q->whereMonth('tanggal', date('m', strtotime($bulan))); }); })->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-count izin">
                                    {{ \App\Models\AbsensiDetail::where('student_id',$siswa->id)->where('status','I')->when($bulan, function ($query) use ($bulan) { $query->whereHas('absensi', function ($q) use ($bulan) { $q->whereYear('tanggal', date('Y', strtotime($bulan))); $q->whereMonth('tanggal', date('m', strtotime($bulan))); }); })->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-count sakit">
                                    {{ \App\Models\AbsensiDetail::where('student_id',$siswa->id)->where('status','S')->when($bulan, function ($query) use ($bulan) { $query->whereHas('absensi', function ($q) use ($bulan) { $q->whereYear('tanggal', date('Y', strtotime($bulan))); $q->whereMonth('tanggal', date('m', strtotime($bulan))); }); })->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-count alpha">
                                    {{ \App\Models\AbsensiDetail::where('student_id',$siswa->id)->where('status','A')->when($bulan, function ($query) use ($bulan) { $query->whereHas('absensi', function ($q) use ($bulan) { $q->whereYear('tanggal', date('Y', strtotime($bulan))); $q->whereMonth('tanggal', date('m', strtotime($bulan))); }); })->count() }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('absensi.riwayat') }}" class="btn-kembali-ms">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
