@extends('layouts.main')
@section('title', 'Detail Hasil Lomba')
@section('content')
@include('component.admin.ms-style')
<style>
    .btn-header-ms.btn-simpan-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }
    .badge-tipe-ms {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
    }
    .badge-tipe-ms.individu { background: #eff6ff; color: #2563eb; }
    .badge-tipe-ms.kelompok { background: #fef3c7; color: #92400e; }
    html.dark-mode .badge-tipe-ms.individu { background: rgba(37,99,235,.15); color: #60a5fa; }
    html.dark-mode .badge-tipe-ms.kelompok { background: rgba(217,119,6,.15); color: #fbbf24; }

    .detail-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;
    }
    .detail-grid .dg-item {
        padding: 14px 18px; border-bottom: 1px solid #f1f5f9;
        display: flex; flex-direction: column; gap: 4px;
    }
    .detail-grid .dg-item:nth-child(odd) { border-right: 1px solid #f1f5f9; }
    .detail-grid .dg-item:last-child,
    .detail-grid .dg-item:nth-last-child(2):nth-child(odd) { border-bottom: none; }
    .detail-grid .dg-label {
        font-size: 11px; font-weight: 600; text-transform: uppercase;
        letter-spacing: .5px; color: #94a3b8;
    }
    .detail-grid .dg-value { font-size: 14px; font-weight: 500; color: #1e293b; }
    html.dark-mode .detail-grid .dg-value { color: var(--text-primary, #e2e8f0); }
    .detail-grid .dg-full { grid-column: 1 / -1; }
    @media (max-width: 576px) {
        .detail-grid { grid-template-columns: 1fr; }
        .detail-grid .dg-item:nth-child(odd) { border-right: none; }
    }
</style>
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-eye"></i></div>
                <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Detail Hasil Lomba</h4></div>
            </div>
        </div>
    </div>
    <div class="card table-card">
        <div class="card-body">
            @php
                $pl = $hasilLomba->pesertaLomba;
                $tab = $pl->isIndividu() ? 'individu' : 'kelompok';
                $backUrl = route('hasil-lomba.index', ['tab' => $tab]);
            @endphp
            <div class="detail-grid">
                <div class="dg-item">
                    <span class="dg-label">Lomba</span>
                    <span class="dg-value">{{ $hasilLomba->lomba->nama ?? '-' }}</span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Tipe</span>
                    <span class="dg-value">
                        @if($pl->isIndividu())
                            <span class="badge-tipe-ms individu"><i class="fas fa-user"></i> Individu</span>
                        @else
                            <span class="badge-tipe-ms kelompok"><i class="fas fa-users"></i> Kelompok</span>
                        @endif
                    </span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Sesi</span>
                    <span class="dg-value">{{ $hasilLomba->lomba->sesiLomba->nama ?? '-' }}</span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Jumlah Juri</span>
                    <span class="dg-value">{{ $hasilLomba->lomba->juri->count() ?? 0 }}</span>
                </div>
                <div class="dg-item dg-full">
                    <span class="dg-label">Juri</span>
                    <span class="dg-value">
                        @php $juriList = $hasilLomba->lomba->juri; @endphp
                        @if($juriList->isEmpty())
                            -
                        @else
                            @foreach($juriList as $j)
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:14px;font-size:12px;font-weight:500;background:#eff6ff;color:#2563eb;margin:2px 4px 2px 0;">
                                    <i class="fas fa-gavel" style="font-size:10px;"></i> {{ $j->guru->nama ?? 'Juri #' . $j->id }}
                                </span>
                            @endforeach
                        @endif
                    </span>
                </div>
                <div class="dg-item dg-full">
                    <span class="dg-label">Peserta</span>
                    <span class="dg-value" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        @if($pl->isIndividu())
                            {{ $pl->student->user->name ?? '-' }}
                        @elseif($pl->kelompokLomba)
                            <i class="fas fa-users me-1"></i>{{ $pl->kelompokLomba->nama_kelompok }}
                            <a href="{{ route('anggota-kelompok.index', ['lomba_id' => $hasilLomba->lomba_id]) }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);box-shadow:0 2px 8px rgba(139,92,246,.25);font-size:11px;height:26px;padding:0 10px;"><i class="fas fa-users"></i> Lihat Anggota</a>
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Nilai Penilaian</span>
                    <span class="dg-value">{{ $hasilLomba->total_dari_penilaian ?? 0 }}</span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Total Nilai</span>
                    <span class="dg-value" style="font-weight:700;">{{ $hasilLomba->total_nilai }}</span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Peringkat</span>
                    <span class="dg-value" style="font-weight:700;color:#16a34a;">{{ $hasilLomba->peringkat }}</span>
                </div>
                <div class="dg-item">
                    <span class="dg-label">Juara</span>
                    <span class="dg-value" style="font-weight:700;color:#16a34a;">{{ $hasilLomba->juara ?? '-' }}</span>
                </div>
            </div>
            <div class="form-actions-cu" style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:24px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <a href="{{ $backUrl }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('hasil-lomba.edit', $hasilLomba->id) }}" class="btn btn-header-ms btn-simpan-ms btn-compact"><i class="fas fa-edit"></i> Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
