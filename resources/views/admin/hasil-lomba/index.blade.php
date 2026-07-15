@extends('layouts.main')
@section('title', 'Hasil Lomba')
@section('content')
@include('component.admin.ms-style')
<style>
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
        color: #475569; min-width: 200px; transition: all .25s; outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: left 12px center; background-size: 14px;
    }
    .search-pill:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .search-pill::placeholder { color: #94a3b8; font-size: 12px; }
    .btn-header-ms.btn-tambah-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }

    /* ---- Tab Navigation ---- */
    .hasil-tab-nav {
        display: flex; gap: 0; margin: 16px 20px 0; border-bottom: 2px solid #e2e8f0;
    }
    .hasil-tab-nav a {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; font-size: 13px; font-weight: 600;
        color: #94a3b8; text-decoration: none; border-bottom: 2px solid transparent;
        margin-bottom: -2px; transition: all .2s; border-radius: 8px 8px 0 0;
    }
    .hasil-tab-nav a:hover { color: #475569; background: #f8fafc; }
    .hasil-tab-nav a.active {
        color: #16a34a; border-bottom-color: #16a34a;
        background: rgba(22,163,74,.04);
    }
    .hasil-tab-nav a .tab-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 22px; height: 20px; padding: 0 6px;
        border-radius: 10px; font-size: 11px; font-weight: 700;
        background: #f1f5f9; color: #64748b;
    }
    .hasil-tab-nav a.active .tab-count {
        background: #dcfce7; color: #16a34a;
    }
    @media (max-width: 576px) {
        .hasil-tab-nav { margin: 12px 14px 0; }
        .hasil-tab-nav a { padding: 8px 14px; font-size: 12px; }
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

    @media (max-width: 768px) {
        .card.border-0.shadow-sm.mb-4 .btn-compact {
            width: fit-content;
            align-self: flex-start;
        }

        #hasilFilter {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            align-items: center;
        }

        #hasilFilter > div {
            min-width: 0 !important;
        }

        #hasilFilter .filter-icon-prepend {
            display: none;
        }

        #hasilFilter .form-select,
        #hasilFilter .search-pill {
            height: 28px !important;
            font-size: 10px !important;
            padding: 0 16px 0 8px !important;
            box-sizing: border-box !important;
        }

        #hasilFilter .form-select {
            width: 100% !important;
            min-width: 0 !important;
        }

        #hasilFilter .search-pill {
            width: 100% !important;
            min-width: 0 !important;
        }

        #hasilFilter .d-flex.align-items-center.gap-1 span {
            font-size: 10px;
        }

        #hasilFilter .d-flex.align-items-center.gap-1 .filter-lomba-wrap .form-select {
            width: 44px !important;
        }
    }
