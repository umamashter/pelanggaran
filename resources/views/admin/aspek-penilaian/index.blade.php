@extends('layouts.main')
@section('title', 'Aspek Penilaian')

@push('css')
<style>
    .pagination-ms { display:flex; justify-content:flex-end; }
    .pagination-ms .pagination { margin:0; gap:4px; }
    .pagination-ms .page-link {
        min-width:34px; height:34px; padding:0 10px; border-radius:8px;
        font-size:13px; font-weight:500; line-height:32px;
        color:#475569; background:#fff; border:1px solid var(--ms-border); box-shadow:none;
    }
    .pagination-ms .page-link:hover { border-color:var(--ms-primary); color:var(--ms-primary); background:var(--ms-primary-light); }
    .pagination-ms .page-item.active .page-link { background:var(--ms-primary); border-color:var(--ms-primary); color:#fff; box-shadow:0 2px 6px rgba(22,163,74,.25); }
    .pagination-ms .page-item.disabled .page-link { opacity:.4; background:#f8fafc; }
    .btn-header-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }

    /* ---- Filter pill */
    .filter-lomba-wrap { position: relative; }
    .filter-lomba-wrap .form-select {
        height: 34px; border-radius: 18px; border: 1.5px solid #e2e8f0;
        font-size: 12px; padding: 0 30px 0 34px; background-color: #f8fafc;
        color: #475569; min-width: 150px; cursor: pointer; transition: all .25s;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center; background-size: 12px;
    }
    .filter-lomba-wrap .form-select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .filter-lomba-wrap .filter-icon-prepend { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px; pointer-events: none; z-index: 1; }
    .filter-lomba-wrap .form-select:hover { border-color: #cbd5e1; background-color: #fff; }
    .dt-toolbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 14px; }
    .dt-left, .dt-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .dt-length-group { display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#64748b; }
    /* Override DataTable default float for length/filter */
    .dt-left .dataTables_length,
    .dt-left .dataTables_filter { float: none; }
    .dt-left .dataTables_length label,
    .dt-left .dataTables_filter label { display: flex; align-items: center; gap: 4px; margin: 0; font-weight: 400; color: #64748b; }
    .dt-left .dataTables_length select {
        height: 34px; border-radius: 18px; border: 1.5px solid #e2e8f0;
        font-size: 12px; padding: 0 28px 0 12px; background-color: #f8fafc;
        color: #475569; cursor: pointer; transition: all .25s;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 10px;
    }
    .dt-left .dataTables_length select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .dt-left .dataTables_filter input {
        height: 34px; border: 1.5px solid #e2e8f0; border-radius: 18px;
        font-size: 12px; padding: 0 14px 0 34px; background-color: #f8fafc;
        color: #475569; min-width: 200px; transition: all .25s; outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: 12px center; background-size: 14px;
    }
    .dt-left .dataTables_filter input:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .dt-left .dataTables_filter input::placeholder { color: #94a3b8; }
    .modal-header-custom { padding:18px 24px; border-bottom:none; }
    .modal-body-custom { padding:16px 24px 20px; }
    html:not(.dark-mode) .modal-content { border:none; border-radius:20px; box-shadow:0 24px 80px rgba(0,0,0,.15); overflow:hidden; }
    html.dark-mode .modal-content { background:#0d2f38 !important; border:2px solid #175265 !important; border-radius:24px !important; box-shadow:0 30px 70px -16px rgba(0,0,0,.7) !important; }
    html:not(.dark-mode) .modal.fade .modal-dialog { transform:scale(0.92) translateY(16px); transition:transform .3s cubic-bezier(.2,.8,.2,1); }
    html:not(.dark-mode) .modal.show .modal-dialog { transform:scale(1) translateY(0); }
    .delete-icon-wrap { width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:4px; }
    html:not(.dark-mode) .delete-icon-wrap { background:linear-gradient(135deg,#fef2f2,#fee2e2); animation:deletePulse 2s ease-in-out infinite; }
    html.dark-mode .delete-icon-wrap { background:rgba(220,38,38,.15); box-shadow:0 0 20px rgba(220,38,38,.1); }
    .delete-icon-wrap i { font-size:32px; color:#dc2626; }
    @keyframes deletePulse { 0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.15)} 50%{box-shadow:0 0 0 12px rgba(220,38,38,0)} }
    .delete-info-box { border-left:4px solid #dc2626; border-radius:12px; padding:14px 18px; }
    html:not(.dark-mode) .delete-info-box { background:linear-gradient(135deg,#f8fafc,#f1f5f9); border:1px solid #e2e8f0; }
    html.dark-mode .delete-info-box { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); }
    .delete-info-box .delete-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:2px; }
    .delete-info-box .delete-value { font-weight:700; font-size:16px; }
    html:not(.dark-mode) .delete-info-box .delete-value { color:#1e293b; }
    html.dark-mode .delete-info-box .delete-value { color:#e2e8f0; }
    .btn-delete-final { border:none !important; border-radius:10px !important; padding:9px 22px !important; font-weight:600 !important; font-size:13px !important; transition:all .25s !important; }
    html:not(.dark-mode) .btn-delete-final { background:linear-gradient(135deg,#dc2626,#b91c1c) !important; color:#fff !important; box-shadow:0 4px 14px rgba(220,38,38,.3) !important; }
    html.dark-mode .btn-delete-final { background:linear-gradient(135deg,#dc2626,#b91c1c) !important; color:#fff !important; box-shadow:0 4px 14px rgba(220,38,38,.4) !important; }
    .btn-delete-final:hover { transform:translateY(-2px) !important; }
    .btn-cancel-modal { border:none !important; border-radius:10px !important; padding:9px 22px !important; font-weight:600 !important; font-size:13px !important; transition:all .25s !important; }
    html:not(.dark-mode) .btn-cancel-modal { background:#f1f5f9 !important; color:#475569 !important; }
    html:not(.dark-mode) .btn-cancel-modal:hover { background:#e2e8f0 !important; color:#1e293b !important; }
    html.dark-mode .btn-cancel-modal { background:rgba(255,255,255,.08) !important; color:var(--text-secondary) !important; }
    html.dark-mode .btn-cancel-modal:hover { background:rgba(255,255,255,.14) !important; color:var(--text-primary) !important; }
    .btn-cancel-modal:hover { transform:translateY(-1px); }
    @media (max-width: 768px) {
        .dt-toolbar { width:100%; }
        .dt-left { width:100% !important; flex-wrap:wrap !important; }
        .dt-left > * { min-width:0; }
        .filter-lomba-wrap { width:100%; }
        .filter-lomba-wrap .form-select { width:100% !important; min-width:0 !important; }
        .dt-left .dataTables_filter input { width:100% !important; min-width:0 !important; }
    }
</style>
@endpush

@section('content')
@include('component.admin.ms-style')

<div class="master-siswa-page">

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Aspek Penilaian</h4>
                        <span style="font-size: 13px; color: #64748b;">Kelola aspek yang dinilai pada setiap lomba</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" id="btnCetakPdf" class="btn btn-header-ms btn-compact" style="background:#c2410c;color:#fff;">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                    <button type="button" id="btnExportExcel" class="btn btn-header-ms btn-compact" style="background:#16a34a;color:#fff;">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <a href="{{ route('aspek-penilaian.create') }}" class="btn btn-header-ms btn-simpan-ms btn-compact">
                        <i class="fas fa-plus"></i> Tambah Aspek
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card table-card">
        <div class="card-body">
            <form id="aspekFilter" autocomplete="off">
                <div class="dt-toolbar">
                    <div class="dt-left">
                        <div class="filter-lomba-wrap">
                            <i class="fas fa-trophy filter-icon-prepend"></i>
                            <select id="exportLomba" class="form-select" style="width:200px;">
                                <option value="">Pilih Lomba</option>
                                @foreach($lombas as $l)
                                <option value="{{ $l->id }}">{{ $l->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table id="table_aspek_penilaian" class="table table-ms">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lomba</th>
                            <th>Jumlah Aspek</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aspekPenilaians as $a)
                        <tr>
                            <td>{{ ($aspekPenilaians->currentPage() - 1) * $aspekPenilaians->perPage() + $loop->iteration }}</td>
                            <td>{{ $a->lomba->nama ?? '-' }}</td>
                            <td>
                                <span class="badge" style="background:#eff6ff;color:#2563eb;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:600;">{{ $a->jumlah_aspek }} aspek</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('aspek-penilaian.show', $a->lomba_id) }}" class="btn btn-outline-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                    @if($a->is_haflah_selesai)
                                    <span class="btn btn-outline-secondary btn-sm" title="Terkunci" style="cursor:not-allowed;opacity:.5;pointer-events:none;"><i class="fas fa-lock"></i></span>
                                    @else
                                    <a href="{{ route('aspek-penilaian.edit', $a->latest_id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#hapusModal"
                                        data-nama="{{ $a->lomba->nama ?? '-' }}"
                                        data-jumlah="{{ $a->jumlah_aspek }}"
                                        data-url="{{ route('aspek-penilaian.hapus-semua', $a->lomba_id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding:48px 20px;color:#94a3b8;">
                                <i class="fas fa-clipboard-list d-block mb-2" style="font-size:32px;color:#cbd5e1;"></i>
                                Belum ada aspek penilaian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection

<div class="modal fade" id="hapusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header-custom border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom text-center px-4">
                <div class="delete-icon-wrap">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 class="fw-bold mt-3 mb-2">Hapus Semua Aspek?</h5>
                <p class="text-muted mb-3" style="font-size:14px;">Semua aspek pada lomba ini akan dihapus.</p>
                <div class="delete-info-box text-start">
                    <div class="delete-label">Lomba</div>
                    <div class="delete-value" id="hapusNama"></div>
                    <div class="delete-label mt-2">Jumlah Aspek</div>
                    <div class="delete-value" id="hapusJumlah"></div>
                </div>
                <form id="hapusForm" action="" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-delete-final">
                            <i class="fas fa-trash me-1"></i> Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var hapusModal = document.getElementById('hapusModal');
    if (!hapusModal) return;

    hapusModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        document.getElementById('hapusNama').textContent = button.getAttribute('data-nama');
        document.getElementById('hapusJumlah').textContent = button.getAttribute('data-jumlah') + ' aspek';
        document.getElementById('hapusForm').action = button.getAttribute('data-url');
        if (hapusModal.parentNode !== document.body) document.body.appendChild(hapusModal);
    });

    hapusModal.addEventListener('hidden.bs.modal', function() {
        var cardBody = document.querySelector('.card-body');
        if (cardBody && hapusModal.parentNode !== cardBody) cardBody.appendChild(hapusModal);
    });
});
</script>
<script>
    $(document).ready(function() {
        var $exportLomba = $('#exportLomba');

        $('#btnCetakPdf').on('click', function () {
            var lombaId = $exportLomba.val();
            if (!lombaId) {
                alert('Pilih lomba terlebih dahulu.');
                return;
            }
            window.location.href = '{{ url("/aspek-penilaian/cetak-pdf") }}/' + lombaId;
        });

        $('#btnExportExcel').on('click', function () {
            var lombaId = $exportLomba.val();
            if (!lombaId) {
                alert('Pilih lomba terlebih dahulu.');
                return;
            }
            window.location.href = '{{ url("/aspek-penilaian/export-excel") }}/' + lombaId;
        });
    });
</script>
@endpush
