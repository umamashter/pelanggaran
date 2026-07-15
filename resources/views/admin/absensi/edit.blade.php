@extends('layouts.main')
@section('title','Edit Absensi')
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
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    box-shadow: 0 4px 14px rgba(0,0,0,.15);
    flex-shrink: 0;
}
.header-icon.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 14px rgba(245,158,11,.3);
}
.form-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
}
.form-card .card-body {
    padding: 16px 20px 20px;
}
.table-form {
    border-collapse: collapse;
    width: 100% !important;
    border: 1px solid var(--ms-border);
    border-radius: 12px;
    margin: 0 !important;
}
.table-form thead th {
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
.table-form tbody td {
    padding: 10px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    line-height: 1.5;
}
.table-form tbody tr:last-child td {
    border-bottom: none;
}
.table-form tbody tr:hover td {
    background: #f8fafc;
}
.table-form .form-select {
    border-radius: 8px;
    border: 1.5px solid var(--ms-border);
    font-size: 13px;
    height: 38px;
    padding: 0 28px 0 12px;
    background-color: #fff;
    transition: all .2s;
    color: var(--ms-text);
    cursor: pointer;
}
.table-form .form-select:focus {
    border-color: var(--ms-primary);
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
}
.btn-update-ms {
    padding: 10px 28px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    transition: all .25s;
    box-shadow: 0 4px 14px rgba(245,158,11,.3);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-update-ms:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245,158,11,.4);
    color: #fff;
}
.btn-update-ms:active {
    transform: translateY(0);
}
.btn-kembali-ms {
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
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
    .table-form thead th { font-size: 11px; padding: 9px 8px; }
    .table-form tbody td { padding: 8px; font-size: 12px; }
    .header-icon { width: 44px; height: 44px; font-size: 20px; }
}
</style>

<div class="master-absensi-page">

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon warning">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                        Edit Absensi
                    </h4>
                    <span class="badge-modern" style="background:#fffbeb;color:#d97706;">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ $absensi->tanggal ? \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') : '' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('absensi.update',$absensi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-form">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Siswa</th>
                                <th width="200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi->details as $detail)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $detail->student->nama }}</td>
                                <td>
                                    <select name="status[{{ $detail->id }}]" class="form-select">
                                        <option value="H" {{ $detail->status=='H' ? 'selected' : '' }}>Hadir</option>
                                        <option value="I" {{ $detail->status=='I' ? 'selected' : '' }}>Izin</option>
                                        <option value="S" {{ $detail->status=='S' ? 'selected' : '' }}>Sakit</option>
                                        <option value="A" {{ $detail->status=='A' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-update-ms">
                        <i class="fas fa-save"></i>
                        Update Absensi
                    </button>
                    <a href="{{ route('absensi.riwayat') }}" class="btn-kembali-ms">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