</style>
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-medal"></i></div>
                    <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Hasil Lomba</h4></div>
                </div>
                <div><a href="{{ route('hasil-lomba.create') }}" class="btn btn-header-ms btn-tambah-ms btn-compact"><i class="fas fa-plus"></i> Tambah</a></div>
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
            @php
                $tabParams = request()->except('tab');
                $tabIndividuUrl = route('hasil-lomba.index', array_merge($tabParams, ['tab' => 'individu']));
                $tabKelompokUrl = route('hasil-lomba.index', array_merge($tabParams, ['tab' => 'kelompok']));
            @endphp
            <nav class="hasil-tab-nav">
                <a href="{{ $tabIndividuUrl }}" class="{{ $tab === 'individu' ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Individu
                </a>
                <a href="{{ $tabKelompokUrl }}" class="{{ $tab === 'kelompok' ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Kelompok
                </a>
            </nav>
            <form id="hasilFilter" method="GET" autocomplete="off" class="d-flex align-items-center gap-2 flex-wrap" style="margin:16px 0;">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <span style="font-size:12px;">Show</span>
                    <div class="filter-lomba-wrap" style="min-width:50px;">
                        <i class="fas fa-list-ol filter-icon-prepend"></i>
                        <select name="per_page" class="form-select" style="min-width:50px;font-size:11px;">
                            @foreach ([10, 15, 25, 50, 100] as $opt)
                                <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span style="font-size:12px;">Entri</span>
                </div>
                <div class="filter-lomba-wrap">
                    <i class="fas fa-trophy filter-icon-prepend"></i>
                    <select name="lomba_id" class="form-select" style="width:160px;font-size:11px;">
                        <option value="">Semua Lomba</option>
                        @foreach($lombas as $l)
                        <option value="{{ $l->id }}" {{ request('lomba_id')==$l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-lomba-wrap">
                    <i class="fas fa-medal filter-icon-prepend"></i>
                    <select name="juara" class="form-select" style="width:140px;font-size:11px;">
                        <option value="">Semua Juara</option>
                        @foreach($juaraList as $j)
                        <option value="{{ $j }}" {{ request('juara')==$j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="search" name="nama" value="{{ request('nama') }}" class="search-pill" placeholder="cari" style="width:120px;min-width:120px;font-size:11px;">
            </form>
            <div class="table-responsive">
                <table class="table table-ms">
                    <thead><tr><th>No</th><th>Lomba</th><th>Peserta</th><th>Nilai Penilaian</th><th>Total Nilai</th><th>Peringkat</th><th>Juara</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($hasilLombas as $hasilLomba)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $hasilLomba->lomba->nama ?? '-' }}</td>
                            <td>
                                @php $pl = $hasilLomba->pesertaLomba; @endphp
                                @if($pl->isIndividu())
                                    {{ $pl->student->user->name ?? '-' }}
                                @elseif($pl->kelompokLomba)
                                    <i class="fas fa-users me-1"></i>{{ $pl->kelompokLomba->nama_kelompok }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $hasilLomba->total_dari_penilaian ?? 0 }}</td>
                            <td>{{ $hasilLomba->total_nilai }}</td>
                            <td>{{ $hasilLomba->peringkat }}</td>
                            <td>{{ $hasilLomba->juara ?? '-' }}</td>
                            <td>
                                <div class="action-group-ms">
                                    <a href="{{ route('hasil-lomba.show', $hasilLomba->id) }}" class="btn btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>
                                    @if($hasilLomba->is_haflah_selesai)
                                    <span class="btn btn-outline-secondary" title="Haflah selesai - terkunci" style="cursor:not-allowed;opacity:.5;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @else
                                    <a href="{{ route('hasil-lomba.edit', $hasilLomba->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#hapusModal"
                                        data-nama="{{ $hasilLomba->pesertaLomba->isIndividu() ? ($hasilLomba->pesertaLomba->student->user->name ?? '-') : ($hasilLomba->pesertaLomba->kelompokLomba->nama_kelompok ?? '-') }}"
                                        data-url="{{ route('hasil-lomba.destroy', $hasilLomba->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hasilLombas->hasPages())
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
                <span class="text-muted" style="font-size:13px;">
                    Menampilkan {{ $hasilLombas->firstItem() }}–{{ $hasilLombas->lastItem() }} dari {{ $hasilLombas->total() }} entri
                </span>
                <div class="pagination-ms">
                    {{ $hasilLombas->onEachSide(1)->links() }}
                </div>
            </div>
            @endif
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
                <h5 class="fw-bold mt-3 mb-2">Hapus Hasil Lomba?</h5>
                <p class="text-muted mb-3" style="font-size:14px">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="delete-info-box text-start">
                    <div class="delete-label">Peserta</div>
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
    (function () {
        var form = document.getElementById('hasilFilter');
        if (!form) return;
        function applyFilter() {
            var params = new URLSearchParams();
            var data = new FormData(form);
            for (var _i = 0, _entries = Array.from(data.entries()); _i < _entries.length; _i++) {
                var _pair = _entries[_i];
                if (_pair[1]) params.append(_pair[0], _pair[1]);
            }
            window.location.search = params.toString();
        }
        form.querySelectorAll('select').forEach(function (el) {
            el.addEventListener('change', applyFilter);
        });
        var searchInput = form.querySelector('input[type="search"]');
        if (searchInput) {
            var debounce;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(applyFilter, 400);
            });
        }
    })();
</script>
@endpush
