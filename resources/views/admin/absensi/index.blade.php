@extends('layouts.main')
@section('title','Absensi Siswa')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-bg: #f5f7fb; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
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
#table_absensi { border-collapse: collapse; width: 100% !important; border: 1px solid var(--ms-border); border-radius: 12px; margin: 0 !important; }
#table_absensi thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; padding: 11px 14px; border-bottom: 2px solid var(--ms-border); white-space: nowrap; text-align: center; }
#table_absensi tbody td { padding: 10px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; line-height: 1.5; }
#table_absensi tbody tr:last-child td { border-bottom: none; }
#table_absensi tbody tr:hover td { background: #f8fafc; }
.btn-absen-ms { padding: 6px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; border: none; transition: all .25s; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
.btn-absen-ms:hover { transform: translateY(-1px); color: #fff; }
.btn-absen-ms.btn-success-ms { background: #16a34a; color: #fff; box-shadow: 0 2px 6px rgba(22,163,74,.25); }
.btn-absen-ms.btn-success-ms:hover { background: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.35); }
.btn-absen-ms.btn-secondary-ms { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
.btn-header-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; border: none; text-decoration: none; }
.btn-header-ms:hover { transform: translateY(-2px); color: #fff; }
.btn-header-ms.btn-add-ms { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
@media (max-width: 768px) { .table-card .card-body { padding: 12px 14px 16px; } .dataTables_wrapper .dataTables_filter { float: none; text-align: left; } .dataTables_wrapper .dataTables_filter label input { width: 100%; } #table_absensi thead th { font-size: 11px; padding: 9px 8px; } #table_absensi tbody td { padding: 8px; font-size: 12px; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Absensi Siswa</h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta"><i class="fas fa-calendar-day me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
                            <span class="badge-modern badge-ta"><i class="fas fa-graduation-cap me-1"></i>{{ $tahunAktif->tahun_ajaran }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('absensi.create') }}" class="btn-header-ms btn-add-ms">
                        <i class="fas fa-plus"></i> Input Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <table id="table_absensi" class="table display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Status Hari Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelasList as $kelas)
                    @php
                        $siswaCount = $kelas->siswaAktif()->where('tahun_ajaran_id', $tahunAktif->id)->count();
                        $sudahAbsen = in_array($kelas->id, $absensiHariIni);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong>{{ $kelas->nama_kelas }}</strong></td>
                        <td class="text-center">{{ $siswaCount }} Siswa</td>
                        <td class="text-center">
                            @if($sudahAbsen)
                                <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-check-circle me-1"></i>Sudah Diabsen</span>
                            @else
                                <span class="badge-modern" style="background:#fef3c7;color:#d97706;"><i class="fas fa-clock me-1"></i>Belum</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('absensi.create', ['kelas_id' => $kelas->id, 'tanggal' => now()->toDateString()]) }}" class="btn-absen-ms btn-success-ms">
                                <i class="fas fa-clipboard-list"></i> Absen
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
