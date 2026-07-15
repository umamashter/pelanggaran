@extends('layouts.main')
@section('title', 'Anggota Kelompok')
@section('content')
@include('component.admin.ms-style')
<style>
    .badge-jumlah {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        background: #f0fdf4;
        color: #16a34a;
        white-space: nowrap;
    }
    .filter-lomba-wrap {
        position: relative;
    }
    .filter-lomba-wrap .form-select {
        height: 34px;
        border-radius: 18px;
        border: 1.5px solid #e2e8f0;
        font-size: 12px;
        padding: 0 30px 0 34px;
        background-color: #f8fafc;
        color: #475569;
        min-width: 150px;
        cursor: pointer;
        transition: all .25s;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 12px;
    }
    .filter-lomba-wrap .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
        background-color: #fff;
    }
    .filter-lomba-wrap .filter-icon-prepend {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
        z-index: 1;
    }
    .filter-lomba-wrap .form-select:hover {
        border-color: transparent !important;
        background-color: #f8fafc;
    }
    .btn-simpan-ms.btn-compact {
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        border-radius: 8px;
    }
    #table_anggota_kelompok_wrapper .dt-toolbar {
        display: flex !important;
        align-items: center;
        flex-wrap: nowrap;
        gap: 10px;
        margin: 0 0 16px;
    }
    #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_length,
    #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_filter,
    #table_anggota_kelompok_wrapper .dt-toolbar #kelompokFilter {
        display: flex !important;
        align-items: center;
        flex-wrap: nowrap;
        float: none !important;
        margin: 0 !important;
        width: auto !important;
    }
    #table_anggota_kelompok_wrapper .dt-toolbar #kelompokFilter {
        display: flex !important;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        margin: 0 !important;
        float: none !important;
        width: auto !important;
    }
    #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_filter {
        margin-left: auto !important;
    }
    #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_filter input {
        width: 180px !important;
        min-width: 180px !important;
    }

    @media (max-width: 768px) {
        .card.border-0.shadow-sm.mb-4 .d-flex.align-items-center.gap-2[style*="flex-wrap:nowrap"] {
            align-self: flex-start;
            width: fit-content;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            align-items: center;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar #kelompokFilter {
            display: contents !important;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar #kelompokFilter .filter-lomba-wrap {
            min-width: 0 !important;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar #kelompokFilter .filter-lomba-wrap .filter-icon-prepend {
            display: none;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar #kelompokFilter .filter-lomba-wrap .form-select,
        #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_length select,
        #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_filter input {
            width: 100% !important;
            min-width: 0 !important;
            height: 28px !important;
            font-size: 10px !important;
            padding: 0 16px 0 8px !important;
            box-sizing: border-box !important;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_length label,
        #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_filter label {
            font-size: 10px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 2px;
            width: 100%;
        }

        #table_anggota_kelompok_wrapper .dt-toolbar .dataTables_filter {
            margin-left: 0 !important;
            width: 100%;
        }
    }

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
</style>
<div class="master-siswa-page">

    {{-- HEADER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Anggota Kelompok</h4>
                        <span style="font-size: 13px; color: #64748b;">Kelola anggota setiap kelompok lomba</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2" style="flex-wrap:nowrap;">
                    <a href="{{ route('kelompok-lomba.print-preview', request()->query()) }}" target="_blank" rel="noopener" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;">
                        <i class="fas fa-print"></i> PDF
                    </a>
                    <a href="{{ route('anggota-kelompok.create') }}" class="btn btn-header-ms btn-simpan-ms btn-compact">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form id="kelompokFilter" method="GET" action="{{ route('anggota-kelompok.index') }}" class="m-0" style="display:none;">
        <div class="filter-lomba-wrap" style="flex-shrink:1;min-width:0;">
            <i class="fas fa-calendar-alt filter-icon-prepend"></i>
            <select name="haflah_id" class="form-select" style="width:110px;font-size:11px;" onchange="this.form.submit()">
                <option value="">Pilih Haflah</option>
                @foreach($haflatuls as $h)
                <option value="{{ $h->id }}" {{ request('haflah_id') == $h->id ? 'selected' : '' }}>{{ $h->nama_acara }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-lomba-wrap" style="flex-shrink:1;min-width:0;">
            <i class="fas fa-trophy filter-icon-prepend"></i>
            <select name="lomba_id" class="form-select" style="width:140px;font-size:11px;" onchange="this.form.submit()">
                <option value="">Pilih Lomba</option>
                @foreach($lombas as $l)
                <option value="{{ $l->id }}" {{ request('lomba_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                @endforeach
            </select>
        </div>
    </form>

    @if(session('success'))
    <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- MAIN TABLE CARD --}}
    <div class="card table-card">
        <div class="card-body">
            <table id="table_anggota_kelompok" class="table table-ms display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelompok</th>
                        <th>Lomba</th>
                        <th>Sesi</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelompoks as $kelompok)
                    @php $firstAnggota = $kelompok->anggota->first(); @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex-shrink:0;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span class="fw-semibold" style="color:#1e293b;">{{ $kelompok->nama_kelompok }}</span>
                            </div>
                        </td>
                        <td>{{ $kelompok->lomba->nama ?? '-' }}</td>
                        <td>{{ $kelompok->lomba->sesiLomba->nama ?? '-' }}</td>
                        <td>{{ !empty($kelompok->lomba->sesiLomba->tanggal) ? \Carbon\Carbon::parse($kelompok->lomba->sesiLomba->tanggal)->translatedFormat('d M Y') : '-' }}</td>
                        <td>
                            @php $l = $kelompok->lomba; @endphp
                            @if($l && $l->kelas_min && $l->kelas_max)
                            <span class="badge" style="background:#c2410c;color:#fff;">Kelas {{ $l->kelas_min == $l->kelas_max ? $l->kelas_min : $l->kelas_min.' - '.$l->kelas_max }}</span>
                            @else
                            <span class="badge" style="background:#16a34a;color:#fff;">Semua Kelas</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-jumlah">
                                <i class="fas fa-user-graduate"></i>
                                {{ $kelompok->anggota_count }} siswa
                            </span>
                        </td>
                        <td>
                            <div class="action-group-ms">
                                @if($firstAnggota)
                                <a href="{{ route('kelompok-lomba.show', $kelompok->id) }}" class="btn btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>
                                @if($kelompok->is_haflah_selesai || $kelompok->penilaian_lombas_count > 0)
                                <span class="btn btn-outline-secondary" title="{{ $kelompok->penilaian_lombas_count > 0 ? 'Anggota sudah memiliki penilaian - tidak dapat diubah' : 'Haflah selesai - terkunci' }}" style="cursor:not-allowed;opacity:.5;">
                                    <i class="fas {{ $kelompok->penilaian_lombas_count > 0 ? 'fa-ban' : 'fa-lock' }}"></i>
                                </span>
                                @else
                                <a href="{{ route('anggota-kelompok.edit', $firstAnggota->id) }}" class="btn btn-outline-warning" title="Kelola"><i class="fas fa-users-cog"></i></a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    data-bs-toggle="modal" data-bs-target="#hapusModal"
                                    data-nama="{{ $kelompok->nama_kelompok }}"
                                    data-url="{{ route('anggota-kelompok.hapus-semua', $kelompok->id) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding:48px 20px;color:#94a3b8;">
                            <i class="fas fa-user-friends d-block mb-2" style="font-size:32px;color:#cbd5e1;"></i>
                            Belum ada anggota kelompok
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

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
                <h5 class="fw-bold mt-3 mb-2">Hapus Anggota Kelompok?</h5>
                <p class="text-muted mb-3" style="font-size:14px">Semua anggota dalam kelompok ini akan dihapus. Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="delete-info-box text-start">
                    <div class="delete-label">Kelompok</div>
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
        var cardBody = document.querySelector('.card-body');
        if (cardBody && hapusModal.parentNode !== cardBody) {
            cardBody.appendChild(hapusModal);
        }
    });
});
</script>
<script>

$(document).ready(function() {
    $('#table_anggota_kelompok').DataTable({
        pagingType: 'simple_numbers', responsive: true, pageLength: 10,
        dom: '<"dt-toolbar"lf>rtip',
        "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
            paginate: { first: '«', previous: '‹', next: '›', last: '»' },
            aria: { paginate: { first: 'First', previous: 'Previous', next: 'Next', last: 'Last' } } },
        initComplete: function() {
            var $filter = $('#kelompokFilter');
            $('#table_anggota_kelompok_wrapper .dataTables_length').after($filter);
            $filter.show();
        }
    });
    $('#table_anggota_kelompok_filter input').attr('placeholder', 'Cari...');
});
</script>
@endpush
