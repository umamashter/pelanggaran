@extends('layouts.main')
@section('title', 'Detail Aspek Penilaian')
@push('css')
<style>
    .detail-aspek-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 680px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .detail-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .detail-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .detail-card-body { padding: 24px 28px 28px; }
    .info-label { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .info-value { font-weight: 700; font-size: 16px; color: #1e293b; }
    .btn-cu { height: 40px; padding: 0 22px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 6px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
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
    html:not(.dark-mode) .delete-icon-wrap i { animation:deleteShake 3s ease-in-out infinite; }
    @keyframes deletePulse { 0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.15)} 50%{box-shadow:0 0 0 12px rgba(220,38,38,0)} }
    @keyframes deleteShake { 0%,100%{transform:rotate(0)} 2%{transform:rotate(8deg)} 4%{transform:rotate(-6deg)} 6%{transform:rotate(4deg)} 8%{transform:rotate(0)} }
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
        .detail-aspek-page { margin-top: 16px; padding: 0 12px; }
        .detail-card-header, .detail-card-body { padding: 18px 20px; }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="detail-aspek-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('aspek-penilaian.index') }}">Aspek Penilaian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="card detail-card">
        <div class="detail-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Detail Aspek Penilaian</h4>
                    <span style="font-size: 13px; color: #64748b;">Lomba: <strong>{{ $lomba->nama ?? '-' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="detail-card-body">

            <div class="row g-4 mb-4">
                <div class="col-sm-6">
                    <div class="info-label">Lomba</div>
                    <div class="info-value">{{ $lomba->nama ?? '-' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="info-label">Jumlah Aspek</div>
                    <div class="info-value">{{ $aspekPenilaians->count() }}</div>
                </div>
            </div>

            <div class="info-label mb-2">Daftar Aspek</div>
            <div class="table-responsive" style="border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                <table class="table table-bordered mb-0" style="font-size:13px;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="width:40px;text-align:center;padding:10px 6px;">No</th>
                            <th style="padding:10px 10px;">Nama Aspek</th>
                            <th style="width:100px;text-align:center;padding:10px 6px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aspekPenilaians as $idx => $a)
                        <tr>
                            <td style="text-align:center;vertical-align:middle;padding:10px 6px;">{{ $idx + 1 }}</td>
                            <td style="vertical-align:middle;padding:10px 10px;">
                                <span class="fw-semibold" style="color:#1e293b;">{{ $a->nama_aspek }}</span>
                            </td>
                            <td style="text-align:center;vertical-align:middle;padding:10px 6px;">
                                @if($a->is_haflah_selesai)
                                <span class="btn btn-outline-secondary btn-sm" title="Haflah selesai - terkunci" style="cursor:not-allowed;opacity:.5;pointer-events:none;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                @else
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('aspek-penilaian.edit', $a->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#hapusModal"
                                        data-nama="{{ $a->nama_aspek }}"
                                        data-url="{{ route('aspek-penilaian.destroy', $a->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:16px;color:#94a3b8;">Belum ada aspek</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('aspek-penilaian.index') }}" class="btn btn-cu btn-cu-secondary mt-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

        </div>
    </div>

</div>

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
                <h5 class="fw-bold mt-3 mb-2">Hapus Aspek?</h5>
                <p class="text-muted mb-3" style="font-size:14px">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="delete-info-box text-start">
                    <div class="delete-label">Aspek</div>
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
@endsection

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
        var cardBody = document.querySelector('.detail-card-body');
        if (cardBody && hapusModal.parentNode !== cardBody) {
            cardBody.appendChild(hapusModal);
        }
    });
});
</script>
@endpush
