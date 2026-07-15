@extends('layouts.main')

@section('title', 'Master Kelas')

@section('content')

@include('component.admin.ms-style')
<style>
    .master-kelas-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        margin-top: 22px;
    }

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

    .header-stats {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: var(--ms-primary-light);
        color: var(--ms-primary-dark);
    }

    .filter-select-group {
        position: relative;
    }

    .filter-select-group .filter-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
        pointer-events: none;
        font-size: 13px;
    }

    .filter-select-group .form-select {
        padding-left: 38px;
        border-radius: 12px;
        border: 1.5px solid var(--ms-border);
        font-size: 13px;
        height: 42px;
        background-color: #fff;
        transition: all .25s;
        min-width: 190px;
        color: var(--ms-text);
        cursor: pointer;
    }

    .filter-select-group .form-select:focus {
        border-color: var(--ms-primary);
        box-shadow: 0 0 0 4px rgba(22,163,74,.12);
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

    .kelas-name-text {
        font-weight: 600;
        color: var(--ms-text);
    }

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

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 3px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
    }

    .count-badge.has-students {
        background: #f0fdf4;
        color: #16a34a;
    }

    .form-label-custom {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .delete-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 4px;
    }

    .delete-icon-wrap i {
        font-size: 32px;
        color: #dc2626;
    }

    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 24px 80px rgba(0,0,0,.15);
        overflow: hidden;
    }

    .modal-header-custom {
        padding: 18px 24px;
        border-bottom: none;
    }

    .modal-header-custom .modal-title {
        font-size: 16px;
        font-weight: 700;
    }

    .modal-body-custom {
        padding: 16px 24px 20px;
    }

    .modal-footer-custom {
        padding: 14px 24px;
        border-top: 1px solid #f1f5f9;
        gap: 8px;
    }

    .modal-footer-custom .btn {
        border-radius: 10px;
        font-size: 13px;
        padding: 9px 22px;
        font-weight: 600;
        transition: all .25s;
    }

    .modal-footer-custom .btn-secondary {
        background: #f1f5f9;
        border: none;
        color: #475569;
    }

    .modal-footer-custom .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }
</style>

