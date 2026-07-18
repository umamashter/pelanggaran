@extends('layouts.main')
@section('title', 'Template Jadwal Pelajaran')
@section('content')
@include('component.admin.ms-style')
<style>
    .page-title-content { display: none !important; }

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
    }
    .nav-kelas .nav-link.active { background: #2563eb; color: #fff; border-color: #2563eb; }
    .nav-kelas .nav-link:not(.active):hover { background: #dbeafe; color: #2563eb; }

    /* Table */
    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

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
                <div class="header-icon"><i class="fas fa-table"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                        Template Jadwal Pelajaran
                    </h4>
                    <p class="mb-1" style="font-size:12px;color:#94a3b8;line-height:1.4;">
                        Jenjang → Kelas → Hari — dapat diedit dan dihapus.
                    </p>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <span class="badge" style="background:rgba(86,179,74,.12);color:rgb(86,179,67);border-radius:8px;padding:5px 10px;font-size:11px;font-weight:600;">
                            <i class="fas fa-list me-1"></i> {{ $jadwals->count() }} Total Jadwal
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-header-ms btn-simpan-ms btn-compact">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

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

@php $hariList = ['Senin','Selasa','Rabu','Kamis','Sabtu','Minggu']; @endphp

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
                            Kelas {{ $k->nama_kelas }}
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
                            <table class="table table-bordered mb-0" style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th style="background:#64748b;color:#fff;text-align:center;vertical-align:middle;width:7%;">Jam</th>
                                        @foreach($hariList as $hari)
                                        <th style="background:rgb(86,179,67);color:#fff;text-align:center;vertical-align:middle;width:15.5%;">{{ substr($hari, 0, 3) }}</th>
                                        @endforeach
                                    </tr>
                                    <tr style="background:#f1f5f9;">
                                        <td class="text-center fw-bold" style="font-size:11px;color:#64748b;">Waktu</td>
                                        @foreach($hariList as $hari)
                                        <td class="text-center" style="font-size:10px;color:#94a3b8;">
                                            @php $jamHari = $jadwalKelas->where('hari', $hari); @endphp
                                            @if($jamHari->isNotEmpty())
                                                {{ substr($jamHari->first()->jam_mulai,0,5) }} - {{ substr($jamHari->last()->jam_selesai,0,5) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([1 => '07:30-08:30', 2 => '08:30-09:30', 3 => '10:00-11:00', 4 => '11:00-12:00'] as $jam => $waktu)
                                    <tr>
                                        <td class="text-center fw-bold" style="background:#f8fafc;font-size:12px;vertical-align:middle;">
                                            Jam {{ $jam }}<br>
                                            <span style="font-size:10px;color:#94a3b8;font-weight:400;">{{ $waktu }}</span>
                                        </td>
                                        @foreach($hariList as $hari)
                                        @php $jadwal = $jadwalKelas->where('hari', $hari)->where('jam_ke', $jam)->first(); @endphp
                                        <td style="vertical-align:middle;{{ $jadwal ? '' : 'background:#fafafa;' }}">
                                            @if($jadwal)
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-bold" style="font-size:12px;color:#166534;">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                                                    <div style="font-size:11px;color:#64748b;">{{ $jadwal->guru->nama ?? '-' }}</div>
                                                </div>
                                                <div class="d-flex gap-1 flex-shrink-0 ms-1">
                                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                                        style="width:24px;height:24px;font-size:10px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#edit{{ $jadwal->id }}">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        style="width:24px;height:24px;font-size:10px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#hapus{{ $jadwal->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @else
                                            <span style="color:#cbd5e1;font-size:11px;">-</span>
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

{{-- ===== MODALS ===== --}}
@foreach($jenjangs as $j)
@foreach($jadwalPerJenjang[$j->id] as $jadwal)
<div class="modal fade" id="edit{{ $jadwal->id }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('jadwal-pelajaran.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-edit me-1"></i> Edit Jadwal — {{ $jadwal->kelas->nama_kelas ?? '' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kelas</label>
                            <select name="kelas_id" class="form-select" required style="height:42px;border-radius:10px;">
                                @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->kelas_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kelas }} {{ $item->jenjang ? '('.$item->jenjang->nama_jenjang.')' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Guru</label>
                            <select name="guru_id" class="form-select" required style="height:42px;border-radius:10px;">
                                @foreach($gurus as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->guru_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="form-select" required style="height:42px;border-radius:10px;">
                                @foreach($mapels as $item)
                                <option value="{{ $item->id }}" {{ $jadwal->mata_pelajaran_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_mapel }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tahun Ajaran</label>
                            <input type="text" class="form-control" readonly
                                value="{{ $tahunAjaranAktif->tahun_ajaran }}"
                                style="background:#f1f5f9;border-radius:10px;height:42px;">
                            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranAktif->id }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Hari</label>
                            <select name="hari" class="form-select" required style="height:42px;border-radius:10px;">
                                @foreach($hariList as $day)
                                <option {{ $jadwal->hari == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jam Pelajaran</label>
                            <select name="jam_ke" class="form-select" required style="height:42px;border-radius:10px;">
                                <option value="1" {{ $jadwal->jam_ke == 1 ? 'selected' : '' }}>Jam 1 (07:30 - 08:30)</option>
                                <option value="2" {{ $jadwal->jam_ke == 2 ? 'selected' : '' }}>Jam 2 (08:30 - 09:30)</option>
                                <option value="3" {{ $jadwal->jam_ke == 3 ? 'selected' : '' }}>Jam 3 (10:00 - 11:00)</option>
                                <option value="4" {{ $jadwal->jam_ke == 4 ? 'selected' : '' }}>Jam 4 (11:00 - 12:00)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="hapus{{ $jadwal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <div class="delete-icon-wrap"><i class="fas fa-trash-alt"></i></div>
                </div>
                <h4 class="fw-bold mb-2">Hapus Jadwal?</h4>
                <p class="text-muted mb-4" style="font-size:13px;">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="card bg-light border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-body py-3">
                        <div class="fw-bold text-primary" style="font-size:14px;">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                        <div class="text-muted" style="font-size:12px;">{{ $jadwal->mapel->nama_mapel ?? '-' }} &middot; {{ $jadwal->hari }} Jam {{ $jadwal->jam_ke }}</div>
                    </div>
                </div>
                <form action="{{ route('jadwal-pelajaran.destroy', $jadwal->id) }}" method="POST">
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
@endforeach
@endforeach

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    document.querySelectorAll('.nav-kelas .nav-link').forEach(function(tab) {
        tab.addEventListener('click', function(e) { e.stopPropagation(); });
    });
});
</script>
@endpush
