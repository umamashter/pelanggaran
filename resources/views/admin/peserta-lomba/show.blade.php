@extends('layouts.main')
@section('title', 'Detail Peserta Lomba')
@push('css')
<style>
    .detail-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; max-width: 680px; margin: 22px auto 0; padding: 0 16px; }
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
    .info-row { display: flex; align-items: center; padding: 14px 0; }
    .info-row .info-icon { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0; margin-right: 14px; box-shadow: 0 2px 8px rgba(22,163,74,.2); }
    .badge-detail { display: inline-flex; align-items: center; gap: 5px; padding: 5px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap; }
    .badge-detail.terdaftar { background: #dbeafe; color: #2563eb; }
    .badge-detail.hadir { background: #dcfce7; color: #16a34a; }
    .badge-detail.tidak-hadir { background: #fee2e2; color: #dc2626; }
    .badge-detail.diskualifikasi { background: #fef3c7; color: #d97706; }

    .btn-header-ms.btn-simpan-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    @media (max-width:768px) { .detail-page { margin-top:16px; padding:0 12px; } .create-card-header { padding:18px 20px 16px; } .create-card-body { padding:18px 20px 22px; } .form-actions-cu { flex-direction:column; } .form-actions-cu .btn, .form-actions-cu a { width:100%; text-align:center; } }
</style>
@endpush
@section('content')
@include('component.admin.ms-style')
<div class="detail-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta-lomba.index') }}">Peserta Lomba</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1e293b;font-size:18px;">
                        @if($pesertaLomba->isIndividu())
                            {{ $pesertaLomba->student->user->name ?? 'Peserta Lomba' }}
                        @elseif($pesertaLomba->kelompokLomba)
                            {{ $pesertaLomba->kelompokLomba->nama_kelompok }}
                        @else
                            Peserta Lomba
                        @endif
                    </h4>
                    <span style="font-size:13px;color:#64748b;">Detail peserta lomba haflatul imtihan</span>
                </div>
            </div>
        </div>
        <div class="create-card-body">

            <div style="background:#f8fafc;border-radius:14px;border:1px solid #e2e8f0;padding:6px 20px;">
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-trophy"></i></div>
                    <div>
                        <div class="detail-label">Lomba</div>
                        <div class="detail-value">{{ $pesertaLomba->lomba->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-week"></i></div>
                    <div>
                        <div class="detail-label">Sesi</div>
                        <div class="detail-value">{{ $pesertaLomba->lomba->sesiLomba->nama ?? '-' }}</div>
                        <div class="detail-label" style="margin-top:6px;">Tanggal</div>
                        <div class="detail-value" style="font-size:14px;">{{ !empty($pesertaLomba->lomba->sesiLomba->tanggal) ? \Carbon\Carbon::parse($pesertaLomba->lomba->sesiLomba->tanggal)->translatedFormat('d F Y') : '-' }}</div>
                    </div>
                </div>
                @if($pesertaLomba->isIndividu())
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-user"></i></div>
                    <div>
                        <div class="detail-label">Nama Siswa</div>
                        <div class="detail-value">{{ $pesertaLomba->student->user->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-id-card"></i></div>
                    <div>
                        <div class="detail-label">NISN</div>
                        <div class="detail-value">{{ $pesertaLomba->student->nisn ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-door-open"></i></div>
                    <div>
                        <div class="detail-label">Kelas</div>
                        <div class="detail-value">{{ $pesertaLomba->student->kelasAktif->kelas->nama_kelas ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="detail-label">Jenjang</div>
                        <div class="detail-value">{{ $pesertaLomba->student->kelasAktif->kelas->jenjang->nama_jenjang ?? '-' }}</div>
                    </div>
                </div>
                @elseif($pesertaLomba->kelompokLomba)
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="detail-label">Nama Kelompok</div>
                        <div class="detail-value">{{ $pesertaLomba->kelompokLomba->nama_kelompok }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-tag"></i></div>
                    <div>
                        <div class="detail-label">Kode Kelompok</div>
                        <div class="detail-value">{{ $pesertaLomba->kelompokLomba->kode_kelompok ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-id-card"></i></div>
                    <div>
                        <div class="detail-label">Anggota</div>
                        <div class="detail-value">
                            <ul style="margin:4px 0 0;padding-left:20px;">
                            @foreach($pesertaLomba->kelompokLomba->anggota as $agt)
                                <li>{{ $agt->student->user->name ?? $agt->student->nama ?? '-' }}</li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-flag"></i></div>
                    <div>
                        <div class="detail-label">Status</div>
                        <div>
                            @php
                                $badgeClass = match($pesertaLomba->status) {
                                    'Terdaftar' => 'terdaftar',
                                    'Hadir' => 'hadir',
                                    'Tidak Hadir' => 'tidak-hadir',
                                    'Diskualifikasi' => 'diskualifikasi',
                                    default => 'terdaftar'
                                };
                                $statusIcon = match($pesertaLomba->status) {
                                    'Terdaftar' => 'fa-user-check',
                                    'Hadir' => 'fa-check-circle',
                                    'Tidak Hadir' => 'fa-times-circle',
                                    'Diskualifikasi' => 'fa-exclamation-triangle',
                                    default => 'fa-circle'
                                };
                            @endphp
                            <span class="badge-detail {{ $badgeClass }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                {{ $pesertaLomba->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions-cu">
                <a href="{{ route('peserta-lomba.index') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('peserta-lomba.edit', $pesertaLomba->id) }}" class="btn btn-header-ms btn-simpan-ms btn-compact"><i class="fas fa-edit"></i> Edit</a>
            </div>

        </div>
    </div>
</div>
@endsection
