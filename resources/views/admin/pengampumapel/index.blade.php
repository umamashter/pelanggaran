@extends('layouts.main')

@section('title','Guru Pengampu Mata Pelajaran')

@section('content')
@include('component.admin.ms-style')
<style>
    .master-pengampu-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        margin-top: 22px;
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

    .btn-lock-ms {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: .6;
    }

    .salin-icon-wrap { width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:4px; }
    html:not(.dark-mode) .salin-icon-wrap { background:linear-gradient(135deg,#eff6ff,#dbeafe); animation:salinPulse 2s ease-in-out infinite; }
    html.dark-mode .salin-icon-wrap { background:rgba(37,99,235,.15); box-shadow:0 0 20px rgba(37,99,235,.1); }
    .salin-icon-wrap i { font-size:32px; color:#2563eb; }
    html:not(.dark-mode) .salin-icon-wrap i { animation:salinBounce 2.5s ease-in-out infinite; }
    @keyframes salinPulse { 0%,100%{box-shadow:0 0 0 0 rgba(37,99,235,.15)} 50%{box-shadow:0 0 0 12px rgba(37,99,235,0)} }
    @keyframes salinBounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-3px)} }

    .salin-info-box { border-left:4px solid #2563eb; border-radius:12px; padding:14px 18px; }
    html:not(.dark-mode) .salin-info-box { background:linear-gradient(135deg,#f8fafc,#f1f5f9); border:1px solid #e2e8f0; }
    html.dark-mode .salin-info-box { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); }
    .salin-info-box .salin-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:2px; }
    .salin-info-box .salin-value { font-weight:700; font-size:15px; }
    html:not(.dark-mode) .salin-info-box .salin-value { color:var(--ms-text); }
    html.dark-mode .salin-info-box .salin-value { color:var(--text-primary); }

    .btn-salin-final { border:none !important; border-radius:10px !important; padding:9px 22px !important; font-weight:600 !important; font-size:13px !important; transition:all .25s !important; }
    .btn-salin-final:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(37,99,235,.3); }

    .btn-header-ms:disabled { opacity:.65; cursor:not-allowed; background:rgba(22,163,74,.25)!important; color:rgba(255,255,255,.7)!important; border:1px solid rgba(22,163,74,.3)!important; }

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

    #pengampuTable_wrapper .dataTables_length,
    #pengampuTable_wrapper .dataTables_filter {
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
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
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
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
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
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
        background-color: #fff;
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
        #pengampuFilter {
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