<div class="master-kelas-page">

    {{-- ===== HEADER ===== --}}
    <div class="header-card-modern">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-wrap">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div>
                    <div class="header-title">Master Kelas</div>
                    <div class="header-sub">Kelola data kelas untuk semua jenjang</div>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2 position-relative" style="z-index:1">
                <div class="header-stats">
                    <span class="header-badge">
                        <i class="fas fa-school"></i>
                        {{ $kelas->count() }} Kelas
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TABLE CARD ===== --}}
    <div class="card table-card-modern">
        <div class="card-body">

            {{-- Toolbar --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="filter-select-group">
                    <i class="fas fa-filter filter-icon"></i>
                    <select id="filterJenjang" class="form-select">
                        <option value="">Semua Jenjang</option>
                        <option value="MI">MI</option>
                        <option value="MTs">MTs</option>
                        <option value="MA">MA</option>
                    </select>
                </div>

                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Kelas
                </button>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-1"></i>
                @foreach($errors->all() as $err)
                {{ $err }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive">
                <table id="kelasTable" class="table table-ms display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Jenjang</th>
                            <th>Tingkat</th>
                            <th>Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="kelas-name-text">{{ $k->nama_kelas }}</span>
                            </td>
                            <td>
                                @php $kode = $k->jenjang->kode ?? ''; @endphp
                                <span class="badge-jenjang {{ strtolower($kode) }}">
                                    <span class="badge-dot"></span>
                                    {{ $kode }}
                                </span>
                            </td>
                            <td>
                                <span>{{ $k->tingkat }}</span>
                            </td>
                            <td>
                                @php $count = $k->siswaAktif->count(); @endphp
                                <span class="count-badge {{ $count > 0 ? 'has-students' : '' }}">
                                    <i class="fas fa-user-graduate me-1" style="font-size:10px"></i>
                                    {{ $count }}
                                </span>
                            </td>
                            <td>
                                <div class="action-group-ms">
                                    <a href="{{ route('kelas.show', $k->id) }}" class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $k->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                        data-bs-toggle="modal"
                                        data-bs-target="#hapus{{ $k->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('kelas.update',$k->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $k->id }}">
                                        <div class="modal-header-custom" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                            <h5 class="modal-title text-white">
                                                <i class="fas fa-edit me-1"></i> Edit Kelas
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body-custom">
                                            <div class="mb-3">
                                                <label class="form-label-custom">Nama Kelas</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-chalkboard"></i></span>
                                                    <input type="text" name="nama_kelas" class="form-control"
                                                        value="{{ old('id') == $k->id ? old('nama_kelas') : $k->nama_kelas }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label-custom">Jenjang</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                                                    <select name="jenjang_id" class="form-control" required>
                                                        <option value="">Pilih Jenjang</option>
                                                        @foreach($jenjangs as $jenjang)
                                                        <option value="{{ $jenjang->id }}" {{ old('id') == $k->id ? (old('jenjang_id') == $jenjang->id ? 'selected' : '') : ($k->jenjang_id == $jenjang->id ? 'selected' : '') }}>
                                                            {{ $jenjang->kode }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label-custom">Tingkat</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-sort-numeric-up"></i></span>
                                                    <input type="number" name="tingkat" class="form-control"
                                                        value="{{ old('id') == $k->id ? old('tingkat') : $k->tingkat }}" min="1" max="12" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer-custom">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn" style="background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;border:none">
                                                <i class="fas fa-save me-1"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL HAPUS --}}
                        <div class="modal fade" id="hapus{{ $k->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header-custom border-0 pb-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body-custom text-center px-4">
                                        <div class="delete-icon-wrap">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                        <h5 class="fw-bold mt-3 mb-2">Hapus Kelas?</h5>
                                        <p class="text-muted mb-3" style="font-size:14px">Data yang dihapus tidak dapat dikembalikan.</p>
                                        <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0">
                                            <span class="fw-bold" style="color:var(--ms-text);font-size:16px">
                                                {{ $k->tingkat }}{{ $k->nama_kelas }}
                                            </span>
                                        </div>
                                        <form action="{{ route('kelas.destroy',$k->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border:none;border-radius:10px;padding:9px 22px;font-weight:600;font-size:13px">
                                                    <i class="fas fa-trash me-1"></i> Ya, Hapus
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-chalkboard" style="font-size:48px;color:#cbd5e1;margin-bottom:16px;display:block"></i>
                                    <h5 style="color:#94a3b8;font-weight:600">Belum ada kelas</h5>
                                    <p style="color:#94a3b8;font-size:14px">Klik "Tambah Kelas" untuk menambahkan kelas baru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header-custom" style="background: linear-gradient(135deg, #16a34a, #15803d);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-plus me-1"></i> Tambah Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body-custom">
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Kelas</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-chalkboard"></i></span>
                            <input type="text" name="nama_kelas" class="form-control"
                                value="{{ old('nama_kelas') }}" placeholder="contoh: A, 1, Unggulan" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Jenjang</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                            <select name="jenjang_id" class="form-control" required>
                                <option value="">Pilih Jenjang</option>
                                @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}" {{ old('jenjang_id') == $jenjang->id ? 'selected' : '' }}>
                                    {{ $jenjang->kode }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label-custom">Tingkat</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-sort-numeric-up"></i></span>
                            <input type="number" name="tingkat" class="form-control"
                                value="{{ old('tingkat') }}" placeholder="contoh: 1, 7, 10" min="1" max="12" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let table = $('#kelasTable').DataTable({
            pagingType: 'simple_numbers',
            responsive: true,
            processing: true,
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
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
                targets: 5
            }]
        });

        $('#filterJenjang').on('change', function() {
            table.column(2)
                .search(this.value)
                .draw();
        });

        $('#kelasTable_filter input').attr('placeholder', 'Cari kelas...');
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let oldId = "{{ old('id') }}";
        if (oldId) {
            let modal = new bootstrap.Modal(
                document.getElementById('modalEdit' + oldId)
            );
            modal.show();
        } else if ("{{ $errors->any() ? 'true' : '' }}" === "true") {
            let modal = new bootstrap.Modal(
                document.getElementById('modalTambah')
            );
            modal.show();
        }
    });
</script>
@endpush