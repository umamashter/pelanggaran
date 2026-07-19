@extends('layouts.main')
@section('title', 'Jadwal Pelajaran')
@section('content')
@include('component.admin.ms-style')
<style>
    .page-title-content { display: none !important; }

    .btn-header-ms.btn-simpan-ms.btn-compact {
        height: 36px;
        padding: 0 8px;
        font-size: 10px;
        border-radius: 8px;
        gap: 3px;
    }
    .btn-header-ms.btn-simpan-ms.btn-compact i { font-size: 10px; }
    .btn-header-ms:disabled { opacity:.65; cursor:not-allowed; background:rgba(22,163,74,.25)!important; color:rgba(255,255,255,.7)!important; border:1px solid rgba(22,163,74,.3)!important; }

    /* Tab Jenjang */
    .nav-jenjang .nav-link {
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 13px;
        color: var(--ms-text-soft);
        padding: 10px 20px;
        transition: all .2s;
    }
    .nav-jenjang .nav-link.active { background: rgb(86, 179, 67); color: #fff; }
    .nav-jenjang .nav-link:not(.active):hover { background: rgba(86,179,74,.1); color: rgb(86,179,67); }

    /* Tab Kelas */
    .nav-kelas .nav-link {
        border-radius: 10px;
        font-weight: 600;
        font-size: 12px;
        padding: 6px 14px;
        color: var(--ms-text-soft);
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        transition: all .2s;
        margin-right: 6px;
        margin-bottom: 4px;
    }
    .nav-kelas .nav-link.active { background: #2563eb; color: #fff; border-color: #2563eb; }
    .nav-kelas .nav-link:not(.active):hover { background: #dbeafe; color: #2563eb; }

    /* Matrix Table */
    .jadwal-matrix {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        font-size: 12px;
    }
    .jadwal-matrix thead th {
        background: #16a34a;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        padding: 10px 6px;
        font-weight: 600;
        border: none;
        font-size: 12px;
    }
    .jadwal-matrix thead th:first-child { border-radius: 10px 0 0 0; }
    .jadwal-matrix thead th:last-child { border-radius: 0 10px 0 0; }
    .jadwal-matrix tbody td {
        padding: 8px 6px;
        vertical-align: middle;
        border: 1px solid #e5e7eb;
        height: 60px;
    }
    .jadwal-matrix tbody tr:last-child td:first-child { border-radius: 0 0 0 10px; }
    .jadwal-matrix tbody tr:last-child td:last-child { border-radius: 0 0 10px 0; }

    .jam-cell-matrix {
        background: #f0fdf4;
        text-align: center;
        font-weight: 700;
        color: #166534;
        white-space: nowrap;
    }
    .jam-cell-matrix .jam-label { font-size: 13px; display: block; }
    .jam-cell-matrix .jam-waktu { font-size: 10px; color: #6b7280; font-weight: 400; }

    .mapel-cell-matrix { text-align: center; background: #fff; transition: all 0.2s; cursor: pointer; }
    .mapel-cell-matrix:hover { background: #f0fdf4; box-shadow: inset 0 0 0 2px #16a34a; }
    .mapel-cell-matrix .nama-mapel { font-weight: 700; color: #166534; font-size: 12px; display: block; margin-bottom: 1px; }
    .mapel-cell-matrix .nama-guru { font-size: 10px; color: #6b7280; }
    .kosong-cell { text-align: center; color: #d1d5db; font-size: 16px; }
    .add-cell-btn {
        width: 32px; height: 32px; border-radius: 10px; border: 2px dashed #d1d5db;
        background: transparent; color: #94a3b8; display: inline-flex;
        align-items: center; justify-content: center; cursor: pointer;
        transition: all .2s; font-size: 14px; font-weight: 700; line-height: 1;
    }
    .add-cell-btn:hover { border-color: #16a34a; color: #16a34a; background: #f0fdf4; transform: scale(1.1); }

    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

    .delete-icon-wrap {
        width: 90px; height: 90px; border-radius: 50%;
        background: rgba(220, 38, 38, .08);
        display: inline-flex; align-items: center; justify-content: center;
    }
    .delete-icon-wrap i { font-size: 2.5rem; color: #dc2626; }

    .salin-icon-wrap { width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:4px; }
    html:not(.dark-mode) .salin-icon-wrap { background:linear-gradient(135deg,#eff6ff,#dbeafe); animation:salinPulse 2s ease-in-out infinite; }
    html.dark-mode .salin-icon-wrap { background:rgba(37,99,235,.15); box-shadow:0 0 20px rgba(37,99,235,.1); }
    .salin-icon-wrap i { font-size:32px; color:#2563eb; }
    html:not(.dark-mode) .salin-icon-wrap i { animation:salinBounce 2.5s ease-in-out infinite; }
    @keyframes salinPulse { 0%,100%{box-shadow:0 0 0 0 rgba(37,99,235,.15)} 50%{box-shadow:0 0 0 12px rgba(37,99,235,0)} }
    @keyframes salinBounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-3px)} }

    .salin-info-box { border-left:4px solid #2563eb; border-radius:12px; padding:14px 18px; }
    html:not(.dark-mode) .salin-info-box { background:linear-gradient(135deg,#f8fafc,#f1f5f9); border:1px solid #e2e8f0; }
    html.dark-mode .salin-info-box { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); }
    .salin-info-box .salin-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:2px; }
    .salin-info-box .salin-value { font-weight:700; font-size:15px; }
    html:not(.dark-mode) .salin-info-box .salin-value { color:var(--ms-text); }
    html.dark-mode .salin-info-box .salin-value { color:var(--text-primary); }

    .btn-salin-final { border:none !important; border-radius:10px !important; padding:9px 22px !important; font-weight:600 !important; font-size:13px !important; transition:all .25s !important; }
    .btn-salin-final:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(37,99,235,.3); }

    @media (max-width: 575.98px) {
        .action-group-ms { display: inline-flex !important; gap: 4px !important; grid-template-columns: unset !important; }
        .action-group-ms .btn { width: 28px !important; height: 28px !important; font-size: 11px !important; }
    }
</style>

{{-- ===== HEADER ===== --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                        Jadwal Pelajaran
                    </h4>
                    <p class="mb-1" style="font-size:12px;color:#94a3b8;line-height:1.4;">
                        Lihat dan kelola jadwal mengajar per jenjang, kelas, dan hari.
                    </p>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <span class="badge" style="background:rgba(86,179,74,.12);color:rgb(86,179,67);border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                            <i class="fas fa-list me-1"></i> {{ $jadwals->count() }} Total Jadwal
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                @if($sudahDisalin)
                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    disabled title="Data tahun ajaran aktif sudah ada">
                    <i class="fas fa-copy me-1"></i> Salin
                </button>
                @else
                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    data-bs-toggle="modal" data-bs-target="#modalSalinJadwal">
                    <i class="fas fa-copy me-1"></i> Salin
                </button>
                @endif

                <a href="{{ route('jadwal-pelajaran.export-pdf', [
                    'jenjang_id' => request('jenjang_id'),
                    'kelas_id' => request('kelas_id'),
                    'guru_id' => request('guru_id'),
                    'tahun_ajaran_id' => request('tahun_ajaran_id')
                ]) }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,.25);">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>

                <button type="button" class="btn btn-header-ms btn-simpan-ms btn-compact"
                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== ALERTS ===== --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" style="border-radius:12px;">
    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" style="border-radius:12px;">
    <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($jenjangs->isEmpty())
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body">
        <div class="empty-state">
            <i class="fas fa-school"></i>
            <p class="mb-0">Belum ada data jenjang.</p>
        </div>
    </div>
</div>
@else

{{-- ===== TAB JENJANG ===== --}}
<ul class="nav nav-tabs nav-jenjang mb-0" id="jenjangTabs" role="tablist">
    @foreach($jenjangs as $j)
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
            id="jenjang-{{ $j->id }}-tab"
            data-bs-toggle="tab"
            data-bs-target="#jenjang-{{ $j->id }}"
            type="button" role="tab">
            <i class="fas fa-school me-1"></i> {{ $j->nama_jenjang }}
            <span class="badge bg-white text-success ms-1" style="font-size:10px;">{{ $jadwalPerJenjang[$j->id]->count() }}</span>
        </button>
    </li>
    @endforeach
</ul>

<div class="card border-0 shadow-sm" style="border-radius: 0 16px 16px 16px;">
    <div class="card-body p-3">
        <div class="tab-content" id="jenjangTabContent">
            @foreach($jenjangs as $j)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                id="jenjang-{{ $j->id }}" role="tabpanel">

                @if($jadwalPerJenjang[$j->id]->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p class="mb-0">Belum ada jadwal untuk jenjang {{ $j->nama_jenjang }}.</p>
                </div>
                @else

                {{-- ===== TAB KELAS ===== --}}
                <ul class="nav nav-tabs nav-kelas mb-3" role="tablist">
                    @foreach($kelasPerJenjang[$j->id] as $k)
                    @php $countKelas = $jadwalPerJenjang[$j->id]->where('kelas_id', $k->id)->count(); @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                            id="kelas-{{ $j->id }}-{{ $k->id }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#kelas-{{ $j->id }}-{{ $k->id }}"
                            type="button" role="tab">
                            {{ $k->nama_kelas }}
                            <span class="badge {{ $countKelas > 0 ? 'bg-primary' : 'bg-secondary' }} ms-1 hari-count">{{ $countKelas }}</span>
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($kelasPerJenjang[$j->id] as $k)
                    @php $jadwalKelas = $jadwalPerJenjang[$j->id]->where('kelas_id', $k->id); @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                        id="kelas-{{ $j->id }}-{{ $k->id }}" role="tabpanel">

                        @if($jadwalKelas->isEmpty())
                        <div class="text-center py-4" style="color:#94a3b8;">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p class="mb-0" style="font-size:13px;">Belum ada jadwal untuk Kelas {{ $k->nama_kelas }}.</p>
                        </div>
                        @else

                        <div class="table-responsive">
                            <table class="jadwal-matrix">
                                <thead>
                                    <tr>
                                        <th style="width:90px;">Jam</th>
                                        @foreach($hariList as $hari)
                                        <th>{{ $hari }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([1 => '07:30-08:30', 2 => '08:30-09:30', 3 => '10:00-11:00', 4 => '11:00-12:00'] as $jam => $waktu)
                                    <tr>
                                        <td class="jam-cell-matrix">
                                            <span class="jam-label">Jam {{ $jam }}</span>
                                            <span class="jam-waktu">{{ $waktu }}</span>
                                        </td>
                                        @foreach($hariList as $hari)
                                        @php $jadwal = $jadwalKelas->where('hari', $hari)->where('jam_ke', $jam)->first(); @endphp
                                         <td class="mapel-cell-matrix" style="{{ $jadwal ? '' : 'background:#fafafa;' }}"
                                            @if($jadwal) data-bs-toggle="modal" data-bs-target="#detail{{ $jadwal->id }}" @endif>
                                            @if($jadwal)
                                            <span class="nama-mapel">{{ $jadwal->mapel->nama_mapel ?? '-' }}</span>
                                            <span class="nama-guru">{{ $jadwal->guru->nama ?? '-' }}</span>
                                            @else
                                            <button type="button" class="add-cell-btn" title="Tambah Jadwal"
                                                data-bs-toggle="modal" data-bs-target="#modalTambah"
                                                data-prefill-kelas="{{ $k->id }}"
                                                data-prefill-hari="{{ $hari }}"
                                                data-prefill-jam="{{ $jam }}">+</button>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @endif
                    </div>
                    @endforeach
                </div>

                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

@endif

{{-- ===== MODALS ===== --}}
@php $jamSlot = [1=>'07:30-08:30',2=>'08:30-09:30',3=>'10:00-11:00',4=>'11:00-12:00']; @endphp
@foreach($jenjangs as $j)
@foreach($jadwalPerJenjang[$j->id] as $jadwal)

{{-- Detail Modal --}}
<div class="modal fade" id="detail{{ $jadwal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">

            {{-- Header --}}
            <div class="position-relative" style="background:linear-gradient(135deg,#059669,#10b981);padding:28px 24px 48px;">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal"></button>
                <div class="text-center text-white">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.2);backdrop-filter:blur(10px);display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                        <i class="fas fa-book-open" style="font-size:26px;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size:20px;letter-spacing:-.3px;">{{ $jadwal->mapel->nama_mapel ?? '-' }}</h5>
                    <span style="font-size:13px;opacity:.85;">{{ $jadwal->guru->nama ?? '-' }}</span>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-4 pb-4" style="margin-top:-28px;">
                <div class="bg-white rounded-4 shadow-sm p-3 mb-3" style="border-radius:16px !important;">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div style="width:36px;height:36px;border-radius:10px;background:#f0fdf4;display:inline-flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fas fa-layer-group" style="font-size:13px;color:#16a34a;"></i>
                            </div>
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Jenjang</div>
                            <div class="fw-bold" style="font-size:13px;color:#1e293b;">{{ $jadwal->jenjang->kode ?? '-' }}</div>
                        </div>
                        <div class="col-4">
                            <div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:inline-flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fas fa-school" style="font-size:13px;color:#2563eb;"></i>
                            </div>
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Kelas</div>
                            <div class="fw-bold" style="font-size:13px;color:#1e293b;">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                        </div>
                        <div class="col-4">
                            <div style="width:36px;height:36px;border-radius:10px;background:#fefce8;display:inline-flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fas fa-calendar-day" style="font-size:13px;color:#ca8a04;"></i>
                            </div>
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Hari</div>
                            <div class="fw-bold" style="font-size:13px;color:#1e293b;">{{ $jadwal->hari }}</div>
                        </div>
                        <div class="col-4">
                            <div style="width:36px;height:36px;border-radius:10px;background:#fdf2f8;display:inline-flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fas fa-clock" style="font-size:13px;color:#db2777;"></i>
                            </div>
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Jam Ke</div>
                            <div class="fw-bold" style="font-size:13px;color:#1e293b;">{{ $jadwal->jam_ke }}</div>
                        </div>
                        <div class="col-4">
                            <div style="width:36px;height:36px;border-radius:10px;background:#f5f3ff;display:inline-flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fas fa-hourglass-half" style="font-size:13px;color:#7c3aed;"></i>
                            </div>
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Waktu</div>
                            <div class="fw-bold" style="font-size:13px;color:#1e293b;">{{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}</div>
                        </div>
                        <div class="col-4">
                            <div style="width:36px;height:36px;border-radius:10px;background:#fff7ed;display:inline-flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fas fa-calendar-alt" style="font-size:13px;color:#ea580c;"></i>
                            </div>
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Tahun Ajaran</div>
                            <div class="fw-bold" style="font-size:13px;color:#1e293b;">{{ $jadwal->tahunAjaran->tahun_ajaran ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning fw-bold flex-fill" style="border-radius:12px;height:44px;font-size:13px;"
                        data-bs-toggle="modal" data-bs-target="#edit{{ $jadwal->id }}">
                        <i class="fas fa-pen me-1"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger fw-bold flex-fill" style="border-radius:12px;height:44px;font-size:13px;"
                        data-bs-toggle="modal" data-bs-target="#hapus{{ $jadwal->id }}">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="edit{{ $jadwal->id }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('jadwal-pelajaran.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">
                <div class="position-relative" style="background:linear-gradient(135deg,#d97706,#f59e0b);padding:24px 24px 20px;">
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal"></button>
                    <div class="d-flex align-items-center gap-3 text-white">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.2);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-pen-to-square" style="font-size:18px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="font-size:17px;">Edit Jadwal</h5>
                            <small style="opacity:.8;font-size:12px;">{{ $jadwal->kelas->nama_kelas ?? '' }} &middot; {{ $jadwal->hari }} Jam {{ $jadwal->jam_ke }}</small>
                        </div>
                    </div>
                </div>
                <div class="modal-body px-4 pt-4 pb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px;color:#64748b;">Kelas</label>
                            <select name="kelas_id" class="form-select" required style="height:44px;border-radius:12px;border:1.5px solid #e2e8f0;font-size:13px;">
                                @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->kelas_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kelas }} {{ $item->jenjang ? '('.$item->jenjang->nama_jenjang.')' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px;color:#64748b;">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="form-select" required style="height:44px;border-radius:12px;border:1.5px solid #e2e8f0;font-size:13px;">
                                @foreach($mapels as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->mata_pelajaran_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_mapel }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px;color:#64748b;">Guru</label>
                            <select name="guru_id" class="form-select" required style="height:44px;border-radius:12px;border:1.5px solid #e2e8f0;font-size:13px;">
                                @foreach($gurus as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->guru_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px;color:#64748b;">Tahun Ajaran</label>
                            <input type="text" class="form-control" readonly
                                value="{{ $tahunAjaranAktif->tahun_ajaran }}"
                                style="background:#f8fafc;border-radius:12px;height:44px;border:1.5px solid #e2e8f0;font-size:13px;color:#64748b;">
                            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px;color:#64748b;">Hari</label>
                            <select name="hari" class="form-select" required style="height:44px;border-radius:12px;border:1.5px solid #e2e8f0;font-size:13px;">
                                @foreach($hariList as $day)
                                <option {{ $jadwal->hari == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px;color:#64748b;">Jam Pelajaran</label>
                            <select name="jam_ke" class="form-select" required style="height:44px;border-radius:12px;border:1.5px solid #e2e8f0;font-size:13px;">
                                @foreach($jamSlot as $jk => $jt)
                                <option value="{{ $jk }}" {{ $jadwal->jam_ke == $jk ? 'selected' : '' }}>Jam {{ $jk }} ({{ $jt }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light fw-semibold" style="border-radius:12px;height:42px;font-size:13px;border:1.5px solid #e2e8f0;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold" style="border-radius:12px;height:42px;font-size:13px;box-shadow:0 4px 14px rgba(217,119,6,.3);">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Hapus Modal --}}
<div class="modal fade" id="hapus{{ $jadwal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">
            <div class="text-center px-4 pt-5 pb-4">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#fef2f2,#fee2e2);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <i class="fas fa-triangle-exclamation" style="font-size:30px;color:#dc2626;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="font-size:18px;color:#1e293b;">Hapus Jadwal?</h5>
                <p class="text-muted mb-4" style="font-size:13px;line-height:1.6;">Data yang dihapus tidak dapat dikembalikan. Pastikan Anda yakin sebelum melanjutkan.</p>

                <div class="bg-light rounded-3 p-3 mb-4 text-start" style="border:1px solid #f1f5f9;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-book" style="font-size:14px;color:#2563eb;"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:14px;color:#1e293b;">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $jadwal->kelas->nama_kelas ?? '-' }} &middot; {{ $jadwal->hari }} Jam {{ $jadwal->jam_ke }}</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('jadwal-pelajaran.destroy', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light fw-semibold flex-fill" style="border-radius:12px;height:44px;font-size:13px;border:1.5px solid #e2e8f0;" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-bold flex-fill" style="border-radius:12px;height:44px;font-size:13px;box-shadow:0 4px 14px rgba(220,38,38,.3);">
                            <i class="fas fa-trash me-1"></i> Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach
@endforeach

{{-- ===== MODAL TAMBAH ===== --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" id="formTambahJadwal">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-plus me-1"></i> Tambah Jadwal Pelajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kelas</label>
                            <select name="kelas_id" id="tambah_kelas" class="form-select" required style="height:42px;border-radius:10px;">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $item)
                                <option value="{{ $item->id }}"
                                    data-jenjang-id="{{ $item->jenjang_id }}"
                                    data-jenjang-nama="{{ $item->jenjang->nama_jenjang ?? '-' }}">
                                    {{ $item->nama_kelas }} — {{ $item->jenjang->nama_jenjang ?? '-' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jenjang <small class="text-muted">(otomatis)</small></label>
                            <input type="text" id="tambah_jenjang" class="form-control" readonly
                                placeholder="Otomatis dari kelas"
                                style="background:#f1f5f9;border-radius:10px;height:42px;">
                            <input type="hidden" name="jenjang_id" id="tambah_jenjang_id">
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="step2_mapel">
                            <label class="form-label fw-semibold">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="tambah_mapel" class="form-select" required style="height:42px;border-radius:10px;">
                                <option value="">Pilih Mata Pelajaran</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="step3_guru">
                            <label class="form-label fw-semibold">Guru</label>
                            <select name="guru_id" id="tambah_guru" class="form-select" required style="height:42px;border-radius:10px;">
                                <option value="">Pilih Guru</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="step4_hari">
                            <label class="form-label fw-semibold">Hari</label>
                            <select name="hari" id="tambah_hari" class="form-select" required style="height:42px;border-radius:10px;">
                                <option value="">Pilih Hari</option>
                                <option>Senin</option>
                                <option>Selasa</option>
                                <option>Rabu</option>
                                <option>Kamis</option>
                                <option>Sabtu</option>
                                <option>Ahad</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="step5_jam">
                            <label class="form-label fw-semibold">Jam Pelajaran</label>
                            <select name="jam_ke" id="tambah_jam" class="form-select" required style="height:42px;border-radius:10px;">
                                <option value="">Pilih Jam</option>
                                <option value="1">Jam 1 (07:30 - 08:30)</option>
                                <option value="2">Jam 2 (08:30 - 09:30)</option>
                                <option value="3">Jam 3 (10:00 - 11:00)</option>
                                <option value="4">Jam 4 (11:00 - 12:00)</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Tahun Ajaran <small class="text-muted">(otomatis)</small></label>
                            @if($tahunAjaranAktif)
                            <input type="text" class="form-control" readonly
                                value="{{ $tahunAjaranAktif->tahun_ajaran }}"
                                style="background:#f1f5f9;border-radius:10px;height:42px;">
                            @else
                            <div class="alert alert-danger mb-0" style="border-radius:10px;">Tidak ada tahun ajaran aktif.</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSimpanJadwal" disabled>
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL SALIN ===== --}}
<div class="modal fade" id="modalSalinJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header border-0 pb-0" style="padding:20px 24px 0;">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <div class="salin-icon-wrap">
                        <i class="fas fa-copy"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:18px;">Salin Jadwal Pelajaran?</h4>
                <p class="text-muted mb-4" style="font-size:13px;line-height:1.6;">
                    Semua jadwal pelajaran dari tahun ajaran sebelumnya akan disalin ke tahun ajaran aktif.
                    Jadwal yang sudah ada atau bentrok akan dilewati otomatis.
                </p>
                <div class="salin-info-box mb-3 text-start">
                    <div class="salin-label">Dari Tahun Ajaran</div>
                    <div class="salin-value" id="salinFromTA">-</div>
                </div>
                <div class="salin-info-box mb-4 text-start" style="border-left-color:#16a34a;">
                    <div class="salin-label">Ke Tahun Ajaran Aktif</div>
                    <div class="salin-value" id="salinToTA">-</div>
                </div>
                <form action="{{ route('jadwal-pelajaran.salin') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal" style="border-radius:10px;padding:9px 22px;font-weight:600;font-size:13px;">Batal</button>
                        <button type="submit" class="btn btn-primary btn-salin-final">
                            <i class="fas fa-copy me-1"></i> Ya, Salin Sekarang
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
$(document).ready(function() {
    var tahunAjarans = @json($tahunAjarans);
    var tahunAktif = @json($tahunAjaranAktif);

    $('#modalSalinJadwal').on('show.bs.modal', function() {
        if (tahunAktif) {
            $('#salinToTA').text(tahunAktif.tahun_ajaran + ' (Aktif)');
        }
        var tahunSebelumnya = tahunAjarans
            .filter(function(ta) { return ta.id != (tahunAktif ? tahunAktif.id : 0); })
            .sort(function(a, b) { return b.tahun_ajaran.localeCompare(a.tahun_ajaran); })[0];
        if (tahunSebelumnya) {
            $('#salinFromTA').text(tahunSebelumnya.tahun_ajaran);
        } else {
            $('#salinFromTA').text('Tidak ada data');
        }
    });

    const pengampuMapels = @json($pengampuMapels);
    const allMapels = @json($mapels->map(function ($m) {
        return ['id' => $m->id, 'nama_mapel' => $m->nama_mapel];
    })->values());
    const allGurus = @json($gurus->map(function ($g) {
        return ['id' => $g->id, 'nama' => $g->nama];
    })->values());

    // ===== STEP-BY-STEP TAMBAH JADWAL =====
    function resetTambahForm() {
        $('#tambah_kelas').val('');
        $('#tambah_jenjang').val('');
        $('#tambah_jenjang_id').val('');
        $('#step2_mapel').addClass('d-none');
        $('#step3_guru').addClass('d-none');
        $('#step4_hari').addClass('d-none');
        $('#step5_jam').addClass('d-none');
        $('#tambah_mapel').html('<option value="">Pilih Mata Pelajaran</option>');
        $('#tambah_guru').html('<option value="">Pilih Guru</option>');
        $('#tambah_hari').val('');
        $('#tambah_jam').val('');
        $('#btnSimpanJadwal').prop('disabled', true);
    }

    $('#modalTambah').on('show.bs.modal', function(e) {
        resetTambahForm();
        var $trigger = $(e.relatedTarget);
        var prefillKelas = $trigger.data('prefill-kelas');
        var prefillHari  = $trigger.data('prefill-hari');
        var prefillJam   = $trigger.data('prefill-jam');
        if (prefillKelas) {
            $('#tambah_kelas').val(prefillKelas).trigger('change');
            if (prefillHari) {
                setTimeout(function() {
                    $('#tambah_hari').val(prefillHari).trigger('change');
                    if (prefillJam) {
                        setTimeout(function() {
                            $('#tambah_jam').val(String(prefillJam)).trigger('change');
                        }, 150);
                    }
                }, 150);
            }
        }
    });

    $('#tambah_kelas').on('change', function() {
        var $opt = $(this).find(':selected');
        var jenjangId = $opt.data('jenjang-id');
        var jenjangNama = $opt.data('jenjang-nama');
        var kelasId = $(this).val();

        $('#tambah_jenjang').val(jenjangNama || '');
        $('#tambah_jenjang_id').val(jenjangId || '');
        $('#step3_guru').addClass('d-none');
        $('#step4_hari').addClass('d-none');
        $('#step5_jam').addClass('d-none');
        $('#tambah_guru').html('<option value="">Pilih Guru</option>');
        $('#tambah_hari').val('');
        $('#tambah_jam').val('');
        $('#btnSimpanJadwal').prop('disabled', true);

        if (!kelasId) {
            $('#step2_mapel').addClass('d-none');
            $('#tambah_mapel').html('<option value="">Pilih Mata Pelajaran</option>');
            return;
        }

        var mapelIds = [...new Set(
            pengampuMapels
                .filter(function(p) { return String(p.kelas_id) === String(kelasId); })
                .map(function(p) { return String(p.mata_pelajaran_id); })
        )];

        var $mapel = $('#tambah_mapel').html('<option value="">Pilih Mata Pelajaran</option>');
        if (!mapelIds.length) {
            $mapel.append('<option value="" disabled>Tidak ada mata pelajaran untuk kelas ini</option>');
            $('#step2_mapel').removeClass('d-none');
            return;
        }
        allMapels.forEach(function(m) {
            if (mapelIds.includes(String(m.id))) {
                $mapel.append('<option value="' + m.id + '">' + m.nama_mapel + '</option>');
            }
        });
        $('#step2_mapel').removeClass('d-none');
    });

    $('#tambah_mapel').on('change', function() {
        var kelasId = $('#tambah_kelas').val();
        var mapelId = $(this).val();
        $('#step4_hari').addClass('d-none');
        $('#step5_jam').addClass('d-none');
        $('#tambah_hari').val('');
        $('#tambah_jam').val('');
        $('#btnSimpanJadwal').prop('disabled', true);

        if (!mapelId || !kelasId) {
            $('#step3_guru').addClass('d-none');
            $('#tambah_guru').html('<option value="">Pilih Guru</option>');
            return;
        }

        var guruIds = [...new Set(
            pengampuMapels
                .filter(function(p) { return String(p.kelas_id) === String(kelasId) && String(p.mata_pelajaran_id) === String(mapelId); })
                .map(function(p) { return String(p.guru_id); })
        )];

        var $guru = $('#tambah_guru').html('<option value="">Pilih Guru</option>');
        if (!guruIds.length) {
            $guru.append('<option value="" disabled>Tidak ada guru untuk mapel ini</option>');
            $('#step3_guru').removeClass('d-none');
            return;
        }
        allGurus.forEach(function(g) {
            if (guruIds.includes(String(g.id))) {
                $guru.append('<option value="' + g.id + '">' + g.nama + '</option>');
            }
        });
        $('#step3_guru').removeClass('d-none');
    });

    $('#tambah_guru').on('change', function() {
        if ($(this).val()) {
            $('#step4_hari').removeClass('d-none');
        } else {
            $('#step4_hari').addClass('d-none');
            $('#step5_jam').addClass('d-none');
        }
        $('#tambah_hari').val('');
        $('#tambah_jam').val('');
        $('#btnSimpanJadwal').prop('disabled', true);
    });

    $('#tambah_hari').on('change', function() {
        if ($(this).val()) {
            $('#step5_jam').removeClass('d-none');
        } else {
            $('#step5_jam').addClass('d-none');
        }
        $('#tambah_jam').val('');
        $('#btnSimpanJadwal').prop('disabled', true);
    });

    $('#tambah_jam').on('change', function() {
        var ready = $('#tambah_kelas').val() && $('#tambah_mapel').val() && $('#tambah_guru').val() && $('#tambah_hari').val() && $(this).val();
        $('#btnSimpanJadwal').prop('disabled', !ready);
    });

    // Tab click propagation fix
    document.querySelectorAll('.nav-kelas .nav-link').forEach(function(tab) {
        tab.addEventListener('click', function(e) { e.stopPropagation(); });
    });
});
</script>
@endpush
