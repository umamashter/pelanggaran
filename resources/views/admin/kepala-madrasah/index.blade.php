@extends('layouts.main')
@section('title', 'Kepala Madrasah')
@push('css')
@include('component.admin.ms-style')
<style>
    .badge-ta { background: #eff6ff; color: #1d4ed8; }
    .badge-jenjang { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; background: #f0fdf4; color: #16a34a; }
    .ms-search-box { position: relative; }
    .ms-search-box .ms-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; pointer-events: none; }
    .ms-search-box .ms-search-input { height: 32px; width: 240px; border: 1.5px solid var(--ms-border); border-radius: 10px; background: #f8fafc; color: var(--ms-text); font-size: 12px; padding: 0 12px 0 34px; outline: none; transition: all .2s; }
    .ms-search-box .ms-search-input:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.08); background: #fff; }
    @media (max-width: 575.98px) {
        .action-group-ms { display: inline-flex !important; gap: 4px !important; grid-template-columns: unset !important; }
        .action-group-ms .btn { width: 28px !important; height: 28px !important; font-size: 11px !important; }
    }
</style>
@endpush

@section('content')
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-user-shield"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Kepala Madrasah</h4>
                    <span class="badge-modern badge-ta"><i class="fas fa-list me-1"></i>{{ $kepalaMadrasah->count() }} Penugasan</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2" style="flex-wrap:nowrap;">
                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
    </div>

    <div class="card table-card" style="border:none;border-radius:18px;box-shadow:0 4px 16px rgba(0,0,0,.06),0 2px 8px rgba(0,0,0,.04);">
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <table id="kmTable" class="table table-ms display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th>Jenjang</th>
                        <th>Nama Kepala</th>
                        <th>Email</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kepalaMadrasah as $km)
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td><span class="badge-jenjang"><i class="fas fa-school"></i> {{ $km->jenjang->kode }} - {{ $km->jenjang->nama_jenjang }}</span></td>
                        <td>{{ $km->user->name ?? '-' }}</td>
                        <td>{{ $km->user->email ?? '-' }}</td>
                        <td style="text-align:center;">
                            <div class="action-group-ms">
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapus{{ $km->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="hapus{{ $km->id }}" tabindex="-1">
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
                                            <h4 class="fw-bold mb-3">Hapus Penugasan?</h4>
                                            <p class="text-muted mb-4">Kepala Madrasah untuk jenjang ini akan dihapus.</p>
                                            <div class="card bg-light border-0 mb-4" style="border-radius: 12px;">
                                                <div class="card-body">
                                                    <div class="fw-bold text-primary">{{ $km->jenjang->kode }} - {{ $km->jenjang->nama_jenjang }}</div>
                                                    <div class="mt-1">{{ $km->user->name ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <form action="{{ route('master-kepala-madrasah.destroy', $km->id) }}" method="POST">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('master-kepala-madrasah.store') }}" method="POST">
                    @csrf
                    <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                        <h5 class="modal-title text-white fw-bold">
                            <i class="fas fa-user-shield me-1"></i> Tambah Kepala Madrasah
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenjang</label>
                            <select name="jenjang_id" class="form-select" required>
                                <option value="">Pilih Jenjang</option>
                                @foreach($jenjangs as $j)
                                    @if(!$kepalaMadrasah->contains('jenjang_id', $j->id))
                                    <option value="{{ $j->id }}">{{ $j->kode }} - {{ $j->nama_jenjang }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">User (Role: Kepala Sekolah)</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Pilih User</option>
                                @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
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
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#kmTable').DataTable({
        pagingType: 'simple_numbers',
        responsive: false,
        scrollX: true,
        searching: false,
        lengthChange: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        columnDefs: [{ orderable: false, targets: 4 }],
        pageLength: 10,
        order: []
    });

    @if($errors->any() || session('error'))
    var modal = new bootstrap.Modal(document.getElementById('modalTambah'));
    modal.show();
    @endif
});
</script>
@endpush
