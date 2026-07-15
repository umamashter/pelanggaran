@extends('layouts.main')
@section('title', 'Detail Kelompok Lomba')
@push('css')
<style>
    .detail-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 860px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu {
        margin-bottom: 20px;
    }
    .breadcrumb-cu .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .breadcrumb-cu .breadcrumb-item {
        font-size: 13px;
    }
    .breadcrumb-cu .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color .2s;
    }
    .breadcrumb-cu .breadcrumb-item a:hover {
        color: #16a34a;
    }
    .breadcrumb-cu .breadcrumb-item.active {
        color: #1e293b;
        font-weight: 500;
    }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before {
        color: #cbd5e1;
    }
    .detail-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04);
    }
    .detail-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .detail-card-body {
        padding: 24px 28px 28px;
    }
    .info-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 15px;
        font-weight: 500;
        color: #1e293b;
    }
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .anggota-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin: 28px 0 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .anggota-section-title .badge-count {
        background: #dcfce7;
        color: #16a34a;
        font-size: 13px;
        padding: 2px 12px;
        border-radius: 20px;
        font-weight: 500;
    }
    .anggota-table-card {
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        overflow: hidden;
    }
    .anggota-table-card table {
        margin: 0;
    }
    .anggota-table-card table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 11px 16px;
        border-bottom: 2px solid #e2e8f0;
    }
    .anggota-table-card table tbody td {
        padding: 10px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .anggota-table-card table tbody tr:last-child td {
        border-bottom: none;
    }
    .anggota-table-card table tbody tr:hover td {
        background: #f8fafc;
    }
    .action-bar {
        display: flex;
        gap: 10px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }
    .btn-cu-action {
        height: 44px;
        padding: 0 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border: none;
        gap: 8px;
        text-decoration: none;
    }
    .btn-cu-action-primary {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        box-shadow: 0 2px 8px rgba(22,163,74,.25);
    }
    .btn-cu-action-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(22,163,74,.35);
        color: #fff;
    }
    .btn-cu-action-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }
    .btn-cu-action-secondary:hover {
        background: #e2e8f0;
        color: #334155;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,.08);
    }
    @media (max-width: 768px) {
        .detail-page {
            margin-top: 16px;
            padding: 0 12px;
        }
        .detail-card-header {
            padding: 18px 20px 16px;
        }
        .detail-card-body {
            padding: 18px 20px 22px;
        }
        .action-bar {
            flex-direction: column;
        }
        .action-bar .btn-cu-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="detail-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('anggota-kelompok.index') }}">Anggota Kelompok</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail Kelompok</li>
        </ol>
    </nav>

    <div class="card detail-card">
        <div class="detail-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Detail Kelompok Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Informasi kelompok dan daftar anggota</span>
                </div>
            </div>
        </div>

        <div class="detail-card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Lomba</div>
                        <div class="info-value">{{ $kelompokLomba->lomba->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Nama Kelompok</div>
                        <div class="info-value">{{ $kelompokLomba->nama_kelompok }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Kelas</div>
                        <div class="info-value">
                            @php $l = $kelompokLomba->lomba; @endphp
                            @if($l && $l->kelas_min && $l->kelas_max)
                            <span class="badge" style="background:#c2410c;color:#fff;">Kelas {{ $l->kelas_min == $l->kelas_max ? $l->kelas_min : $l->kelas_min.' - '.$l->kelas_max }}</span>
                            @else
                            <span class="badge" style="background:#16a34a;color:#fff;">Semua Kelas</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Kode Kelompok</div>
                        <div class="info-value">{{ $kelompokLomba->kode_kelompok ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="anggota-section-title">
                <i class="fas fa-user-graduate" style="color: #16a34a;"></i>
                Anggota Kelompok
                <span class="badge-count">{{ $kelompokLomba->anggota->count() }} siswa</span>
            </div>

            <div class="anggota-table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:60px;text-align:center;">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Tingkat</th>
                            <th>Jenjang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompokLomba->anggota as $i => $anggota)
                        <tr>
                            <td style="text-align:center;">{{ $i + 1 }}</td>
                            <td>{{ $anggota->student->nisn ?? '-' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:600;flex-shrink:0;">
                                        {{ strtoupper(substr($anggota->student->user->name ?? $anggota->student->nama ?? '-', 0, 1)) }}
                                    </div>
                                    <span>{{ $anggota->student->user->name ?? $anggota->student->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ $anggota->student->kelasAktif->kelas->tingkat ?? '-' }}</td>
                            <td>{{ $anggota->student->kelasAktif->kelas->jenjang->nama_jenjang ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center" style="padding:32px;color:#94a3b8;">Belum ada anggota</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="action-bar">
                <a href="{{ route('anggota-kelompok.index') }}" class="btn-cu-action btn-cu-action-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('kelompok-lomba.edit', $kelompokLomba->id) }}" class="btn-cu-action btn-cu-action-primary">
                    <i class="fas fa-edit"></i> Edit Kelompok
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
