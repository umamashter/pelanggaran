@extends('layouts.main')
@section('title', 'Master Siswa')
@push('css')
<style>
    @include('component.admin.ms-style')
</style>
<style>
    .l-header .c-header-icon.lol.logo {
        display: none !important;
    }

    @media (min-width: 769px) {
        .l-header .js-hamburger {
            display: none !important;
        }
    }

    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 36px;
        padding: 0 8px;
        font-size: 10px;
        border-radius: 8px;
        gap: 3px;
    }

    .btn-header-ms.btn-simpan-ms.btn-compact i {
        font-size: 10px;
    }

    .badge-semester {
        background: #f0fdf4;
        color: #16a34a;
    }

    .badge-ta {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .filter-select-group {
        position: relative;
    }

    .filter-select-group .filter-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
        pointer-events: none;
        font-size: 12px;
    }

    .filter-select-group .form-select {
        padding-left: 30px;
        border-radius: 8px;
        border: 1.5px solid var(--ms-border);
        font-size: 11px;
        height: 32px;
        background-color: #f8fafc;
        transition: all .2s;
        min-width: 170px;
        color: var(--ms-text);
    }

    .filter-select-group .form-select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .master-siswa-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 10px;
        align-items: center;
        flex: 1 1 0;
        min-width: 0;
    }

    @media (min-width: 1200px) {
        .master-siswa-actions {
            flex-wrap: nowrap;
        }
    }

    .master-siswa-header-left {
        flex: 0 1 auto;
        min-width: 0;
    }

    .master-siswa-header-left h4 {
        white-space: nowrap;
    }

    .master-siswa-semester-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        border: none;
    }

    .master-siswa-semester-filter label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ms-text-soft);
        margin: 0;
        padding: 0;
        white-space: nowrap;
    }

    .master-siswa-semester-filter select {
        height: 30px;
        min-width: 150px;
        border: 1.5px solid var(--ms-border);
        border-radius: 8px;
        background: #f8fafc;
        color: var(--ms-text);
        font-size: 12px;
        padding: 0 10px;
        cursor: pointer;
        margin: 0;
    }

    .master-siswa-semester-filter select:focus {
        outline: none;
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background: #fff;
    }

    /* ---- Hide default DataTables search ---- */
    #table_data_user_filter {
        display: none !important;
    }

    /* ---- Custom Toolbar ---- */
    .ms-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .ms-toolbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ms-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ms-search-box {
        position: relative;
    }

    .ms-search-box .ms-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .ms-search-box .ms-search-input {
        height: 32px;
        width: 240px;
        border: 1.5px solid var(--ms-border);
        border-radius: 10px;
        background: #f8fafc;
        color: var(--ms-text);
        font-size: 12px;
        padding: 0 12px 0 34px;
        outline: none;
        transition: all .2s;
    }

    .ms-search-box .ms-search-input::placeholder {
        color: #94a3b8;
    }

    .ms-search-box .ms-search-input:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .08);
        background: #fff;
    }

    @media (max-width: 768px) {
        .ms-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .ms-toolbar-left,
        .ms-toolbar-right {
            width: 100%;
        }

        .ms-search-box .ms-search-input {
            width: 100%;
        }
    }

    /* ---- Modal Tempatkan ---- */
    .modal-tempatkan .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .18);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .modal-tempatkan .modal-dialog {
        margin-top: 20px;
        margin-bottom: 20px;
        max-width: min(1140px, calc(100vw - 2rem));
    }

    .modal-tempatkan .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, .14);
    }

    .modal-tempatkan .modal-body {
        padding: 18px 18px 24px;
        background: #f8fafc;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }

    .modal-tempatkan .modal-footer {
        padding: 18px 24px 34px;
        border-top: 1px solid #e2e8f0;
        background: #fff;
        flex: 0 0 auto;
    }

    .modal-tempatkan .modal-footer .btn {
        min-width: 110px;
    }

    .form-card-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .form-grid-compact {
        display: flex;
        gap: 12px;
        align-items: stretch;
    }

    .form-grid-compact>.form-grid-col {
        flex: 1 1 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    @media (max-width: 991px) {
        .form-grid-compact {
            flex-direction: column;
        }

        .modal-tempatkan .modal-dialog {
            max-width: calc(100vw - 1rem);
            margin: 12px auto;
        }
    }

    @media (max-width: 576px) {
        .modal-tempatkan .modal-content {
            max-height: 92vh;
            border-radius: 16px;
        }

        .modal-tempatkan .modal-header,
        .modal-tempatkan .modal-body,
        .modal-tempatkan .modal-footer {
            padding-left: 14px;
            padding-right: 14px;
        }
    }

    @media (max-width: 575.98px) {
        .dataTables_scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .dataTables_scrollHead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .action-group-ms {
            display: inline-flex !important;
            gap: 4px !important;
            grid-template-columns: unset !important;
        }
        .action-group-ms .btn {
            width: 28px !important;
            height: 28px !important;
            font-size: 11px !important;
        }
    }

    .form-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .form-card-title i {
        color: #16a34a;
    }

    .form-tempatkan .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-tempatkan .form-control,
    .form-tempatkan .form-select {
        height: 40px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #fff;
        font-size: 13px;
        color: #1e293b;
        box-shadow: none;
    }

    .form-tempatkan .form-control:focus,
    .form-tempatkan .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .12);
    }

    .form-tempatkan .form-help {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .toggle-ortu {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #0f172a;
        font-size: 13px;
        font-weight: 600;
    }

    .toggle-ortu:hover {
        border-color: #16a34a;
        color: #16a34a;
        background: #f0fdf4;
    }

    .btn-filter {
        background: #fff;
        border: 1.5px solid var(--ms-border);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        transition: all .25s;
    }

    .btn-filter:hover {
        border-color: var(--ms-primary);
        color: var(--ms-primary);
        background: var(--ms-primary-light);
    }

    .dropdown-menu.filter-dropdown {
        border: 1px solid var(--ms-border);
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(0, 0, 0, .08);
        padding: 6px;
        max-height: 200px;
        overflow-y: auto;
    }

    .dropdown-menu.filter-dropdown .dropdown-item {
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 13px;
        color: #374151;
        transition: all .2s;
    }

    .dropdown-menu.filter-dropdown .dropdown-item:hover {
        background: var(--ms-primary-light);
        color: var(--ms-primary);
    }

    /* ---- Info Card ---- */
    .info-card-modern {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #1e40af;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
    }

    .info-card-modern .info-icon {
        font-size: 18px;
        color: #3b82f6;
        flex-shrink: 0;
    }

    /* ---- Poin Colors ---- */
    .poin-rendah {
        color: #16a34a;
        font-weight: 700;
    }

    .poin-sedang {
        color: #eab308;
        font-weight: 700;
    }

    .poin-tinggi {
        color: #f97316;
        font-weight: 700;
    }

    .poin-bahaya {
        color: #ef4444;
        font-weight: 700;
    }

    /* ---- Table Link ---- */
    #table_data_user tbody td a {
        text-decoration: none;
    }

    /* ---- Kelas Filter ---- */
    .kelas-filter-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kelas-filter-label {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--ms-text-soft);
        white-space: nowrap;
    }

    .kelas-filter-control {
        height: 32px;
        min-width: 150px;
        border: 1.5px solid var(--ms-border);
        border-radius: 8px;
        background: #f8fafc;
        color: var(--ms-text);
        font-size: 12px;
        padding: 0 10px;
    }

    .kelas-filter-control:focus {
        outline: none;
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background: #fff;
    }

    /* ---- Laravel Pagination ---- */
    .pagination .page-link {
        color: #16a34a;
        border-color: #d1fae5;
        box-shadow: none;
    }

    .pagination .page-link:hover {
        color: #15803d;
        background: #f0fdf4;
        border-color: #16a34a;
    }

    .pagination .page-item.active .page-link {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
        border-color: #e2e8f0;
        background: #fff;
    }

    /* ---- Modal (generic) ---- */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .12);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 24px;
    }

    .modal-header.bg-info {
        background: linear-gradient(135deg, #0ea5e9, #38bdf8) !important;
    }

    .modal-header .modal-title {
        font-weight: 600;
        font-size: 16px;
    }

    .modal-body {
        padding: 20px 24px;
    }

    .modal-body .row.ing {
        padding: 6px 0 !important;
    }

    .modal-body .dem {
        font-weight: 600;
        color: #475569;
        font-size: 14px;
    }

    .modal-body .pisah {
        color: #94a3b8;
        padding: 0 4px;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 14px 24px;
    }

    .modal-footer .btn {
        border-radius: 8px;
        font-size: 13px;
        padding: 8px 20px;
        font-weight: 500;
    }

    .modal-footer .btn-secondary {
        background: #f1f5f9;
        border: none;
        color: #475569;
    }

    .modal-footer .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-header-ms:disabled {
        opacity: .5;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="master-siswa-page">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

                {{-- Left: Icon + Title + Badges --}}
                <div class="d-flex align-items-center gap-3 master-siswa-header-left">
                    <div class="header-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Master Siswa
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $tahunAktif->tahun_ajaran }}
                            </span>
                            <span class="badge-modern badge-semester">
                                <i class="fas fa-layer-group me-1"></i>
                                Semester {{ $semesterDipilih->nama }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Controls --}}
                <div class="master-siswa-actions">
                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact" data-bs-toggle="modal" data-bs-target="#modalTempatkanSiswa">
                        <i class="fas fa-user-plus"></i> Tempatkan Siswa
                    </button>

                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#e0f2fe;color:#0369a1;" data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </button>

                    <form action="/admin/kenaikan-kelas" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;" @if(!$canPromote) disabled @endif>
                            <i class="fas fa-level-up-alt"></i> Kenaikan Kelas
                        </button>
                    </form>

                    <form action="/admin/perpindahan-semester" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#ede9fe;color:#5b21b6;" @if(!$canMoveSemester) disabled @endif>
                            <i class="fas fa-exchange-alt"></i> Perpindahan Semester
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

    {{-- ===== INFO CARD ===== --}}
    <div class="info-card-modern mb-4">
        <i class="fas fa-info-circle info-icon"></i>
        <span>Menampilkan data siswa :</span>
        <strong>{{ $tahunAktif->tahun_ajaran }} - Semester {{ $semesterDipilih->nama }}</strong>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="card table-card">
        <div class="card-body">

            {{-- ===== CUSTOM TOOLBAR ===== --}}
            <div class="ms-toolbar">
                <div class="ms-toolbar-left">
                    <form method="GET" action="/master-siswa" class="master-siswa-semester-filter">
                        <select name="semester_id" onchange="this.form.submit()">
                            @foreach($filterOptions as $option)
                            <option value="{{ $option['id'] }}" {{ $semesterId == $option['id'] ? 'selected' : '' }}>
                                {{ $option['label'] }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="ms-toolbar-right">
                    <div class="ms-search-box">
                        <i class="fas fa-search ms-search-icon"></i>
                        <input type="text" id="ms-custom-search" class="ms-search-input" placeholder="Cari nama / NISN...">
                    </div>
                </div>
            </div>

            <table id="table_data_user" class="table table-ms display" cellspacing="0" width="100%">
                <thead class="thead-inverse">
                    <th>No.</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jenjang</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($siswas as $siswa)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $siswa->nisn }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>
                            @if($siswa->riwayatDipilih)
                            {{ $siswa->riwayatDipilih->kelas->tingkat }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($siswa->riwayatDipilih)
                            {{ $siswa->riwayatDipilih->kelas->jenjang->nama_jenjang ?? '-' }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            {{ $siswa->riwayatDipilih?->tahunAjaran?->tahun_ajaran ?? '-' }}
                        </td>
                        <td>
                            @if($siswa->riwayatDipilih)
                            {{ $siswa->riwayatDipilih->semester?->nama ?? '-' }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            <a href="/master-histori/{{ $siswa->id }}"
                                @if ($siswa->poin == 0) style="color:green;" @endif
                                @if ($siswa->poin <= 55) style="color:#fcbc05;" @endif
                                    @if ($siswa->poin <= 149) style="color:#fd5d03;" @endif
                                        @if ($siswa->poin >= 150) style="color:red;" @endif>
                                        <b>{{ $siswa->poin }}</b>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="action-group-ms">
                                <a href="{{ route('master-siswa.detail', $siswa->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/pelanggaran/tambah/{{ $siswa->nisn }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="/pelanggaran/kurang/{{ $siswa->nisn }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <a href="#modalEdit{{ $siswa->id }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Detail --}}
                    <div id="modalCenter{{ $siswa->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Detail Siswa
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">NISN</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->nisn }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">Nama</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->nama }}</div>
                                    </div>
                                    <hr class="my-2" style="border-color: #e2e8f0;">
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">TTL</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->ttl }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">JK</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->jk }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">Agama</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->agama }}</div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">Alamat</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">{{ $siswa->alamat }}</div>
                                    </div>
                                    <hr class="my-2" style="border-color: #e2e8f0;">
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">No.Telp</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">
                                            <a href="tel:{{ $siswa->no_telp }}" style="color:#2563eb;text-decoration:none;">
                                                <i class="fas fa-phone-alt me-1" style="font-size:11px;"></i>
                                                {{ $siswa->no_telp }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row ing ps-2 py-1">
                                        <div class="col-4 dem">No.Telp Rumah</div>
                                        <div class="pisah">:</div>
                                        <div class="col-7">
                                            <a href="tel:{{ $siswa->no_telp_rumah }}" style="color:#2563eb;text-decoration:none;">
                                                <i class="fas fa-phone-alt me-1" style="font-size:11px;"></i>
                                                {{ $siswa->no_telp_rumah }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>
                                        Kembali
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Edit Siswa --}}
                    <div class="modal fade" id="modalEdit{{ $siswa->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kelas: {{ $siswa->nama }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kelas_id" class="form-label">Pilih Kelas Baru</label>
                                            <select name="kelas_id" class="form-select" required>
                                                @foreach($kelas as $k)
                                                <option value="{{ $k->id }}" {{ optional($siswa->kelasAktif)->kelas_id == $k->id ? 'selected' : '' }}>
                                                    {{ $k->tingkat }}{{ $k->nama_kelas }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="modalImportSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('master-siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <div>
                        <h5 class="modal-title mb-1"><i class="fas fa-file-excel me-2"></i>Import Excel</h5>
                        <div style="font-size:12px;opacity:.9;">Sistem akan membaca kolom secara otomatis.</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3 py-2 px-3" style="border-radius:12px;border:none;background:#ecfeff;color:#0f766e;font-size:13px;">
                        <i class="fas fa-circle-info me-1"></i>
                        Template kosong ini sudah berisi header. Isi baris di bawahnya dengan data siswa.
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('master-siswa.template') }}" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-download me-1"></i> Download Template Excel
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-success px-4">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-tempatkan" id="modalTempatkanSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('master-siswa.store') }}" method="POST" id="formTempatkanSiswa" class="form-tempatkan">
                @csrf
                <div class="modal-header bg-success text-white">
                    <div>
                        <h5 class="modal-title mb-1"><i class="fas fa-user-plus me-2"></i>Tempatkan Siswa</h5>
                        <div style="font-size:12px;opacity:.9;">Pilih user lalu lengkapi data, lalu simpan.</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3 py-2 px-3" style="border-radius:12px;border:none;background:#ecfeff;color:#0f766e;font-size:13px;">
                        <i class="fas fa-circle-info me-1"></i>
                        Jika user sudah punya data siswa, field akan terisi otomatis. Admin cukup cek lalu simpan.
                    </div>

                    <div class="form-grid-compact">
                        <div class="form-grid-col">
                            <div class="form-card-section">
                                <div class="form-card-title"><i class="fas fa-user-tag"></i> Akun & Penempatan</div>
                                <div class="row g-2">
                                    <div class="col-lg-7">
                                        <label class="form-label">User Siswa</label>
                                        <select name="user_id" id="user_id_siswa" class="form-select" required>
                                            <option value="">-- Pilih User --</option>
                                            @foreach ($usersSiswa as $user)
                                            <option value="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-nisn="{{ $user->nisn ?? '' }}"
                                                data-nama="{{ $user->studentProfile->nama ?? $user->name }}"
                                                data-ttl="{{ $user->studentProfile->ttl ?? '' }}"
                                                data-jk="{{ $user->studentProfile->jk ?? '' }}"
                                                data-agama="{{ $user->studentProfile->agama ?? '' }}"
                                                data-alamat="{{ $user->studentProfile->alamat ?? '' }}"
                                                data-no-telp="{{ $user->studentProfile->no_telp ?? '' }}"
                                                data-n-ayah="{{ $user->studentProfile->n_ayah ?? '' }}"
                                                data-n-ibu="{{ $user->studentProfile->n_ibu ?? '' }}"
                                                data-alamat-ortu="{{ $user->studentProfile->alamat_ortu ?? '' }}"
                                                data-no-telp-rumah="{{ $user->studentProfile->no_telp_rumah ?? '' }}"
                                                data-kelas-id="{{ $user->studentProfile?->kelasAktif?->kelas_id ?? '' }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }}){{ $user->studentProfile ? ' - sudah ada data' : '' }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="form-help">Hanya user dengan role siswa yang ditampilkan.</div>
                                    </div>
                                    <div class="col-lg-5">
                                        <label class="form-label">Kelas Aktif</label>
                                        <select name="kelas_id" id="kelas_id_siswa" class="form-select" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->jenjang?->nama_jenjang ?? 'Jenjang' }} - Kelas {{ $k->tingkat }}{{ $k->nama_kelas }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-card-section mt-2">
                                <div class="form-card-title"><i class="fas fa-people-roof"></i> Data Orang Tua</div>
                                <div class="row g-2">
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">Nama Ayah</label>
                                        <input type="text" name="n_ayah" id="n_ayah_siswa" class="form-control" value="{{ old('n_ayah') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">Nama Ibu</label>
                                        <input type="text" name="n_ibu" id="n_ibu_siswa" class="form-control" value="{{ old('n_ibu') }}" required>
                                    </div>
                                    <div class="col-lg-8 col-md-12">
                                        <label class="form-label">Alamat Orang Tua</label>
                                        <input type="text" name="alamat_ortu" id="alamat_ortu_siswa" class="form-control" value="{{ old('alamat_ortu') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">No. Telp Rumah</label>
                                        <input type="text" name="no_telp_rumah" id="no_telp_rumah_siswa" class="form-control" value="{{ old('no_telp_rumah') }}" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-grid-col">
                            <div class="form-card-section">
                                <div class="form-card-title"><i class="fas fa-id-card"></i> Data Siswa</div>
                                <div class="row g-2">
                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">NISN</label>
                                        <input type="text" name="nisn" id="nisn_siswa" class="form-control" value="{{ old('nisn') }}" maxlength="10" required>
                                    </div>
                                    <div class="col-lg-8 col-md-6">
                                        <label class="form-label">Nama Siswa</label>
                                        <input type="text" name="nama" id="nama_siswa" class="form-control" value="{{ old('nama') }}" required>
                                    </div>

                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="ttl" id="ttl_siswa" class="form-control" value="{{ old('ttl') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="date" id="date_siswa" class="form-control" value="{{ old('date') }}" required>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="jk" id="jk_siswa" class="form-select" required>
                                            <option value="">-- Pilih JK --</option>
                                            <option value="Laki-laki" {{ old('jk') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jk') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <label class="form-label">Agama</label>
                                        <input type="text" class="form-control" value="Islam" disabled>
                                        <input type="hidden" name="agama" id="agama_siswa" value="Islam">
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <label class="form-label">No. Telp</label>
                                        <input type="text" name="no_telp" id="no_telp_siswa" class="form-control" value="{{ old('no_telp') }}" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" name="alamat" id="alamat_siswa" class="form-control" value="{{ old('alamat') }}" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-success px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#table_data_user').DataTable({
            pagingType: 'simple_numbers',
            responsive: false,
            scrollX: true,
            processing: true,
            pageLength: 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                paginate: {
                    first: '\u00ab',
                    previous: '\u2039',
                    next: '\u203a',
                    last: '\u00bb'
                },
                aria: {
                    paginate: {
                        first: 'First',
                        previous: 'Previous',
                        next: 'Next',
                        last: 'Last'
                    }
                },
            },
            "columnDefs": [{
                    "orderable": false,
                    "width": "30%",
                    "targets": 2
                },
                {
                    "orderable": false,
                    "targets": 5
                },
            ],
            lengthChange: false
        });

        // ===== Kelas filter → append into toolbar-left =====
        var $kelasSelect = $('<select/>', {
            class: 'kelas-filter-control',
            html: '<option value="">Semua Kelas</option>'
        });

        table.column(3).data().unique().sort().each(function(d) {
            var value = $('<div/>').html(d).text().trim();
            if (value && value !== '-') {
                $kelasSelect.append($('<option/>', {
                    value: value,
                    text: value
                }));
            }
        });

        var $kelasWrap = $('<div class="kelas-filter-wrap"></div>');
        $kelasWrap.append('<label class="kelas-filter-label">Kelas</label>');
        $kelasWrap.append($kelasSelect);
        $('.ms-toolbar-left').append($kelasWrap);

        $kelasSelect.on('change', function() {
            var value = $.fn.dataTable.util.escapeRegex($(this).val());
            table.column(3).search(value ? '^' + value + '$' : '', true, false).draw();
        });

        // ===== Custom search → wired to DataTables =====
        $('#ms-custom-search').on('keyup', function() {
            var keyword = $(this).val();
            table.search(keyword).draw();
        });

        function splitTtl(ttl) {
            if (!ttl) return {
                tempat: '',
                tanggal: ''
            };
            var parts = ttl.split(',');
            if (parts.length < 2) return {
                tempat: ttl.trim(),
                tanggal: ''
            };
            var tanggal = parts.pop().trim();
            return {
                tempat: parts.join(',').trim(),
                tanggal: tanggal
            };
        }

        function fillStudentFields(option) {
            if (!option || !option.value) return;

            var data = option.dataset;
            $('#nisn_siswa').val(data.nisn || '');
            $('#nama_siswa').val(data.nama || data.name || '');

            var ttl = splitTtl(data.ttl || '');
            $('#ttl_siswa').val(ttl.tempat || '');
            $('#date_siswa').val(ttl.tanggal || '');

            $('#jk_siswa').val(data.jk || '');
            $('#agama_siswa').val('Islam');
            $('#alamat_siswa').val(data.alamat || '');
            $('#no_telp_siswa').val(data.noTelp || '');
            $('#n_ayah_siswa').val(data.nAyah || '');
            $('#n_ibu_siswa').val(data.nIbu || '');
            $('#alamat_ortu_siswa').val(data.alamatOrtu || '');
            $('#no_telp_rumah_siswa').val(data.noTelpRumah || '');

            if (data.kelasId) {
                $('#kelas_id_siswa').val(data.kelasId);
            }
        }

        $('#user_id_siswa').on('change', function() {
            fillStudentFields(this.options[this.selectedIndex]);
        });

        @if (!$errors->any())
        if ($('#user_id_siswa').val()) {
            fillStudentFields($('#user_id_siswa')[0].options[$('#user_id_siswa')[0].selectedIndex]);
        }
        @endif

        @if ($errors->any())
        var modal = new bootstrap.Modal(document.getElementById('modalTempatkanSiswa'));
        modal.show();
        @endif

        @if ($errors->has('file'))
        var importModal = new bootstrap.Modal(document.getElementById('modalImportSiswa'));
        importModal.show();
        @endif

    });
</script>
@endpush