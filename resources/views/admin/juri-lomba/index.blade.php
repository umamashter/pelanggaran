@extends('layouts.main')
@section('title', 'Juri Lomba')
@section('content')
@include('component.admin.ms-style')
<style>
    .btn-header-ms.btn-tambah-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }

    /* ============================================================
       MODAL HAPUS
       ============================================================ */
    .modal-header-custom { padding:18px 24px; border-bottom:none; }
    .modal-body-custom { padding:16px 24px 20px; }

    html:not(.dark-mode) .modal-content {
        border:none; border-radius:20px;
        box-shadow:0 24px 80px rgba(0,0,0,.15); overflow:hidden;
    }
    html.dark-mode .modal-content {
        background:#0d2f38 !important;
        border:2px solid #175265 !important;
        border-radius:24px !important;
        box-shadow:0 30px 70px -16px rgba(0,0,0,.7) !important;
        backdrop-filter:none !important;
        -webkit-backdrop-filter:none !important;
    }
    html.dark-mode .table-card { border:2px solid #175265 !important; }

    html:not(.dark-mode) .modal.fade .modal-dialog {
        transform:scale(0.92) translateY(16px);
        transition:transform .3s cubic-bezier(.2,.8,.2,1);
    }
    html:not(.dark-mode) .modal.show .modal-dialog { transform:scale(1) translateY(0); }

    .delete-icon-wrap { width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:4px; }
    html:not(.dark-mode) .delete-icon-wrap { background:linear-gradient(135deg,#fef2f2,#fee2e2); animation:deletePulse 2s ease-in-out infinite; }
    html.dark-mode .delete-icon-wrap { background:rgba(220,38,38,.15); box-shadow:0 0 20px rgba(220,38,38,.1); }
    .delete-icon-wrap i { font-size:32px; color:#dc2626; }
    html:not(.dark-mode) .delete-icon-wrap i { animation:deleteShake 3s ease-in-out infinite; }
    @keyframes deletePulse { 0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.15)} 50%{box-shadow:0 0 0 12px rgba(220,38,38,0)} }
    @keyframes deleteShake { 0%,100%{transform:rotate(0)} 2%{transform:rotate(8deg)} 4%{transform:rotate(-6deg)} 6%{transform:rotate(4deg)} 8%{transform:rotate(0)} }

    .delete-info-box { border-left:4px solid #dc2626; border-radius:12px; padding:14px 18px; }
    html:not(.dark-mode) .delete-info-box { background:linear-gradient(135deg,#f8fafc,#f1f5f9); border:1px solid #e2e8f0; }
    html.dark-mode .delete-info-box { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); }
    .delete-info-box .delete-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:2px; }
    .delete-info-box .delete-value { font-weight:700; font-size:16px; }
    html:not(.dark-mode) .delete-info-box .delete-value { color:var(--ms-text); }
    html.dark-mode .delete-info-box .delete-value { color:var(--text-primary); }

    .btn-delete-final { border:none !important; border-radius:10px !important; padding:9px 22px !important; font-weight:600 !important; font-size:13px !important; transition:all .25s !important; }
    html:not(.dark-mode) .btn-delete-final { background:linear-gradient(135deg,#dc2626,#b91c1c) !important; color:#fff !important; box-shadow:0 4px 14px rgba(220,38,38,.3) !important; }
    html.dark-mode .btn-delete-final { background:linear-gradient(135deg,#dc2626,#b91c1c) !important; color:#fff !important; box-shadow:0 4px 14px rgba(220,38,38,.4) !important; }
    .btn-delete-final:hover { transform:translateY(-2px) !important; }
    html:not(.dark-mode) .btn-delete-final:hover { box-shadow:0 8px 24px rgba(220,38,38,.4) !important; }
    html.dark-mode .btn-delete-final:hover { box-shadow:0 8px 24px rgba(220,38,38,.5) !important; }
    .btn-delete-final:active { transform:translateY(0) !important; }

    .btn-cancel-modal { border:none !important; border-radius:10px !important; padding:9px 22px !important; font-weight:600 !important; font-size:13px !important; transition:all .25s !important; }
    html:not(.dark-mode) .btn-cancel-modal { background:#f1f5f9 !important; color:#475569 !important; }
    html:not(.dark-mode) .btn-cancel-modal:hover { background:#e2e8f0 !important; color:#1e293b !important; }
    html.dark-mode .btn-cancel-modal { background:rgba(255,255,255,.08) !important; color:var(--text-secondary) !important; }
    html.dark-mode .btn-cancel-modal:hover { background:rgba(255,255,255,.14) !important; color:var(--text-primary) !important; }
    .btn-cancel-modal:hover { transform:translateY(-1px); }

    @media (max-width: 768px) {
        .card.border-0.shadow-sm.mb-4 .btn-compact {
            width: fit-content;
            align-self: flex-start;
        }

        #table_juri_lomba_wrapper > .row:first-child {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        #table_juri_lomba_wrapper > .row:first-child > div {
            width: 100% !important;
            flex: none !important;
            max-width: 100% !important;
            padding: 0 !important;
        }

        #table_juri_lomba_wrapper .dataTables_length,
        #table_juri_lomba_wrapper .dataTables_filter {
            float: none !important;
            width: 100%;
            text-align: left;
        }

        #table_juri_lomba_wrapper .dataTables_length label,
        #table_juri_lomba_wrapper .dataTables_filter label {
            font-size: 10px;
            display: flex;
            align-items: center;
            gap: 4px;
            width: 100%;
        }

        #table_juri_lomba_wrapper .dataTables_length select {
            height: 28px !important;
            font-size: 10px !important;
            padding: 0 16px 0 8px !important;
            box-sizing: border-box !important;
        }

        #table_juri_lomba_wrapper .dataTables_filter input {
            height: 28px !important;
            font-size: 10px !important;
            padding: 0 16px 0 8px !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }
    }
</style>
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-gavel"></i></div>
                    <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Juri Lomba</h4></div>
                </div>
                <div><a href="{{ route('juri-lomba.create') }}" class="btn btn-header-ms btn-tambah-ms btn-compact"><i class="fas fa-plus"></i> Tambah</a></div>
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
            <div class="table-responsive">
                <table id="table_juri_lomba" class="table table-ms display">
                    <thead><tr><th>No</th><th>Lomba</th><th>Nama Juri</th><th>Jumlah Juri</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($juriLombas as $juriLomba)
                        <tr>
                            <td>{{ ($juriLombas->currentPage() - 1) * $juriLombas->perPage() + $loop->iteration }}</td>
                            <td>{{ $juriLomba->lomba->nama ?? '-' }}</td>
                            <td>{{ $juriLomba->nama_juri ?: '-' }}</td>
                            <td>{{ $juriLomba->jumlah_juri }}</td>
                            <td>
                                <div class="action-group-ms">
                                    <a href="{{ route('juri-lomba.show', $juriLomba->latest_id) }}" class="btn btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>
        @if($juriLomba->is_haflah_selesai || $juriLomba->penilaian_count > 0)
        <span class="btn btn-outline-secondary" title="{{ $juriLomba->penilaian_count > 0 ? 'Juri sudah melakukan penilaian - tidak dapat diubah' : 'Haflah selesai - terkunci' }}" style="cursor:not-allowed;opacity:.5;">
            <i class="fas {{ $juriLomba->penilaian_count > 0 ? 'fa-ban' : 'fa-lock' }}"></i>
        </span>
        @else
        <a href="{{ route('juri-lomba.edit', $juriLomba->latest_id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
        <button type="button" class="btn btn-outline-danger" title="Hapus"
            data-bs-toggle="modal" data-bs-target="#hapusModal"
            data-nama="{{ $juriLomba->lomba->nama ?? '-' }}"
            data-url="{{ route('juri-lomba.destroy', $juriLomba->latest_id) }}">
            <i class="fas fa-trash"></i>
        </button>
        @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- MODAL HAPUS --}}
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
                <h5 class="fw-bold mt-3 mb-2">Hapus Juri?</h5>
                <p class="text-muted mb-3" style="font-size:14px">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="delete-info-box text-start">
                    <div class="delete-label">Juri</div>
                    <div class="delete-value" id="hapusNama"></div>
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
        var nama = button.getAttribute('data-nama');
        var url = button.getAttribute('data-url');

        document.getElementById('hapusNama').textContent = nama;
        document.getElementById('hapusForm').action = url;

        if (hapusModal.parentNode !== document.body) {
            document.body.appendChild(hapusModal);
        }
    });

    hapusModal.addEventListener('hidden.bs.modal', function() {
        var cardBody = document.querySelector('.card-body');
        if (cardBody && hapusModal.parentNode !== cardBody) {
            cardBody.appendChild(hapusModal);
        }
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#table_juri_lomba').DataTable({
            pagingType: 'simple_numbers', responsive: true, processing: true, pageLength: 10,
            "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                paginate: { first: '«', previous: '‹', next: '›', last: '»' },
                aria: { paginate: { first: 'First', previous: 'Previous', next: 'Next', last: 'Last' } } }
        });
        $('#table_juri_lomba_filter input').attr('placeholder', 'Cari...');
    });
</script>
@endpush
