@extends('layouts.main')
@section('title','Jadwal Pelajaran')
@section('content')
@include('component.admin.ms-style')
<style>
    .page-title-content {
        display: none !important;
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

    .dt-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .dt-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    #table_jadwal_wrapper .dataTables_length,
    #table_jadwal_wrapper .dataTables_filter {
        display: none !important;
    }

    .dt-length-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        font-size: 12px;
        color: var(--ms-text-soft);
    }

    .dt-length-group .form-select {
        height: 34px;
        border: 1.5px solid var(--ms-border);
        border-radius: 10px;
        padding: 0 28px 0 10px;
        font-size: 12px;
        color: var(--ms-text);
        background-color: #f8fafc;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        transition: all .2s;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 12px;
    }

    .dt-length-group .form-select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .search-pill {
        height: 34px;
        border: 1.5px solid var(--ms-border);
        border-radius: 10px;
        padding: 0 14px 0 34px;
        font-size: 12px;
        color: var(--ms-text);
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") 10px center no-repeat;
        background-size: 14px;
        outline: none;
        transition: all .2s;
    }

    .search-pill:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .filter-lomba-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .filter-lomba-wrap .filter-icon-prepend {
        position: absolute;
        left: 10px;
        font-size: 11px;
        color: #94a3b8;
        pointer-events: none;
        z-index: 2;
    }

    .filter-lomba-wrap .form-select {
        height: 34px;
        border: 1.5px solid var(--ms-border);
        border-radius: 10px;
        padding: 0 28px 0 30px;
        font-size: 12px;
        color: var(--ms-text);
        background-color: #f8fafc;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        transition: all .2s;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 12px;
    }

    .filter-lomba-wrap .form-select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        background-color: #fff;
    }

    .delete-icon-wrap {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(220, 38, 38, .08);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .delete-icon-wrap i {
        font-size: 2.5rem;
        color: #dc2626;
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

        .dt-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .dt-left {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
        .dt-length-group {
            justify-content: flex-start;
        }
        #filterForm {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .filter-lomba-wrap {
            width: 100%;
        }
        .filter-lomba-wrap .form-select {
            width: 100%;
        }
        #customSearch {
            width: 100% !important;
        }
    }
</style>

