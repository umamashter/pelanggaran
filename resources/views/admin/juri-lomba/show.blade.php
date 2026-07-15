@extends('layouts.main')
@section('title', 'Detail Juri Lomba')
@push('css')
<style>
    .detail-juri-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 680px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .detail-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .detail-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .detail-card-body { padding: 24px 28px 28px; }
    .info-label { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .info-value { font-weight: 700; font-size: 16px; color: #1e293b; }
    .badge-cu { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; }
    .badge-cu-green { background: #f0fdf4; color: #16a34a; }
    .badge-cu-blue { background: #eff6ff; color: #2563eb; }
    .btn-cu { height: 40px; padding: 0 22px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 6px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    @media (max-width: 768px) {
        .detail-juri-page { margin-top: 16px; padding: 0 12px; }
        .detail-card-header, .detail-card-body { padding: 18px 20px; }
    }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="detail-juri-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('juri-lomba.index') }}">Juri Lomba</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="card detail-card">
        <div class="detail-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Detail Juri Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Lomba: <strong>{{ $juriLomba->lomba->nama ?? '-' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="detail-card-body">

            <div class="row g-4 mb-4">
                <div class="col-sm-4">
                    <div class="info-label">Lomba</div>
                    <div class="info-value">{{ $juriLomba->lomba->nama ?? '-' }}</div>
                </div>
                <div class="col-sm-4">
                    <div class="info-label">Jenis</div>
                    <div class="info-value">{{ $juriLomba->lomba->jenis ?? '-' }}</div>
                </div>
                <div class="col-sm-4">
                    <div class="info-label">Total Juri</div>
                    <div class="info-value">{{ $allJuri->count() }}</div>
                </div>
            </div>

            <div class="info-label mb-2">Daftar Juri</div>
            <div class="table-responsive" style="border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                <table class="table table-bordered mb-0" style="font-size:13px;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="width:40px;text-align:center;padding:10px 6px;">No</th>
                            <th style="padding:10px 10px;">Nama Juri</th>
                            <th style="width:120px;text-align:center;padding:10px 6px;">Jumlah Penilaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allJuri as $idx => $j)
                        <tr>
                            <td style="text-align:center;vertical-align:middle;padding:10px 6px;">{{ $idx + 1 }}</td>
                            <td style="vertical-align:middle;padding:10px 10px;">
                                {{ $j->guru->nama ?? '-' }}
                                @if($j->id === $juriLomba->id)
                                    <span class="badge-cu badge-cu-green" style="margin-left:6px;">Aktif</span>
                                @endif
                            </td>
                            <td style="text-align:center;vertical-align:middle;padding:10px 6px;">
                                <span class="badge-cu badge-cu-blue">{{ $j->penilaian_count }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:16px;color:#94a3b8;">Belum ada juri</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('juri-lomba.index') }}" class="btn btn-cu btn-cu-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('juri-lomba.edit', $juriLomba->id) }}" class="btn btn-cu btn-cu-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
