@extends('layouts.main')

@section('title', 'Detail Kelas')

@section('content')

@include('component.admin.ms-style')
<style>
    .detail-kelas-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        margin-top: 22px;
    }

    /* ---- Header ---- */
    .header-card-modern {
        border: none;
        border-radius: 20px;
        background: #fff;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,.05), 0 2px 8px rgba(0,0,0,.03), inset 0 1px 0 rgba(255,255,255,.8);
    }

    .header-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #16a34a, #22c55e, #4ade80);
    }

    .header-icon-wrap {
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

    .header-title {
        color: var(--ms-text);
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -.3px;
        margin-bottom: 2px;
    }

    .header-sub {
        color: var(--ms-text-soft);
        font-size: 13px;
    }

    .btn-back-ms {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        border: none;
        color: #fff;
        text-decoration: none;
        transition: all .25s;
        box-shadow: 0 2px 8px rgba(22,163,74,.25);
    }

    .btn-back-ms:hover {
        box-shadow: 0 6px 20px rgba(22,163,74,.35);
        color: #fff;
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-back-ms.btn-tambah-ms {
        background: #fff;
        border: 1.5px solid var(--ms-border);
        color: #475569;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }

    .btn-back-ms.btn-tambah-ms:hover {
        border-color: var(--ms-primary);
        color: var(--ms-primary);
        background: var(--ms-primary-light);
        box-shadow: 0 3px 8px rgba(0,0,0,.08);
    }

    /* ---- Info Cards ---- */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,.04), inset 0 1px 0 rgba(255,255,255,.8);
        transition: all .3s;
        position: relative;
        overflow: hidden;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,.08);
    }

    .info-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 0 0 16px 16px;
    }

    .info-card.card-kelas::after { background: linear-gradient(90deg, #16a34a, #22c55e); }
    .info-card.card-jenjang::after { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .info-card.card-wali::after { background: linear-gradient(90deg, #d97706, #f59e0b); }
    .info-card.card-siswa::after { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

    .info-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .card-kelas .info-card-icon { background: #dcfce7; color: #16a34a; }
    .card-jenjang .info-card-icon { background: #dbeafe; color: #3b82f6; }
    .card-wali .info-card-icon { background: #fef3c7; color: #d97706; }
    .card-siswa .info-card-icon { background: #ede9fe; color: #8b5cf6; }

    .info-card-label {
        font-size: 12px;
        color: var(--ms-text-soft);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 4px;
    }

    .info-card-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--ms-text);
        line-height: 1.3;
    }

    /* ---- Jenjang Badge ---- */
    .badge-jenjang {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

    .badge-jenjang .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .badge-jenjang.mi { background: #dcfce7; color: #15803d; }
    .badge-jenjang.mi .badge-dot { background: #22c55e; }
    .badge-jenjang.mts { background: #dbeafe; color: #1d4ed8; }
    .badge-jenjang.mts .badge-dot { background: #3b82f6; }
    .badge-jenjang.ma { background: #fef3c7; color: #b45309; }
    .badge-jenjang.ma .badge-dot { background: #f59e0b; }

    /* ---- Table Card ---- */
    .table-card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,.05), 0 2px 8px rgba(0,0,0,.03), inset 0 1px 0 rgba(255,255,255,.8);
        background: #fff;
        overflow: hidden;
    }

    .table-card-modern .card-body {
        padding: 20px 24px 24px;
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-card-title h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--ms-text);
    }

    .table-card-title .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: var(--ms-primary-light);
        color: var(--ms-primary-dark);
    }

    /* ---- Student Row ---- */
    .student-nama {
        font-weight: 600;
        color: var(--ms-text);
    }

    .student-nisn {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 12px;
        color: var(--ms-text-soft);
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* ---- Status Badge ---- */
    .badge-status-ms {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-status-ms.aktif {
        background: #dcfce7;
        color: #16a34a;
    }

    /* ---- Empty State ---- */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .empty-state-icon i {
        font-size: 32px;
        color: #94a3b8;
    }

    .empty-state h5 {
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 14px;
        margin: 0;
    }

    /* ---- Responsive ---- */
    @media (max-width: 1199px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .header-card-modern {
            padding: 20px;
            border-radius: 16px;
        }

        .header-icon-wrap {
            width: 44px;
            height: 44px;
            font-size: 20px;
            border-radius: 12px;
        }

        .header-title {
            font-size: 18px;
        }

        .info-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .info-card {
            padding: 14px;
            border-radius: 12px;
        }

        .info-card-icon {
            width: 36px;
            height: 36px;
            font-size: 15px;
            margin-bottom: 8px;
            border-radius: 10px;
        }

        .info-card-label {
            font-size: 10px;
        }

        .info-card-value {
            font-size: 15px;
        }

        .table-card-modern {
            border-radius: 16px;
        }

        .table-card-modern .card-body {
            padding: 14px;
        }
    }

    @media (max-width: 575.98px) {
        .header-card-modern {
            padding: 16px;
        }

        .header-title {
            font-size: 16px;
        }

        .header-sub {
            font-size: 12px;
        }

        .btn-back-ms {
            padding: 5px 10px;
            font-size: 11px;
            gap: 4px;
            white-space: nowrap;
            width: fit-content;
            align-self: flex-start;
        }

        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .info-card {
            padding: 12px;
            border-radius: 12px;
        }

        .info-card-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
            margin-bottom: 6px;
            border-radius: 8px;
        }

        .info-card-label {
            font-size: 9px;
            letter-spacing: .3px;
        }

        .info-card-value {
            font-size: 14px;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-ms thead th {
            font-size: 11px;
            padding: 9px 8px;
        }

        .table-ms tbody td {
            padding: 8px;
            font-size: 12px;
        }

        .student-nisn {
            font-size: 11px;
            padding: 2px 6px;
        }

        .badge-status-ms {
            padding: 4px 10px;
            font-size: 11px;
        }

        .dataTables_wrapper .dataTables_filter label input {
            width: 100%;
        }

        .dataTables_wrapper .dataTables_length {
            float: none;
            margin-bottom: 8px;
        }

        .dataTables_wrapper .dataTables_paginate {
            justify-content: center;
        }

        .dataTables_wrapper .dataTables_info {
            text-align: center;
            font-size: 12px;
        }
    }

    @media (max-width: 400px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
        }

        .info-card-icon {
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .info-card-text {
            flex: 1;
        }
    }
</style>

<div class="detail-kelas-page">

    {{-- ===== HEADER ===== --}}
    <div class="header-card-modern">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-wrap">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                    <div class="header-title">Detail Kelas</div>
                    <div class="header-sub">Informasi lengkap kelas {{ $kelas->tingkat }}{{ $kelas->nama_kelas }}</div>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('kelas.create') }}" class="btn-back-ms btn-tambah-ms">
                    <i class="fas fa-plus"></i> Tambah Kelas
                </a>
                <a href="{{ route('kelas.index') }}" class="btn-back-ms">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- ===== INFO CARDS ===== --}}
    <div class="info-grid">
        <div class="info-card card-kelas">
            <div class="info-card-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="info-card-text">
                <div class="info-card-label">Kelas</div>
                <div class="info-card-value">{{ $kelas->tingkat }}{{ $kelas->nama_kelas }}</div>
            </div>
        </div>

        <div class="info-card card-jenjang">
            <div class="info-card-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="info-card-text">
                <div class="info-card-label">Jenjang</div>
                <div class="info-card-value">
                    @php $kode = $kelas->jenjang->kode ?? ''; @endphp
                    <span class="badge-jenjang {{ strtolower($kode) }}">
                        <span class="badge-dot"></span>
                        {{ $kode }}
                    </span>
                </div>
            </div>
        </div>

        <div class="info-card card-wali">
            <div class="info-card-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="info-card-text">
                <div class="info-card-label">Wali Kelas</div>
                <div class="info-card-value">{{ $kelas->waliKelas->guru->nama ?? '-' }}</div>
            </div>
        </div>

        <div class="info-card card-siswa">
            <div class="info-card-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="info-card-text">
                <div class="info-card-label">Jumlah Siswa</div>
                <div class="info-card-value">{{ $kelas->siswaAktif->count() }} Siswa</div>
            </div>
        </div>
    </div>

    {{-- ===== DAFTAR SISWA ===== --}}
    <div class="card table-card-modern">
        <div class="card-body">

            <div class="table-card-header">
                <div class="table-card-title">
                    <h5><i class="fas fa-users me-1"></i> Daftar Siswa Aktif</h5>
                    <span class="count-badge">{{ $kelas->siswaAktif->count() }}</span>
                </div>
            </div>

            @if($kelas->siswaAktif->count() > 0)
                <table id="siswaTable" class="table table-ms display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th width="100">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas->siswaAktif as $anggota)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="student-nisn">{{ $anggota->student->nisn }}</span>
                            </td>
                            <td>
                                <span class="student-nama">{{ $anggota->student->nama }}</span>
                            </td>
                            <td>
                                <span class="badge-status-ms aktif">
                                    <i class="fas fa-check-circle" style="font-size:10px"></i> Aktif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <h5>Belum ada siswa</h5>
                    <p>Belum ada siswa yang terdaftar di kelas ini</p>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection

@if($kelas->siswaAktif->count() > 0)
@push('scripts')
<script>
    $(document).ready(function() {
        $('#siswaTable').DataTable({
            pagingType: 'simple_numbers',
            responsive: false,
            scrollX: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Siswa tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ siswa",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total siswa)",
                paginate: {
                    first: '«',
                    previous: '‹',
                    next: '›',
                    last: '»'
                }
            },
            columnDefs: [{
                orderable: false,
                targets: 3
            }],
            order: [[0, 'asc']]
        });

        $('#siswaTable_filter input').attr('placeholder', 'Cari siswa...');
    });
</script>
@endpush
@endif
