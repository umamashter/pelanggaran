@extends('layouts.main')
@section('title', 'Tahun Ajaran')

@section('content')
@include('component.admin.ms-style')
<style>
    .master-ta-page {
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

    .badge-status-ms.arsip {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-semester-ms {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
        margin-bottom: 4px;
    }

    .badge-semester-ms.aktif { background: #dcfce7; color: #16a34a; }
    .badge-semester-ms.nonaktif { background: #f1f5f9; color: #64748b; }
    .badge-semester-ms.ganjil { background: #dcfce7; color: #16a34a; }
    .badge-semester-ms.genap { background: #fef3c7; color: #d97706; }

    .btn-aksi-ta {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all .25s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-aksi-ta:hover { transform: translateY(-1px); }

    .btn-aksi-ta.btn-aktifkan { background: #dcfce7; color: #16a34a; }
    .btn-aksi-ta.btn-aktifkan:hover { background: #16a34a; color: #fff; box-shadow: 0 3px 10px rgba(22,163,74,.3); }

    .btn-aksi-ta.btn-edit-ta { background: #fffbeb; color: #d97706; }
    .btn-aksi-ta.btn-edit-ta:hover { background: #d97706; color: #fff; box-shadow: 0 3px 10px rgba(217,119,6,.3); }

    .btn-aksi-ta.btn-hapus-ta { background: #fef2f2; color: #dc2626; }
    .btn-aksi-ta.btn-hapus-ta:hover { background: #dc2626; color: #fff; box-shadow: 0 3px 10px rgba(220,38,38,.3); }

    .btn-aksi-ta.btn-aktif-disabled { background: #f0fdf4; color: #16a34a; cursor: default; opacity: .7; }

    .btn-aksi-ta.btn-ganti-semester { background: #eff6ff; color: #2563eb; }
    .btn-aksi-ta.btn-ganti-semester:hover { background: #2563eb; color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,.3); }

    .delete-icon-wrap {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(220,38,38,.08);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .delete-icon-wrap i {
        font-size: 2.5rem;
        color: #dc2626;
    }
</style>

<div class="master-ta-page">

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
                            Tahun Ajaran
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern badge-ta">
                                <i class="fas fa-list me-1"></i>
                                {{ $tahunAjaran->count() }} Tahun Ajaran
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Tambah --}}
                <div class="d-flex flex-wrap align-items-center gap-2">
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

            <div class="table-responsive">

                <table id="tahunAjaranTable" class="table table-ms display" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($tahunAjaran as $index => $item)

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->tahun_ajaran }}</td>
                            <td>
                                @if($item->status == 'Aktif')
                                <span class="badge-status-ms aktif">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                                @elseif($item->status == 'Arsip')
                                <span class="badge-status-ms arsip">
                                    <i class="fas fa-lock"></i> Terkunci
                                </span>
                                @else
                                <span class="badge-status-ms" style="background:#f1f5f9;color:#94a3b8;">
                                    <i class="fas fa-clock"></i> Tidak Aktif
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'Aktif')
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    <button class="btn-aksi-ta btn-aktif-disabled" disabled>
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </button>
                                    <a href="{{ route('semester.index') }}" class="btn-aksi-ta btn-ganti-semester">
                                        <i class="fas fa-exchange-alt"></i> Atur Semester
                                    </a>
                                </div>
                                @elseif($item->status == 'Arsip')
                                <span class="text-muted" style="font-size:12px;">
                                    <i class="fas fa-lock"></i> Terkunci
                                </span>
                                @else
                                <form action="{{ route('tahun-ajaran.aktifkan',$item->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-aksi-ta btn-aktifkan"
                                        onclick="return confirm('Aktifkan tahun ajaran {{ $item->tahun_ajaran }}?')">
                                        <i class="fas fa-check"></i> Aktifkan
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('tahun-ajaran.update',$item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                            <h5 class="modal-title text-white fw-bold">
                                                <i class="fas fa-edit me-1"></i> Edit Tahun Ajaran
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Tahun Mulai</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-calendar"></i></span>
                                                    <input type="number" name="tahun_mulai" class="form-control"
                                                        value="{{ explode('/', $item->tahun_ajaran)[0] }}"
                                                        placeholder="Contoh: 2025" min="2000" max="2100" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Hapus --}}
                        <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1">
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
                                        <h4 class="fw-bold mb-3">Hapus Tahun Ajaran?</h4>
                                        <p class="text-muted mb-4">Data yang dihapus tidak dapat dikembalikan.</p>
                                        <div class="card bg-light border-0 mb-4" style="border-radius: 12px;">
                                            <div class="card-body">
                                                <div class="fw-bold text-primary">{{ $item->tahun_ajaran }}</div>
                                            </div>
                                        </div>
                                        <form action="{{ route('tahun-ajaran.destroy',$item->id) }}" method="POST">
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

</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-plus me-1"></i> Tambah Tahun Ajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-calendar"></i></span>
                            <input type="number" name="tahun_mulai" class="form-control"
                                value="{{ date('Y') }}"
                                placeholder="Contoh: 2025" min="2000" max="2100" required>
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

@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        $('#tahunAjaranTable').DataTable({
            pagingType: 'simple_numbers',
            responsive: true,
            processing: true,
            pageLength: 5,
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
                targets: 3
            }]
        });

        $('#tahunAjaranTable_filter input').attr('placeholder', 'Cari tahun ajaran...');
    });
</script>

@endpush