<div class="master-jadwal-page">

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

                {{-- Left: Icon + Title + Badge --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Jadwal Pelajaran
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-list me-1"></i>
                                {{ $jadwals->count() }} Jadwal
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Export PDF + Tambah --}}
                <div class="d-flex flex-wrap align-items-center gap-2">

                    <a href="{{ route('jadwal-pelajaran.export-pdf', [
                        'jenjang_id' => request('jenjang_id'),
                        'kelas_id' => request('kelas_id'),
                        'guru_id' => request('guru_id'),
                        'tahun_ajaran_id' => request('tahun_ajaran_id')
                    ]) }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,.25);">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>

                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                        data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </button>

                </div>

            </div>

        </div>
    </div>

    {{-- ===== MAIN TABLE CARD ===== --}}
    <div class="card table-card">
        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- FILTER TOOLBAR --}}
            <div class="dt-toolbar">
                <div class="dt-left">
                    <div class="dt-length-group">
                        <span style="font-size:12px;">Tampilkan</span>
                        <select id="customLength" class="form-select" style="min-width:50px;font-size:11px;">
                            @foreach ([10, 15, 25, 50, 100] as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        <span style="font-size:12px;">data</span>
                    </div>
                    <form id="filterForm" method="GET" autocomplete="off" style="display:inline;">
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-layer-group filter-icon-prepend"></i>
                            <select name="jenjang_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Jenjang</option>
                                @foreach($jenjangs as $item)
                                <option value="{{ $item->id }}" {{ request('jenjang_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_jenjang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-school filter-icon-prepend"></i>
                            <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kelas }} {{ $item->jenjang ? '('.$item->jenjang->nama_jenjang.')' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-user filter-icon-prepend"></i>
                            <select name="guru_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Guru</option>
                                @foreach($gurus as $item)
                                <option value="{{ $item->id }}" {{ request('guru_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-calendar-day filter-icon-prepend"></i>
                            <select name="hari" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Hari</option>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Sabtu','Minggu'] as $day)
                                <option value="{{ $day }}" {{ request('hari') == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-calendar-alt filter-icon-prepend"></i>
                            <select name="tahun_ajaran_id" class="form-select" onchange="this.form.submit()">
                                @foreach($tahunAjarans as $item)
                                <option value="{{ $item->id }}" {{ (request('tahun_ajaran_id') == $item->id || (!request('tahun_ajaran_id') && $item->status == 'Aktif')) ? 'selected' : '' }}>
                                    {{ $item->tahun_ajaran }}{{ $item->status == 'Aktif' ? ' (Aktif)' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <input type="search" id="customSearch" class="search-pill" placeholder="Cari jadwal..." style="width:160px;min-width:120px;font-size:12px;">
                </div>
            </div>

                <table id="table_jadwal" class="table table-ms display" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenjang</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Guru</th>
                            <th>Hari</th>
                            <th>JP</th>
                            <th>Waktu</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($jadwals as $jadwal)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jadwal->jenjang->nama_jenjang ?? '-' }}</td>
                            <td>{{ $jadwal->kelas->nama_kelas ?? '-' }}</td>
                            <td>{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                            <td>{{ $jadwal->guru->nama ?? '-' }}</td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>Jam {{ $jadwal->jam_ke }}</td>
                            <td>{{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}</td>
                            <td>
                                @if($jadwal->tahunAjaran)
                                {{ $jadwal->tahunAjaran->tahun_ajaran }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <div class="action-group-ms">
                                    <button type="button" class="btn btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $jadwal->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#hapus{{ $jadwal->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="edit{{ $jadwal->id }}">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form action="{{ route('jadwal-pelajaran.update',$jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                            <h5 class="modal-title text-white fw-bold">
                                                <i class="fas fa-edit me-1"></i> Edit Jadwal Pelajaran
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Kelas</label>
                                                    <div class="input-group-cu">
                                                        <span class="input-group-cu-icon"><i class="fas fa-school"></i></span>
                                                        <select name="kelas_id" class="form-select" required>
                                                            @foreach($kelas as $item)
                                                            <option value="{{ $item->id }}" {{ $jadwal->kelas_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama_kelas }} {{ $item->jenjang ? '('.$item->jenjang->nama_jenjang.')' : '' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Guru</label>
                                                    <div class="input-group-cu">
                                                        <span class="input-group-cu-icon"><i class="fas fa-user"></i></span>
                                                        <select name="guru_id" class="form-select" required>
                                                            @foreach($gurus as $item)
                                                            <option value="{{ $item->id }}" {{ $jadwal->guru_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Mata Pelajaran</label>
                                                    <div class="input-group-cu">
                                                        <span class="input-group-cu-icon"><i class="fas fa-book"></i></span>
                                                        <select name="mata_pelajaran_id" class="form-select" required>
                                                            @foreach($mapels as $item)
                                                            <option value="{{ $item->id }}" {{ $jadwal->mata_pelajaran_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama_mapel }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Tahun Ajaran Aktif</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $tahunAjaranAktif->tahun_ajaran }}"
                                                        readonly style="background:#f1f5f9;border-radius:12px;height:46px;padding:0 16px;">
                                                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Hari</label>
                                                    <div class="input-group-cu">
                                                        <span class="input-group-cu-icon"><i class="fas fa-calendar-day"></i></span>
                                                        <select name="hari" class="form-select">
                                                            <option {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                                            <option {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                                            <option {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                                            <option {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                                            <option {{ $jadwal->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                                            <option {{ $jadwal->hari == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Jam Pelajaran</label>
                                                    <div class="input-group-cu">
                                                        <span class="input-group-cu-icon"><i class="fas fa-clock"></i></span>
                                                        <select name="jam_ke" class="form-select" required>
                                                            <option value="1" {{ $jadwal->jam_ke == 1 ? 'selected' : '' }}>Jam 1 (07:30 - 08:30)</option>
                                                            <option value="2" {{ $jadwal->jam_ke == 2 ? 'selected' : '' }}>Jam 2 (08:30 - 09:30)</option>
                                                            <option value="3" {{ $jadwal->jam_ke == 3 ? 'selected' : '' }}>Jam 3 (10:00 - 11:00)</option>
                                                            <option value="4" {{ $jadwal->jam_ke == 4 ? 'selected' : '' }}>Jam 4 (11:00 - 12:00)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Hapus --}}
                        <div class="modal fade" id="hapus{{ $jadwal->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header border-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center px-4 pb-4">
                                        <div class="mb-3">
                                            <div class="delete-icon-wrap">
                                                <i class="fas fa-trash-alt"></i>
                                            </div>
                                        </div>
                                        <h4 class="fw-bold mb-3">Hapus Jadwal?</h4>
                                        <p class="text-muted mb-4">Data yang dihapus tidak dapat dikembalikan.</p>
                                        <div class="card bg-light border-0 mb-4" style="border-radius: 12px;">
                                            <div class="card-body">
                                                <div class="fw-bold text-primary">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                                                <div class="text-muted">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <form action="{{ route('jadwal-pelajaran.destroy',$jadwal->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-1"></i> Ya, Hapus
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach

                    </tbody>

                </table>

        </div>
    </div>

</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" id="formTambahJadwal">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-plus me-1"></i> Tambah Jadwal Pelajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">

                    <div class="row">
                        {{-- STEP 1: Kelas --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kelas</label>
                            <select name="kelas_id" id="tambah_kelas" class="form-select" required style="height:46px;border-radius:12px;">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $item)
                                <option value="{{ $item->id }}"
                                    data-jenjang-id="{{ $item->jenjang_id }}"
                                    data-jenjang-nama="{{ $item->jenjang->nama_jenjang ?? '-' }}">
                                    {{ $item->nama_kelas }} — {{ $item->jenjang->nama_jenjang ?? '-' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- STEP 1b: Jenjang (auto) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jenjang <small class="text-muted">(otomatis)</small></label>
                            <input type="text" id="tambah_jenjang" class="form-control" readonly
                                placeholder="Otomatis dari kelas"
                                style="background:#f1f5f9;border-radius:12px;height:46px;padding:0 16px;">
                            <input type="hidden" name="jenjang_id" id="tambah_jenjang_id">
                        </div>

                        {{-- STEP 2: Mata Pelajaran (hidden until kelas selected) --}}
                        <div class="col-md-6 mb-3 d-none" id="step2_mapel">
                            <label class="form-label fw-semibold">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="tambah_mapel" class="form-select" required style="height:46px;border-radius:12px;">
                                <option value="">Pilih Mata Pelajaran</option>
                            </select>
                        </div>

                        {{-- STEP 3: Guru (hidden until mapel selected) --}}
                        <div class="col-md-6 mb-3 d-none" id="step3_guru">
                            <label class="form-label fw-semibold">Guru</label>
                            <select name="guru_id" id="tambah_guru" class="form-select" required style="height:46px;border-radius:12px;">
                                <option value="">Pilih Guru</option>
                            </select>
                        </div>

                        {{-- STEP 4: Hari (hidden until guru selected) --}}
                        <div class="col-md-6 mb-3 d-none" id="step4_hari">
                            <label class="form-label fw-semibold">Hari</label>
                            <select name="hari" id="tambah_hari" class="form-select" required style="height:46px;border-radius:12px;">
                                <option value="">Pilih Hari</option>
                                <option>Senin</option>
                                <option>Selasa</option>
                                <option>Rabu</option>
                                <option>Kamis</option>
                                <option>Sabtu</option>
                                <option>Minggu</option>
                            </select>
                        </div>

                        {{-- STEP 5: Jam (hidden until hari selected) --}}
                        <div class="col-md-6 mb-3 d-none" id="step5_jam">
                            <label class="form-label fw-semibold">Jam Pelajaran</label>
                            <select name="jam_ke" id="tambah_jam" class="form-select" required style="height:46px;border-radius:12px;">
                                <option value="">Pilih Jam</option>
                                <option value="1">Jam 1 (07:30 - 08:30)</option>
                                <option value="2">Jam 2 (08:30 - 09:30)</option>
                                <option value="3">Jam 3 (10:00 - 11:00)</option>
                                <option value="4">Jam 4 (11:00 - 12:00)</option>
                            </select>
                        </div>

                        {{-- Tahun Ajaran (auto, full width) --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Tahun Ajaran <small class="text-muted">(otomatis)</small></label>
                            @if($tahunAjaranAktif)
                            <input type="text" class="form-control" readonly
                                value="{{ $tahunAjaranAktif->tahun_ajaran }}"
                                style="background:#f1f5f9;border-radius:12px;height:46px;padding:0 16px;">
                            @else
                            <div class="alert alert-danger mb-0" style="border-radius:12px;">Tidak ada tahun ajaran aktif.</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSimpanJadwal" disabled>
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const pengampuMapels = @json($pengampuMapels);
        const allMapels = @json($mapels->map(function ($m) {
            return ['id' => $m->id, 'nama_mapel' => $m->nama_mapel];
        })->values());
        const allGurus = @json($gurus->map(function ($g) {
            return ['id' => $g->id, 'nama' => $g->nama];
        })->values());

        // DataTable
        var table = $('#table_jadwal').DataTable({
            pagingType: 'simple_numbers',
            responsive: false,
            scrollX: true,
            processing: true,
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: '«',
                    previous: '‹',
                    next: '›',
                    last: '»'
                },
                aria: {
                    paginate: {
                        first: 'First',
                        previous: 'Previous',
                        next: 'Next',
                        last: 'Last'
                    }
                }
            },
            columnDefs: [{
                orderable: false,
                targets: 9
            }]
        });

        $('#customLength').on('change', function() {
            table.page.len($(this).val()).draw();
        });
        $('#customSearch').on('input', function() {
            table.search($(this).val()).draw();
        });

        // ===== STEP-BY-STEP TAMBAH JADWAL =====
        function resetTambahForm() {
            $('#tambah_kelas').val('');
            $('#tambah_jenjang').val('');
            $('#tambah_jenjang_id').val('');
            $('#step2_mapel').addClass('d-none');
            $('#step3_guru').addClass('d-none');
            $('#step4_hari').addClass('d-none');
            $('#step5_jam').addClass('d-none');
            $('#tambah_mapel').html('<option value="">Pilih Mata Pelajaran</option>');
            $('#tambah_guru').html('<option value="">Pilih Guru</option>');
            $('#tambah_hari').val('');
            $('#tambah_jam').val('');
            $('#btnSimpanJadwal').prop('disabled', true);
        }

        $('#modalTambah').on('show.bs.modal', function() {
            resetTambahForm();
        });

        // STEP 1: Kelas → auto jenjang + show mapel
        $('#tambah_kelas').on('change', function() {
            var $opt = $(this).find(':selected');
            var jenjangId = $opt.data('jenjang-id');
            var jenjangNama = $opt.data('jenjang-nama');
            var kelasId = $(this).val();

            // Auto-fill jenjang
            $('#tambah_jenjang').val(jenjangNama || '');
            $('#tambah_jenjang_id').val(jenjangId || '');

            // Reset downstream
            $('#step3_guru').addClass('d-none');
            $('#step4_hari').addClass('d-none');
            $('#step5_jam').addClass('d-none');
            $('#tambah_guru').html('<option value="">Pilih Guru</option>');
            $('#tambah_hari').val('');
            $('#tambah_jam').val('');
            $('#btnSimpanJadwal').prop('disabled', true);

            if (!kelasId) {
                $('#step2_mapel').addClass('d-none');
                $('#tambah_mapel').html('<option value="">Pilih Mata Pelajaran</option>');
                return;
            }

            // Find unique mapel_ids from pengampu_mapel for this kelas
            var mapelIds = [...new Set(
                pengampuMapels
                    .filter(function(p) {
                        return String(p.kelas_id) === String(kelasId);
                    })
                    .map(function(p) {
                        return String(p.mata_pelajaran_id);
                    })
            )];

            var $mapel = $('#tambah_mapel').html('<option value="">Pilih Mata Pelajaran</option>');
            if (!mapelIds.length) {
                $mapel.append('<option value="" disabled>Tidak ada mata pelajaran untuk kelas ini</option>');
                $('#step2_mapel').removeClass('d-none');
                return;
            }

            allMapels.forEach(function(m) {
                if (mapelIds.includes(String(m.id))) {
                    $mapel.append('<option value="' + m.id + '">' + m.nama_mapel + '</option>');
                }
            });

            $('#step2_mapel').removeClass('d-none');
        });

        // STEP 2: Mapel → show guru (filtered by kelas + mapel)
        $('#tambah_mapel').on('change', function() {
            var kelasId = $('#tambah_kelas').val();
            var mapelId = $(this).val();

            // Reset downstream
            $('#step4_hari').addClass('d-none');
            $('#step5_jam').addClass('d-none');
            $('#tambah_hari').val('');
            $('#tambah_jam').val('');
            $('#btnSimpanJadwal').prop('disabled', true);

            if (!mapelId || !kelasId) {
                $('#step3_guru').addClass('d-none');
                $('#tambah_guru').html('<option value="">Pilih Guru</option>');
                return;
            }

            // Find unique guru_ids from pengampu_mapel for this kelas + mapel
            var guruIds = [...new Set(
                pengampuMapels
                .filter(function(p) {
                    return String(p.kelas_id) === String(kelasId) && String(p.mata_pelajaran_id) === String(mapelId);
                })
                .map(function(p) {
                    return String(p.guru_id);
                })
            )];

            var $guru = $('#tambah_guru').html('<option value="">Pilih Guru</option>');
            if (!guruIds.length) {
                $guru.append('<option value="" disabled>Tidak ada guru untuk mapel ini</option>');
                $('#step3_guru').removeClass('d-none');
                return;
            }

            allGurus.forEach(function(g) {
                if (guruIds.includes(String(g.id))) {
                    $guru.append('<option value="' + g.id + '">' + g.nama + '</option>');
                }
            });

            $('#step3_guru').removeClass('d-none');
        });

        // STEP 3: Guru → show hari
        $('#tambah_guru').on('change', function() {
            if ($(this).val()) {
                $('#step4_hari').removeClass('d-none');
            } else {
                $('#step4_hari').addClass('d-none');
                $('#step5_jam').addClass('d-none');
            }
            $('#tambah_hari').val('');
            $('#tambah_jam').val('');
            $('#btnSimpanJadwal').prop('disabled', true);
        });

        // STEP 4: Hari → show jam
        $('#tambah_hari').on('change', function() {
            if ($(this).val()) {
                $('#step5_jam').removeClass('d-none');
            } else {
                $('#step5_jam').addClass('d-none');
            }
            $('#tambah_jam').val('');
            $('#btnSimpanJadwal').prop('disabled', true);
        });

        // STEP 5: Jam → enable submit
        $('#tambah_jam').on('change', function() {
            var ready = $('#tambah_kelas').val() && $('#tambah_mapel').val() && $('#tambah_guru').val() && $('#tambah_hari').val() && $(this).val();
            $('#btnSimpanJadwal').prop('disabled', !ready);
        });
    });
</script>
@endpush
