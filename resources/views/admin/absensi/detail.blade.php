@extends('layouts.main')
@section('title','Detail Absensi')
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
.table-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.table-card .card-body { padding: 16px 20px 20px; }
.table-detail { border-collapse: collapse; width: 100% !important; border: 1px solid var(--ms-border); border-radius: 12px; margin: 0 !important; }
.table-detail thead th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; padding: 11px 14px; border-bottom: 2px solid var(--ms-border); white-space: nowrap; text-align: center; }
.table-detail tbody td { padding: 10px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; line-height: 1.5; }
.table-detail tbody tr:last-child td { border-bottom: none; }
.table-detail tbody tr:hover td { background: #f8fafc; }
.badge-status-ms { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-status-ms.hadir { background: #f0fdf4; color: #16a34a; }
.badge-status-ms.izin { background: #fffbeb; color: #d97706; }
.badge-status-ms.sakit { background: #fef2f2; color: #dc2626; }
.badge-status-ms.alpha { background: #f1f5f9; color: #64748b; }
.btn-kembali-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1.5px solid var(--ms-border); background: #fff; color: #475569; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-kembali-ms:hover { border-color: var(--ms-primary); color: var(--ms-primary); background: var(--ms-primary-light); }
.btn-edit-ms { padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; border: none; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.btn-edit-ms:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245,158,11,.3); color: #fff; }
@media (max-width: 768px) { .info-card-modern { flex-direction: column; gap: 8px; align-items: flex-start; } .table-detail thead th { font-size: 11px; padding: 9px 8px; } .table-detail tbody td { padding: 8px; font-size: 12px; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-eye"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Detail Absensi</h4>
                    <span class="badge-modern" style="background:#eff6ff;color:#1d4ed8;">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ $absensi->tanggal->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-card-modern mb-4">
        <div class="info-item"><i class="fas fa-chalkboard"></i><span><strong>Kelas :</strong> {{ $absensi->kelas->nama_kelas }}</span></div>
        <div class="info-item"><i class="fas fa-graduation-cap"></i><span><strong>Tahun Ajaran :</strong> {{ $absensi->tahunAjaran->tahun_ajaran }}</span></div>
        <div class="info-item"><i class="fas fa-user"></i><span><strong>Dicatat oleh :</strong> {{ $absensi->user->name ?? '-' }}</span></div>
    </div>

    <div class="card table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-detail">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th width="150">Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi->details as $detail)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $detail->student->nisn }}</td>
                            <td>{{ $detail->student->nama }}</td>
                            <td class="text-center">
                                @if($detail->status == 'H')
                                <span class="badge-status-ms hadir"><i class="fas fa-check-circle me-1"></i>Hadir</span>
                                @elseif($detail->status == 'I')
                                <span class="badge-status-ms izin"><i class="fas fa-clock me-1"></i>Izin</span>
                                @elseif($detail->status == 'S')
                                <span class="badge-status-ms sakit"><i class="fas fa-thermometer-half me-1"></i>Sakit</span>
                                @else
                                <span class="badge-status-ms alpha"><i class="fas fa-times-circle me-1"></i>Alpha</span>
                                @endif
                            </td>
                            <td>{{ $detail->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('absensi.edit', $absensi->id) }}" class="btn-edit-ms">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('absensi.riwayat') }}" class="btn-kembali-ms">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
