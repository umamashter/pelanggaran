@extends('layouts.main')
@section('title', 'Kategori Lomba')

@section('content')
@include('component.admin.ms-style')
<style>
    /* ---- Filter pill — model sama dengan login-history / anggota-kelompok ---- */
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

    .search-pill {
        height: 34px; border: 1.5px solid #e2e8f0; border-radius: 18px;
        font-size: 12px; padding: 0 16px 0 34px; background-color: #f8fafc;
        color: #475569; min-width: 240px; transition: all .25s; outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: 12px center; background-size: 14px;
    }
    .search-pill:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .search-pill::placeholder { color: #94a3b8; }

    .dt-toolbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 14px; }
    .dt-left, .dt-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .dt-length-group { display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#64748b; }

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

    .btn-header-ms.btn-simpan-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }
    @media (max-width: 768px) {
        .card-body.p-4.d-flex.flex-column.flex-xl-row.justify-content-between.align-items-xl-center.gap-3 > a.btn-header-ms {
            align-self: flex-start;
            width: fit-content;
        }

        #kategoriLombaFilter.dt-toolbar {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            align-items: center;
        }

        #kategoriLombaFilter .dt-left {
            display: contents;
        }

        #kategoriLombaFilter .dt-left .dt-length-group {
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        #kategoriLombaFilter .dt-left .dt-length-group span {
            font-size: 10px;
        }

        #kategoriLombaFilter .dt-left .dt-length-group .filter-lomba-wrap {
            min-width: 0 !important;
        }

        #kategoriLombaFilter .dt-left .dt-length-group .filter-lomba-wrap .filter-icon-prepend {
            display: none;
        }

        #kategoriLombaFilter .dt-left .dt-length-group .filter-lomba-wrap .form-select {
            width: 44px !important;
            min-width: 0 !important;
            padding: 0 16px 0 8px;
            font-size: 10px;
            height: 28px;
        }

        #kategoriLombaFilter .dt-left > .filter-lomba-wrap {
            min-width: 0 !important;
        }

        #kategoriLombaFilter .dt-left > .filter-lomba-wrap .filter-icon-prepend {
            display: none;
        }

        #kategoriLombaFilter .dt-left > .filter-lomba-wrap .form-select {
            width: 100% !important;
            min-width: 0 !important;
            font-size: 10px;
            padding: 0 16px 0 8px;
            height: 28px !important;
        }

        #kategoriLombaFilter .dt-right {
            grid-column: 1 / -1;
        }

        #kategoriLombaFilter .dt-right .search-pill {
            width: 100%;
            min-width: 0 !important;
            box-sizing: border-box;
            height: 28px !important;
            font-size: 10px !important;
            padding: 0 16px 0 8px !important;
        }
    }

    /* ============================================================
       MODAL HAPUS — premium styling
       ============================================================ */
    .modal-header-custom {
        padding: 18px 24px;
        border-bottom: none;
    }

    .modal-body-custom {
        padding: 16px 24px 20px;
    }

    html:not(.dark-mode) .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 24px 80px rgba(0,0,0,.15);
        overflow: hidden;
    }

    html.dark-mode .modal-content {
        background: #0d2f38 !important;
        border: 2px solid #175265 !important;
        border-radius: 24px !important;
        box-shadow: 0 30px 70px -16px rgba(0,0,0,.7) !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }

    html.dark-mode .table-card {
        border: 2px solid #175265 !important;
    }

    html:not(.dark-mode) .modal.fade .modal-dialog {
        transform: scale(0.92) translateY(16px);
        transition: transform .3s cubic-bezier(.2, .8, .2, 1);
    }

    html:not(.dark-mode) .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }

    .delete-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 4px;
    }

    html:not(.dark-mode) .delete-icon-wrap {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        animation: deletePulse 2s ease-in-out infinite;
    }

    html.dark-mode .delete-icon-wrap {
        background: rgba(220,38,38,.15);
        box-shadow: 0 0 20px rgba(220,38,38,.1);
    }

    .delete-icon-wrap i {
        font-size: 32px;
        color: #dc2626;
    }

    html:not(.dark-mode) .delete-icon-wrap i {
        animation: deleteShake 3s ease-in-out infinite;
    }

    @keyframes deletePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(220,38,38,.15); }
        50% { box-shadow: 0 0 0 12px rgba(220,38,38,0); }
    }

    @keyframes deleteShake {
        0%, 100% { transform: rotate(0deg); }
        2% { transform: rotate(8deg); }
        4% { transform: rotate(-6deg); }
        6% { transform: rotate(4deg); }
        8% { transform: rotate(0deg); }
    }

    .delete-info-box {
        border-left: 4px solid #dc2626;
        border-radius: 12px;
        padding: 14px 18px;
    }

    html:not(.dark-mode) .delete-info-box {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1px solid #e2e8f0;
    }

    html.dark-mode .delete-info-box {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.1);
    }

    .delete-info-box .delete-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #94a3b8;
        margin-bottom: 2px;
    }

    .delete-info-box .delete-value {
        font-weight: 700;
        font-size: 16px;
    }

    html:not(.dark-mode) .delete-info-box .delete-value {
        color: var(--ms-text);
    }

    html.dark-mode .delete-info-box .delete-value {
        color: var(--text-primary);
    }

    .btn-delete-final {
        border: none !important;
        border-radius: 10px !important;
        padding: 9px 22px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        transition: all .25s !important;
    }

    html:not(.dark-mode) .btn-delete-final {
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(220,38,38,.3) !important;
    }

    html.dark-mode .btn-delete-final {
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(220,38,38,.4) !important;
    }

    .btn-delete-final:hover {
        transform: translateY(-2px) !important;
    }

    html:not(.dark-mode) .btn-delete-final:hover {
        box-shadow: 0 8px 24px rgba(220,38,38,.4) !important;
    }

    html.dark-mode .btn-delete-final:hover {
        box-shadow: 0 8px 24px rgba(220,38,38,.5) !important;
    }

    .btn-delete-final:active {
        transform: translateY(0) !important;
    }

    .btn-cancel-modal {
        border: none !important;
        border-radius: 10px !important;
        padding: 9px 22px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        transition: all .25s !important;
    }

    html:not(.dark-mode) .btn-cancel-modal {
        background: #f1f5f9 !important;
        color: #475569 !important;
    }

    html:not(.dark-mode) .btn-cancel-modal:hover {
        background: #e2e8f0 !important;
        color: #1e293b !important;
    }

    html.dark-mode .btn-cancel-modal {
        background: rgba(255,255,255,.08) !important;
        color: var(--text-secondary) !important;
    }

    html.dark-mode .btn-cancel-modal:hover {
        background: rgba(255,255,255,.14) !important;
        color: var(--text-primary) !important;
    }

    .btn-cancel-modal:hover {
        transform: translateY(-1px);
    }
