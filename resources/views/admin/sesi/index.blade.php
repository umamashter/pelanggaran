@extends('layouts.main')
@section('title', 'Daftar Nama Sesi')

@section('content')
@include('component.admin.ms-style')
<style>
    /* ---- Filter pill — model sama dengan login-history / anggota-kelompok ---- */
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
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
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
        border-color: #cbd5e1;
        background-color: #fff;
    }

    .dt-toolbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin: 0 0 14px;
    }

    .dt-left,
    .dt-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dt-length-group {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
    }

    .pagination-ms {
        display: flex;
        justify-content: flex-end;
    }

    .pagination-ms .pagination {
        margin: 0;
        gap: 4px;
    }

    .pagination-ms .page-link {
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        line-height: 32px;
        color: #475569;
        background: #fff;
        border: 1px solid var(--ms-border);
        box-shadow: none;
    }

    .pagination-ms .page-link:hover {
        border-color: var(--ms-primary);
        color: var(--ms-primary);
        background: var(--ms-primary-light);
    }

    .pagination-ms .page-item.active .page-link {
        background: var(--ms-primary);
        border-color: var(--ms-primary);
        color: #fff;
        box-shadow: 0 2px 6px rgba(22, 163, 74, .25);
    }

    .pagination-ms .page-item.disabled .page-link {
        opacity: .4;
        background: #f8fafc;
    }

    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .card-body.p-4.d-flex.flex-column.flex-xl-row.justify-content-between.align-items-xl-center.gap-3>a.btn-header-ms {
            align-self: flex-start;
            width: fit-content;
        }

        #sesiFilter.dt-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }

        #sesiFilter .dt-left {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center;
            gap: 4px;
            width: 100%;
        }

        #sesiFilter .dt-left .dt-length-group {
            flex-shrink: 0;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        #sesiFilter .dt-left .dt-length-group span {
            font-size: 10px;
        }

        #sesiFilter .dt-left .dt-length-group .filter-lomba-wrap {
            min-width: 0 !important;
        }

        #sesiFilter .dt-left .dt-length-group .filter-lomba-wrap .filter-icon-prepend {
            display: none;
        }

        #sesiFilter .dt-left .dt-length-group .filter-lomba-wrap .form-select {
            width: 44px !important;
            min-width: 0 !important;
            padding: 0 16px 0 8px;
            font-size: 10px;
            height: 28px;
        }

        #sesiFilter .dt-left>.filter-lomba-wrap {
            flex: 1;
            min-width: 0 !important;
        }

        #sesiFilter .dt-left>.filter-lomba-wrap .filter-icon-prepend {
            display: none;
        }

        #sesiFilter .dt-left>.filter-lomba-wrap .form-select {
            width: 100% !important;
            min-width: 0 !important;
            font-size: 10px;
            padding: 0 16px 0 8px;
            height: 28px !important;
        }
    }
</style>

<div class="master-siswa-page">

    @if(session('success'))
    <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Nama Sesi</h4>
                    <span style="font-size: 13px; color: #64748b;">Daftar nama sesi yang dapat dipakai pada kegiatan haflah berlangsung</span>
                </div>
            </div>
            <a href="{{ route('sesi.create') }}" class="btn btn-header-ms btn-simpan-ms btn-compact">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    {{-- Table card --}}
    <div class="card table-card">
        <div class="card-body">

            {{-- Filter otomatis gaya DataTables, kontrol pill --}}
            <form id="sesiFilter" method="GET" class="dt-toolbar" autocomplete="off">
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
            </form>

            @if($sesis->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.4;"></i>
                Belum ada data nama sesi.
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-ms">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sesi</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sesis as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $s->nama }}</td>
                            <td>
                                @if($s->tanggal)
                                <span class="badge" style="background:#fdf2f8;color:#be185d;font-weight:600;">
                                    <i class="fas fa-calendar-day me-1"></i>{{ \Carbon\Carbon::parse($s->tanggal)->isoFormat('D MMM YYYY') }}
                                </span>
                                @else
                                <span style="color:#94a3b8;font-size:12px;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($s->jam_mulai || $s->jam_selesai)
                                <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-weight:600;">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $s->jam_mulai ? \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') : '??' }} -
                                    {{ $s->jam_selesai ? \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') : '??' }}
                                </span>
                                @else
                                <span style="color:#94a3b8;font-size:12px;">—</span>
                                @endif
                            </td>
                            <td>
                                @php $used = in_array($s->nama, $usedNames); @endphp
                                <div class="action-group-ms">
                                    @if($s->is_haflah_selesai)
                                    <span class="btn btn-outline-secondary" title="Haflah selesai - terkunci" style="cursor:not-allowed;opacity:.5;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @elseif($used)
                                    <span class="btn btn-outline-secondary" title="Sedang digunakan di sesi lomba" style="cursor:default;opacity:.5;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @else
                                    <a href="{{ route('sesi.edit', $s->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('sesi.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus nama sesi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
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
                    Menampilkan {{ $sesis->firstItem() ?? 0 }}–{{ $sesis->lastItem() ?? 0 }} dari {{ $sesis->total() }} entri
                </span>
                <div class="pagination-ms">
                    {{ $sesis->onEachSide(1)->links() }}
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('sesiFilter');
        if (!form) return;

        function applyFilter() {
            const params = new URLSearchParams();
            const data = new FormData(form);
            for (const [k, v] of data.entries()) {
                if (v) params.append(k, v);
            }
            window.location.search = params.toString();
        }

        form.querySelectorAll('select').forEach(function(el) {
            el.addEventListener('change', applyFilter);
        });
    })();
</script>
@endpush
@endsection