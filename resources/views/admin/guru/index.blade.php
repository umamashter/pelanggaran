@extends('layouts.main')

@section('title','Master Guru')

@push('css')
@include('component.admin.ms-style')
<style>
    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')

<div class="master-siswa-page">

    {{-- ===== HEADER CARD ===== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-user-tie"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Master Guru</h4>
                    <span class="badge-modern badge-ta">
                        <i class="fas fa-address-book me-1"></i>
                        {{ $gurus->count() }} Guru
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2" style="flex-wrap:nowrap;">
                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-user-plus"></i> Tambah
                </button>
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

            <div class="table-responsive">
                <table id="guruTable" class="table table-ms display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Guru</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gurus as $guru)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $guru->kode_guru }}</td>
                            <td>{{ $guru->nama }}</td>
                            <td>{{ $guru->nip }}</td>
                            <td>{{ $guru->no_hp }}</td>
                            <td>
                                <div class="action-group-ms">
                                    <button type="button" class="btn btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $guru->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#hapus{{ $guru->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="edit{{ $guru->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('master-guru.update', $guru->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                            <h5 class="modal-title text-white fw-bold">
                                                <i class="fas fa-edit me-1"></i> Edit Guru
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Guru</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $guru->nama }}" readonly
                                                    style="background:#f1f5f9;border-radius:12px;">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">NIP</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-id-card"></i></span>
                                                    <input type="text" name="nip" value="{{ $guru->nip }}"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">No HP</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-phone"></i></span>
                                                    <input type="text" name="no_hp" value="{{ $guru->no_hp }}"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Alamat</label>
                                                <div class="input-group-cu">
                                                    <span class="input-group-cu-icon"><i class="fas fa-map-marker-alt"></i></span>
                                                    <textarea name="alamat" class="form-control">{{ $guru->alamat }}</textarea>
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

                        {{-- Modal Hapus --}}
                        <div class="modal fade" id="hapus{{ $guru->id }}" tabindex="-1">
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
                                        <h4 class="fw-bold mb-3">Hapus Guru?</h4>
                                        <p class="text-muted mb-4">Data yang dihapus tidak dapat dikembalikan.</p>
                                        <div class="card bg-light border-0 mb-4" style="border-radius: 12px;">
                                            <div class="card-body">
                                                <div class="fw-bold text-primary">{{ $guru->kode_guru }}</div>
                                                <div class="mt-1">{{ $guru->nama }}</div>
                                            </div>
                                        </div>
                                        <form action="{{ route('master-guru.destroy', $guru->id) }}" method="POST">
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
            <form action="{{ route('master-guru.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-plus me-1"></i> Tambah Guru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih User Guru</label>
                        <select name="user_id" class="form-select" required style="height:46px;border-radius:12px;">
                            <option value="">— Pilih User —</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIP</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="nip" class="form-control"
                                placeholder="Masukkan NIP">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No HP</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-phone"></i></span>
                            <input type="text" name="no_hp" class="form-control"
                                placeholder="Masukkan nomor HP">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea name="alamat" class="form-control"
                                placeholder="Masukkan alamat"></textarea>
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
        $('#guruTable').DataTable({
            pagingType: 'simple_numbers',
            responsive: true,
            processing: true,
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "language": {
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
            "columnDefs": [{
                "orderable": false,
                "targets": 5
            }]
        });

        $('#guruTable_filter input').attr('placeholder', 'Cari guru...');
    });
</script>
@endpush
