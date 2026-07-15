@extends('layouts.main')
@section('title', 'Detail Haflatul Imtihan')
@section('content')

@include('component.admin.ms-style')

<style>
:root {
    --hi-primary: #7c3aed;
    --hi-primary-light: #ede9fe;
}

.detail-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #94a3b8;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
}

.info-row {
    display: flex;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--hi-primary);
    font-size: 16px;
    flex-shrink: 0;
    margin-right: 14px;
}

.badge-haflah {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.badge-haflah.bg-purple { background: #ede9fe; color: #7c3aed; }
.badge-haflah.bg-green { background: #d1fae5; color: #059669; }
.badge-haflah.bg-gray { background: #f1f5f9; color: #64748b; }

.btn-hi-secondary {
    background: #fff;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 24px;
    font-size: 14px;
    font-weight: 600;
    transition: all .25s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-hi-secondary:hover {
    border-color: var(--hi-primary);
    color: var(--hi-primary);
    background: var(--hi-primary-light);
}

.btn-hi-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 24px;
    font-size: 14px;
    font-weight: 600;
    transition: all .25s;
    box-shadow: 0 4px 14px rgba(245,158,11,.3);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-hi-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245,158,11,.4);
    color: #fff;
}
</style>

<div class="master-siswa-page">

    {{-- HEADER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1.5px solid #e2e8f0;">
                <div class="header-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);box-shadow:0 4px 14px rgba(124,58,237,.3);">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:var(--ms-text);font-size:20px;">{{ $haflatul->nama_acara }}</h4>
                    <p class="mb-0" style="color:var(--ms-text-soft);font-size:13px;">
                        Detail penyelenggaraan haflatul imtihan
                        @if(session('haflah_id') == $haflatul->id)
                        <span class="badge-haflah bg-green ms-2" style="font-size:11px;padding:3px 10px;">
                            <i class="fas fa-circle" style="font-size:6px;"></i> SEDANG AKTIF
                        </span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- INFO TABLE --}}
            <div style="background:#f8fafc;border-radius:14px;border:1px solid #e2e8f0;padding:6px 20px;">
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div>
                        <div class="detail-label">Tahun Ajaran</div>
                        <div class="detail-value">{{ $haflatul->tahunAjaran?->tahun_ajaran ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-day"></i></div>
                    <div>
                        <div class="detail-label">Tanggal Mulai</div>
                        <div class="detail-value">{{ \Helper::tanggal_indonesia($haflatul->tanggal_mulai) }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="detail-label">Tanggal Selesai</div>
                        <div class="detail-value">{{ \Helper::tanggal_indonesia($haflatul->tanggal_selesai) }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-flag"></i></div>
                    <div>
                        <div class="detail-label">Status</div>
                        <div>
                            @php
                            $badgeClass = match($haflatul->status) {
                                'Aktif' => 'bg-green',
                                'Persiapan' => 'bg-gray',
                                'Selesai' => 'bg-purple',
                                default => 'bg-gray'
                            };
                            $statusIcon = match($haflatul->status) {
                                'Aktif' => 'fa-play-circle',
                                'Persiapan' => 'fa-clock',
                                'Selesai' => 'fa-box-archive',
                                default => 'fa-circle'
                            };
                            @endphp
                            <span class="badge-haflah {{ $badgeClass }}">
                                <i class="fas {{ $statusIcon }}" style="font-size:11px;"></i>
                                {{ $haflatul->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex gap-3 mt-4 pt-3" style="border-top:1.5px solid #e2e8f0;">
                <a href="{{ route('haflatul-imtihan.index') }}" class="btn-hi-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('haflatul-imtihan.edit', $haflatul->id) }}" class="btn-hi-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