</style>

<div class="master-siswa-page">

    {{-- HEADER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-tag"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Kategori Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Kategori untuk mengelompokkan jenis lomba</span>
                </div>
            </div>
            <a href="{{ route('kategori-lomba.create') }}" class="btn btn-header-ms btn-simpan-ms btn-compact">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="card table-card">
        <div class="card-body">

            {{-- Filter otomatis gaya DataTables, kontrol pill --}}
            <form id="kategoriLombaFilter" method="GET" class="dt-toolbar" autocomplete="off">
                <div class="dt-left">
                    <div class="dt-length-group">
                        <span>Show</span>
                        <div class="filter-lomba-wrap" style="min-width:80px;">
                            <i class="fas fa-list-ol filter-icon-prepend"></i>
                            <select name="per_page" class="form-select">
                                @foreach ([10, 15, 25, 50, 100] as $opt)
                                    <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span>Entri</span>
                    </div>
                    <div class="filter-lomba-wrap" style="min-width:200px;">
                        <i class="fas fa-calendar-alt filter-icon-prepend"></i>
                        <select name="haflah_id" class="form-select">
                            <option value="">Haflah Aktif</option>
                            @foreach($haflatuls as $h)
                            <option value="{{ $h->id }}" {{ request('haflah_id')==$h->id ? 'selected' : '' }}>{{ $h->nama_acara }} ({{ $h->tahunAjaran->tahun_ajaran ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dt-right">
                    <input type="search" name="search" value="{{ request('search') }}" class="search-pill" placeholder="Cari nama kategori...">
                </div>
            </form>

            @if($kategoriLombas->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.4;"></i>
                    Belum ada data kategori lomba.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-ms">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Warna</th>
                            <th>Icon</th>
                            <th>Urutan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoriLombas as $kategoriLomba)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $kategoriLomba->nama }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $kategoriLomba->warna }}; color:#fff;">
                                    {{ $kategoriLomba->warna }}
                                </span>
                            </td>
                            <td>
                                @if($kategoriLomba->icon)
                                    <i class="fas {{ $kategoriLomba->icon }}"></i>
                                    <span class="text-muted ms-1" style="font-size:11px;">{{ $kategoriLomba->icon }}</span>
                                @else
                                    <span style="color:#94a3b8;font-size:12px;">—</span>
                                @endif
                            </td>
                            <td>{{ $kategoriLomba->urutan }}</td>
                            <td>
                                <div class="action-group-ms">
                                    <a href="{{ route('kategori-lomba.show', $kategoriLomba->id) }}" class="btn btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>
                                    @if($kategoriLomba->is_haflah_selesai)
                                    <span class="btn btn-outline-secondary" title="Haflah selesai - terkunci" style="cursor:not-allowed;opacity:.5;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @elseif($kategoriLomba->lombas_count > 0)
                                    <a href="{{ route('kategori-lomba.edit', $kategoriLomba->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <span class="btn btn-outline-secondary" title="Tidak dapat dihapus, sudah digunakan di lomba" style="cursor:not-allowed;opacity:.5;">
                                        <i class="fas fa-trash text-muted"></i>
                                    </span>
                                    @else
                                    <a href="{{ route('kategori-lomba.edit', $kategoriLomba->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#hapusModal"
                                        data-nama="{{ $kategoriLomba->nama }}"
                                        data-url="{{ route('kategori-lomba.destroy', $kategoriLomba->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
                <span class="text-muted" style="font-size:13px;">
                    Menampilkan {{ $kategoriLombas->firstItem() ?? 0 }}–{{ $kategoriLombas->lastItem() ?? 0 }} dari {{ $kategoriLombas->total() }} entri
                </span>
                <div class="pagination-ms">
                    {{ $kategoriLombas->onEachSide(1)->links() }}
                </div>
            </div>
            @endif

            {{-- Modal Hapus — tunggal, di luar tabel --}}
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
                            <h5 class="fw-bold mt-3 mb-2">Hapus Kategori?</h5>
                            <p class="text-muted mb-3" style="font-size:14px">Data yang dihapus tidak dapat dikembalikan.</p>
                            <div class="delete-info-box text-start">
                                <div class="delete-label">Kategori</div>
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
            </div>        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const form = document.getElementById('kategoriLombaFilter');
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
    form.querySelectorAll('select').forEach(function (el) {
        el.addEventListener('change', applyFilter);
    });
    form.querySelectorAll('input[type="search"], input[type="text"]').forEach(function (el) {
        el.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(applyFilter, 350);
        });
    });
})();

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
@endpush
@endsection
