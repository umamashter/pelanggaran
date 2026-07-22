@extends('layouts.main')
@section('title','Riwayat Absensi')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
.btn-header-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; border: none; text-decoration: none; }
.btn-header-ms:hover { transform: translateY(-2px); color: #fff; }
.btn-header-ms.btn-rekap-ms { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
.filter-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.filter-card .card-body { padding: 16px 20px; }
.filter-card .form-label { font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 4px; }
.filter-card .form-select { border-radius: 10px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 40px; padding: 0 14px; background-color: #f8fafc; transition: all .2s; color: var(--ms-text); }
.filter-card .form-select:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
.btn-filter-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(22,163,74,.25); height: 40px; }
.btn-filter-ms:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(22,163,74,.35); color: #fff; }
.table-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.table-card .card-body { padding: 16px 20px 20px; }
.dataTables_wrapper .dataTables_filter { float: none; text-align: right; margin-bottom: 8px; }
.dataTables_wrapper .dataTables_filter label { position: relative; display: inline-flex; align-items: center; font-size: 0; line-height: 0; color: transparent; }
.dataTables_wrapper .dataTables_filter label input { font-size: 14px; line-height: normal; color: var(--ms-text); height: 40px; border: 1.5px solid var(--ms-border); border-radius: 24px; padding: 0 16px 0 40px; width: 300px; background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") 14px center no-repeat; background-size: 16px; transition: all .25s; outline: none; }
.dataTables_wrapper .dataTables_filter label input:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
.dataTables_wrapper .dataTables_paginate { padding-top: 12px !important; display: flex; align-items: center; justify-content: flex-end; gap: 4px; float: none; text-align: right; }
.dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid var(--ms-border); border-radius: 8px; padding: 6px 12px; font-size: 13px; font-weight: 500; color: #475569; background: #fff; cursor: pointer; transition: all .2s; min-width: 36px; text-align: center; display: inline-block; line-height: 1.4; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: var(--ms-primary); border-color: var(--ms-primary); color: #fff; box-shadow: 0 2px 6px rgba(22,163,74,.25); }
.dataTables_wrapper .dataTables_paginate .paginate_button.previous, .dataTables_wrapper .dataTables_paginate .paginate_button.next { font-size: 15px; padding: 6px 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .4; cursor: default; pointer-events: none; background: #f8fafc; }
.dataTables_wrapper .dataTables_info { font-size: 13px; color: var(--ms-text-soft); padding-top: 16px !important; clear: both; }
#riwayatTable { border-collapse: collapse; width: 100% !important; border: 1px solid var(--ms-border); border-radius: 12px; margin: 0 !important; }
#riwayatTable thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; padding: 11px 14px; border-bottom: 2px solid var(--ms-border); white-space: nowrap; text-align: center; }
#riwayatTable tbody td { padding: 10px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; line-height: 1.5; }
#riwayatTable tbody tr:last-child td { border-bottom: none; }
#riwayatTable tbody tr:hover td { background: #f8fafc; }
.btn-aksi-ms { width: 32px; height: 32px; border: none !important; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; padding: 0; font-size: 12px; transition: all .25s; box-shadow: 0 1px 2px rgba(0,0,0,.05); line-height: 1; text-decoration: none; margin: 0 2px; }
.btn-aksi-ms:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.12); }
.btn-aksi-ms.btn-info-ms { background: #eff6ff; color: #2563eb; }
.btn-aksi-ms.btn-info-ms:hover { background: #2563eb; color: #fff; }
.btn-aksi-ms.btn-warning-ms { background: #fffbeb; color: #d97706; }
.btn-aksi-ms.btn-warning-ms:hover { background: #d97706; color: #fff; }
.badge-siswa-count { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #f0fdf4; color: #16a34a; }
@media (max-width: 768px) { .table-card .card-body { padding: 12px 14px 16px; } .dataTables_wrapper .dataTables_filter { float: none; text-align: left; } .dataTables_wrapper .dataTables_filter label input { width: 100%; } #riwayatTable thead th { font-size: 11px; padding: 9px 8px; } #riwayatTable tbody td { padding: 8px; font-size: 12px; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-history"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Riwayat Absensi</h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta"><i class="fas fa-clipboard-check me-1"></i>{{ $absensis->count() }} Data</span>
                            <span class="badge-modern badge-ta"><i class="fas fa-graduation-cap me-1"></i>{{ $tahunAktif->tahun_ajaran }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('absensi.rekap') }}" class="btn-header-ms btn-rekap-ms">
                        <i class="fas fa-file-alt me-1"></i> Rekap Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-chalkboard me-1" style="color:var(--ms-primary);"></i>Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $item)
                        <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn-filter-ms"><i class="fas fa-search"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <table id="riwayatTable" class="table display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Dicatat Oleh</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $absensi)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $absensi->tanggal->format('d-m-Y') }}</td>
                        <td><strong>{{ $absensi->kelas->nama_kelas ?? '-' }}</strong></td>
                        <td class="text-center"><span class="badge-siswa-count">{{ $absensi->details->count() }} Siswa</span></td>
                        <td>{{ $absensi->user->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('absensi.detail', $absensi->id) }}" class="btn-aksi-ms btn-info-ms" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('absensi.edit', $absensi->id) }}" class="btn-aksi-ms btn-warning-ms" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
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
    $('#riwayatTable').DataTable({
        pageLength: 10,
        pagingType: 'simple_numbers',
        responsive: false,
        scrollX: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        }
    });
    $('#riwayatTable_filter input').attr('placeholder', 'Cari riwayat...');
});
</script>
@endpush
