@extends('layouts.main')
@section('title','Detail Absensi Guru')
@section('content')
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #0ea5e9; --ms-primary-dark: #0284c7; --ms-primary-light: #e0f2fe; --ms-border: #e2e8f0; --ms-text: #1e293b; }
.master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #0ea5e9, #38bdf8); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(14,165,233,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.info-card-modern { background: #f0f9ff; border-left: 4px solid #0ea5e9; border-radius: 12px; padding: 16px 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 16px 24px; font-size: 14px; color: #075985; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.info-card-modern .info-item { display: flex; align-items: center; gap: 8px; }
.info-card-modern .info-item i { color: #0ea5e9; font-size: 16px; width: 18px; text-align: center; }
.detail-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.detail-card .card-body { padding: 20px 24px; }
.foto-preview { max-width: 300px; border-radius: 14px; border: 3px solid var(--ms-border); box-shadow: 0 4px 16px rgba(0,0,0,.1); }
.detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
.detail-row:last-child { border-bottom: none; }
.detail-label { color: #64748b; font-size: 13px; font-weight: 500; }
.detail-value { color: #1e293b; font-size: 13px; font-weight: 600; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
@media (max-width: 768px) { .info-card-modern { flex-direction: column; gap: 8px; align-items: flex-start; } .detail-card .card-body { padding: 16px; } .foto-preview { max-width: 100%; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-user-check"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Detail Absensi Guru</h4>
                    <span class="badge-modern" style="background:#eff6ff;color:#1d4ed8;">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ $absensi->tanggal->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-card-modern mb-4">
        <div class="info-item"><i class="fas fa-user"></i><span><strong>Guru :</strong> {{ $absensi->user->name ?? '-' }}</span></div>
        <div class="info-item"><i class="fas fa-clock"></i><span><strong>Jam Masuk :</strong> {{ substr($absensi->jam_masuk, 0, 5) }} WIB</span></div>
        <div class="info-item"><i class="fas fa-map-marker-alt"></i><span><strong>Jarak :</strong> {{ round($absensi->jarak_masuk) }} meter</span></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card detail-card">
                <div class="card-body text-center">
                    <img src="{{ asset('storage/absensi-guru/foto/' . $absensi->foto_masuk) }}" class="foto-preview mb-3" alt="Foto Selfie">
                    <p style="color:#64748b;font-size:12px;">Foto Selfie Absensi</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card detail-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color:#1e293b;"><i class="fas fa-info-circle me-1" style="color:#0ea5e9;"></i>Informasi Absensi</h6>
                    <div class="detail-row">
                        <span class="detail-label">Nama Guru</span>
                        <span class="detail-value">{{ $absensi->user->name ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal</span>
                        <span class="detail-value">{{ $absensi->tanggal->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Jam Masuk</span>
                        <span class="detail-value">{{ substr($absensi->jam_masuk, 0, 5) }} WIB</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Latitude</span>
                        <span class="detail-value">{{ $absensi->latitude_masuk }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Longitude</span>
                        <span class="detail-value">{{ $absensi->longitude_masuk }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Jarak dari Madrasah</span>
                        <span class="detail-value">{{ round($absensi->jarak_masuk) }} meter</span>
                    </div>
                    @if($absensi->akurasi_gps)
                    <div class="detail-row">
                        <span class="detail-label">Akurasi GPS</span>
                        <span class="detail-value">{{ $absensi->akurasi_gps }} meter</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.absensi-guru.index') }}" class="btn-kembali-ms">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