<div class="master-pengampu-page">

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

                {{-- Left: Icon + Title + Badge --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Pengampu Mapel
                        </h4>
                        <p class="mb-1" style="font-size:12px;color:#94a3b8;line-height:1.4;">
                            Tentukan guru yang mengajar mata pelajaran di setiap kelas.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-list me-1"></i>
                                {{ $pengampus->count() }} Pengampu
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Salin + Tambah --}}
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if($sudahDisalin)
                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                        disabled title="Data tahun ajaran aktif sudah ada">
                        <i class="fas fa-copy me-1"></i> Salin
                    </button>
                    @else
                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                        data-bs-toggle="modal" data-bs-target="#modalSalinPengampu">
                        <i class="fas fa-copy me-1"></i> Salin
                    </button>
                    @endif
                    <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                        data-bs-toggle="modal" data-bs-target="#modalTambahPengampu">
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
                    <form id="pengampuFilter" method="GET" autocomplete="off" style="display:inline;">
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-calendar-alt filter-icon-prepend"></i>
                            <select name="tahun_ajaran_id" class="form-select" onchange="this.form.submit()">
                                @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ (request('tahun_ajaran_id') ?: ($tahunAktif->id ?? ''))==$ta->id ? 'selected' : '' }}>
                                    {{ $ta->tahun_ajaran }}{{ $ta->status == 'Aktif' ? ' (Aktif)' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-layer-group filter-icon-prepend"></i>
                            <select name="jenjang_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Jenjang</option>
                                @foreach($jenjangs as $j)
                                <option value="{{ $j->id }}" {{ request('jenjang_id')==$j->id ? 'selected' : '' }}>
                                    {{ $j->nama_jenjang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <input type="search" id="customSearch" class="search-pill" placeholder="Cari pengampu..." style="width:160px;min-width:120px;font-size:12px;">
                </div>
            </div>

                <table id="pengampuTable" class="table table-ms display" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Jenjang</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($pengampus as $item)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->guru->nama }}</td>
                            <td>{{ $item->mapel->nama_mapel }}</td>
                            <td>{{ $item->mapel->jenjang->nama_jenjang ?? '-' }}</td>
                            <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                            <td>{{ $item->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                            <td>
                                @if($item->is_locked)
                                <span class="badge-status-ms aktif">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                                @else
                                <span class="badge-status-ms nonaktif">
                                    <i class="fas fa-times-circle"></i> Tidak Aktif
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_locked)
                                <div class="action-group-ms">
                                    <button class="btn btn-lock-ms" disabled title="Sudah digunakan di jadwal">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                    <button class="btn btn-lock-ms" disabled title="Sudah digunakan di jadwal">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                </div>
                                @else
                                <div class="action-group-ms">
                                    <button type="button" class="btn btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $item->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#hapus{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('pengampu-mapel.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                            <h5 class="modal-title text-white fw-bold">
                                                <i class="fas fa-edit me-1"></i> Edit Pengampu
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                         <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Guru</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-user"></i></span>
                                                    <select name="guru_id" class="form-select" required>
                                                        @foreach($gurus as $guru)
                                                        <option value="{{ $guru->id }}" {{ $item->guru_id == $guru->id ? 'selected' : '' }}>
                                                            {{ $guru->nama }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Jenjang</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                                                    <select name="jenjang_id" class="form-select editJenjang" required>
                                                        <option value="">Pilih Jenjang</option>
                                                        @foreach($jenjangs as $j)
                                                        <option value="{{ $j->id }}" {{ ($item->mapel->jenjang_id ?? '') == $j->id ? 'selected' : '' }}>
                                                            {{ $j->nama_jenjang }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kelas</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-chalkboard"></i></span>
                                                    <select name="kelas_id" class="form-select editKelas" required>
                                                        <option value="">Pilih Kelas</option>
                                                        @foreach($kelas as $kelasItem)
                                                        <option value="{{ $kelasItem->id }}" data-jenjang="{{ $kelasItem->jenjang_id ?? '' }}" {{ $item->kelas_id == $kelasItem->id ? 'selected' : '' }}>
                                                            {{ $kelasItem->nama_kelas }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Mata Pelajaran</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-book"></i></span>
                                                    <select name="mata_pelajaran_id" class="form-select editMapel" required>
                                                        <option value="">Pilih Mata Pelajaran</option>
                                                        @foreach($mapels as $mapel)
                                                        <option value="{{ $mapel->id }}" data-jenjang="{{ $mapel->jenjang_id ?? '' }}" {{ $item->mata_pelajaran_id == $mapel->id ? 'selected' : '' }}>
                                                            {{ $mapel->nama_mapel }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Tahun Ajaran</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-calendar-alt"></i></span>
                                                    <input type="text" class="form-control" value="{{ $item->tahunAjaran->tahun_ajaran ?? '-' }}" readonly style="background: #f8f9fa;">
                                                    <input type="hidden" name="tahun_ajaran_id" value="{{ $item->tahun_ajaran_id }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Hapus --}}
                        <div class="modal fade" id="hapus{{ $item->id }}" tabindex="-1">
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
                                        <h4 class="fw-bold mb-3">Hapus Data Pengampu?</h4>
                                        <p class="text-muted mb-4">Data yang dihapus tidak dapat dikembalikan.</p>
                                        <div class="card bg-light border-0 mb-4" style="border-radius: 12px;">
                                            <div class="card-body">
                                                <div class="fw-bold text-primary">{{ $item->guru->nama }}</div>
                                                <div class="text-muted">{{ $item->mapel->nama_mapel }}</div>
                                            </div>
                                        </div>
                                        <form action="{{ route('pengampu-mapel.destroy',$item->id) }}" method="POST">
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

{{-- Modal Tambah Pengampu --}}
<div class="modal fade" id="modalTambahPengampu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('pengampu-mapel.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-user-plus me-1"></i> Tambah Pengampu Mata Pelajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="stepGuru">
                        <label class="form-label fw-semibold">Guru</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-user"></i></span>
                            <select name="guru_id" class="form-select" required>
                                <option value="">Pilih Guru</option>
                                @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 d-none" id="stepJenjang">
                        <label class="form-label fw-semibold">Jenjang</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                            <select name="jenjang_id" id="tambahJenjang" class="form-select" required>
                                <option value="">Pilih Jenjang</option>
                                @foreach($jenjangs as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jenjang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 d-none" id="stepKelasMapel">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kelas</label>
                            <div class="input-group-cu">
                                <span class="input-group-cu-icon"><i class="fas fa-chalkboard"></i></span>
                                <select name="kelas_id" id="tambahKelas" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $kelasItem)
                                    <option value="{{ $kelasItem->id }}" data-jenjang="{{ $kelasItem->jenjang_id ?? '' }}">{{ $kelasItem->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mata Pelajaran</label>
                            <div class="input-group-cu">
                                <span class="input-group-cu-icon"><i class="fas fa-book"></i></span>
                                <select name="mata_pelajaran_id" id="tambahMapel" class="form-select" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}" data-jenjang="{{ $mapel->jenjang_id ?? '' }}">{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 d-none" id="stepTA">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control" value="{{ $tahunAktif->tahun_ajaran ?? '-' }}" readonly style="background: #f8f9fa;">
                            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-none" id="stepFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="tambahSubmit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Salin Pengampu --}}
<div class="modal fade" id="modalSalinPengampu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header border-0 pb-0" style="padding:20px 24px 0;">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <div class="salin-icon-wrap">
                        <i class="fas fa-copy"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:18px;">Salin Pengampu Mapel?</h4>
                <p class="text-muted mb-4" style="font-size:13px;line-height:1.6;">
                    Semua data pengampu dari tahun ajaran sebelumnya akan disalin ke tahun ajaran aktif.
                    Data yang sudah ada akan dilewati otomatis.
                </p>

                <div class="salin-info-box mb-3 text-start">
                    <div class="salin-label">Dari Tahun Ajaran</div>
                    <div class="salin-value" id="salinFromTA">-</div>
                </div>
                <div class="salin-info-box mb-4 text-start" style="border-left-color:#16a34a;">
                    <div class="salin-label">Ke Tahun Ajaran Aktif</div>
                    <div class="salin-value" id="salinToTA">-</div>
                </div>

                <form action="{{ route('pengampu-mapel.salin') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal" style="border-radius:10px;padding:9px 22px;font-weight:600;font-size:13px;">Batal</button>
                        <button type="submit" class="btn btn-primary btn-salin-final">
                            <i class="fas fa-copy me-1"></i> Ya, Salin Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tahunAjarans = @json($tahunAjarans);
        var tahunAktif = @json($tahunAktif);

        $('#modalSalinPengampu').on('show.bs.modal', function() {
            if (tahunAktif) {
                $('#salinToTA').text(tahunAktif.tahun_ajaran + ' (Aktif)');
            }
            var tahunSebelumnya = tahunAjarans
                .filter(function(ta) { return ta.id != (tahunAktif ? tahunAktif.id : 0); })
                .sort(function(a, b) { return b.tahun_ajaran.localeCompare(a.tahun_ajaran); })[0];
            if (tahunSebelumnya) {
                $('#salinFromTA').text(tahunSebelumnya.tahun_ajaran);
            } else {
                $('#salinFromTA').text('Tidak ada data');
            }
        });

        var table = $('#pengampuTable').DataTable({
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
                targets: 7
            }]
        });

        $('#customLength').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        $('#customSearch').on('input', function() {
            table.search($(this).val()).draw();
        });

        function filterByJenjang(jenjangVal, kelasSel, mapelSel) {
            var $kelas = $(kelasSel);
            var $mapel = $(mapelSel);
            var prevKelas = $kelas.val();
            var prevMapel = $mapel.val();

            $kelas.find('option[data-jenjang]').each(function() {
                var $opt = $(this);
                if (!jenjangVal || $opt.data('jenjang') == jenjangVal) {
                    $opt.show();
                } else {
                    $opt.hide();
                    if ($opt.val() === prevKelas) prevKelas = '';
                }
            });
            $kelas.val(prevKelas || '');

            $mapel.find('option[data-jenjang]').each(function() {
                var $opt = $(this);
                if (!jenjangVal || $opt.data('jenjang') == jenjangVal) {
                    $opt.show();
                } else {
                    $opt.hide();
                    if ($opt.val() === prevMapel) prevMapel = '';
                }
            });
            $mapel.val(prevMapel || '');
        }

        $('#tambahJenjang').on('change', function() {
            resetTambahOptions();
            filterByJenjang($(this).val(), '#tambahKelas', '#tambahMapel');
            $('#stepKelasMapel').removeClass('d-none');
            $('#stepTA').removeClass('d-none');
            $('#stepFooter').removeClass('d-none');
        });

        $(document).on('change', '.editJenjang', function() {
            var $modal = $(this).closest('.modal');
            filterByJenjang($(this).val(), $modal.find('.editKelas'), $modal.find('.editMapel'));
        });

        var existingPengampus = @json($existingPengampus);

        function resetTambahOptions() {
            $('#tambahKelas option[data-jenjang]').show();
            $('#tambahMapel option[data-jenjang]').show();
        }

        function applyTambahFilters() {
            var guruId = $('#modalTambahPengampu select[name="guru_id"]').val();
            var kelasId = $('#tambahKelas').val();
            var mapelId = $('#tambahMapel').val();

            resetTambahOptions();
            filterByJenjang($('#tambahJenjang').val(), '#tambahKelas', '#tambahMapel');

            if (guruId && kelasId) {
                var usedMapelIds = existingPengampus
                    .filter(function(p) { return p.guru_id == guruId && p.kelas_id == kelasId; })
                    .map(function(p) { return String(p.mata_pelajaran_id); });

                $('#tambahMapel option[data-jenjang]').each(function() {
                    var $opt = $(this);
                    if ($opt.val() === '') return;
                    if (usedMapelIds.indexOf($opt.val()) !== -1) {
                        $opt.hide();
                    }
                });

                if (usedMapelIds.indexOf(mapelId) !== -1) {
                    $('#tambahMapel').val('');
                }
            }

            if (guruId && $('#tambahMapel').val()) {
                var activeMapelId = $('#tambahMapel').val();
                var usedKelasIds = existingPengampus
                    .filter(function(p) { return p.guru_id == guruId && p.mata_pelajaran_id == activeMapelId; })
                    .map(function(p) { return String(p.kelas_id); });

                $('#tambahKelas option[data-jenjang]').each(function() {
                    var $opt = $(this);
                    if ($opt.val() === '') return;
                    if (usedKelasIds.indexOf($opt.val()) !== -1) {
                        $opt.hide();
                    }
                });

                if (usedKelasIds.indexOf(kelasId) !== -1) {
                    $('#tambahKelas').val('');
                }
            }
        }

        $('#modalTambahPengampu').on('change', '#tambahKelas', function() {
            applyTambahFilters();
        });

        $('#modalTambahPengampu').on('change', '#tambahMapel', function() {
            applyTambahFilters();
        });

        $('#modalTambahPengampu').on('show.bs.modal', function() {
            $('#stepJenjang').addClass('d-none');
            $('#stepKelasMapel').addClass('d-none');
            $('#stepTA').addClass('d-none');
            $('#stepFooter').addClass('d-none');
            $('#modalTambahPengampu select[name="guru_id"]').val('');
            $('#tambahJenjang').val('');
            $('#tambahKelas').val('');
            $('#tambahMapel').val('');
        });

        $('#modalTambahPengampu').on('change', 'select[name="guru_id"]', function() {
            if ($(this).val()) {
                $('#stepJenjang').removeClass('d-none');
            } else {
                $('#stepJenjang').addClass('d-none');
                $('#stepKelasMapel').addClass('d-none');
                $('#stepTA').addClass('d-none');
                $('#stepFooter').addClass('d-none');
            }
        });
    });
</script>
@endpush
