@extends('layouts.main')
@section('title', 'Semester')

@section('content')
<style>
    .page-title-content { display: none !important; }

    :root {
        --ms-primary: #16a34a;
        --ms-primary-dark: #15803d;
        --ms-primary-light: #dcfce7;
        --ms-bg: #f5f7fb;
        --ms-border: #e2e8f0;
        --ms-text: #1e293b;
        --ms-text-soft: #64748b;
    }

    .semester-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }

    .header-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 24px;
        box-shadow: 0 4px 14px rgba(37,99,235,.3); flex-shrink: 0;
    }

    .badge-modern {
        display: inline-flex; align-items: center;
        padding: 4px 14px; border-radius: 20px;
        font-size: 12px; font-weight: 500; white-space: nowrap;
    }

    .badge-semester-ms {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 11px; font-weight: 500; white-space: nowrap;
    }

    .badge-semester-ms.aktif { background: #dcfce7; color: #16a34a; }
    .badge-semester-ms.nonaktif { background: #f1f5f9; color: #64748b; }

    .btn-header-ms {
        padding: 8px 20px; border-radius: 10px; font-size: 13px;
        font-weight: 600; transition: all .25s; white-space: nowrap;
        display: inline-flex; align-items: center; gap: 6px;
        border: none; height: auto;
    }

    .btn-header-ms:hover { transform: translateY(-2px); }
    .btn-header-ms:active { transform: translateY(0); }
    .btn-header-ms.btn-tambah-ms {
        background: #fff; border: 1.5px solid var(--ms-border); color: #475569;
    }
    .btn-header-ms.btn-tambah-ms:hover {
        border-color: #2563eb; color: #2563eb;
        background: #eff6ff; box-shadow: 0 3px 8px rgba(0,0,0,.08);
    }

    .table-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .table-card .card-body { padding: 16px 20px 20px; }

    .alert-modern-ms { border: none; border-radius: 12px; padding: 14px 20px; font-size: 14px; margin-bottom: 20px; }
    .alert-modern-ms.alert-success { background: #f0fdf4; color: #16a34a; border-left: 4px solid #16a34a; }
    .alert-modern-ms.alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }

    .btn-aksi-semester {
        padding: 6px 14px; border-radius: 8px; font-size: 12px;
        font-weight: 500; transition: all .25s; border: none;
        display: inline-flex; align-items: center; gap: 5px; white-space: nowrap;
    }
    .btn-aksi-semester:hover { transform: translateY(-1px); }

    .btn-aksi-semester.btn-aktifkan { background: #dcfce7; color: #16a34a; }
    .btn-aksi-semester.btn-aktifkan:hover { background: #16a34a; color: #fff; box-shadow: 0 3px 10px rgba(22,163,74,.3); }
    .btn-aksi-semester.btn-edit-semester { background: #fffbeb; color: #d97706; }
    .btn-aksi-semester.btn-edit-semester:hover { background: #d97706; color: #fff; box-shadow: 0 3px 10px rgba(217,119,6,.3); }
    .btn-aksi-semester.btn-hapus-semester { background: #fef2f2; color: #dc2626; }
    .btn-aksi-semester.btn-hapus-semester:hover { background: #dc2626; color: #fff; box-shadow: 0 3px 10px rgba(220,38,38,.3); }
    .btn-aksi-semester.btn-aktif-disabled { background: #f0fdf4; color: #16a34a; cursor: default; opacity: .7; }

    .ta-card {
        border: 1.5px solid var(--ms-border); border-radius: 14px;
        transition: all .25s; cursor: pointer;
    }
    .ta-card:hover { border-color: #2563eb; box-shadow: 0 4px 12px rgba(37,99,235,.1); }
    .ta-card.active { border-color: #2563eb; background: #eff6ff; }

    .input-group-cu { position: relative; }
    .input-group-cu-icon {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%); color: #94a3b8;
        z-index: 4; font-size: 15px; pointer-events: none;
    }
    .input-group-cu:focus-within .input-group-cu-icon { color: var(--ms-primary); }
    .input-group-cu .form-control, .input-group-cu .form-select {
        height: 46px; border: 1.5px solid #e2e8f0;
        border-radius: 12px; padding: 0 16px 0 42px; font-size: 14px;
    }
    .input-group-cu .form-control:focus, .input-group-cu .form-select:focus {
        border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1);
        outline: none;
    }
</style>

<div class="semester-page">

    {{-- Header --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">
                            Manajemen Semester
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <span class="badge-modern" style="background:#eff6ff;color:#2563eb;">
                                <i class="fas fa-list me-1"></i>
                                {{ $tahunAjaran->count() }} Tahun Ajaran
                            </span>
                            @if($tahunAktif)
                            <span class="badge-modern" style="background:#f0fdf4;color:#16a34a;">
                                <i class="fas fa-check-circle me-1"></i>
                                Aktif: {{ $tahunAktif->tahun_ajaran }} ({{ $tahunAktif->semester ?? '-' }})
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn btn-header-ms btn-tambah-ms"
                        data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="card table-card">
        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-modern-ms alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @foreach($tahunAjaran as $ta)
            <div class="ta-card p-3 mb-3 {{ $ta->status == 'Aktif' ? 'active' : '' }}">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1" style="font-size:16px;">
                            {{ $ta->tahun_ajaran }}
                            @if($ta->status == 'Aktif')
                            <span class="badge bg-success ms-2" style="font-size:10px;">Aktif</span>
                            @else
                            <span class="badge bg-secondary ms-2" style="font-size:10px;">Tidak Aktif</span>
                            @endif
                        </h5>
                        <small class="text-muted">Semester aktif: <strong>{{ $ta->semester ?? '-' }}</strong></small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Semester</th>
                                <th style="width:120px;">Status</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th style="width:180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ta->semesters as $i => $semester)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge-semester-ms {{ $semester->aktif ? 'aktif' : 'nonaktif' }}">
                                        @if($semester->aktif)
                                        <i class="fas fa-check-circle"></i>
                                        @else
                                        <i class="fas fa-clock"></i>
                                        @endif
                                        {{ $semester->nama }}
                                    </span>
                                </td>
                                <td>
                                    @if($semester->aktif)
                                    <span class="badge bg-success" style="font-size:11px;">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary" style="font-size:11px;">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $semester->tanggal_mulai ? \Carbon\Carbon::parse($semester->tanggal_mulai)->translatedFormat('d F Y') : '-' }}</td>
                                <td>{{ $semester->tanggal_selesai ? \Carbon\Carbon::parse($semester->tanggal_selesai)->translatedFormat('d F Y') : '-' }}</td>
                                <td>
                                    @if($semester->aktif)
                                    <div class="d-flex gap-1">
                                        <button class="btn-aksi-semester btn-aktif-disabled" disabled>
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </button>
                                        <button type="button" class="btn-aksi-semester btn-edit-semester"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $semester->id }}">
                                            <i class="fas fa-pen"></i> Edit
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-muted" style="font-size:12px;">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="modalEdit{{ $semester->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('semester.update', $semester->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                                                <h5 class="modal-title text-white fw-bold">
                                                    <i class="fas fa-edit me-1"></i> Edit Semester
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Semester</label>
                                                    <div class="input-group-cu">
                                                        <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                                                        <input type="text" class="form-control" value="{{ $semester->nama }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control"
                                                        value="{{ $semester->tanggal_mulai ? $semester->tanggal_mulai->format('Y-m-d') : '' }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" class="form-control"
                                                        value="{{ $semester->tanggal_selesai ? $semester->tanggal_selesai->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle me-1"></i> Belum ada semester.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

        </div>
    </div>

</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('semester.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-plus me-1"></i> Tambah Semester
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($tahunAktif)
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control" value="{{ $tahunAktif->tahun_ajaran }}" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Semester</label>
                        <div class="input-group-cu">
                            <span class="input-group-cu-icon"><i class="fas fa-layer-group"></i></span>
                            <select name="nama" class="form-select" required>
                                <option value="">— Pilih Semester —</option>
                                <option value="Ganjil">Ganjil</option>
                                @if($tahunAktif->semesters()->where('nama', 'Ganjil')->exists())
                                <option value="Genap">Genap</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" style="border-radius:12px;height:46px;padding:0 16px;border:1.5px solid #e2e8f0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" style="border-radius:12px;height:46px;padding:0 16px;border:1.5px solid #e2e8f0;">
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size:3rem;"></i>
                        <p class="mt-3 mb-0 fw-semibold">Belum ada tahun ajaran aktif</p>
                        <small class="text-muted">Aktifkan tahun ajaran terlebih dahulu sebelum menambah semester.</small>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    @if($tahunAktif)
                    <button type="submit" class="btn btn-primary" style="background:#2563eb;border:none;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
