@extends('layouts.main')
@section('title', 'Riwayat Login — Semua Pengguna')

@section('content')
@include('component.admin.ms-style')
<style>
    /* ---- Filter pill — model diambil dari anggota-kelompok ---- */
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

    /* Search pill — senada dengan filter-lomba-wrap, pakai ikon search */
    .search-pill {
        height: 34px; border: 1.5px solid #e2e8f0; border-radius: 18px;
        font-size: 12px; padding: 0 16px 0 34px; background-color: #f8fafc;
        color: #475569; min-width: 240px; transition: all .25s; outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: 12px center; background-size: 14px;
    }
    .search-pill:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
    .search-pill::placeholder { color: #94a3b8; }

    /* Toolbar gaya DataTables yang menampung pill */
    .dt-toolbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 14px; }
    .dt-left, .dt-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .dt-length-group { display:inline-flex; align-items:center; gap:8px; font-size:12px; color:#64748b; }

    /* Tabel: ikut .table-ms, tapi kolom pertama tidak dibatasi 60px */
    .login-hist-table thead th { text-align:left; }
    .login-hist-table thead th:first-child,
    .login-hist-table tbody td:first-child { width:auto; text-align:left; }

    /* Pagination kecil senada dengan .dataTables_paginate ms-style */
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

    @media (max-width: 768px) {
        .dt-toolbar { justify-content:flex-start; }
        .search-pill { min-width:100%; flex:1 1 100%; }
    }
</style>

<div class="master-siswa-page">

    {{-- HEADER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-clock-rotate-left"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Riwayat Login</h4>
                    <span style="font-size: 13px; color: #64748b;">Audit trail seluruh percobaan login di sistem</span>
                </div>
            </div>
            <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;">
                <i class="fas fa-database me-1"></i> Total {{ $histories->total() }} entri
            </span>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card table-card">
        <div class="card-body">

            {{-- Filter otomatis gaya DataTables, kontrol pill model anggota-kelompok --}}
            <form id="loginHistoryFilter" method="GET" class="dt-toolbar" autocomplete="off">
                <div class="dt-left">
                    <div class="dt-length-group">
                        <span>Tampilkan</span>
                        <div class="filter-lomba-wrap" style="min-width:80px;">
                            <i class="fas fa-list-ol filter-icon-prepend"></i>
                            <select name="per_page" class="form-select">
                                @foreach ([10, 15, 25, 50, 100] as $opt)
                                    <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span>data</span>
                    </div>
                    <div class="filter-lomba-wrap" style="min-width:150px;">
                        <i class="fas fa-filter filter-icon-prepend"></i>
                        <select name="status" class="form-select">
                            <option value="">Semua status</option>
                            <option value="success" @if(request('status')==='success') selected @endif>Berhasil</option>
                            <option value="failed" @if(request('status')==='failed') selected @endif>Gagal</option>
                            <option value="throttled" @if(request('status')==='throttled') selected @endif>Terblokir</option>
                        </select>
                    </div>
                </div>
                <div class="dt-right">
                    <input type="search" name="search" value="{{ request('search') }}" class="search-pill" placeholder="Cari nama / username / email">
                </div>
            </form>

            @if ($histories->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.4;"></i>
                    Tidak ada riwayat yang cocok.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-ms login-hist-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Status</th>
                            <th>OTP</th>
                            <th>Perangkat</th>
                            <th>IP</th>
                            <th>Logout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $h)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $h->login_at?->format('d M Y, H:i') }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $h->login_at?->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if ($h->user)
                                    <div class="fw-semibold">{{ $h->user->name }}</div>
                                    <div class="text-muted" style="font-size:11px;">@ {{ $h->user->username }}</div>
                                @else
                                    <span class="text-danger fst-italic" style="font-size:11px;">
                                        Tidak dikenal
                                        @if (!empty($h->metadata['attempted']))
                                            ({{ $h->metadata['attempted'] }})
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td>{!! $h->login_status_badge !!}</td>
                            <td>{!! $h->otp_status_badge !!}</td>
                            <td>
                                <span class="fw-semibold">{{ $h->browser }}</span>
                                <span class="text-muted d-block" style="font-size:11px;">{{ $h->os }} · {{ $h->device }}</span>
                            </td>
                            <td><code>{{ $h->ip_address }}</code></td>
                            <td>
                                @if ($h->logout_at)
                                    <span class="text-muted" style="font-size:11px;">{{ $h->logout_at->format('d M, H:i') }}</span>
                                @else
                                    <span class="badge bg-success-subtle text-success" style="font-size:10px;">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
                <span class="text-muted" style="font-size:13px;">
                    Menampilkan {{ $histories->firstItem() ?? 0 }}–{{ $histories->lastItem() ?? 0 }} dari {{ $histories->total() }} entri
                </span>
                <div class="pagination-ms">
                    {{ $histories->onEachSide(1)->links() }}
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const form = document.getElementById('loginHistoryFilter');
    if (!form) return;

    // Submit bersih: buang param kosong & reset ke halaman 1 tiap ganti filter.
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
</script>
@endpush
@endsection
