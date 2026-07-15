@extends('layouts.main')
@section('title', 'Mata Pelajaran')
@section('content')
@include('component.admin.ms-style')
<style>
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

    .filter-lomba-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        flex-shrink: 1;
        min-width: 0;
    }

    .filter-lomba-wrap .filter-icon-prepend {
        position: absolute;
        left: 12px;
        color: #94a3b8;
        font-size: 12px;
        z-index: 2;
        pointer-events: none;
    }

    .filter-lomba-wrap .form-select {
        padding-left: 32px;
        padding-right: 32px;
        height: 34px;
        font-size: 12px;
        border-radius: 10px;
        border: 1.5px solid var(--ms-border);
        background-color: #f8fafc;
        color: var(--ms-text);
        cursor: pointer;
        appearance: none;
    }

    .filter-lomba-wrap .form-select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        outline: none;
        background-color: #fff;
    }

    .dt-toolbar {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .dt-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        overflow: hidden;
    }

    .dt-length-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        font-size: 12px;
        color: var(--ms-text-soft);
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

    .pagination {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
        margin-top: 12px;
    }

    .pagination .page-item .page-link {
        border: 1px solid var(--ms-border);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        background: #fff;
        text-decoration: none;
        transition: all .2s;
    }

    .pagination .page-item .page-link:hover {
        border-color: var(--ms-primary);
        color: var(--ms-primary);
        background: var(--ms-primary-light);
    }

    .pagination .page-item.active .page-link {
        background: var(--ms-primary);
        border-color: var(--ms-primary);
        color: #fff;
        box-shadow: 0 2px 6px rgba(22, 163, 74, .25);
    }

    .pagination .page-item.disabled .page-link {
        opacity: .4;
        cursor: default;
        pointer-events: none;
        background: #f8fafc;
    }

    @media (max-width: 575.98px) {
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
</style>

<div class="master-siswa-page">

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

                {{-- Left: Icon + Title + Badges --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Data Mata Pelajaran
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-list me-1"></i>
                                {{ $mapel->total() }} Mata Pelajaran
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Buttons --}}
                <div class="d-flex flex-wrap align-items-center gap-2">

                    <button class="btn btn-header-ms btn-simpan-ms btn-compact"
                        style="background:#e0f2fe;color:#0369a1;"
                        data-bs-toggle="modal"
                        data-bs-target="#modalImport">
                        <i class="fas fa-file-import"></i> Import
                    </button>

                    <a href="{{ route('mata-pelajaran.export') }}"
                        class="btn btn-header-ms btn-simpan-ms btn-compact"
                        style="background:#fef3c7;color:#92400e;">
                        <i class="fas fa-file-export"></i> Export
                    </a>

                    <button class="btn btn-header-ms btn-simpan-ms btn-compact"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah
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

            @if ($errors->any())
            <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan:
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- FILTER TOOLBAR --}}
            <form id="mapelFilter" method="GET" autocomplete="off">
                <div class="dt-toolbar">
                    <div class="dt-left">
                        <div class="dt-length-group">
                            <span style="font-size:12px;">Tampilkan</span>
                            <div class="filter-lomba-wrap" style="min-width:50px;">
                                <i class="fas fa-list-ol filter-icon-prepend"></i>
                                <select name="per_page" class="form-select" style="min-width:50px;font-size:11px;">
                                    @foreach ([10, 15, 25, 50, 100] as $opt)
                                    <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span style="font-size:12px;">data</span>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-layer-group filter-icon-prepend"></i>
                            <select name="jenjang_id" class="form-select">
                                <option value="">Semua Jenjang</option>
                                @foreach($jenjangs as $j)
                                <option value="{{ $j->id }}" {{ request('jenjang_id')==$j->id ? 'selected' : '' }}>
                                    {{ $j->nama_jenjang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-toggle-on filter-icon-prepend"></i>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('status')=='Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Nonaktif" {{ request('status')=='Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-bookmark filter-icon-prepend"></i>
                            <select name="kurikulum_id" class="form-select">
                                <option value="">Semua Kurikulum</option>
                                @foreach($kurikulums as $k)
                                <option value="{{ $k->id }}" {{ request('kurikulum_id')==$k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kurikulum }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="search" name="search" value="{{ request('search') }}" class="search-pill" placeholder="Cari nama / kode..." style="width:160px;min-width:120px;font-size:12px;">
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-ms" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Mata Pelajaran</th>
                            <th>Jenjang</th>
                            <th>Kurikulum</th>
                            <th>Kelompok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($mapel as $item)

                        <tr>
                            <td>{{ ($mapel->currentPage() - 1) * $mapel->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->kode_mapel }}</td>
                            <td>{{ $item->nama_mapel }}</td>
                            <td>{{ $item->jenjang->nama_jenjang ?? '-' }}</td>
                            <td>{{ $item->kurikulum->nama_kurikulum ?? '-' }}</td>
                            <td>{{ $item->kelompok }}</td>
                            <td>
                                @if($item->status == 'Aktif')
                                <span class="badge-status-ms aktif">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                                @else
                                <span class="badge-status-ms nonaktif">
                                    <i class="fas fa-times-circle"></i> Nonaktif
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group-ms">
                                    <button class="btn btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-hapus"
                                        data-nama="{{ $item->nama_mapel }}"
                                        data-kode="{{ $item->kode_mapel }}"
                                        data-kurikulum="{{ $item->kurikulum->nama_kurikulum ?? '-' }}"
                                        data-url="{{ route('mata-pelajaran.destroy', $item->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('mata-pelajaran.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                            <h5 class="modal-title text-white fw-bold">
                                                <i class="fas fa-edit me-1"></i> Edit Mata Pelajaran
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="edit_id" value="{{ $item->id }}">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Mata Pelajaran</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-book"></i></span>
                                                    <input type="text" name="nama_mapel" value="{{ $item->nama_mapel }}"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Jenjang</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                                                    <select name="jenjang_id" class="form-control">
                                                        <option value="">-- Pilih Jenjang --</option>
                                                        @foreach($jenjangs as $jenjang)
                                                        <option value="{{ $jenjang->id }}" {{ $item->jenjang_id == $jenjang->id ? 'selected' : '' }}>
                                                            {{ $jenjang->nama_jenjang }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kurikulum</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                                                    <select name="kurikulum_id" class="form-control">
                                                        @foreach($kurikulums as $kurikulum)
                                                        <option value="{{ $kurikulum->id }}" {{ $item->kurikulum_id == $kurikulum->id ? 'selected' : '' }}>
                                                            {{ $kurikulum->nama_kurikulum }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kelompok</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-tag"></i></span>
                                                    <select name="kelompok" class="form-control" required>
                                                        <option value="PAI" {{ $item->kelompok == 'PAI' ? 'selected' : '' }}>PAI</option>
                                                        <option value="Umum" {{ $item->kelompok == 'Umum' ? 'selected' : '' }}>Umum</option>
                                                        <option value="Muatan Lokal" {{ $item->kelompok == 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal</option>
                                                        <option value="Ekstrakurikuler" {{ $item->kelompok == 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Status</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-toggle-on"></i></span>
                                                    <select name="status" class="form-control">
                                                        <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="Nonaktif" {{ $item->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center">Belum ada data</td>
                        </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if($mapel->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3" style="font-size:13px;color:var(--ms-text-soft);">
                <span>Menampilkan {{ $mapel->firstItem() }} sampai {{ $mapel->lastItem() }} dari {{ $mapel->total() }} data</span>
                <nav>
                    {{ $mapel->links('pagination::bootstrap-4') }}
                </nav>
            </div>
            @endif

        </div>
    </div>

</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('mata-pelajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-book me-1"></i> Tambah Mata Pelajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mata Pelajaran</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-book"></i></span>
                            <input type="text" name="nama_mapel" class="form-control"
                                placeholder="Masukkan nama mata pelajaran" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenjang</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                            <select name="jenjang_id" class="form-control">
                                <option value="">-- Pilih Jenjang --</option>
                                @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}">{{ $jenjang->nama_jenjang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kurikulum</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                            <select name="kurikulum_id" class="form-control">
                                <option value="">-- Pilih Kurikulum --</option>
                                @foreach($kurikulums as $kurikulum)
                                <option value="{{ $kurikulum->id }}">{{ $kurikulum->nama_kurikulum }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelompok</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-tag"></i></span>
                            <select name="kelompok" class="form-control" required>
                                <option value="">-- Pilih Kelompok --</option>
                                <option value="PAI">PAI</option>
                                <option value="Umum">Umum</option>
                                <option value="Muatan Lokal">Muatan Lokal</option>
                                <option value="Ekstrakurikuler">Ekstrakurikuler</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-toggle-on"></i></span>
                            <select name="status" class="form-control" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Import --}}
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('mata-pelajaran.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-file-excel me-1"></i> Import Mata Pelajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-file-excel"></i></span>
                            <input type="file" name="file" class="form-control"
                                accept=".xlsx,.xls,.csv" required>
                        </div>
                    </div>
                    <div class="alert alert-info" style="border-radius: 10px;">
                        <i class="fas fa-info-circle me-1"></i>
                        Format kolom Excel:<br>
                        <strong>nama_mapel | kurikulum | kelompok | status</strong><br>
                        <small class="text-muted">Jenjang diisi manual setelah import</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload me-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    (function() {
        const form = document.getElementById('mapelFilter');
        if (!form) return;

        function applyFilter() {
            const params = new URLSearchParams();
            const data = new FormData(form);
            for (const [k, v] of data.entries()) {
                if (v) params.append(k, v);
            }
            window.location.search = params.toString();
        }

        let debounce;
        form.querySelectorAll('select').forEach(function(el) {
            el.addEventListener('change', applyFilter);
        });
        form.querySelectorAll('input[type="search"], input[type="text"]').forEach(function(el) {
            el.addEventListener('input', function() {
                clearTimeout(debounce);
                debounce = setTimeout(applyFilter, 350);
            });
        });
    })();

    $(document).ready(function() {
        $('.btn-hapus').on('click', function() {
            var nama = $(this).data('nama');
            var kode = $(this).data('kode');
            var kurikulum = $(this).data('kurikulum');
            var url = $(this).data('url');

            swal({
                title: 'Hapus Mata Pelajaran?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                dangerMode: true,
                buttons: ['Batal', 'Ya, Hapus']
            }).then(function(hapus) {
                if (hapus) {
                    $('<form>', {
                        method: 'POST',
                        action: url
                    }).append(
                        $('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        }),
                        $('<input>', {
                            type: 'hidden',
                            name: '_method',
                            value: 'DELETE'
                        })
                    ).appendTo('body').submit();
                }
            });
        });
    });
</script>
@endpush
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let editId = "{{ old('edit_id') }}";
        if (editId) {
            let modal = new bootstrap.Modal(
                document.getElementById("edit" + editId)
            );
            modal.show();
        } else if ("{{ $errors->any() ? 'true' : '' }}" === "true") {
            let modal = new bootstrap.Modal(
                document.getElementById("modalTambah")
            );
            modal.show();
        }
    });
</script>