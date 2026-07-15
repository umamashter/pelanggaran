@extends('layouts.main')
@section('title', 'Detail Sesi Lomba')
@push('css')
<style>
    .create-sesi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; max-width: 680px; margin: 22px auto 0; padding: 0 16px; }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 24px 28px 28px; }
    .header-icon { width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(22,163,74,.25); }
    .detail-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; color: #94a3b8; margin-bottom: 4px; }
    .detail-value { font-size: 16px; font-weight: 600; color: #1e293b; }
    .info-row { display: flex; align-items: center; padding: 14px 0; border-bottom: 1px solid #f1f5f9; }
    .info-row:last-child { border-bottom: none; }
    .info-row .info-icon { width: 40px; height: 40px; border-radius: 10px; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #16a34a; font-size: 16px; flex-shrink: 0; margin-right: 14px; }
    .badge-show { display: inline-flex; align-items: center; gap: 5px; padding: 5px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap; }
    .badge-show.bg-pink { background: #fdf2f8; color: #be185d; }
    .badge-show.bg-blue { background: #eff6ff; color: #1d4ed8; }
    .badge-show.bg-gray { background: #f1f5f9; color: #64748b; }
    .btn-cu { height: 44px; padding: 0 28px; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; gap: 8px; border: none; text-decoration: none; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    @media (max-width:768px) { .create-sesi-page { margin-top:16px; padding:0 12px; } .create-card-header { padding:18px 20px 16px; } .create-card-body { padding:18px 20px 22px; } .form-actions-cu { flex-direction:column; } .form-actions-cu .btn-cu { width:100%; } }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="create-sesi-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sesi-lomba.index') }}">Sesi Lomba</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-calendar-week"></i></div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1e293b;font-size:18px;">{{ $sesiLomba->nama }}</h4>
                    <span style="font-size:13px;color:#64748b;">Detail sesi lomba haflatul imtihan</span>
                </div>
            </div>
        </div>
        <div class="create-card-body">

            <div style="background:#f8fafc;border-radius:14px;border:1px solid #e2e8f0;padding:6px 20px;">
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div>
                        <div class="detail-label">Haflatul Imtihan</div>
                        <div class="detail-value">{{ $sesiLomba->haflatulImtihan->nama_acara ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-tag"></i></div>
                    <div>
                        <div class="detail-label">Nama Sesi</div>
                        <div class="detail-value">{{ $sesiLomba->nama }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-day"></i></div>
                    <div>
                        <div class="detail-label">Tanggal</div>
                        <div class="detail-value">
                            <span class="badge-show bg-pink">
                                <i class="fas fa-calendar-day me-1"></i>
                                {{ \Carbon\Carbon::parse($sesiLomba->tanggal)->isoFormat('D MMM YYYY') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="detail-label">Jam</div>
                        <div class="detail-value">
                            <span class="badge-show bg-blue"><i class="fas fa-play me-1"></i>{{ $sesiLomba->jam_mulai ? \Carbon\Carbon::parse($sesiLomba->jam_mulai)->format('H:i') : '-' }}</span>
                            <span style="color:#94a3b8;margin:0 6px;">s/d</span>
                            <span class="badge-show bg-blue"><i class="fas fa-stop me-1"></i>{{ $sesiLomba->jam_selesai ? \Carbon\Carbon::parse($sesiLomba->jam_selesai)->format('H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                @if($sesiLomba->keterangan)
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-align-left"></i></div>
                    <div>
                        <div class="detail-label">Keterangan</div>
                        <div class="detail-value" style="font-weight:400;font-size:14px;">{{ $sesiLomba->keterangan }}</div>
                    </div>
                </div>
                @endif
            </div>

            <div class="form-actions-cu">
                <a href="{{ route('sesi-lomba.index') }}" class="btn btn-cu btn-cu-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('sesi-lomba.edit', $sesiLomba->id) }}" class="btn btn-cu btn-cu-primary"><i class="fas fa-edit"></i> Edit</a>
            </div>

        </div>
    </div>
</div>
@endsection
