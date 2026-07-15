@extends('layouts.main')
@section('title', 'Detail Lomba')
@push('css')
<style>
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<style>
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
    }
    .detail-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px 18px;
        border: 1px solid var(--ms-border);
        transition: all .2s;
    }
    .detail-item:hover {
        background: #fff;
        border-color: var(--ms-primary);
        box-shadow: 0 2px 8px rgba(22,163,74,.08);
    }
    .detail-item .label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .detail-item .value {
        font-size: 15px;
        font-weight: 600;
        color: var(--ms-text);
        word-break: break-word;
    }
    .detail-item.full-width {
        grid-column: 1 / -1;
    }
    .detail-item .value.deskripsi {
        font-weight: 400;
        white-space: pre-line;
    }
    .status-pill-show {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pill-show.belum-mulai {
        background: #f1f5f9;
        color: #64748b;
    }
    .status-pill-show.berlangsung {
        background: #dcfce7;
        color: #16a34a;
    }
    .status-pill-show.selesai {
        background: #dbeafe;
        color: #2563eb;
    }
    .count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        background: #f0fdf4;
        color: #16a34a;
    }
    .count-badge.juri {
        background: #eff6ff;
        color: #2563eb;
    }
    @media (max-width: 640px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="master-siswa-page">
    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('lomba.index') }}">Lomba</a></li>
            <li class="breadcrumb-item active">Detail Lomba</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-eye"></i></div>
                <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">{{ $lomba->nama }}</h4></div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('lomba.index') }}" class="btn btn-header-ms btn-simpan-ms" style="background: linear-gradient(135deg, #f59e0b, #f97316); box-shadow: 0 2px 8px rgba(245,158,11,.25);"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                <a href="{{ route('lomba.edit', $lomba->id) }}" class="btn btn-header-ms btn-simpan-ms"><i class="fas fa-edit me-1"></i> Edit</a>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="label">Haflah</div>
                    <div class="value">{{ $lomba->haflatulImtihan->nama_acara ?? '-' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Sesi</div>
                    <div class="value">{{ $lomba->sesiLomba->nama ?? '-' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Kategori</div>
                    <div class="value">{{ $lomba->kategori->nama ?? '-' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Jenis</div>
                    <div class="value">{{ $lomba->jenis }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Peserta</div>
                    <div class="value">{{ $lomba->kelas_min && $lomba->kelas_max ? ($lomba->kelas_min == $lomba->kelas_max ? 'Kelas '.$lomba->kelas_min : 'Kelas '.$lomba->kelas_min.' - '.$lomba->kelas_max) : 'Semua Kelas' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Status</div>
                    <div class="value">
                        @php
                        $badgeClass = match($lomba->status) {
                        'Belum Mulai' => 'belum-mulai',
                        'Berlangsung' => 'berlangsung',
                        'Selesai' => 'selesai',
                        default => 'belum-mulai'
                        };
                        @endphp
                        <span class="status-pill-show {{ $badgeClass }}">{{ $lomba->status }}</span>
                    </div>
                </div>
                <div class="detail-item full-width">
                    <div class="label">Deskripsi</div>
                    <div class="value deskripsi">{{ $lomba->deskripsi ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card mt-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="header-icon" style="width:42px;height:42px;font-size:18px;"><i class="fas fa-chart-bar"></i></div>
                <div><h5 class="mb-0 fw-bold" style="color: var(--ms-text);">Data Terkait</h5></div>
            </div>
            <div class="d-flex flex-wrap gap-3">
                <span class="count-badge"><i class="fas fa-users"></i> {{ $totalPesertaLomba }} Total Peserta</span>
                <span class="count-badge" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-check-circle"></i> {{ $pesertaTerdaftar }} Terdaftar</span>
                <span class="count-badge" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-hourglass-half"></i> {{ $pesertaBelumTerdaftar }} Belum Terdaftar</span>
                <span class="count-badge juri"><i class="fas fa-gavel"></i> {{ $totalJuri }} Juri</span>
            </div>
        </div>
    </div>
</div>
@endsection
