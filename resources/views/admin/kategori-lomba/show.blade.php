@extends('layouts.main')
@section('title', 'Detail Kategori Lomba')
@push('css')
<style>
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
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
    .swatch-preview {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .swatch-preview .swatch {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .icon-preview {
        font-size: 24px;
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 12px;
        color: var(--ms-primary);
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
    @media (max-width: 640px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="master-siswa-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kategori-lomba.index') }}">Kategori Lomba</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-eye"></i></div>
                <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">{{ $kategoriLomba->nama }}</h4></div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('kategori-lomba.index') }}" class="btn btn-header-ms btn-simpan-ms" style="background: linear-gradient(135deg, #f59e0b, #f97316); box-shadow: 0 2px 8px rgba(245,158,11,.25);"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                <a href="{{ route('kategori-lomba.edit', $kategoriLomba->id) }}" class="btn btn-header-ms btn-simpan-ms"><i class="fas fa-edit me-1"></i> Edit</a>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="label">Nama</div>
                    <div class="value">{{ $kategoriLomba->nama }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Warna</div>
                    <div class="value swatch-preview">
                        <span class="swatch" style="background:{{ $kategoriLomba->warna }}"></span>
                        {{ $kategoriLomba->warna }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="label">Icon</div>
                    <div class="value d-flex align-items-center gap-2">
                        <span class="icon-preview"><i class="{{ $kategoriLomba->icon }}"></i></span>
                        <span>{{ $kategoriLomba->icon }}</span>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="label">Urutan</div>
                    <div class="value">{{ $kategoriLomba->urutan }}</div>
                </div>
            </div>
        </div>
    </div>

    @php $totalLombas = $kategoriLomba->lombas->count(); @endphp
    @if($totalLombas > 0)
    <div class="card table-card mt-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="header-icon" style="width:42px;height:42px;font-size:18px;"><i class="fas fa-chart-bar"></i></div>
                <div><h5 class="mb-0 fw-bold" style="color: var(--ms-text);">Data Terkait</h5></div>
            </div>
            <div class="d-flex flex-wrap gap-3">
                <span class="count-badge"><i class="fas fa-trophy"></i> {{ $totalLombas }} Lomba</span>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection