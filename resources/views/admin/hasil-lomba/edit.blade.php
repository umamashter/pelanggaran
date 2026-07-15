@extends('layouts.main')
@section('title', 'Edit Hasil Lomba')
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
</style>
<div class="master-siswa-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-edit"></i></div>
                <div><h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Edit Hasil Lomba</h4></div>
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
            <form action="{{ route('hasil-lomba.update', $hasilLomba->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Lomba</label>
                    <input type="text" class="form-control" value="{{ $hasilLomba->lomba->nama ?? '-' }}" readonly>
                    <input type="hidden" name="lomba_id" value="{{ $hasilLomba->lomba_id }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipe</label>
                    <div>
                        @if($pl->isIndividu())
                            <span class="badge-tipe-ms individu"><i class="fas fa-user"></i> Individu</span>
                        @else
                            <span class="badge-tipe-ms kelompok"><i class="fas fa-users"></i> Kelompok</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Peserta</label>
                    <input type="text" class="form-control" value="{{ $pl->isIndividu() ? ($pl->student->user->name ?? $pl->student->nama ?? '-') : ($pl->kelompokLomba->nama_kelompok ?? '-') }}" readonly>
                    <input type="hidden" name="peserta_lomba_id" value="{{ $hasilLomba->peserta_lomba_id }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Nilai</label>
                    <input type="text" class="form-control" value="{{ $hasilLomba->total_nilai }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Peringkat</label>
                    <input type="text" class="form-control" value="{{ $hasilLomba->peringkat }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Juara</label>
                    <input type="text" class="form-control" value="{{ $hasilLomba->juara }}" readonly>
                </div>
                <div class="form-actions-cu" style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:24px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <a href="{{ $backUrl }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-header-ms btn-simpan-ms btn-compact"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection